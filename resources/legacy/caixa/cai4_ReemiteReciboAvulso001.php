<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_recibo_classe.php");

$clrotulo = new rotulocampo;
$clrotulo->label("k00_numpre");

$clRecibo = new cl_recibo();

$oPost = db_utils::postMemory($_POST);

if (isset($oPost->k00_numpre)) {
	$sSqlDadosRecibo = $clRecibo->sql_query_file(null,"distinct k00_numpre, k00_numcgm, k00_tipo",null,"k00_numpre = {$oPost->k00_numpre}");
	$rsDadosRecibo   = $clRecibo->sql_record($sSqlDadosRecibo);
	if ($clRecibo->numrows == 0) {
		db_msgbox("Nenhum recibo avulso foi encontrado com o numpre {$oPost->k00_numpre}");
		db_redireciona();
	}

	$oDadosRecibo = db_utils::fieldsMemory($rsDadosRecibo, 0);

	$sUrl  = "cai4_recibo003.php?iNumpre={$oDadosRecibo->k00_numpre}&tipo={$oDadosRecibo->k00_tipo}&ver_inscr=";
	$sUrl .= "&numcgm={$oDadosRecibo->k00_numcgm}&emrec=t&CHECK10=&tipo_debito={$oDadosRecibo->k00_tipo}&lReemissao=true";
	$sUrl .= "&k03_tipo={$oDadosRecibo->k00_tipo}&k03_parcelamento=f&k03_perparc=f&ver_numcgm={$oDadosRecibo->k00_numcgm}";

	echo "<script> ";
	echo "   window.open('{$sUrl}','','location=0'); ";
	echo "</script>";

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
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <table  border="0" cellpadding="0" cellspacing="0">
      <tr> 
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
     </tr>
  </table>
  <center>
    <form name='form1' method="POST">
    <table width="250">
      <tr>
        <td align="center">
          <fieldset>
            <legend><b>Reemisao de recibo</legend>
            <table>
              <tr>
                <td nowrap title="<?=@$k00_numpre?>">
                 <?=$Lk00_numpre?>
                </td>
                <td nowrap> 
                 <? db_input('k00_numpre',10,$Ik00_numpre,true,'text',1)  ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td align="center">
          <input type='button' value='Reemitir Recibo' id='reemitir' name='reemitir' onclick='js_Reemitir()'>
        </td>
      </tr>
    </table>
    </form>
  </center>
  </body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>

function js_Reemitir() {
	if ($F(k00_numpre) == "") {
		alert("Informe o Numpre do Recibo Avulso que deseja reemitir");
		return false;
	}	

	if (confirm('Reemitir Recibo?')) {
    document.form1.submit();
		return true;
	}
}
</script>