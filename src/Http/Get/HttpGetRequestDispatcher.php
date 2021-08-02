<?php
declare(strict_types=1);

namespace Simovative\Zeus\Http\Get;

use Simovative\Zeus\Http\Request\HttpRequestDispatcherInterface;
use Simovative\Zeus\Http\Response\HttpResponseInterface;
use Simovative\Zeus\Http\Response\HttpResponseLocatorInterface;
use Simovative\Zeus\Http\Routing\RouteInterface;

class HttpGetRequestDispatcher implements HttpRequestDispatcherInterface {

    /**
     * @var HttpResponseLocatorInterface
     */
    private $httpResponseLocator;

    public function __construct(HttpResponseLocatorInterface $httpResponseLocator)
    {
        $this->httpResponseLocator = $httpResponseLocator;
    }
    
    public function dispatch(RouteInterface $route): HttpResponseInterface
    {
        return $this->httpResponseLocator->getResponseFor($route->getHandler());
    }
}
