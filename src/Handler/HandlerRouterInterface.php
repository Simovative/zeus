<?php

declare(strict_types=1);

namespace Simovative\Zeus\Command;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @author tp
 */
interface HandlerRouterInterface
{
    
    /**
     * @author tp
     * @param ServerRequestInterface $request
     * @return RequestHandlerInterface|null
     */
    public function route(ServerRequestInterface $request): ?RequestHandlerInterface;
}
