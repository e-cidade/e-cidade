<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBselller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");

require_once("libs/db_utils.php");
require_once("classes/db_clienteatributovalor_classe.php");
require_once("classes/db_clienteatributo_classe.php");
require_once("classes/db_clientes_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$clClientes              = new cl_clientes();
$clClienteAtributo       = new cl_clienteatributo();
$clClienteAtributoValor  = new cl_clienteatributovalor();
$clIframeAlterarExcluir  = new cl_iframe_alterar_excluir;

$clClienteAtributoValor->rotulo->label();

$db_opcao = 22;
$db_botao = false;
$lSqlErro = false;

if( isset($oPost->incluir) || isset($oPost->alterar) || isset($oPost->excluir) ){

  if ($lSqlErro == false) {

  	db_inicio_transacao();

  	$clClienteAtributoValor->at94_cliente         = $oPost->at94_cliente;
  	$clClienteAtributoValor->at94_valor           = $oPost->at94_valor;
  	$clClienteAtributoValor->at94_clienteatributo = $oPost->at94_clienteatributo;

  	if ( isset($oPost->incluir) ) {
	  	$clClienteAtributoValor->incluir(null);
  	} else if ( isset($oPost->alterar) ) {
  	  $clClienteAtributoValor->alterar($oPost->at94_sequencial);
  	} else {
  	  $clClienteAtributoValor->excluir($oPost->at94_sequencial);
  	}

  	$sMsgErro = $clClienteAtributoValor->erro_msg;

    if ($clClienteAtributoValor->erro_status == 0) {
      $lSqlErro = true;
    }

    db_fim_transacao($lSqlErro);

  }

} else if ( isset($oPost->opcao) ) {

	$sSqlDadosAtributo = $clClienteAtributoValor->sql_query($oPost->at94_sequencial);
  $rsDadosAtributo   = $clClienteAtributoValor->sql_record($sSqlDadosAtributo);

  if ( $rsDadosAtributo ) {
	  db_fieldsmemory($rsDadosAtributo,0);
  }

}



if ( isset($oGet->at94_cliente) ) {
 	$at94_cliente = $oGet->at94_cliente;
} else if ( isset($oPost->at94_cliente) ) {
	$at94_cliente = $oPost->at94_cliente;
}

if (isset($db_opcaoal)){
  $db_opcao = 33;
  $db_botao = false;
} else if (isset($opcao) && $opcao=="alterar"){
  $db_botao = true;
  $db_opcao = 2;
} else if (isset($opcao) && $opcao=="excluir"){
  $db_opcao = 3;
  $db_botao = true;
} else {

  $db_opcao = 1;
  $db_botao=true;

  if(isset($oPost->novo) ||
     isset($oPost->alterar) ||
     isset($oPost->excluir) ||
    (isset($oPost->incluir) && $lSqlErro == false ) ){

    $at94_sequencial      = "";
    $at93_descricao       = "";
    $at94_clienteatributo = "";
    $at94_valor           = "";

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
<form name="form1" method="post" action="">
	<table align="center">
	  <tr>
	    <td>
			  <fieldset>
			    <legend>
			      <b>Dados Atributo</b>
			    </legend>
			  	<table>
	          <tr>
	            <td>
	              <?=@$Lat94_sequencial?>
	            </td>
	            <td>
	              <?
	                db_input('at94_sequencial',10,$Iat94_sequencial,true,'text',3,'');
	                db_input('at94_cliente'   ,10,'',true,'hidden',3,'');
	              ?>
	            </td>
	          </tr>
            <tr>
              <td>
                <b>
                <?
                  db_ancora($Lat94_clienteatributo,"js_pesquisaAtributo(true);",$db_opcao);
                ?>
                </b>
              </td>
              <td>
                <?
                  db_input('at94_clienteatributo',10,$Iat94_clienteatributo,true,'text',$db_opcao,"onChange='js_pesquisaAtributo(false)'");
                  db_input('at93_descricao'      ,40,'',true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <?=@$Lat94_valor?>
              </td>
              <td>
                <?
                  db_input('at94_valor',54,$Iat94_valor,true,'text',$db_opcao,'');
                ?>
              </td>
            </tr>
	          <tr>
					    <td colspan="2" align="center">
							  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
							  <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
					    </td>
					  </tr>
					</table>
			  </fieldset>
			</td>
		</tr>
		<tr>
		  <td>
			  <table>
				  <tr>
				    <td valign="top"  align="center">
					    <?

					      $aChavePri     = array("at94_sequencial"=>@$at94_sequencial);
					      $sWhereAtributo = "at94_cliente = {$oGet->at94_cliente} ";
					      $sSqlAtributo   = $clClienteAtributoValor->sql_query(null,"*",null,$sWhereAtributo);

					      $clIframeAlterarExcluir->chavepri      = $aChavePri;
							  $clIframeAlterarExcluir->sql           = $sSqlAtributo;
							  $clIframeAlterarExcluir->campos        = "at94_sequencial,at93_descricao,at94_valor";
							  $clIframeAlterarExcluir->legenda       = "Atributos Lan�ados";
							  $clIframeAlterarExcluir->iframe_height = "160";
							  $clIframeAlterarExcluir->iframe_width  = "500";
							  $clIframeAlterarExcluir->iframe_alterar_excluir($db_opcao);
					    ?>
			      </td>
			    </tr>
	 	    </table>
	  	</td>
	  </tr>
	</table>
</form>
</body>
</html>
<?
if(isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)){

 db_msgbox($sMsgErro);

  if($clClienteAtributoValor->erro_campo!=""){
    echo "<script> document.form1.".$clClienteAtributoValor->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clClienteAtributoValor->erro_campo.".focus();</script>";
  }
}
?>
<script>

function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

function js_pesquisaAtributo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_atributos','db_iframe_atributos','func_clienteatributo.php?funcao_js=parent.js_mostraAtributo1|at93_sequencial|at93_descricao','Pesquisa',true);
  }else{
     if(document.form1.at94_clienteatributo.value != ''){
       js_OpenJanelaIframe('CurrentWindow.corpo.iframe_atributos','db_iframe_atributos','func_clienteatributo.php?pesquisa_chave='+document.form1.at94_clienteatributo.value+'&funcao_js=parent.js_mostraAtributo','Pesquisa',false);
     } else{
       document.form1.at93_descricao.value = '';
     }
  }
}

function js_mostraAtributo(chave,erro){
  document.form1.at93_descricao.value = chave;
  if(erro==true){
    document.form1.at94_clienteatributo.focus();
    document.form1.at94_clienteatributo.value = '';
  }
}

function js_mostraAtributo1(chave1,chave2){
  document.form1.at94_clienteatributo.value = chave1;
  document.form1.at93_descricao.value       = chave2;
  db_iframe_atributos.hide();
}

</script>
