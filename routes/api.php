<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->as('api.v1.')->group(__DIR__.'/api_v1.php');
