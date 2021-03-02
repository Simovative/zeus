<?php

declare(strict_types=1);

namespace Simovative\Zeus\Command;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Simovative\Zeus\Exception\RouteNotFoundException;
use Simovative\Zeus\Http\Request\HttpRequestInterface;

/**
 * @author tp
 */
class HandlerDispatcher implements HandlerDispatcherInterface
{
    
    /**
     * @var HandlerRouterChainInterface
     */
    private $routerChain;
    
    /**
     * @author tp
     * @param HandlerRouterChainInterface $routerChain
     */
    public function __construct(
        HandlerRouterChainInterface $routerChain
    ) {
        $this->routerChain = $routerChain;
    }
    
    /**
     * @inheritdoc
     * @author tp
     * @throws RouteNotFoundException
     */
    public function dispatch(HttpRequestInterface $request): ResponseInterface
    {
        $serverRequest = ServerRequest::fromGlobals();
        $serverRequest = $serverRequest->withUri($request->getUrl());
        $handler = $this->routerChain->route($serverRequest);
        if (null === $handler) {
            throw new RouteNotFoundException($request->getUrl());
        }
        $serverRequest = $serverRequest->withParsedBody($request->getParsedBody());
        $serverRequest = $serverRequest->withAttribute(
            HandlerRouteParameterMapInterface::class,
            $this->createRouteParameterMap($serverRequest->getUri())
        );
        return $handler->handle($serverRequest);
    }
    
    /**
     * @author tpawlow
     * @param UriInterface $uri
     * @return HandlerRouteParameterMapInterface
     */
    private function createRouteParameterMap(UriInterface $uri): HandlerRouteParameterMapInterface {
        $routeParameters = explode('/', $uri->getPath());
        return new HandlerRouteParameterMap($routeParameters);
    }
}
