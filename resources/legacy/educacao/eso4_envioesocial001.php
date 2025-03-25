<?php

/**
 *
 * @author I
 * @revision $Author: dbrobson $
 * @version $Revision: 1.10 $
 */
require "libs/db_stdlib.php";
require "libs/db_utils.php";
require "libs/db_conecta.php";
include "libs/db_sessoes.php";
include "libs/db_usuariosonline.php";
include "dbforms/db_funcoes.php";

$clrotulo = new rotulocampo;
$clrotulo->label("o124_descricao");
$clrotulo->label("o124_sequencial");
$clrotulo->label("o15_descr");
$clrotulo->label("o15_codigo");
// try {
//   $anofolha = DBPessoal::getAnoFolha();
//   $mesfolha = DBPessoal::getMesFolha();
// } catch (Exception $error) {
//   $anofolha = db_getsession("DB_anousu");
//   $mesfolha = date("m", db_getsession("DB_datausu"));
// }
?>
<html>

<head>
  <title>DBSeller Informática Ltda - Página Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">

  <style>
  .tabela-campos-envio>tr>td {
    margin-top: 30px !important;
    margin-bottom: 30px !important;
  }
  </style>
</head>

<body bgcolor="#cccccc" style="margin-top: 25px;">
  <center>


    <form name="form1" method="post" action="">
      <div style="display: table">
        <fieldset>
          <legend>
            <b>Exportação - eSocial</b>
          </legend>
          <table class='form-container'>
            <tr>
              <td colspan="2">
                <fieldset>
                  <legend>
                    <b>Dados Envio</b>
                  </legend>
                  <table class="tabela-campos-envio">
                    <tr>
                      <td align="left">
                        <label>Competência:</label>
                        <?php
                        db_input('anofolha', 4, 1, true, 'text', 2, "class='field-size1' onkeyup='moveFocusToMesfolha(event)'", "", "", "", 4);
                        db_input('mesfolha', 2, 1, true, 'text', 2, "class='field-size1' onblur='padZero(this)'", "", "", "", 2);
                        ?>
                      </td>
                      <td align="left" style="display: none;">
                        <label for="cboEmpregador">Empregador:</label>
                        <select name="empregador" id="cboEmpregador" style="width: 250px">
                          <option value="">selecione</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td align="left" style="">
                        <label for="tpAmb">Ambiente:</label>
                        <select name="tpAmb" id="tpAmb" style="width: 250px">
                          <option value="1">Produção</option>
                          <option value="2">Produção restrita - dados reais</option>
                          <option value="3">Produção restrita - dados fictícios</option>
                        </select>
                      </td>
                      <td align="left">
                        <label for="modo">Tipo:</label>
                        <select name="modo" id="modo" style="width: 250px;">
                          <option value="INC">Inclusão</option>
                          <option value="ALT">Alteração</option>
                          <option value="EXC">Exclusão</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td align="left" id="indapuracao_col" style="display: none; ">
                        <label for="indapuracao">Apuração:</label>
                        <select name="indapuracao" id="indapuracao" style="width: 250px;">
                          <option value="1">Mensal</option>
                          <option value="2">Anual</option>
                        </select>
                      </td>
                      <td id="dtalteracao" align="left" style="width: 250px; display: none; ">
                        <label for="dtalteracao">Data Alteração:</label>
                        <?php db_inputdata('dt_alteracao', '', '', '', true, 'text', 1, ""); ?>
                      </td>
                    </tr>
                    <tr>
                      <td align="left" id="tppgto_col" style="display: none; ">
                        <label for="tppgto">Tipo de Pagamento:</label>
                        <select name="tppgto" id="tppgto" style="width: 450px;">
                          <option value="1">Pagamento de remuneração, conforme apurado em ideDmDev do S-1200</option>
                          <option value="2">Pagamento de verbas rescisórias conforme apurado em ideDmDev do S-2299
                          </option>
                          <option value="3">Pagamento de verbas rescisórias conforme apurado em ideDmDev do S-2399
                          </option>
                          <option value="4">Pagamento de remuneração conforme apurado em ideDmDev do S-1202</option>
                          <option value="5">Pagamento de benefícios previdenciários, conforme apurado em ideDmDev do
                            S-1207</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td id="tipo_col" style="display: none; ">
                        <label>Tipo de evento:</label>
                        <select name="tpevento" id="tpevento" onchange="js_tpevento()" style="width: 250px;">
                          <option value="1">RH</option>
                          <option value="2">Contabilidade</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td align="left" id="transDCTFWeb_col" style="display: none; ">
                        <label>transDCTFWeb:</label>
                        <select name="transDCTFWeb" id="transDCTFWeb" style="width: 250px;">
                          <option value="S">Sim</option>
                          <option value="N">Não</option>
                        </select>
                      </td>
                      <td align="left" id="evtpgtos_col" style="display: none; ">
                        <label>evtpgtos:</label>
                        <select name="evtpgtos" id="evtpgtos" style="width: 250px;">
                          <option value="S">Sim</option>
                          <option value="N">Não</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td align="left" id="dtpgto" style="display: none; " style="width: 250px;">
                        <label>Data de Pagamento:</label>
                        <?php
                        $Sdt_pgto = "Data Pagamento";
                        db_inputdata('data_pgto', null, null, null, true, 'text', 1);
                        ?>
                      </td>
                    </tr>
                  </table>

                </fieldset>
              </td>
            </tr>
            <tr>
              <td>
                <fieldset>
                  <legend>
                    <b>Fase de Geração</b>
                  </legend>
                  <table>
                    <tr>
                      <td>
                        <input type="radio" value="EvtIniciaisTabelas" name="fase" onclick="checkFase(this.value);" />
                        <label for="Fase1">1ª Fase - Eventos Iniciais e de Tabelas</label><br>
                      </td>
                      <td>
                        <input type="radio" value="EvtNaoPeriodicos" name="fase" onclick="checkFase(this.value);" />
                        <label for="Fase2">2ª Fase - Eventos não Periódicos</label><br>
                      </td>
                      <td>
                        <input type="radio" value="EvtPeriodicos" name="fase" onclick="checkFase(this.value);" />
                        <label for="Fase3">3ª Fase - Eventos Periódicos</label><br>
                      </td>
                      <td>
                        <input type="radio" value="" name="fase" onclick="checkFase(this.value);" />
                        <label for="Fase4">Limpeza Ambiente de Teste</label><br>
                      </td>
                    </tr>
                  </table>
                </fieldset>
              </td>
            </tr>
            <?php
            include('forms/db_frmesocialevtinitabelas.php');
            include('forms/db_frmesocialevtnaoperiodicos.php');
            include('forms/db_frmesocialevtperiodicos.php');
            ?>
          </table>
        </fieldset>
        <div style="text-align: center;">
          <input type="button" id="btnMarcarTodos" value="Marcar Todos" onclick="js_marcaTodos();" />
          <input type="button" id="btnLimparTodos" value="Limpar Todos" onclick="js_limpa();" />
          <input type="button" id="btnEnviar" value="Enviar para eSocial" onclick="js_processar();" />
          <input type="button" id="btnConsultar" value="Consultar Envio" onclick="js_consultar();" />
        </div>
      </div>
    </form>

  </center>
