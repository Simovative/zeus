<?php
declare(strict_types=1);

namespace Simovative\Zeus\Http\Routing;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Simovative\Zeus\Command\CommandBuilderInterface;
use Simovative\Zeus\Content\Content;
use Simovative\Zeus\Http\Request\HttpRequestInterface;
use Simovative\Zeus\Http\Response\HttpResponseInterface;

interface RouteFactoryInterface
{
    public function createPsrRoute(ServerRequestInterface $request, RequestHandlerInterface $handler): PsrRoute;

    public function createCommandRoute(HttpRequestInterface $request, CommandBuilderInterface $commandBuilder): CommandRoute;

    /**
     * @param HttpRequestInterface $request
     * @param Content|HttpResponseInterface $content
     * @return GetRoute
     */
    public function createGetRoute(HttpRequestInterface $request, $content): GetRoute;
}
