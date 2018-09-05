<?php
namespace Simovative\Zeus\Http;

use Simovative\Zeus\Bundle\BundleInterface;
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
	 * @var BundleInterface[]
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
			$this->exceptionHandler = $this->getMasterFactory()->createExceptionHandler($this);
			$this->exceptionHandler->registerExceptionHandler();
			$this->bundles = $this->registerBundles($request);
			foreach ($this->bundles as $bundle) {
				$bundle->registerFactories($this->getMasterFactory());
			}
			
			foreach ($this->bundles as $index => $bundle) {
				if ($request->isGet()) {
					$bundle->registerGetRouters($this->getMasterFactory()->getHttpGetRequestRouterChain());
				}
				if ($request->isPost()) {
					$bundle->registerPostRouters($this->getMasterFactory()->getCommandRequestRouterChain());
					$bundle->registerPostController($this->getMasterFactory()->getApplicationController());
					$bundle->registerBundleController($this->getMasterFactory()->getApplicationController());
				}
				if ($request->isPatch()) {
					$bundle->registerPatchRouters($this->getMasterFactory()->getCommandRequestRouterChain());
					$bundle->registerPatchController($this->getMasterFactory()->getApplicationController());
					$bundle->registerBundleController($this->getMasterFactory()->getApplicationController());
				}
				if ($request->isPut()) {
					$bundle->registerPutRouters($this->getMasterFactory()->getCommandRequestRouterChain());
					$bundle->registerPutController($this->getMasterFactory()->getApplicationController());
					$bundle->registerBundleController($this->getMasterFactory()->getApplicationController());
				}
				if ($request->isDelete()) {
					$bundle->registerDeleteRouters($this->getMasterFactory()->getCommandRequestRouterChain());
					$bundle->registerDeleteController($this->getMasterFactory()->getApplicationController());
					$bundle->registerBundleController($this->getMasterFactory()->getApplicationController());
				}
			}
		
			$locator = $this->getMasterFactory()->createHttpRequestDispatcherLocator();
			$dispatcher = $locator->getDispatcherFor($request);
			$content = $dispatcher->dispatch($request);
			$response = $this->getMasterFactory()->getHttpResponseLocator()->getResponseFor($content);
			if ($this->getApplicationState() !== null) {
				$this->getApplicationState()->commit();
			}
			if ($send) {
				$response->send();
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
	 * @param HttpRequestInterface $request
	 * @return \Simovative\Zeus\Bundle\BundleInterface[]
	 */
	abstract protected function registerBundles(HttpRequestInterface $request);
	
	/**
	 * If the application has no state, just return null.
	 *
	 * @author Benedikt Schaller
	 * @return ApplicationStateInterface|null
	 */
	abstract protected function getApplicationState();
}
