<?php

use Illuminate\Support\Facades\Route;

// Rotas específicas para o módulo de procedimentos
Route::prefix('manutencao-de-dados')->group(function () {
    require __DIR__.'/manutencaoLancamentosPatrimonial/manutencaoLancamentosPatrimonial.php';
});
