<?php
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_classesgenericas.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbtextField.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<br><br>
<form id='frmImplantacaoDepreciacao'>
<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
    	<fieldset>
    		<legend><b>Filtros para Pesquisa</b></legend>
    		<br>
    		<table width="100%">
    			<tr>
    			  <td><?php db_ancora('<b>Classifica��o: </b>', ' js_pesquisaClassificacaoInicial(true); ', 1); ?></td>
    			  <td>
    			    <?php
    			      db_input('t64_codcla_ini', 5, "", true, 'text', 1, ' onchange="js_pesquisaClassificacaoInicial(false);" ');
    			      db_input('t64_descr_ini', 20, "", true, 'text', 3);
    			    ?>
    			  </td>
    			  <td><?php db_ancora('<b>At�: </b>', ' js_pesquisaClassificacaoFinal(true); ', 1); ?></td>
    			  <td>
    			    <?php
    			    	db_input('t64_codcla_fin', 5, "", true, 'text', 1, ' onchange="js_pesquisaClassificacaoFinal(false);" ');
    			    	db_input('t64_descr_fin', 20, "", true, 'text', 3);
    			    ?>
    			  </td>
    			</tr>
    		</table>
    		<br>
    	</fieldset>
    	<br>
    	<input type="button" value="Pesquisar" onclick="js_envia();">
    </center>
  </td>
  </tr>
</table>
</form>
  <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>

<script>

var sUrl = "pat5_implantacaobensdepreciacao.RPC.php";
var iClassificacaoInicial = "";
var iClassificacaoFinal = "";

/**
 * Fun��o que efetua a pesquisa de classifica��es de bens para a classifica��o inicial
 */
function js_pesquisaClassificacaoInicial(mostra) {

	if (mostra === true) {
		js_OpenJanelaIframe('CurrentWindow.corpo',
				                'db_iframe_classificacao',
				                'func_clabens.php?funcao_js=parent.js_mostraClassificacaoInicial|t64_codcla|t64_descr',
				                'Pesquisa Classifica��o',
				                true);
	} else {

		var sValorCampo = $F('t64_codcla_ini');
		js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_classificacao',
                        'func_clabens.php?pesquisa_chave='+sValorCampo+'&funcao_js=parent.js_mostraClassificacaoInicial',
                        'Pesquisa Classifica��o',
                        false);
	}
}

/**
 * Fun��o que mostra o resultado da pesquisa de classifica��o de bens para a classifica��o inicial
 */
function js_mostraClassificacaoInicial() {

	if (arguments[1] === true) {

		$('t64_codcla_ini').value = '';
		$('t64_descr_ini').value  = arguments[0];
	} else if (arguments[1] === false) {

		$('t64_codcla_ini').value = arguments[2];
		$('t64_descr_ini').value  = arguments[0];
	}	else {

		$('t64_codcla_ini').value = arguments[0];
	  $('t64_descr_ini').value  = arguments[1];
	}
	db_iframe_classificacao.hide();
}

/**
 * Fun��o que efetua a pesquisa de classifica��es de bens para a classifica��o final
 */
function js_pesquisaClassificacaoFinal(mostra) {

	if (mostra === true) {
		js_OpenJanelaIframe('CurrentWindow.corpo',
				                'db_iframe_classificacao',
				                'func_clabens.php?funcao_js=parent.js_mostraClassificacaoFinal|t64_codcla|t64_descr',
				                'Pesquisa Classifica��o',
				                true);
	} else {

		var sValorCampo = $F('t64_codcla_fin');
		js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_classificacao',
                        'func_clabens.php?pesquisa_chave='+sValorCampo+'&funcao_js=parent.js_mostraClassificacaoFinal',
                        'Pesquisa Classifica��o',
                        false);
	}
}

/**
 * Fun��o que mostra o resultado da pesquisa de classifica��o de bens para a classifica��o final
 */
