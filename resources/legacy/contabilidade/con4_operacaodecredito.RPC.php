<?php
require_once("std/db_stdClass.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/JSON.php");
require_once("std/DBDate.php");
require_once("libs/db_liborcamento.php");
include("libs/db_libcontabilidade.php");
require_once("classes/db_orcorgao_classe.php");

db_postmemory($_POST);

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->status   = 1;

switch ($oParam->exec) {

    case 'verificaMovimentos':
        $clMovimentacao = new cl_movimentacaodedivida();
        $sCampos = "op02_sequencial, 
        op03_descr as op02_movimentacao, 
        op02_valor, op02_data, 
        op02_movimentacao as movimentacao, 
        op02_justificativa, 
        op02_tipo,
        op02_movautomatica";
        $rsMovimentos = $clMovimentacao->sql_record($clMovimentacao->buscaMovimentacoes(null, $sCampos, "op02_data", "op02_operacaodecredito = {$oParam->iOperacaoCredito}"));

        if ($clMovimentacao->erro_status == "0"){
            $oRetorno->status = 2;
            $oRetorno->message = "Erro ao buscar movimentações.";
        } else {
            $oRetorno->movimentos = db_utils::getCollectionByRecord($rsMovimentos, false, false, true);
        }

    break;

    case 'incluiMovimentacao':

        $sDataMovimento = App\Support\String\DateFormatter::convertDateFormatBRToISO($oParam->op02_data);
        $sSqlConsultaFimPeriodoContabil = "SELECT * FROM condataconf WHERE c99_anousu = ".db_getsession('DB_anousu')." and c99_instit = ".db_getsession('DB_instit');
        $rsConsultaFimPeriodoContabil   = db_query($sSqlConsultaFimPeriodoContabil);
    
        if (pg_num_rows($rsConsultaFimPeriodoContabil) > 0) {
            $oFimPeriodoContabil = db_utils::fieldsMemory($rsConsultaFimPeriodoContabil, 0);
            
            if ($oFimPeriodoContabil->c99_data != '' && db_strtotime($sDataMovimento) <= db_strtotime($oFimPeriodoContabil->c99_data)) {                
                $erro = true;
            }
        }

        if (!$erro) {
            $clMovimentacao = new cl_movimentacaodedivida();
            $clMovimentacao->op02_operacaodecredito = $oParam->op02_operacaodecredito;
            $clMovimentacao->op02_movimentacao      = $oParam->op02_movimentacao;
            $clMovimentacao->op02_tipo              = $oParam->op02_tipo;
            $clMovimentacao->op02_data              = $sDataMovimento;
            $clMovimentacao->op02_justificativa     = $oParam->op02_justificativa;
            $clMovimentacao->op02_valor             = $oParam->op02_valor;

            $clMovimentacao->incluir();

            if ($clMovimentacao->erro_status == "0"){
                $oRetorno->status = 2;
                $oRetorno->message = "Erro ao incluir movimentação.";
            } else {
                $rsMovimentos = $clMovimentacao->sql_record($clMovimentacao->buscaMovimentacoes($clMovimentacao->op02_sequencial, "op02_sequencial, op03_descr as op02_movimentacao, op02_tipo, op02_valor, op02_data, op02_movimentacao as movimentacao", "movimentacao"));
                $oRetorno->movimento = db_utils::getCollectionByRecord($rsMovimentos, false, false, true)[0];
                $oRetorno->message = "Movimentação incluida com sucesso.";
            }
        } else {
            $oRetorno->status = 2;
            $oRetorno->message = "Data inferior à data do fim do período contábil.";
        }

    break;

    case 'excluiMovimentacao':

        $sSqlConsultaFimPeriodoContabil = "SELECT * FROM condataconf WHERE c99_anousu = ".db_getsession('DB_anousu')." and c99_instit = ".db_getsession('DB_instit');
        $rsConsultaFimPeriodoContabil   = db_query($sSqlConsultaFimPeriodoContabil);
    
        if (pg_num_rows($rsConsultaFimPeriodoContabil) > 0) {
            $oFimPeriodoContabil = db_utils::fieldsMemory($rsConsultaFimPeriodoContabil, 0);
            
            if ($oFimPeriodoContabil->c99_data != '' && db_strtotime($oParam->op02_data) <= db_strtotime($oFimPeriodoContabil->c99_data)) {                
                $erro = true;
            }
        }

        if (!$erro) {
            $clMovimentacao = new cl_movimentacaodedivida();

            $clMovimentacao->excluir($oParam->op02_sequencial);

            if ($clMovimentacao->erro_status == "0"){
                $oRetorno->status = 2;
                $oRetorno->message = "Erro ao excluir movimentação.";
            } else {
                $oRetorno->item = $oParam;
                $oRetorno->message = "Movimentação excluida com sucesso.";
            }
        } else {
            $oRetorno->status = 2;
            $oRetorno->message = "Data inferior à data do fim do período contábil.";
        }

    break;

    case 'alteraMovimentacao':

        $sDataMovimento = App\Support\String\DateFormatter::convertDateFormatBRToISO($oParam->op02_data);
        $sSqlConsultaFimPeriodoContabil = "SELECT * FROM condataconf WHERE c99_anousu = ".db_getsession('DB_anousu')." and c99_instit = ".db_getsession('DB_instit');
        $rsConsultaFimPeriodoContabil   = db_query($sSqlConsultaFimPeriodoContabil);
    
        if (pg_num_rows($rsConsultaFimPeriodoContabil) > 0) {
            $oFimPeriodoContabil = db_utils::fieldsMemory($rsConsultaFimPeriodoContabil, 0);
            
            if ($oFimPeriodoContabil->c99_data != '' && db_strtotime($sDataMovimento) <= db_strtotime($oFimPeriodoContabil->c99_data)) {                
                $erro = true;
            }
        }

        if (!$erro) {
            $clMovimentacao = new cl_movimentacaodedivida();

            $clMovimentacao->op02_tipo              = $oParam->op02_tipo;
            $clMovimentacao->op02_data              = $sDataMovimento;
            $clMovimentacao->op02_justificativa     = $oParam->op02_justificativa;
            $clMovimentacao->op02_valor             = $oParam->op02_valor;

            $clMovimentacao->alterar($oParam->op02_sequencial);

            if ($clMovimentacao->erro_status == "0"){
                $oRetorno->status = 2;
                $oRetorno->message = "Erro ao alterar movimentação.";
            } else {
                $oRetorno->item = $oParam;
                $oRetorno->message = "Movimentação alterada com sucesso.";
            }
        } else {
            $oRetorno->status = 2;
            $oRetorno->message = "Data inferior à data do fim do período contábil.";
        }

    break;

}

$oRetorno->message = utf8_encode($oRetorno->message);
echo $oJson->encode($oRetorno);