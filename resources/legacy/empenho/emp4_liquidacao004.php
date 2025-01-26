<?
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
use Illuminate\Database\Capsule\Manager as DB;
ini_set("ERROR_REPORTING", "E_ALL & ~ E_NOTICE");


require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_liborcamento.php"));

require_once(modification("classes/db_orcdotacao_classe.php"));
require_once(modification("classes/db_orctiporec_classe.php"));
require_once(modification("classes/db_empempenho_classe.php"));
require_once(modification("classes/db_empelemento_classe.php"));
require_once(modification("classes/db_pagordem_classe.php"));
require_once(modification("classes/db_pagordemele_classe.php"));
require_once(modification("classes/db_pagordemnota_classe.php"));
require_once(modification("classes/db_pagordemval_classe.php"));
require_once(modification("classes/db_pagordemrec_classe.php"));
require_once(modification("classes/db_pagordemtiporec_classe.php"));
require_once(modification("classes/db_empnota_classe.php"));
require_once(modification("classes/db_empnotaele_classe.php"));
require_once(modification("classes/db_tabrec_classe.php"));
require_once(modification("classes/db_conplanoreduz_classe.php"));
require_once(modification("classes/db_conlancam_classe.php"));
require_once(modification("classes/db_conlancamemp_classe.php"));
require_once(modification("classes/db_conlancamdoc_classe.php"));
require_once(modification("classes/db_conlancamele_classe.php"));
require_once(modification("classes/db_conlancamnota_classe.php"));
require_once(modification("classes/db_conlancamcgm_classe.php"));
require_once(modification("classes/db_conlancamdot_classe.php"));
require_once(modification("classes/db_conlancamval_classe.php"));
require_once(modification("classes/db_conlancamlr_classe.php"));
require_once(modification("classes/db_conlancamcompl_classe.php"));
require_once(modification("classes/db_conlancamord_classe.php"));
require_once(modification("classes/lancamentoContabil.model.php"));

require_once(modification("model/configuracao/Instituicao.model.php"));
require_once(modification("interfaces/ILancamentoAuxiliar.interface.php"));
require_once(modification("interfaces/IRegraLancamentoContabil.interface.php"));
require_once(modification("model/contabilidade/planoconta/interface/ISistemaConta.interface.php"));

require_once(modification("model/contabilidade/contacorrente/ContaCorrenteFactory.model.php"));
require_once(modification("model/contabilidade/contacorrente/ContaCorrenteBase.model.php"));
require_once(modification("model/financeiro/ContaBancaria.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaPlano.model.php"));
require_once(modification("model/contabilidade/planoconta/ClassificacaoConta.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaCorrente.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaOrcamento.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaPlanoPCASP.model.php"));
require_once(Modification::getFile('model/agendaPagamento.model.php'));
require_once(modification("model/retencaoNota.model.php"));
require_once(modification("model/ordemCompra.model.php"));

require_once(modification("model/contabilidade/planoconta/SistemaContaCompensado.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaFinanceiroBanco.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaFinanceiroCaixa.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaFinanceiroExtraOrcamentaria.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaFinanceiro.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaPatrimonial.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaOrcamentario.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaContaNaoAplicado.model.php"));

require_once(modification("model/contabilidade/planoconta/SubSistemaConta.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaConta.model.php"));

require_once(Modification::getFile("classes/empenho.php"));
require_once(modification("model/CgmFactory.model.php"));

require_once(modification("model/Dotacao.model.php"));


db_app::import("exceptions.*");

$objEmpenho = new empenho();
$post = db_utils::postmemory($_POST);
$json = new services_json();
$objJson = $json->decode(str_replace("\\", "", $_POST["json"]));
$method = $objJson->method;
$oAgendaPagamento = new agendaPagamento();
$item = 0; //se deve trazer as notas, ou os itens do empenho.
$objEmpenho->setEmpenho($objJson->iEmpenho);
$objEmpenho->setEncode(true);
$paremetrosaldo = new cl_parametroscontratos;

$dtDataSessao = db_getsession("DB_datausu");

$clempparametro       = new cl_empparametro;
$clempempenholiberado = new cl_empempenholiberado;
$lBloquear = false;
$result    = $clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu")));
if ($result != false && $clempparametro->numrows > 0) {
    $oParam = db_utils::fieldsMemory($result, 0);
}
if ($oParam->e30_liberaempenho == 't') {

    if (isset($objJson->iEmpenho) && !empty($objJson->iEmpenho)) {
        $sCampos = "empempenholiberado.*";
        $sWhere  = "e22_numemp = {$objJson->iEmpenho} ";
        $sWhere .= " AND EXISTS(SELECT 1 FROM empempenholiberado WHERE e22_numemp= e60_numemp)";
        $sWhere .= " AND NOT EXISTS(SELECT 1 FROM desdobramentosliberadosordemcompra WHERE pc33_codele = e64_codele ";
        $sWhere .= " AND pc33_anousu = " . db_getsession("DB_anousu") . ")";

        $sSqlEmpenhosLiberados  = $clempempenholiberado->sql_query(null, $sCampos, null, $sWhere);
        $rsSqlEmpenhosLiberados = $clempempenholiberado->sql_record($sSqlEmpenhosLiberados);

        if ($clempempenholiberado->numrows == 0) {
            $lBloquear = true;
        }
    }
}
//var_dump($lBloquear);exit("saiu");

//VERIFICA CPF E CNPJ ZERADOS OC 7037
$result_cgmzerado = db_query("select z01_cgccpf from empempenho inner join cgm on z01_numcgm = e60_numcgm where e60_numemp = {$objJson->iEmpenho}");
db_fieldsmemory($result_cgmzerado, 0)->z01_cgccpf;
$zerado = false;

if (strlen($z01_cgccpf) == 14) {
    $tipofornec = 'cnpj';
    if ($z01_cgccpf == '00000000000000') {
        $zerado = true;
    }
}

if (strlen($z01_cgccpf) == 11) {
    $tipofornec = 'cpf';
    if ($z01_cgccpf == '00000000000') {
        $zerado = true;
    }
}

//FIM OC 7037

