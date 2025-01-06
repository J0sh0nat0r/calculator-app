<?php

declare(strict_types=1);

namespace App\Math\Contracts;

use App\Math\Expr;

interface Calculator
{
    public function evaluate(Expr $expr): string;
}
