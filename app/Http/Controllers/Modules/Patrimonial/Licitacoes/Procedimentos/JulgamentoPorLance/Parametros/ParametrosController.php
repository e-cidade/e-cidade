<?php

namespace App\Http\Controllers\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\Parametros;

use App\Exceptions\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\JulgamentoException;
use App\Helpers\StringHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\Julgamento\ParametrosLancesRequest;
use App\Services\Patrimonial\Licitacao\Procedimentos\JulgamentoPorLance\FaseDeLancesService;
use Illuminate\Support\Facades\Session;

class ParametrosController extends Controller
{
    protected $julgamentoService;

    /**
     * Construtor do controller para injeção de dependência do JulgamentoService.
     *
     * @param JulgamentoService $julgamentoService
     */
    public function __construct(
        FaseDeLancesService $faseDeLancesService
    )
    {
        $this->faseDeLancesService = $faseDeLancesService;
    }

    /**
     * Exibe a página principal de Parametros.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $param = $this->faseDeLancesService->obterParametros();

        if (!$param) {
            $param = [
                'l13_instit' => Session::get('DB_instit'),
                'l13_precoref' => [],
                'l13_difminlance' => number_format(0, 2, ',', '.'),
                'l13_clapercent' => number_format(0, 2, ',', '.')
            ];
        } else {
            $param['l13_difminlance'] = number_format($param['l13_difminlance'], 2, ',', '.');
            $param['l13_clapercent'] = number_format($param['l13_clapercent'], 2, ',', '.');
        }

        return view()->file(base_path('resources/legacy/licitacao/lic_fasedelance004.php'), ['param' => $param]);
    }

    /**
     * Atualiza os parâmetros do julgamento.
     *
     * Esta função valida os dados recebidos por meio do request,
     * chama o serviço de julgamento para alterar os parâmetros e
     * retorna uma resposta apropriada dependendo do resultado da operação.
     *
     * @param ParametrosLancesRequest $request Objeto contendo os parâmetros validados da solicitação.
     *
     * @return \Illuminate\Http\JsonResponse Retorna uma resposta JSON:
     * - Em caso de sucesso, uma mensagem indicando que os parâmetros foram atualizados.
     * - Em caso de exceção específica de julgamento, um erro com código 400 e a mensagem da exceção.
     * - Em caso de erro inesperado, um erro genérico com código 500.
     *
     * @throws JulgamentoException Lançada quando ocorre uma falha específica na lógica de julgamento.
     * @throws \Exception Lançada para erros inesperados não tratados especificamente.
     */
    public function update(ParametrosLancesRequest $request)
    {
        $validated = $request->validated();

        try {

            if ($validated['l13_clapercent'] == 0) {
                $validated['l13_clapercent'] = null;
            }

            $this->faseDeLancesService->alterarParametro($validated);

            return response()->json(['message' => StringHelper::toUtf8('Parâmetros do julgamentos alterado com sucesso.')]);

        } catch (JulgamentoException $e) {

            return response()->json(['error' => $e->getMessage()], 400);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Erro inesperado.'], 500);

        }
    }
}
