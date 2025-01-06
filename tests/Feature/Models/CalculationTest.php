<?php

declare(strict_types=1);

use App\Casts\Expr as ExprCast;
use App\Models\Calculation;

mutates(Calculation::class, ExprCast::class);

it('casts expr on set', function () {
    $expr = $this->faker->mathExpr;

    $model = new Calculation(['expr' => (string) $expr]);

    expect($model->expr)->toEqual($expr);
});
