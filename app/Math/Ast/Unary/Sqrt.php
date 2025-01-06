<?php

declare(strict_types=1);

namespace App\Math\Ast\Unary;

use App\Math\Ast\Numeric;
use App\Math\Ast\UnaryExpr;
use App\Math\TokenType;

final readonly class Sqrt extends UnaryExpr
{
    public static function token(): TokenType
    {
        return TokenType::Sqrt;
    }

    protected function shouldFenceOperand(): bool
    {
        return ! $this->operand instanceof Numeric || parent::shouldFenceOperand();
    }
}
