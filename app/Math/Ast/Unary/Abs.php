<?php

declare(strict_types=1);

namespace App\Math\Ast\Unary;

use App\Math\Ast\UnaryExpr;
use App\Math\TokenType;

final readonly class Abs extends UnaryExpr
{
    public static function token(): TokenType
    {
        return TokenType::Add;
    }
}