function js_mostraClassificacaoFinal() {

	if (arguments[1] === true) {

		$('t64_codcla_fin').value = '';
		$('t64_descr_fin').value  = arguments[0];
	} else if (arguments[1] === false) {

		$('t64_codcla_fin').value = arguments[2];
		$('t64_descr_fin').value  = arguments[0];
	}	else {

		$('t64_codcla_fin').value = arguments[0];
	  $('t64_descr_fin').value  = arguments[1];
	}
	db_iframe_classificacao.hide();
}

/**
 * Fun��o que exibe a windowAux para ser inserido o tipo de depreca��o e a vida �til
 */
function js_envia() {


	var iCodClaInicial    = $F('t64_codcla_ini');
	iClassificacaoInicial = $F('t64_codcla_ini');
	var iCodClaFinal      = $F('t64_codcla_fin');
	iClassificacaoFinal   = $F('t64_codcla_fin');

	if (iCodClaInicial == "") {

		alert("Informe a classifica��o inicial.");
		return false;
	}
	if (iCodClaFinal == "") {

		alert("Informe a classifica��o final.");
		return false;
	}
	if (iCodClaInicial > iCodClaFinal) {

		alert("A classifica��o inicial deve ser menor que a final.");
		return false;
	}


	var larguraJanela     = screen.availWidth - 200;
	var alturaJanela      = screen.availHeight - 200;
	windowClassificacoes  = new windowAux('windowClassificacoes',
			                                  'Classifica��es Filtradas',
			                                  larguraJanela, alturaJanela);

	var sConteudowinAux  = '<div> ';
	    sConteudowinAux += '  <fieldset style="margin-top:5px; width:97%;">                                           ';
	    sConteudowinAux += '    <legend><b>Dados da Deprecia��o</b></legend>                                          ';
	    sConteudowinAux += '    <table>                                                                               ';
	    sConteudowinAux += '      <tr>                                                                                ';
	    sConteudowinAux += '        <td nowrap="nowrap">                                                              ';
			sConteudowinAux += '          <a href="#" class="dbancora" style="text-decoration:underline;"                 ';
			sConteudowinAux += '             onclick="js_pesquisaTipoDepreciacao(true);"><b>Tipo de Deprecia��o:</b></a>  ';
	    sConteudowinAux += '        </td>                                                                             ';
	    sConteudowinAux += '        <td id="inputcodigotipodepreciacao"                                               ';
	    sConteudowinAux += '            onChange="js_pesquisaTipoDepreciacao(false);"></td>                           ';
	    sConteudowinAux += '        <td id="inputdescricaotipodepreciacao"></td>                                      ';
	    sConteudowinAux += '        <td nowrap="nowrap">                                                              ';
	    sConteudowinAux += '          <b>Vida �til:</b>                                                               ';
	    sConteudowinAux += '        </td>                                                                             ';
	    sConteudowinAux += '        <td id="inputvidautil"></td>                                                      ';
	    sConteudowinAux += '        <td nowrap="nowrap">                                                              ';
	    sConteudowinAux += '          <a href="#" class="dbancora" style="text-decoration:underline;"                 ';
			sConteudowinAux += '             onclick="js_pesquisaTipoAquisicao(true);"><b>Tipo de Aquisi��o:</b></a>      ';
	    sConteudowinAux += '				</td>                                                                             ';
	    sConteudowinAux += '        <td id="inputcodigotipoaquisicao"                                                 ';
	    sConteudowinAux += '            onChange="js_pesquisaTipoAquisicao(false);"></td>                             ';
	    sConteudowinAux += '        <td id="inputdescricaotipoaquisicao"></td>                                        ';
	    sConteudowinAux += '      </tr>                                                                               ';
	    sConteudowinAux += '    </table>                                                                              ';
	    sConteudowinAux += '  </fieldset>                                                                             ';
	    sConteudowinAux += '  <fieldset style="margin-top:5px; margin-bottom:5px; width:97%;">                        ';
	    sConteudowinAux += '    <legend><b>Classifica��o/Bens</b></legend>                                            ';
	    sConteudowinAux += '    <div id="gridContainer"></div>                                                        ';
	    sConteudowinAux += '  </fieldset>                                                                             ';
	    sConteudowinAux += '  <center>                                                                                ';
	    sConteudowinAux += '  <input type="button" value="Processar" onclick="js_processaTipoDepreciacao();">         ';
	    sConteudowinAux += '  <input type="button" value="Cancelar" onclick="js_cancelaProcessamentoClassificacao();">';
	    sConteudowinAux += '</center></div>                                                                           ';
  windowClassificacoes.setContent(sConteudowinAux);

  var sTextoMessageBoard  = 'Informe o tipo de deprecia��o e a vida �til dos bens. A informa��o ser� replicada para ';
      sTextoMessageBoard += 'as classifica��es/Bens listados abaixo.';

  messageBoard = new DBMessageBoard('msgboard1',
		                                'Manuten��o de Tipos de Deprecia��o.',
		                                sTextoMessageBoard,
		                                $('windowwindowClassificacoes_content'));

  dbGrid = new DBGrid('gridContainer');
  dbGrid.nameInstance = 'dbGrid';
  dbGrid.hasTotalizador = false;
  dbGrid.setHeight(alturaJanela - 300);
  dbGrid.allowSelectColumns(false);

  var aHeader = new Array();
      aHeader[0] = 'Classifica��o';
      aHeader[1] = '';
    	aHeader[2] = 'Descri��o';
    	aHeader[3] = 'C�digo Bem';
    	aHeader[4] = 'Placa';
    	aHeader[5] = 'Vida �til';
    	aHeader[6] = 'Deprecia��o';
    	aHeader[7] = 'C�digo Tipo Deprecia��o';

  var aAligns = new Array();
  		aAligns[0] = 'left';
  		aAligns[1] = 'left';
  		aAligns[2] = 'left';
  		aAligns[3] = 'right';
  		aAligns[4] = 'right';
  		aAligns[5] = 'right';
  		aAligns[6] = 'left';
  		aAligns[7] = 'left';

  dbGrid.setCellAlign(aAligns);
  dbGrid.setHeader(aHeader);
  dbGrid.aHeaders[1].lDisplayed = false;
  dbGrid.aHeaders[3].lDisplayed = false;
  dbGrid.aHeaders[7].lDisplayed = false;
  dbGrid.show($('gridContainer'));

  oTxtCodTipoDepreciacao = new DBTextField('oTxtCodTipoDepreciacao', 'oTxtCodTipoDepreciacao', '', 4);
  oTxtCodTipoDepreciacao.show($('inputcodigotipodepreciacao'));
  oTxtCodTipoDepreciacao.setReadOnly(false);

  oTxtDescrTipoDepreciacao = new DBTextField('oTxtDescrTipoDepreciacao', 'oTxtDescrTipoDepreciacao', '', 20);
  oTxtDescrTipoDepreciacao.show($('inputdescricaotipodepreciacao'));
  oTxtDescrTipoDepreciacao.setReadOnly(true);

  oTxtVidaUtil = new DBTextField('oTxtVidaUtil', 'oTxtVidaUtil', '', 20);
  oTxtVidaUtil.show($('inputvidautil'));
  oTxtVidaUtil.setReadOnly(false);

  oTxtCodTipoAquisicao = new DBTextField('oTxtCodTipoAquisicao', 'oTxtCodTipoAquisicao', '', 4);
  oTxtCodTipoAquisicao.show($('inputcodigotipoaquisicao'));
	oTxtCodTipoAquisicao.setReadOnly(false);

	oTxtDescrTipoAquisicao = new DBTextField('oTxtDescrTipoAquisicao', 'oTxtDescrTipoAquisicao', '', 20);
	oTxtDescrTipoAquisicao.show($('inputdescricaotipoaquisicao'));
	oTxtDescrTipoAquisicao.setReadOnly(true);

  windowClassificacoes.setShutDownFunction(function() {
    js_cancelaProcessamentoClassificacao();
  });
  windowClassificacoes.show();
  messageBoard.show();
  js_mostraClassificacaoBensNaGrid();
}

