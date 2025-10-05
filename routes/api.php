<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PropertyController;
use App\Http\Middleware\ForceJsonResponse; // <-- Importe o middleware
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\OwnerController;
use App\Http\Controllers\Api\Public\PropertyController as PublicPropertyController;


// Rotas Públicas (sem autenticação)
Route::prefix('public/v1/tenants/{tenant_slug}')->group(function () {
    Route::get('properties', [PublicPropertyController::class, 'index']);
    Route::get('properties/{property:uuid}', [PublicPropertyController::class, 'show']);
});

Route::prefix('v1')->middleware(ForceJsonResponse::class)->group(function () {
    Route::post('/login', [AuthController::class, 'login']); // <-- Rota de login
    Route::post('/register', [AuthController::class, 'register']); // <-- Rota de registro
});


Route::prefix('v1')
    ->middleware([
        ForceJsonResponse::class,
        'auth:sanctum' // Adicionamos o segurança na porta do grupo 'v1'
    ])
    ->group(function () {
        Route::get('/user', [AuthController::class, 'user']); // Dados do usuário logado
        Route::post('/logout', [AuthController::class, 'logout']); // Logout

        Route::apiResource('properties', PropertyController::class);
        Route::apiResource('owners', OwnerController::class)->only(['index', 'store']);

        //owner
         Route::get('/my-properties', [PropertyController::class, 'myProperties']);
    });
