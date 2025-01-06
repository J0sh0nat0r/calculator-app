<?php

declare(strict_types=1);

namespace App\Math\Ast\Binary;

use App\Math\Ast\BinaryExpr;
use App\Math\TokenType;

final readonly class Div extends BinaryExpr
{
    public static function token(): TokenType
    {
        return TokenType::Div;
    }
}
