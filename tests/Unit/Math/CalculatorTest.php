<?php

declare(strict_types=1);

use App\Math\Calculator;
use App\Math\Exceptions\UnsupportedExpressionException;
use App\Math\Expr;
use App\Math\Lexer;
use App\Math\Parser;
use App\Math\TokenType;

mutates(Calculator::class);

test('evaluation', function (string $expr, string $expected): void {
    $parser = new Parser(new Lexer($expr));

    $expr = $parser->parse();
    $result = (new Calculator(3))->evaluate($expr);

    expect($result)->toBcEqual($expected);
})->with([
    ['40+2', '42'],
    ['1-0.1', '0.9'],
    ['1/3*3', '0.999'],
    ['-5+10', '5'],
    ['6*7', '42'],
    ['1+2*3', '7'],
    ['10/2+3', '8'],
    ['4*5-10', '10'],
    ['-3*2', '-6'],
    ['+-3', '3'],
    ['100-50/2', '75'],
    ['3+4*2/8', '4'],
    ['--10', '10'],
    ['+-5+3', '8'],
    ['6/3*2', '4'],
    ['1-2+3-4+5', '3'],
    ['10/5-3', '-1'],
    ['2*-3+6', '0'],
    ['12+-4*-3', '24'],
    ['3^3+7', '34'],
    ['sqrt 0', '0'],
]);

test('unsupported expression', function () {
    (new Calculator(0))->evaluate(new readonly class extends Expr
    {
        public static function token(): TokenType
        {
            throw new RuntimeException;
        }

        public static function getLeftBindingPower(): ?int
        {
            throw new RuntimeException;
        }

        public static function getRightBindingPower(): ?int
        {
            throw new RuntimeException;
        }

        public function jsonSerialize(): mixed
        {
            throw new RuntimeException;
        }

        public function __toString()
        {
            throw new RuntimeException;
        }
    });
})->throws(UnsupportedExpressionException::class);
