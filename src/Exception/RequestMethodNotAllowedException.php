<?php

declare(strict_types=1);

namespace Simovative\Zeus\Exception;

class RequestMethodNotAllowedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('request method not allowed.', 405);
    }
}
