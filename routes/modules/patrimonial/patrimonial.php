<?php

use Illuminate\Support\Facades\Route;

// Agrupar rotas do módulo Patrimonial
Route::prefix('patrimonial')->group(function () {
    // Inclui rotas do submódulo Compras
    require __DIR__ . '/compras/compras.php';

    // Inclui rotas do submódulo Contratos
    require __DIR__ . '/contratos/contratos.php';

    // Inclui rotas do submódulo Licitações
    require __DIR__ . '/licitacoes/licitacoes.php';

    // Inclui rotas do submódulo Material
    require __DIR__ . '/material/material.php';

    // Inclui rotas do submódulo Obras
    require __DIR__ . '/obras/obras.php';

    // Inclui rotas do submódulo Ouvidoria
    require __DIR__ . '/ouvidoria/ouvidoria.php';

    // Inclui rotas do submódulo Patrimonio
    require __DIR__ . '/patrimonio/patrimonio.php';

    // Inclui rotas do submódulo Protocolo
    require __DIR__ . '/protocolo/protocolo.php';

    // Inclui rotas do submódulo Veiculos
    require __DIR__ . '/veiculos/veiculos.php';
});
