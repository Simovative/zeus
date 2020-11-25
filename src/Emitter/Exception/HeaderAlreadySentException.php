<?php

declare(strict_types=1);

namespace Simovative\Zeus\Emitter\Exception;

use Exception;

final class HeaderAlreadySentException extends Exception implements EmitterExceptionInterface
{
    /**
     * @return HeaderAlreadySentException
     */
    public static function forHeader(): HeaderAlreadySentException
    {
        return new static('Header already sent.');
    }
}
