<?php
declare(strict_types=1);

namespace Simovative\Zeus\Http\Request;

use GuzzleHttp\Psr7\ServerRequest;

interface ServerRequestFactoryInterface
{
    public function createFromZeusRequest(HttpRequestInterface $request): ServerRequest;
}
