<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ImportController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\FundController;

/* ------------------ ROTAS DE AUTENTICAÇÃO ------------------ */
Route::prefix('auth')->group(function () {
    Route::post('login',   [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('me',      [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh',[AuthController::class, 'refresh']);
    });
});

/* ------------------ ROTAS PROTEGIDAS ------------------ */
Route::middleware('auth:api')->group(function () {

    // Somente ADMIN pode iniciar importações
    Route::post('/imports', [ImportController::class, 'store'])
         ->middleware('role:admin,api');

    // ADMIN ou OPERADOR podem listar/baixar
    Route::middleware('role:admin|user,api')->group(function () {
        Route::get('/imports',                             [ImportController::class, 'index']);
        Route::get('/imports/{import}',                    [ImportController::class, 'show']);
        Route::get('/imports/{import}/download/excel',     [ImportController::class, 'downloadExcel']);
        Route::get('/imports/{import}/download/cnab',      [ImportController::class, 'downloadCnab']);
    });

    // Somente ADMIN pode gerenciar usuários
    Route::middleware('role:admin,api')->group(function () {
        Route::get('/users',  [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
    });

    // Qualquer autenticado pode listar fundos
    Route::get('/funds', [FundController::class, 'index']);
});
