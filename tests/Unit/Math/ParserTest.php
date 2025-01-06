<?php

declare(strict_types=1);

use App\Math\Ast\Binary\Add;
use App\Math\Ast\Binary\Div;
use App\Math\Ast\Binary\Mul;
use App\Math\Ast\Binary\Pow;
use App\Math\Ast\Binary\Sub;
use App\Math\Ast\Unary\Abs;
use App\Math\Ast\Unary\Neg;
use App\Math\Ast\Unary\Sqrt;
use App\Math\Exceptions\ParserException;
use App\Math\Expr;
use App\Math\Lexer;
use App\Math\Parser;

mutates(Parser::class);

test('simple expressions', function (string $rawExpr, Expr $expected): void {
    $expr = parse($rawExpr);

    expect($expr)->toEqual($expected);
})->with([
    ['40 + 2', new Add(c(40), c(2))],
    ['21 / 3', new Div(c(21), c(3))],
    ['16 * 8', new Mul(c(16), c(8))],
    ['120^89', new Pow(c(120), c(89))],
    ['70 - 6', new Sub(c(70), c(6))],
    ['+11440', new Abs(c(11440))],
    ['-12870', new Neg(c(12870))],
    ['sqrt 9', new Sqrt(c(9))],
]);

it('parses a complex expression', function (): void {
    $expr = parse('3 + 5 * -7 * 11 + 13');

    expect($expr)->toEqual(new Add(
        new Add(
            c(3),
            new Mul(
                new Mul(c(5), new Neg(c(7))),
                c(11)
            )
        ),
        c(13)
    )
    );
});

it('parses a parenthesized expression', function (): void {
    $expr = parse('(17 + 19) * 23');

    expect($expr)->toEqual(new Mul(
        new Add(
            c(17),
            c(19)
        ),
        c(23)
    )
    );
});

test('binary associativity', function (string $op, bool $left, string $node) {
    $expr = parse("3 $op 7 $op 11");

    if ($left) {
        expect($expr)->toEqual(new $node(new $node(c(3), c(7)), c(11)));
    } else {
        expect($expr)->toEqual(new $node(c(3), new $node(c(7), c(11))));
    }
})->with([
    ['+', true, Add::class],
    ['-', true, Sub::class],
    ['/', true, Div::class],
    ['*', true, Mul::class],
    ['^', false, Pow::class],
]);

describe('operator precedence', function () {
    test('neg', function () {
        $expr = parse('-5 * 7');

        expect($expr)->toEqual(new Mul(
            new Neg(c(5)),
            c(7)
        ));
    });

    test('pow', function () {
        $expr = parse('2^3 / 2');

        expect($expr)->toEqual(new Div(
            new Pow(c(2), c(3)),
            c(2)
        ));
    });
});

describe('malformed input', function () {
    test('unfinished expression', fn () => parse('5 *'))
        ->throws(ParserException::class, 'unexpected EOF (expecting expression)');

    test('unclosed parenthesis', fn () => parse('(3 + 2'))
        ->throws(ParserException::class, "unexpected EOF (expecting ')'");

    test('bad parenthesis', fn () => parse('(2 - 3('))
        ->throws(ParserException::class, "unexpected token: '(' (expecting ')'");

    test('unexpected token', fn () => parse('10 / /'))
        ->throws(ParserException::class, "unexpected token: '/'");

    test('bad sequence', fn () => parse('3 3'))
        ->throws(ParserException::class, "unexpected token: '3'");

    test('dangling input', fn () => parse('sqrt(16) 2'))
        ->throws(ParserException::class, "unexpected token: '2' (expecting EOF)");
});

test('fake expression round-trip', function (Expr $expr): void {
    $raw = (string) $expr;

    $parsedExpr = parse($raw);

    expect($parsedExpr)->toEqual($expr);
})->with('fake_expressions');

function parse(string $input): Expr
{
    return (new Parser(new Lexer($input)))->parse();
}
