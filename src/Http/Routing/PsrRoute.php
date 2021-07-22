<?php
declare(strict_types=1);

namespace Simovative\Zeus\Http\Routing;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PsrRoute implements RouteInterface
{
    /**
     * @var RequestHandlerInterface
     */
    private $handler;

    /**
     * @var ServerRequestInterface
     */
    private $routedRequest;

    public function __construct(RequestHandlerInterface $handler, ServerRequestInterface $routedRequest)
    {
        $this->handler = $handler;
        $this->routedRequest = $routedRequest;
    }

    public function isGetRoute(): bool
    {
        return false;
    }

    public function isCommandRoute(): bool
    {
        return false;
    }

    public function isPsrRoute(): bool
    {
        return true;
    }

    public function getRoutedRequest(): ServerRequestInterface
    {
        return $this->routedRequest;
    }

    public function getHandler(): RequestHandlerInterface
    {
        return $this->handler;
    }
}
