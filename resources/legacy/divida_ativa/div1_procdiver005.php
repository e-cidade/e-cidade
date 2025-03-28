<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
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

require_once("classes/db_procdiver_classe.php");
require_once("classes/db_recparprocdiver_classe.php");

$clprocdiver    = new cl_procdiver;

$oGet           = db_utils::postMemory($HTTP_GET_VARS);
$oPost          = db_utils::postMemory($HTTP_POST_VARS);

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$db_opcao       = 22;
$db_botao       = false;

if ( isset($oPost->alterar) ) {

  $lErroSql = false;

  db_inicio_transacao();
  $clprocdiver->alterar($oPost->dv09_procdiver);

  if ( $clprocdiver->erro_status == 0 ) {
    $lErroSql = true;
  }

  $sErroMsg = $clprocdiver->erro_msg;
  $db_opcao = 2;
  $db_botao = true;

  db_fim_transacao($lErroSql);

} elseif ( isset($oGet->chavepesquisa) ) {

  $db_opcao        = 2;
  $db_botao        = true;

  $sSqlProcedencia = $clprocdiver->sql_query($oGet->chavepesquisa,"*",null,"dv09_procdiver = {$oGet->chavepesquisa} and dv09_instit = ".db_getsession('DB_instit')." ");
  $rsProcedencia   = $clprocdiver->sql_record($sSqlProcedencia);

  db_fieldsmemory($rsProcedencia,0);

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
<body bgcolor=#CCCCCC>

	<?
	include("forms/db_frmprocdiver.php");
	?>

</body>
</html>
<?
if ( isset($alterar) ) {

  if ( $lErroSql == true ) {

    db_msgbox($sErroMsg);

    if ( $clprocdiver->erro_campo != "" ) {

      echo "<script> document.form1.".$clprocdiver->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprocdiver->erro_campo.".focus();</script>";

    }

  } else {
    db_msgbox($sErroMsg);
  }

}

if ( isset($chavepesquisa) ) {

  echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.recparprocdiver.disabled=false;
         CurrentWindow.corpo.iframe_recparprocdiver.location.href='div1_recparprocdiver001.php?procdiver=".@$dv09_procdiver."';
     ";

  if( isset($liberaaba) ) {
    echo "  parent.mo_camada('recparprocdiver');";
  }

  echo"}\n
    js_db_libera();
  </script>\n
 ";
}

if ( $db_opcao==22 || $db_opcao == 33 ) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
