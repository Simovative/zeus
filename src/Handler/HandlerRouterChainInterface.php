<?php

declare(strict_types=1);

namespace Simovative\Zeus\Command;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Simovative\Zeus\Exception\RouteNotFoundException;

/**
 * @author tp
 */
interface HandlerRouterChainInterface
{
    
    /**
     * @author tp
     * @param HandlerRouterInterface $router
     * @return void
     */
    public function register(HandlerRouterInterface $router): void;
    
    /**
     * @author tp
     * @param ServerRequestInterface $request
     * @return RequestHandlerInterface
     * @throws RouteNotFoundException
     */
    public function route(ServerRequestInterface $request): RequestHandlerInterface;
}
