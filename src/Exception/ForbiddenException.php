<?php

namespace SIMDE\Ginger\Exception;

use RuntimeException;

final class ForbiddenException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 403);
    }
}
