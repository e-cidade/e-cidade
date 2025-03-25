<?php

namespace App\Http\Controllers\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances;

use App\Exceptions\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\JulgamentoException;
use App\Helpers\StringHelper;
use App\Http\Controllers\DataGridController;
use App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\FinalizarRequest;
use App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\Julgamento\AlterarStatusItemRequest;
use App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\Julgamento\LimparLancesRequest;
use App\Models\Patrimonial\Licitacao\Liclicita;
use App\Models\Patrimonial\Licitacao\Liclicitem;
use App\Repositories\Patrimonial\Licitacao\LicilicitemRepository;
use App\Services\Patrimonial\Licitacao\Procedimentos\JulgamentoPorLance\FaseDeLancesService;
use App\Services\Patrimonial\Licitacao\Procedimentos\JulgamentoPorLance\JulgamentoItemService;
use App\Services\Patrimonial\Licitacao\Procedimentos\JulgamentoPorLance\JulgamentoLoteService;
use App\Traits\ValidatesControllerProperties;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class FaseDeLancesController extends DataGridController
{
    use ValidatesControllerProperties;

    protected $julgamentoItemService;
    protected $faseDeLancesService;
    protected $julgamentoLoteService;

    private $columns = [
        ['label' => 'Codigo',        'name' => 'l20_codigo'],
        ['label' => 'Processo',      'name' => 'l20_edital'],
        ['label' => 'Exercicio',     'name' => 'l20_anousu'],
        ['label' => 'Modalidade',    'name' => 'l03_descr'],
        ['label' => 'Numero',        'name' => 'l20_numero'],
        ['label' => 'Edital',        'name' => 'l20_nroedital'],
        ['label' => 'Objeto',        'name' => 'l20_objeto'],
        ['label' => '',              'name' => 'l20_objeto as l20_objeto_full'],
        ['label' => '',              'name' => 'l20_mododisputa']
    ];

    /**
     * Construtor do controller para injeção de dependência do JulgamentoService.
     *
     * @param FaseDeLancesService $faseDeLancesService
     */
    public function __construct(
        FaseDeLancesService $faseDeLancesService,
        JulgamentoItemService $julgamentoItemService,
        JulgamentoLoteService $julgamentoLoteService,
        LicilicitemRepository $licilicitemRepository
    )
    {
        $this->validateRequiredProperties();
        $this->faseDeLancesService = $faseDeLancesService;
        $this->julgamentoItemService = $julgamentoItemService;
        $this->julgamentoLoteService = $julgamentoLoteService;
        $this->licilicitemRepository = $licilicitemRepository;
    }

    /**
     * Exibe a página principal de Fase de Lances.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $statusItens = $this->faseDeLancesService->obterStatusDosItens();
        $param = $this->faseDeLancesService->obterParametros();

        $statusItensLabels = [];

        foreach ($statusItens as $key => $statusItem) {
            $key++;
            if ($statusItem['l31_codigo'] !== 2 && $statusItem['l31_codigo'] !== 7) {
                $statusItensLabels[$key] = $statusItem['l31_label'];
            }
        }

        $redirect = request('redirect') ?? null;

        return view()->file(base_path('resources/legacy/licitacao/lic_fasedelance.php'), [
            "columns" => $this->columns,
            "param" => $param,
            "statusItensLabels" => $statusItensLabels,
            "redirect" => $redirect
        ]);
    }

    /**
     * Recupera os dados de Licitação para o grid.
     *
     * @param Liclicita $model
     * @param Request $request
     * @return JsonResource
     */
    public function getLiclicita(Liclicita $model, Request $request) : JsonResource
    {
        $request = $request->merge([
            'filter' => $request->input('filter', 'l20_lances:true,l20_instit:' . Session::get('DB_instit')),
            'order' => $request->input('order', 'l20_codigo'),
            'dir' => $request->input('dir', 'desc'),
        ]);

        $query = $model->newQuery();
        $columns = $this->columns($request, $query, $this->columns);

        $this->includeColumns($columns, $query);

        $query->addSelect(DB::raw("CASE WHEN LENGTH(l20_objeto) > 50 THEN CONCAT(SUBSTRING(l20_objeto, 1, 100), ' ...') ELSE l20_objeto END as l20_objeto"));
        $this->include($request, $query);
        $query->join('licitacao.cflicita', 'liclicita.l20_codtipocom', '=', 'licitacao.cflicita.l03_codigo');
        $this->order($request, $query);
        $page = $request->query('page', 1);
        $show = $request->query('show', $query->getModel()->getPerPage());
        $this->filter($query, $request, $this->columns);

        return $this->newCollection(
            $query->paginate($show, $page),
            $this->columns
        );
    }

    /**
     * Recupera os itens da Licitação para o grid.
     *
     * @param Liclicitem $model
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function getLiclicitaItens(Liclicitem $model, Request $request)
    {
        $columns = [
            ['label' =>  StringHelper::toUtf8('Sequencial'), 'name' => 'l21_ordem'],
            ['label' =>  '', 'name' => 'pc22_orcamitem'],
            ['label' =>  StringHelper::toUtf8('Codigo'), 'name' => 'pc01_codmater'],
            ['label' =>  StringHelper::toUtf8('Descricao'), 'name' => 'pc01_descrmater'],
            ['label' =>  '', 'name' => 'pc01_complmater'],
            ['label' =>  StringHelper::toUtf8('Status'), 'name' => 'l31_label'],
        ];

        // Inicia uma nova consulta no modelo Liclicitem
        $query = $model->newQuery();

        $columnsNames = $this->columns($request, $query, $columns);
        $this->includeColumns($columnsNames, $query);

        $query->join('compras.pcprocitem', 'pcprocitem.pc81_codprocitem', '=', 'licitacao.liclicitem.l21_codpcprocitem')
            ->join('compras.solicitem', 'solicitem.pc11_codigo', '=', 'pcprocitem.pc81_solicitem')
            ->join('compras.solicitempcmater', 'solicitempcmater.pc16_solicitem', '=', 'solicitem.pc11_codigo')
            ->join('compras.pcmater', 'pcmater.pc01_codmater', '=', 'solicitempcmater.pc16_codmater')
            ->join('licitacao.pcorcamitemlic', 'pcorcamitemlic.pc26_liclicitem', '=', 'liclicitem.l21_codigo')
            ->join('compras.pcorcamitem', 'pcorcamitem.pc22_orcamitem', '=', 'pcorcamitemlic.pc26_orcamitem')
            ->leftJoin('licitacao.julgitem', 'julgitem.l30_orcamitem', '=', 'pcorcamitem.pc22_orcamitem')
            ->leftJoin('licitacao.julgitemstatus', 'julgitemstatus.l31_codigo', '=', 'julgitem.l30_julgitemstatus');

        $this->order($request, $query);

        $page = $request->query('page', 1);
        $show = $request->query('show', $query->getModel()->getPerPage());

        $this->filter($query, $request, $columns);

        $collection = $query->paginate($show, ['*'], 'page', $page);

        $collection->getCollection()->transform(function ($item) {
            if (empty($item->l31_label)) {
                $item->l31_label = 'Em aberto';
            }
            return $item;
        });

        return $this->newCollection(
            $collection,
            $columns
        );
    }

    /**
     * Recupera os lotes da Licitação para o grid.
     *
     * @param Liclicitem $model
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function getLiclicitaLotes(Liclicitem $model, Request $request)
    {
        $columns = [
            ['label' => StringHelper::toUtf8('Identificador'), 'name' => 'l04_numerolote'],
            ['label' => StringHelper::toUtf8('Descricao'), 'name' => 'l04_descricao'],
            ['label' => StringHelper::toUtf8('Status'), 'name' => 'l31_label'],
        ];

        // Inicia uma nova consulta no modelo Liclicitem
        $query = $model->newQuery();

        $columnsNames = $this->columns($request, $query, $columns);
        $this->includeColumns($columnsNames, $query);

        $query->join('compras.pcprocitem', 'compras.pcprocitem.pc81_codprocitem', '=', 'licitacao.liclicitem.l21_codpcprocitem')
            ->join('compras.solicitem', 'compras.solicitem.pc11_codigo', '=', 'compras.pcprocitem.pc81_solicitem')
            ->join('compras.solicitempcmater', 'compras.solicitempcmater.pc16_solicitem', '=', 'compras.solicitem.pc11_codigo')
            ->join('compras.pcmater', 'compras.pcmater.pc01_codmater', '=', 'compras.solicitempcmater.pc16_codmater')
            ->join('licitacao.pcorcamitemlic', 'licitacao.pcorcamitemlic.pc26_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')
            ->join('compras.pcorcamitem', 'compras.pcorcamitem.pc22_orcamitem', '=', 'licitacao.pcorcamitemlic.pc26_orcamitem')
            ->join('licitacao.liclicitemlote', 'licitacao.liclicitemlote.l04_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')
            ->leftJoin('licitacao.julgitem', 'licitacao.julgitem.l30_numerolote', '=', 'licitacao.liclicitemlote.l04_numerolote')
            ->leftJoin('licitacao.julgitemstatus', 'licitacao.julgitemstatus.l31_codigo', '=', 'licitacao.julgitem.l30_julgitemstatus')
            ->groupBy('l04_numerolote', 'l04_descricao', 'l31_label');

        $this->order($request, $query);

        $page = $request->query('page', 1);
        $show = $request->query('show', $query->getModel()->getPerPage());

        $this->filter($query, $request, $columns);

        $collection = $query->paginate($show, ['*'], 'page', $page);

        $cont = 1;
        $collection->getCollection()->transform(function ($item) use (&$cont) {
            if (empty($item->l31_label)) {
                $item->l31_label = 'Em aberto';
            }
            return $item;
        });

        return $this->newCollection(
            $collection,
            $columns
        );
    }

    /**
     * Finaliza uma licitação de modo de disputa fechado e retorna o status.
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
        $param = $this->faseDeLancesService->obterParametros();

        try {

            if ($validated['tipoJulg'] == 1) {

                $fornecedoresEPropostas = $this->julgamentoItemService->obterListaDeFornecedoresEProposta($validated['licitacaoCodigo'], $validated['licitacaoItemCodigo'], $param->l13_clapercent, true);

                $this->julgamentoItemService->limparLances(
                    $fornecedoresEPropostas[0]->pc22_orcamitem
                );

                // $detalhesDaMenorOfertaDoFornecedor = $this->licilicitemRepository->getLowestBidDetailsForSupplier($validated['licitacaoCodigo'], $validated['licitacaoItemCodigo'], $fornecedorEProposta->pc21_orcamforne, $fornecedorEProposta->pc22_orcamitem);
                foreach ($fornecedoresEPropostas as $i => $fornecedorEProposta) {
                    $this->julgamentoItemService->registrarLance(
                        $validated['licitacaoCodigo'],
                        $validated['licitacaoItemCodigo'],
                        $fornecedorEProposta->pc21_orcamforne,
                        null,
                        $fornecedorEProposta->pc22_orcamitem
                    );
                }

                $finalizar = $this->julgamentoItemService->finalizar(
                    $validated['licitacaoCodigo'],
                    $validated['licitacaoItemCodigo'],
                    $fornecedoresEPropostas[0]->pc22_orcamitem,
                );

            } else if ($validated['tipoJulg'] == 3) {

                $fornecedoresEPropostas = $this->julgamentoLoteService->obterListaDeFornecedoresEProposta($validated['licitacaoCodigo'], $validated['numeroLoteCodigo'], $param->l13_clapercent, true);

                $this->julgamentoLoteService->limparLances(
                    $validated['numeroLoteCodigo']
                );

                foreach ($fornecedoresEPropostas as $i => $fornecedorEProposta) {
                    $this->julgamentoLoteService->registrarLance(
                        $validated['licitacaoCodigo'], 
                        $fornecedorEProposta,
                        null,
                        $validated['numeroLoteCodigo']
                    );
                }

                $finalizar = $this->julgamentoLoteService->finalizar(
                    $validated['tipoJulg'],
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
     * Altera o status de um item com motivo detalhado.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function alterarStatusItem(AlterarStatusItemRequest $request)
    {
        $validated = $request->validated();

        try {

            if ($validated['tipoJulg'] == 1) {

                $this->julgamentoItemService->alterarStatusItem(
                    $validated['ids'],
                    $validated['categorias'],
                    $validated['motivo']
                );

            } else if ($validated['tipoJulg'] == 3) {

                $this->julgamentoLoteService->alterarStatusItem(
                    $validated['ids'],
                    $validated['categorias'],
                    $validated['motivo']
                );

            }

            return response()->json(['message' => 'Status do item alterado com sucesso.']);

        } catch (JulgamentoException $e) {

            return response()->json(['error' => StringHelper::toUtf8($e->getMessage())], 400);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Erro inesperado.'], 500);

        }
    }

    /**
     * Limpa os lances de itens de orçamento e altera seu status.
     *
     * - Valida os dados da requisição.
     * - Chama os serviços para limpar lances e atualizar status.
     * - Retorna JSON com sucesso ou erro (400 para erros específicos, 500 para inesperados).
     *
     * @param LimparLancesRequest $request Dados validados da requisição.
     * @return \Illuminate\Http\JsonResponse Resultado da operação.
     */
    public function limparLances(LimparLancesRequest $request)
    {
        $validated = $request->validated();

        try {

            if ($validated['tipoJulg'] == 1) {

                $this->julgamentoItemService->limparLances(
                    $validated['orcamentoItemCodigo']
                );

                
                $this->julgamentoItemService->alterarStatusItem(
                    $validated['orcamentoItemCodigo'],
                    1,
                    mb_convert_encoding('O status do item foi alterado para em aberto após a remoção dos lances.', 'UTF-8', 'ISO-8859-1')
                );

            } else if ($validated['tipoJulg'] == 3) {

                $this->julgamentoLoteService->limparLances(
                    $validated['numeroLoteCodigo']
                );

                
                $this->julgamentoLoteService->alterarStatusItem(
                    $validated['numeroLoteCodigo'],
                    1,
                    mb_convert_encoding('O status do item foi alterado para em aberto após a remoção dos lances.', 'UTF-8', 'ISO-8859-1')
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
}