function js_pesquisaTipoDepreciacao(mostra) {

	if (mostra === true) {
		js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_tipodepreciacao',
                        'func_benstipodepreciacao.php?l=true&funcao_js=parent.js_mostraTipoDepreciacao|t46_sequencial|t46_descricao',
                        'Pesquisa Tipo Depreciacao',
                        true);
	} else {

		var sValorCampo = $F('oTxtCodTipoDepreciacao');
		js_OpenJanelaIframe('CurrentWindow.corpo',
				                'db_iframe_tipodepreciacao',
				                'func_benstipodepreciacao.php?l=true&pesquisa_chave='+sValorCampo+
				                '&funcao_js=parent.js_mostraTipoDepreciacao',
				                'Pesquisa Tipo Deprecia��o',
				                false);
	}
	$('Jandb_iframe_tipodepreciacao').style.zIndex = 10000;
}

function js_mostraTipoDepreciacao() {

	if (arguments[1] === true) {

		$('oTxtCodTipoDepreciacao').value = '';
		$('oTxtDescrTipoDepreciacao').value = arguments[0];
	} else if (arguments[1] === false) {

		$('oTxtDescrTipoDepreciacao').value = arguments[0];
	} else {

		$('oTxtCodTipoDepreciacao').value = arguments[0];
		$('oTxtDescrTipoDepreciacao').value = arguments[1];
	}
	db_iframe_tipodepreciacao.hide();
}

