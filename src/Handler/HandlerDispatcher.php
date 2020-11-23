<?php

declare(strict_types=1);

namespace Simovative\Zeus\Command;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
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
        $commandRequest = CommandRequest::fromHttpRequest($request);
        $serverRequest = ServerRequest::fromGlobals();
        $handler = $this->routerChain->route($serverRequest);
        if (null === $handler) {
            throw new RouteNotFoundException($request->getUrl());
        }
        $serverRequest = $serverRequest->withParsedBody($commandRequest->getBody());
        return $handler->handle($serverRequest);
    }
}
