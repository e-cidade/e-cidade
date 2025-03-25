<?php

use Illuminate\Support\Facades\Route;

// Agrupar rotas do módulo Configuração
Route::prefix('relatorios')->group(function () {
    // Rotas para Procedimentos
    require __DIR__.'/relatorios/relatoriodeusuarios.php';
});
