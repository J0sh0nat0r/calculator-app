<?php

declare(strict_types=1);

namespace App\Providers;

use App\Math\Calculator;
use App\Math\Contracts\Calculator as CalculatorContract;
use App\Math\FakerProvider;
use Faker\Generator;
use Illuminate\Support\ServiceProvider;

final class MathServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CalculatorContract::class, Calculator::class);
        $this->app->when(Calculator::class)
            ->needs('$scale')
            ->giveConfig('math.scale');

        $this->app->extend(Generator::class, static function (Generator $generator): Generator {
            $generator->addProvider(new FakerProvider($generator));

            return $generator;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
