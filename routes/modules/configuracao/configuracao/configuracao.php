<?php

use Illuminate\Support\Facades\Route;

// Agrupar rotas do módulo Configuração
Route::prefix('configuracao')->group(function () {
    // Rotas para Procedimentos
    require __DIR__.'/procedimentos/procedimentos.php';
});
