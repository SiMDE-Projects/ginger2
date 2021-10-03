<?php

namespace App\Exception;

use RuntimeException;
use Throwable;

final class AccountsException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 500, null);
    }
}
