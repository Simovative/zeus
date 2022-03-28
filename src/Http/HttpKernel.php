<?php

declare(strict_types=1);

namespace Simovative\Zeus\Http;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Simovative\Zeus\Bundle\BundleHandlerInterface;
use Simovative\Zeus\Bundle\BundleInterface;
use Simovative\Zeus\Dependency\KernelInterface;
use Simovative\Zeus\Dependency\MasterFactory;
use Simovative\Zeus\Emitter\EmitterInterface;
use Simovative\Zeus\Http\Request\HttpRequestInterface;
use Simovative\Zeus\Http\Response\HttpResponseInterface;

abstract class HttpKernel implements KernelInterface
{
    public const REQUEST_ATTRIBUTE_BUNDLES = '_bundles';

    private MasterFactory $masterFactory;

    public function __construct(
        MasterFactory $masterFactory
    ) {
        $this->masterFactory = $masterFactory;
    }

    public function run(ServerRequestInterface $request, bool $send = true): ?ResponseInterface
    {
        $bundles = $this->getBundles($request);
        $request = $request->withAttribute(self::REQUEST_ATTRIBUTE_BUNDLES, $bundles);

        $masterFactory = $this->registerBundleFactories($this->masterFactory, $bundles);

        $pipeline = $this->buildPipeline($masterFactory, $bundles);

        $psrResponse = $pipeline->handle($request);

        if (! $send) {
            return $psrResponse;
        }

        $this->masterFactory->createEmitter()->emit($psrResponse);
        return null;
    }

    private function registerBundleFactories(MasterFactory $masterFactory, array $bundles): MasterFactory
    {
        foreach ($bundles as $bundle) {
            $bundle->registerFactories($masterFactory);
        }

        return $masterFactory;
    }

    /**
     * @param MasterFactory $masterFactory
     * @param BundleInterface[] $bundles
     *
     * @return RequestHandlerInterface
     */
    abstract protected function buildPipeline(MasterFactory $masterFactory, array $bundles): RequestHandlerInterface;

    /**
     * @param ServerRequestInterface $request
     *
     * @return BundleInterface[]
     */
    abstract protected function getBundles(ServerRequestInterface $request): array;




    public function runOld(HttpRequestInterface $request, $send = true)
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
}
