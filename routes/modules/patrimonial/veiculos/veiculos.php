<?php

use Illuminate\Support\Facades\Route;

// Rotas específicas para o módulo de veiculos
Route::prefix('veiculos')->group(function () {
    // Rotas para Cadastros
    require __DIR__ . '/cadastros/cadastros.php';
    
    // Rotas para Consultas
    require __DIR__ . '/consultas/consultas.php';

    // Rotas para Procedimentos
    require __DIR__ . '/procedimentos/procedimentos.php';
    
    // Rotas para Relatórios
    require __DIR__ . '/relatorios/relatorios.php';
});
