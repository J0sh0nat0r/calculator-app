<?php

declare(strict_types=1);

namespace App\Math\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
final readonly class InfixBindingPower
{
    public function __construct(public int $left, public int $right) {}
}