function js_pesquisaTipoAquisicao(mostra) {

  if (mostra === true) {
    js_OpenJanelaIframe('CurrentWindow.corpo',
        								'db_iframe_tipoaquisicao',
        								'func_benstipoaquisicao.php?funcao_js=parent.js_mostraTipoAquisicao|t45_sequencial|t45_descricao',
        								'Pesquisa Tipo Aquisi��o',
        								true);
  } else {

    var sValorCampo = $F('oTxtCodTipoAquisicao');
    js_OpenJanelaIframe('CurrentWindow.corpo',
												'db_iframe_tipoaquisicao',
												'func_benstipoaquisicao.php?funcao_js=parent.js_mostraTipoAquisicao&pesquisa_chave='+sValorCampo,
												'Pesquisa Tipo Aquisi��o',
												true);
  }
  $('Jandb_iframe_tipoaquisicao').style.zIndex = 10000;
}

function js_mostraTipoAquisicao(oAjax) {

  if (arguments[1] === true) {

		$('oTxtCodTipoAquisicao').value = '';
		$('oTxtDescrTipoAquisicao').value = arguments[0];
	} else if (arguments[1] === false) {

		$('oTxtDescrTipoAquisicao').value = arguments[0];
	} else {

		$('oTxtCodTipoAquisicao').value = arguments[0];
		$('oTxtDescrTipoAquisicao').value = arguments[1];
	}
	db_iframe_tipoaquisicao.hide();
}

function js_mostraClassificacaoBensNaGrid() {

	var oParam = new Object();
			oParam.exec = 'getBensClassificacao';
			oParam.t64_codcla_ini = $F('t64_codcla_ini');
			oParam.t64_codcla_fin = $F('t64_codcla_fin');

	var oAjax = new Ajax.Request(sUrl,
			                         {method: 'post',
			                          asynchronous: false,
			                          parameters: 'json='+Object.toJSON(oParam),
			                          onComplete: js_populaGrid
                               }
                              );
}

