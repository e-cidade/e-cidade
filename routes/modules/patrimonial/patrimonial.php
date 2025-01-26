<?php

use Illuminate\Support\Facades\Route;

// Agrupar rotas do m�dulo Patrimonial
Route::prefix('patrimonial')->group(function () {
    // Inclui rotas do subm�dulo Compras
    require __DIR__ . '/compras/compras.php';

    // Inclui rotas do subm�dulo Contratos
    require __DIR__ . '/contratos/contratos.php';

    // Inclui rotas do subm�dulo Licita��es
    require __DIR__ . '/licitacoes/licitacoes.php';

    // Inclui rotas do subm�dulo Material
    require __DIR__ . '/material/material.php';

    // Inclui rotas do subm�dulo Obras
    require __DIR__ . '/obras/obras.php';

    // Inclui rotas do subm�dulo Ouvidoria
    require __DIR__ . '/ouvidoria/ouvidoria.php';

    // Inclui rotas do subm�dulo Patrimonio
    require __DIR__ . '/patrimonio/patrimonio.php';

    // Inclui rotas do subm�dulo Protocolo
    require __DIR__ . '/protocolo/protocolo.php';

    // Inclui rotas do subm�dulo Veiculos
    require __DIR__ . '/veiculos/veiculos.php';
});
