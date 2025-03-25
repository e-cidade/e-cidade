<?php

namespace App\Services\Patrimonial\Licitacao;

use cl_cgm;
use cl_db_documento;
use cl_homologacaoadjudica;
use cl_liclicita;
use db_utils;
use libdocumento;

include("libs/db_libdocumento.php");

class DadosRelatorioHomologacaoAdjudicacaoService
{
    public $clHomologacaoAdjudica;
    private $clLiclicita;
    private $clDbDocumento;
    private $clCgm;

    public function __construct()
    {
        $this->clHomologacaoAdjudica = new cl_homologacaoadjudica;
        $this->clLiclicita =  new cl_liclicita();
        $this->clDbDocumento = new cl_db_documento();
        $this->clCgm = new cl_cgm();
    }

    public function obterDadosRelatorio($codigoLicitacao, $tipoRelatorio)
    {
        return [
            'datahomologacao' => implode('/',array_reverse(explode('-',$this->getLicitacaoInfo($codigoLicitacao)->l202_datahomologacao))),
            'dataadjudicacao' => implode('/',array_reverse(explode('-',$this->getDtAjudicacao($codigoLicitacao)->l202_dataadjudicacao))),
            'tipoJulgamento' => $this->getLicitacaoInfo($codigoLicitacao)->l20_tipojulg,
            'criterioAdjudicacao' => $this->getLicitacaoInfo($codigoLicitacao)->l20_criterioadjudicacao,
            'codigoHomologacao' => $this->getCodigoHomologacao($codigoLicitacao, $tipoRelatorio),
            'itens' => $this->getItens($codigoLicitacao, $tipoRelatorio),
            'valorTotal' => $this->getValorTotal($codigoLicitacao, $tipoRelatorio),
            'responsavel' => $this->getResponsavel($codigoLicitacao, $tipoRelatorio)->z01_nome,
            'paragrafosPreDefinidos' => $this->getParagrafosPreDefinidos($codigoLicitacao, $tipoRelatorio),
        ];
    }

    private function getLicitacaoInfo($codigoLicitacao)
    {
        $rsLicitacao = $this->clLiclicita->sql_record($this->clLiclicita->sql_query(null,"*",null,"l20_codigo = $codigoLicitacao order by l202_datahomologacao asc"));
        return db_utils::fieldsMemory($rsLicitacao, 0);
    }

    private function getDtAjudicacao($codigoLicitacao)
    {
        $rsLicitacao = $this->clLiclicita->sql_record($this->clLiclicita->sql_query(null,"*",null,"l20_codigo = $codigoLicitacao order by l202_dataadjudicacao desc"));
        return db_utils::fieldsMemory($rsLicitacao, 0);
    }
    private function getModeloRelatorio($tipoRelatorio)
    {
        $modeloRelatorio = $tipoRelatorio === "Homologação" ? "HOMOLOGACAO RELATORIO" : "ADJUDICACAO RELATORIO";
        $rsModeloRelatorio = $this->clDbDocumento->sql_record($this->clDbDocumento->sql_query("", "*", "", "db03_descr like '$modeloRelatorio'"));
        return db_utils::fieldsMemory($rsModeloRelatorio, 0);
    }

    private function getCodigoHomologacao($codigoLicitacao, $tipoRelatorio)
    {
        if ($tipoRelatorio === "Homologação") {
            return $this->clHomologacaoAdjudica->getCodigoHomologacao($codigoLicitacao);
        }
        return null;
    }

    private function getItens($codigoLicitacao, $tipoRelatorio)
    {
        $codigoHomologacao = $this->getCodigoHomologacao($codigoLicitacao, $tipoRelatorio);
        $sSqlItens = $tipoRelatorio === "Homologação" ?
            $this->clHomologacaoAdjudica->sqlItensHomologados($codigoLicitacao, $codigoHomologacao) :
            $this->clHomologacaoAdjudica->sqlItensAdjudicados($codigoLicitacao);
        return $this->clHomologacaoAdjudica->sql_record($sSqlItens);
    }

    private function getValorTotal($codigoLicitacao, $tipoRelatorio)
    {
        $codigoHomologacao = $this->getCodigoHomologacao($codigoLicitacao, $tipoRelatorio);
        $valorTotal = $this->clHomologacaoAdjudica->getValorTotal($codigoLicitacao, $codigoHomologacao);
        return number_format($valorTotal, 2, ',', '.');
    }

    private function getResponsavel($codigoLicitacao, $tipoRelatorio)
    {
        $tipoResponsavel = $tipoRelatorio === "Homologação" ? 6 : 7;
        $rsResponsavel = $this->clHomologacaoAdjudica->sql_record($this->clHomologacaoAdjudica->sqlGetResponsavel($codigoLicitacao,$tipoResponsavel));
        return db_utils::fieldsMemory($rsResponsavel, 0);
    }

    private function getParagrafosPreDefinidos($codigoLicitacao, $tipoRelatorio)
    {
        $licitacao = $this->getLicitacaoInfo($codigoLicitacao);
        $responsavel = $this->getResponsavel($codigoLicitacao, $tipoRelatorio);
        $modeloRelatorio = $this->getModeloRelatorio($tipoRelatorio);

        $objParagrafosPreDefinidos = new libdocumento($modeloRelatorio->db03_tipodoc, null);
        $objParagrafosPreDefinidos->l20_edital = $licitacao->l20_edital;
        $objParagrafosPreDefinidos->l44_descricao = strtoupper($licitacao->l44_descricao);
        $objParagrafosPreDefinidos->l20_anousu = $licitacao->l20_anousu;
        $objParagrafosPreDefinidos->l20_objeto = $licitacao->l20_objeto;
        $objParagrafosPreDefinidos->l03_descr = $licitacao->l03_descr;
        $objParagrafosPreDefinidos->l20_numero = $licitacao->l20_numero;
        $objParagrafosPreDefinidos->z01_nome = $responsavel->z01_nome;
        $objParagrafosPreDefinidos->valor_total = $this->getValorTotal($codigoLicitacao, $tipoRelatorio);
        $objParagrafosPreDefinidos->z01_cpf = $responsavel->z01_cgccpf;
        $objParagrafosPreDefinidos->l44_descricao = strtoupper($licitacao->l44_descricao);

        return preg_replace('/^\s+/m', '', end($objParagrafosPreDefinidos->getDocParagrafos())->oParag->db02_texto);

    }
}
