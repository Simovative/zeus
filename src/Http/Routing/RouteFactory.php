<?php
declare(strict_types=1);

namespace Simovative\Zeus\Http\Routing;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Simovative\Zeus\Command\CommandBuilderInterface;
use Simovative\Zeus\Http\Request\HttpRequestInterface;

class RouteFactory implements RouteFactoryInterface
{

    public function createPsrRoute(ServerRequestInterface $request, RequestHandlerInterface $handler): PsrRoute
    {
        return new PsrRoute($handler, $request);
    }

    public function createCommandRoute(
        HttpRequestInterface $request,
        CommandBuilderInterface $commandBuilder
    ): CommandRoute {
        return new CommandRoute($commandBuilder, $request);
    }

    /**
     * @inheritDoc
     */
    public function createGetRoute(HttpRequestInterface $request, $content): GetRoute
    {
        return new GetRoute($content, $request);
    }
}
