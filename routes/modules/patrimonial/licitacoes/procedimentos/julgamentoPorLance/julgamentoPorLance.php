<?php

use Illuminate\Support\Facades\Route;

// Rotas específicas para o módulo de julgamento por lance
Route::prefix('julgamento-por-lance')->group(function () {
    require __DIR__ . '/faseDeLances/faseDeLances.php';
    require __DIR__ . '/parametros/parametros.php';
    require __DIR__ . '/relatorios/relatorios.php';
});
