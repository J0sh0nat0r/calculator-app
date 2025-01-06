<?php

declare(strict_types=1);

use App\Http\Controllers\ShowCalculatorController;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowCalculatorController::class)->name('index');
