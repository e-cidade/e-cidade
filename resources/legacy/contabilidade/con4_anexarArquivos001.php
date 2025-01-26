<?php

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_libdicionario.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_acordodocumento_classe.php");
require_once("classes/db_acordo_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/renderComponents/index.php");

$oGet              = db_utils::postMemory($_GET);
$clAcordoDocumento = new cl_acordodocumento;
$clAcordo          = new cl_acordo;
$db_opcao          = 22;
$db_botao          = false;
$clRotulo          = new rotulocampo;
$cltipoanexo       = new cl_tipoanexo;
$cllicanexopncp    = new cl_licanexopncp;

$clAcordoDocumento->rotulo->label();
$clRotulo->label('ac40_acordo');
$clRotulo->label('ac40_descricao');
$clRotulo->label('ac16_resumoobjeto');
$clRotulo->label('ac16_sequencial');
// $clRotulo->label("l20_codigo");
// $clRotulo->label("l20_objeto");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js");
      db_app::load("widgets/dbtextField.widget.js, dbViewCadEndereco.classe.js");
      db_app::load("dbmessageBoard.widget.js, dbautocomplete.widget.js,dbcomboBox.widget.js, datagrid.widget.js");
      db_app::load("estilos.css,grid.style.css");
    ?>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

    <div style="margin-top: 30px;"></div>
    <form name="form1" id='form1' method="post" action="" enctype="multipart/form-data">
      <center>
        <div style="width: 750px;">
          <fieldset>
            <legend><b>Adicionar Documento:</b></legend>
            <table>
              <tr>
                <td title="<?php echo $Tac16_sequencial; ?>">
                  <?php db_ancora($Lac16_sequencial, 'js_pesquisaContrato(true);', 1)?>
                </td>
                <td nowrap="nowrap">
                  <?php
                    db_input('iCodigoContrato', 9, @$Iac16_sequencial, true, 'text', 1, " onchange='js_pesquisaContrato(false);' ");
                    db_input('sDescricaoContrato', 42, @$Iac16_resumoobjeto, true, 'text', 3, "");
                  ?>
                </td>
              </tr>
              <tr>
                <td valign="top">
                  <b>Documento: </b>
                </td>
                <td valign='top' style="height: 25px;">
                  <?php
                    db_input("uploadfile", 30, 0, true, "file", 1, "disabled style='background-color:#DEB887;'");
                    db_input("namefile", 30, 0, true, "hidden", 1);
                  ?>
                </td>
              </tr>
              <tr>
            <td nowrap title="<?= @$Tl213_sequencial ?>">
              <b>
                <?
                db_ancora("Tipo de Anexo :", "js_pesquisal20_codtipocom(true);", 3);
                ?>
              </b>
            </td>
            <td>
              <?


              $tipo = array();
              $tipo[0] = "Selecione";
              $result_tipo = $cltipoanexo->sql_record($cltipoanexo->sql_query(null, "*", "l213_sequencial", "l213_sequencial > 11"));


              for ($iIndiceTipo = 0; $iIndiceTipo < $cltipoanexo->numrows; $iIndiceTipo++) {

                $oTipo = db_utils::fieldsMemory($result_tipo, $iIndiceTipo);

                $tipo[$oTipo->l213_descricao] = $oTipo->l213_descricao;
              }

              if ($cltipoanexo->numrows == 0) {
                db_msgbox("Nenhuma Tipo de anexo cadastrado!!");
                $result_tipo = "";
                $db_opcao = 3;
                $db_botao = false;
                db_input("l213_sequencial", 10, "", true, "text");
                db_input("l213_sequencial", 40, "", true, "text");
              } else {
                $db_opcao = 1;
                db_select("l213_sequencial", $tipo, true, $db_opcao, "");
              }
              ?>
            </td>
          </tr>
            </table>
          </fieldset>
        </div>

      <div style="width: 100%; display: flex; justify-content: center;">

        <?php $component->render('buttons/solid', [
          'designButton' => 'success',
          'id' => 'btnSalvar',
          'type' => 'button',
          'message' => 'Salvar',
          'disabled' => true
        ]); ?>

      </div>

        <div style="width: 750px;">
          <fieldset>
            <legend><b>Documentos Cadastrados</b></legend>
            <div id='ctnDbGridDocumentos'></div>
          </fieldset>

        <div style="width: 100%; display: flex; justify-content: center;">

          <?php $component->render('buttons/solid', [
            'designButton' => 'success',
            'type' => 'button',
            'onclick' => "js_enviarDocumentoPNCP()",
            'message' => 'Envia documento para o PNCP',
            'size' => 'sm'
          ]); ?>

          <?php $component->render('buttons/solid', [
            'designButton' => 'danger',
            'type' => 'button',
            'onclick' => "openModal('justificativaModal')",
            'message' => 'Excluir documento no PNCP',
            'size' => 'sm'
          ]); ?>

          <?php $component->render('buttons/solid', [
            'designButton' => 'danger',
            'type' => 'button',
            'onclick' => "js_excluirSelecionados()",
            'message' => 'Excluir Selecionados',
            'size' => 'sm'
          ]); ?>

          <?php $component->render('buttons/solid', [
            'designButton' => 'secondary',
            'type' => 'button',
            'onclick' => "js_downloadSelecionados()",
            'message' => 'Download',
            'size' => 'sm'
          ]); ?>

        </div>

        </div>
      </center>
    </form>
    <?php
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>

  <?php $component->render('modais/simpleModal/startModal', [
    'title' => 'Justificativa para o PNCP',
    'id' => 'justificativaModal',
    'size' => 'lg'
  ], true); ?>

    <?php db_textarea('justificativapncp', 10, 48, false, true, 'text', $db_opcao, "", "", "justificativapncp", "255"); ?>

    <div style="width: 100%; display: flex; justify-content: center;">
      <?php $component->render('buttons/solid', [
        'designButton' => 'success',
        'onclick' => 'js_excluirDocumentoPNCP();',
        'message' => 'Salvar justificativa PNCP',
        'size' => 'md'
      ]); ?>
    </div>

  <?php $component->render('modais/simpleModal/endModal', [], true); ?>

  </body>
  <div id='teste' style='display:none'></div>
