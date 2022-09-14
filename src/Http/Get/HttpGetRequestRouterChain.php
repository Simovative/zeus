<?php

namespace Simovative\Zeus\Http\Get;

use Simovative\Zeus\Content\Content;
use Simovative\Zeus\Http\Request\HttpRequestInterface;

class HttpGetRequestRouterChain
{

    /**
     * @var HttpGetRequestRouterInterface[]
     */
    private array $routers = [];

    public function register(HttpGetRequestRouterInterface $router): void
    {
        $this->routers[] = $router;
    }

    /**
     * @author mnoerenberg
     * @author shartmann
     * @param HttpRequestInterface $request
     * @return Content|null
     */
    public function route(HttpRequestInterface $request)
    {
        foreach ($this->routers as $router) {
            $result = $router->route($request);
            if ($result !== null) {
                return $result;
            }
        }
        return null;
    }
}
