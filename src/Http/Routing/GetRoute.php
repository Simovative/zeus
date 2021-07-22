<?php
declare(strict_types=1);

namespace Simovative\Zeus\Http\Routing;

use Psr\Http\Message\ServerRequestInterface;
use Simovative\Zeus\Content\Content;
use Simovative\Zeus\Http\Request\HttpRequestInterface;
use Simovative\Zeus\Http\Response\HttpResponseInterface;

class GetRoute implements RouteInterface
{
    /**
     * @var ServerRequestInterface
     */
    private $routedRequest;

    /**
     * @var Content|HttpResponseInterface
     */
    private $content;

    /**
     * @param Content|HttpResponseInterface $content
     * @param HttpRequestInterface $routedRequest
     */
    public function __construct($content, HttpRequestInterface $routedRequest)
    {
        $this->routedRequest = $routedRequest;
        $this->content = $content;
    }

    public function isGetRoute(): bool
    {
        return true;
    }

    public function isCommandRoute(): bool
    {
        return false;
    }

    public function isPsrRoute(): bool
    {
        return false;
    }

    public function getRoutedRequest(): HttpRequestInterface
    {
        return $this->routedRequest;
    }

    /**
     * @return Content|HttpResponseInterface
     */
    public function getHandler()
    {
        return $this->content;
    }
}
