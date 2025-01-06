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
use App\Math\Exceptions\UnsupportedExpressionException;
use BCMathExtended\BC;
use ValueError;

final readonly class Calculator implements Contracts\Calculator
{
    public function __construct(private int $scale)
    {
        // FIXME: static :(
        BC::setTrimTrailingZeroes(false);
    }

    /**
     * @return numeric-string
     */
    public function evaluate(Expr $expr): string
    {
        return match ($expr::class) {
            Abs::class => BC::abs($this->evaluate($expr->operand)),
            Add::class => $this->evalBinary(bcadd(...), $expr),
            Numeric::class => $expr->value,
            Div::class => $this->evalBinary(bcdiv(...), $expr),
            Mul::class => $this->evalBinary(bcmul(...), $expr),
            Neg::class => $this->evalNeg($expr),
            Pow::class => $this->evalBinary(BC::pow(...), $expr),
            Sqrt::class => $this->evalSqrt($expr),
            Sub::class => $this->evalBinary(bcsub(...), $expr),
            default => throw new UnsupportedExpressionException($expr::class)
        };
    }

    /**
     * @param  callable(numeric-string, numeric-string, int): numeric-string  $handler
     * @return numeric-string
     */
    private function evalBinary(callable $handler, BinaryExpr $expr): string
    {
        $lhs = $this->evaluate($expr->lhs);
        $rhs = $this->evaluate($expr->rhs);

        return $handler($lhs, $rhs, $this->scale);
    }

    /**
     * @return numeric-string
     */
    private function evalNeg(Neg $expr): string
    {
        return bcsub('0', $this->evaluate($expr->operand), $this->scale);
    }

    /**
     * @return numeric-string
     */
    private function evalSqrt(Sqrt $expr): string
    {
        $operand = $this->evaluate($expr->operand);

        try {
            return bcsqrt($operand, $this->scale);
        } catch (ValueError) {
            return '0';
        }
    }
}
