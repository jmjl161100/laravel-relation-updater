<?php

namespace Jmjl161100\RelationUpdater\Exceptions;

use Exception;
use Throwable;

class RelationUpdateException extends Exception
{
    public function __construct($message = '', $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
