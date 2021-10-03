<?php

namespace SIMDE\Ginger\Exception;

use RuntimeException;
use Throwable;

final class ValidationException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 400, null);
    }
}