function js_populaGrid(oAjax) {

	var oRetorno = eval('('+oAjax.responseText+')');
	dbGrid.clearAll(true);
	if (oRetorno.status == 1) {

	  /**
	   * Percorre retorno do RPC imprimindo os dados na grid
	   */
	  var iLinha = 0;
	  for (iClassificacao in oRetorno.aDados) {

	    if (typeof(oRetorno.aDados[iClassificacao]) == "function") {
				continue;
	    }
      with (oRetorno.aDados[iClassificacao]) {

        /**
         * Imprime a linha que mostra a classifica��o dos bens
         */
        var aRowClassificacao = new Array();
        aRowClassificacao[0]  = classificacao;
        aRowClassificacao[1]  = '';
				aRowClassificacao[2]  = descricao.urlDecode();
				aRowClassificacao[3]  = '';
				aRowClassificacao[4]  = '';
				aRowClassificacao[5]  = '';
				aRowClassificacao[6]  = '';
				aRowClassificacao[7]  = '';
		    dbGrid.addRow(aRowClassificacao);

		    dbGrid.aRows[iLinha].sStyle = 'background-color:#eeeee2;';
		    dbGrid.aRows[iLinha].aCells.each(function (oCell, iIndice) {

          oCell.sStyle +=';border-right: 1px solid #eeeee2;';
          oCell.sStyle += 'text-align:left;font-weight:bold;';
			  });

			  /**
			   * Percorremos os itens para verificarmos a quantidade de caracteres do campo t52_bem
			   * e guardamos essa informa��o para ser usada no js_strLeftPad
			   */
			  var iQuantCaracteresCodigoBem = 1;
			  itens.each(function(oItem, iSeqItem) {

				   if (oItem.t52_bem.length > iQuantCaracteresCodigoBem) {
				     iQuantCaracteresCodigoBem = oItem.t52_bem.length;
				   }
			  });

		    /**
		     *  Adiciona os bens associados a classifica��o adicionada anteriormente
		     */
		    itens.each(function(oItem, iSeqItem) {

					var sDescricao                 = "";
					var iSequencialTipoDepreciacao = "";

		      var aRowItem = new Array();
							aRowItem[0]  = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+classificacao+"."+js_strLeftPad(oItem.t52_bem, iQuantCaracteresCodigoBem, "0");
							aRowItem[1]  = '';
							aRowItem[2]  = oItem.t52_descr.urlDecode();
							aRowItem[3]  = oItem.t52_bem;
							aRowItem[4]  = oItem.t52_ident;
							aRowItem[5]  = oItem.t44_vidautil;

					if (oItem.t46_sequencial != "") {

					  sDescricao 								 = oItem.t46_sequencial+' - '+oItem.t46_descricao.urlDecode();
						iSequencialTipoDepreciacao = oItem.t46_sequencial;
					}

					aRowItem[6]  	 = sDescricao;
					aRowItem[7]  	 = iSequencialTipoDepreciacao;
					dbGrid.addRow(aRowItem);
				  iLinha++;
		    });
        iLinha++;
      }
	  }
    dbGrid.renderRows();
	} else {
		alert(oRetorno.message.urlDecode());
	}
}

/**
 * Processa o tipo de deprecia��o
 * Valida se existem conflitos dos tipos de deprecia��o para os bens listados. Caso exista, ser� aberta uma
 * windowAux listando os bens em conflito.
 */
