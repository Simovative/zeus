<?php
declare(strict_types=1);

namespace Simovative\Zeus\Http\Routing;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Simovative\Zeus\Command\CommandBuilderInterface;
use Simovative\Zeus\Content\Content;
use Simovative\Zeus\Http\Request\HttpRequestInterface;
use Simovative\Zeus\Http\Response\HttpResponseInterface;

interface RouteInterface
{
    public function isGetRoute(): bool;

    public function isCommandRoute(): bool;

    public function isPsrRoute(): bool;

    /**
     * @return HttpRequestInterface|ServerRequestInterface
     */
    public function getRoutedRequest();

    /**
     * @return Content|HttpResponseInterface|CommandBuilderInterface|RequestHandlerInterface
     */
    public function getHandler();
}
