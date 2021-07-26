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
    /**
     * @var CommandRequestRouterChain
     */
    private $commandRequestRouterChain;
    /**
     * @var HttpGetRequestRouterChain
     */
    private $getRequestRouterChain;
    /**
     * @var HandlerRouterChainInterface
     */
    private $handlerRouterChain;
    /**
     * @var RouteFactoryInterface
     */
    private $routeFactory;

    public function __construct(
        CommandRequestRouterChain $commandRequestRouterChain,
        HttpGetRequestRouterChain $getRequestRouterChain,
        HandlerRouterChainInterface $handlerRouterChain,
        RouteFactoryInterface $routeFactory
    ) {
        $this->commandRequestRouterChain = $commandRequestRouterChain;
        $this->getRequestRouterChain = $getRequestRouterChain;
        $this->handlerRouterChain = $handlerRouterChain;
        $this->routeFactory = $routeFactory;
    }

    /**
     * @inheritDoc
     */
    public function route(HttpRequestInterface $request, ServerRequestInterface $psrRequest): RouteInterface
    {
        if ($request->isGet() || $request->isHead()) {
            $content = $this->getRequestRouterChain->route($request);
            if ($content !== null) {
                return $this->routeFactory->createGetRoute($request, $content);
            }
        }
        $commandBuilder = $this->commandRequestRouterChain->route($request);
        if ($commandBuilder !== null) {
            return $this->routeFactory->createCommandRoute($request, $commandBuilder);
        }
        $psrHandler = $this->handlerRouterChain->route($psrRequest);
        if ($psrHandler !== null) {
            $psrRequest = $psrRequest->withParsedBody($request->getParsedBody());
            $psrRequest = $psrRequest->withAttribute(
                HandlerRouteParameterMapInterface::class,
                $this->createRouteParameterMap($psrRequest->getUri())
            );
            return $this->routeFactory->createPsrRoute($psrRequest, $psrHandler);
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
