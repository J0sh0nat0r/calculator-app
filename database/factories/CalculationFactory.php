<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Math\Contracts\Calculator;
use DivisionByZeroError;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Calculation>
 */
class CalculationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array{expr: string, result: string}
     */
    public function definition(): array
    {
        $expr = $this->faker->mathExpr;

        try {
            $result = app(Calculator::class)->evaluate($expr);
        } catch (DivisionByZeroError) {
            $result = '0.0';
        }

        return [
            'expr' => $expr,
            'result' => $result,
        ];
    }
}
