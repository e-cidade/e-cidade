<?php

use App\Http\Controllers\SicomLicitacaoController;
use Illuminate\Support\Facades\Route;

// Rotas específicas para o módulo de procedimentos
Route::prefix('procedimentos')->group(function () {
    require __DIR__ . '/julgamentoPorLance/julgamentoPorLance.php';
    Route::post('sicom/getProcessos', [SicomLicitacaoController::class, 'getProcessos'])->name('getProcessos');
    Route::post('sicom/validacaoCadastroInicial', [SicomLicitacaoController::class, 'validacaoCadastroInicial'])->name('validacaoCadastroInicial');
    Route::post('sicom/getCodigoRemessa', [SicomLicitacaoController::class, 'getCodigoRemessa'])->name('getCodigoRemessa');
    Route::post('sicom/gerarArquivos', [SicomLicitacaoController::class, 'gerarArquivos'])->name('gerarArquivos');
    Route::post('sicom/salvarRemessa', [SicomLicitacaoController::class, 'salvarRemessa'])->name('salvarRemessa');
});
