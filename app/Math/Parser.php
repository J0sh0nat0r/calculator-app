<?php

declare(strict_types=1);

namespace App\Math;

use App\Math\Ast\Binary\Add;
use App\Math\Ast\Binary\Div;
use App\Math\Ast\Binary\Mul;
use App\Math\Ast\Binary\Pow;
use App\Math\Ast\Binary\Sub;
use App\Math\Ast\Numeric;
use App\Math\Ast\Unary\Abs;
use App\Math\Ast\Unary\Neg;
use App\Math\Ast\Unary\Sqrt;
use App\Math\Ast\UnaryExpr;
use App\Math\Exceptions\ParserException;
use Doctrine\Common\Lexer\Token;

/**
 * A simple Pratt parser for mathematical expressions such as `1 + 2 + 3` and `(4 * 3) + 7 / -2`
 *
 * @see https://matklad.github.io/2020/04/13/simple-but-powerful-pratt-parsing.html
 */
final readonly class Parser
{
    public function __construct(private Lexer $lexer) {}

    /**
     * @throws ParserException
     */
    public function parse(): Expr
    {
        $this->lexer->moveNext();

        $expr = $this->expr(0);

        if ($this->lexer->lookahead !== null) {
            throw new ParserException("unexpected token: '{$this->lexer->lookahead->value}' (expecting EOF)");
        }

        return $expr;
    }

    /**
     * @throws ParserException
     */
    private function expr(int $rbp): Expr
    {
        $this->lexer->moveNext();
        $lhs = match ($this->lexer->token?->type) {
            TokenType::Numeric => new Numeric($this->lexer->token->value),
            TokenType::LParens => $this->parenthesized(),
            null => throw new ParserException('unexpected EOF (expecting expression)'),
            default => $this->unary($this->lexer->token)
        };

        while ($token = $this->lexer->lookahead) {
            $bp = $token->type->getInfixBindingPower();

            if ($bp === null || $bp->left < $rbp) {
                break;
            }

            $this->lexer->moveNext();

            $rhs = $this->expr($bp->right);

            $lhs = match ($token->type) {
                TokenType::Add => new Add($lhs, $rhs),
                TokenType::Sub => new Sub($lhs, $rhs),
                TokenType::Div => new Div($lhs, $rhs),
                TokenType::Mul => new Mul($lhs, $rhs),
                TokenType::Pow => new Pow($lhs, $rhs),
                default => throw new ParserException("unexpected token: '$token->value'")
            };
        }

        return $lhs;
    }

    /**
     * @param  Token<TokenType, string>  $op
     *
     * @throws ParserException
     */
    private function unary(Token $op): UnaryExpr
    {
        $rhs = fn (): Expr => $this->expr($op->type->getPrefixBindingPower());

        return match ($op->type) {
            TokenType::Add => new Abs($rhs()),
            TokenType::Sub => new Neg($rhs()),
            TokenType::Sqrt => new Sqrt($rhs()),
            default => throw new ParserException("unexpected token: '$op->value'")
        };
    }

    /**
     * @throws ParserException
     */
    private function parenthesized(): Expr
    {
        $expr = $this->expr(0);

        $this->expect(TokenType::RParens);

        return $expr;
    }

    /**
     * @return Token<TokenType, string>
     *
     * @throws ParserException
     */
    private function expect(TokenType $type): Token
    {
        if (! $this->lexer->isNextToken($type)) {
            $value = $this->lexer->lookahead?->value;

            if ($value === null) {
                throw new ParserException("unexpected EOF (expecting '$type->value')");
            }

            throw new ParserException("unexpected token: '$value' (expecting '$type->value')");
        }

        $this->lexer->moveNext();

        return $this->lexer->token;
    }
}
