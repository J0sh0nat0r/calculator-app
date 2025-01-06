<?php

declare(strict_types=1);

use App\Math\Expr;

arch()
    ->expect('App\Math\Ast')
    ->toExtend(Expr::class)
    ->ignoring('App\Math\Ast\Concerns');

arch()
    ->expect('App\Math\Attributes')
    ->toHaveAttribute(Attribute::class);

arch()
    ->expect('App\Math\Contracts')
    ->toBeInterfaces();

arch()
    ->expect('App\Math\Exceptions')
    ->toExtend(Exception::class);

arch()
    ->expect('App\Math')
    ->not
    ->toUse('Illuminate');
