<?php

use Illuminate\Support\Facades\Route;

// Agrupar rotas do módulo Configuração
Route::prefix('configuracao')->group(function () {
    // Inclui rotas do submódulo Compras
    require __DIR__.'/configuracao/configuracao.php';
});
