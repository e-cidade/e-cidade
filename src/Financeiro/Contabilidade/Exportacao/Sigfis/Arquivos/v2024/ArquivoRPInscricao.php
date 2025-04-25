<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Arquivos\v2024;

use Illuminate\Database\Capsule\Manager as DB;
use stdClass;

class ArquivoRPInscricao extends ArquivoBase
{
    protected $sNomeArquivo = 'InscricaoRestosPagar';

    public function gerarDados()
    {
        $poste = $_POST["json"];
        $expl1ode = explode(":", $poste);
        $expl2ode = explode("\\\"", $expl1ode[2]);
        $arquivosdo = $expl2ode[1];
        if($arquivosdo == "0"){
            $this->competencia = $this->iAnoUsu . "00"; //"202400";
        }
        
        
        
        $anousuario = db_getsession("DB_anousu") - 1;
        $anousuario = $anousuario."-12-31";
        
        

        $campos = [
            'e60_numemp',
            'e60_anousu',
            'e91_anousu',
            'e60_codemp',
            'o58_orgao',
            'o58_unidade',

            'e91_vlremp', 'e91_vlranu', 'e91_vlrliq',
            'e91_vlrliq', 'e91_vlrpag'
        ];

        if($arquivosdo == "13"){
            $this->competencia = "202413";
            $anousuario = "2024-12-31";
            $empenhos = DB::table('empresto')
            ->select($campos)
            ->join('empenho.empempenho', 'e60_numemp', 'e91_numemp')
            ->join('orcamento.orcdotacao', function ($join) {
                $join->on('e60_anousu', 'o58_anousu')
                    ->on('e60_coddot', 'o58_coddot');
            })
            ->where('e60_anousu', $this->iAnoUsu)
            ->where('e60_instit', $this->instit)
            ->get();            

        }else{
            $empenhos = DB::table('empresto')
            ->select($campos)
            ->join('empenho.empempenho', 'e60_numemp', 'e91_numemp')
            ->join('orcamento.orcdotacao', function ($join) {
                $join->on('e60_anousu', 'o58_anousu')
                    ->on('e60_coddot', 'o58_coddot');
            })
            ->where('e91_anousu', $this->iAnoUsu)
            ->where('e60_instit', $this->instit)
            ->get();
            
        }

        

        if ($empenhos->isEmpty()) {
            throw new \Exception(sprintf(
                'Não foi encontrado nenhum registro para o arquivo da Remessa de %s',
                'Restos a Pagar - Inscrição'
            ));
        }

        $obj = new stdClass();
        $obj->InscricoesRestosPagar = [];



        foreach ($empenhos as $empenho) {
            $valorRPNP = round($empenho->e91_vlremp - $empenho->e91_vlranu - $empenho->e91_vlrliq, 2);
            $vaorRPP = round($empenho->e91_vlrliq - $empenho->e91_vlrpag, 2);

            $data  = (object) [
                'Identificador' => $empenho->e60_numemp,
                'CodigoUnidadeGestora' => $this->sCodigoTribunal,
                'CodigoOrgao' => $empenho->o58_orgao,
                'CodigoUnidadeOrcamentaria' => $empenho->o58_unidade,
                'Exercicio' => $this->iAnoUsu,
                'NumeroEmpenho' => $empenho->e60_codemp,
                'AnoEmpenho' => $empenho->e60_anousu,
                'DataInscricao' => $anousuario,
                'Competencia' => $this->competencia,
                'ValorRestosPagarProcessado' => $vaorRPP,
                'ValorRestosPagarNaoProcessado' => $valorRPNP,
                'Justificativa' => '',
            ];

            $obj->InscricoesRestosPagar[] = (object)[
                'InscricaoRestosPagar' => $data
            ];
        }

        $this->aDados =  $obj;
    }
}
