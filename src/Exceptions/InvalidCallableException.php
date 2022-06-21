<?php

declare(strict_types=1);

namespace Ari\WpHook\Exceptions;

use Exception;
use Throwable;

class InvalidCallableException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: 'Callable must be and array of format [$className, $method]';
        parent::__construct($message, $code, $previous);
    }
}
