<?php

use App\Http\Controllers\Modules\Configuracao\Configuracao\Procedimentos\ManutencaoDeDados\ManutencaoLancamentosPatrimonial\ControleDeDatas\EmpAutorizaController;
use Illuminate\Support\Facades\Route;

// Rotas específicas para o módulo de Autorização de Empenho
Route::prefix('autorizacao-de-empenho')->group(function () {
    Route::get('/', [EmpAutorizaController::class, 'index'])->name('empautoriza.index');
    Route::get('empautoriza/getByPrimaryKeyRange/{iCodigoEmpenhoInicial}/{iCodigoEmpenhoFinal}', [EmpAutorizaController::class, 'getByPrimaryKeyRange'])->name('empautoriza-getByIdRange');
    Route::post('empautoriza/updateDateByIds', [EmpAutorizaController::class, 'updateDateByIds'])->name('empautoriza-updateDateByIds');
});
