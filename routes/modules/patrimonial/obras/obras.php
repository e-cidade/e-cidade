<?php

use Illuminate\Support\Facades\Route;

// Rotas específicas para o módulo de obras
Route::prefix('obras')->group(function () {
    // Rotas para Cadastros
    require __DIR__ . '/cadastros/cadastros.php';
    
    // Rotas para Migração
    require __DIR__ . '/notificacoes/notificacoes.php';
    
    // Rotas para Procedimentos
    require __DIR__ . '/procedimentos/procedimentos.php';
});
