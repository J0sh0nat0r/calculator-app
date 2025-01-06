<?php

declare(strict_types=1);

use App\Math\Expr;

test('JSON serialization', function (Expr $expr): void {
    expect(json_encode($expr, JSON_PRETTY_PRINT))->toMatchSnapshot();
})->with('fake_expressions');

test('stringification', function (Expr $expr): void {
    expect((string) $expr)->toMatchSnapshot();
})->with('fake_expressions');
