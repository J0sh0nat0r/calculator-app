<?php

declare(strict_types=1);

use App\Math\TokenType;
use Doctrine\Common\Lexer\Token;
use PHPUnit\Framework\Assert;

expect()->extend('toBcEqual', function (string $expected) {
    Assert::assertTrue(
        bccomp($expected, $this->value) === 0,
        "Failed asserting that $this->value is equal to $expected"
    );

    return $this;
});

expect()->extend('toBeToken', function (?TokenType $type = null, ?string $value = null) {
    return $this->toBeInstanceOf(Token::class)
        ->and($this->value->type)->when($type !== null, fn ($t) => $t->toBe($type))
        ->and($this->value->value)->when($value !== null, fn ($v) => $v->toBe($value));
});
