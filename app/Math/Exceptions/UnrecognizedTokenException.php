<?php

declare(strict_types=1);

namespace App\Math\Exceptions;

use Exception;

class UnrecognizedTokenException extends Exception
{
    public function __construct(public readonly string $token)
    {
        parent::__construct("Invalid token: $token");
    }
}
