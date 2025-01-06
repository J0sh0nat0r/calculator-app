<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\CalculationController;

Route::apiResource('calculations', CalculationController::class)->except('update');
