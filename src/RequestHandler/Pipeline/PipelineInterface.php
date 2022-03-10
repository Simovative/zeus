<?php

declare(strict_types=1);

namespace Simovative\Zeus\RequestHandler\Pipeline;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface PipelineInterface extends RequestHandlerInterface
{
    /**
     * @param MiddlewareInterface $middleware
     *
     * @return PipelineInterface
     */
    public function pipe(
        MiddlewareInterface $middleware
    ): PipelineInterface;

    /**
     * @return int
     */
    public function count(): int;
}