</html>

<style>
  #justificativapncp {
    width: 100%;
    margin-bottom: 7px;
    font-size: 1rem;
  }
</style>

<script type="text/javascript">
  loadComponents([
    'buttonsSolid',
    'simpleModal'
  ]);

var iAcordo = null;

/**
 * Fun��es para busca de departamentos
 */
function js_pesquisaContrato(lMostra) {

  var sFuncao = 'func_acordoinstit.php?funcao_js=parent.js_mostraContrato|ac16_sequencial|ac16_resumoobjeto';

  if (lMostra == false) {

    var iContrato = $F('iCodigoContrato');
    sFuncao = 'func_acordoinstit.php?pesquisa_chave='+iContrato+'&funcao_js=parent.js_completaContrato'+
              '&descricao=true';
  }

  js_OpenJanelaIframe('', 'db_iframe_contrato', sFuncao,'Pesquisar Contrato', lMostra, '25');
}

function js_completaContrato(iContrato, sDescricao, lErro) {

  $('sDescricaoContrato').value = sDescricao;

  if (lErro) {

    $('iCodigoContrato').focus();
    $('iCodigoContrato').value = '';
    iAcordo = null;
    js_controlaCampos(true);
    oGridDocumento.clearAll(true);
    return false;
  }

  js_controlaCampos(false);
  iAcordo = $F("iCodigoContrato");
  js_getDocumento();
}

function js_mostraContrato (iCodigo, sDescricao) {

  $('iCodigoContrato').value = iCodigo;
  $('sDescricaoContrato').value = sDescricao;
  db_iframe_contrato.hide();
  js_controlaCampos(false);
  iAcordo = iCodigo;
  js_getDocumento();
}

function js_controlaCampos(lBloqueia) {

  if (lBloqueia) {

    $("uploadfile").setAttribute("disabled", true);
    $("uploadfile").style.backgroundColor = "#DEB887";

    $("l213_sequencial").setAttribute("disabled", true);
    $("l213_sequencial").style.backgroundColor = "#DEB887";

    $("btnSalvar").setAttribute("disabled", true);

  } else {

    $("uploadfile").removeAttribute("disabled");
    $("uploadfile").style.backgroundColor = "#FFFFFF";

    $("l213_sequencial").style.backgroundColor = "#FFFFFF";
    $("l213_sequencial").removeAttribute("disabled");

    $("btnSalvar").removeAttribute("disabled");
  }
}

var sUrlRpc = "con4_contratos.RPC.php";

oGridDocumento     = new DBGrid('gridDocumento');
oGridDocumento.nameInstance = "oGridDocumento";
oGridDocumento.setCheckbox(0);
oGridDocumento.setCellWidth(["30%", "30%", "30%", "50%", "30%", "30%"]);
oGridDocumento.setCellAlign(new Array("center", "center", "center", "center", "center"));
oGridDocumento.setHeader(new Array("C�digo","Acordo","Tipo", "A��o"));
oGridDocumento.allowSelectColumns(true);
oGridDocumento.show($('ctnDbGridDocumentos'));

