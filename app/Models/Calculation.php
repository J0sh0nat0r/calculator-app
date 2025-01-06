<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\Expr;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Calculation extends Model
{
    /** @use HasFactory<\Database\Factories\CalculationFactory> */
    use HasFactory;

    protected $fillable = [
        'expr',
    ];

    protected function casts(): array
    {
        return [
            'expr' => Expr::class,
        ];
    }
}
