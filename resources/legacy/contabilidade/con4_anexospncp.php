<?php

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"], $result);
$oGet          = db_utils::postMemory($_GET);

?>
<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <?php
    db_app::load("estilos.css, grid.style.css");
    db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js");
    ?>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table style="display: none">
    <tr>
    <td nowrap="nowrap">
        <?php
            $sequencial = isset($oGet->ac16_sequencial) ? $oGet->ac16_sequencial : '';
            db_input("ac16_sequencial", 30, 0, true, "text", $sequencial, "disabled style='background-color:#DEB887;'");
        ?>
</td>
    </tr>
</table>

<div class="container" style="width:650px;">

    <fieldset style="margin-top:15px;">
        <legend>Documentos Anexados</legend>
        <div id="ctnDbGridDocumentos"></div>
    </fieldset>

    <input type="button" id="btnDownloadAnexos" value="Download" onClick="js_downloadSelecionados();" />

</div>

</body>

</html>
<script type="text/javascript">
let acordo = document.getElementById('ac16_sequencial').value;
var iAcordo = acordo;

var sUrlRpc = "con4_contratos.RPC.php";

oGridDocumento     = new DBGrid('gridDocumento');
oGridDocumento.nameInstance = "oGridDocumento";
oGridDocumento.setCheckbox(0);
oGridDocumento.setCellWidth(["15%", "80%", "30%"]);
oGridDocumento.setCellAlign(new Array("center", "center", "center"));
oGridDocumento.setHeader(new Array("Seq", "Descrição", "Tipo"));
oGridDocumento.allowSelectColumns(true);
oGridDocumento.show($('ctnDbGridDocumentos'));
js_getDocumento();
function js_getDocumento() {

if (iAcordo == null) {
  return false;
}

var oParam       = new Object();
oParam.exec      = 'getDocumento';
oParam.acordo   = iAcordo;
var oAjax        = new Ajax.Request(
                            sUrlRpc,
                           { parameters: 'json='+Object.toJSON(oParam),
                             asynchronous:false,
                             method: 'post',
                             onComplete : js_retornoGetDocumento
                           });
}

function js_retornoGetDocumento(oAjax) {

var oRetorno = eval('('+oAjax.responseText+")");
oGridDocumento.clearAll(true);

if (oRetorno.dados.length == 0) {
  return false;
}

oRetorno.dados.each(function (oDocumento, iSeq) {

  var aLinha = new Array();
  aLinha[0]  = oDocumento.iCodigo;
  aLinha[1]  = oDocumento.sNomeArquivo
  aLinha[2]  = decodeURIComponent(oDocumento.sDescricao);
  oGridDocumento.addRow(aLinha);
});

oGridDocumento.renderRows();

}

function js_downloadSelecionados(){
  const documentosSelecionados = oGridDocumento.getSelection("object")
  let acordo = document.getElementById('ac16_sequencial').value;
var iSelecionados = documentosSelecionados.length;
var iCodigoProcesso = acordo;
var aDocumentos = [];
var aTipo = [];

if (iSelecionados== 0) {
  alert('Selecione pelo menos um arquivo para Excluir')
  return false
}

if (!confirm('Confirma o download do Documento?')) {
    return false;
  }

if (empty(iCodigoProcesso)) {

  alert('Acordo não informado.');
  return false;
}

for (var iIndice = 0; iIndice < iSelecionados; iIndice++) {

var iDocumento = documentosSelecionados[iIndice].aCells[0].getValue();
aDocumentos.push(iDocumento);

var iTipo = documentosSelecionados[iIndice].aCells[3].getValue();
aTipo.push(iTipo);

}

js_divCarregando('Aguarde... Fazendo dowload do documentos!', 'msgbox');

var oParametros = new Object();

oParametros.exec = 'DowloadDocumentosSelecionados';
oParametros.iCodigoProcesso = iCodigoProcesso;
oParametros.aDocumentos = aDocumentos;
oParametros.aTipoDocumentos = aTipo;

var oAjax = new Ajax.Request(
  sUrlRpc, {
    parameters: 'json=' + Object.toJSON(oParametros),
    method: 'post',
    asynchronous: false,

    /**
     *
     * Retorno do RPC
     */
    onComplete: function(oAjax) {

      js_removeObj("msgbox");
      var oRetorno = eval('(' + oAjax.responseText + ")");
      var sMensagem = oRetorno.message;
      var status = oRetorno.status;
      for (var cont = 0; cont <= oRetorno.length; cont++) {
        document.write(oRetorno[cont] + "<br>");
      }
      if (oRetorno.status == 2) {
        alert(sMensagem);
        return false;
      }
      for (var cont = 0; cont < oRetorno.contador; cont++) {
        window.open("db_download.php?arquivo="+oRetorno.nomearquivo[cont]);
      }

      alert("Download do(s) Anexo(s) efetuado com Sucesso!");


    }
  });

}



</script>
