<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_orcdotacao_classe.php");
include("classes/db_orcdotacaocontr_classe.php");
include("libs/JSON.php");

$clorcdotacao = new cl_orcdotacao();

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

try {
  
	switch ($oParam->exec) {

		case "getDotacao":
            
            $rsDotacao  = $clorcdotacao->sql_record($clorcdotacao->sql_query(db_getsession("DB_anousu"), $oParam->iCodDot));

            if ($clorcdotacao->numrows == 0) {
                throw new Exception("Nenhuma dotaï¿½ï¿½o encontrada.");
            }

            if ($clorcdotacao->numrows > 0) {
            
                $oDotacao   = db_utils::fieldsMemory($rsDotacao, 0);
                
                $oDotacao->o40_descr = urlencode($oDotacao->o40_descr);
                $oDotacao->o41_descr = urlencode($oDotacao->o41_descr);
                $oDotacao->o55_descr = urlencode($oDotacao->o55_descr);
                $oDotacao->o15_descr = urlencode($oDotacao->o15_descr);
                $oDotacao->o54_descr = urlencode($oDotacao->o54_descr);
                $oDotacao->o52_descr = urlencode($oDotacao->o52_descr);
                $oDotacao->o53_descr = urlencode($oDotacao->o53_descr);
			    
                $oRetorno->oDotacao = $oDotacao;
                
            }
				 
			break;

        case "excluirDotacao":
            $clorcsuplementacaoparametro = new cl_orcsuplementacaoparametro;
            $result = $clorcsuplementacaoparametro->sql_record($clorcsuplementacaoparametro->sql_query(db_getsession("DB_anousu"),"*"));
            $o134_orcamentoaprovado = db_utils::fieldsMemory($result,0)->o134_orcamentoaprovado;
            if($o134_orcamentoaprovado == 't'){
                $erro_msg = "ORÇAMENTO APROVADO. O PROCEDIMENTO NÃO PODE SER REALIZADO..";
                $oRetorno->message = mb_convert_encoding($erro_msg, 'UTF-8', 'ISO-8859-1');
            } else {  
                $clorcdotacaocontr = new cl_orcdotacaocontr;
                $oRetorno->aIndice = array();
                foreach ($oParam->aDados as $oItem) {
                    db_inicio_transacao();
                    $erro = false;
                
                    $rsDot = $clorcdotacaocontr->sql_record($clorcdotacaocontr->sql_query_file($oItem->iAnoDot,$oItem->iCodDot));
                    if($clorcdotacaocontr->numrows > 0){
                        $clorcdotacaocontr->excluir(null, "o61_coddot={$oItem->iCodDot} and o61_anousu = {$oItem->iAnoDot}");
                        if($clorcdotacaocontr->erro_status == 0) {                  
                            $erro = true;
                            $erro_msg = $clorcdotacaocontr->erro_msg;
                            $sFalha .= " {$oItem->iCodDot},";                
                        }
                    }

                    if($erro != true){
                        $clorcdotacao->excluir($oItem->iAnoDot,$oItem->iCodDot);
                        if($clorcdotacao->erro_status == 0){
                            $erro = true;
                            $erro_msg = $clorcdotacao->erro_msg;
                            $sFalha .= " {$oItem->iCodDot},";
                        }
                    }

                    db_fim_transacao($erro);
                    if (!$erro) {
                        $sSucesso .= " {$oItem->iCodDot},";
                        $oRetorno->aIndice[] = $oItem->iIndice;
                    }
                }
                $sMessagemSucesso = "\nDotações excluidas com sucesso:";
                $sMessagemFalha = "\nDotações não excluidas:";
                $sMensagemRetorno = '';
                if ($sFalha != '') {
                    $sMensagemRetorno .= $erro_msg;
                    $sMensagemRetorno .= "\n".$sMessagemFalha."\n - ".rtrim($sFalha,',');
                }
                if ($sSucesso != '') {
                    $sMensagemRetorno .= "\n".$sMessagemSucesso."\n - ".rtrim($sSucesso,',');
                }                
                $oRetorno->message = mb_convert_encoding($sMensagemRetorno, 'UTF-8', 'ISO-8859-1');
            }
        break;

        case "alterarValorDotacoes":
            $clorcsuplementacaoparametro = new cl_orcsuplementacaoparametro;
            $result = $clorcsuplementacaoparametro->sql_record($clorcsuplementacaoparametro->sql_query(db_getsession("DB_anousu"),"*"));
            $o134_orcamentoaprovado = db_utils::fieldsMemory($result,0)->o134_orcamentoaprovado;
            if($o134_orcamentoaprovado == 't'){
                $erro_msg = "ORÇAMENTO APROVADO. O PROCEDIMENTO NÃO PODE SER REALIZADO..";
                $oRetorno->message = mb_convert_encoding($erro_msg, 'UTF-8', 'ISO-8859-1');
            } else {
                $sSucesso = '';
                $sFalha = '';
                foreach ($oParam->aDados as $oItem) {
                    db_inicio_transacao();
                    $erro = false;

                    $clorcdotacao->o58_valor = $oItem->iValor;
                    $clorcdotacao->alterar($oItem->iAnoDot,$oItem->iCodDot,true);
                    if($clorcdotacao->erro_status == 0){
                        $erro = true;
                        $erro_msg = $clorcdotacao->erro_msg;
                        $sFalha .= " {$oItem->iCodDot},";
                    }
                    db_fim_transacao($erro);
                    if (!$erro) {
                        $sSucesso .= " {$oItem->iCodDot},";
                    }
                }
                $sMessagemSucesso = "\nDotações alteradas com sucesso:";
                $sMessagemFalha = "\nDotações não alteradas:";
                $sMensagemRetorno = '';
                if ($sFalha != '') {
                    $sMensagemRetorno .= $erro_msg;
                    $sMensagemRetorno .= "\n".$sMessagemFalha."\n - ".rtrim($sFalha,',');
                }
                if ($sSucesso != '') {
                    $sMensagemRetorno .= "\n".$sMessagemSucesso."\n - ".rtrim($sSucesso,',');
                }
                $oRetorno->message = mb_convert_encoding($sMensagemRetorno, 'UTF-8', 'ISO-8859-1');
            }
		break;
        
        case "alterarDotacoes":
            $clorcsuplementacaoparametro = new cl_orcsuplementacaoparametro;
            $result = $clorcsuplementacaoparametro->sql_record($clorcsuplementacaoparametro->sql_query(db_getsession("DB_anousu"),"*"));
            $o134_orcamentoaprovado = db_utils::fieldsMemory($result,0)->o134_orcamentoaprovado;
            if($o134_orcamentoaprovado == 't'){
                $erro_msg = "ORÇAMENTO APROVADO. O PROCEDIMENTO NÃO PODE SER REALIZADO..";
                $oRetorno->message = mb_convert_encoding($erro_msg, 'UTF-8', 'ISO-8859-1');
            } else {
                $sCampo = $oParam->sCampoAlterado;            
                $aMatriz = array('Orgao' => 'o58_orgao', 'Unidade' => 'o58_unidade', 'Funcao' => 'o58_funcao', 
                'Subfuncao' => 'o58_subfuncao', 'Programa' => 'o58_programa' , 'Projetos' => 'o58_projativ', 
                'Elemento' => 'o58_codele', 'Fonte' => 'o58_codigo');
                $sSucesso = '';
                $sFalha = '';
                $sDuplicidade = '';
                foreach ($oParam->aDados as $oItem) {
                    $oDotacao = new cl_orcdotacao();
                    db_inicio_transacao();
                    $erro = false;
                    
                    $rsDotacao = $oDotacao->sql_record($oDotacao->sql_query($oItem->iAnoDot,$oItem->iCodDot));
                    if ($rsDotacao) {
                        $rsDotacao = db_utils::fieldsMemory($rsDotacao,0);
                        $sWhere = '';
                        foreach ($aMatriz as $coluna) {
                            if ($aMatriz[$sCampo] != $coluna) {
                                if ($sCampo != 'Unidade' || $coluna != 'o58_orgao') {
                                    $sWhere .= "{$coluna} = {$rsDotacao->{$coluna}}";
                                }
                            } else {
                                if ($sCampo == 'Unidade') {
                                    $sWhere .= " o58_orgao = {$oParam->sCampoAlteradoOrgao} AND ";
                                } 
                                $sWhere .= "{$coluna} = {$oParam->sCampoAlteradoValor}";
                            }

                            if ($sWhere != '') {
                                $sWhere .= " AND ";
                            }
                        }
                        $sWhere .= " o58_anousu = {$rsDotacao->o58_anousu} ";
                        $rsDotacaoIgual = $oDotacao->sql_record($oDotacao->sql_query(null,null,'*',null,$sWhere));
                        if ($rsDotacaoIgual) {
                            $sDuplicidade .= " {$oItem->iCodDot},";
                            $erro = true;
                        }
                    }
                    
                    if (!$erro) {
                        if ($sCampo == 'Unidade') {
                            $clorcdotacao->o58_unidade = $oParam->sCampoAlteradoValor;
                            $clorcdotacao->o58_orgao = $oParam->sCampoAlteradoOrgao;
                        } else {
                            $clorcdotacao->{$aMatriz[$sCampo]} = $oParam->sCampoAlteradoValor;
                        }
                        $clorcdotacao->alterar($oItem->iAnoDot,$oItem->iCodDot,true);
                        if($clorcdotacao->erro_status == 0){
                            $erro = true;
                            $erro_msg = $clorcdotacao->erro_msg;
                            $sFalha .= " {$oItem->iCodDot},";
                        }                            
                    }
                    db_fim_transacao($erro);
                    if (!$erro) {
                        $sSucesso .= " {$oItem->iCodDot},";
                    }
                }
                $sMessagemSucesso = "\nDotações alteradas com sucesso:";
                $sMessagemFalha = "\nDotações não alteradas:";
                $sMessagemDuplicidade = "\nDotações não alteradas para evitar duplicidade:";
                $sMensagemRetorno = '';
                if ($sFalha != '') {
                    $sMensagemRetorno .= $erro_msg;
                    $sMensagemRetorno .= "\n".$sMessagemFalha."\n - ".rtrim($sFalha,',');
                }
                if ($sDuplicidade != '') {
                    $sMensagemRetorno .= "\n".$sMessagemDuplicidade."\n - ".rtrim($sDuplicidade,',');
                }
                if ($sSucesso != '') {
                    $sMensagemRetorno .= "\n".$sMessagemSucesso."\n - ".rtrim($sSucesso,',');
                }
                $oRetorno->message = mb_convert_encoding($sMensagemRetorno, 'UTF-8', 'ISO-8859-1');
            }
		break;
	}
  
} catch (Exception $eErro){
  
  $oRetorno->status = 2;
  $oRetorno->message = urlencode($eErro->getMessage());
  
} 

echo $oJson->encode($oRetorno);

?>