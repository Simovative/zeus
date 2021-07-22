<?php
declare(strict_types=1);

namespace Simovative\Zeus\Http\Routing;

use Psr\Http\Message\ServerRequestInterface;
use Simovative\Zeus\Command\CommandBuilderInterface;
use Simovative\Zeus\Http\Request\HttpRequestInterface;

class CommandRoute implements RouteInterface
{
    /**
     * @var ServerRequestInterface
     */
    private $routedRequest;

    /**
     * @var CommandBuilderInterface
     */
    private $commandBuilder;

    public function __construct(CommandBuilderInterface $commandBuilder, HttpRequestInterface $routedRequest)
    {
        $this->routedRequest = $routedRequest;
        $this->commandBuilder = $commandBuilder;
    }

    public function isGetRoute(): bool
    {
        return false;
    }

    public function isCommandRoute(): bool
    {
        return true;
    }

    public function isPsrRoute(): bool
    {
        return false;
    }

    public function getRoutedRequest(): HttpRequestInterface
    {
        return $this->routedRequest;
    }

    public function getHandler(): CommandBuilderInterface
    {
        return $this->commandBuilder;
    }
}
