<?php

declare(strict_types=1);

namespace Simovative\Zeus\Http;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Simovative\Zeus\Bundle\BundleHandlerInterface;
use Simovative\Zeus\Bundle\BundleInterface;
use Simovative\Zeus\Dependency\FrameworkFactory;
use Simovative\Zeus\Dependency\KernelInterface;
use Simovative\Zeus\Dependency\MasterFactory;
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
     * @var BundleInterface[]
     */
    protected $bundles;

    /**
     * @param MasterFactory $masterFactory
     * @author mnoerenberg
     */
    public function __construct(MasterFactory $masterFactory)
    {
        $this->masterFactory = $masterFactory;
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
            $this->bundles = $this->registerBundles($request);
            foreach ($this->bundles as $bundle) {
                $bundle->registerFactories($this->getMasterFactory());
            }

            foreach ($this->bundles as $bundle) {
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
                if ($request->isOption()) {
                    /** @noinspection NestedPositiveIfStatementsInspection */
                    if ($bundle instanceof BundleHandlerInterface) {
                        $bundle->registerOptionsHandlerRouters($this->getMasterFactory()->getHandlerRouterChain());
                    }
                }
            }

            $locator = $this->getMasterFactory()->createHttpRequestDispatcherLocator();
            $router = $this->getMasterFactory()->createRouter();
            $psrRequest = $this->createPsrRequestFromZeusRequest($request);
            $route = $router->route($request, $psrRequest);

            $dispatcher = $locator->getDispatcherFor($route);
            $response = $dispatcher->dispatch($route);
            if ($this->getApplicationState() !== null) {
                $this->getApplicationState()->commit();
            }
            if ($send) {
                if ($route->isPsrRoute()) {
                    $this->getMasterFactory()->createEmitter()->emit($response);
                } else {
                    $response->send();
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
            // Cast is needed, even if your ide tells you it is not!
            $message .= ' - Request-Url: ' . (string)$request->getUrl();
        }
        error_log($message, self::LOG_TO_SAPI);
        return $message;
    }

    /**
     * @return MasterFactory|FrameworkFactory
     * @author shartmann
     */
    protected function getMasterFactory()
    {
        return $this->masterFactory;
    }

    /**
     * Returns installed Bundles.
     *
     * @param HttpRequestInterface $request
     * @return BundleInterface[]
     * @author mnoerenberg
     */
    abstract protected function registerBundles(HttpRequestInterface $request);

    /**
     * If the application has no state, just return null.
     *
     * @return ApplicationStateInterface|null
     * @author Benedikt Schaller
     */
    abstract protected function getApplicationState();

    private function createPsrRequestFromZeusRequest(HttpRequestInterface $request): ServerRequestInterface
    {
        $serverRequestFactory = $this->getMasterFactory()->createServerRequestFactory();
        return $serverRequestFactory->createFromZeusRequest($request);
    }
}
