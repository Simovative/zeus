<?php
declare(strict_types=1);

namespace Simovative\Zeus\Http\Routing;

use Psr\Http\Message\ServerRequestInterface;
use Simovative\Zeus\Http\Request\HttpRequestInterface;

interface HttpRouterInterface {

    public function route(HttpRequestInterface $request, ServerRequestInterface $psrRequest): ?RouteInterface;
}
