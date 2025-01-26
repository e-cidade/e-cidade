<?php

use Illuminate\Support\Facades\Route;

// Rotas espec�ficas para o m�dulo de obras
Route::prefix('obras')->group(function () {
    // Rotas para Cadastros
    require __DIR__ . '/cadastros/cadastros.php';
    
    // Rotas para Migra��o
    require __DIR__ . '/notificacoes/notificacoes.php';
    
    // Rotas para Procedimentos
    require __DIR__ . '/procedimentos/procedimentos.php';
});
