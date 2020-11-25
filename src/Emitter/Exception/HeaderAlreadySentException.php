<?php

declare(strict_types=1);

namespace Simovative\Zeus\Emitter\Exception;

use Exception;

/**
 * @author mnoernberg
 */
final class HeaderAlreadySentException extends Exception implements EmitterExceptionInterface
{
    /**
     * @author mnoernberg
     * @return HeaderAlreadySentException
     */
    public static function forHeader(): HeaderAlreadySentException
    {
        return new static('Header already sent.');
    }
}
