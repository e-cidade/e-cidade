<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_liclicitem_classe.php");
require_once("classes/db_liclicita_classe.php");
require_once("classes/db_liclicitaitemlog_classe.php");
require_once("model/licitacao.model.php");
require_once("model/licitacao/SituacaoLicitacao.model.php");

?>
<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<style>
  .bensMeEpp,
  .bensMeEpp td {
    background-color: #c0bfff;
  }
</style>

<body style="margin-top: 5px; background-color: #CCCCCC;">
  <center>
    <fieldset>
      <legend><b>Itens da Licita��o</b></legend>
      <div id="ctnGridItens"></div>
    </fieldset>
  </center>
  <div style='width: 100%; margin-bottom: 12px;'>
    <fieldset style='width: 150px'>
      <legend>Legenda</legend>
      <table colspan='0' class='table__legenda'>
        <tr>
          <td align='center' class='bensMeEpp' height='30px' width='150px'><b>Itens ME/EPP</b></td>
        </tr>
      </table>
    </fieldset>
  </div>
</body>

</html>

<script>
  var aHeaders = ["C�digo", "Autoriza��o", "Qtd. Solicitada", "Vlr. Unit�rio", "Unidade", "Material", "Fornecedor", ""];

  var aCellAlign = ["center", "center", "center", "right", "center", "left", "left", "left"];

  var aCellWidth = ["5%", "8%", "8%", "8%", "8%", "38%", "22%", ""];

  var oGridItens = new DBGrid("oGridItens");
  oGridItens.sNameInstance = "oGridItens";
  oGridItens.setHeader(aHeaders);
  oGridItens.setCellAlign(aCellAlign);
  oGridItens.setCellWidth(aCellWidth);
  oGridItens.show($('ctnGridItens'));

  oGridItens.setStatus("Coloque o cursor sob a linha para obter mais informa��es.");

  var oGet = js_urlToObject();
  var oParametro = {
    "exec": "getItensConsultaLicitacao",
    "iCodigoLicitacao": oGet.l20_codigo
  };

  js_divCarregando("Aguarde, carregando itens da licita��o", "msgBox");
  new Ajax.Request("lic4_licitacao.RPC.php", {
    method: 'post',
    asynchronous: false,
    parameters: 'json=' + Object.toJSON(oParametro),
    onComplete: completarGrid
  });

  function completarGrid(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("(" + oAjax.responseText + ")");

    oGridItens.clearAll(true);
    if (oRetorno.aItens.length == 0) {
      alert("Nenhum item encontrado para a licita��o " + oGet.l20_codigo + ".");
    }

    /**
     * Adicionamos os itens na grid
     */
    oRetorno.aItens.each(function(oItem, iIndice) {

      if (oItem.iAutorizacao == undefined) {
        oItem.iAutorizacao = "";
        oItem.sDescricaoMaterial = decodeURI(oItem.sDescricaoMaterial);
      }
      var aLinha = [oItem.iCodigo,
        oItem.iAutorizacao,
        oItem.iQuantidade,
        oItem.nValorUnitario,
        oItem.sUnidadeDeMedida.urlDecode(),
        oItem.sDescricaoMaterial.urlDecode().substr(0, 50),
        oItem.sFornecedor.urlDecode().substr(0, 35),
        oItem.lReservado
      ]
      //oItem.sObservacao.urlDecode().substr(0, 35)];
      oGridItens.addRow(aLinha);
    });

    oGridItens.renderRows();

    js_setBackgroundReservado();

    /**
     * Adicionamos o texto completo quando o usu�rio passar o mouse por cima da linha
     */
    oRetorno.aItens.each(function(oItem, iIndice) {

      oGridItens.setHint(iIndice, 5, oItem.sDescricaoMaterial.urlDecode());
      oGridItens.setHint(iIndice, 6, oItem.sFornecedor.urlDecode());

      /*if (oItem.sObservacao.trim() != "") {
        oGridItens.setHint(iIndice, 7, oItem.sObservacao.urlDecode());
      }*/

    });

    function js_setBackgroundReservado() {
      let aItens = document.getElementById('oGridItensbody').childNodes[0].children;

      for (let index = 0; index < aItens.length; index++) {
        if (aItens[index].cells[7].innerText.trim() == 't') {
          aItens[index].classList.add('bensMeEpp');
        }
      }
    }
  }
</script>