</body>

</html>
<?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
<script type="text/javascript">
var arrEvts = ['EvtIniciaisTabelas', 'EvtNaoPeriodicos', 'EvtPeriodicos'];
var empregador = Object();
(function() {

  new AjaxRequest('eso4_esocialapi.RPC.php', {
    exec: 'getEmpregadores'
  }, function(retorno, lErro) {

    if (lErro) {
      alert(retorno.sMessage);
      return false;
    }
    empregador = retorno.empregador;

    $('cboEmpregador').length = 0;
    $('cboEmpregador').add(new Option(empregador.nome, empregador.cgm));
  }).setMessage('Buscando servidores.').execute();
})();


function checkFase(fase) {

  arrEvts.each(function(evt) {
    if (fase == evt) {
      $(evt).show();
    } else {
      $(evt).hide();
    }
  });
  js_limpa();
}

function padZero(input) {
  let value = input.value.trim();
  if (value.length === 1 && value !== '0') {
    input.value = '0' + value;
  }
}

async function js_processar() {

  if ($F('anofolha').length < 4 || parseInt($("mesfolha").value) < 1 || parseInt($("mesfolha").value) > 12) {
    alert("Campo 'Competência' deve ser preenchido.");
    return false;
  }

  if (document.getElementById('S1210').checked) {
    const tpeventoValue = document.getElementById('tpevento').value;
    if (tpeventoValue === '1') {
      if ($F('data_pgto').length <= 0) {
        alert("Campo 'Data de Pagamento' deve ser preenchido.");
        return false;
      }
    }
  }

  const mes = document.getElementById("mesfolha").value;
  const ano = document.getElementById("anofolha").value;

  const message = '<span style="color: red; font-weight: bold; font-size: 16px;">ATENÇÃO!</span><br><br>' +
    '<span style="font-size: 14px;">Deseja confirmar o envio do mês <span style="font-weight: bold;">' +
    mes + '/' + ano +
    '</span></span>?';

  const result = await showCustomConfirm(message);
  if (!result) {
    return false;
  }


  if ($("cboEmpregador").value == '') {

    alert("Selecione um empregador");
    return false;
  }

  if ($("tpAmb").value == '') {

    alert("Selecione um Ambiente de envio");
    return false;
  }

  let sSelectedFase = getRadioOption();
  let aArquivosSelecionados = new Array();
  let aArquivos = $$(`#${sSelectedFase} :input[type='checkbox']`);
  aArquivos.each(function(oElemento, iIndice) {

    if (oElemento.checked) {
      aArquivosSelecionados.push(oElemento.value.toUpperCase());
    }
  });
  if (aArquivosSelecionados.length == 0) {

    alert("Nenhum arquivo foi selecionado para ser gerado");
    return false;
  }

  js_divCarregando('Aguarde, processando arquivos', 'msgBox');
  var oParam = new Object();
  oParam.exec = "transmitir";
  oParam.arquivos = aArquivosSelecionados;
  oParam
    .empregador = $("cboEmpregador").value;
  oParam.iAnoValidade = $("anofolha").value;
  oParam.iMesValidade = $(
    "mesfolha").value;
  oParam.tpAmb = $("tpAmb").value;
  oParam.modo = $("modo").value;
  oParam.dtalteracao = $(
    "dt_alteracao").value;
  oParam.indapuracao = $("indapuracao").value;
  oParam.tppgto = $("tppgto").value;
  oParam
    .tpevento = $("tpevento").value;
  oParam.transDCTFWeb = $("transDCTFWeb").value;
  oParam.evtpgtos = $("evtpgtos")
    .value;
  oParam.dtpgto = $("data_pgto").value;
  var oAjax = new Ajax.Request("eso4_esocialapi.RPC.php", {
    method: 'post',
    parameters: 'json=' + Object.toJSON(oParam),
    onComplete: js_retornoProcessamento
  });

}

