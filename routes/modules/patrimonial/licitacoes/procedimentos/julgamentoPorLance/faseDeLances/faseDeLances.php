<?php

use App\Http\Controllers\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\FaseDeLancesController;
use App\Http\Controllers\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\JulgamentoController;
use App\Http\Controllers\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\ReadequarPropostaController;
use Illuminate\Support\Facades\Route;

// Rotas específicas para o módulo de fase de lances
Route::prefix('fase-de-lances')->group(function () {
    // Rotas da tela de incial da rotina de fase de lances
    Route::get('/', [FaseDeLancesController::class, 'index'])->name('faseDeLances.index');
    Route::get('/listar-licitacoes', [FaseDeLancesController::class, 'listGridData'])->name('faseDeLances.getLicitacoes');
    
    Route::post('/alterar-status-item', [FaseDeLancesController::class, 'alterarStatusItem'])->name('faseDeLances.alterarStatusItem');
    Route::post('/limpar-lance', [FaseDeLancesController::class, 'limparLances'])->name('faseDeLances.limparLances');
    
    Route::get('/get-liclicita-lotes', [FaseDeLancesController::class, 'getLiclicitaLotes'])->name('datagrid.getLiclicitaLotes');

    Route::post('/finalizar', [FaseDeLancesController::class, 'finalizar'])->name('faseDeLances.finalizar');

    // Rotas da tela de julgamentos da rotina de fase de lances
    Route::get('/julgamento/{codigoLicitacao}/{codigoLicitacaoItem}', [JulgamentoController::class, 'index'])->name('julgamento.index');
    Route::get('/julgamento/licitacao-fornecedores/{codigoLicitacao}/{codigoLicitacaoItem}', [JulgamentoController::class, 'obterListaDeFornecedoresEPropostaDeLicitacao'])->name('julgamento.obterFornecedoresProposta');
    Route::get('/julgamento/fornecedores-microempresa/{codigoLicitacao}/{codigoLicitacaoItem}', [JulgamentoController::class, 'validarModeloEmpresarialDosFornecedores'])->name('julgamento.validarModeloEmpresarialDosFornecedores');
    
    Route::post('/julgamento/finalizar', [JulgamentoController::class, 'finalizar'])->name('julgamento.finalizar');
    Route::post('/julgamento/registrar-lance', [JulgamentoController::class, 'registrarLance'])->name('julgamento.registrarLance');
    Route::post('/julgamento/registrar-lance-sem-valor', [JulgamentoController::class, 'registrarLanceSemValor'])->name('julgamento.registrarLanceSemValor');
    Route::post('/julgamento/reverter-lance', [JulgamentoController::class, 'reverterLance'])->name('julgamento.reverterLance');
    Route::post('/julgamento/limpar-lance', [JulgamentoController::class, 'limparLances'])->name('julgamento.limparLances');
    Route::post('/julgamento/alterar-status-fornecedor', [JulgamentoController::class, 'alterarStatusFornecedor'])->name('julgamento.alterarStatusFornecedor');
    Route::post('/julgamento/liberar-micro-empresas', [JulgamentoController::class, 'liberarMicroEmpresas'])->name('julgamento.liberarMicroEmpresas');

    // Rotas da tela de readequar proposta da rotina de fase de lances
    Route::get('/readequar-proposta/{codigoLicitacao}/{codigoLicitacaoItem}', [ReadequarPropostaController::class, 'index'])->name('readequarProposta.index');
    Route::get('/readequar-proposta/obter-itens-da-readequecao-de-proposta/{codigoLicitacao}/{codigoOrcamforne}/{codigoLote}', [ReadequarPropostaController::class, 'obterItensDaReadequecaoDeProposta'])->name('readequarProposta.obterItensDaReadequecaoDeProposta');
    Route::post('/readequar-proposta/verificar-proposta-existente/', [ReadequarPropostaController::class, 'verificarPropostaExistente'])->name('readequarProposta.verificarPropostaExistente');
    Route::post('/readequar-proposta/salvar-proposta/', [ReadequarPropostaController::class, 'salvarProposta'])->name('readequarProposta.salvarProposta');
    Route::post('/readequar-proposta/deletar-proposta/', [ReadequarPropostaController::class, 'deletarProposta'])->name('readequarProposta.deletarProposta');
    Route::post('/readequar-proposta/importar-itens/', [ReadequarPropostaController::class, 'uploadXlsx'])->name('readequarProposta.importarItens');
});