/**
 * Cria um listener para subir a imagem, e criar um preview da mesma
 */
$("uploadfile").observe('change', function() {

  startLoading();
  var iFrame = document.createElement("iframe");
  iFrame.src = 'func_uploadfiledocumento.php?clone=form1';
  iFrame.id  = 'uploadIframe';
  $('teste').appendChild(iFrame);

});

function startLoading() {
  js_divCarregando('Aguarde... Carregando Documento','msgbox');
}

function endLoading() {
  js_removeObj('msgbox');
}

function js_salvarDocumento() {

  if ($F('namefile') == '') {

     alert('Escolha um Documento!');
     return false;
  }

  if ($F('l213_sequencial') == 0) {

    alert('Selecione o tipo de Anexo!');
    return false;
  }

  var oParam       = new Object();
  oParam.exec      = 'adicionarDocumento';
  oParam.acordo    = $F('iCodigoContrato');
  oParam.descricao = encodeURIComponent(($F('l213_sequencial').replace(/\\/g,  "<contrabarra>")));
  oParam.arquivo   = $F('namefile');
  js_divCarregando('Aguarde... Salvando Documento','msgbox');
  var oAjax        = new Ajax.Request(
                              sUrlRpc,
                             { parameters: 'json='+Object.toJSON(oParam),
                               method: 'post',
                               asynchronous:false,
                               onComplete : js_retornoSalvarFoto
                             });
}

function js_retornoSalvarFoto(oAjax) {

js_removeObj("msgbox");
var oRetorno = eval('('+oAjax.responseText+")");
if (oRetorno.status == 1) {

   $('uploadfile').value     = '';
   $("l213_sequencial").value = "";
   js_getDocumento();
} else {
  alert(oRetorno.message.urlDecode());
}
}

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
    aLinha[1]  = oDocumento.iAcordo;
    aLinha[2]  = decodeURIComponent(oDocumento.sDescricao);
    aLinha[3]  = '<input type="button" value="Alterar" onclick="js_alterarDocumento(' +oDocumento.iCodigo+ ');" >  ';
    aLinha[3]  += '<input type="button" value="Excluir" onclick="js_excluirDocumento(' +oDocumento.iCodigo+ ')" />  ';



    oGridDocumento.addRow(aLinha);
  });

  oGridDocumento.renderRows();

}
$('btnSalvar').observe("click",js_salvarDocumento);
js_getDocumento();

function js_excluirDocumento(iCodigoDocumento) {

  if (!confirm('Confirma a Exclus�o do Documento?')) {
    return false;
  }
  var oParam             = new Object();
  oParam.exec            = 'excluirDocumento';
  oParam.acordo          = iAcordo;
  oParam.codigoDocumento = iCodigoDocumento;
  js_divCarregando('Aguarde... excluindo documento','msgbox');
  var oAjax        = new Ajax.Request(
                              sUrlRpc,
                             { asynchronous:false,
                               parameters: 'json='+Object.toJSON(oParam),
                               method: 'post',
                               onComplete : js_retornoExcluiDocumento
                             });

}

function js_retornoExcluiDocumento(oAjax) {

  js_removeObj("msgbox");
  var oRetorno = eval('('+oAjax.responseText+")");
  if (oRetorno.status == 2) {

     alert("N�o foi possivel excluir o documento:\n "+ oRetorno.message);
  }

  js_getDocumento();

}

function js_documentoDownload(iCodigoDocumento) {

  if (!confirm('Deseja realizar o Download do Documento?')) {
    return false;
  }
  var oParam              = new Object();
  oParam.exec             = 'downloadDocumento';
  oParam.acordo           = iAcordo;
  oParam.iCodigoDocumento = iCodigoDocumento;
  js_divCarregando('Aguarde... realizando Download do documento','msgbox');
  var oAjax        = new Ajax.Request(
                              sUrlRpc,
                             { asynchronous:false,
                               parameters: 'json='+Object.toJSON(oParam),
                               method: 'post',
                               onComplete : js_downloadDocumento
                             });
}

function js_downloadDocumento(oAjax) {

  js_removeObj("msgbox");
  var oRetorno = eval('('+oAjax.responseText+")");
  if (oRetorno.status == 2) {
     alert("N�o foi possivel carregar o documento:\n "+ oRetorno.message);
  }
  window.open("db_download.php?arquivo="+oRetorno.nomearquivo);
}


