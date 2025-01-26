<?php

/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_liclicita_classe.php");
require_once("classes/db_liclicitaproc_classe.php");
require_once("classes/db_pctipocompra_classe.php");
require_once("classes/db_pctipocompranumero_classe.php");
require_once("classes/db_pccfeditalnum_classe.php");
require_once("classes/db_db_usuarios_classe.php");
require_once("classes/db_liclicitemlote_classe.php");
require_once("classes/db_liclicitem_classe.php");
require_once("classes/db_pcorcamitemlic_classe.php");
require_once("classes/db_pcprocitem_classe.php");
require_once("classes/db_pcproc_classe.php");
require_once("classes/db_pcorcamdescla_classe.php");
require_once("classes/db_cflicita_classe.php");
require_once("classes/db_homologacaoadjudica_classe.php");
require_once("classes/db_liccomissaocgm_classe.php");
require_once("classes/db_condataconf_classe.php");
require_once("classes/db_liccategoriaprocesso_classe.php");
require_once("classes/db_pccflicitapar_classe.php");
require_once("classes/db_pccflicitanum_classe.php");
require_once("classes/db_pccfeditalnum_classe.php");
include("classes/db_pcparam_classe.php");


parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$oPost = db_utils::postMemory($_POST);


$clliclicita          = new cl_liclicita;
$clliclicitaproc      = new cl_liclicitaproc;
$clpctipocompra       = new cl_pctipocompra;
$clpctipocompranumero = new cl_pctipocompranumero;
$cldb_usuarios        = new cl_db_usuarios;
$clliclicitemlote     = new cl_liclicitemlote;
$clliclicitem         = new cl_liclicitem;
$clpcorcamitemlic     = new cl_pcorcamitemlic;
$clpcorcamdescla      = new cl_pcorcamdescla;
$clcflicita           = new cl_cflicita;
$oDaoLicitaPar        = new cl_pccflicitapar;
$clhomologacao        = new cl_homologacaoadjudica;
$clliccomissaocgm     = new cl_liccomissaocgm;
$clpccfeditalnum      = new cl_pccfeditalnum;
$clpcprocitem         = new cl_pcprocitem;
$clpcproc             = new cl_pcproc;
$cliccategoriaprocesso = new cl_liccategoriaprocesso;
$clpcparam  = new cl_pcparam;
$clpccflicitapar     = new cl_pccflicitapar;
$clpccflicitanum     = new cl_pccflicitanum;
$clpccfeditalnum     = new cl_pccfeditalnum;


$db_opcao = 22;
$db_botao = true;
$sqlerro  = false;
$instit     = db_getsession("DB_instit");
$anousu     = db_getsession("DB_anousu");
$mostrar  = 0;

$result_tipo = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "*"));
if ($clpcparam->numrows > 0) {
    db_fieldsmemory($result_tipo, 0);
} else {
    $erro = true;
}

$oParamNumManual = db_query("select * from licitaparam;");
$oParamNumManual = db_utils::fieldsmemory($oParamNumManual, 0);
$l12_numeracaomanual = $oParamNumManual->l12_numeracaomanual;

