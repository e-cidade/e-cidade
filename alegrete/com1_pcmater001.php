<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_pcmater_classe.php");
include("classes/db_pcmaterele_classe.php");
include("classes/db_pcgrupo_classe.php");
include("classes/db_pcsubgrupo_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);

$clpcmater = new cl_pcmater;
$clpcmaterele = new cl_pcmaterele;
$clpcgrupo = new cl_pcgrupo;
$clpcsubgrupo = new cl_pcsubgrupo;
$db_opcao = 1;
$db_botao = true;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  db_inicio_transacao();
  $sqlerro=false;
  $clpcmater->incluir(null);
  if($clpcmater->erro_status==0){
    $sqlerro = true;
  }else{
    $codmater =  $clpcmater->pc01_codmater;
  }
  if($sqlerro==false){
    $arr =  split("XX",$codeles);
    for($i=0; $i<count($arr); $i++ ){
       $elemento = $arr[$i]; 
       if(trim($elemento)!=""){
	 $clpcmaterele->pc07_codmater = $codmater;
	 $clpcmaterele->pc07_codele = $elemento;
	 $clpcmaterele->incluir($codmater,$elemento); 
	 if($clpcmaterele->erro_status==0){
	   db_msgbox('erro');
	   $sqlerro = true;
	 }
       }
      
    }	 
  }
  db_fim_transacao($sqlerro);


  //
  $conectar = pg_connect("host=192.168.1.1 dbname=sam30 user=postgres");
  //
  $sql = "insert into  mater (m01_codigo,
                           m01_digito,
                           m01_descr1, 
                           m01_descr2,
			   m01_descr3,
			   m01_descr4,
			   m01_descr5,
			   m01_descr6,
			   m01_descr7) values (
                           lpad($codmater,7,0),
			   '0',
                           '".substr($clpcmater->pc01_descrmater,0,50)."', 
                           '".substr($clpcmater->pc01_descrmater,50,30).substr($clpcmater->pc01_complmater,0,20)."',
			   '".substr($clpcmater->pc01_descrmater,20,50)."',
			   '".substr($clpcmater->pc01_descrmater,70,50)."',
			   '".substr($clpcmater->pc01_descrmater,120,50)."',
			   '".substr($clpcmater->pc01_descrmater,170,50)."',
			   '".substr($clpcmater->pc01_descrmater,230,50)."')";
  $result = pg_query($sql);
  //
  include("libs/db_conecta.php");
  //


  
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_iniciar() {
  if(document.form1)
    document.form1.pc01_descrmater.focus();
}
</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_iniciar()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmpcmater.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  if($clpcmater->erro_status=="0"){
    $clpcmater->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clpcmater->erro_campo!=""){
      echo "<script> document.form1.".$clpcmater->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpcmater->erro_campo.".focus();</script>";
    };
  }else{
    $clpcmater->erro(true,true);
  };
};
?>
