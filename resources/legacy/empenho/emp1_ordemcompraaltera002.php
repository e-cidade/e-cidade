<?
//ini_set('display_errors','on');
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

//echo ($HTTP_SERVER_VARS['QUERY_STRING']);exit;
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_utils.php");
include("classes/db_cgm_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_db_almoxdepto_classe.php");
include("classes/db_matordem_classe.php");
include("classes/db_matordemanu_classe.php");
include("classes/db_matparam_classe.php");
include("classes/db_matordemitem_classe.php");
include("classes/db_empempenho_classe.php");
include("classes/db_matestoqueitemoc_classe.php");

$clcgm                        = new cl_cgm;
$clmatordem                    = new cl_matordem;
$clmatparam                    = new cl_matparam;
$cldbdepart                 = new cl_db_depart;
$clempempenho                = new cl_empempenho;
$clmatordemanu                = new cl_matordemanu;
$clmatordemitem                = new cl_matordemitem;
$cldb_almoxdepto            = new cl_db_almoxdepto;
$clmatestoqueitemoc         = new cl_matestoqueitemoc;
$clempempitem		      = new cl_empempitem;
$oJson                      = new services_json();

$dados = $oJson->decode(str_replace("\\", "", $json));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

if (isset($dados->altera)) {
    db_inicio_transacao();
    $valor_total = 0;
    $sqlerro = false;

    $sqlItens = 'select * from matordem inner join matordemitem on m52_codordem = matordem.m51_codordem where m51_codordem = ' . $dados->m51_codordem . ' order by m52_sequen';
    $rsItens  = db_query($sqlItens);

    $itensAcordo = db_utils::getCollectionByRecord($rsItens);

    for ($count = 0; $count < count($itensAcordo); $count++) {

        $objeto = $itensAcordo[$count];

        if ($objeto->m52_sequen == $dados->itens[$count]->sequen) {
            if (floatval($dados->itens[$count]->valortotal) != $objeto->m52_valor) {
                $objeto->m52_valor = $dados->itens[$count]->valortotal;
            }
        }

        if (strpos($objeto->m52_valor, '.') && strpos($objeto->m52_valor, ',')) {
            $objeto->m52_valor = str_replace('.', '', $objeto->m52_valor);
        }

        if (strpos($objeto->m52_valor, ',')) {
            $objeto->m52_valor = str_replace(',', '.', $objeto->m52_valor);
        }

        $valor_total += $objeto->m52_valor;

        if(isset($dados->itens[$count]->coditem)){
            $sSqlTab    = "select sum(l223_total) as totaltabela from empordemtabela where l223_pcmaterordem = ".$dados->itens[$count]->coditem." and l223_codordem = ".$objeto->m51_codordem;
            
            $rsTabItem  = $clempempitem->sql_record($sSqlTab);
            $oTabItem = db_utils::fieldsMemory($rsTabItem, 0);
            $nTabela = round($oTabItem->totaltabela,2) ; 
            
            if ($clempempitem->numrows == 1 && $oTabItem->totaltabela > 0) {
            
            $nValor   = DBNumber::round($objeto->m52_valor,2); 
            
                if($nTabela != $nValor){
                    $sqlerro  = true;
                    $erro_msg = "Usu�rio: A soma dos itens da tabela est� divergente do valor total do item Tabela.";
                    break;
                }
            }
        }
    }

    /*
	 * Sequenciais que ir�o sofrer altera��o
	 *
	 * */
    $sItens = '';
    for ($i = 0; $i < count($dados->itens); $i++) {
        $sItens .= $dados->itens[$i]->sequen . ',';
    }

    $sItens = substr($sItens, 0, strlen($sItens) - 1);

    $result_ordem = $clmatordem->sql_record($clmatordem->sql_query_file("", "*", "", "m51_codordem = $dados->m51_codordem"));

    db_fieldsmemory($result_ordem, 0);
    
    $dataemp = $clmatordem->getDataEmp($m51_codordem);

    $dataempenho = DateTime::createFromFormat('d/m/Y', $dataemp);
    $dataordemcompra = DateTime::createFromFormat('d/m/Y', $dados->m51_data);

    if ($dataordemcompra < $dataempenho) {
        $sqlerro = true;
        $erro_msg = "Data da ordem n�o pode ser anterior a data de emiss�o do empenho.";
    } else if($sqlerro == false){
        
        $clmatordem->m51_codordem    = $m51_codordem;
        $clmatordem->m51_data        = $dados->m51_data;
        $clmatordem->m51_depto       = $dados->coddepto;
        $clmatordem->m51_deptoorigem = $dados->m51_deptoorigem;
        $clmatordem->m51_numcgm      = $m51_numcgm;
        $clmatordem->m51_obs         = utf8_decode(str_replace("n", "\n",$dados->obs));
        $clmatordem->m51_valortotal  = $valor_total;
        $clmatordem->m51_prazoentnovo    = $dados->m51_prazoentnovo;
        $clmatordem->m51_prazoent    = $dados->m51_prazoent;
        $clmatordem->alterar($m51_codordem);

        if ($clmatordem->erro_status == 0) {
            $sqlerro = true;
            $erro_msg = $clmatordem->erro_msg;
        } else {
            $erro_msg = 'Ordem de Compra alterada com sucesso!';
        }
    }

    for ($count = 0; $count < count($dados->itens); $count++) {

        $objeto         = $dados->itens[$count];
        $numemp         = $objeto->numemp;
        $quantidade     = $objeto->quantidade;
        $sequen         = $objeto->sequen;
        $valor_item     = $objeto->valortotal;
        $valor_unitario = $objeto->valorunitario;

        if (strpos(trim($valor_item), ',') != "") {
            $valor_item = str_replace('.', '', $valor_item);
            $valor_item = str_replace(',', '.', $valor_item);
        }

        if (strpos(trim($quantidade), ',') != "") {
            $quantidade = str_replace('.', '', $quantidade);
            $quantidade = str_replace(',', '.', $quantidade);
        }

        if (strpos(trim($valor_unitario), ',') != "") {
            $valor_unitario = str_replace('.', '', $valor_unitario);
            $valor_unitario = str_replace(',', '.', $valor_unitario);
        }

        $result_item = $clmatordemitem->sql_record($clmatordemitem->sql_query_file(
            null,
            "*",
            null,
            " m52_codordem = $dados->m51_codordem and m52_numemp = $numemp and m52_sequen = $sequen "
        ));

        if (pg_num_rows($result_item)) {

            if ($quantidade == 0 || $valor_item == 0) {
                $clmatordemitem->excluir($oItem->m52_codlanc);
                if ($clmatordemitem->erro_status == 0) {
                    $sqlerro = true;
                    $erro_msg = $clmatordemitem->erro_msg;
                }
            } else {
                db_fieldsmemory($result_item, 0);

                $clmatordemitem->m52_codlanc  = $m52_codlanc;
                $clmatordemitem->m52_codordem = $dados->m51_codordem;
                $clmatordemitem->m52_numemp   = $numemp;
                $clmatordemitem->m52_sequen   = $sequen;
                $clmatordemitem->m52_quant    = $quantidade;
                $clmatordemitem->m52_valor    = $valor_item;
                $clmatordemitem->m52_vlruni   = $valor_unitario;
                $clmatordemitem->alterar($m52_codlanc);

                if ($clmatordemitem->erro_status == 0) {
                    $sqlerro = true;
                    $erro_msg = $clmatordemitem->erro_msg;
                }
            }
        } else {
            $clmatordemitem->m52_codordem  = $dados->m51_codordem;
            $clmatordemitem->m52_numemp    = $numemp;
            $clmatordemitem->m52_sequen    = $sequen;
            $clmatordemitem->m52_quant     = $quantidade;
            $clmatordemitem->m52_valor     = $valor_item;
            $clmatordemitem->m52_vlruni    = $valor_unitario;

            $clmatordemitem->incluir(null);

            if ($clmatordemitem->erro_status == 0) {
                $sqlerro = true;
                $erro_msg = $clmatordemitem->erro_msg;
            }
        }
    }

    /*
	 * Exclus�o dos itens que n�o foram marcados na tela e automaticamente ser�o exclu�dos da Ordem de Compra...
	 *
	 * */

    if ($sItens) {
        $result_item = $clmatordemitem->sql_record($clmatordemitem->sql_query_file(
            null,
            "m52_codlanc",
            null,
            " m52_codordem = $dados->m51_codordem and m52_numemp = $numemp and m52_sequen not in ($sItens) "
        ));

        for ($contador = 0; $contador < pg_num_rows($result_item); $contador++) {
            $iCodLanc = db_utils::fieldsMemory($result_item, 0)->m52_codlanc;
            $clmatordemitem->excluir($iCodLanc);
        }
    }

    db_fim_transacao($sqlerro);

    if (isset($dados->altera)) {
        $oRetorno           = new stdClass();
        $oRetorno->message  = urlencode($erro_msg);
        $oRetorno->codordem = $dados->m51_codordem;
        $oRetorno->erro     = $sqlerro;
        echo $oJson->encode($oRetorno);
        die();
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
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
    <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
        <tr>
            <td width="360" height="18">&nbsp;</td>
            <td width="263">&nbsp;</td>
            <td width="25">&nbsp;</td>
            <td width="140">&nbsp;</td>
        </tr>
    </table>
    <table style="padding-top:15px;" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td height="430" valign="top" bgcolor="#CCCCCC">
                <center>
                    <? include("forms/db_frmmatordemaltera.php"); ?>
                </center>
            </td>
        </tr>
    </table>
    <?
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
    ?>
    <?
    //if (isset($dados->altera)){
    //    db_msgbox($erro_msg);
    //    echo "<script>";
    //    if($clmatordem->erro_campo!=""){
    //      echo "document.form1.".$clmatordem->erro_campo.".style.backgroundColor='#99A9AE';";
    //      echo "document.form1.".$clmatordem->erro_campo.".focus();";
    //    }else{
    //        echo "let confirmation = window.confirm('Deseja imprimir a Ordem de Compra?');";
    //        echo "if(confirmation){";
    //        echo "      jan = window.open('emp2_ordemcompra002.php?cods=$m51_codordem', '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1, location=0');";
    //        echo "      jan.moveTo(0,0);";
    //        echo "}";
    //        echo "top.corpo.location.href='emp1_ordemcompraaltera001.php';";
    //    }
    //	echo "</script>";
    //}
    ?>
</body>

</html>
