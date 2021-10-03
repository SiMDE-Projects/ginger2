<?php

namespace App\Exception;

use RuntimeException;
use Throwable;

final class ForbiddenException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 403, null);
    }
}