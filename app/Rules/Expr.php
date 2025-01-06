<?php

declare(strict_types=1);

namespace App\Rules;

use App\Math\Exceptions\ParserException;
use App\Math\Exceptions\UnrecognizedTokenException;
use App\Math\Lexer;
use App\Math\Parser;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class Expr implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            (new Parser(new Lexer((string) $value)))->parse();
        } catch (UnrecognizedTokenException $e) {
            $fail("Unrecognized token in :attribute: $e->token");
        } catch (ParserException $e) {
            $fail($e->getMessage());
        }
    }
}
