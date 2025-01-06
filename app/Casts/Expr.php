<?php

declare(strict_types=1);

namespace App\Casts;

use App\Math\Exceptions\ParserException;
use App\Math\Expr as MathExpr;
use App\Math\Lexer;
use App\Math\Parser;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @implements CastsAttributes<MathExpr, MathExpr>
 */
final class Expr implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     *
     * @throws ParserException
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?MathExpr
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof MathExpr) {
            return $value;
        }

        $parser = new Parser(new Lexer((string) $value));

        return $parser->parse();
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        return (string) $value;
    }
}
