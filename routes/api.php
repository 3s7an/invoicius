<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AutomatizationController;
use Illuminate\Support\Facades\Route;

Route::post('/automatizations/process', [AutomatizationController::class, 'process']);
