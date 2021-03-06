<?php

declare(strict_types=1);

namespace Simovative\Zeus\Emitter;

use Psr\Http\Message\ResponseInterface;

interface EmitterInterface
{
    /**
     * @author mnoernberg
     * @param ResponseInterface $response
     *
     * @return void
     */
    public function emit(ResponseInterface $response): void;
}
