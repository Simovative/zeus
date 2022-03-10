<?php

declare(strict_types=1);

namespace Simovative\Zeus\RequestHandler\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Simovative\Zeus\RequestHandler\Exception\MiddlewareExhaustedException;
use SplQueue;

final class Pipeline implements PipelineInterface
{
    /**
     * @var SplQueue
     */
    private SplQueue $queue;

    public function __construct()
    {
        $this->queue = new SplQueue();
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->queue->isEmpty()) {
            throw MiddlewareExhaustedException::fromExhausted();
        }

        return $this->getNext()->process($request, $this);
    }

    /**
     * @param MiddlewareInterface $middleware
     *
     * @return PipelineInterface
     */
    public function pipe(
        MiddlewareInterface $middleware
    ): PipelineInterface {
        $this->queue->enqueue($middleware);

        return $this;
    }

    public function count(): int
    {
        return $this->queue->count();
    }

    private function getNext(): MiddlewareInterface
    {
        return $this->queue->dequeue();
    }
}
