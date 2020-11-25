<?php

declare(strict_types=1);

namespace Simovative\Zeus\Emitter\Exception;

use Exception;

final class OutputAlreadySentException extends Exception implements EmitterExceptionInterface
{
    /**
     * @return OutputAlreadySentException
     */
    public static function forOutput(): OutputAlreadySentException
    {
        return new static('Output already sent.');
    }
}
