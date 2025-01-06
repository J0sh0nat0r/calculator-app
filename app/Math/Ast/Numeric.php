<?php

declare(strict_types=1);

namespace App\Math\Ast;

use App\Math\Expr;

final readonly class Numeric extends Expr
{
    /**
     * @param  numeric-string  $value
     */
    public function __construct(public readonly string $value) {}

    /**
     * @return array{
     *     type: string,
     *     value: string
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => 'Numeric',
            'value' => $this->value,
        ];
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
