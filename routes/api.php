<?php

use App\Http\Controllers\LibreSignCallbackController;
use App\Http\Controllers\RedesimController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['redesimAuth']], function () {
    //redesim
    Route::group(['prefix' => 'redesim'], function () {
        Route::post('/companies', [RedesimController::class, 'index'])
            ->name('redesim.companies');
    });
});

// Callback do middleware LibreSign (devolucao do PDF assinado).
// Autenticado por Bearer token (ECIDADE_CALLBACK_TOKEN) no proprio controller.
Route::post('/libresign/callback', [LibreSignCallbackController::class, 'store'])
    ->name('libresign.callback');
