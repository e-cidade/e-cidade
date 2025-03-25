<?php

namespace App\Http\Controllers\Modules\Configuracao\Configuracao\Procedimentos\ManutencaoDeDados\ManutencaoLancamentosPatrimonial\ControleDeDatas;

use App\Http\Controllers\Controller;
use App\Services\Configuracao\Empenho\EmpAutorizaService;
use Exception;
use Illuminate\Http\Request;

class EmpAutorizaController extends Controller
{
    protected $empAutorizaService;

    public function __construct(EmpAutorizaService $empAutorizaService)
    {
        $this->empAutorizaService = $empAutorizaService;
    }

    public function index()
    {
        return view()->file(base_path('resources/legacy/materiais/m4_datasautorizacaoempenho.php'));
    }

    public function getByPrimaryKeyRange($iCodigoEmpenhoInicial, $iCodigoEmpenhoFinal)
    {
        try {
            $autorizacoes = $this->empAutorizaService->getByPrimaryKeyRange($iCodigoEmpenhoInicial, $iCodigoEmpenhoFinal);

            return response()->json(['responseMessage' => mb_convert_encoding('Autorizações obtidas com sucesso.', 'UTF-8', 'ISO-8859-1'), 'autorizacoes' => $autorizacoes], 200);
        } catch (Exception $e) {
            return response()->json(['responseMessage' => $e->getMessage()], 500);
        }
    }

    public function updateDateByIds(Request $request)
    {
        try {
            $dadosJson = $request->input('dados');

            // Limpeza da string JSON (remove caracteres indesejados)
            $dadosJson = trim($dadosJson, '^"');

            // Remove barras de escape
            $dadosJson = stripslashes($dadosJson);

            // Decodificar o JSON
            $dados = json_decode($dadosJson, true);

            $this->empAutorizaService->updateDateByIds($dados);

            return response()->json(['responseMessage' => mb_convert_encoding('Usuário: Data(s) atualizada(s) com sucesso', 'UTF-8', 'ISO-8859-1')], 200);
        } catch (\Exception $e) {
            return response()->json(['responseMessage' => 'Erro ao atualizar registros: '.$e->getMessage()], 500);
        }
    }
}
