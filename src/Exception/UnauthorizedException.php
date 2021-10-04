<?php

namespace SIMDE\Ginger\Exception;

use RuntimeException;
use Throwable;

final class UnauthorizedException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 401, null);
    }
}
