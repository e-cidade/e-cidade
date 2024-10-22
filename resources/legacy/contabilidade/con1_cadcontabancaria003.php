<?

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_contabancaria_classe.php");
require_once("classes/db_db_operacaodecredito_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clcontabancaria     = new cl_contabancaria;
$cloperacaodecredito = new cl_db_operacaodecredito;
$clsaltes            = new cl_saltes;
$mostrarCampo        = "none";
$mostrarCampo2       = "none";
$db_opcao = 33;
$cloperacaodecredito->rotulo->label();

if (isset($chavepesquisa)) {
  $db_opcao = 3;
  $result   = $clcontabancaria->sql_record($clcontabancaria->sql_query_cadcontanovo($chavepesquisa));
  db_fieldsmemory($result, 0);

  $iCodigoRecurso    = $c61_codigo;
  $sDescricaoRecurso = $o15_descr;
  $db83_codigotce    = $c61_codtce;
  $db83_reduzido     = $k13_reduz;
  $dtImplantacao     = explode("-", $k13_dtimplantacao, 3);
  $db83_dataimplantaoconta_dia = $dtImplantacao[2];
  $db83_dataimplantaoconta_mes = $dtImplantacao[1];
  $db83_dataimplantaoconta_ano = $dtImplantacao[0];
  $db_botao = true;
}
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
<?
if (isset($excluir)) {

  if ($clcontabancaria->erro_status == "0") {
    $clcontabancaria->erro(true, false);
  } else {
    $clcontabancaria->erro(true, true);
  }
} 
if ($db_opcao == 33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
  js_tabulacaoforms("form1", "excluir", true, 1, "excluir", true);
</script>