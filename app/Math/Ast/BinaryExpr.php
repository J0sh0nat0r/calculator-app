<?php

declare(strict_types=1);

namespace App\Math\Ast;

use App\Math\Ast\Concerns\IsInfixExpr;
use App\Math\Expr;

abstract readonly class BinaryExpr extends Expr
{
    use IsInfixExpr;

    public function __construct(public Expr $lhs, public Expr $rhs) {}

    /**
     * @return array{
     *     type: string,
     *     lhs: Expr,
     *     fenceLhs: bool,
     *     rhs: Expr,
     *     fenceRhs: bool
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => class_basename(static::class),
            'lhs' => $this->lhs,
            'fenceLhs' => $this->shouldFenceLhs(),
            'rhs' => $this->rhs,
            'fenceRhs' => $this->shouldFenceRhs(),
        ];
    }

    public function __toString(): string
    {
        if ($this->shouldFenceLhs()) {
            $lhs = "($this->lhs)";
        } else {
            $lhs = (string) $this->lhs;
        }

        if ($this->shouldFenceRhs()) {
            $rhs = "($this->rhs)";
        } else {
            $rhs = (string) $this->rhs;
        }

        $op = static::token()->value;

        return "$lhs $op $rhs";
    }

    private function shouldFenceLhs(): bool
    {
        $lhsRbp = $this->lhs::getRightBindingPower();

        return $lhsRbp !== null && $lhsRbp < static::getLeftBindingPower();
    }

    private function shouldFenceRhs(): bool
    {
        $rhsLbp = $this->rhs::getLeftBindingPower();

        return $rhsLbp !== null && $rhsLbp < static::getRightBindingPower();
    }
}
