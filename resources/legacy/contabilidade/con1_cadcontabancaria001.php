<?
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_contabancaria_classe.php");
require_once("classes/db_db_operacaodecredito_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_conparametro_classe.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_libdicionario.php");
require_once("libs/db_libcontabilidade.php");
require_once("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);

$clsaltes            = new cl_saltes;
$clcontabancaria     = new cl_contabancaria;
$cloperacaodecredito = new cl_db_operacaodecredito;
$db_opcao            = 1;
$db_botao            = true;
$mostrarCampo        = "none";
$mostrarCampo2       = "none";
?>
<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <table align="center" style="margin-top:20px;">
    <tr>
      <td>
        <center>
          <?
          include("forms/db_frmcontabancarianovo.php");
          ?>
        </center>
      </td>
    </tr>
  </table>

  <?php
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
  ?>

</body>

</html>
<script>
  js_tabulacaoforms("form1", "db83_descricao", true, 1, "db83_descricao", true);
</script>
<?
if (isset($incluir)) {

  if ($clcontabancaria->erro_status == "0") {

    $clcontabancaria->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clcontabancaria->erro_campo != "") {

      echo "<script> document.form1." . $clcontabancaria->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1." . $clcontabancaria->erro_campo . ".focus();</script>";
    }
  } else {
    $clcontabancaria->erro(true, true);
  }
}
?>