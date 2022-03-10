<?php

declare(strict_types=1);

namespace Simovative\Zeus\RequestHandler\Exception;

use Exception;

final class MiddlewareExhaustedException extends Exception implements RequestHandlerExceptionInterface
{
    public static function fromExhausted(): MiddlewareExhaustedException
    {
        return new self('Middleware stack exhausted.');
    }
}
