<?php

declare(strict_types=1);

namespace Simovative\Zeus\Command;

use Psr\Http\Message\ResponseInterface;
use Simovative\Zeus\Http\Request\HttpRequestInterface;

/**
 * @author tp
 */
interface HandlerDispatcherInterface
{
    
    /**
     * @author tp
     * @param HttpRequestInterface $request
     * @return ResponseInterface
     */
    public function dispatch(HttpRequestInterface $request): ResponseInterface;
}