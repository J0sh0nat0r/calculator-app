<?php

declare(strict_types=1);

namespace App\Math;

use App\Math\Attributes\InfixBindingPower;
use App\Math\Attributes\PrefixBindingPower;
use ReflectionClassConstant;

enum TokenType: string
{
    case Numeric = '#';
    #[InfixBindingPower(1, 2)]
    #[PrefixBindingPower(7)]
    case Add = '+';
    #[InfixBindingPower(1, 2)]
    #[PrefixBindingPower(7)]
    case Sub = '-';
    #[InfixBindingPower(3, 4)]
    case Div = '/';
    #[InfixBindingPower(3, 4)]
    case Mul = '*';
    #[InfixBindingPower(6, 5)]
    case Pow = '^';
    #[PrefixBindingPower(7)]
    case Sqrt = 'sqrt';

    case LParens = '(';
    case RParens = ')';

    public function getInfixBindingPower(): ?InfixBindingPower
    {
        return $this->getCaseAttribute(InfixBindingPower::class);
    }

    public function getPrefixBindingPower(): int
    {
        return $this->getCaseAttribute(PrefixBindingPower::class)->power;
    }

    /**
     * @template T of object
     *
     * @param  class-string<T>  $type
     * @return ?T
     */
    private function getCaseAttribute(string $type): ?object
    {
        $attrs = (new ReflectionClassConstant(self::class, $this->name))->getAttributes($type);

        return empty($attrs) ? null : $attrs[0]->newInstance();
    }
}
