<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

//
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
  var descricao = '';
  data1 =  obj.data1_ano.value+'-'+obj.data1_mes.value+'-'+obj.data1_dia.value;
  data2 =  obj.data2_ano.value+'-'+obj.data2_mes.value+'-'+obj.data2_dia.value;
  data3 =  obj.data3_ano.value+'-'+obj.data3_mes.value+'-'+obj.data3_dia.value;
  data4 =  obj.data4_ano.value+'-'+obj.data4_mes.value+'-'+obj.data4_dia.value;
  total =  obj.totalizar.value;
  ordem =  obj.ordem.value;   
  
  for (i=0;i<obj.ordem.options.length;i++) {
    if(obj.ordem.options[i].value == ordem){
      descricao = obj.ordem.options[i].text;
      break;
    }  
  }
  ordem2 =  obj.ordem2.value;
 if ((data2 < data1)||(data4 < data3)){
     alert('Datas invalidas');
     return;
  } else {
    jan = window.open('ate2_relclienteproced002.php?&data1='+data1+'&data2='+data2+'&data3='+data3+'&data4='+data4+'&totalizar='+total+'&ordem='+ordem+'&ordem2='+ordem2+'&descricao='+descricao,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');

   
   
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

  <table  align="center" border="0">
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td align='left'  >
         <b> Atual: </b>
         </td>
         <td>
         <? 
         db_inputdata('data1','','','',true,'text',1,"");   		          
         echo "<b> a </b> ";
         db_inputdata('data2','','','',true,'text',1,"");
         ?>
        </td>
		<td></td>		
      </tr>
      <tr>
        <td align='left'  >
         <b> Antes: </b>
         </td>
         <td>
         <? 
         db_inputdata('data3','','','',true,'text',1,"");   		          
         echo "<b> a </b> ";
         db_inputdata('data4','','','',true,'text',1,"");
         ?>
        </td>
		<td></td>		
      </tr>
      <tr>
        <td align='left'  >
         <b> Totalizar por: </b>
         </td>
         <td>
         <? 
         $x = array ("cp" => "cliente e procedimento", "c" => "clientes", "p" => "procedimento", "m" => "m�dulo");
	     db_select('totalizar', $x, true, 4, "");
	     ?>
        </td>
		<td></td>		
      </tr>
       
      <tr>
        <td align='left'  >
         <b> Ordenar por: </b>
         </td>
         <td>
         <? 
         
          
      
         $x = array ( "duvidas_atu"          => "D�vidas atual",
			          "duvidas_fin_atu"      => "D�vidas atual finalizado",
			          "duvidas_antes"        => "D�vidas antes",
			          "duvidas_fin_antes"    => "D�vidas antes finalizado",
			          "erros_atu"            => "Erros atual",
			          "erros_fin_atu"        => "Erros atual finalizado",
			          "erros_antes"          => "Erros antes",
			          "erros_fin_antes"      => "Erros antes finalizado",
			          "melhorias_atu"        => "Melhorias atual",
			          "melhorias_fin_atu"    => "Melhorias atual finalizado",
			          "melhorias_antes"      => "Melhorias antes",
			          "melhorias_fin_antes"  => "Melhorias antes finalizado",
			          "base_atu"             => "Monitoria atual",
			          "base_fin_atu"         => "Monitoria atual finalizado",
			          "base_antes"           => "Monitoria antes ",
			          "base_fin_antes"       => "Monitoria antes finalizado", 
                      "total_aten_atu"       => "Total atual", 
                      "total_aten_fin_atu"   => "Total atual finalizados", 
                      "total_aten_antes"     => "Total anterior ", 
                      "total_aten_fin_antes" => "Total anterior finalizados");
	     db_select('ordem', $x, true, 4, "");
	    
         $x = array ("a" => "ascendente", "d" => "descendente");
	     db_select('ordem2', $x, true, 4, "");
	     
	     ?>
        </td>
		<td></td>		
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
