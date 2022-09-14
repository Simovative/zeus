<?php

declare(strict_types=1);

namespace Simovative\Zeus\Bundle;

use Simovative\Zeus\Command\HandlerRouterChainInterface;

interface BundleHandlerInterface
{
    
    public function registerGetHandlerRouters(HandlerRouterChainInterface $routerChain): void;
    
    public function registerPostHandlerRouters(HandlerRouterChainInterface $routerChain): void;
    
    public function registerPatchHandlerRouters(HandlerRouterChainInterface $routerChain): void;
    
    public function registerPutHandlerRouters(HandlerRouterChainInterface $routerChain): void;
    
    public function registerDeleteHandlerRouters(HandlerRouterChainInterface $routerChain): void;

    public function registerOptionsHandlerRouters(HandlerRouterChainInterface $routerChain): void;
}