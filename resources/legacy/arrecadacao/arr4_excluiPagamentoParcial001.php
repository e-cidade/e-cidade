<?php

use App\Models\AutorizaUsuarioExcluirPgtoParcial;

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
include_once("libs/db_sessoes.php");
include_once("libs/db_usuariosonline.php");
include_once("dbforms/db_funcoes.php");
include_once("libs/db_utils.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/md5.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/datagrid.widget.js"></script>

<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc" onload="js_pesquisaAbatimento()">
<br><br>
<?php
$AutorizaUsuarioExcluirPgtoParcial = AutorizaUsuarioExcluirPgtoParcial::query()->where ('id_usuario', db_getsession("DB_id_usuario"))->first();
$idUsuario = $AutorizaUsuarioExcluirPgtoParcial->id_usuario;

if (db_getsession("DB_id_usuario") == 1 || (!empty($idUsuario))) {
?>
<div align="center">
<fieldset style="width: 300px;">
<legend> <b>Excluir Pagamento Parcial</b> </legend>
<form name="form1" method="POST">
 <table align="center" border=0 width="100%">
  <tr>
  <tr>
   <td>
     <?
       db_ancora("Abatimento:","js_pesquisaAbatimento();",1);
     ?>
   </td>
   <td>
     <?
       db_input("abatimento",10,"1",true,'text',3);
      ?>
   </td>
  </tr>
  <tr>
    <td colspan=2 style="visibility:hidden;" id ="MI">
      <?
       db_ancora('Consultar Origens Atuais do Abatimento',"js_consultaOrigemPgtoParcial()",1,'');
      ?>
    </td>
  </tr>
 </table>
</fieldset>
<br>
 <input type="button" name="btnExcluir" id="btnExcluir" value="Excluir" disabled />
</form>
</div>

<?php
  } else {
	  db_msgbox("Procedimento n�o dispon�vel!");
  }
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>


  var sUrlRPC = "arr4_manutencaoAbatimento.RPC.php";

	$('btnExcluir').observe("click", function() {
		js_ExcluirPagamentoParcial();
	});

  function js_pesquisaAbatimento() {
	    js_OpenJanelaIframe('','db_iframe_abatimento','func_abatimento.php?tipo=1&funcao_js=parent.js_mostraAbatimento1|k125_sequencial|k125_valor','Pesquisa',true);
	}

	function js_mostraAbatimento1(chave1,chave2) {

	  $("abatimento").value    = chave1;
	  db_iframe_abatimento.hide();

	  $('btnExcluir').disabled  = false;
	  $("MI").style.visibility = 'visible';
	  js_buscaOrigensAbatimento(chave1);
	}

	function js_consultaOrigemPgtoParcial() {

		var sUrl = 'func_origemabatimentoparcial.php?iAbatimento='+$("abatimento").value;
	  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_abatimento',sUrl,'Origem Pagto. Parcial',true);

	}

	function js_ExcluirPagamentoParcial() {

		if (confirm("Confirma a exclus�o do pagto. parcial gerado pelo abatimento "+$("abatimento").value+"?")) {
			if (confirm("Este procedimento n�o poder� ser revertido!\nTem certeza que deseja confirmar a opera��o?")) {
		    if (confirm("Confirma a exclus�o do pagto. parcial gerado pelo abatimento "+$("abatimento").value+" mesmo sabendo que a opera��o n�o poder� ser revertida?")) {
  		  	if (!confirm("Confirma exclus�o do abatimento "+$("abatimento").value+"?")) {
  	  		  return false;
  		  	}
	  	  } else {
		  	  return false;
	  	  }
			} else {
				return false;
			}
		} else {
			return false;
		}
		var oParam  				   = new Object();
		    oParam.iAbatimento = $("abatimento").value;
		    oParam.exec 		   = "exluirPagamentoParcial";

		  js_divCarregando("Aguarde, processando exclus�o do abatimento...", "msgBox");

		  var oAjax = new Ajax.Request(sUrlRPC,
		                                {
		                                  method:'post',
		                                  parameters:'json='+Object.toJSON(oParam),
		                                  onComplete: js_retornoExcluirPagamentoParcial
		                                }
		                              );

	}

	function js_retornoExcluirPagamentoParcial(oAjax){

		  js_removeObj("msgBox");
		  var oRetorno = eval("("+oAjax.responseText+")")
		  alert(oRetorno.message.urlDecode());

		  if (oRetorno.status == 1) {
		    js_limpaTela();
		  }
  }

	function js_limpaTela() {

		$("abatimento").value    = "";
		$("MI").style.visibility = 'hidden';
		$('btnExcluir').disabled  = true;
		js_pesquisaAbatimento();

	}

</script>
