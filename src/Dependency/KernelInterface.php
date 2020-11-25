<?php

declare(strict_types=1);

namespace Simovative\Zeus\Dependency;

use Psr\Http\Message\ResponseInterface;
use Simovative\Zeus\Http\Request\HttpRequestInterface;
use Simovative\Zeus\Http\Response\HttpResponseInterface;

/**
 * @author mnoerenberg
 */
interface KernelInterface
{
    
    /**
     * Handles exceptions and errors.
     * Called by exception handler.
     *
     * @author mnoerenberg
     * @param \Exception|\Throwable $throwable
     * @param HttpRequestInterface|null $request
     * @return HttpResponseInterface|null
     */
    public function report($throwable, HttpRequestInterface $request = null);
    
    /**
     * @author shartmann
     * @param HttpRequestInterface $request
     * @return HttpResponseInterface|ResponseInterface
     */
    public function run(HttpRequestInterface $request);
    
}
