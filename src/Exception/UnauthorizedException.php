<?php

namespace SIMDE\Ginger\Exception;

use RuntimeException;

final class UnauthorizedException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 401);
    }
}
