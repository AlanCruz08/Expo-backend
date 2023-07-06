<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonasController;

Route::middleware(['auth:sanctum'])
    ->prefix('persona')
    ->group(function () {

        Route::get('/check', function() { return 'ok'; });

        Route::get('', [PersonasController::class, 'index'])
            ->name('index');

        Route::get('/{personaID}', [PersonasController::class, 'show'])
            ->name('show')
            ->whereNumber('persona');

        Route::post('', [PersonasController::class, 'store'])
            ->name('store');

        Route::put('/{personaID}', [PersonasController::class, 'update'])
            ->name('update')
            ->whereNumber('persona');

        Route::delete('/{personaID}', [PersonasController::class, 'destroy'])
            ->name('destroy')
            ->whereNumber('persona');
    });
