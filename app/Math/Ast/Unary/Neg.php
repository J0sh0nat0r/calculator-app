<?php

declare(strict_types=1);

namespace App\Math\Ast\Unary;

use App\Math\Ast\Numeric;
use App\Math\Ast\UnaryExpr;
use App\Math\TokenType;

final readonly class Neg extends UnaryExpr
{
    public static function token(): TokenType
    {
        return TokenType::Sub;
    }

    public function __toString(): string
    {
        if ($this->operand instanceof Numeric) {
            return "-$this->operand";
        }

        return parent::__toString();
    }
}
