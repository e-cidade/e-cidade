<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Patrimonial\Licitacao\Liclicita;
use App\Models\Patrimonial\Licitacao\Remessasicom;
use App\Services\Licitacao\Sicom\SicomLicitacaoService;
use App\Services\Licitacao\Sicom\SicomManagerService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Capsule\Manager as DB;

class SicomLicitacaoController extends Controller
{
    protected $sicomLicitacaoService;

    public function __construct(SicomLicitacaoService $sicomLicitacaoService)
    {
        $this->sicomLicitacaoService = $sicomLicitacaoService;
    }

    public function getProcessos(Request $request)
    {

        $agrupamento = $request->input('agrupamento');

        try {
            $processos = $this->sicomLicitacaoService->getProcessos($agrupamento);

            return response()->json(['responseMessage' => mb_convert_encoding('Processos obtidos com sucesso.', 'UTF-8', 'ISO-8859-1'), 'processos' => $processos], 200);
        } catch (Exception $e) {
            return response()->json(['responseMessage' => $e->getMessage()], 500);
        }
    }

    public function getCodigoRemessa()
    {
        try {
            $remessa = $this->sicomLicitacaoService->getCodigoRemessa();
            return response()->json(['responseMessage' => mb_convert_encoding('Código de Remessa gerado com sucesso.', 'UTF-8', 'ISO-8859-1'), 'remessa' => $remessa], 200);
        } catch (Exception $e) {
            return response()->json(['responseMessage' => $e->getMessage()], 500);
        }
    }

    public function validacaoCadastroInicial(Request $request){
        try {
            $this->sicomLicitacaoService->validacaoCadastroInicial($request);
            return response()->json(['responseMessage' => mb_convert_encoding('Validacação cadastro inicial concluida com sucesso.', 'UTF-8', 'ISO-8859-1')], 200);
        } catch (Exception $e) {
            return response()->json(['responseMessage' => mb_convert_encoding($e->getMessage(),'UTF-8','ISO-8859-1')], 500);
        }
    }

    public function gerarArquivos(Request $request)
    {
        $sicomManagerService = new SicomManagerService();
        try {
             $urlArquivos = $sicomManagerService->gerarArquivos($request);
            return response()->json(['responseMessage' => mb_convert_encoding('Arquivo(s) gerado(s)', 'UTF-8', 'ISO-8859-1'),'urlArquivos' => $urlArquivos], 200);
        } catch (Exception $e) {
            return response()->json(['responseMessage' => mb_convert_encoding($e->getMessage(),'UTF-8','ISO-8859-1')], 500);
        }
    }

    public function salvarRemessa(Request $request){

        try {
            $this->sicomLicitacaoService->salvarRemessa($request);
            return response()->json(['responseMessage' => mb_convert_encoding('Remessa salva.', 'UTF-8', 'ISO-8859-1')], 200);
        } catch (Exception $e) {
            return response()->json(['responseMessage' => $e->getMessage()], 500);
        }

    }
}
