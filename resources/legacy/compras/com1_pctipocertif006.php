<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBselller Servicos de Informatica
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
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_pctipocertif_classe.php");
require_once("classes/db_pctipocertifdepartamento_classe.php");
require_once("classes/db_pcfornecertif_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clpctipocertif             = new cl_pctipocertif;
$clpcfornecertif            = new cl_pcfornecertif;
$clpctipocertifdepartamento = new cl_pctipocertifdepartamento;

$db_opcao = 33;
$db_botao = false;
$sqlerro  = false;

if (isset($oPost->excluir)) {

  $sWhere          = "pc74_pctipocertif={$oPost->pc70_codigo}";
  $sSqlForneCertif = $clpcfornecertif->sql_query_file(null, "*", "", $sWhere);
  $rsForneCertif   = $clpcfornecertif->sql_record($sSqlForneCertif);

  if ($clpcfornecertif->numrows == 0) {

    db_inicio_transacao();

    $clpctipocertifdepartamento->pc34_sequencial = $oPost->pc70_codigo;
    $clpctipocertifdepartamento->excluir($oPost->pc70_codigo);

    $erro_msg = $clpctipocertifdepartamento->erro_msg;
    if ($clpctipocertifdepartamento->erro_status == 0) {
      $sqlerro = true;
    }

    $clpctipocertif->excluir($oPost->pc70_codigo);

    $erro_msg = $clpctipocertif->erro_msg;
    if ($clpctipocertif->erro_status == 0) {
      $sqlerro = true;
    }

    db_fim_transacao($sqlerro);

    $db_opcao = 3;
    $db_botao = true;
  } else {

    $sqlerro  = true;
    $erro_msg = "Voc� n�o pode excluir um tipo de certificado que est� sendo utilizado.";
  }
} else if (isset($oGet->chavepesquisa)) {

  $db_opcao = 3;
  $db_botao = true;

  $result   = $clpctipocertif->sql_record($clpctipocertif->sql_query($oGet->chavepesquisa));
  db_fieldsmemory($result, 0);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, strings.js, prototype.js");
  db_app::load("estilos.css, grid.style.css");
?>
<style>
td {
  white-space: nowrap;
}

fieldset table td:first-child {
  width: 80px;
  white-space: nowrap;
}

#pc70_descr {
  width: 100%;
}

#pc70_subgrupo {
  width: 22%;
}

#pc70_subgrupo_select_descr {
  width: 22%;
}

textarea {
  width: 100%;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
    <center>
      <?
        include("forms/db_frmpctipocertif.php");
      ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?
if (isset($oPost->excluir)) {

  if ($sqlerro == true) {

    db_msgbox($erro_msg);
    if ($clpctipocertif->erro_campo != "") {

      echo "<script> document.form1.".$clpctipocertif->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpctipocertif->erro_campo.".focus();</script>";
    }
  } else {

    db_msgbox($erro_msg);
    echo "
      <script>
        function js_db_tranca(){
          parent.location.href='com1_pctipocertif003.php';
        }\n
        js_db_tranca();
      </script>\n
    ";
  }
}

if (isset($oGet->chavepesquisa)) {

  echo "
    <script>
      function js_db_libera(){
        parent.document.formaba.pctipocertifdepartamento.disabled=false;
        CurrentWindow.corpo.iframe_pctipocertifdepartamento.location.href='com1_pctipocertifdepartamento001.php?db_opcaoal=33&pc34_pctipocertif=".@$oGet->chavepesquisa."';
  ";

  if (isset($oGet->liberaaba)) {
    echo "  parent.mo_camada('pctipocertifdepartamento');";
  }

  echo"}\n
      js_db_libera();
    </script>\n
  ";
}

if ($db_opcao == 22 || $db_opcao == 33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>