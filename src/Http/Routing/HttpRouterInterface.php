<?php
declare(strict_types=1);

namespace Simovative\Zeus\Http\Routing;

use Psr\Http\Message\ServerRequestInterface;
use Simovative\Zeus\Exception\IncompleteSetupException;
use Simovative\Zeus\Exception\RouteNotFoundException;
use Simovative\Zeus\Http\Request\HttpRequestInterface;

interface HttpRouterInterface {

    /**
     * @throws RouteNotFoundException
     * @throws IncompleteSetupException
     */
    public function route(HttpRequestInterface $request, ServerRequestInterface $psrRequest): RouteInterface;
}
