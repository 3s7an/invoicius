<?php

use App\Http\Controllers\Api\AutomatizationController;
use Illuminate\Support\Facades\Route;

Route::post('/automatizations/process', [AutomatizationController::class, 'process']);