function js_processaTipoDepreciacao() {

  if (oTxtCodTipoDepreciacao.getValue() == "") {

    alert("Informe o tipo de deprecia��o.");
    return false;
  }
  if (oTxtVidaUtil.getValue() == "") {

		alert("Informe a vida �til.");
		return false;
  }
  if (oTxtCodTipoAquisicao.getValue() == "") {

    alert("Informe o tipo de aquisi��o.");
    return false;
  }

	var aItensComConflito = new Array();
	aItensProcessar = new Array();
  dbGrid.aRows.each (function (oRow, iIdRow) {

		/**
		 *  Adiciona os itens que possuem tipo de deprecia��o diferentes do tipo escolhido pelo usu�rio.
		 *  Refatorar essa valida��o
		 */
    if (oRow.aCells[7].getValue().trim() != oTxtCodTipoDepreciacao.getValue() && oRow.aCells[7].getValue().trim() != "") {
      aItensComConflito.push(oRow);
    } else {
      aItensProcessar.push(oRow);
    }
  });

  if (aItensComConflito.length > 0) {

    var iLargura = $('windowClassificacoes').getWidth() - 100;
  	var iAltura  = $('windowClassificacoes').getHeight() - 100;
  	oWindowConflitos  = new windowAux('oWindowConflitos',
  			                              'Conflitos Encontrados',
  			                              iLargura, iAltura);

  	var sConteudowinAux  = '<div align="center">                                            ';
  			sConteudowinAux += '  <fieldset>                                                    ';
  			sConteudowinAux += '    <legend><b>Conflitos</b></legend>                           ';
  			sConteudowinAux += '    <div id="gridConflitosContainer"></div>                     ';
  			sConteudowinAux += '  </fieldset>                                                   ';
  			sConteudowinAux += '  <input type="button" name="btnProcessar" id="btnProcessar"    ';
    		sConteudowinAux += '         value="Processar" onclick="js_configuraConflitos();">  ';
  			sConteudowinAux += '  <input type="button" name="btnCancelar" id="btnCancelar"      ';
    		sConteudowinAux += '         value="Cancelar" onclick="js_fechaWindowConflitos();"> ';
  			sConteudowinAux += '</div>                                                          ';
		oWindowConflitos.setContent(sConteudowinAux);
		oWindowConflitos.allowCloseWithEsc(true);

	  var sTextoMessageBoard  = 'Os bens/classifica��es abaixo est�o em conflito com a configura��o de deprecia��o ';
        sTextoMessageBoard += 'informada. Marque as que deseja alterar o tipo de deprecia��o.';

		oMsgBoardConflito = new DBMessageBoard('oMsgBoardConflito',
    	                                    'Conflitos encontrados no processamento',
    	                                    sTextoMessageBoard,
    	                                    oWindowConflitos.getContentContainer());
    oWindowConflitos.setChildOf(windowClassificacoes);
    oWindowConflitos.setShutDownFunction(function () {
      js_fechaWindowConflitos();
    });
    oWindowConflitos.show(40, 50);
    oMsgBoardConflito.show();

    oGridConflitos                = new DBGrid('gridConflitosContainer');
    oGridConflitos.nameInstance   = 'oGridConflitos';
    oGridConflitos.hasTotalizador = false;
    oGridConflitos.hasCheckbox    = true;
    oGridConflitos.setHeight(iAltura - 200);
    oGridConflitos.allowSelectColumns(false);

    var aHeader    = new Array();
        aHeader[0] = 'Classifica��o';
      	aHeader[1] = 'Descri��o';
      	aHeader[2] = 'C�digo Bem';
      	aHeader[3] = 'Placa';
      	aHeader[4] = 'Vida �til';
      	aHeader[5] = 'Deprecia��o';
      	aHeader[6] = 'C�digo Tipo Deprecia��o';

    var aAligns 	 = new Array();
    		aAligns[0] = 'left';
    		aAligns[1] = 'left';
    		aAligns[2] = 'right';
    		aAligns[3] = 'right';
    		aAligns[4] = 'right';
    		aAligns[5] = 'left';
    		aAligns[6] = 'left';

		oGridConflitos.setCellAlign(aAligns);
    oGridConflitos.setHeader(aHeader);
    oGridConflitos.aHeaders[3].lDisplayed = false;
    oGridConflitos.aHeaders[7].lDisplayed = false;
    oGridConflitos.show($('gridConflitosContainer'));

    oGridConflitos.clearAll(true);

    aItensComConflito.each(function (oRowItemConlito, iIdLinha) {

      var aLinha    = new Array();
          aLinha[0] = oRowItemConlito.aCells[0].getValue();
          aLinha[1] = oRowItemConlito.aCells[2].getValue();
          aLinha[2] = oRowItemConlito.aCells[3].getValue();
          aLinha[3] = oRowItemConlito.aCells[4].getValue();
          aLinha[4] = oRowItemConlito.aCells[5].getValue();
          aLinha[5] = oRowItemConlito.aCells[6].getValue();
          aLinha[6] = oRowItemConlito.aCells[7].getValue();
      oGridConflitos.addRow(aLinha);
    });
    oGridConflitos.renderRows();
  } else {

    if (!confirm("Deseja implementar a deprecia��o para os bens listados?")) {
			return false;
    }
  	js_processarImplementacao();
  }

}


