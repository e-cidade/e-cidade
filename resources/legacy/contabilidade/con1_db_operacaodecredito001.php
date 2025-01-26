
<? 
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_db_operacaodecredito_classe.php");
include("classes/db_operacaodecreditopcasp_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
$cldb_operacaodecredito = new cl_db_operacaodecredito;
$cldb_operacaodecreditopcasp = new cl_db_operacaodecreditopcasp;

$db_opcao = 1;
$db_botao = true;

if(isset($incluir)){
  db_inicio_transacao();
  $cldb_operacaodecredito->incluir($op01_sequencial);
  db_fim_transacao();
}

if(isset($salvar_pcasp)) {

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

<?php 
  $returnLastSequencial = '';
?>

<table class="tabs bordas" border="0" cellspacing="0">
  <tr style="height: 26px;"> 
    <td id="tab_divida_consolidada" type="button" class="active" onclick="actionAbas('divida_consolidada');">&nbsp D�vida Consolidada &nbsp</td>
    <td id="tab_pcasp" type="button" type="button" class="" onclick="actionAbas('pcasp');">&nbsp Pcasp &nbsp</td>
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
<script>

js_tabulacaoforms("form1","op01_numerocontratoopc",true,1,"op01_numerocontratoopc",true);

</script>
<?

if(isset($incluir)){

  if($cldb_operacaodecredito->erro_status=="0"){

    $cldb_operacaodecredito->erro(true,false);

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cldb_operacaodecredito->erro_campo!=""){
      echo "<script> document.form1.".$cldb_operacaodecredito->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_operacaodecredito->erro_campo.".focus();</script>";
    }

    echo "<script>
    
          var pcaspElement = document.getElementById('tab_pcasp');
          if (pcaspElement) {
            pcaspElement.setAttribute('onclick', 'actionAbas(\'pcasp\');preencheSequencialIncluir()' );
          }

          var dividaElement = document.getElementById('tab_divida_consolidada');
          if (dividaElement) {
            dividaElement.setAttribute('onclick', 'actionAbas(\'divida_consolidada\');preencheSequencialIncluir()' );
          }

          var element = document.getElementById('tab_divida_consolidada');
          if (element) {
            element.click();
          }

        </script>";

  } else {

    echo "<script>  
    
            var dividaElement = document.getElementById('tab_divida_consolidada');
            if (dividaElement) {
              dividaElement.setAttribute('onclick', 'actionAbas(\'divida_consolidada\');preencheSequencialIncluir()' );
            }

            var pcaspElement = document.getElementById('tab_pcasp');
            if (pcaspElement) {
              pcaspElement.setAttribute('onclick', 'actionAbas(\'pcasp\');preencheSequencialIncluir()' );
            }

            var element = document.getElementById('tab_pcasp');
            if (element) {
              element.click();
            }
  
          </script>";

    $cldb_operacaodecredito->erro(true,false);
  }
}

if(isset($salvar_pcasp)) {

  if($cldb_operacaodecreditopcasp->erro_status=="0"){
    $cldb_operacaodecreditopcasp->erro(true,false);
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cldb_operacaodecreditopcasp->erro_campo!=""){
      echo "<script> document.form1.".$cldb_operacaodecreditopcasp->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_operacaodecreditopcasp->erro_campo.".focus();</script>";
    }
  } else {
    $cldb_operacaodecreditopcasp->erro(true,true);
  }
}
?>
