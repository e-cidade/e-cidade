<?php

namespace App\Services\Licitacao\Sicom;

use App\Services\Licitacao\Sicom\Ano2025\ArquivoIdeService;
use App\Services\Licitacao\Sicom\Ano2025\ArquivoAberlicService;
use App\Services\Licitacao\Sicom\Ano2025\ArquivoConsidService;
use App\Services\Licitacao\Sicom\Ano2025\ArquivoDispensaService;
use App\Services\Licitacao\Sicom\Ano2025\ArquivoJulglicService;
use App\Services\Licitacao\Sicom\Ano2025\ArquivoParelicService;
use App\Services\Licitacao\Sicom\Ano2025\ArquivoPartlicService;
use App\Services\Licitacao\Sicom\Ano2025\ArquivoResplicService;
use App\Services\Licitacao\Sicom\Ano2025\ArquivoEditalAnexoService;
use App\Services\Licitacao\Sicom\Ano2025\ArquivoHablicService;
use App\Services\Licitacao\Sicom\Ano2025\ArquivoHomolicService;
use App\Services\Licitacao\Sicom\Ano2025\ArquivoRegadesaoService;
use App\Services\Licitacao\Sicom\Ano2025\ArquivoReglicService;
use db_utils;
use Exception;

require_once 'dbforms/db_funcoes.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';



use Illuminate\Database\Capsule\Manager as DB;
use ZipArchive;

class SicomManagerService
{

    private $services;

    // Modificado para aceitar um array de serviços no construtor
    public function __construct()
    {
        $this->services = ['IDE' => new ArquivoIdeService(),'ABERLIC' => new ArquivoAberlicService(),'PARELIC' => new ArquivoParelicService(),'RESPLIC' => new ArquivoResplicService(),
                           'PARTLIC' => new ArquivoPartlicService(), 'JULGLIC' => new ArquivoJulglicService(), 'DISPENSA' => new ArquivoDispensaService(), 'CONSID' => new ArquivoConsidService(),
                           'REGLIC' => new ArquivoReglicService(), 'HOMOLIC' => new ArquivoHomolicService(), 'HABLIC' => new ArquivoHablicService(),'REGADESAO' => new ArquivoRegadesaoService()];
    }

    public function gerarArquivos($request)
    {

        parse_str($request->getContent(), $params);
        $processos = json_decode($params['processos'], true);
        $arquivosSelecionados = json_decode($params['arquivos'], true);
        $array_licitacao = [];
        $array_adesao = [];

        // Loop para percorrer o array original e separar os itens
        foreach ($processos as $processo) {
            
            if (isset($processo['l227_licitacao'])) {
                $array_licitacao[] = $processo;
                continue;
            } 
            
            $array_adesao[] = $processo;
        }

        $licitacoes = implode(",", array_map(function($processo) {
            return $processo['l227_licitacao'];
        }, $array_licitacao));

        // Validação Julgamento
        if(!empty($licitacoes)){
            $validacaoJulgamento = DB::select("select * from liclicita where l20_codigo in ($licitacoes) and l20_statusenviosicom = 2 and l20_licsituacao = 0");
            if (!empty($validacaoJulgamento)) {
                throw new Exception("Usuário: A situação das licitações [$licitacoes] estão em andamento. É necessário incluir o julgamento da licitação no sistema para, em seguida, gerar os arquivos necessários para envio ao SICOM.");
            }

        }

        // Validação Homologacao
        if(!empty($licitacoes)){
            $validacaoHomologacao = DB::select("select * from liclicita where l20_codigo in ($licitacoes) and l20_statusenviosicom = 3 and l20_licsituacao != 10");
            if (!empty($validacaoHomologacao)) {
                throw new Exception("Usuário: As licitações [$licitacoes] não estão homologadas.  É necessário incluir a homologação da licitação no sistema para, em seguida, gerar os arquivos necessários para envio ao SICOM.");
            }

        }

        $adesoes = implode(",", array_map(function($adesao) {
            return $adesao['l227_adesao'];
        }, $array_adesao));

        $remessa = json_decode($params['remessa'], true);

        $dadosNomeZip = DB::select(
            "
            SELECT 
                db21_codigomunicipoestado AS codmunicipio,
                CASE 
                    WHEN si09_tipoinstit::varchar = '2' THEN cgc::varchar 
                    ELSE si09_cnpjprefeitura::varchar 
                END AS cnpjmunicipio,
                si09_tipoinstit AS tipoorgao,
                si09_codorgaotce AS codorgao
            FROM db_config
            LEFT JOIN public.infocomplementaresinstit 
                ON si09_instit = " . db_getsession("DB_instit") . "
            WHERE codigo = " . db_getsession("DB_instit")
        );

        $tiposOrgaos = ["50","51","52","53","54","55","56","57","58"];

        $dadosNomeZip = $dadosNomeZip[0] ?? null; 
        $exercicioReferencia = db_getsession("DB_anousu");

        $prefixoZero = in_array($dadosNomeZip->tipoorgao, $tiposOrgaos) ? "" : "0";

        $filePathEditalAnexo = "";

        $filePaths = [];
        $filePathZip = '/tmp/' . "EDITAL_$prefixoZero{$dadosNomeZip->codmunicipio}_$prefixoZero{$dadosNomeZip->codorgao}_{$exercicioReferencia}.zip";

        if (file_exists($filePathZip)) {
            unlink($filePathZip);
        }

        $zip = new ZipArchive();
        $zip->open($filePathZip, ZipArchive::CREATE);
        foreach ($this->services as $nomeArquivo => $service) {

            if (in_array($nomeArquivo, $arquivosSelecionados)) {
                $sequenciais = $nomeArquivo == "REGADESAO" ? $adesoes : $licitacoes;
                $service->gerarArquivo($sequenciais,$remessa);
                array_push($filePaths,$nomeArquivo);
                $csvFile = "$nomeArquivo.csv";
                $zip->addFile($csvFile, $csvFile);
            }

        }


        $zip->close();

        if (!empty(array_intersect($arquivosSelecionados, ["ABERLIC","DISPENSA","JULGLIC","REGADESAO"]))) {
            $arquivoEditalAnexoService = new ArquivoEditalAnexoService();
            $filePathEditalAnexo = $arquivoEditalAnexoService->gerarZip($licitacoes,$adesoes);
        }
      
        return [
            'csv' => $filePaths,
            'zip' => $filePathZip,
            'nomeZip' => "EDITAL_$prefixoZero{$dadosNomeZip->codmunicipio}_$prefixoZero{$dadosNomeZip->codorgao}_{$exercicioReferencia}.zip",
            'edital' => $filePathEditalAnexo
        ];

    }
}
