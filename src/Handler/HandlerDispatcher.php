<?php

declare(strict_types=1);

namespace Simovative\Zeus\Command;

use Psr\Http\Message\ResponseInterface;
use Simovative\Zeus\Http\Request\HttpRequestDispatcherInterface;
use Simovative\Zeus\Http\Routing\RouteInterface;

/**
 * @author tp
 */
class HandlerDispatcher implements HttpRequestDispatcherInterface
{
    public function dispatch(RouteInterface $route): ResponseInterface
    {
        $serverRequest = $route->getRoutedRequest();
        $handler = $route->getHandler();
        return $handler->handle($serverRequest);
    }
}
