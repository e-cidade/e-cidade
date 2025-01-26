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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_acordo_classe.php");
require_once("classes/db_acordoacordogarantia_classe.php");
require_once("classes/db_acordoacordopenalidade_classe.php");
require_once("classes/db_acordoitem_classe.php");
require_once("classes/db_acordoaux_classe.php");
require_once("classes/db_parametroscontratos_classe.php");

$oParam = new cl_parametroscontratos;

$oParam = $oParam->sql_query(null, '*');
$oParam = db_query($oParam);
$oParam = db_utils::fieldsMemory($oParam);
$oParam = $oParam->pc01_liberarcadastrosemvigencia;
if ($oParam == 't') {
    $clacordo = new cl_acordoaux;
} else {
    $clacordo = new cl_acordo;
}
if (isset($chavepesquisa)) {
    $oAcordoVigencia = $clacordo->sql_query($chavepesquisa, 'ac16_semvigencia as vigencia');
    $oAcordoVigencia = $clacordo->sql_record($oAcordoVigencia);
    $oAcordoVigencia = db_utils::fieldsMemory($oAcordoVigencia);
    $vigente = $oAcordoVigencia->vigencia;
}
if ($oParam != 't' || $vigente != 't') {
    $clacordo = new cl_acordo;
}


/*
$clacordoacordogarantia = new cl_acordoacordogarantia;
$clacordoacordopenalidade = new cl_acordoacordopenalidade;
$clacordoitem = new cl_acordoitem;
  */
db_postmemory($HTTP_POST_VARS);
$db_opcao = 22;
$db_botao = false;
if (isset($alterar)) {
    $sqlerro = false;
    db_inicio_transacao();
    $clacordo->alterar($ac16_sequencial);
    if ($clacordo->erro_status == 0) {
        $sqlerro = true;
    }
    $erro_msg = $clacordo->erro_msg;
    db_fim_transacao($sqlerro);
    $db_opcao = 2;
    $db_botao = true;
} else if (isset($chavepesquisa)) {
    $db_opcao = 2;
    $db_botao = true;
    //$result = $clacordo->sql_record($clacordo->sql_query($chavepesquisa));
    db_fieldsmemory($result, 0);
}
unset($_SESSION["oContrato"]);
?>
<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js");
    db_app::load("dbmessageBoard.widget.js, prototype.js");
    if ($oParam == 't' && $vigente == 't') {
        db_app::load("contratosaux.classe.js");
    } else {
        db_app::load("contratos.classe.js");
    }
    db_app::load("estilos.css, grid.style.css");
    ?>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
    <?

    if ($oParam == 't' && $vigente == 't') {
        include("forms/db_frmacordoalteraaux.php");
    } else {
        include("forms/db_frmacordo.php");
    }
    ?>
</body>

</html>
<?
if (isset($alterar)) {
    if ($sqlerro == true) {
        db_msgbox($erro_msg);
        if ($clacordo->erro_campo != "") {
            echo "<script> document.form1." . $clacordo->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1." . $clacordo->erro_campo . ".focus();</script>";
        };
    } else {
        db_msgbox($erro_msg);
    }
}
if (isset($chavepesquisa)) {
    echo "
 <script>
  function js_db_libera() {

   parent.document.formaba.acordo.disabled=false;
   parent.document.formaba.acordogarantia.disabled=false;
   (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acordogarantia.location.href='aco1_acordoacordogarantia001.php?ac12_acordo=" . @$chavepesquisa . "';
   parent.document.formaba.acordopenalidade.disabled=false;
   (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acordopenalidade.location.href='aco1_acordoacordopenalidade001.php?ac15_acordo=" . @$chavepesquisa . "';
   parent.document.formaba.acordoitem.disabled=false;
   (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acordoitem.location.href='aco1_acordoitem001.php?ac20_acordo=" . @$chavepesquisa . "';
   parent.document.formaba.acordodocumento.disabled=false;
   (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acordodocumento.location.href='aco1_acordodocumento001.php?ac40_acordo=" . @$chavepesquisa . "';
   ";
    if (isset($liberaaba)) {
    }
    echo "}\n
   js_db_libera();
 </script>\n
 ";
}
if ($db_opcao == 22 || $db_opcao == 33) {
    echo "<script>document.form1.pesquisar.click();</script>";
}

//echo "<script>alert($('ac16_origem').disabled);</script>";

?>
