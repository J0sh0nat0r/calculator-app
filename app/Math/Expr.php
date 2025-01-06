<?php

declare(strict_types=1);

namespace App\Math;

use JsonSerializable;
use Stringable;

abstract readonly class Expr implements JsonSerializable, Stringable
{
    public static function getLeftBindingPower(): ?int
    {
        return null;
    }

    public static function getRightBindingPower(): ?int
    {
        return null;
    }
}