switch ($objJson->method) {

    case "getEmpenhos":

        $lValidaNotasEmpenho = false;

        $oDaoElementos        = db_utils::getDao('orcelemento');
        $sWhereEmpenho        = " e60_numemp =  {$objJson->iEmpenho}";
        $sSqlBuscaElemento    = $oDaoElementos->sql_query_estrut_empenho(null, "substr(o56_elemento,1,7) AS o56_elemento", null, $sWhereEmpenho);
        $rsBuscaElemento      = $oDaoElementos->sql_record($sSqlBuscaElemento);

        $sSqlBuscaDesdobramento    = $oDaoElementos->sql_query_estrut_empenho(null, "substr(o56_elemento,1,9) AS o56_elemento", null, $sWhereEmpenho);
        $rsBuscaDesdobramento      = $oDaoElementos->sql_record($sSqlBuscaDesdobramento);

        $oDaoPatriInst    = db_utils::getDao('cfpatriinstituicao');
        $sWherePatriInst  = " t59_instituicao = " . db_getsession('DB_instit');
        $sSqlPatriInst    = $oDaoPatriInst->sql_query_file(null, "t59_dataimplanatacaodepreciacao", null, $sWherePatriInst);
        $rsPatriInst      = $oDaoPatriInst->sql_record($sSqlPatriInst);

        if ($oDaoPatriInst->numrows > 0) {

            $dtImplantacao = db_utils::fieldsMemory($rsPatriInst, 0)->t59_dataimplanatacaodepreciacao;
            if (!empty($dtImplantacao)) {
                $lValidaNotasEmpenho = true;
            }
        }

        $oDaoEmpNota      = db_utils::getDao('empnota');
        $sWhereBuscaNotas = " e69_numemp = {$objJson->iEmpenho} ";
        $sSqlBuscaNotas   = $oDaoEmpNota->sql_query_elemento_patrimonio(null, "empnota.* ", null, $sWhereBuscaNotas);
        $rsBuscaNotas     = $oDaoEmpNota->sql_record($sSqlBuscaNotas);
        $aBuscaNotas      = db_utils::getCollectionByRecord($rsBuscaNotas);
        $aNotasEmpenho    = array();
        $oGrupoElemento   = new stdClass();

        if (count($aBuscaNotas) > 0 && $lValidaNotasEmpenho) {

            foreach ($aBuscaNotas as $oNota) {

                $oDaoEmpNotaItemBensPendente      = db_utils::getDao('empnotaitembenspendente');
                $sWhereBuscaItensForaDoPatrimonio = " e69_codnota = {$oNota->e69_codnota} and e136_sequencial is null "
                    . " and e139_sequencial is null and m73_cancelado is false ";

                $sSqlBuscaItensForaDoPatrimonio = $oDaoEmpNotaItemBensPendente->sql_query_patrimonio(
                    null,
                    "e69_codnota",
                    null,
                    $sWhereBuscaItensForaDoPatrimonio
                );
                $rsBuscaItensForaDoPatrimonio = $oDaoEmpNotaItemBensPendente->sql_record($sSqlBuscaItensForaDoPatrimonio);
                if ($oDaoEmpNotaItemBensPendente->numrows > 0) {
                    $aNotasEmpenho[] = $oNota->e69_codnota;
                }
            }
        }

        $objEmpenho->operacao = $objJson->operacao;
        if (isset($objJson->itens)) {
            $item = 1;
        }
        $objEmpenho->setEmpenho($objJson->iEmpenho);

        if (count($aNotasEmpenho) > 0 && $lValidaNotasEmpenho) {

            //echo $objEmpenho->empenho2Json('',$item, $aNotasEmpenho);
            $oEmpenho = json_decode($objEmpenho->empenho2Json('', $item, $aNotasEmpenho));
            $oEmpenho->sDesdobramento = db_utils::fieldsMemory($rsBuscaDesdobramento, 0)->o56_elemento;
            $oGrupoElemento->iGrupo = "";
            $oGrupoElemento->sGrupo = "";
            $oEmpenho->oGrupoElemento = $oGrupoElemento;
            $oEmpenho->LiberadoLic = $lBloquear;
            $oEmpenho->Zerado = $zerado;
            $oEmpenho->Tipofornec = $tipofornec;
            $oEmpenho->bPermitidoLiquidacao = $bPermitidoLiquidacao;
            $oEmpenho->obrigaDiaria = $oParam->e30_obrigadiarias;
            $rsEstruturalDotacao = db_query('SELECT fc_estruturaldotacao('.$oEmpenho->e60_anousu.', '.$oEmpenho->e60_coddot.') as estrutural_dotacao');
            $oEmpenho->estruturalDotacao = db_utils::fieldsMemory($rsEstruturalDotacao, 0)->estrutural_dotacao;
            echo $json->encode($oEmpenho);
        } else {

            $oEmpenho = json_decode($objEmpenho->empenho2Json('', $item));
            $oGrupoContaOrcamento = GrupoContaOrcamento::getGrupoConta($oEmpenho->e64_codele, db_getsession("DB_anousu"));

            $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oEmpenho->e60_numemp);
            if ($oGrupoContaOrcamento && !$oEmpenhoFinanceiro->isEmpenhoPassivo()) {

                $iGrupo = $oGrupoContaOrcamento->getCodigo();
                $sDescricao = $oGrupoContaOrcamento->getDescricao();

                /**
                 * Caso o empennho seja dos grupos abaixo, nao devemos permitir a liquidacao
                 * do mesmo atraves da rotina de liquidacao sem ordem de compra
                 */
                if ($iGrupo != "") {

                    if (in_array($iGrupo, array(7, 8, 9))) {


                        $oGrupoElemento->iGrupo = $iGrupo;
                        $oGrupoElemento->sGrupo = urlencode($sDescricao);
                        $oEmpenho->sDesdobramento = db_utils::fieldsMemory($rsBuscaDesdobramento, 0)->o56_elemento;
                        $oEmpenho->oGrupoElemento = $oGrupoElemento;
                        $oEmpenho->LiberadoLic = $lBloquear;
                        $oEmpenho->Zerado = $zerado;
                        $oEmpenho->Tipofornec = $tipofornec;
                        $oEmpenho->bPermitidoLiquidacao = $bPermitidoLiquidacao;
                        $oEmpenho->obrigaDiaria = $oParam->e30_obrigadiarias;
                        $rsEstruturalDotacao = db_query('SELECT fc_estruturaldotacao('.$oEmpenho->e60_anousu.', '.$oEmpenho->e60_coddot.') as estrutural_dotacao');
                        $oEmpenho->estruturalDotacao = db_utils::fieldsMemory($rsEstruturalDotacao, 0)->estrutural_dotacao;
                        echo $json->encode($oEmpenho);
                    } else {

                        //echo $objEmpenho->empenho2Json('',$item);
                        $oEmpenho = json_decode($objEmpenho->empenho2Json('', $item));
                        $oEmpenho->sDesdobramento = db_utils::fieldsMemory($rsBuscaDesdobramento, 0)->o56_elemento;
                        $oGrupoElemento->iGrupo = "";
                        $oGrupoElemento->sGrupo = "";
                        $oEmpenho->oGrupoElemento = $oGrupoElemento;
                        $oEmpenho->LiberadoLic = $lBloquear;
                        $oEmpenho->Zerado = $zerado;
                        $oEmpenho->Tipofornec = $tipofornec;
                        $oEmpenho->bPermitidoLiquidacao = $bPermitidoLiquidacao;
                        $oEmpenho->obrigaDiaria = $oParam->e30_obrigadiarias;
                        $rsEstruturalDotacao = db_query('SELECT fc_estruturaldotacao('.$oEmpenho->e60_anousu.', '.$oEmpenho->e60_coddot.') as estrutural_dotacao');
                        $oEmpenho->estruturalDotacao = db_utils::fieldsMemory($rsEstruturalDotacao, 0)->estrutural_dotacao;
                        echo $json->encode($oEmpenho);
                    }
                }
            } else {
                // echo $objEmpenho->empenho2Json('',$item);
                $oEmpenho = json_decode($objEmpenho->empenho2Json('', $item));
                $oEmpenho->sEstrutural = db_utils::fieldsMemory($rsBuscaElemento, 0)->o56_elemento;
                $oEmpenho->sDesdobramento = db_utils::fieldsMemory($rsBuscaDesdobramento, 0)->o56_elemento;

                $oGrupoElemento->iGrupo = "";
                $oGrupoElemento->sGrupo = "";
                $oEmpenho->oGrupoElemento = $oGrupoElemento;
                $oEmpenho->LiberadoLic = $lBloquear;
                $oEmpenho->Zerado = $zerado;
                $oEmpenho->Tipofornec = $tipofornec;
                $oEmpenho->bPermitidoLiquidacao = $bPermitidoLiquidacao;
                $oEmpenho->obrigaDiaria = $oParam->e30_obrigadiarias;
                $rsEstruturalDotacao = db_query('SELECT fc_estruturaldotacao('.$oEmpenho->e60_anousu.', '.$oEmpenho->e60_coddot.') as estrutural_dotacao');
                $oEmpenho->estruturalDotacao = db_utils::fieldsMemory($rsEstruturalDotacao, 0)->estrutural_dotacao;
                echo $json->encode($oEmpenho);
            }
        }
        break;

    case "liquidarAjax":

        $dDataLiquidacao = App\Support\String\DateFormatter::convertDateFormatBRToISO(trim($objJson->dDataLiquidacao));

        if($objJson->dDataVencimento != null || $objJson->dDataVencimento != ''){
            $dDataVencimento =  App\Support\String\DateFormatter::convertDateFormatBRToISO(trim($objJson->dDataVencimento));
        }else{
            $dDataVencimento = null;
        }

        ////////////////////////////////////

        if (isset($objJson->z01_credor) && !empty($objJson->z01_credor)) {
            $objEmpenho->setCredor($objJson->z01_credor);
        }

        try {
            // Condi��o de valida��o dos empenhos
            // Verificar data do sistema
            $dtServidor = date('d-m-Y', DB_getsession('DB_datausu'));
            $chave = true;

            // Verificar data da �ltima liquida��o
            $sSqlLiquidados = 'SELECT e50_data as dtultimaliquidacao FROM pagordem WHERE e50_numemp = ' . $objJson->iEmpenho . ' ORDER BY e50_data DESC LIMIT 1 ';
            $rsLiquidados = pg_query($sSqlLiquidados);

            if (@pg_num_rows($rsLiquidados) > 0) {
                db_fieldsmemory($rsLiquidados, 0);

                if ($oParam->e30_liquidacaodataanterior == 'f') {
                    if (date("Y-m-d", strtotime($dDataLiquidacao)) < date("Y-m-d", strtotime($dtultimaliquidacao))) {
                        throw new Exception("N�o � permitido liquidar com data anterior ao �ltimo lan�amento de liquida��o.");
                    }
                }
            }

            $dtDataSessao = date("Y-m-d", $dtDataSessao);
            $oDaoConlancamEmp = new cl_conlancamemp();
            $sWhereEmpenho  = "     conlancamemp.c75_numemp = {$objJson->iEmpenho} ";
            $sWhereEmpenho .= " and conhistdoc.c53_tipo     = 200 ";
            $sWhereEmpenho .= " and conlancam.c70_data      > '{$dDataLiquidacao}' ";
            $sSqlBuscaDocumentos = $oDaoConlancamEmp->sql_query_documentos(null, "conhistdoc.*", 1, $sWhereEmpenho);
            $rsBuscaDocumentos = $oDaoConlancamEmp->sql_record($sSqlBuscaDocumentos);

            if ($oDaoConlancamEmp->numrows > 0) {
                throw new Exception("N�o � poss�vel realizar o lan�amento cont�bil com data anterior a data dos lan�amentos de controle de liquida��o.");
            }

            $sHistorico = db_stdClass::normalizeStringJsonEscapeString($objJson->historico); //addslashes(stripslashes(utf8_decode()))
            $oRetorno   = $objEmpenho->liquidarAjax($objJson->iEmpenho,
                                                    $objJson->notas, $sHistorico,
                                                    $objJson->e50_compdesp,
                                                    $objJson->e83_codtipo,
                                                    $objJson->cattrabalhador,
                                                    $objJson->numempresa,
                                                    $objJson->contribuicaoPrev,
                                                    $objJson->cattrabalhadorremuneracao,
                                                    $objJson->valorremuneracao,
                                                    $objJson->valordesconto,
                                                    $objJson->competencia,
                                                    $objJson->e50_retencaoir,
                                                    $objJson->e50_naturezabemservico,
                                                    $dDataLiquidacao, 
                                                    $dDataVencimento,
                                                    $objJson->e50_contafornecedor,
                                                    $objJson->e50_numcgmordenador);
            $oDadosRetorno = $json->decode(str_replace("\\", "", $oRetorno));

            if ($oRetorno !== false) {

                if ($oDadosRetorno->erro == 1) {

                    //caso procedimento com sucesso  vincula o processo administrativo
                    $sProcessoAdministrativo = addslashes(db_stdClass::normalizeStringJson($objJson->e03_numeroprocesso));

                    if (!empty($sProcessoAdministrativo)) {

                        $aOrdensGeradas = explode(",", $oDadosRetorno->sOrdensGeradas);

                        foreach ($aOrdensGeradas as $iIndOrdensGeradas => $iOrdem) {

                            $oDaoPagordemProcesso = new cl_pagordemprocesso();
                            $oDaoPagordemProcesso->e03_numeroprocesso = $sProcessoAdministrativo;
                            $oDaoPagordemProcesso->e03_pagordem = $iOrdem;
                            $oDaoPagordemProcesso->incluir(null);
                            if ($oDaoPagordemProcesso->erro_status == 0) {
                                throw new Exception($oDaoPagordemProcesso->erro_msg);
                            }
                        }
                    }

                    echo $oRetorno;
                }

                /**[Extensao OrdenadorDespesa] inclusao_ordenador_1*/
            }


            if ($objEmpenho->lSqlErro && !empty($objEmpenho->sMsgErro)) {
                throw new Exception($objEmpenho->sMsgErro);
            }
        } catch (Exception $eErro) {

            $oRetorno = $json->encode(array("sMensagem" => urlencode($eErro->getMessage()), "lErro" => true));
            echo $oRetorno;
        }

        break;

    case "geraOC":
        $dDataLiquidacao =  App\Support\String\DateFormatter::convertDateFormatBRToISO(trim($objJson->dDataLiquidacao));
        if($objJson->dDataVencimento != null || $objJson->dDataVencimento != ''){
            $dDataVencimento = App\Support\String\DateFormatter::convertDateFormatBRToISO(trim($objJson->dDataVencimento));
        }else{
            $dDataVencimento = null;
        }
        ////////////////////////////////////

        // Condi��o de valida��o dos empenhos
        // Verificar data do sistema
        $dtServidor = date('d-m-Y', DB_getsession('DB_datausu'));
        $chave = true;

        // Verificar data da �ltima liquida��o
        $sSqlLiquidados = 'SELECT e50_data as dtultimaliquidacao FROM pagordem WHERE e50_numemp = ' . $objJson->iEmpenho . ' ORDER BY e50_data DESC LIMIT 1 ';
        $rsLiquidados = pg_query($sSqlLiquidados);

        if (@pg_num_rows($rsLiquidados) > 0)
            db_fieldsmemory($rsLiquidados, 0);

        if ($oParam->e30_liquidacaodataanterior == 'f') {
            if (date("Y-m-d", strtotime($dDataLiquidacao)) < date("Y-m-d", strtotime($dtultimaliquidacao))) {
                $chave = false;
                $objEmpenho->sMsgErro = "N�o � permitido liquidar com data anterior ao �ltimo lan�amento de liquida��o.";
                $retorno = array("erro" => 2, "mensagem" => urlencode($objEmpenho->sMsgErro), "e50_codord" => null);
                echo $json->encode($retorno);
                break;
            }
        }

        $z01_credor = $objJson->z01_credor;
        $sHistorico = db_stdClass::normalizeStringJsonEscapeString($objJson->historico);
        $objEmpenho->setEmpenho($objJson->iEmpenho);
        $objEmpenho->setCredor($z01_credor);


        if ($objJson->iCgmEmitente != "") {            
            $oDaoCgm   = db_utils::getDao("cgm");
            $sSqlCgmEmitente   = $oDaoCgm->sql_query_file($objJson->iCgmEmitente);
            $sSqlCgm   = $oDaoCgm->sql_query_file($objJson->cgm);
            $oDadosCgm = db_utils::fieldsMemory($oDaoCgm->sql_record($sSqlCgm), 0);
            $oDadosCgmEmitente = db_utils::fieldsMemory($oDaoCgm->sql_record($sSqlCgmEmitente), 0);
            if (substr($oDadosCgm->z01_cgccpf, 0, 8) != substr($oDadosCgmEmitente->z01_cgccpf, 0, 8)) {
                $chave = false; 
                $objEmpenho->sMsgErro   = "Verifique o CNPJ do Emitente!";
            }
        }
        
        if ($chave != false) {
            /**
             * Pode ser que o m�todo gerarOrdemCompra retorne false ou um JSON
             */
            $oRetorno = $objEmpenho->gerarOrdemCompra(
                $objJson->e69_nota,
                $objJson->valorTotal,
                $objJson->notas,
                true,
                $objJson->e69_dtnota,
                $sHistorico,
                true,
                $objJson->oInfoNota,
                $objJson->e69_notafiscaleletronica,
                $objJson->e69_chaveacesso,
                $objJson->e69_nfserie,
                $objJson->e50_compdesp,
                $objJson->e83_codtipo,
                true,
                $objJson->iCgmEmitente,
                $objJson->cattrabalhador,
                $objJson->numempresa,
                $objJson->contribuicaoPrev,
                $objJson->cattrabalhadorremuneracao,
                $objJson->valorremuneracao,
                $objJson->valordesconto,
                $objJson->competencia,
                $objJson->e50_retencaoir,
                $objJson->e50_naturezabemservico,
                $dDataLiquidacao,
                $dDataVencimento,
                $objJson->e50_contafornecedor,
                $objJson->e50_numcgmordenador
            );
        }

        if (isset($objJson->verificaChave) && $objJson->verificaChave == 1 && $objJson->e69_notafiscaleletronica != 2 && $objJson->e69_notafiscaleletronica != 3) {
            $ufs = array(

                11 => "RO", 12 => "AC", 13 => "AM", 14 => "RR", 15 => "PA", 16 => "AP", 17 => "TO", 21 => "MA", 22 => "PI",
                23 => "CE", 24 => "RN", 25 => "PB", 26 => "PE", 27 => "AL", 28 => "SE", 29 => "BA", 31 => "MG", 32 => "ES",
                33 => "RJ", 35 => "SP", 41 => "PR", 42 => "SC", 43 => "RS", 50 => "MS", 51 => "MT", 52 => "GO", 53 => "DF"

            );

            $ufKey   = substr($objJson->e69_chaveacesso, 0, 2);
            $dataKey = substr($objJson->e69_chaveacesso, 2, 4);
            $cnpjKey = substr($objJson->e69_chaveacesso, 6, 14);
            $nfKey   = substr($objJson->e69_chaveacesso, 25, 9);

            $oDaoCgm   = db_utils::getDao("cgm");

            // Condicao da OC17910
            if ($objJson->iCgmEmitente > "0")
                $sSqlCgm   = $oDaoCgm->sql_query_file($objJson->iCgmEmitente);
            else
                $sSqlCgm   = $oDaoCgm->sql_query_file($objJson->cgm);
            $rsCgm     = $oDaoCgm->sql_record($sSqlCgm);
            $oDadosCgm = db_utils::fieldsMemory($rsCgm, 0);


            $key  = (array_key_exists($ufKey, $ufs)) ? $ufKey : 0;
            $data = substr(implode("", array_reverse(explode("/", $objJson->e69_dtnota))), 2, 4);

            if ($ufs[$key] != $oDadosCgm->z01_uf) {
                $chave = false;
                $objEmpenho->sMsgErro   = "Chave de acesso inv�lida!\nVerifique a Cidade e o Estado do Fornecedor!";
            } else if (strcmp($data, $dataKey)) {
                $chave = false;
                $objEmpenho->sMsgErro   = "Chave de acesso inv�lida!\nVerifique a data da Nota Fiscal!";
            } else if (strcmp(str_pad($objJson->e69_nota, 9, "0", STR_PAD_LEFT), $nfKey)) {
                $chave = false;
                $objEmpenho->sMsgErro   = "Chave de acesso inv�lida!\nVerifique o N�mero da Nota!";
            } else if ($objJson->e69_notafiscaleletronica == 1) {
                if (strcmp($oDadosCgm->z01_cgccpf, $cnpjKey)) {
                    $chave = false; //
                    $objEmpenho->sMsgErro   = "Chave de acesso inv�lida!\nVerifique o CNPJ do Fornecedor!";
                }
            }
        }

        if ($chave !== false) {

            if ($oRetorno !== false) {


                //caso procedimento com sucesso  vincula o processo administrativo
                $sProcessoAdministrativo = addslashes(stripslashes(utf8_decode($objJson->e03_numeroprocesso)));
                $oDadosRetorno = $json->decode(str_replace("\\", "", $oRetorno));

                if ($oDadosRetorno->erro != 2) {

                    if (!empty($sProcessoAdministrativo)) {

                        $oDaoPagordemProcesso = new cl_pagordemprocesso();
                        $oDaoPagordemProcesso->e03_numeroprocesso = $sProcessoAdministrativo;
                        $oDaoPagordemProcesso->e03_pagordem = $oDadosRetorno->e50_codord;
                        $oDaoPagordemProcesso->incluir(null);
                        if ($oDaoPagordemProcesso->erro_status == 0) {
                            throw new Exception($oDaoPagordemProcesso->erro_msg);
                        }
                    }
                    /**[Extensao OrdenadorDespesa] inclusao_ordenador_2*/
                }

                echo $oRetorno;
            } else {

                $retorno = array("erro" => 2, "mensagem" => urlencode($objEmpenho->sMsgErro), "e50_codord" => null);
                echo $json->encode($retorno);
            }
        } else {
            $retorno = array("erro" => 2, "mensagem" => urlencode($objEmpenho->sMsgErro), "e50_codord" => null);
            echo $json->encode($retorno);
        }

        break;

    case "verificaacordo":

        /*CASE CRIADA PARA VERIFICAR SE O PARAMETRO DO CONTRATO - RETORNO POR POSICAO ESTA ATIVO E SE O EMPENHO TEM VINCULO COM UM ACORDO
      CAMPO - pc01_liberarsaldoposicao
      FALSE - RETORNA PARA POSICAO FINAL
      TRUE - RETORNA PARA POSICAO VINCULADA
    */

        $Sqlparemetrosaldo = $paremetrosaldo->sql_record($paremetrosaldo->sql_query_file('', 'pc01_liberarsaldoposicao'));
        $resulparemetrosaldo  = db_utils::fieldsMemory($Sqlparemetrosaldo, 0);
        $aItens = $objJson->itensAnulados;
        $iStatus = 1;
        $nMensagem = 'Usu�rio: ';

        //VERIFICA O PARAMETRO DO SALDO POR POSICAO
        if ($resulparemetrosaldo->pc01_liberarsaldoposicao == 'f' && pg_num_rows($Sqlparemetrosaldo) > 0) {

            for ($iInd = 0; $iInd < count($aItens); $iInd++) {
                //INICIA A VERERIFICA�AO DE TODOS AS LANA�AMENTOS CASO NAO EXISTA ELE FINALIZA POIS NAO PERTENCE A UM ACORDO OU NAO EXISTE LANCAMENTO
                $acordoMaterial = db_query("SELECT ac26_acordo, ac20_pcmater
            FROM acordoitemexecutadoempautitem
            JOIN acordoitemexecutado ON ac19_acordoitemexecutado=ac29_sequencial
            JOIN acordoitem ON ac29_acordoitem=ac20_sequencial
            JOIN acordoposicao ON ac20_acordoposicao=ac26_sequencial
            JOIN empautitem ON ac19_autori=e55_autori
            AND ac19_sequen=e55_sequen
            JOIN empautoriza ON e55_autori=e54_autori
            JOIN empempaut ON e61_autori=e54_autori
            JOIN empempitem ON e62_numemp=e61_numemp and ac20_pcmater=e62_item
            WHERE e62_sequencial = {$aItens[$iInd]->e62_sequencial}");

                $rsacordoMaterial = db_utils::fieldsMemory($acordoMaterial, 0);

                if (pg_num_rows($acordoMaterial) == 0) {
                    break;
                }

                $DaoacordoItem = db_utils::getDao('acordoitem');

                $ItemUltimaPosicao = $DaoacordoItem->sql_record("
            SELECT ac20_sequencial,ac20_quantidade,ac20_valortotal,ac20_valorunitario,ac20_acordoposicao,ac20_servicoquantidade,pc01_servico
            FROM acordoitem
            inner join pcmater on pc01_codmater = ac20_pcmater
            inner join acordoposicao on ac26_sequencial = ac20_acordoposicao
            WHERE ac26_acordo = {$rsacordoMaterial->ac26_acordo}
            AND ac26_sequencial =
            (SELECT max(ac26_sequencial)
            FROM acordoposicao
            WHERE ac26_acordo = {$rsacordoMaterial->ac26_acordo})
            AND ac20_pcmater = {$rsacordoMaterial->ac20_pcmater} ");

                if (pg_num_rows($ItemUltimaPosicao) == 0) {
                    break;
                }

                $ItemUltimaPosicao = db_utils::fieldsMemory($ItemUltimaPosicao, 0);


                $empempaut = db_query("select e61_autori from empempaut where e61_numemp = {$objJson->iEmpenho}");

                if (pg_num_rows($empempaut) == 0) {
                    break;
                }

                $rsempempaut = db_utils::fieldsMemory($empempaut, 0);

                $empautitem = db_query("select e55_sequen from empautitem where e55_autori = {$rsempempaut->e61_autori} and e55_item = {$rsacordoMaterial->ac20_pcmater}");

                if (pg_num_rows($empautitem) == 0) {
                    break;
                }

                $rsempautitem = db_utils::fieldsMemory($empautitem, 0);

                $acordoitemexecutadoempautitem = db_query("select min(ac19_acordoitemexecutado) as itemexecutado from acordoitemexecutadoempautitem  where ac19_autori = {$rsempempaut->e61_autori} and ac19_sequen = {$rsempautitem->e55_sequen}");

                if (pg_num_rows($acordoitemexecutadoempautitem) == 0) {
                    break;
                }

                $rsacordoitemexecutadoempautitem = db_utils::fieldsMemory($acordoitemexecutadoempautitem, 0);

                $acordoitemexecutado = db_query("select ac29_acordoitem from acordoitemexecutado where ac29_sequencial = {$rsacordoitemexecutadoempautitem->itemexecutado}");

                if (pg_num_rows($acordoitemexecutado) == 0) {
                    break;
                }

                $rsacordoitemexecutado = db_utils::fieldsMemory($acordoitemexecutado, 0);

                $acordoitem = db_query("select ac20_acordoposicao from acordoitem where ac20_sequencial = {$rsacordoitemexecutado->ac29_acordoitem}");

                if (pg_num_rows($acordoitem) == 0) {

                    break;
                }

                $rsacordoitem = db_utils::fieldsMemory($acordoitem, 0);

                $ItemAtualPosicao = $DaoacordoItem->sql_record("
            SELECT ac20_sequencial,ac20_quantidade,ac20_valortotal,ac20_valorunitario,ac20_acordoposicao,ac20_servicoquantidade
            FROM acordoitem
            JOIN acordoposicao ON ac20_acordoposicao = ac26_sequencial
            WHERE ac26_acordo = {$rsacordoMaterial->ac26_acordo}
            AND ac26_sequencial ={$rsacordoitem->ac20_acordoposicao}
            AND ac20_pcmater = {$rsacordoMaterial->ac20_pcmater} ");

                if (pg_num_rows($ItemAtualPosicao) == 0) {
                    break;
                }

                $ItemAtualPosicao = db_utils::fieldsMemory($ItemAtualPosicao, 0);

                $Dadosemp = $DaoacordoItem->sql_record("select e62_servicoquantidade,pcmater.pc01_servico from empempitem
        inner join pcmater on
        pcmater.pc01_codmater = empempitem.e62_item
        where e62_numemp = $objJson->iEmpenho and pc01_codmater= $rsacordoMaterial->ac20_pcmater");

                if (pg_num_rows($Dadosemp) == 0) {
                    break;
                }

                $rsDadosemp = db_utils::fieldsMemory($Dadosemp, 0);
                //FINALIZA A VERIFICACAO
                //CASO CHEGUE AQUI FAZ A COMPARACAO DOS DADOS DO EMPENHO COM A ULTIMA POSICAO SE TEM ALGUMA ALTERACAO


                if ($rsDadosemp->e62_servicoquantidade != $ItemUltimaPosicao->ac20_servicoquantidade) {
                    $nMensagem = "Usu�rio: N�o ser� poss�vel a anula��o do empenho.\n\nMotivo: A forma de controle do item " . $rsacordoMaterial->ac20_pcmater . " no empenho � diferente da posi��o atual do contrato!";
                    $iStatus = 2;
                    echo $json->encode(array("mensagem" => urlencode($nMensagem), "status" => $iStatus));
                    return;
                }
                if ($ItemUltimaPosicao->ac20_servicoquantidade == 'f' && $ItemUltimaPosicao->pc01_servico == 't') {
                } else if ($ItemUltimaPosicao->ac20_valorunitario != $ItemAtualPosicao->ac20_valorunitario) {
                    $nMensagem .= "Item " . $rsacordoMaterial->ac20_pcmater . ": O valor unit�rio atual do contrato � " . $ItemUltimaPosicao->ac20_valorunitario . " e o valor unit�rio do item a ser anulado � " . $ItemAtualPosicao->ac20_valorunitario . ". Ao anular os itens do empenho, o valor unitario ser� o " . $ItemUltimaPosicao->ac20_valorunitario . ".\n\n";
                    $iStatus = 3;
                }
            }
        }
        echo $json->encode(array("mensagem" => urlencode($nMensagem), "status" => $iStatus));


        break;

    case "anularEmpenho":


        $Sqlparemetrosaldo = $paremetrosaldo->sql_record($paremetrosaldo->sql_query_file('', 'pc01_liberarsaldoposicao'));
        $resulparemetrosaldo  = db_utils::fieldsMemory($Sqlparemetrosaldo, 0);
        $aItens = $objJson->itensAnulados;


        $objEmpenho->buscaUltimoDocumentoExecutadoDoc($objJson->iEmpenho, 21, date('Y-m-d', db_getsession('DB_datausu')));
        if ($objEmpenho->lSqlErro) {
            $nMensagem = urlencode($objEmpenho->sErroMsg);
            $iStatus = 2;
        } else {
            $objEmpenho->setRecriarSaldo($objJson->lRecriarReserva);
            $iCodAnu = $objEmpenho->anularEmpenho(
                $objJson->itensAnulados,
                $objJson->nValor,
                $objJson->sMotivo,
                $objJson->aSolicitacoes,
                $objJson->iTipoAnulacao
            );
            if ($objEmpenho->lSqlErro) {

                $nMensagem = urlencode($objEmpenho->sErroMsg);
                $iStatus = 2;
            } else {
                $nMensagem = '';
                $iStatus = 1;
            }
        }



        if ($resulparemetrosaldo->pc01_liberarsaldoposicao == 'f' && pg_num_rows($Sqlparemetrosaldo) > 0 && $iStatus == 1) {

            db_inicio_transacao();

            for ($iInd = 0; $iInd < count($aItens); $iInd++) {

                $acordoMaterial = db_query("SELECT ac26_acordo, ac20_pcmater
            FROM acordoitemexecutadoempautitem
            JOIN acordoitemexecutado ON ac19_acordoitemexecutado=ac29_sequencial
            JOIN acordoitem ON ac29_acordoitem=ac20_sequencial
            JOIN acordoposicao ON ac20_acordoposicao=ac26_sequencial
            JOIN empautitem ON ac19_autori=e55_autori
            AND ac19_sequen=e55_sequen
            JOIN empautoriza ON e55_autori=e54_autori
            JOIN empempaut ON e61_autori=e54_autori
            JOIN empempitem ON e62_numemp=e61_numemp and ac20_pcmater=e62_item
            WHERE e62_sequencial = {$aItens[$iInd]->e62_sequencial}");

                $rsacordoMaterial = db_utils::fieldsMemory($acordoMaterial, 0);

                $DaoacordoItemDotacao = db_utils::getDao('acordoitemdotacao');

                $Dotacao = $DaoacordoItemDotacao->sql_record("SELECT ac22_sequencial,ac22_valor,ac22_quantidade
            FROM acordoitemdotacao
            JOIN acordoitem ON ac20_sequencial = ac22_acordoitem
            JOIN acordoposicao ON ac20_acordoposicao = ac26_sequencial
            WHERE ac26_acordo = {$rsacordoMaterial->ac26_acordo}
            AND ac20_pcmater = {$rsacordoMaterial->ac20_pcmater}
            AND ac20_acordoposicao =
            (SELECT max(ac26_sequencial)
            FROM acordoposicao
            WHERE ac26_acordo = {$rsacordoMaterial->ac26_acordo} and ac26_acordoposicaotipo <> 1)");
                if (pg_num_rows($Dotacao) == 0) {
                    break;
                }

                $Dotacao = db_utils::fieldsMemory($Dotacao, 0);

                $DaoacordoItem = db_utils::getDao('acordoitem');

                $ItemUltimaPosicao = $DaoacordoItem->sql_record("
            SELECT ac20_sequencial,ac20_quantidade,ac20_valortotal,ac20_valorunitario,ac20_acordoposicao,ac20_servicoquantidade,pc01_servico
            FROM acordoitem
            inner join pcmater on pc01_codmater = ac20_pcmater
            inner join acordoposicao on ac26_sequencial = ac20_acordoposicao
            WHERE ac26_acordo = {$rsacordoMaterial->ac26_acordo}
            AND ac26_sequencial =
            (SELECT max(ac26_sequencial)
            FROM acordoposicao
            WHERE ac26_acordo = {$rsacordoMaterial->ac26_acordo})
            AND ac20_pcmater = {$rsacordoMaterial->ac20_pcmater} ");
                $ItemUltimaPosicao = db_utils::fieldsMemory($ItemUltimaPosicao, 0);


                $empempaut = db_query("select e61_autori from empempaut where e61_numemp = {$objJson->iEmpenho}");

                if (pg_num_rows($empempaut) == 0) {
                    print_r('erro empaut');
                    break;
                }

                $rsempempaut = db_utils::fieldsMemory($empempaut, 0);

                $empautitem = db_query("select e55_sequen from empautitem where e55_autori = {$rsempempaut->e61_autori} and e55_item = {$rsacordoMaterial->ac20_pcmater}");

                if (pg_num_rows($empautitem) == 0) {
                    print_r('erro empautitem');
                    break;
                }

                $rsempautitem = db_utils::fieldsMemory($empautitem, 0);

                $acordoitemexecutadoempautitem = db_query("select min(ac19_acordoitemexecutado) as itemexecutado from acordoitemexecutadoempautitem  where ac19_autori = {$rsempempaut->e61_autori} and ac19_sequen = {$rsempautitem->e55_sequen}");

                if (pg_num_rows($acordoitemexecutadoempautitem) == 0) {
                    print_r('erro executempaut');
                    break;
                }

                $rsacordoitemexecutadoempautitem = db_utils::fieldsMemory($acordoitemexecutadoempautitem, 0);

                $acordoitemexecutado = db_query("select ac29_acordoitem from acordoitemexecutado where ac29_sequencial = {$rsacordoitemexecutadoempautitem->itemexecutado}");

                if (pg_num_rows($acordoitemexecutado) == 0) {
                    print_r('erro itemexecutad');
                    break;
                }

                $rsacordoitemexecutado = db_utils::fieldsMemory($acordoitemexecutado, 0);

                $acordoitem = db_query("select ac20_acordoposicao from acordoitem where ac20_sequencial = {$rsacordoitemexecutado->ac29_acordoitem}");

                if (pg_num_rows($acordoitem) == 0) {
                    print_r('erro acordoitem');
                    break;
                }
                $rsacordoitem = db_utils::fieldsMemory($acordoitem, 0);
                //VERIFICA SE A POSICAO DE CRIACAO DO EMPENHO � DIFERENTE DA POSICAO FINAL DO ACORDO
                if ($rsacordoitem->ac20_acordoposicao != $ItemUltimaPosicao->ac20_acordoposicao) {


                    $DaoacordoItem = db_utils::getDao('acordoitemexecutado');
                    if ($ItemUltimaPosicao->ac20_servicoquantidade == 'f' && $ItemUltimaPosicao->pc01_servico == 't') {
                        //CRIA UMA POSICAO NA TABELA acordoitemexecutado CONTROLADO POR VALOR
                        $DaoacordoItem->ac29_acordoitem = $ItemUltimaPosicao->ac20_sequencial;
                        $DaoacordoItem->ac29_quantidade = -1;
                        $DaoacordoItem->ac29_valor = ($aItens[$iInd]->vlrtot) * -1;
                        $DaoacordoItem->ac29_tipo = 1;
                        $DaoacordoItem->ac29_observacao = 'liberarsaldoposicao';
                        $DaoacordoItem->ac29_automatico = 't';
                        $DaoacordoItem->ac29_datainicial = date('Y-m-d', db_getsession('DB_datausu'));
                        $DaoacordoItem->ac29_datafinal = date('Y-m-d', db_getsession('DB_datausu'));
                        $DaoacordoItem->incluir();
                    } else {
                        //CRIA UMA POSICAO NA TABELA acordoitemexecutado CONTROLADO POR QUANTIDADE
                        $DaoacordoItem->ac29_acordoitem = $ItemUltimaPosicao->ac20_sequencial;
                        $DaoacordoItem->ac29_quantidade = $aItens[$iInd]->quantidade * -1;
                        $DaoacordoItem->ac29_valor = ($aItens[$iInd]->quantidade * $ItemUltimaPosicao->ac20_valorunitario) * -1;
                        $DaoacordoItem->ac29_tipo = 1;
                        $DaoacordoItem->ac29_observacao = 'liberarsaldoposicao';
                        $DaoacordoItem->ac29_automatico = 't';
                        $DaoacordoItem->ac29_datainicial = date('Y-m-d', db_getsession('DB_datausu'));
                        $DaoacordoItem->ac29_datafinal = date('Y-m-d', db_getsession('DB_datausu'));
                        $DaoacordoItem->incluir();
                    }
                }
            }
        }

        if ($iStatus == 2) {
            db_fim_transacao(true);
        } else {
            db_fim_transacao(false);
            $nMensagem = '';
            $iStatus = 1;
        }

        echo $json->encode(array("mensagem" => $nMensagem, "status" => $iStatus, 'iCodAnu' => $iCodAnu));

        break;

    case "verificaRP":

        /*CASE CRIADA PARA VERIFICAR SE O PARAMETRO DO CONTRATO - RETORNO POR POSICAO ESTA ATIVO E SE O EMPENHO TEM VINCULO COM UM ACORDO
      CAMPO - pc01_liberarsaldoposicao
      FALSE - RETORNA PARA POSICAO FINAL
      TRUE - RETORNA PARA POSICAO VINCULADA
    */

        $Sqlparemetrosaldo = $paremetrosaldo->sql_record($paremetrosaldo->sql_query_file('', 'pc01_liberarsaldoposicao'));
        $resulparemetrosaldo  = db_utils::fieldsMemory($Sqlparemetrosaldo, 0);
        $aItens = $objJson->aItens;
        $iStatus = 1;
        $nMensagem = 'Usu�rio: ';

        //VERIFICA O PARAMETRO DO SALDO POR POSICAO
        if ($resulparemetrosaldo->pc01_liberarsaldoposicao == 'f' && pg_num_rows($Sqlparemetrosaldo) > 0 && $iStatus == 1) {
            for ($iInd = 0; $iInd < count($aItens); $iInd++) {
                //INICIA A VERERIFICA�AO DE TODOS AS LANA�AMENTOS CASO NAO EXISTA ELE FINALIZA POIS NAO PERTENCE A UM ACORDO OU NAO EXISTE LANCAMENTO
                $acordoMaterial = db_query("SELECT ac26_acordo, ac20_pcmater
            FROM acordoitemexecutadoempautitem
            JOIN acordoitemexecutado ON ac19_acordoitemexecutado=ac29_sequencial
            JOIN acordoitem ON ac29_acordoitem=ac20_sequencial
            JOIN acordoposicao ON ac20_acordoposicao=ac26_sequencial
            JOIN empautitem ON ac19_autori=e55_autori
            AND ac19_sequen=e55_sequen
		        JOIN empautoriza ON e55_autori=e54_autori
		        JOIN empempaut ON e61_autori=e54_autori
		        JOIN empempitem ON e62_numemp=e61_numemp and ac20_pcmater=e62_item
		        WHERE e62_sequencial = {$aItens[$iInd]->iCodItem}");

                $rsacordoMaterial = db_utils::fieldsMemory($acordoMaterial, 0);

                if (pg_num_rows($acordoMaterial) == 0) {
                    break;
                }


                $DaoacordoItem = db_utils::getDao('acordoitem');

                $ItemUltimaPosicao = $DaoacordoItem->sql_record("
            SELECT ac20_sequencial,ac20_quantidade,ac20_valortotal,ac20_valorunitario,ac20_acordoposicao,ac20_servicoquantidade,pc01_servico
            FROM acordoitem
            inner join pcmater on pc01_codmater = ac20_pcmater
            inner join acordoposicao on ac26_sequencial = ac20_acordoposicao
            WHERE ac26_acordo = {$rsacordoMaterial->ac26_acordo}
            AND ac26_sequencial =
            (SELECT max(ac26_sequencial)
            FROM acordoposicao
            WHERE ac26_acordo = {$rsacordoMaterial->ac26_acordo})
            AND ac20_pcmater = {$rsacordoMaterial->ac20_pcmater} ");

                if (pg_num_rows($ItemUltimaPosicao) == 0) {
                    break;
                }

                $ItemUltimaPosicao = db_utils::fieldsMemory($ItemUltimaPosicao, 0);


                $empempaut = db_query("select e61_autori from empempaut where e61_numemp = {$objJson->iEmpenho}");

                if (pg_num_rows($empempaut) == 0) {
                    break;
                }


                $rsempempaut = db_utils::fieldsMemory($empempaut, 0);


                $empautitem = db_query("select e55_sequen from empautitem where e55_autori = {$rsempempaut->e61_autori} and e55_item = {$rsacordoMaterial->ac20_pcmater}");

                if (pg_num_rows($empautitem) == 0) {
                    break;
                }

                $rsempautitem = db_utils::fieldsMemory($empautitem, 0);


                $acordoitemexecutadoempautitem = db_query("select min(ac19_acordoitemexecutado) as itemexecutado from acordoitemexecutadoempautitem  where ac19_autori = {$rsempempaut->e61_autori} and ac19_sequen = {$rsempautitem->e55_sequen}");

                if (pg_num_rows($acordoitemexecutadoempautitem) == 0) {
                    break;
                }

                $rsacordoitemexecutadoempautitem = db_utils::fieldsMemory($acordoitemexecutadoempautitem, 0);

                $acordoitemexecutado = db_query("select ac29_acordoitem from acordoitemexecutado where ac29_sequencial = {$rsacordoitemexecutadoempautitem->itemexecutado}");

                if (pg_num_rows($acordoitemexecutado) == 0) {
                    break;
                }

                $rsacordoitemexecutado = db_utils::fieldsMemory($acordoitemexecutado, 0);

                $acordoitem = db_query("select ac20_acordoposicao from acordoitem where ac20_sequencial = {$rsacordoitemexecutado->ac29_acordoitem}");

                if (pg_num_rows($acordoitem) == 0) {

                    break;
                }

                $rsacordoitem = db_utils::fieldsMemory($acordoitem, 0);

                $ItemAtualPosicao = $DaoacordoItem->sql_record("
            SELECT ac20_sequencial,ac20_quantidade,ac20_valortotal,ac20_valorunitario,ac20_acordoposicao,ac20_servicoquantidade
            FROM acordoitem
            JOIN acordoposicao ON ac20_acordoposicao = ac26_sequencial
            WHERE ac26_acordo = {$rsacordoMaterial->ac26_acordo}
            AND ac26_sequencial ={$rsacordoitem->ac20_acordoposicao}
            AND ac20_pcmater = {$rsacordoMaterial->ac20_pcmater} ");

                if (pg_num_rows($ItemAtualPosicao) == 0) {
                    break;
                }

                $ItemAtualPosicao = db_utils::fieldsMemory($ItemAtualPosicao, 0);


                $Dadosemp = $DaoacordoItem->sql_record("select e62_servicoquantidade,pcmater.pc01_servico from empempitem
            inner join pcmater on
            pcmater.pc01_codmater = empempitem.e62_item
            where e62_numemp = $objJson->iEmpenho and pc01_codmater= $rsacordoMaterial->ac20_pcmater");

                if (pg_num_rows($Dadosemp) == 0) {
                    break;
                }

                $rsDadosemp = db_utils::fieldsMemory($Dadosemp, 0);
                //FINALIZA A VERIFICACAO
                //CASO CHEGUE AQUI FAZ A COMPARACAO DOS DADOS DO EMPENHO COM A ULTIMA POSICAO SE TEM ALGUMA ALTERACAO

                if ($rsDadosemp->e62_servicoquantidade != $ItemUltimaPosicao->ac20_servicoquantidade) {
                    $nMensagem = "Usu�rio: N�o ser� poss�vel a anula��o do empenho.\n\nMotivo: A forma de controle do item " . $rsacordoMaterial->ac20_pcmater . " no empenho � diferente da posi��o atual do contrato!";
                    $iStatus = 2;
                    echo $json->encode(array("sMensagem" => urlencode($nMensagem), "iStatus" => $iStatus));
                    return;
                }
                if ($ItemUltimaPosicao->ac20_servicoquantidade == 'f' && $ItemUltimaPosicao->pc01_servico == 't') {
                } else if ($ItemUltimaPosicao->ac20_valorunitario != $ItemAtualPosicao->ac20_valorunitario) {
                    $nMensagem .= "Item " . $rsacordoMaterial->ac20_pcmater . ": O valor unit�rio atual do contrato � " . $ItemUltimaPosicao->ac20_valorunitario . " e o valor unit�rio do item a ser anulado � " . $ItemAtualPosicao->ac20_valorunitario . ". Ao anular os itens do empenho, o valor unitario ser� o " . $ItemUltimaPosicao->ac20_valorunitario . ".\n\n";
                    $iStatus = 3;
                }
            }
        }


        echo $json->encode(array("sMensagem" => urlencode($nMensagem), "iStatus" => $iStatus, "iTipo" => $objJson->iTipo));


        break;

    case "getDadosRP":

        if ($objEmpenho->getDadosRP($objJson->iTipoRP)) {
            echo $json->encode($objEmpenho->dadosEmpenho);
        } else {
            echo $json->encode(array("status" => 2, "sMensagem" => urlencode($objEmpenho->sErroMsg)));
        }
        break;

    case "estornarRP":

        try {

            $Sqlparemetrosaldo = $paremetrosaldo->sql_record($paremetrosaldo->sql_query_file('', 'pc01_liberarsaldoposicao'));
            $resulparemetrosaldo  = db_utils::fieldsMemory($Sqlparemetrosaldo, 0);
            $aItens = $objJson->aItens;

            $objEmpenho->buscaUltimoDocumentoExecutadoDoc($objJson->iEmpenho, 21, date('Y-m-d', db_getsession('DB_datausu')));

            if ($objEmpenho->lSqlErro) {
                $sMensagem = urlencode($objEmpenho->sErroMsg);
                $iStatus = 2;
            } else {

                db_inicio_transacao();
                $objEmpenho->estornarRP(
                    $objJson->iTipo,
                    $objJson->aNotas,
                    $objJson->sValorEstornar,
                    $objJson->sMotivo,
                    $objJson->aItens,
                    $objJson->tipoAnulacao,
                    $objJson->sAto,
                    $objJson->dDataAto
                );

                $iStatus = 1;
                $sMensagem = "Empenho estornado com sucesso";
            }

            if ($resulparemetrosaldo->pc01_liberarsaldoposicao == 'f' && pg_num_rows($Sqlparemetrosaldo) > 0 && $iStatus == 1) {


                for ($iInd = 0; $iInd < count($aItens); $iInd++) {

                    $acordoMaterial = db_query("SELECT ac26_acordo, ac20_pcmater
              FROM acordoitemexecutadoempautitem
              JOIN acordoitemexecutado ON ac19_acordoitemexecutado=ac29_sequencial
              JOIN acordoitem ON ac29_acordoitem=ac20_sequencial
              JOIN acordoposicao ON ac20_acordoposicao=ac26_sequencial
              JOIN empautitem ON ac19_autori=e55_autori
              AND ac19_sequen=e55_sequen
              JOIN empautoriza ON e55_autori=e54_autori
              JOIN empempaut ON e61_autori=e54_autori
              JOIN empempitem ON e62_numemp=e61_numemp and ac20_pcmater=e62_item
              WHERE e62_sequencial = {$aItens[$iInd]->iCodItem}");

                    $rsacordoMaterial = db_utils::fieldsMemory($acordoMaterial, 0);

                    if (pg_num_rows($acordoMaterial) == 0) {
                        break;
                    }


                    $DaoacordoItem = db_utils::getDao('acordoitem');

                    $ItemUltimaPosicao = $DaoacordoItem->sql_record("
              SELECT ac20_sequencial,ac20_quantidade,ac20_valortotal,ac20_valorunitario,ac20_acordoposicao,ac20_servicoquantidade,pc01_servico
              FROM acordoitem
              inner join pcmater on pc01_codmater = ac20_pcmater
              inner join acordoposicao on ac26_sequencial = ac20_acordoposicao
              WHERE ac26_acordo = {$rsacordoMaterial->ac26_acordo}
              AND ac26_sequencial =
              (SELECT max(ac26_sequencial)
              FROM acordoposicao
              WHERE ac26_acordo = {$rsacordoMaterial->ac26_acordo})
              AND ac20_pcmater = {$rsacordoMaterial->ac20_pcmater} ");

                    if (pg_num_rows($ItemUltimaPosicao) == 0) {
                        break;
                    }

                    $ItemUltimaPosicao = db_utils::fieldsMemory($ItemUltimaPosicao, 0);


                    $empempaut = db_query("select e61_autori from empempaut where e61_numemp = {$objJson->iEmpenho}");

                    if (pg_num_rows($empempaut) == 0) {
                        break;
                    }


                    $rsempempaut = db_utils::fieldsMemory($empempaut, 0);


                    $empautitem = db_query("select e55_sequen from empautitem where e55_autori = {$rsempempaut->e61_autori} and e55_item = {$rsacordoMaterial->ac20_pcmater}");

                    if (pg_num_rows($empautitem) == 0) {
                        break;
                    }

                    $rsempautitem = db_utils::fieldsMemory($empautitem, 0);


                    $acordoitemexecutadoempautitem = db_query("select min(ac19_acordoitemexecutado) as itemexecutado from acordoitemexecutadoempautitem  where ac19_autori = {$rsempempaut->e61_autori} and ac19_sequen = {$rsempautitem->e55_sequen}");

                    if (pg_num_rows($acordoitemexecutadoempautitem) == 0) {
                        break;
                    }

                    $rsacordoitemexecutadoempautitem = db_utils::fieldsMemory($acordoitemexecutadoempautitem, 0);

                    $acordoitemexecutado = db_query("select ac29_acordoitem from acordoitemexecutado where ac29_sequencial = {$rsacordoitemexecutadoempautitem->itemexecutado}");

                    if (pg_num_rows($acordoitemexecutado) == 0) {
                        break;
                    }

                    $rsacordoitemexecutado = db_utils::fieldsMemory($acordoitemexecutado, 0);

                    $acordoitem = db_query("select ac20_acordoposicao from acordoitem where ac20_sequencial = {$rsacordoitemexecutado->ac29_acordoitem}");

                    if (pg_num_rows($acordoitem) == 0) {

                        break;
                    }

                    $rsacordoitem = db_utils::fieldsMemory($acordoitem, 0);


                    $ItemAtualPosicao = $DaoacordoItem->sql_record("
              SELECT ac20_sequencial,ac20_quantidade,ac20_valortotal,ac20_valorunitario,ac20_acordoposicao,ac20_servicoquantidade
              FROM acordoitem
              JOIN acordoposicao ON ac20_acordoposicao = ac26_sequencial
              WHERE ac26_acordo = {$rsacordoMaterial->ac26_acordo}
              AND ac26_sequencial ={$rsacordoitem->ac20_acordoposicao}
              AND ac20_pcmater = {$rsacordoMaterial->ac20_pcmater} ");

                    if (pg_num_rows($ItemAtualPosicao) == 0) {
                        break;
                    }

                    $ItemAtualPosicao = db_utils::fieldsMemory($ItemAtualPosicao, 0);


                    $Dadosemp = $DaoacordoItem->sql_record("select e62_servicoquantidade,pcmater.pc01_servico from empempitem
          inner join pcmater on
          pcmater.pc01_codmater = empempitem.e62_item
          where e62_numemp = $objJson->iEmpenho and pc01_codmater= $rsacordoMaterial->ac20_pcmater");

                    if (pg_num_rows($Dadosemp) == 0) {
                        break;
                    }

                    $rsDadosemp = db_utils::fieldsMemory($Dadosemp, 0);


                    //VERIFICA SE A POSICAO DE CRIACAO DO EMPENHO � DIFERENTE DA POSICAO FINAL DO ACORDO
                    if ($rsacordoitem->ac20_acordoposicao != $ItemUltimaPosicao->ac20_acordoposicao) {


                        $DaoacordoItem = db_utils::getDao('acordoitemexecutado');
                        if ($ItemUltimaPosicao->ac20_servicoquantidade == 'f' && $ItemUltimaPosicao->pc01_servico == 't') {
                            //CRIA UMA POSICAO NA TABELA acordoitemexecutado CONTROLADO POR VALOR
                            $DaoacordoItem->ac29_acordoitem = $ItemUltimaPosicao->ac20_sequencial;
                            $DaoacordoItem->ac29_quantidade = -1;
                            $DaoacordoItem->ac29_valor = ($aItens[$iInd]->vlrtot) * -1;
                            $DaoacordoItem->ac29_tipo = 1;
                            $DaoacordoItem->ac29_observacao = 'liberarsaldoposicao';
                            $DaoacordoItem->ac29_automatico = 't';
                            $DaoacordoItem->ac29_datainicial = date('Y-m-d', db_getsession('DB_datausu'));
                            $DaoacordoItem->ac29_datafinal = date('Y-m-d', db_getsession('DB_datausu'));
                            $DaoacordoItem->incluir();
                        } else {
                            //CRIA UMA POSICAO NA TABELA acordoitemexecutado CONTROLADO POR QUANTIDADE
                            $DaoacordoItem->ac29_acordoitem = $ItemUltimaPosicao->ac20_sequencial;
                            $DaoacordoItem->ac29_quantidade = $aItens[$iInd]->quantidade * -1;
                            $DaoacordoItem->ac29_valor = ($aItens[$iInd]->quantidade * $ItemUltimaPosicao->ac20_valorunitario) * -1;
                            $DaoacordoItem->ac29_tipo = 1;
                            $DaoacordoItem->ac29_observacao = 'liberarsaldoposicao';
                            $DaoacordoItem->ac29_automatico = 't';
                            $DaoacordoItem->ac29_datainicial = date('Y-m-d', db_getsession('DB_datausu'));
                            $DaoacordoItem->ac29_datafinal = date('Y-m-d', db_getsession('DB_datausu'));
                            $DaoacordoItem->incluir();
                        }
                    }
                }

                if ($iStatus == 2) {
                    db_fim_transacao(true);
                } else {
                    db_fim_transacao(false);
                    $nMensagem = '';
                    $iStatus = 1;
                }
            }
        } catch (Exception $e) {

            $iStatus = 2;
            $sMensagem = urlencode($e->getMessage());
            db_fim_transacao(true);
        }
        echo $json->encode(array("sMensagem" => $sMensagem, "iStatus" => $iStatus));
        break;

    case "getDadosRP":

        if ($objEmpenho->getDados($objJson->iEmpenho)) {

            $rsNotas = $objEmpenho->getNotas($objJson->iEmpenho);
            if ($rsNotas) {

                for ($iNotas = 0; $iNotas < $objEmpenho->iNumRowsNotas; $iNotas++) {

                    $oNota = db_utils::fieldsMemory($rsNotas, $iNotas);
                    $oNota->temMovimentoConfigurado = false;
                    $oNota->temRetencao = false;
                    $oNota->VlrRetencao = 0;
                    /**
                     * Pesquisamos se existem algum movimento para essa nota.
                     */
                    $sWhereIni = "e50_codord = {$oNota->e50_codord} and e97_codforma is not null";
                    $sWhereIni .= " and corempagemov.k12_codmov is null and e81_cancelado is null";
                    $sJoin = " left join empagenotasordem on e81_codmov         = e43_empagemov  ";
                    $sJoin .= " left join empageordem      on e43_ordempagamento = e42_sequencial ";
                    $aMOvimentos = $oAgendaPagamento->getMovimentosAgenda($sWhereIni, $sJoin, false, false);
                    if (count($aMOvimentos) > 0) {

                        $oNota->temMovimentoConfigurado = true;
                    }

                    //Verifica se a nota possui reten��es lan�adas
                    $oRetencao = new retencaoNota($oNota->e69_codnota);
                    if ($oNota->e50_codord != "" && $oRetencao->getValorRetencao($oNota->e50_codord) > 0) {

                        $oNota->temRetencao = true;
                        $oNota->VlrRetencao = $oRetencao->getValorRetencao($oNota->e50_codord);
                    }
                    $objEmpenho->dadosEmpenho->aNotas[] = $oNota;
                }
            }

            echo $json->encode($objEmpenho->dadosEmpenho);
        } else {
            echo $json->encode(array("status" => 2, "sMensagem" => urlencode($objEmpenho->sErroMsg)));
        }
        break;

    case "getDadosNotas":

        if ($objEmpenho->getDados($objJson->iEmpenho)) {

            $rsNotas = $objEmpenho->getNotas($objJson->iEmpenho);
            if ($rsNotas) {

                for ($iNotas = 0; $iNotas < $objEmpenho->iNumRowsNotas; $iNotas++) {

                    $oNota = db_utils::fieldsMemory($rsNotas, $iNotas);
                    $oNota->temMovimentoConfigurado = false;
                    $oNota->temRetencao = false;
                    $oNota->VlrRetencao = 0;
                    /**
                     * Pesquisamos se existem algum movimento para essa nota.
                     */
                    $sWhereIni = "e50_codord = {$oNota->e50_codord} and e97_codforma is not null";
                    $sWhereIni .= " and corempagemov.k12_codmov is null and e81_cancelado is null";
                    $sJoin = " left join empagenotasordem on e81_codmov         = e43_empagemov  ";
                    $sJoin .= " left join empageordem      on e43_ordempagamento = e42_sequencial ";
                    $aMOvimentos = $oAgendaPagamento->getMovimentosAgenda($sWhereIni, $sJoin, false, false);
                    if (count($aMOvimentos) > 0) {

                        $oNota->temMovimentoConfigurado = true;
                    }

                    //Verifica se a nota possui reten��es lan�adas
                    $oRetencao = new retencaoNota($oNota->e69_codnota);
                    if ($oNota->e50_codord != "" && $oRetencao->getValorRetencao($oNota->e50_codord) > 0) {
                        $oNota->temRetencao = true;
                        $oNota->VlrRetencao = $oRetencao->getValorRetencao($oNota->e50_codord);
                    }

                    $objEmpenho->dadosEmpenho->aNotas[] = $oNota;
                }
            }
        }

        $objEmpenho->dadosEmpenho->isRestoPagar = $objEmpenho->dadosEmpenho->e60_anousu < db_getsession("DB_anousu");

        echo $json->encode($objEmpenho->dadosEmpenho);
        break;

    case "getItensNota":

        /**
         * Busca os ITENS da nota
         */
        $oDadosRetorno = new stdClass();
        $objEmpenho->setEncode(true);
        $aItens = $objEmpenho->getItensNota($objJson->iCodNota);

        /** Pegar o total dos valores de reten��o para esta OP */
        /** Retornar esses valores no JSON */
        $oRetencao = new retencaoNota($objJson->iCodNota);
        $oOrdemPag = new cl_pagordem();
        $nValorOP = $oRetencao->getValorOP();

        $sQueryQtdMovOP = $oOrdemPag->sql_query_movimento(
            null,
            "COUNT(e50_codord) as total",
            null,
            " (e50_codord = {$oRetencao->getCodOrd()}) AND (e81_cancelado IS NULL) "
        );
        $nValorRetencoes = $oRetencao->getRetencoesFromCodOrd(true);
        $nQtdMocOP = db_utils::fieldsMemory(db_query($sQueryQtdMovOP), 0)->total;

        if (!$aItens) {

            $oDadosRetorno->status = 1;
            $oDadosRetorno->sMensagem = "N�o foi poss�vel recuperar os itens da nota!";
        } else {

            $oDadosRetorno->status    = 2;
            $oDadosRetorno->iCodNota  = $objJson->iCodNota;
            $oDadosRetorno->iCodNota  = $objJson->iCodNota;
            $oDadosRetorno->iEmpenho  = $objJson->iEmpenho;
            $oDadosRetorno->aItens    = $aItens;
            $oDadosRetorno->nRetencoes  = $nValorRetencoes;
            $oDadosRetorno->nValorOP    = $nValorOP;
            $oDadosRetorno->nQtdMovOP   = $nQtdMocOP;
        }

        echo $json->encode($oDadosRetorno);
        break;

    case 'getContasFornecedor':

        $clpcfornecon = new cl_pcfornecon;
        $rsContaFornecedor = $clpcfornecon->sql_record($clpcfornecon->sql_query_lefpadrao(null,"pc63_contabanco, pc63_banco||' / '||pc63_agencia||'-'||pc63_agencia_dig||' / '||pc63_conta||'-'||pc63_conta_dig as conta,case when pc63_tipoconta = 1 then 'C/C' else 'C/I' end as tipoconta,case when pc64_contabanco is not null then 1 else 0 end as tipo","tipo DESC"," pc63_numcgm = {$objJson->iFornecedor} "));

        echo $json->encode(db_utils::getCollectionByRecord($rsContaFornecedor));
        break;

    case "getOrdenador":

        if ((isset($objJson->sNumEmpenho) && !empty($objJson->sNumEmpenho)) && (isset($objJson->sOrdenador) && !empty($objJson->sOrdenador))) {
                      
            foreach (listarOrdenadoresCGM($objJson->sNumEmpenho,$objJson->sOrdenador) as $assinante) {
              $o41_cgmordenador  = $assinante['z01_numcgm'];
              $o41_cgmordenadordescr = $assinante['nome'];
              $db243_data_inicio = $assinante['db243_data_inicio'];
              $db243_data_final  = $assinante['db243_data_final']; 
            }
          }

          $oDadosRetorno->status    = 2;
          $oDadosRetorno->db243_data_final  = $o41_cgmordenador;
          $oDadosRetorno->o41_cgmordenadordescr  = $o41_cgmordenadordescr ;
          $oDadosRetorno->db243_data_inicio  = $db243_data_inicio;
          $oDadosRetorno->db243_data_final    = $db243_data_final;
          echo $json->encode($oDadosRetorno);

        break;

    default:
        echo $objEmpenho->$method($objJson->iEmpenho, $objJson->notas, $objJson->historico, true,$objJson->dDataEstorno);
        break;
}

function listarOrdenadoresCGM($e60_numemp,$o41_cgmordenador) 
{

  $o58_anousu  = 0;
  $o58_instit  = 0;
  $o58_orgao   = 0;
  $o58_unidade = 0;
  $e56_autori  = 0;
  $clempautidot      = new cl_empautidot;
  $clorcunidade      = new cl_orcunidade;
  $oDaoElementos     = db_utils::getDao('orcelemento');

  $sWhereEmpenho     = " e60_numemp =  {$e60_numemp}";
  $sSqlBuscaElemento = $oDaoElementos->sql_query_estrut_empenho(null, "substr(o56_elemento,1,7) AS o56_elemento,e60_coddot,e60_emiss", null, $sWhereEmpenho);
  $rsBuscaElemento   = $oDaoElementos->sql_record($sSqlBuscaElemento);
  $coddotacao = db_utils::fieldsMemory($rsBuscaElemento, 0)->e60_coddot;

  $anoUsu = db_getsession("DB_anousu");
  $sWhere = "e56_coddot =	" . $coddotacao . " and ( e56_anousu = " . $anoUsu ." or e64_vlrpag > 0 ) and e61_numemp = ".$e60_numemp; 
  $rsResult = $clempautidot->sql_record($clempautidot->sql_query_dotacao_empenho(null, "*", null, $sWhere));
  $numrows = $clempautidot->numrows;

  if ($numrows > 0) {
     
      $o58_anousu  = db_utils::fieldsMemory($rsResult, 0)->o58_anousu;
      $o58_instit  = db_utils::fieldsMemory($rsResult, 0)->o58_instit;
      $o58_orgao   = db_utils::fieldsMemory($rsResult, 0)->o58_orgao;
      $o58_unidade = db_utils::fieldsMemory($rsResult, 0)->o58_unidade;
      $e56_autori  = db_utils::fieldsMemory($rsResult, 0)->e56_autori;
  }

  $anoUsu = db_getsession("DB_anousu");
  $results = DB::table('empenho.empautidot')
               ->join('orcdotacao', function($join) {
               $join->on('orcdotacao.o58_anousu', '=', 'empautidot.e56_anousu')
                    ->on('orcdotacao.o58_coddot', '=', 'empautidot.e56_coddot');
               })
               ->join('empautoriza', 'empautoriza.e54_autori', '=', 'empautidot.e56_autori')
               ->join('orcelemento', function($join) {
                   $join->on('orcelemento.o56_codele', '=', 'orcdotacao.o58_codele')
                        ->on('orcelemento.o56_anousu', '=', 'orcdotacao.o58_anousu');
               })
               ->join('empempaut', 'empempaut.e61_autori', '=', 'empautidot.e56_autori')
               ->join('empelemento', 'empelemento.e64_numemp', '=', 'empempaut.e61_numemp')
               ->leftJoin('orctiporec', 'orctiporec.o15_codigo', '=', 'empautidot.e56_orctiporec')
               ->select('*')
               ->where('empenho.empautidot.e56_autori', '=',$e56_autori)
               ->where(function ($query) {
                $query->where('empenho.empautidot.e56_anousu', '=', $anoUsu)
                      ->orWhere('empelemento.e64_vlrpag', '>', 0);
               })
               ->get()->toArray();
        
   foreach ($results as $autorizacao) {  
       $o58_instit  =  $autorizacao->o58_instit; 
       $o58_orgao   =  $autorizacao->o58_orgao; 
       $o58_unidade =  $autorizacao->o58_unidade; 
       $o58_anousu  =  $autorizacao->o58_anousu; 
   }   

   $aAssinantes = DB::table('configuracoes.assinatura_digital_assinante')
               ->join('configuracoes.db_usuarios', 'configuracoes.assinatura_digital_assinante.db243_usuario', '=', 'configuracoes.db_usuarios.id_usuario')
               ->join('configuracoes.db_usuacgm', 'configuracoes.db_usuacgm.id_usuario', '=', 'configuracoes.db_usuarios.id_usuario')
               ->join('protocolo.cgm', 'protocolo.cgm.z01_numcgm', '=', 'configuracoes.db_usuacgm.cgmlogin')
               ->where('configuracoes.assinatura_digital_assinante.db243_instit', '=', $o58_instit)
               ->where('configuracoes.assinatura_digital_assinante.db243_orgao', '=', $o58_orgao)
               ->where('configuracoes.assinatura_digital_assinante.db243_unidade', '=', $o58_unidade)
               ->where('configuracoes.assinatura_digital_assinante.db243_anousu', '=', $anoUsu)
               ->where('protocolo.cgm.z01_numcgm', '=', $o41_cgmordenador)
               ->where('configuracoes.assinatura_digital_assinante.db243_cargo', '=', 2)
               ->where('configuracoes.assinatura_digital_assinante.db243_documento', '=', 1)
               ->select('configuracoes.db_usuarios.login', 'configuracoes.db_usuarios.nome', 'configuracoes.db_usuarios.email', 'protocolo.cgm.z01_cgccpf' , 'configuracoes.assinatura_digital_assinante.db243_cargo','protocolo.cgm.z01_numcgm','configuracoes.assinatura_digital_assinante.db243_data_inicio','configuracoes.assinatura_digital_assinante.db243_data_final')
               ->distinct('login', 'nome', 'z01_cgccpf', 'db243_cargo')
               ->get()->toArray();         

   $rowCount = count($aAssinantes);  
   $ordenadoresArray = array(); 
   $uniqueKeys = array(); 
   
   foreach ($aAssinantes as $assinante) {
       $uniqueKey = $assinante->nome . '|' . $assinante->z01_numcgm;
           if (!in_array($uniqueKey, $uniqueKeys)) {
               $ordenadoresArray[] = [
                   'nome' => $assinante->nome,
                   'z01_numcgm' => $assinante->z01_numcgm,
                   'db243_data_inicio' => $assinante->db243_data_inicio,
                   'db243_data_final' => $assinante->db243_data_final,
                   'rowCount'    => $rowCount
               ];
               $uniqueKeys[] = $uniqueKey;
           }
   }
   return $ordenadoresArray;
}