function js_retornoProcessamento(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("(" + oAjax.responseText + ")");
  if (oRetorno.iStatus == 1) {
    alert(oRetorno.sMessage.urlDecode());
  } else {
    alert("Houve um erro no processamento! " + oRetorno.sMessage.urlDecode());
    return false;
  }
}

function js_marcaTodos() {

  var aCheckboxes = $$('input[type=checkbox]');
  aCheckboxes.each(function(oCheckbox) {
    oCheckbox.checked = true;
  });
}

function js_limpa() {

  var aCheckboxes = $$('input[type=checkbox]');
  aCheckboxes.each(function(oCheckbox) {
    oCheckbox.checked = false;
  });
}

function getRadioOption() {

  let aRadioOption = $$('input[type=radio]');
  let iRadioOption = 'EvtIniciaisTabelas';
  aRadioOption.each(function(oRadioOption) {
    if (oRadioOption.checked) {
      iRadioOption = oRadioOption.value;
    }
  });
  return iRadioOption;
}

function js_consultar() {

  js_OpenJanelaIframe('top.corpo', 'iframe_consulta_envio', 'func_consultaenvioesocial.php', 'Pesquisa', true);
}

function js_alt() {
  const indapuracaoCol = document.getElementById('indapuracao_col');
  const tppgtoCol = document.getElementById('tppgto_col');
  const tipoCol = document.getElementById('tipo_col');
  const transDCTFWeb = document.getElementById('transDCTFWeb_col');
  const evtpgtos = document.getElementById('evtpgtos_col');
  const dtpgto = document.getElementById('dtpgto');

  // Default to hiding all columns
  indapuracaoCol.style.display = 'none';
  tppgtoCol.style.display = 'none';
  tipoCol.style.display = 'none';
  transDCTFWeb.style.display = 'none';
  evtpgtos.style.display = 'none';
  dtpgto.style.display = 'none';

  // Update display based on checked checkboxes
  if (document.getElementById('S1200').checked) {
    indapuracaoCol.style.display = 'inline';
    tipoCol.style.display = 'inline';
  }
  if (document.getElementById('S1202').checked || document.getElementById('S1207').checked) {
    indapuracaoCol.style.display = 'inline';
    tipoCol.style.display = 'inline';
  }
  if (document.getElementById('S1210').checked) {
    indapuracaoCol.style.display = 'inline';
    tppgtoCol.style.display = 'inline';
    tipoCol.style.display = 'inline';
    dtpgto.style.display = 'inline';
  }
  if (document.getElementById('S1299').checked) {
    indapuracaoCol.style.display = 'inline';
    transDCTFWeb.style.display = 'inline';
    evtpgtos.style.display = 'inline';
  }
}

