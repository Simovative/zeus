<?php

declare(strict_types=1);

namespace Simovative\Zeus\Http\Routing;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Simovative\Zeus\Command\CommandRequestRouterChain;
use Simovative\Zeus\Command\HandlerRouteParameterMap;
use Simovative\Zeus\Command\HandlerRouteParameterMapInterface;
use Simovative\Zeus\Command\HandlerRouterChainInterface;
use Simovative\Zeus\Exception\RouteNotFoundException;
use Simovative\Zeus\Http\Get\HttpGetRequestRouterChain;
use Simovative\Zeus\Http\Request\HttpRequestInterface;

class HttpRouter implements HttpRouterInterface
{
    public function __construct(
        private readonly CommandRequestRouterChain $commandRequestRouterChain,
        private readonly HttpGetRequestRouterChain $getRequestRouterChain,
        private readonly HandlerRouterChainInterface $handlerRouterChain,
        private readonly RouteFactoryInterface $routeFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function route(HttpRequestInterface $request, ServerRequestInterface $psrRequest): RouteInterface
    {
        $psrHandler = $this->handlerRouterChain->route($psrRequest);
        if ($psrHandler !== null) {
            $psrRequest = $psrRequest->withParsedBody($request->getParsedBody());
            $psrRequest = $psrRequest->withAttribute(
                HandlerRouteParameterMapInterface::class,
                $this->createRouteParameterMap($psrRequest->getUri())
            );
            return $this->routeFactory->createPsrRoute($psrRequest, $psrHandler);
        }
        if ($request->isGet() || $request->isHead() || $request->isOption()) {
            $content = $this->getRequestRouterChain->route($request);
            if ($content !== null) {
                return $this->routeFactory->createGetRoute($request, $content);
            }
        }
        $commandBuilder = $this->commandRequestRouterChain->route($request);
        if ($commandBuilder !== null) {
            return $this->routeFactory->createCommandRoute($request, $commandBuilder);
        }

        throw new RouteNotFoundException($request->getUrl());
    }

    /**
     * @param UriInterface $uri
     * @return HandlerRouteParameterMapInterface
     * @author tpawlow
     */
    private function createRouteParameterMap(UriInterface $uri): HandlerRouteParameterMapInterface
    {
        $routeParameters = explode('/', $uri->getPath());
        return new HandlerRouteParameterMap($routeParameters);
    }
}
