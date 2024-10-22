<?

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_planosaude_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$instit   = db_getsession('DB_instit');
$clplanosaude = new cl_planosaude;
$db_opcao = 22;
$db_botao = false;

if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $db_opcaoal = 2;
  
  $clplanosaude->r75_anousu     = $r75_anousu;
  $clplanosaude->r75_mesusu     = $r75_mesusu;
  $clplanosaude->r75_regist     = $r75_regist;
  $clplanosaude->r75_numcgm     = $r75_numcgm;
  $clplanosaude->r75_cnpj       = $r75_cnpj;
  $clplanosaude->r75_ans        = str_pad($r75_ans,6,'0',STR_PAD_LEFT);
  $clplanosaude->r75_nomedependente = $r75_nomedependente;
  $clplanosaude->r75_valor      = $r75_valor;
  $clplanosaude->r75_instit     = $instit;
  $clplanosaude->alterar($r75_sequencial);

  if ($clplanosaude->erro_status == "0") {
      $lErro = true;
  }    

  db_fim_transacao();
}else if(isset($chavepesquisa) || isset($opcao)){
   $db_opcao = 2;
   $db_campos = "planosaude.*,z01_nome";
   $db_where = "r75_regist = $chavepesquisa2 and r75_anousu = $chavepesquisa and r75_mesusu = $chavepesquisa1";

   if(!empty($chavepesquisa3)){
    $db_where .= " and r75_numcgm = $chavepesquisa3";
   }
   
   $result = $clplanosaude->sql_record($clplanosaude->sql_query_dados(null,$db_campos,'r75_sequencial',$db_where));
   db_fieldsmemory($result,0);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="25%" height="18">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmplanosaude.php");
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
if(isset($alterar)){
  if($clplanosaude->erro_status=="0"){
    $clplanosaude->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clplanosaude->erro_campo!=""){
      echo "<script> document.form1.".$clplanosaude->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clplanosaude->erro_campo.".focus();</script>";
    };
  }else{
    $clplanosaude->erro(true,true);
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>