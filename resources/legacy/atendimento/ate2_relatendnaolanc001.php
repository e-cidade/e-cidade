<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
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
  obj = document.form1;
  data_ini =  obj.data1_ano.value+'-'+obj.data1_mes.value+'-'+obj.data1_dia.value;
  data_fin =  obj.data2_ano.value+'-'+obj.data2_mes.value+'-'+obj.data2_dia.value;
 if (data_fin < data_ini){
     alert('Datas invalidas');
     return;
  } else {
    jan = window.open('ate2_relatendnaolanc002.php?&data='+data_ini+'&data1='+data_fin,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
}  
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td align='center'  colspan=2 >
         <b> Per�odo : </b>
         <? 
         db_inputdata('data1','','','',true,'text',1,"");   		          
         echo "<b> a </b> ";
         db_inputdata('data2','','','',true,'text',1,"");
         ?>
        </td>
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