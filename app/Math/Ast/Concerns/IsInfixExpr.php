<?php

declare(strict_types=1);

namespace App\Math\Ast\Concerns;

use App\Math\Attributes\InfixBindingPower;
use App\Math\TokenType;

trait IsInfixExpr
{
    abstract public static function token(): TokenType;

    public static function getLeftBindingPower(): ?int
    {
        return self::getBindingPower()?->left;
    }

    public static function getRightBindingPower(): ?int
    {
        return self::getBindingPower()?->right;
    }

    private static function getBindingPower(): ?InfixBindingPower
    {
        return static::token()->getInfixBindingPower();
    }
}
