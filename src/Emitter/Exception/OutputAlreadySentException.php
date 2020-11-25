<?php

declare(strict_types=1);

namespace Simovative\Zeus\Emitter\Exception;

use Exception;

/**
 * @author mnoernberg
 */
final class OutputAlreadySentException extends Exception implements EmitterExceptionInterface
{
    /**
     * @author mnoernberg
     * @return OutputAlreadySentException
     */
    public static function forOutput(): OutputAlreadySentException
    {
        return new static('Output already sent.');
    }
}
