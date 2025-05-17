<?php

use Illuminate\Support\Facades\Route;
use Modules\Mercado\Http\Controllers\MercadoController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('mercados', MercadoController::class)->names('mercado');
});
