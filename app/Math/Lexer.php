<?php

declare(strict_types=1);

namespace App\Math;

use App\Math\Exceptions\UnrecognizedTokenException;
use Doctrine\Common\Lexer\AbstractLexer;

/**
 * @extends AbstractLexer<TokenType, string>
 */
class Lexer extends AbstractLexer
{
    /**
     * @throws UnrecognizedTokenException
     */
    public function __construct(string $input)
    {
        $this->setInput($input);
    }

    protected function getCatchablePatterns(): array
    {
        // @pest-mutate-ignore - dropping patterns has unintended effects due to catch-all @ AbstractLexer.php#251
        return [
            // Numbers
            '(?:[0-9]+(?:\.[0-9]+)*)',
            // Textual identifiers (e.g 'sqrt')
            '[a-z]+',
        ];
    }

    protected function getNonCatchablePatterns(): array
    {
        return [
            // Ignore whitespace
            '\s+',
            '(.)',
        ];
    }

    /**
     * @throws UnrecognizedTokenException
     */
    protected function getType(string &$value): TokenType
    {
        if ($tt = TokenType::tryFrom($value)) {
            return $tt;
        }

        if (is_numeric($value)) {
            return TokenType::Numeric;
        }

        throw new UnrecognizedTokenException($value);
    }
}
