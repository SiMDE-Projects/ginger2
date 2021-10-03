<?php

namespace App\Exception;

use RuntimeException;
use Throwable;

final class ApplicationNotFoundException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 404, null);
    }
}
