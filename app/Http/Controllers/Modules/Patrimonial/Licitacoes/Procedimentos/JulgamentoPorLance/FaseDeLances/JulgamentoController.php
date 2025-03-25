<?php

namespace App\Http\Controllers\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances;

use App\Exceptions\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\JulgamentoException;
use App\Helpers\StringHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\Julgamento\AlterarStatusFornecedorRequest;
use App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\Julgamento\FinalizarRequest;
use App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\Julgamento\LiberarMicroEmpresasRequest;
use App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\Julgamento\LimparLancesRequest;
use App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\Julgamento\RegistrarLanceRequest;
use App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\Julgamento\RegistrarLanceSemValorRequest;
use App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\Julgamento\ReverterLanceRequest;
use App\Models\Patrimonial\Licitacao\Julgitem;
use App\Models\Patrimonial\Licitacao\Liclicita;
use App\Services\Patrimonial\Licitacao\Procedimentos\JulgamentoPorLance\FaseDeLancesService;
use App\Services\Patrimonial\Licitacao\Procedimentos\JulgamentoPorLance\JulgamentoItemService;
use App\Services\Patrimonial\Licitacao\Procedimentos\JulgamentoPorLance\JulgamentoLoteService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JulgamentoController extends Controller
{
    protected $faseDeLancesService;
    protected $julgamentoItemService;
    protected $julgamentoLoteService;
    
    /**
     * Construtor do controller para injeção de dependência do JulgamentoService.
     *
     * @param JulgamentoService $julgamentoItemService
     */
    public function __construct(
        FaseDeLancesService $faseDeLancesService, 
        JulgamentoItemService $julgamentoItemService, 
        JulgamentoLoteService $julgamentoLoteService
    )
    {
        $this->faseDeLancesService = $faseDeLancesService;
        $this->julgamentoItemService = $julgamentoItemService;
        $this->julgamentoLoteService = $julgamentoLoteService;
    }

    /**
     * Página inicial do julgamento (pode ser utilizado para carregar uma visão inicial).
     */
    public function index($codigoLicitacao, $codigoLicitacaoItemOuLote)
    {        
        $liclicita = Liclicita::findOrFail($codigoLicitacao);

        if ($liclicita->l20_tipojulg == 1) {
            $data = $this->pageContentItem($codigoLicitacao, $codigoLicitacaoItemOuLote);
        } else if ($liclicita->l20_tipojulg == 3) {
            $data = $this->pageContentLot($codigoLicitacao, $codigoLicitacaoItemOuLote);
        }

        $data["tipojulg"] = $liclicita->l20_tipojulg;

        return view()->file(base_path('resources/legacy/licitacao/lic_fasedelance002.php'), $data);
    }

    /**
     * Obtém os dados necessários para carregar a view com a formatação correta.
     * 
     * @param int $codigoLicitacao Código da licitação.
     * @param int $codigoLicitacaoItemOuLote Código do item da licitação.
     * 
     * @return array $data.
     */
    private function pageContentItem($codigoLicitacao, $codigoLicitacaoItemOuLote)
    {
        $param = $this->faseDeLancesService->obterParametros();
        $selectedItem = $this->julgamentoItemService->obterDadosDoItemSelecionado($codigoLicitacao, $codigoLicitacaoItemOuLote);
        $orcamitemCode = $this->julgamentoItemService->obterCodigoDoItemDeOrcamento($codigoLicitacao, $codigoLicitacaoItemOuLote);

        $statusFornecedores = $this->faseDeLancesService->obterStatusDosFornecedores();

        $statusItensLabels = [
            '1' => $statusFornecedores[0]->l35_label,
            '4' => $statusFornecedores[3]->l35_label,
            '5' => $statusFornecedores[4]->l35_label
        ];

        $data = [
            "selectedItem" => [
                'descricao' => $selectedItem->pc01_descrmater, 
                'unidade' => $selectedItem->m61_descr, 
            ],
            "statusItensLabels" => $statusItensLabels,
            "codigoItemOuLote" => $orcamitemCode->pc22_orcamitem,
            "julgitemstatus" => $orcamitemCode->l30_julgitemstatus,
            "codigoLicitacao" => $codigoLicitacao,
            "codigoLicitacaoItem" => $codigoLicitacaoItemOuLote,
            "param" => $param,
        ];

        if (!empty($param->l13_precoref)) {
            $data["precoReferencia"] = $this->julgamentoItemService->obterPrecoReferencia($codigoLicitacao, $codigoLicitacaoItemOuLote);
        }

        return $data;
    }

    /**
     * Obtém os dados necessários para carregar a view com a formatação correta.
     * 
     * @param int $codigoLicitacao Código da licitação.
     * @param int $codigoLicitacaoItemOuLote Código do lote da licitação.
     * 
     * @return array $data.
     */
    private function pageContentLot($codigoLicitacao, $codigoLicitacaoItemOuLote)
    {
        $param = $this->faseDeLancesService->obterParametros();
        $selectedItem = $this->julgamentoLoteService->obterDadosDoLotesSelecionado($codigoLicitacaoItemOuLote);
        $julgItemLotes = Julgitem::where('l30_numerolote', $codigoLicitacaoItemOuLote)->first();

        $statusFornecedores = $this->faseDeLancesService->obterStatusDosFornecedores();

        $statusItensLabels = [
            '1' => $statusFornecedores[0]->l35_label,
            '4' => $statusFornecedores[3]->l35_label,
            '5' => $statusFornecedores[4]->l35_label
        ];

        $data = [
            "selectedItem" => [
                'descricao' => $selectedItem->l04_descricao, 
                'unidade' => $selectedItem->l04_unidade, 
            ],
            "statusItensLabels" => $statusItensLabels,
            "codigoItemOuLote" => $codigoLicitacaoItemOuLote,
            "julgitemstatus" => $julgItemLotes->l30_julgitemstatus??null,
            "codigoLicitacao" => $codigoLicitacao,
            "codigoLicitacaoItem" => $codigoLicitacaoItemOuLote,
            "param" => $param,
        ];

        if (!empty($param->l13_precoref)) {
            $data["precoReferencia"] = $this->julgamentoLoteService->obterPrecoReferencia($codigoLicitacao, $codigoLicitacaoItemOuLote);
        }

        return $data;
    }

    /**
     * Obtém a lista de fornecedores e suas propostas para uma licitação, incluindo os lances realizados.
     * Caso não haja propostas ou ocorra erro, retorna mensagens de erro apropriadas.
     * 
     * @param int $codigoLicitacao Código da licitação.
     * @param int $codigoLicitacaoItem Código do item da licitação.
     * 
     * @return \Illuminate\Http\Resources\Json\JsonResource Coleção de fornecedores e propostas.
     * 
     * @throws JulgamentoException Se não forem encontradas propostas.
     * @throws \Exception Se ocorrer erro inesperado.
     */
    public function obterListaDeFornecedoresEPropostaDeLicitacao($codigoLicitacao, $codigoLicitacaoItemOuLote) 
    {
        $tipojulg = request()->get('tipojulg', null);

        if (empty($tipojulg) || $tipojulg == '1') {
            return $this->obterListaDeFornecedoresEPropostaDeLicitacaoDosItens($codigoLicitacao, $codigoLicitacaoItemOuLote);
        } else {
            return $this->obterListaDeFornecedoresEPropostaDeLicitacaoDosLotes($codigoLicitacao, $codigoLicitacaoItemOuLote);
        }
    }

    private function obterListaDeFornecedoresEPropostaDeLicitacaoDosItens($codigoLicitacao, $codigoLicitacaoItem) 
    {
        try {

            $param = $this->faseDeLancesService->obterParametros();
            $ignorarCache = request()->get('ignorarCache', false);

            $list = $this->julgamentoItemService->obterListaDeFornecedoresEProposta($codigoLicitacao, $codigoLicitacaoItem, $param->l13_clapercent, $ignorarCache);

            if ($list->isEmpty()) {
                throw new JulgamentoException("Não encontrado propostas do fornecedores.");
            }

            // if ($list->count() < 3) {
            //     throw new JulgamentoException("O pregão não pode ser iniciado com menos de três propostas classificadas de fornecedores distintos.");
            // }

            foreach ($list as $i => $value) {
                if ($lastBid = $this->julgamentoItemService->obterLance($value->pc21_orcamforne, $value->pc22_orcamitem)) {
                    $value->l32_lance = $lastBid->l32_lance;
                } else {
                    $value->l32_lance = null;
                }
            }

            return JsonResource::collection($list)->additional([
                'nextBid' => $this->julgamentoItemService->obterProximoFornecedorParaLance($codigoLicitacao, $codigoLicitacaoItem, null, $param->l13_clapercent, $ignorarCache),
            ]);

        } catch (JulgamentoException $e) {

            return response()->json(['error' => '#JU222: ' . StringHelper::toUtf8($e->getMessage())], 400);

        } catch (\Exception $e) {

            return response()->json(['error' => '#JU223: Erro inesperado.'], 500);

        }
    }

    private function obterListaDeFornecedoresEPropostaDeLicitacaoDosLotes($codigoLicitacao, $codigoNumerolote) 
    {
        try {

            $param = $this->faseDeLancesService->obterParametros();
            $ignorarCache = request()->get('ignorarCache', false);

            $list = $this->julgamentoLoteService->obterListaDeFornecedoresEProposta($codigoLicitacao, $codigoNumerolote, $param->l13_clapercent, $ignorarCache);

            if ($list->isEmpty()) {
                throw new JulgamentoException("Não encontrado propostas do fornecedores.");
            }

            if ($list->count() < 3) {
                throw new JulgamentoException("O pregão não pode ser iniciado com menos de três propostas classificadas de fornecedores distintos.");
            }

            foreach ($list as $i => $value) {
                if ($lastBid = $this->julgamentoLoteService->obterLance($value->pc21_orcamforne, $codigoNumerolote)) {
                    $value->l32_lance = $lastBid->l32_lance;
                } else {
                    $value->l32_lance = null;
                }
            }

            return JsonResource::collection($list)->additional([
                'nextBid' => $this->julgamentoLoteService->obterProximoFornecedorParaLance($codigoLicitacao, $codigoNumerolote, null, $param->l13_clapercent, $ignorarCache),
            ]);

        } catch (JulgamentoException $e) {

            return response()->json(['error' => '#JU224: ' . StringHelper::toUtf8($e->getMessage())], 400);

        } catch (\Exception $e) {

            return response()->json(['error' => '#JU225: Erro inesperado.'], 500);

        }
    }

    /**
     * Finaliza o julgamento de uma licitação e retorna o status.
     * Em caso de falha, lança exceções personalizadas ou genéricas conforme o tipo de erro.
     * 
     * @param FinalizarRequest $request A requisição com os dados para finalizar o julgamento.
     * 
     * @return \Illuminate\Http\JsonResponse Resposta com a mensagem de sucesso ou erro.
     * 
     * @throws JulgamentoException Se ocorrer erro no processo de finalização.
     * @throws \Exception Se ocorrer erro inesperado.
     */
    public function finalizar(FinalizarRequest $request)
    {
        $validated = $request->validated();

        try {

            if ($validated['tipoJulg'] == 1) {

                $finalizar = $this->julgamentoItemService->finalizar(
                    $validated['licitacaoCodigo'],
                    $validated['licitacaoItemCodigo'],
                    $validated['orcamentoItemCodigo']
                );

            } else if ($validated['tipoJulg'] == 3) {

                $finalizar = $this->julgamentoLoteService->finalizar(
                    $validated['numeroLoteCodigo']
                );
                
            }

            return response()->json([
                'message' => 'Julgamento finalizado com sucesso.',
                'finalizar' => $finalizar,
            ], 200);

        } catch (JulgamentoException $e) {

            return response()->json(['error' => StringHelper::toUtf8($e->getMessage())], 400);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Erro inesperado.'], 500);

        }
    }

    /**
     * Registra um lance para um item específico.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registrarLance(RegistrarLanceRequest $request)
    {
        $validated = $request->validated();

        try {

            $param = $this->faseDeLancesService->obterParametros();
            $ignorarCache = request()->get('ignorarCache', false);

            if ($validated['tipoJulg'] == 1) {

                $julgLance = $this->julgamentoItemService->registrarLance(
                    $validated['licitacaoCodigo'],
                    $validated['licitacaoItemCodigo'],
                    $validated['fornecedorCodigo'],
                    $validated['valorLance'],
                    $validated['orcamentoItemCodigo']
                );

                return response()->json([
                    'message' => 'Lance registrado com sucesso.',
                    'julgLance' => $julgLance,
                    'nextBid' => $this->julgamentoItemService->obterProximoFornecedorParaLance($validated['licitacaoCodigo'], $validated['licitacaoItemCodigo'], null, $param->l13_clapercent, $ignorarCache)
                ], 200);

            } else if ($validated['tipoJulg'] == 3) {

                $julgLance = $this->julgamentoLoteService->registrarLance(
                    $validated['licitacaoCodigo'],
                    $validated['fornecedorCodigo'],
                    $validated['valorLance'],
                    $validated['numeroLoteCodigo']
                );

                return response()->json([
                    'message' => 'Lance registrado com sucesso.',
                    'julgLance' => $julgLance,
                    'nextBid' => $this->julgamentoLoteService->obterProximoFornecedorParaLance($validated['licitacaoCodigo'], $validated['numeroLoteCodigo'], null, $param->l13_clapercent, $ignorarCache)
                ], 200);

            }

        } catch (JulgamentoException $e) {

            return response()->json(['error' => StringHelper::toUtf8($e->getMessage())], 400);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Erro inesperado.'], 500);

        }
    }

    /**
     * Registra um lance como "sem valor" para um item específico.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registrarLanceSemValor(RegistrarLanceSemValorRequest $request)
    {
        $validated = $request->validated();

        try {

            $param = $this->faseDeLancesService->obterParametros();
            $ignorarCache = request()->get('ignorarCache', false);

            if ($validated['tipoJulg'] == 1) {

                $julgLance = $this->julgamentoItemService->registrarLance(
                    $validated['licitacaoCodigo'], 
                    $validated['licitacaoItemCodigo'],
                    $validated['fornecedorCodigo'],
                    null,
                    $validated['orcamentoItemCodigo']
                );

                return response()->json([
                    'message' => 'Lance registrado como "sem valor" com sucesso.',
                    'julgLance' => $julgLance,
                    'nextBid' => $this->julgamentoItemService->obterProximoFornecedorParaLance($validated['licitacaoCodigo'], $validated['licitacaoItemCodigo'], null, $param->l13_clapercent, $ignorarCache)
                ], 200);

            } else if ($validated['tipoJulg'] == 3) {

                $julgLance = $this->julgamentoLoteService->registrarLance(
                    $validated['licitacaoCodigo'],
                    $validated['fornecedorCodigo'],
                    null,
                    $validated['numeroLoteCodigo']
                );

                return response()->json([
                    'message' => 'Lance registrado com sucesso.',
                    'julgLance' => $julgLance,
                    'nextBid' => $this->julgamentoLoteService->obterProximoFornecedorParaLance($validated['licitacaoCodigo'], $validated['numeroLoteCodigo'], null, $param->l13_clapercent, $ignorarCache)
                ], 200);
                
            }

        } catch (JulgamentoException $e) {

            return response()->json(['error' => StringHelper::toUtf8($e->getMessage())], 400);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Erro inesperado.'], 500);

        }
    }

    /**
     * Reverte um lance já registrado para um fornecedor e item específico.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reverterLance(ReverterLanceRequest $request)
    {
        $validated = $request->validated();

        try {

            if ($validated['tipoJulg'] == 1) {
                $this->julgamentoItemService->reverterLance(
                    $validated['orcamentoItemCodigo']
                );
            } else if ($validated['tipoJulg'] == 3) {
                $this->julgamentoLoteService->reverterLance(
                    $validated['numeroLoteCodigo']
                );
            }

            return response()->json([
                'message' => 'Lance revertido com sucesso.',
            ], 200);

        } catch (JulgamentoException $e) {

            return response()->json(['error' => StringHelper::toUtf8($e->getMessage())], 400);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Erro inesperado.'], 500);

        }
    }

    /**
     * Remove todos os lances associados a um item específico.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function limparLances(LimparLancesRequest $request)
    {
        $validated = $request->validated();

        try {

            if ($validated['tipoJulg'] == 1) {

                $this->julgamentoItemService->limparLances(
                    $validated['orcamentoItemCodigo']
                );

            } else if ($validated['tipoJulg'] == 3) {

                $this->julgamentoLoteService->limparLances(
                    $validated['numeroLoteCodigo']
                );

            }

            return response()->json([
                'message' => 'Lances limpos com sucesso.',
            ], 200);

        } catch (JulgamentoException $e) {

            return response()->json(['error' => StringHelper::toUtf8($e->getMessage())], 400);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Erro inesperado.'], 500);

        }
    }

    /**
     * Altera o status de um fornecedor no julgamento com motivo detalhado.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function alterarStatusFornecedor(AlterarStatusFornecedorRequest $request)
    {
        $validated = $request->validated();

        try {

            if ($validated['tipoJulg'] == 1) {

                $this->julgamentoItemService->atualizaStatusFornecedor(
                    $validated['itemCodigo'],
                    $validated['fornecedorCodigo'],
                    $validated['fornecedorCategoria'],
                    $validated['fornecedorMotivo']
                );

            } else if ($validated['tipoJulg'] == 3) {

                $this->julgamentoLoteService->atualizaStatusFornecedor(
                    $validated['itemCodigo'],
                    $validated['fornecedorCodigo'],
                    $validated['fornecedorCategoria'],
                    $validated['fornecedorMotivo']
                );
                
            }

            return response()->json(['message' => 'Status do fornecedor alterado com sucesso.']);

        } catch (JulgamentoException $e) {

            return response()->json(['error' => StringHelper::toUtf8($e->getMessage())], 400);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Erro inesperado.'], 500);

        }
    }

    /**
     * Valida os fornecedores com modelo de microempresa para a licitação e item informados.
     * 
     * @param int $liclicita Código da licitação.
     * @param int $liclicitem Código do item da licitação.
     * 
     * @return \Illuminate\Http\Resources\Json\JsonResource Lista de fornecedores.
     * 
     * @throws JulgamentoException Se falhar ao obter a lista.
     * @throws \Exception Se ocorrer erro inesperado.
     */
    public function validarModeloEmpresarialDosFornecedores($liclicita, $liclicitem, Request $request)
    {

        $tipojulg = $request->query('tipojulg');

        try {
            
            if ($tipojulg == 1) {

                $param = $this->faseDeLancesService->obterParametros();
                $listaDeFornecedoresComModeloDeMicroEmpresa = $this->julgamentoItemService->obterFornecedoresComModeloDeMicroempresa($liclicita, $liclicitem, $param->l13_clapercent);
                return JsonResource::collection($listaDeFornecedoresComModeloDeMicroEmpresa);

            } else if ($tipojulg == 3) {

                $param = $this->faseDeLancesService->obterParametros();
                $listaDeFornecedoresComModeloDeMicroEmpresa = $this->julgamentoLoteService->obterFornecedoresComModeloDeMicroempresa($liclicita, $liclicitem, $param->l13_clapercent);
                return JsonResource::collection($listaDeFornecedoresComModeloDeMicroEmpresa);

            }

        } catch (JulgamentoException $e) {

            return response()->json(['error' => StringHelper::toUtf8($e->getMessage())], 400);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Erro inesperado.'], 500);

        }
    }

    /**
     * Libera microempresas para participação em uma licitação e item específicos.
     * 
     * @param LiberarMicroEmpresasRequest $request A requisição com os dados para liberar as microempresas.
     * 
     * @return \Illuminate\Http\JsonResponse Resposta com a mensagem de sucesso ou erro.
     * 
     * @throws JulgamentoException Se ocorrer erro ao liberar as microempresas.
     * @throws \Exception Se ocorrer erro inesperado.
     */
    public function liberarMicroEmpresas(LiberarMicroEmpresasRequest $request) 
    {
        $validated = $request->validated();

        try {

            if ($validated['tipoJulg'] == 1) {

                $param = $this->faseDeLancesService->obterParametros();
                $this->julgamentoItemService->liberarMicroEmpresas($validated['codigoLicitacao'], $validated['codigoLicitacaoItem'], $param->l13_clapercent);

            } else if ($validated['tipoJulg'] == 3) {

                $param = $this->faseDeLancesService->obterParametros();
                $this->julgamentoLoteService->liberarMicroEmpresas($validated['codigoLicitacao'], $validated['numeroLote'], $param->l13_clapercent);

            }

            return response()->json([
                'message' => 'Lance revertido com sucesso.',
                'liberarMicroEmpresas' => true
            ], 200);

        } catch (JulgamentoException $e) {

            return response()->json(['error' => StringHelper::toUtf8($e->getMessage())], 400);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Erro inesperado.'], 500);

        }
    }
}
