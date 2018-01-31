<?php
namespace Simovative\Zeus\Http;

use Simovative\Zeus\Bundle\Bundle;
use Simovative\Zeus\Dependency\FrameworkFactory;
use Simovative\Zeus\Dependency\KernelInterface;
use Simovative\Zeus\Dependency\MasterFactory;
use Simovative\Zeus\Exception\ExceptionHandler;
use Simovative\Zeus\Http\Request\HttpRequestInterface;
use Simovative\Zeus\Http\Response\HttpResponseInterface;
use Simovative\Zeus\State\ApplicationStateInterface;

/**
 * @author mnoerenberg
 */
abstract class HttpKernel implements KernelInterface {
	
	const LOG_TO_SAPI = 4;
	
	/**
	 * @var MasterFactory
	 */
	protected $masterFactory;
	
	/**
	 * @var ExceptionHandler
	 */
	protected $exceptionHandler;
	
	/**
	 * @var Bundle[]
	 */
	protected $bundles;
	
	/**
	 * @author mnoerenberg
	 * @param MasterFactory $masterFactory
	 */
	public function __construct(MasterFactory $masterFactory) {
		$this->masterFactory = $masterFactory;
		$this->initializeExceptionHandler();
	}
	
	/**
	 * @author shartmann
	 * @return void
	 */
	protected function initializeExceptionHandler() {
		$this->exceptionHandler = $this->getMasterFactory()->createExceptionHandler($this);
		$this->exceptionHandler->register();
	}
	
	/**
	 * Entry point of the application.
	 *
	 * @author mnoerenberg
	 * @inheritdoc
	 */
	public function run(HttpRequestInterface $request, $send = true) {
		try {
			$this->bundles = $this->registerBundles();
			foreach ($this->bundles as $bundle) {
				$bundle->registerFactories($this->getMasterFactory());
			}
			foreach ($this->bundles as $index => $bundle) {
				// get
				if ($request->isGet()) {
					$bundle->registerGetRouters($this->getMasterFactory()->getHttpGetRequestRouterChain());
				}
				
				// post
				if ($request->isPost()) {
					$bundle->registerPostRouters($this->getMasterFactory()->getCommandRequestRouterChain());
					$bundle->registerBundleController($this->getMasterFactory()->getApplicationController());
				}
			}
			
			$locator = $this->getMasterFactory()->createHttpRequestDispatcherLocator();
			$dispatcher = $locator->getDispatcherFor($request);
			$content = $dispatcher->dispatch($request);
			$response = $this->getMasterFactory()->getHttpResponseLocator()->getResponseFor($content);
			if ($send) {
				$response->send();
			}
			if ($this->getApplicationState() !== null) {
				$this->getApplicationState()->commit();
			}
		} catch (\Exception $throwable) {
			$response = $this->report($throwable, $request);
			if ($send && $response instanceof HttpResponseInterface) {
				$response->send();
			}
		}
		return $response;
	}
	
	/**
	 * @author Benedikt Schaller
	 * @inheritdoc
	 */
	public function report($throwable, HttpRequestInterface $request = null) {
		$message = sprintf(
			'Error %s on line "%s" in file "%s": %s',
			$throwable->getCode(),
			$throwable->getLine(),
			$throwable->getFile(),
			$throwable->getMessage()
		);
		if ($request !== null) {
			$message .= ' - Request-Url: ' . (string) $request->getUrl();
		}
		error_log($message, self::LOG_TO_SAPI);
		return $message;
	}
	
	/**
	 * @author shartmann
	 * @return MasterFactory|FrameworkFactory
	 */
	protected function getMasterFactory() {
		return $this->masterFactory;
	}
	
	/**
	 * Returns installed Bundles.
	 *
	 * @author mnoerenberg
	 * @return Bundle[]
	 */
	abstract protected function registerBundles();
	
	/**
	 * If the application has no state, just return null.
	 *
	 * @author Benedikt Schaller
	 * @return ApplicationStateInterface|null
	 */
	abstract protected function getApplicationState();
}
