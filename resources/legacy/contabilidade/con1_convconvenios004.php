<?php
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_convconvenios_classe.php");
include("classes/db_convdetalhaconcedentes_classe.php");
$clconvconvenios = new cl_convconvenios;
$clconvdetalhaconcedentes = new cl_convdetalhaconcedentes;

db_postmemory($HTTP_POST_VARS);
   $db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $clconvconvenios->incluir($c206_sequencial);

  if($clconvconvenios->erro_status==0){
    $sqlerro=true;
  }

  $erro_msg = $clconvconvenios->erro_msg;

  $c206_sequencial= $clconvconvenios->c206_sequencial;
  $db_opcao = 1;
  $db_botao = true;
  db_fim_transacao($sqlerro);

  if(!empty($c206_sequencial)) {

    $pacote_detalhamentos = '[' . str_replace('\"', '"', $pacote_detalhamentos) . ']';
    $pacote_detalhamentos = json_decode(mb_convert_encoding($pacote_detalhamentos,'UTF-8', 'ISO-8859-1'), true);
    $pacote_detalhamentos = mb_convert_encoding($pacote_detalhamentos,'ISO-8859-1', 'UTF-8');

    foreach ($pacote_detalhamentos as $key => $item_detalhe) {

      switch ($item_detalhe["c207_esferaconcedente"]) {
        case 'Federal': $item_detalhe["c207_esferaconcedente"] = 1;
          break;
        case 'Estadual': $item_detalhe["c207_esferaconcedente"] = 2;
          break;
        case 'Municipal': $item_detalhe["c207_esferaconcedente"] = 3;
          break;
        case 'Exterior': $item_detalhe["c207_esferaconcedente"] = 4;
          break;
        case 'Instituição Privada': $item_detalhe["c207_esferaconcedente"] = 5;
          break;
        default:  $item_detalhe["c207_esferaconcedente"] = 1;
          break;
      }

      $item_detalhe['c207_codconvenio']    = $c206_sequencial;
      $item_detalhe['c207_valorconcedido'] = str_replace(',', '.', str_replace('.', '', $item_detalhe['c207_valorconcedido']));
      
      $clconvdetalhaconcedentes->incluir(null, $item_detalhe);
    }
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
    	<?php
    	 include("forms/db_frmconvconvenios.php");
    	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clconvconvenios->erro_campo!=""){
      echo "<script> document.form1.".$clconvconvenios->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clconvconvenios->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
   db_redireciona("con1_convconvenios005.php?liberaaba=true&chavepesquisa=$c206_sequencial");
  }
}
?>
