<?php

declare(strict_types=1);

namespace App\Math\Ast;

use App\Math\Ast\Concerns\IsPrefixExpr;
use App\Math\Expr;

abstract readonly class UnaryExpr extends Expr
{
    use IsPrefixExpr;

    public function __construct(public Expr $operand) {}

    /**
     * @return array{
     *     type: string,
     *     operand: Expr,
     *     fenceOperand: bool
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => class_basename(static::class),
            'operand' => $this->operand,
            'fenceOperand' => $this->shouldFenceOperand(),
        ];
    }

    // FIXME: Currently supports only right-associative (prefix) unary operators
    protected function shouldFenceOperand(): bool
    {
        $operandLbp = $this->operand::getLeftBindingPower();

        return $operandLbp !== null && $operandLbp < static::getRightBindingPower();
    }

    public function __toString(): string
    {
        $token = static::token();

        if ($this->shouldFenceOperand()) {
            return "{$token->value}($this->operand)";
        }

        return "{$token->value}{$this->operand}";
    }
}
