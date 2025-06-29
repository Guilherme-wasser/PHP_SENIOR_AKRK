<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ImportController;

Route::prefix('auth')->group(function () {
    Route::post('login',    [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('me',       [AuthController::class, 'me']);
        Route::post('logout',  [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
});


Route::middleware('auth:api')->group(function () {

    // Apenas ADMIN pode criar importações
    Route::post('/imports',  [ImportController::class,'store'])
         ->middleware('role:admin');

    // Ambos os papéis podem listar/baixar
    Route::get('/imports',                     [ImportController::class,'index']);
    Route::get('/imports/{import}/download/excel', [ImportController::class,'downloadExcel']);
    Route::get('/imports/{import}/download/cnab',  [ImportController::class,'downloadCnab']);
});

