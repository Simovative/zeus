<?php

declare(strict_types=1);

namespace Simovative\Zeus\Handler;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Simovative\Zeus\Command\HandlerRouterChainInterface;
use Simovative\Zeus\Command\HandlerRouterInterface;
use Simovative\Zeus\Exception\RouteNotFoundException;
use Simovative\Zeus\Http\Url\Url;

/**
 * @author tp
 */
class HandlerRouterChain implements HandlerRouterChainInterface
{
    
    /**
     * @var HandlerRouterInterface[]
     */
    private $routers;
    
    /**
     * @author therion86
     */
    public function __construct() {
        $this->routers = [];
    }

    /**
     * @inheritDoc
     * @author tp
     */
    public function register(HandlerRouterInterface $router): void
    {
        $this->routers[] = $router;
    }
    
    /**
     * @inheritDoc
     * @author tp
     */
    public function route(ServerRequestInterface $request): RequestHandlerInterface
    {
        foreach ($this->routers as $router) {
            $result = $router->route($request);
            if ($result !== null) {
                return $result;
            }
        }
        
        throw new RouteNotFoundException(new Url($request->getUri()->getPath()));
    }
}
