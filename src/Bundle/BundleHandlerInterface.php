<?php

declare(strict_types=1);

namespace Simovative\Zeus\Bundle;

use Simovative\Zeus\Command\HandlerRouterChainInterface;

/**
 * @author tp
 */
interface BundleHandlerInterface
{
    
    /**
     * @author tp
     * @param HandlerRouterChainInterface $routerChain
     * @return void
     */
    public function registerGetHandlerRouters(HandlerRouterChainInterface $routerChain): void;
    
    /**
     * @author tp
     * @param HandlerRouterChainInterface $routerChain
     * @return void
     */
    public function registerPostHandlerRouters(HandlerRouterChainInterface $routerChain): void;
    
    /**
     * @author tp
     * @param HandlerRouterChainInterface $routerChain
     * @return void
     */
    public function registerPatchHandlerRouters(HandlerRouterChainInterface $routerChain): void;
    
    /**
     * @author tp
     * @param HandlerRouterChainInterface $routerChain
     * @return void
     */
    public function registerPutHandlerRouters(HandlerRouterChainInterface $routerChain): void;
    
    /**
     * @author tp
     * @param HandlerRouterChainInterface $routerChain
     * @return void
     */
    public function registerDeleteHandlerRouters(HandlerRouterChainInterface $routerChain): void;
}