if (isset($alterar)) {

    db_inicio_transacao();
    $db_opcao = 2;

    $erro_msg = "";

    if ($l12_numeracaomanual == 't') {

        /*
         * buscando informa��es da licita��o atual sem as altera��es feitas.
  	    */

        $licitacao = db_query("select * from liclicita where l20_codigo = $l20_codigo");
        $licitacao = db_utils::fieldsMemory($licitacao, 0);


        /*
         * Verifica��o se as numera��es ja foram utilizadas ao inserir as numera��es manualmente
         * em caso de altera��o de alguma das numera��es ao alterar a licita��o.
  	    */


        if ($l20_edital != $licitacao->l20_edital || $l20_nroedital != $licitacao->l20_nroedital ||  $l20_numero != $licitacao->l20_numero) {
            if ($l20_edital != $licitacao->l20_edital) {
                $oProcessoLicitatorio = db_query("select * from liclicita where l20_edital = $l20_edital and l20_anousu = $anousu and l20_instit = $instit;");
                if (pg_numrows($oProcessoLicitatorio) > 0) {
                    $erro_msg .= "J� existe licita��o com o processo licitat�rio n�mero $l20_edital\n\n";
                    $sqlerro = true;
                }
            }

            if ($l20_numero != $licitacao->l20_numero) {
                $oNumeracao = db_query("select * from liclicita where l20_numero = $l20_numero and l20_anousu = $anousu and l20_instit = $instit and l20_codtipocom = $l20_codtipocom;");
                if (pg_numrows($oNumeracao) > 0) {
                    $erro_msg .= "J� existe licita��o com a modalidade $l20_codtipocom numera��o $l20_numero\n\n";
                    $sqlerro = true;
                }
            }



            if ($l20_nroedital != $licitacao->l20_nroedital) {
                $oEdital = db_query("select * from liclicita where l20_anousu = $anousu and l20_instit = $instit and l20_nroedital = $l20_nroedital;");
                if (pg_numrows($oEdital) > 0) {
                    $erro_msg .= "J� existe licita��o com o edital $l20_nroedital\n\n";
                    $sqlerro = true;
                }
            }




            /* Verifica��o da numera��o do processo licitat�rio cujo o seu subsequente n�o tenha sido utilizado
				    e atualiza��o na tabela respons�vel por fazer o controle desta numera��o  */

            $edital = $l20_edital;
            do {
                $edital = $edital + 1;
                $oLicitacao = db_query("select * from liclicita where l20_anousu = $anousu and l20_instit = $instit and l20_edital = $edital;");
                if (pg_numrows($oLicitacao) == 0) {
                    $clpccflicitanum->l24_numero = $edital - 1;
                    $clpccflicitanum->alterar_where(null, "l24_instit=$instit and l24_anousu=$anousu");
                    break;
                }
            } while (1);

            /* Verifica��o da numera��o da licita��o cujo o seu subsequente n�o tenha sido utilizado
				    e atualiza��o na tabela respons�vel por fazer o controle desta numera��o  */
            $numeracao =  $l20_numero;

            $result_modalidade = $clpccflicitapar->sql_record($clpccflicitapar->sql_query_modalidade(null, "*", null, "l25_codcflicita = $l20_codtipocom and l25_anousu = $anousu and l03_instit = $instit"));
            db_fieldsmemory($result_modalidade, 0, 2);

            do {
                $numeracao = $numeracao + 1;
                $oLicitacao = db_query("select * from liclicita where l20_numero = $numeracao and l20_anousu = $anousu and l20_instit = $instit and l20_codtipocom = $l20_codtipocom;");
                if (pg_numrows($oLicitacao) == 0) {
                    $clpccflicitapar->l25_numero = $numeracao - 1;
                    $clpccflicitapar->alterar_where(null, "l25_codigo = $l25_codigo and l25_anousu = $anousu");
                    break;
                }
            } while (1);

            /* Verifica��o da numera��o do edital cujo o seu subsequente n�o tenha sido utilizado
				    e atualiza��o na tabela respons�vel por fazer o controle desta numera��o  */

            if ($l20_nroedital != null) {

                $numeroedital = $l20_nroedital;


                do {
                    $numeroedital = $numeroedital + 1;
                    $oLicitacao = db_query("select * from liclicita where l20_anousu = $anousu and l20_instit = $instit and l20_nroedital = $numeroedital;");
                    if (pg_numrows($oLicitacao) == 0) {
                        $clpccfeditalnum->l47_numero = $numeroedital - 1;
                        $clpccfeditalnum->l47_instit = db_getsession('DB_instit');
                        $clpccfeditalnum->l47_anousu = db_getsession('DB_anousu');
                        $clpccfeditalnum->incluir(null);

                        break;
                    }
                } while (1);
            }
        }
    }

    if ($sqlerro == false) {

        /*
            Valida��es dos membros da licita��o
            48 - Convite
            49 - Tomada de Pre�os
            50 - Concorr�ncia
            52 - Preg�o presencial
            53 - Preg�o eletr�nico
            54 - Leil�o
            */

        if ($oPost->modalidade_tribunal == 48 || $oPost->modalidade_tribunal == 49 || $oPost->modalidade_tribunal == 50 || $oPost->modalidade_tribunal == 52 || $oPost->modalidade_tribunal == 53 || $oPost->modalidade_tribunal == 54) {
            $salvarModalidade = 1;
            if ($respConducodigo == "") {
                $erro_msg .= 'Respons�vel pela condu��o do processo n�o informado\n\n';
                $nomeCampo = "respConducodigo";
                $sqlerro = true;
            }
            if ($respAbertcodigo == "") {
                $erro_msg .= 'Respons�vel pela abertura do processo n�o informado\n\n';
                $nomeCampo = "respAbertcodigo";
                $sqlerro = true;
            }
            if ($respEditalcodigo == "") {
                $erro_msg .= 'Respons�vel pela emiss�o do edital n�o informado\n\n';
                $nomeCampo = "respEditalcodigo";
                $sqlerro = true;
            }
            /*
            if ($respPubliccodigo == "") {
                $erro_msg .= 'Respons�vel pela publica��o n�o informado\n\n';
                $nomeCampo = "respPubliccodigo";
                $sqlerro = true;
            }
            */
            if ($oPost->l20_naturezaobjeto == 1) {
                if ($respObrascodigo == "") {
                    $erro_msg .= 'Respons�vel pelos or�amentos, obras e servi�os n�o informado\n\n';
                    $nomeCampo = "respObrascodigo";
                    $sqlerro = true;
                }
            }
            if ($oPost->modalidade_tribunal == 54) {
                if ($respAvaliBenscodigo == "") {
                    $erro_msg .= 'Respons�vel pela avalia��o de bens n�o informado\n\n';
                    $nomeCampo = "respAvaliBenscodigo";
                    $sqlerro = true;
                }
            }
        } else if ($oPost->modalidade_tribunal == 100 || $oPost->modalidade_tribunal == 101 || $oPost->modalidade_tribunal == 102 || $oPost->modalidade_tribunal == 103) {
            $salvarModalidade = 2;
            if ($respAutocodigo == "") {
                $erro_msg .= 'Respons�vel pela condu��o do processo n�o informado\n\n';
                $nomeCampo = "respAutocodigo";
                $sqlerro = true;
            }
            if ($oPost->l20_naturezaobjeto == 1) {
                if ($respObrascodigo == "") {
                    $erro_msg .= 'Respons�vel pelos or�amentos, obras e servi�os n�o informado\n\n';
                    $nomeCampo = "respObrascodigo";
                    $sqlerro = true;
                }
            }
        }

        if ($confirmado == 0) {
            $l20_tipojulg = $tipojulg;
        }

        $sWhereLicProc  = "l34_liclicita = {$l20_codigo}";
        $rsConsultaProc = $clliclicitaproc->sql_record($clliclicitaproc->sql_query_file(null, "*", null, $sWhereLicProc));
        $iLinhasLicProc = $clliclicitaproc->numrows;

        if ($iLinhasLicProc > 0) {

            $oLicProc = db_utils::fieldsMemory($rsConsultaProc, 0);

            if ($oLicProc->l34_protprocesso != $l34_protprocesso) {
                $clliclicitaproc->excluir(null, $sWhereLicProc);
                if ($clliclicitaproc->erro_status == 0) {
                    $sqlerro  = true;
                    $erro_msg = $clliclicitaproc->erro_msg;
                }
                $lIncluiProc = true;
            } else {
                $lIncluiProc = false;
            }
        } else {
            $lIncluiProc = true;
        }

        $sqlerro    = false;
        // ID's do l03_pctipocompratribunal com base no l20_codtipocom escolhido pelo usurio
        $sSql = $clcflicita->sql_query_file((int)$oPost->l20_codtipocom, 'distinct(l03_pctipocompratribunal)');
        $aCf = db_utils::getColectionByRecord($clcflicita->sql_record($sSql));
        $iTipoCompraTribunal = (int)$aCf[0]->l03_pctipocompratribunal;

        //Casos em que o Tipo de Licitao e Natureza do Procedimento devem ser verificados
        $aTipoLicNatProc = array(50, 48, 49, 53, 52, 54);

        $erro_msg = '';

        /*
      Verifica se os Campos "Tipo de Licitao", "Natureza do Procedimento" no foram selecionados.
        */
        if (in_array($iTipoCompraTribunal, $aTipoLicNatProc)) {
            if ($oPost->modalidade_tribunal != 51) {
                if ($oPost->l20_tipliticacao == '0' || empty($oPost->l20_tipliticacao)) {
                    $erro_msg .= 'Campo Tipo de Licitacao nao informado\n\n';
                    $sqlerro = true;
                }
            }
            if ($oPost->l20_tipnaturezaproced == '0' || empty($oPost->l20_tipnaturezaproced)) {
                $erro_msg .= 'Campo Natureza do Procedimento nao informado\n\n';
                $sqlerro = true;
            }
        }
        $oParamLicicita = db_stdClass::getParametro('licitaparam', array(db_getsession("DB_instit")));
        $l12_pncp = $oParamLicicita[0]->l12_pncp;

        // if ($l20_leidalicitacao == 1 && $l12_pncp == 't') {
        //     if ($oPost->l212_codigo == 0) {
        //         $erro_msg .= 'Campo Amparo Legal n�o informado\n\n';
        //         $sqlerro = true;
        //         $mostrar = 1;
        //     }
        // }

        if ($l20_categoriaprocesso == 0 && $l12_pncp == 't') {
            $erro_msg .= 'Campo categoria do processo n�o informado\n\n';
            $sqlerro = true;
        }

        //verifica modalidades e presencial
        $sSql = $clcflicita->sql_query_file(null, 'l03_presencial', '', "l03_codigo = $oPost->l20_codtipocom");
        $aCf = db_utils::getColectionByRecord($clcflicita->sql_record($sSql));
        $sPresencial = $aCf[0]->l03_presencial;

        if (in_array($iTipoCompraTribunal, $aTipoLicNatProc)) {
            if ($sPresencial == 't' && $l12_pncp == 't') {
                if ($oPost->l20_justificativapncp == '' || $oPost->l20_justificativapncp == null) {
                    $erro_msg .= 'Campo Justificativa PNCP n�o informado\n\n';
                    $sqlerro = true;
                }
            }
        }
        /*
      Verifica se o Campo "Natureza do Objeto" no foi selecionado.
        */
        if ($oPost->modalidade_tribunal != 51) {
            if ($oPost->l20_naturezaobjeto == '0' || empty($oPost->l20_naturezaobjeto)) {
                $erro_msg .= 'Campo Natureza do Objeto nao informado\n\n';
                $sqlerro = true;
            }
        }

        /*
        Verifica se o campo "Regime de execu��o" foi selecionado
        */

        if ($oPost->l20_naturezaobjeto == 1 || $oPost->l20_naturezaobjeto == 7) {
            if ($oPost->l20_regimexecucao == 0) {
                $erro_msg .= 'Campo Regime da Execu��o n�o selecionado\n\n';
                $sqlerro = true;
            }
        }

        if ($lIncluiProc && !$sqlerro && $lprocsis == 's') {

            $clliclicitaproc->l34_liclicita    = $l20_codigo;
            $clliclicitaproc->l34_protprocesso = $l34_protprocesso;
            $clliclicitaproc->incluir(null);

            if ($clliclicitaproc->erro_status == 0) {
                $sqlerro  = true;
                $erro_msg = $clliclicitaproc->erro_msg;
            }
            }

            if ($lprocsis == 's') {
            $sProcAdmin = " ";
            } else {
            $sProcAdmin = $l20_procadmin;
            }

            /*OC4448*/
            if ($iLinhasLicProc > 0 && $lprocsis == 'n') {


                $resultado = db_query("
                BEGIN;
                    delete from liclicitaproc where l34_liclicita = {$l20_codigo} and l34_protprocesso = {$l34_protprocesso};
                COMMIT;
                ");

                    if ($resultado == false) {
                        $erro_msg = "Erro ao desvincular processo do sistema!";
                        $sqlerro  = true;
                    }
            }

        $iNumero          = $l20_numero;
        $sSqlLicLicita    = $clliclicita->sql_query_file($l20_codigo, "l20_codtipocom");
        $rsLicLicita      = $clliclicita->sql_record($sSqlLicLicita);
        $iLinhasLicLicita = $clliclicita->numrows;

        $aModalidades = array(48, 49, 50, 52, 54);
        if (in_array($modalidade_tribunal, $aModalidades)) {
            if ($l20_nroedital) {
                $result_numedital = $clpccfeditalnum->sql_record($clpccfeditalnum->sql_query_file(null, "*", null, "l47_instit=$instit and l47_anousu=$anousu
      and l47_numero = $l20_nroedital"));
                $sql = $clliclicita->sql_query_file($l20_codigo, 'l20_nroedital as codigoEdital, l20_codigo as numeroLicitacao');
                $result = $clliclicita->sql_record($sql);
                db_fieldsmemory($result);

                if ($codigoedital != $l20_nroedital && $clpccfeditalnum->numrows == 0) {
                    $clpccfeditalnum->l47_numero = $l20_nroedital;
                    $clpccfeditalnum->l47_anousu = $anousu;
                    $clpccfeditalnum->l47_instit = $instit;
                    $clpccfeditalnum->incluir();
                }
            }
        }

        if ($iLinhasLicLicita > 0) {

            $iModalidade = db_utils::fieldsMemory($rsLicLicita, 0)->l20_codtipocom;

            if ($l20_codtipocom != $iModalidade) {

                $sWhereLicitaPar = "l03_codigo = {$l20_codtipocom} and l25_anousu = " . db_getsession("DB_anousu");
                $sSqlLicitaPar   = $oDaoLicitaPar->sql_query(null, "l25_codigo, l25_numero", null, $sWhereLicitaPar);
                $rsLicitaPar     = $oDaoLicitaPar->sql_record($sSqlLicitaPar);

                if ($oDaoLicitaPar->numrows > 0) {

                    $oDadosLicitaPar  = db_utils::fieldsMemory($rsLicitaPar, 0);
                    $iCodigoLicitaPar = $oDadosLicitaPar->l25_codigo;
                    $iNumero          = $oDadosLicitaPar->l25_numero;
                    $iNumero          = $iNumero + 1;

                    $oDaoLicitaPar->l25_numero = $iNumero;
                    $oDaoLicitaPar->alterar_where(null, "l25_codigo = {$iCodigoLicitaPar}");

                    if ($oDaoLicitaPar->erro_status == 0) {

                        $sqlerro  = true;
                        $erro_msg = $oDaoLicitaPar->erro_msg;
                    }
                } else {

                    $erro_msg = "Verifique se est� configurado a numera��o de licita��o por modalidade.";
                    $sqlerro  = true;
                }
            }

            $res_liclicitem     = $clliclicitem->sql_record($clliclicitem->sql_query_file(null, "l21_codigo,l21_codpcprocitem", "l21_codigo", "l21_codliclicita = $l20_codigo"));
            $numrows_liclicitem = $clliclicitem->numrows;
            if ($numrows_liclicitem > 0) {
                for ($i = 0; $i < $numrows_liclicitem; $i++) {

                    db_fieldsmemory($res_liclicitem, $i);

                    $valores_codpcprocitem[$i]       = $l21_codpcprocitem;
                }

                for ($i = 0; $i < $numrows_liclicitem; $i++) {

                    $res_pcprocitem    = $clpcprocitem->sql_record($clpcprocitem->sql_query_file(null, "pc81_codproc", "pc81_codproc", "pc81_codprocitem = $valores_codpcprocitem[$i]"));
                    $numrows_pcprocitem = $clpcprocitem->numrows;
                    db_fieldsmemory($res_pcprocitem, 0);
                    $valores_codproc[$i] = $pc81_codproc;
                }

                $val = 0;
                $op  = 0;
                for ($i = 0; $i < $numrows_liclicitem; $i++) {

                    $res_pcproc    = $clpcproc->sql_record($clpcproc->sql_query_file(null, "pc80_criterioadjudicacao", "pc80_criterioadjudicacao", "pc80_codproc = $valores_codproc[$i]"));
                    $numrows_pcproc = $clpcproc->numrows;
                    db_fieldsmemory($res_pcproc, 0);

                    if ($val != 0) {
                        if ($val == $pc80_criterioadjudicacao)
                            $op++;
                    } else {
                        $val = $pc80_criterioadjudicacao;
                    }
                }
            }
            if ($val != "" && $val != $l20_criterioadjudicacao) {
                $erro_msg = "Crit�rio de adjudica��o n�o corresponde aos itens de compras j� inseridos";
                $sqlerro = true;
                $mostrar = 1;
            } else if ($val == "") {
                //$sqlerro = false;
            }
        }

        /**
         * Verificar Encerramento Periodo Patrimonial
         */
        if (in_array($modalidade_tribunal, array(100, 101, 102, 103))) {
            $dtpubratificacao = db_utils::fieldsMemory(db_query($clliclicita->sql_query_file($l20_codigo, "l20_dtpubratificacao")), 0)->l20_dtpubratificacao;
        } else {
            $sSql = db_query($clhomologacao->sql_query_file('', "l202_datahomologacao", '', 'l202_licitacao = ' . $l20_codigo));
            $dtpubratificacao = db_utils::fieldsMemory($sSql, 0)->l202_datahomologacao;
        }

        if (!empty($dtpubratificacao)) {
            $clcondataconf = new cl_condataconf;
            if (!$clcondataconf->verificaPeriodoPatrimonial($dtpubratificacao) || !$clcondataconf->verificaPeriodoPatrimonial($l20_dtpubratificacao)) {
                $erro_msg = $clcondataconf->erro_msg;
                $sqlerro  = true;
            }
        }

        //verificar
//        if (in_array($modalidade_tribunal, [52, 53]) && $l20_leidalicitacao == "2") {
//
//            $verifica = $clliclicita->verificaMembrosModalidade("pregao", $l20_equipepregao);
//            if (!$verifica) {
//                $erro_msg = "Para as modalidades Preg�o presencial e Preg�o eletr�nico � necess�rio\nque a Comiss�o de Licita��o tenham os tipos Pregoeiro e Membro da Equipe de Apoio";
//                $sqlerro = true;
//            }
//        } else if (in_array($modalidade_tribunal, [48, 49, 50]) && $l20_leidalicitacao == "2") {
//
//            $verifica = $clliclicita->verificaMembrosModalidade("outros", $l20_equipepregao);
//            if (!$verifica) {
//                $erro_msg = "Para as modalidades Tomada de Pre�os, Concorr�ncia e Convite � necess�rio\nque a Comiss�o de Licita��o tenham os tipos Secret�rio, Presidente e Membro da Equipe de Apoio";
//                $sqlerro = true;
//            }
//        }

		//VALIDA�OES DE DATAS
		$dataaber = DateTime::createFromFormat('d/m/Y', $oPost->l20_dataaber);
		$datacria = DateTime::createFromFormat('d/m/Y', $oPost->l20_datacria);
		$dataaberproposta = DateTime::createFromFormat('d/m/Y', $oPost->l20_dataaberproposta);
        $aMod = array("100","101","102","103");

		if (!in_array($modalidade_tribunal, $aMod)) {
			if ($dataaberproposta < $dataaber) {
				$erro_msg = "A data informada no campo Abertura das Propostas deve ser  superior a Data Edital/Convite.";
				$nomeCampo = "l20_dataaberproposta";
				$sqlerro = true;
			}

			if ($dataaber < $datacria) {
				$erro_msg = "A data inserida no campo Data Emis/Alt Edital/Convite dever� ser maior ou igual a data inserida no campo Data Abertura Proc. Adm.";
				$nomeCampo = "l20_dataaber";
				$sqlerro = true;
			}

			if ($dataaberproposta < $datacria) {
				$erro_msg = "A data inserida no campo Data Abertura Proposta dever� ser maior ou igual a data inserida no campo Data Abertura Proc. Adm.";
				$nomeCampo = "l20_dataaberproposta";
				$sqlerro = true;
			}
		}

         if(in_array($modalidade_tribunal, $aMod) && $l20_leidalicitacao == 1){
		 	if($l20_tipoprocesso === "5" || $l20_tipoprocesso === '6'){
                $l20_criterioadjudicacaodispensa = $oPost->l20_criterioadjudicacao;
		 	}
		 }

        if($l20_tipnaturezaproced == "1" || $l20_tipnaturezaproced == "3"){
            $l20_usaregistropreco = "f";
        }

        if(($l20_tipoprocesso == '5' || $l20_tipoprocesso == '6') && $oPost->l20_criterioadjudicacaodispensa == '0'){

			$erro_msg = 'O campo crit�rio de adjudica��o � de preenchimento obrigat�rio"';
			$nomeCampo = 'l20_criteriodeadjudicacaodispensa';
			$sqlerro = true;
		}
        if($l20_tipoprocesso == '1' && $l20_dispensaporvalor == "" && $l20_leidalicitacao == '1'){
            $erro_msg = 'O campo Dispensa por valor � de preenchimento obrigat�rio';
            $nomeCampo = 'l20_dispensaporvalor';
            $sqlerro = true;
        }

        if($oPost->l20_dispensaporvalor == ""){
            $l20_dispensaporvalor = 'f';
        }
         $rsEdital = db_query("select * from liclancedital
             inner join liclicita on liclancedital.l47_liclicita = liclicita.l20_codigo
             where l20_codigo = $l20_codigo");
              if(pg_num_rows($rsEdital)){
                  $l20_cadinicial = 2;
              }else{
                  $l20_cadinicial = 1;
              }

              if($oPost->l20_dispensaporvalor == 't'){
                $l20_cadinicial = '0';
            }

        if ($sqlerro == false) {
            $clliclicita->l20_amparolegal       = $oPost->l212_codigo;
            $clliclicita->l20_numero       = $iNumero;
            $clliclicita->l20_procadmin    = $sProcAdmin;
            $clliclicita->l20_equipepregao = $l20_equipepregao;
            //$clliclicita->l20_horaaber     = $l20_horaaber;
            $clliclicita->l20_nroedital = $l20_nroedital;
            if($l20_criterioadjudicacaodispensa != '0'){
                $clliclicita->l20_criterioadjudicacao   = $oPost->l20_criterioadjudicacaodispensa; //OC3770
            }else{
                $clliclicita->l20_criterioadjudicacao   = $l20_criterioadjudicacao;
            }
            $clliclicita->l20_exercicioedital       = $oPost->l20_datacria_ano;
            $clliclicita->l20_justificativapncp     = $oPost->l20_justificativapncp;
            $clliclicita->l20_categoriaprocesso     = $oPost->l20_categoriaprocesso;
            $clliclicita->l20_receita               = $oPost->l20_receita;
            $clliclicita->l20_dataaber              = $oPost->l20_dataaber;
            $clliclicita->l20_datacria              = $oPost->l20_datacria;
            $clliclicita->l20_recdocumentacao       = $oPost->l20_dataaberproposta;
            $clliclicita->l20_dataaberproposta      = $oPost->l20_dataaberproposta;
            $clliclicita->l20_cadinicial            = $l20_cadinicial;
            $clliclicita->l20_dispensaporvalor      = $oPost->l20_dispensaporvalor;
            $clliclicita->l20_lances      = $oPost->l20_lances;


            $clliclicita->alterar($l20_codigo, $descricao);

            if ($clliclicita->erro_status == "0") {
                $erro_msg = $clliclicita->erro_msg;
                $sqlerro = true;
            }
        }


        /**
         * Acoes na troca de tipo de julgamento
         *
         * Se tipojulg == 1 era Por Item quando for trocado:
         *    l20_tipojulg == 2(Global)   - UPDATE NA TABELA liclicitemlote
         *    l20_tipojulg == 3(Por lote) - DELETE
         *
         *
         * Se tipojulg == 2 era Global quando for trocado:
         *    l20_tipojulg == 1(Por item) - UPDATE NA TABELA liclicitemlote
         *    l20_tipojulg == 3(Por lote) - DELETE
         *
         *
         * Se tipojulg == 3 era Por Lote quando for trocado:
         *    l20_tipojulg == 1(Por item) - DELETE, INSERT NA TABELA liclicitemlote
         *    l20_tipojulg == 2(Global)   - DELETE, INSERT NA TABELA liclicitemlote
         */
        if ($sqlerro == false) {

            if ($tipojulg != $l20_tipojulg && $confirmado == 1) {

                $res_liclicitem     = $clliclicitem->sql_record($clliclicitem->sql_query_file(null, "l21_codigo", "l21_codigo", "l21_codliclicita = $l20_codigo"));
                $numrows_liclicitem = $clliclicitem->numrows;

                $lista_liclicitem   = "";
                $lista_l21_codigo   = "";
                $virgula            = "";

                for ($i = 0; $i < $numrows_liclicitem; $i++) {

                    db_fieldsmemory($res_liclicitem, $i);

                    $lista_liclicitem .= $virgula . $l21_codigo;
                    $lista_l21_codigo .= $virgula . $l21_codigo;
                    $virgula           = ", ";
                }

                if (strlen($lista_liclicitem) > 0) {
                    $lista_liclicitem = "l04_liclicitem in (" . $lista_liclicitem . ")";
                }

                if ($sqlerro == false  && strlen($lista_liclicitem) > 0) {
                    if ($tipojulg == 1) {  // Por item
                        if ($l20_tipojulg == 2) {  // Trocou para GLOBAL
                            $sql = "update liclicitemlote set l04_descricao = 'GLOBAL' where " . $lista_liclicitem;
                            $clliclicitemlote->sql_record($sql);
                        }

                        if ($l20_tipojulg == 3) {   // Trocou para LOTE

                            $clliclicitemlote->excluir(null, $lista_liclicitem);
                            if ($clliclicitemlote->erro_status == "0") {
                                $sqlerro = true;
                            }
                        }
                    }

                    if ($tipojulg == 2) {  // Global
                        if ($l20_tipojulg == 1) {   // Trocou para ITEM
                            $res_solicitem     = $clliclicitem->sql_record($clliclicitem->sql_query(null, "l21_codigo,pc11_codigo", "l21_codigo", "l21_codigo in ($lista_l21_codigo)"));
                            $numrows_solicitem = $clliclicitem->numrows;

                            if ($numrows_solicitem == 0) {
                                $sqlerro = true;
                            }

                            for ($i = 0; $i < $numrows_solicitem; $i++) {
                                db_fieldsmemory($res_solicitem, $i);

                                $l04_descricao = "LOTE_AUTOITEM_" . $pc11_codigo;
                                $sql           = "update liclicitemlote set l04_descricao = '$l04_descricao'
                                 where l04_liclicitem = $l21_codigo";

                                $clliclicitemlote->sql_record($sql);
                            }
                        }

                        if ($l20_tipojulg == 3) {   // Trocou para LOTE
                            $clliclicitemlote->excluir(null, $lista_liclicitem);
                            if ($clliclicitemlote->erro_status == "0") {
                                $sqlerro = true;
                            }
                        }
                    }

                    if ($tipojulg == 3) {  // Por lote

                        // Testa se existe lote anterior para fazer insert caso nao exista
                        $res_liclicitemlote     = $clliclicitemlote->sql_record($clliclicitemlote->sql_query_file(null, "l04_liclicitem", "l04_liclicitem", "l04_liclicitem in ($lista_l21_codigo)"));
                        $numrows_liclicitemlote = $clliclicitemlote->numrows;

                        $res_solicitem          = $clliclicitem->sql_record($clliclicitem->sql_query(null, "l21_codigo,pc11_codigo", "l21_codigo", "l21_codigo in ($lista_l21_codigo)"));
                        $numrows_solicitem      = $clliclicitem->numrows;

                        if ($l20_tipojulg == 1) {   // Trocou para ITEM

                            if ($numrows_solicitem == 0) {
                                $sqlerro = true;
                            }
                            $sequenciallote = 0;
                            for ($i = 0; $i < $numrows_solicitem; $i++) {

                                db_fieldsmemory($res_solicitem, $i);
                                $sequenciallote++;
                                $l04_descricao = "LOTE_AUTOITEM_" . $pc11_codigo;

                                if ($numrows_liclicitemlote == 0) {

                                    $clliclicitemlote->l04_descricao  = $l04_descricao;
                                    $clliclicitemlote->l04_liclicitem = $l21_codigo;
                                    $clliclicitemlote->l04_seq = $sequenciallote;
                                    $clliclicitemlote->incluir(null);
                                    if ($clliclicitemlote->erro_status == "0") {
                                        $sqlerro = true;
                                        break;
                                    }
                                } else {
                                    $sql = "update liclicitemlote set l04_descricao = '$l04_descricao'
                         where l04_liclicitem = $l21_codigo";
                                    $clliclicitemlote->sql_record($sql);
                                }
                            }
                        }

                        if ($l20_tipojulg == 2) {   // Trocou para GLOBAL
                            if ($numrows_liclicitemlote == 0) {
                                if ($numrows_solicitem == 0) {
                                    $sqlerro = true;
                                }
                                $sequencial = 0;
                                for ($i = 0; $i < $numrows_solicitem; $i++) {

                                    db_fieldsmemory($res_solicitem, $i);
                                    $sequencial++;
                                    $l04_descricao = "GLOBAL";
                                    $clliclicitemlote->l04_descricao  = $l04_descricao;
                                    $clliclicitemlote->l04_liclicitem = $l21_codigo;
                                    $clliclicitemlote->l04_seq = $sequencial;
                                    $clliclicitemlote->incluir(null);

                                    if ($clliclicitemlote->erro_status == "0") {
                                        $sqlerro = true;
                                        break;
                                    }
                                }
                            } else {
                                $sql = "update liclicitemlote set l04_descricao = 'GLOBAL' where " . $lista_liclicitem;
                                $clliclicitemlote->sql_record($sql);
                            }
                        }
                    }
                }

                /**
                 * Corrigimos os dados do lote
                 */
                if ($l20_tipojulg == 3) {

                    $sSqlItensProcesso  = $clliclicitem->sql_query_proc(
                        null,
                        "l21_codigo, pc68_nome",
                        "l21_codigo",
                        "l21_codliclicita = {$l20_codigo}
                                                            and pc68_sequencial is not null"
                    );
                    $rsItensProcesso    = $clliclicitem->sql_record($sSqlItensProcesso);
                    $iItensProcesso     = $clliclicitem->numrows;
                    if ($iItensProcesso > 0) {
                        $sequencial = 0;
                        for ($iItem = 0; $iItem < $iItensProcesso; $iItem++) {

                            $oDadosLote = db_utils::fieldsMemory($rsItensProcesso, $iItem);

                            $sequencial++;

                            $clliclicitemlote->l04_descricao  = $oDadosLote->pc68_nome;
                            $clliclicitemlote->l04_liclicitem = $oDadosLote->l21_codigo;
                            $clliclicitemlote->l04_seq = $sequencial;
                            $clliclicitemlote->incluir(null);
                            if ($clliclicitemlote->erro_status == 0) {

                                $sqlerro = true;
                                break;
                            }
                        }
                    }
                }

                if ($sqlerro == false && strlen($lista_l21_codigo) > 0) {

                    $res_pcorcamitemlic = $clpcorcamitemlic->sql_record($clpcorcamitemlic->sql_query(null, "pc22_orcamitem", null, "pc26_liclicitem in ($lista_l21_codigo)"));
                    $numrows_itemlic    = $clpcorcamitemlic->numrows;

                    for ($i = 0; $i < $numrows_itemlic; $i++) {
                        db_fieldsmemory($res_pcorcamitemlic, $i);

                        $clpcorcamdescla->excluir(null, null, "pc32_orcamitem = $pc22_orcamitem");

                        if ($clpcorcamdescla->erro_status == "0") {
                            $sqlerro = true;
                            break;
                        }
                    }
                }

                if ($sqlerro == true) {
                    $erro_msg = "Modificacoes nao foram alteradas.Verificar dados desta licitacao.";
                }
            }
        }
        if ($salvarModalidade == 1) {
            if ($respConducodigo != "") {
                //excluir o reponsavel
                $dbquery = "l31_tipo = '5' and l31_licitacao = $l20_codigo";
                $clliccomissaocgm->excluir(null, $dbquery);

                $clliccomissaocgm->l31_numcgm = $respConducodigo;
                $clliccomissaocgm->l31_tipo = 5;
                $clliccomissaocgm->l31_licitacao = $l20_codigo;
                $clliccomissaocgm->incluir(null);
            }
            if ($respAbertcodigo != "") {
                //excluir o reponsavel
                $dbquery = "l31_tipo = '1' and l31_licitacao = $l20_codigo";
                $clliccomissaocgm->excluir(null, $dbquery);

                $clliccomissaocgm->l31_numcgm = $respAbertcodigo;
                $clliccomissaocgm->l31_tipo = 1;
                $clliccomissaocgm->l31_licitacao = $l20_codigo;
                $clliccomissaocgm->incluir(null);
            }
            if ($respEditalcodigo != "") {
                //excluir o reponsavel
                $dbquery = "l31_tipo = '2' and l31_licitacao = $l20_codigo";
                $clliccomissaocgm->excluir(null, $dbquery);

                $clliccomissaocgm->l31_numcgm = $respEditalcodigo;
                $clliccomissaocgm->l31_tipo = 2;
                $clliccomissaocgm->l31_licitacao = $l20_codigo;
                $clliccomissaocgm->incluir(null);
            }
            /*
            if ($respPubliccodigo != "") {
                //excluir o reponsavel
                $dbquery = "l31_tipo = '8' and l31_licitacao = $l20_codigo";
                $clliccomissaocgm->excluir(null, $dbquery);

                $clliccomissaocgm->l31_numcgm = $respPubliccodigo;
                $clliccomissaocgm->l31_tipo = 8;
                $clliccomissaocgm->l31_licitacao = $l20_codigo;
                $clliccomissaocgm->incluir(null);
            }*/
            if ($respObrascodigo != "") {
                //excluir o reponsavel
                $dbquery = "l31_tipo = '10' and l31_licitacao = $l20_codigo";
                $clliccomissaocgm->excluir(null, $dbquery);

                $clliccomissaocgm->l31_numcgm = $respObrascodigo;
                $clliccomissaocgm->l31_tipo = 10;
                $clliccomissaocgm->l31_licitacao = $l20_codigo;
                $clliccomissaocgm->incluir(null);
            }
            if ($respAvaliBenscodigo != "") {
                //excluir o reponsavel
                $dbquery = "l31_tipo = '9' and l31_licitacao = $l20_codigo";
                $clliccomissaocgm->excluir(null, $dbquery);

                $clliccomissaocgm->l31_numcgm = $respAvaliBenscodigo;
                $clliccomissaocgm->l31_tipo = 9;
                $clliccomissaocgm->l31_licitacao = $l20_codigo;
                $clliccomissaocgm->incluir(null);
            }
        } else if ($salvarModalidade == 2) {
            if ($respConducodigo != "") {
                //excluir o reponsavel
                $dbquery = "l31_tipo = '5' and l31_licitacao = $l20_codigo";
                $clliccomissaocgm->excluir(null, $dbquery);
            }
            if ($respAbertcodigo != "") {
                //excluir o reponsavel
                $dbquery = "l31_tipo = '1' and l31_licitacao = $l20_codigo";
                $clliccomissaocgm->excluir(null, $dbquery);
            }
            if ($respEditalcodigo != "" && $l20_dtpubratificacao != "") {
                //excluir o reponsavel
                $dbquery = "l31_tipo = '2' and l31_licitacao = $l20_codigo";
                $clliccomissaocgm->excluir(null, $dbquery);
            }
            /*
            if ($respPubliccodigo != "") {
                //excluir o reponsavel
                $dbquery = "l31_tipo = '8' and l31_licitacao = $l20_codigo";
                $clliccomissaocgm->excluir(null, $dbquery);
            }
            */
            if ($respObrascodigo != "") {
                //excluir o reponsavel
                $dbquery = "l31_tipo = '10' and l31_licitacao = $l20_codigo";
                $clliccomissaocgm->excluir(null, $dbquery);

                $clliccomissaocgm->l31_numcgm = $respObrascodigo;
                $clliccomissaocgm->l31_tipo = 10;
                $clliccomissaocgm->l31_licitacao = $l20_codigo;
                $clliccomissaocgm->incluir(null);
            }
            if ($respAvaliBenscodigo != "") {
                //excluir o reponsavel
                $dbquery = "l31_tipo = '9' and l31_licitacao = $l20_codigo";
                $clliccomissaocgm->excluir(null, $dbquery);
            }
            if ($respAutocodigo != "") {
                //excluir o reponsavel
                $dbquery = "l31_tipo = '1' and l31_licitacao = $l20_codigo";
                $clliccomissaocgm->excluir(null, $dbquery);

                $clliccomissaocgm->l31_numcgm = $respAutocodigo;
                $clliccomissaocgm->l31_tipo = 1;
                $clliccomissaocgm->l31_licitacao = $l20_codigo;
                $clliccomissaocgm->incluir(null);
            }
        }
    }

    db_fim_transacao($sqlerro);
} else if (isset($chavepesquisa)) {
    /*$result =  $clhomologacao->sql_record($clhomologacao->sql_query_file(null,"*","","l202_licitacao = {$chavepesquisa}"));
    if ($clhomologacao->numrows > 0) {
        $db_opcao==22;
        unset($chavepesquisa);
        db_msgbox("Esta licita��o j� est� homologada.");
    } else {*/

    $db_opcao  = 2;
    $sCampos   = '*, ';
    $sCampos  .= "(select count(*) from liclicitem where l21_codliclicita = l20_codigo) as itens_lancados";
    $result = $clliclicita->sql_record($clliclicita->sql_query($chavepesquisa, $sCampos));

    if ($clliclicita->numrows > 0) {

        db_fieldsmemory($result, 0);

        if ($l08_altera == "t") {
            $db_botao = true;
        }

        if (isset($l34_protprocesso) && trim($l34_protprocesso) != '') {
            $l34_protprocessodescr = $p58_requer;
        }

        $tipojulg = $l20_tipojulg;

        if (!empty($p58_numero)) {

            $p58_numero       = "{$p58_numero}/{$p58_ano}";
            $l34_protprocesso = $p58_codproc;
        }
    }

    if (db_getsession("DB_anousu") >= 2016) {

        echo "<script>
               parent.iframe_liclicitem.location.href='lic1_liclicitemalt001.php?licitacao=$chavepesquisa&tipojulg=" . @$tipojulg . "';\n
               parent.document.formaba.liclicitem.disabled=false;\n

             </script>";

        echo "<script>
            parent.iframe_resplicita.location.href='lic1_resplicitacao001.php?l20_naturezaobjeto=$l20_naturezaobjeto&l31_licitacao=$l20_codigo&l20_codtipocom=$l20_codtipocom';
    </script>";
    } else {
        echo "<script>
               parent.iframe_liclicitem.location.href='lic1_liclicitemalt001.php?licitacao=$chavepesquisa&tipojulg=" . @$tipojulg . "';\n
                  parent.iframe_liclicpublicacoes.location.href='lic1_liclicpublicacao001.php?licitacao=$chavepesquisa&tipojulg=" . @$tipojulg . "';\n
               parent.document.formaba.liclicitem.disabled=false;\n
             </script>";
    }

    $aNatProcedimentosValidosParaDotacao = array(0,1);
    if ($pc30_permsemdotac == "t" && in_array($l20_tipnaturezaproced,$aNatProcedimentosValidosParaDotacao)) {
        echo "<script>

        parent.iframe_dotacoesnovo.location.href='com1_dotacoesnovo001lic.php?licitacao=$chavepesquisa&tipojulg=" . @$tipojulg . "';\n
        parent.document.getElementById('dotacoesnovo').style.display='block';\n
        parent.document.formaba.dotacoesnovo.disabled=false;\n

        </script>";
    } else {
        echo "<script>

        parent.iframe_dotacoesnovo.location.href='com1_dotacoesnovo001lic.php?licitacao=$chavepesquisa&tipojulg=" . @$tipojulg . "';\n
        parent.document.getElementById('dotacoesnovo').style.display='none'\n

        </script>";
    }

    $script = "<script>";
    if (isset($tipojulg) && $tipojulg == 3) {
        $script .= "parent.iframe_liclicitemlote.location.href='lic1_liclicitemlote001.php?licitacao=$chavepesquisa&tipojulg=" . @$tipojulg . "';\n
                  parent.document.formaba.liclicitemlote.disabled=false;\n";
        $script .= "parent.document.getElementById('liclicitemlote').style.display='block';\n";
    } else {
        $script .= "parent.document.formaba.liclicitemlote.disabled=true;\n";
    }

    $script .= "</script>\n";
    echo $script;

    $clliclicita->sql_record($clliclicita->sql_query('', '*', '', "l20_codigo = $l20_codigo and l20_naturezaobjeto = 6"));

    if ($cllicita->numrows > 0) {

        $clliccomissaocgm->sql_record($clliccomissaocgm->sql_query('', 'distinct l31_tipo', '', "l31_licitacao = $l20_codigo
      and l31_tipo::int in (1,2,3,4,5,6,7,8,9)"));
        if ($clliccomissaocgm->numrows == 9) {
            $script = "<script>
        parent.document.formaba.liclicitem.disabled=false;
        parent.document.formaba.resplicita.disabled=false;
        </script>";
            echo $script;
        } else if ($clliccomissaocgm->numrows > 0 and $clliccomissaocgm->numrows < 9) {
            $script = "<script>
        parent.document.formaba.resplicita.disabled=false;
        </script>";
            echo $script;
        }
    } else {

        $clliccomissaocgm->sql_record($clliccomissaocgm->sql_query('', 'distinct l31_tipo', '', "l31_licitacao = $l20_codigo
      and l31_tipo::int in (1,2,3,4,5,6,7,8)"));
        if ($clliccomissaocgm->numrows == 8) {
            $script = "<script>
        parent.document.formaba.liclicitem.disabled=false;
        parent.document.formaba.resplicita.disabled=false;
        </script>";
            echo $script;
        } else if ($clliccomissaocgm->numrows > 0 and $clliccomissaocgm->numrows < 8) {
            $script = "<script>
        parent.document.formaba.resplicita.disabled=false;
        </script>";
            echo $script;
        }
    }
}
?>
<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.maskedinput.js"></script>

    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
                <center>
                    <?
                    include("forms/db_frmliclicita.php");
                    ?>
                </center>
            </td>
        </tr>
    </table>
    <script>
        function js_confirmar() {
            var tipojulg = document.form1.tipojulg.value;
            var julgamento = document.form1.l20_tipojulg.value;

            if (tipojulg != julgamento) {
                if (confirm("Todos os dados da licitacao serao modificados. Confirma?") == false) {
                    document.form1.pesquisar.click();
                    document.form1.confirmado.value = 0;
                    return false;
                } else {
                    document.form1.confirmado.value = 1;
                    return true;
                }
            } else {
                document.form1.confirmado.value = 0;
                return true;
            }
        }
    </script>
    <?
    //db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>

</html>
<?
if (isset($alterar)) {
    if ($sqlerro == true) {
        if ($mostrar == 0) {
            db_msgbox($erro_msg);
        }
        $clliclicita->erro_msg = $erro_msg;
        $clliclicita->erro_status = 0;
        $clliclicita->erro(true, false);
        $db_botao = true;
        echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
        echo "<script>location.href='lic1_liclicita002.php?chavepesquisa=$l20_codigo';</script>";
        if ($clliclicita->erro_campo != "") {
            echo "<script> document.form1." . $clliclicita->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1." . $clliclicita->erro_campo . ".focus();</script>";
        };
    } else {
        //$clliclicita->erro(true,true);
        $db_botao = true;
        db_msgbox("Altera��o Efetuada com Sucesso!!");
        if (db_getsession("DB_anousu") >= 2016) {
            echo "<script> mo_camada('liclicitem'); </script>";
            echo "<script>parent.document.formaba.liclicitem.disabled=false;</script>";

            if ($l20_tipojulg == 3) {
                echo "<script>parent.document.formaba.liclicitemlote.disabled=false;</script>";
                echo "<script>parent.document.getElementById('liclicitemlote').style.display='block';\n</script>";
            }

            echo "<script>location.href='lic1_liclicita002.php?chavepesquisa=$l20_codigo';</script>";
        } else {
            echo "<script> mo_camada('liclicitem'); </script>";

            echo "<script>parent.document.formaba.liclicitem.disabled=false;</script>";
            echo "<script>location.href='lic1_liclicita002.php?chavepesquisa=$l20_codigo';</script>";
        }
    };
};
if ($db_opcao == 22) {
    echo "<script>document.form1.pesquisar.click();</script>";
}
?>
