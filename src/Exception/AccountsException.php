<?php

namespace SIMDE\Ginger\Exception;

use RuntimeException;

final class AccountsException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 500);
    }
}
