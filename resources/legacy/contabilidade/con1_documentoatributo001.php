<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_documentoatributo_classe.php");
include("classes/db_documento_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cldocumentoatributo = new cl_documentoatributo;
$cldocumento = new cl_documento;
$db_opcao = 22;
$db_botao = false;

if(isset($db45_codcam)) {
	if(!$db45_codcam) {
		$cldocumentoatributo->db45_codcam = 'NULL';
	}
}

if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $cldocumentoatributo->incluir($db45_sequencial);
    $erro_msg = $cldocumentoatributo->erro_msg;
    if($cldocumentoatributo->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    $cldocumentoatributo->alterar($db45_sequencial);
    $erro_msg = $cldocumentoatributo->erro_msg;
    if($cldocumentoatributo->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $cldocumentoatributo->excluir($db45_sequencial);
    $erro_msg = $cldocumentoatributo->erro_msg;
    if($cldocumentoatributo->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $cldocumentoatributo->sql_record($cldocumentoatributo->sql_query($db45_sequencial));
   if($result!=false && $cldocumentoatributo->numrows>0){
     db_fieldsmemory($result,0);
   }
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmdocumentoatributo.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($cldocumentoatributo->erro_campo!=""){
        echo "<script> document.form1.".$cldocumentoatributo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$cldocumentoatributo->erro_campo.".focus();</script>";
    }
}
?>