<?php

declare(strict_types=1);

namespace App\Math;

use App\Math\Ast\Binary\Add;
use App\Math\Ast\Binary\Div;
use App\Math\Ast\Binary\Mul;
use App\Math\Ast\Binary\Pow;
use App\Math\Ast\Binary\Sub;
use App\Math\Ast\BinaryExpr;
use App\Math\Ast\Numeric;
use App\Math\Ast\Unary\Abs;
use App\Math\Ast\Unary\Neg;
use App\Math\Ast\Unary\Sqrt;
use Faker\Generator;
use Faker\Provider\Base;

final class FakerProvider extends Base
{
    /**
     * @var \Closure[]
     */
    private readonly array $ops;

    public function __construct(Generator $generator)
    {
        parent::__construct($generator);

        $this->ops = [
            fn (Expr $expr): Abs => new Abs($expr),
            fn (Expr $expr): Neg => new Neg($expr),
            fn (Expr $expr): Add => $this->binary(Add::class, $expr),
            fn (Expr $expr): Div => $this->binary(Div::class, $expr),
            fn (Expr $expr): Mul => $this->binary(Mul::class, $expr),
            fn (Expr $expr): Sub => $this->binary(Sub::class, $expr),
            fn (Expr $expr): Sqrt => new Sqrt($expr),
        ];
    }

    public function mathExpr(int $nbOps = 7): Expr
    {
        $expr = $this->number();

        // Limit to single pow expression to reduce result range
        if ($nbOps > 0 && $this->generator->boolean(10)) {
            $expr = $this->binary(Pow::class, $expr);
        }

        for (; $nbOps > 0; $nbOps--) {
            $expr = $this->generator->randomElement($this->ops)($expr);
        }

        return $expr;
    }

    /**
     * @template T of BinaryExpr
     *
     * @param  class-string<T>  $type
     * @return T
     */
    private function binary(string $type, Expr $expr): BinaryExpr
    {
        return $this->generator->boolean()
            ? new $type($expr, $this->number())
            : new $type($this->number(), $expr);
    }

    private function number(): Numeric
    {
        $integral = $this->generator->randomNumber(4);

        $value = $this->generator->boolean(37)
            ? (string) $integral
            : "$integral.".$this->generator->randomNumber(3);

        return new Numeric($value);
    }
}
