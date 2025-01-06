<?php

declare(strict_types=1);

namespace App\Math\Ast\Concerns;

use App\Math\TokenType;

trait IsPrefixExpr
{
    abstract public static function token(): TokenType;

    public static function getLeftBindingPower(): int
    {
        return static::token()->getPrefixBindingPower();
    }

    public static function getRightBindingPower(): int
    {
        return static::token()->getPrefixBindingPower();
    }
}
