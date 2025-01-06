<?php

declare(strict_types=1);

use App\Math\Ast\Numeric;

function c(int|string $value): Numeric
{
    return new Numeric((string) $value);
}
