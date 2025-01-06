<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Calculation;
use Illuminate\Database\Seeder;

class CalculationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Calculation::factory()
            ->count(1000)
            ->create();
    }
}
