<?php

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
require_once("libs/JSON.php");

db_postmemory($_POST);

$oJson             = new services_json();
$oParametro            =  json_decode(str_replace('\\', '', $_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->erro  = '';

$clHabilitacaoForn = new cl_habilitacaoforn;

try {

  switch ($oParametro->sExecuta) {

    case "pesquisaTodosFornecedores":

        $rsFornecedores = $clHabilitacaoForn->sql_record($clHabilitacaoForn->sqlFornecedoresParaHabilitar($oParametro->codigoLicitacao));
        $oRetorno->fornecedores = pg_fetch_all($rsFornecedores);
        $rsFornecedores = $clHabilitacaoForn->sql_record( $clHabilitacaoForn->sql_query(null,"l206_sequencial,z01_numcgm,z01_nome",null,"l206_licitacao=$oParametro->codigoLicitacao"));
        $oRetorno->fornecedoresHabilitados = pg_fetch_all($rsFornecedores);
        break;

    case "pesquisaFornecedoresParaHabilitar":

        $rsFornecedores = $clHabilitacaoForn->sql_record($clHabilitacaoForn->sqlFornecedoresParaHabilitar($oParametro->codigoLicitacao));
        $oRetorno->fornecedores = pg_fetch_all($rsFornecedores);
        break;
    
    case "dadosFornecedorHabilitado":

        $campos = "l206_numcertidaoinss,l206_numcertidaofgts,l206_numcertidaocndt,to_char(l206_datahab,'DD/MM/YYYY') as l206_datahab,to_char(l206_dataemissaoinss,'DD/MM/YYYY') as l206_dataemissaoinss,to_char(l206_dataemissaofgts,'DD/MM/YYYY') as l206_dataemissaofgts,to_char(l206_dataemissaocndt,'DD/MM/YYYY') as l206_dataemissaocndt,to_char(l206_datavalidadeinss,'DD/MM/YYYY') as l206_datavalidadeinss,to_char(l206_datavalidadefgts,'DD/MM/YYYY') as l206_datavalidadefgts,to_char(l206_datavalidadecndt,'DD/MM/YYYY') as l206_datavalidadecndt";
        $rsFornecedores = $clHabilitacaoForn->sql_record( $clHabilitacaoForn->sql_query_file($oParametro->sequencialHabilitacao,$campos,null,""));
        $oRetorno->fornecedor = pg_fetch_all($rsFornecedores);
        break;

    case "alterarForncedorHabilitado":

        $clHabilitacaoForn->l206_numcertidaoinss = $oParametro->l206_numcertidaoinss;
        $clHabilitacaoForn->l206_numcertidaofgts = $oParametro->l206_numcertidaofgts;
        $clHabilitacaoForn->l206_numcertidaocndt = $oParametro->l206_numcertidaocndt;
        $clHabilitacaoForn->l206_datahab = implode('-', array_reverse(explode('/', $oParametro->l206_datahab)));
        $clHabilitacaoForn->l206_dataemissaoinss = implode('-', array_reverse(explode('/', $oParametro->l206_dataemissaoinss)));
        $clHabilitacaoForn->l206_dataemissaofgts = implode('-', array_reverse(explode('/', $oParametro->l206_dataemissaofgts)));
        $clHabilitacaoForn->l206_dataemissaocndt = implode('-', array_reverse(explode('/', $oParametro->l206_dataemissaocndt)));
        $clHabilitacaoForn->l206_datavalidadeinss = implode('-', array_reverse(explode('/', $oParametro->l206_datavalidadeinss)));
        $clHabilitacaoForn->l206_datavalidadefgts = implode('-', array_reverse(explode('/', $oParametro->l206_datavalidadefgts)));
        $clHabilitacaoForn->l206_datavalidadecndt = implode('-', array_reverse(explode('/', $oParametro->l206_datavalidadecndt)));
        $clHabilitacaoForn->l206_sequencial = $oParametro->sequencialHabilitacao;
        $clHabilitacaoForn->alterar($oParametro->sequencialHabilitacao);
        if($clHabilitacaoForn->erro_status == 0) throw new Exception($clHabilitacaoForn->erro_msg);
        $oRetorno->erro = "Usuário: Alteração efetuada com Sucesso.";
        break;

      case "incluirFornecedor":

        $clHabilitacaoForn->l206_numcertidaoinss = $oParametro->l206_numcertidaoinss;
        $clHabilitacaoForn->l206_numcertidaofgts = $oParametro->l206_numcertidaofgts;
        $clHabilitacaoForn->l206_numcertidaocndt = $oParametro->l206_numcertidaocndt;
        $clHabilitacaoForn->l206_datahab = implode('-', array_reverse(explode('/', $oParametro->l206_datahab)));
        $clHabilitacaoForn->l206_dataemissaoinss = implode('-', array_reverse(explode('/', $oParametro->l206_dataemissaoinss)));
        $clHabilitacaoForn->l206_dataemissaofgts = implode('-', array_reverse(explode('/', $oParametro->l206_dataemissaofgts)));
        $clHabilitacaoForn->l206_dataemissaocndt = implode('-', array_reverse(explode('/', $oParametro->l206_dataemissaocndt)));
        $clHabilitacaoForn->l206_datavalidadeinss = implode('-', array_reverse(explode('/', $oParametro->l206_datavalidadeinss)));
        $clHabilitacaoForn->l206_datavalidadefgts = implode('-', array_reverse(explode('/', $oParametro->l206_datavalidadefgts)));
        $clHabilitacaoForn->l206_datavalidadecndt = implode('-', array_reverse(explode('/', $oParametro->l206_datavalidadecndt)));
        $clHabilitacaoForn->l206_licitacao = $oParametro->l206_licitacao;
        $clHabilitacaoForn->l206_fornecedor = $oParametro->l206_forncedor;
        $clHabilitacaoForn->incluir(null);
        if($clHabilitacaoForn->erro_status == 0) throw new Exception($clHabilitacaoForn->erro_msg);
        $oRetorno->erro = "Usuário: Inclusão efetuada com Sucesso.";
        break;

      case "excluirForncedorHabilitado":

        $clHabilitacaoForn->excluir($oParametro->sequencialHabilitacao);
        if($clHabilitacaoForn->erro_status == 0) throw new Exception($clHabilitacaoForn->erro_msg);
        $oRetorno->erro = "Usuário: Exclusão efetuada com Sucesso.";
        break;

      case "verificacaoDispensaInexibilidade":
        
        $oRetorno->bloquearCampos = $clHabilitacaoForn->verificacaoDispensaInexibilidade($oParametro->codigoLicitacao,$oParametro->codigoFornecedor);
        break;
  }

} catch (Exception $e) {
  $oRetorno->erro   = $e->getMessage();
  $oRetorno->status = 2;
}

echo $oJson->encode(DBString::utf8_encode_all($oRetorno));
