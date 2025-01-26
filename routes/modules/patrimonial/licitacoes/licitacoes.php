<?php

use Illuminate\Support\Facades\Route;

// Rotas espec�ficas para o m�dulo de licitacoes
Route::prefix('licitacoes')->group(function () {
    // Rotas para Cadastros
    require __DIR__ . '/cadastros/cadastros.php';
    
    // Rotas para Consultas
    require __DIR__ . '/consultas/consultas.php';
    
    // Rotas para Migra��o
    require __DIR__ . '/notificacoes/notificacoes.php';
    
    // Rotas para Procedimentos
    require __DIR__ . '/procedimentos/procedimentos.php';
    
    // Rotas para Relat�rios
    require __DIR__ . '/relatorios/relatorios.php';
});
