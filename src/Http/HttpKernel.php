<?php

declare(strict_types=1);

namespace Simovative\Zeus\Http;

use Exception;
use Simovative\Zeus\Bundle\BundleHandlerInterface;
use Simovative\Zeus\Bundle\BundleInterface;
use Simovative\Zeus\Dependency\FrameworkFactory;
use Simovative\Zeus\Dependency\KernelInterface;
use Simovative\Zeus\Dependency\MasterFactory;
use Simovative\Zeus\Exception\ExceptionHandler;
use Simovative\Zeus\Exception\RouteNotFoundException;
use Simovative\Zeus\Http\Request\HttpRequestInterface;
use Simovative\Zeus\Http\Response\HttpResponseInterface;
use Simovative\Zeus\State\ApplicationStateInterface;

/**
 * @author mnoerenberg
 */
abstract class HttpKernel implements KernelInterface
{

    private const LOG_TO_SAPI = 4;

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
    public function __construct(MasterFactory $masterFactory)
    {
        $this->masterFactory = $masterFactory;
        $this->initializeExceptionHandler();
    }

    /**
     * @author shartmann
     * @return void
     */
    protected function initializeExceptionHandler(): void
    {
        $this->exceptionHandler = $this->getMasterFactory()->createExceptionHandler($this);
        $this->exceptionHandler->register();
    }

    /**
     * Entry point of the application.
     *
     * @author mnoerenberg
     * @inheritdoc
     */
    public function run(HttpRequestInterface $request, $send = true)
    {
        try {
            $this->exceptionHandler = $this->getMasterFactory()->createExceptionHandler($this);
            $this->exceptionHandler->register();
            $this->bundles = $this->registerBundles($request);
            foreach ($this->bundles as $bundle) {
                $bundle->registerFactories($this->getMasterFactory());
            }

            foreach ($this->bundles as $index => $bundle) {
                if ($request->isGet()) {
                    $bundle->registerGetRouters($this->getMasterFactory()->getHttpGetRequestRouterChain());
                    if ($bundle instanceof BundleHandlerInterface) {
                        $bundle->registerGetHandlerRouters($this->getMasterFactory()->getHandlerRouterChain());
                    }
                }

                if ($request->isPost()) {
                    $bundle->registerPostRouters($this->getMasterFactory()->getCommandRequestRouterChain());
                    $bundle->registerPostController($this->getMasterFactory()->getApplicationController());
                    $bundle->registerBundleController($this->getMasterFactory()->getApplicationController());
                    if ($bundle instanceof BundleHandlerInterface) {
                        $bundle->registerPostHandlerRouters($this->getMasterFactory()->getHandlerRouterChain());
                    }
                }

                if ($request->isPatch()) {
                    $bundle->registerPatchRouters($this->getMasterFactory()->getCommandRequestRouterChain());
                    $bundle->registerPatchController($this->getMasterFactory()->getApplicationController());
                    $bundle->registerBundleController($this->getMasterFactory()->getApplicationController());
                    if ($bundle instanceof BundleHandlerInterface) {
                        $bundle->registerPatchHandlerRouters($this->getMasterFactory()->getHandlerRouterChain());
                    }
                }

                if ($request->isPut()) {
                    $bundle->registerPutRouters($this->getMasterFactory()->getCommandRequestRouterChain());
                    $bundle->registerPutController($this->getMasterFactory()->getApplicationController());
                    $bundle->registerBundleController($this->getMasterFactory()->getApplicationController());
                    if ($bundle instanceof BundleHandlerInterface) {
                        $bundle->registerPutHandlerRouters($this->getMasterFactory()->getHandlerRouterChain());
                    }
                }

                if ($request->isDelete()) {
                    $bundle->registerDeleteRouters($this->getMasterFactory()->getCommandRequestRouterChain());
                    $bundle->registerDeleteController($this->getMasterFactory()->getApplicationController());
                    $bundle->registerBundleController($this->getMasterFactory()->getApplicationController());
                    if ($bundle instanceof BundleHandlerInterface) {
                        $bundle->registerDeleteHandlerRouters($this->getMasterFactory()->getHandlerRouterChain());
                    }
                }

                if ($request->isHead()) {
                    $bundle->registerHeadRouters($this->getMasterFactory()->getHttpGetRequestRouterChain());
                }
            }

            $locator = $this->getMasterFactory()->createHttpRequestDispatcherLocator();
            $dispatcher = $locator->getDispatcherFor($request);
            try {
                $content = $dispatcher->dispatch($request);
                $response = $this->getMasterFactory()->getHttpResponseLocator()->getResponseFor($content);
                if ($this->getApplicationState() !== null) {
                    $this->getApplicationState()->commit();
                }
                if ($send) {
                    $response->send();
                }
            } catch (RouteNotFoundException $routeNotFoundException) {
                $handlerDispatcher = $this->getMasterFactory()->createHandlerDispatcher();
                $response = $handlerDispatcher->dispatch($request);
                if ($send) {
                    $this->getMasterFactory()->createEmitter()->emit($response);
                }
            }
        } catch (Exception $throwable) {
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
    public function report($throwable, HttpRequestInterface $request = null)
    {
        $message = sprintf(
            'Error %s on line "%s" in file "%s": %s',
            $throwable->getCode(),
            $throwable->getLine(),
            $throwable->getFile(),
            $throwable->getMessage()
        );
        if ($request !== null) {
            $message .= ' - Request-Url: ' . (string)$request->getUrl();
        }
        error_log($message, self::LOG_TO_SAPI);
        return $message;
    }

    /**
     * @author shartmann
     * @return MasterFactory|FrameworkFactory
     */
    protected function getMasterFactory()
    {
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
