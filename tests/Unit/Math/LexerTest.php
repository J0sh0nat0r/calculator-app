<?php

declare(strict_types=1);

use App\Math\Exceptions\UnrecognizedTokenException;
use App\Math\Lexer;
use App\Math\TokenType;

mutates(Lexer::class);

describe('lexer', function (): void {
    it('recognizes integers', function (): void {
        $input = (string) fake()->randomNumber();
        $lexer = new Lexer($input);

        $token = $lexer->glimpse();

        expect($token)->toBeToken(TokenType::Numeric, $input);
    });

    it('recognizes floats', function (): void {
        $input = (string) fake()->randomFloat();
        $lexer = new Lexer($input);

        $token = $lexer->glimpse();

        expect($token)->toBeToken(TokenType::Numeric, $input);
    });

    it('recognizes tokens', function (TokenType $type) {
        $lexer = new Lexer($type->value);

        $token = $lexer->glimpse();

        expect($token)->toBeToken($type, $type->value);
    })->with([
        TokenType::Add,
        TokenType::Sub,
        TokenType::Div,
        TokenType::Mul,
        TokenType::Pow,
        TokenType::Sqrt,
        TokenType::LParens,
        TokenType::RParens,
    ]);

    it('skips whitespace', function (): void {
        $lexer = new Lexer("\t 42");

        $token = $lexer->glimpse();

        expect($token)->toBeToken(TokenType::Numeric, '42');
    });

    test('token sequence', function (): void {
        $lexer = new Lexer('0.3+-0.7*1');
        $tokens = [];

        $lexer->moveNext();
        while ($token = $lexer->lookahead) {
            $tokens[] = $token;
            $lexer->moveNext();
        }

        expect($tokens)->sequence(
            fn ($token) => $token->toBeToken(TokenType::Numeric, '0.3'),
            fn ($token) => $token->toBeToken(TokenType::Add, '+'),
            fn ($token) => $token->toBeToken(TokenType::Sub, '-'),
            fn ($token) => $token->toBeToken(TokenType::Numeric, '0.7'),
            fn ($token) => $token->toBeToken(TokenType::Mul, '*'),
            fn ($token) => $token->toBeToken(TokenType::Numeric, '1'),
        );
    });

    test('unrecognized token', function (): void {
        $lexer = new Lexer('!');

        $lexer->moveNext();
    })->throws(UnrecognizedTokenException::class);
});
