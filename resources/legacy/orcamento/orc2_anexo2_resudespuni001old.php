<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function js_emite(){
  jan = window.open('orc2_anexo2_resudespuni002.php?&getunidade='+document.form1.getunidade.value+'&getorgao='+document.form1.getorgao.value+'&tipo_agrupa='+document.form1.tipo_agrupa.value+'&tipo_impressao='+document.form1.origem.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
    <form name="form1" method="post" action="" onsubmit="return js_verifica();">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td align="left" title="Origem dos dados a serem gerados no relat�rio."><strong>Origem dos dados :</strong></td>
        <td>
          <?
            $x = array("O"=>"Or�amento","B"=>"Balan�o");
            db_select('origem',$x,true,2,"");
          ?>
        </td>
      </tr>
      <tr>
        <td align="left" ><strong>Agrupar Por :</strong></td>
        <td>
          <?
            $x = array("1"=>"Geral","2"=>"�rg�o","3"=>"Unidade");
            db_select('tipo_agrupa',$x,true,2,"");
          ?>
        </td>
      </tr>
      <tr>
        <td align="left" ><strong>Org�o :</strong></td>
        <td>
           <?db_input('getorgao',2,'',true,'text',2)?>
        </td>
      </tr>
      <tr>
        <td align="left" ><strong>Unidade :</strong></td>
        <td>
           <?db_input('getunidade',2,'',true,'text',2)?>
        </td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
