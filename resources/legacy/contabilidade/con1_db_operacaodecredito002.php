<? 
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_db_operacaodecredito_classe.php");
include("classes/db_operacaodecreditopcasp_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cldb_operacaodecredito      = new cl_db_operacaodecredito;
$cldb_operacaodecreditopcasp = new cl_db_operacaodecreditopcasp;

$db_opcao = 22;
$db_botao = false;

if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $cldb_operacaodecredito->alterar($op01_sequencial);
  db_fim_transacao();
}else if(isset($chavepesquisa)){

  $db_opcao = 2;
  $result  = $cldb_operacaodecredito->sql_record($cldb_operacaodecredito->sql_query($chavepesquisa));

  $campos_pcasp = "
                  dv01_principaldividalongoprazop, 
                  dv01_principaldividacurtoprazop,
                  dv01_principaldividaf,
                  dv01_jurosdividalongoprazop,
                  dv01_jurosdividaf,
                  dv01_jurosdividacurtoprazop,
                  dv01_controlelrf,
                  cp.c60_descr  as dv01_texto_principaldividalongoprazop, 
                  cp2.c60_descr as dv01_texto_principaldividacurtoprazop,
                  cp3.c60_descr as dv01_texto_principaldividaf,
                  cp4.c60_descr as dv01_texto_jurosdividalongoprazop,
                  cp5.c60_descr as dv01_texto_jurosdividaf, 
                  cp6.c60_descr as dv01_texto_jurosdividacurtoprazop, 
                  cp7.c60_descr as dv01_texto_controlelrf
                ";

  $result_pcasp = $cldb_operacaodecreditopcasp->sql_record($cldb_operacaodecreditopcasp->sql_query($chavepesquisa, $campos_pcasp));

  db_fieldsmemory($result,0);
  db_fieldsmemory($result_pcasp,0);

   if(empty($op01_datadecadastro) && !empty($op01_dataassinaturacop)) {
    $GLOBALS['op01_datadecadastro_dia'] = $GLOBALS['op01_dataassinaturacop_dia'];
    $GLOBALS['op01_datadecadastro_mes'] = $GLOBALS['op01_dataassinaturacop_mes'];
    $GLOBALS['op01_datadecadastro_ano'] = $GLOBALS['op01_dataassinaturacop_ano'];
    $GLOBALS['op01_datadecadastro']     = $GLOBALS['op01_dataassinaturacop'];
   }

    $db_botao = true;
}

if(isset($salvar_pcasp)) {

  $db_opcao = 2;
  $exists = $cldb_operacaodecreditopcasp->codeAlreadyExists($chavepesquisa);

  if(!empty($exists)) {
    $cldb_operacaodecreditopcasp->alterar($chavepesquisa);
  } else {
    $cldb_operacaodecreditopcasp->incluir($chavepesquisa);
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

<table class="tabs bordas" border="0" cellspacing="0">
  <tr style="height: 26px;"> 
    <td id="tab_divida_consolidada" type="button" class="active" onclick="actionAbas('divida_consolidada')">&nbsp Dívida Consolidada &nbsp</td>
    <td id="tab_pcasp" type="button" type="button" class="" onclick="actionAbas('pcasp')">&nbsp Pcasp &nbsp</td>
  </tr>
</table>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table style="padding-top:15px;" align="center">
  <tr> 
    <td> 
      <center>
	<?
	include("forms/db_frmdb_operacaodecredito.php");
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
  if($cldb_operacaodecredito->erro_status=="0"){
    $cldb_operacaodecredito->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cldb_operacaodecredito->erro_campo!=""){
      echo "<script> document.form1.".$cldb_operacaodecredito->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_operacaodecredito->erro_campo.".focus();</script>";
    }
  }else{
    $cldb_operacaodecredito->erro(true,false);
  }
}

if(isset($salvar_pcasp)) {

  if($cldb_operacaodecreditopcasp->erro_status=="0"){
    $cldb_operacaodecreditopcasp->erro(true,false);
    $db_botao=true;
    echo "<script> document.frm_pcasp.db_opcao.disabled=false;</script>  ";
    if($cldb_operacaodecreditopcasp->erro_campo!=""){
      echo "<script> document.frm_pcasp.".$cldb_operacaodecreditopcasp->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.frm_pcasp.".$cldb_operacaodecreditopcasp->erro_campo.".focus();</script>";
    }
  } else {
    $cldb_operacaodecreditopcasp->erro(true,true);
  }
}

if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>

js_tabulacaoforms("form1","op01_numerocontratoopc",true,1,"op01_numerocontratoopc",true);
js_tabulacaoforms("frm_pcasp","dv01_codoperacaocredito",true,1,"dv01_codoperacaocredito",true);
</script>
