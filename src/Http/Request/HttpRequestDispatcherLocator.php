<?php

namespace Simovative\Zeus\Http\Request;

use LogicException;
use Simovative\Zeus\Dependency\FrameworkFactory;
use Simovative\Zeus\Http\Routing\RouteInterface;

/**
 * @author mnoerenberg
 */
class HttpRequestDispatcherLocator implements HttpRequestDispatcherLocatorInterface
{

    /**
     * @var FrameworkFactory
     */
    private $frameworkFactory;

    /**
     * @param FrameworkFactory $frameworkFactory
     * @author mnoerenberg
     */
    public function __construct(FrameworkFactory $frameworkFactory)
    {
        $this->frameworkFactory = $frameworkFactory;
    }

    public function getDispatcherFor(RouteInterface $route): HttpRequestDispatcherInterface
    {
        if ($route->isCommandRoute()) {
            return $this->frameworkFactory->createHttpCommandDispatcher();
        }
        if ($route->isGetRoute()) {
            return $this->frameworkFactory->createHttpGetRequestDispatcher();
        }
        if ($route->isPsrRoute()) {
            return $this->frameworkFactory->createHandlerDispatcher();
        }

        throw new LogicException('request method not allowed.');
    }
}
