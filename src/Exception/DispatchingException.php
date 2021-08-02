<?php
declare(strict_types=1);

namespace Simovative\Zeus\Exception;

use Exception;

class DispatchingException extends Exception
{
    public static function createForPopulationError(): DispatchingException
    {
        return new self('Error messaged can not be populated to the content!');
    }
}
