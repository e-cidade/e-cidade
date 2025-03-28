<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2012  DBselller Servicos de Informatica
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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$oDaoAtoLegal         = db_utils::getdao("atolegal");
$oDaoAnexoAtoLegal    = db_utils::getdao("edu_anexoatolegal");
$oDaoAtoEscola        = db_utils::getdao("atoescola");
$oDaoAtoJustificativa = db_utils::getdao("atojustificativa");
$db_botao             = false;
$db_opcao             = 33;

if (isset($excluir)) {

  $sSqlJustificativa  = $oDaoAtoJustificativa->sql_query("", "*", "", " ed07_i_ato = $ed05_i_codigo");
  $rsAtoJustificativa = $oDaoAtoJustificativa->sql_record($sSqlJustificativa);

  if ($oDaoAtoJustificativa->numrows > 0) {
    ?><script>alert("Ato Legal n�o pode ser exclu�do,  pois est� relacionado com uma Justificativa");</script><?
  } else {

    db_inicio_transacao();
    $db_opcao = 3;
    $ed19_i_escola = db_getsession("DB_coddepto");
    $oDaoAtoEscola->excluir("", "ed19_i_ato = $ed05_i_codigo and ed19_i_escola = $ed19_i_escola");
    $oDaoAnexoAtoLegal->excluir("", "ed292_atolegal = ".$ed05_i_codigo);
    $oDaoAtoJustificativa->excluir("", "ed07_i_ato = $ed05_i_codigo");
    $oDaoAtoLegal->excluir($ed05_i_codigo);
    db_fim_transacao();

  }

} elseif (isset($chavepesquisa)) {

 $db_opcao     = 3;
 $sSqlAtoLegal = $oDaoAtoLegal->sql_query($chavepesquisa);
 $rsAtoLegal   = $oDaoAtoLegal->sql_record($sSqlAtoLegal);
 db_fieldsmemory($rsAtoLegal, 0);
 $db_botao = true;

?>

  <script language="JavaScript">
    parent.document.formaba.a2.disabled = false;
    CurrentWindow.corpo.iframe_a2.location.href   = 'edu1_edu_anexoatolegal003.php?chavepesquisa=<?=$ed05_i_codigo?>';
  </script>

<?
}
?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
 </head>
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
     <?MsgAviso(db_getsession("DB_coddepto"), "escola");?>
     <br>
     <center>
      <fieldset style="width:95%"><legend><b>Exclus�o de Ato Legal</b></legend>
       <?include("forms/db_frmatolegal.php");?>
      </fieldset>
     </center>
    </td>
   </tr>
  </table>
 </body>
</html>
<?
if (isset($excluir)) {

  if ($oDaoAtoLegal->erro_status == "0") {
    $oDaoAtoLegal->erro(true, false);
  } else {
    $oDaoAtoLegal->erro(true, true);
  }

}

if ($db_opcao == 33) {
  echo "<script>$('pesquisar').click();</script>";
}
?>
