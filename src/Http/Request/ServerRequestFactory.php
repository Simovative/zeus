<?php
declare(strict_types=1);

namespace Simovative\Zeus\Http\Request;

use GuzzleHttp\Psr7\ServerRequest;

class ServerRequestFactory implements ServerRequestFactoryInterface
{

    public function createFromZeusRequest(HttpRequestInterface $request): ServerRequest
    {
        return ServerRequest::fromGlobals();
        return $psrRequest->withUri($request->getUrl());
    }
}
