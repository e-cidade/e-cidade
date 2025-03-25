<?php

use Illuminate\Support\Facades\Route;

// Rotas específicas para o módulo de procedimentos
Route::prefix('controle-de-datas')->group(function () {
    require __DIR__.'/autorizacoesDeEmpenho.php';
});
