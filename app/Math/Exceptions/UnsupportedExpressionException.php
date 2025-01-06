<?php

declare(strict_types=1);

namespace App\Math\Exceptions;

use App\Math\Expr;

class UnsupportedExpressionException extends \RuntimeException
{
    /**
     * @param  class-string<Expr>  $type
     */
    public function __construct(string $type)
    {
        parent::__construct("Unsupported expression of type: $type");
    }
}
