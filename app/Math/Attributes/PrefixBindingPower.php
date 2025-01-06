<?php

declare(strict_types=1);

namespace App\Math\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
final readonly class PrefixBindingPower
{
    public function __construct(public int $power) {}
}