function js_dataalt() {
  if (document.getElementById('S1210').checked) {
    document.getElementById('dtalteracao').style.display = 'inline';
  } else {
    document.getElementById('dtalteracao').style.display = 'none';
  }
}

function js_S3000(e) {
  var button = document.getElementById("btnEnviar");
  if (e.checked) {
    document.getElementById('indapuracao_col').style.display = 'inline';
    console.log("Checkbox estão marcados");
    js_OpenJanelaIframe('top.corpo', 'iframe_envio_s3000', 'func_envioesocialS3000.php', 'Pesquisa', true);
    button.disabled = true;
  } else {
    document.getElementById('indapuracao_col').style.display = 'none';
    console.log("Checkbox não estão marcados");
    button.disabled = false;
  }

}

function js_tpevento() {
  const tpeventoValue = document.getElementById('tpevento').value;
  if (tpeventoValue === '1') {
    document.getElementById('dtpgto').style.display = 'inline';
  } else {
    document.getElementById('dtpgto').style.display = 'none';
  }
}

function moveFocusToMesfolha(event) {
  const anofolha = event.target;
  if (anofolha.value.length === 4) {
    document.getElementById('mesfolha').focus();
  }
}

checkFase('');

function showCustomConfirm(message) {
  return new Promise((resolve) => {
    // Create the confirm dialog elements
    const confirmOverlay = document.createElement('div');
    confirmOverlay.style.position = 'fixed';
    confirmOverlay.style.top = '0';
    confirmOverlay.style.left = '0';
    confirmOverlay.style.width = '100%';
    confirmOverlay.style.height = '100%';
    confirmOverlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
    confirmOverlay.style.display = 'flex';
    confirmOverlay.style.justifyContent = 'center';
    confirmOverlay.style.alignItems = 'center';
    confirmOverlay.style.zIndex = '1000';

    const confirmBox = document.createElement('div');
    confirmBox.style.backgroundColor = 'white';
    confirmBox.style.padding = '20px';
    confirmBox.style.borderRadius = '5px';
    confirmBox.style.boxShadow = '0 0 10px rgba(0, 0, 0, 0.5)';
    confirmBox.style.textAlign = 'center';

    const confirmMessage = document.createElement('p');
    confirmMessage.innerHTML = message;

    const confirmYesButton = document.createElement('button');
    confirmYesButton.textContent = 'Sim';
    confirmYesButton.style.margin = '5px';
    confirmYesButton.onclick = () => {
      document.body.removeChild(confirmOverlay);
      resolve(true);
    };

    const confirmNoButton = document.createElement('button');
    confirmNoButton.textContent = 'Não';
    confirmNoButton.style.margin = '5px';
    confirmNoButton.onclick = () => {
      document.body.removeChild(confirmOverlay);
      resolve(false);
    };

    confirmBox.appendChild(confirmMessage);
    confirmBox.appendChild(confirmYesButton);
    confirmBox.appendChild(confirmNoButton);
    confirmOverlay.appendChild(confirmBox);
    document.body.appendChild(confirmOverlay);
  });
}
</script>
<div id='debug'>
</div>