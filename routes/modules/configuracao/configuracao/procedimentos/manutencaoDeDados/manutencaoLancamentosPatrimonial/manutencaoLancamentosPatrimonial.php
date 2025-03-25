<?php

use Illuminate\Support\Facades\Route;

// Rotas específicas para o módulo de procedimentos
Route::prefix('manutencao-lancamentos-patrimonial')->group(function () {
    require __DIR__.'/controleDeDatas/controleDeDatas.php';
});
