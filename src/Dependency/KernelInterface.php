<?php

declare(strict_types=1);

namespace Simovative\Zeus\Dependency;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface KernelInterface
{
    public function run(ServerRequestInterface $request, bool $send = false): ?ResponseInterface;
}