function js_enviarDocumentoPNCP(){

    const documentosSelecionados = oGridDocumento.getSelection("object")
    var iSelecionados = documentosSelecionados.length;
    var iCodigoProcesso = $('iCodigoContrato').value;
    var aDocumentos = [];
    var aTipo = [];

    if (iSelecionados== 0) {
      alert('Selecione pelo menos arquivo para Enviar')
      return false
    }
    if (!confirm('Confirma o Envio do Documento?')) {
    return false;
  }

    if (empty(iCodigoProcesso)) {

      alert('Acordo n�o informado.');
      return false;
    }

    for (var iIndice = 0; iIndice < iSelecionados; iIndice++) {

    var iDocumento = documentosSelecionados[iIndice].aCells[0].getValue();
    aDocumentos.push(iDocumento);

    var iTipo = documentosSelecionados[iIndice].aCells[3].getValue();
    aTipo.push(iTipo);

    }

    js_divCarregando('Aguarde... Enviando documentos!', 'msgbox');

    var oParametros = new Object();

    oParametros.exec = 'EnviarDocumentoPNCP';
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
            alert(sMensagem.urlDecode());
            return false;
          }
          alert("Anexo(s) Enviado(s) com Sucesso!");


        }
      });

}

function js_excluirDocumentoPNCP(){

const documentosSelecionados = oGridDocumento.getSelection("object")
var iSelecionados = documentosSelecionados.length;
var iCodigoProcesso = $('iCodigoContrato').value;
var aDocumentos = [];
var aTipo = [];
let justificativa = document.getElementById('justificativapncp').value.trim();

if (iSelecionados== 0) {
  alert('Selecione pelo menos um arquivo para Excluir')
  return false
}

if (justificativa === '') {
  alert('A justificativa n�o pode estar vazia');
  return false;
}

if (!confirm('Confirma a Exclus�o do Documento?')) {
    return false;
  }

if (empty(iCodigoProcesso)) {

  alert('Acordo n�o informado.');
  return false;
}

for (var iIndice = 0; iIndice < iSelecionados; iIndice++) {

var iDocumento = documentosSelecionados[iIndice].aCells[0].getValue();
aDocumentos.push(iDocumento);

var iTipo = documentosSelecionados[iIndice].aCells[3].getValue();
aTipo.push(iTipo);

}

js_divCarregando('Aguarde... Excluindo documentos!', 'msgbox');

var oParametros = new Object();

oParametros.exec = 'ExcluirDocumentoPNCP';
oParametros.iCodigoProcesso = iCodigoProcesso;
oParametros.aDocumentos = aDocumentos;
oParametros.aTipoDocumentos = aTipo;
oParametros.justificativa = justificativa;

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

      closeModal('justificativaModal');
      clearModaFieldsRenderComponents();
      js_removeObj("msgbox");
      var oRetorno = eval('(' + oAjax.responseText + ")");
      var sMensagem = oRetorno.message;
      var status = oRetorno.status;
      for (var cont = 0; cont <= oRetorno.length; cont++) {
        document.write(oRetorno[cont] + "<br>");
      }
      if (oRetorno.status == 2) {
        alert(sMensagem.urlDecode());
        return false;
      }
      alert("Documento(s) Excluido(s) com Sucesso do PNCP!");


    }
  });

}

function js_excluirSelecionados(){
  const documentosSelecionados = oGridDocumento.getSelection("object")
var iSelecionados = documentosSelecionados.length;
var iCodigoProcesso = $('iCodigoContrato').value;
var aDocumentos = [];
var aTipo = [];

if (iSelecionados== 0) {
  alert('Selecione pelo menos um arquivo para Excluir')
  return false
}

if (!confirm('Confirma a Exclus�o do Documento?')) {
    return false;
  }

if (empty(iCodigoProcesso)) {

  alert('Acordo n�o informado.');
  return false;
}

for (var iIndice = 0; iIndice < iSelecionados; iIndice++) {

var iDocumento = documentosSelecionados[iIndice].aCells[0].getValue();
aDocumentos.push(iDocumento);

var iTipo = documentosSelecionados[iIndice].aCells[3].getValue();
aTipo.push(iTipo);

}

js_divCarregando('Aguarde... Excluindo documentos!', 'msgbox');

var oParametros = new Object();

oParametros.exec = 'ExcluirDocumentosSelecionados';
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

      alert("Anexo(s) Excluido(s) com Sucesso!");
      oGridDocumento.renderRows();


    }
  });

}
function js_downloadSelecionados(){
  const documentosSelecionados = oGridDocumento.getSelection("object")
var iSelecionados = documentosSelecionados.length;
var iCodigoProcesso = $('iCodigoContrato').value;
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

  alert('Acordo n�o informado.');
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

js_pesquisaContrato(true);

</script>