function js_configuraConflitos() {

  if (!confirm("Deseja implementar a deprecia��o para os bens listados?")) {
		return false;
  }

	var aLinhasSelecionadas = oGridConflitos.getSelection("object");
	aLinhasSelecionadas.each(function(oLinhaSelecionada, iIdLinha){
	  aItensProcessar.push(oLinhaSelecionada);
	});
  js_processarImplementacao();
}

function js_processarImplementacao() {

  var aBensImplementar = new Array();

  if (aItensProcessar.length == 0) {

    alert("Sem itens para a implanta��o de deprecia��o.");
    return false;
  }

  aItensProcessar.each(function(oBemImplementar, iIdLinha){

    if (oBemImplementar.aCells[3].getValue().trim() != "") {

  		var oBem = new Object();
  		    oBem.iBem = oBemImplementar.aCells[3].getValue();
  		aBensImplementar.push(oBem);
    }
  });

  var oParam                  = new Object();
    	oParam.exec             = 'processarImplementacao';
    	oParam.iTipoDepreciacao = $F('oTxtCodTipoDepreciacao');
    	oParam.iVidaUtil        = $F('oTxtVidaUtil');
    	oParam.iTipoAquisicao   = $F('oTxtCodTipoAquisicao');
      oParam.aItens           = aBensImplementar;

  var oAjax = new Ajax.Request(sUrl,
  	                           {method: 'post',
  	                            asynchronous: false,
  	                            parameters: 'json='+Object.toJSON(oParam),
  	                            onComplete: js_retornoProcessamentoImplementacao
                               }
                              );
}

function js_retornoProcessamentoImplementacao(oAjax) {

  var oRetorno = eval('('+oAjax.responseText+')');
  if (oRetorno.status == 1) {

    alert('Implanta��o de deprecia��o efetuada com sucesso.');
    js_cancelaProcessamentoClassificacao();
    js_fechaWindowConflitos();
    $('t64_codcla_ini').value = '';
    $('t64_descr_ini').value  = '';
    $('t64_codcla_fin').value = '';
    $('t64_descr_fin').value  = '';
  } else {
    alert(oRetorno.message.urlDecode());
  }
}

function js_fechaWindowConflitos() {
  oWindowConflitos.destroy();
}

function js_cancelaProcessamentoClassificacao() {
  windowClassificacoes.destroy();
}

function js_validaCalculos() {

  var oParam      = new Object();
      oParam.exec = 'validarCalculosEfetuados';

  var oAjax = new Ajax.Request(sUrl,
                               {method: 'post',
                                asynchronous: false,
                                parameters: 'json='+Object.toJSON(oParam),
                                onComplete: js_retornoValidaCalculos
                               }
                              );
}

function js_retornoValidaCalculos(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 2) {

    $('frmImplantacaoDepreciacao').disable();
    alert(oRetorno.message.urlDecode());
  }
}

js_validaCalculos();
</script>
