<?php
$oRotulo = new rotulocampo;
$oRotulo->label('tf25_i_destino');
$oRotulo->label('tf02_i_lotacao');
$oRotulo->label('tf10_i_prestadora');
$oRotulo->label('z01_nome');
$oRotulo->label('tf03_c_descr');
$oRotulo->label('tf10_i_centralagend');
$oRotulo->label('tf17_c_localsaida');
$oRotulo->label('tf18_i_veiculo');
$oRotulo->label('tf18_i_motorista');

?>

<form name="form1">
  <div class='container'>
    <fieldset>
      <legend>Agendamento de Sa�da</legend>
      <fieldset class='separator'>
        <legend>Paciente</legend>
        <table class="form-container">
          <tr>
            <td class="field-size2 bold" nowrap="nowrap" >Pedido:</td>
            <td nowrap="nowrap">
              <?php
                db_input('iCodigoAgenda', 10, "", true, 'hidden', 3); //tf17_i_codigo c�digo da tfd_agendasaida
                db_input('iPedidoTFD',    10, "", true, 'text',   3);
              ?>
            </td>
          </tr>
          <tr>
            <td class="field-size2 bold" nowrap="nowrap" >Paciente:</td>
            <td nowrap="nowrap">
              <?php
                db_input('iCgsPaciente',  10, "", true, 'text', 3);
                db_input('sNomePaciente', 56, "", true, 'text', 3);
              ?>
            </td>
          </tr>
        </table>

      </fieldset>

      <fieldset class='separator'>
        <legend>Destino</legend>
        <table class="form-container">
          <tr>
            <td class='field-size2 bold' nowrap="nowrap">
              <?php
                db_ancora(@$Ltf10_i_prestadora,"js_pesquisaPrestadora(true);", 1);
              ?>
            </td>
            <td nowrap="nowrap">
            <?php

                db_input('iCodigoPrestadora', 10, $Itf10_i_prestadora, true, 'text', 1, "onchange='js_pesquisaPrestadora(false);'");
                db_input('sNomePrestadora',   56, '', true, 'text', 3, '');
                db_input('iCodigoCentral',    10, $Itf10_i_prestadora, true, 'hidden', 3);
                db_input('iVinculoCentralPrestadora', 10, "", true, 'hidden', 3);
              ?>
            </td>
          </tr>
          <tr>
            <td class='field-size2 bold' nowrap="nowrap">
              Destino:
            </td>
            <td nowrap="nowrap">
              <?php
                db_input('sDestinoPrestadora', 70, $Itf03_c_descr, true, 'text', 3, '');
                db_input('iDestinoPrestadora', 10, '', true, 'hidden', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td class='field-size2 bold' nowrap="nowrap">Agendamento:</td>
            <td nowrap="nowrap">
              <?php
                db_inputdata('dtAgendamentoPrestadora', '', '', '', true, 'text', 1, "onchange='js_validaDataPedido();'",
                             '', '', '', '', '', 'js_validaDataPedido()');
                db_input('horaAgendamentoPrestadora', 5, '', true, 'text', 1, '');
              ?>
            </td>
          </tr>
        </table>

      </fieldset>

      <fieldset class='separator'>
        <legend>Agendamento</legend>
        <table class="form-container">
          <tr>
            <td class='field-size2 bold' nowrap="nowrap">Data Sa�da:</td>
            <td nowrap="nowrap">
              <?php
                db_inputdata('dtSaida', '', '', '', true, 'text', 1, "onchange='js_validaDataSaida();'",
                             '', '', '', '', '', 'js_validaDataSaida()');
              ?>
            </td>
            <td class='bold text-right' nowrap="nowrap">Hora Sa�da:</td>
            <td nowrap="nowrap" >
              <?php

                db_input('horaSaida', 5, '', true, 'text', 1);
                db_select('selectHoraSaida', array(), true, 1, "style='display:none; width: 60px;' onchange='js_atualizaHoraSaida();'");
              ?>
            </td>
          </tr>
          <tr>
            <td class='field-size2 bold' nowrap="nowrap">Local:</td>
            <td nowrap="nowrap" colspan="3">
              <?php
                db_input('sLocalSaida', 69, $Itf17_c_localsaida, true, 'text', 1, "onkeyup='js_ValidaMaiusculo(this, \"t\", event);' style='text-transform:uppercase;'", "", "", "", 50);
              ?>
            </td>
          </tr>
          <tr>
            <td class='field-size2 bold' nowrap="nowrap">Data Retorno:</td>
            <td nowrap="nowrap">
              <?php
                db_inputdata('dtRetorno', '', '', '', true, 'text', 1, "onchange='js_validaDataRetorno();'",
                             '', '', '', '', '', 'js_validaDataRetorno()');
              ?>
            </td>
            <td class='bold text-right' nowrap="nowrap">Hora Retorno:</td>
            <td nowrap="nowrap">
              <?php
                db_input('horaRetorno', 5, '', true, 'text', 1);
              ?>
            </td>
          </tr>
        </table>

      </fieldset>

      <fieldset class='separator'>
        <legend>Ve�culo</legend>
        <table class="form-container">
          <tr>
            <td class='field-size2 bold' nowrap="nowrap">
            <?php
              db_ancora($Ltf18_i_veiculo, "js_pesquisaVeiculo(true);", 1);
            ?>
            </td>
            <td nowrap="nowrap">
              <?php
                db_input('iCodigoVeiculo', 10, $Itf18_i_veiculo, true, 'text', 1, " onchange='js_pesquisaVeiculo(false);'");
                db_input('sPlacaVeiculo',  56, '', true, 'text', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td class='field-size2 bold' nowrap="nowrap">
              <?php
                db_ancora($Ltf18_i_motorista, "js_pesquisaMotorista(true);", 1);
              ?>
            </td>
            <td nowrap="nowrap">
              <?php
                db_input('iCodigoMotorista', 10, $Itf18_i_motorista, true, 'text', 1, " onchange='js_pesquisaMotorista(false);'");
                db_input('sNomeMotorista', 56, '' , true, 'text', 3, '');
              ?>
            </td>
          </tr>
        </table>

        <fieldset class='separator'>
          <legend>Lota��o</legend>
          <table class="form-container">
          <tr>
            <td class='bold' nowrap="nowrap">Total de Lugares: </td>
            <td nowrap="nowrap"><input type="text" size="3" id='totalLugares'            class="readonly" value = '0' /></td>
            <td class='bold' nowrap="nowrap"> - Pacientes </td>
            <td nowrap="nowrap"><input type="text" size="3" id='numeroPacientes'         class="readonly" value = '0' /></td>
            <td class='bold' nowrap="nowrap"> - Acompanhantes </td>
            <td nowrap="nowrap"><input type="text" size="3" id='numeroAcompanhantes'     class="readonly" value = '0' /></td>
            <td class='bold' nowrap="nowrap"> = Lugares Dispon�veis: </td>
            <td nowrap="nowrap"><input type="text" size="3" id='totalLugaresDisponiveis' class="readonly" value = '0' /></td>
          </tr>
        </table>


        </fieldset>
      </fieldset>

    </fieldset>
  </div>

  <div class="container">
    <fieldset style ='width:1000px;'>
        <legend>Passageiros</legend>
        <div id='ctnGridPassageiros'></div>
    </fieldset>

    <input name="salvar"  type="button" id="salvar"  value="Incluir"  />
    <input name="excluir" type="button" id="excluir" value="Excluir" style="display: none" />
    <input name="fechar"  type="button" id="fechar"  value="Fechar" onclick="parent.db_iframe_saida.hide();" />
  </div>
</form>
<script type="text/javascript">

var sRPC = 'tfd4_agendasaida.RPC.php';
var oGet = js_urlToObject();

var oPedido = {};

const MSG_AGENDASAIDA = 'saude.tfd.db_frm_agendasaida.';

var oHoraAgendamento = new DBInputHora($('horaAgendamentoPrestadora'));
var oHoraSaida       = new DBInputHora($('horaSaida'));
var oHoraRetorno     = new DBInputHora($('horaRetorno'));

var oGridPassageiros = new DBGrid('gridPassageiros');
oGridPassageiros.nameInstance = 'oGridPassageiros';
oGridPassageiros.setCheckbox(0);
oGridPassageiros.setCellWidth( ['10%', '80%', '10%'] );
oGridPassageiros.setCellAlign( ['left', 'left', 'center'] );
oGridPassageiros.setHeader( ['CGS', 'Passageiro', 'Fica'] );
// oGridPassageiros.aHeaders[0].lDisplayed = false;
oGridPassageiros.show($('ctnGridPassageiros'));


var lUsaGradeHorario = false;
(function () {


  js_buscaParametros();
  js_buscaDadosPedido(oGet.tf17_i_pedidotfd);

})();

function js_buscaParametros() {

  var oParametros = {'sExecucao' : 'getParametros'}

  js_divCarregando(_M( MSG_AGENDASAIDA + "vericando_parametros"), "msgBox");

  var oObjeto          = {};
  oObjeto.method       = 'post';
  oObjeto.parameters   = 'json='+Object.toJSON(oParametros);
  oObjeto.asynchronous = false;
  oObjeto.onComplete   = function(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval('(' + oAjax.responseText + ')');
    lUsaGradeHorario = oRetorno.lUtilizaGradeHorario;

    if ( lUsaGradeHorario ) {

      $('horaSaida').style.display       = 'none';
      $('selectHoraSaida').style.display = '';
    }

  };

  new Ajax.Request(sRPC, oObjeto);
}

function js_buscaDadosPedido (iPedido) {

  $('totalLugares').value            = 0;
  $('numeroPacientes').value         = 0;
  $('numeroAcompanhantes').value     = 0;
  $('totalLugaresDisponiveis').value = 0;

  var oParametros = {'sExecucao' : 'getDadosPedido', 'iPedido' : iPedido, 'iCgs' : oGet.tf01_i_cgsund};

  js_divCarregando(_M( MSG_AGENDASAIDA + "vericando_pedido"), "msgBox");

  var oObjeto          = {};
  oObjeto.method       = 'post';
  oObjeto.parameters   = 'json='+Object.toJSON(oParametros);
  oObjeto.asynchronous = false;
  oObjeto.onComplete   = function(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval('(' + oAjax.responseText + ')');

    if (parseInt(oRetorno.iStatus) == 2) {

      alert(oRetorno.sMensagem.urlDecode());
      return false;
    }

    js_atualizaDadosPedido(oRetorno.oPedido);
    return true
  };

  new Ajax.Request(sRPC, oObjeto);
};

/**
 * Atualiza os dados do pedido
 * @param  {Object} oDadosPedido
 * @return {void}
 */
function js_atualizaDadosPedido(oDadosPedido) {

  oPedido         = oDadosPedido;
  oPedido.iPedido = oGet.tf17_i_pedidotfd;

  if (oPedido.iCodigoAgenda != '') {

    $('salvar').value          = 'Alterar';
    $('excluir').style.display = '';
  }

  $('iCodigoAgenda').value             = oPedido.iCodigoAgenda;
  $('iPedidoTFD').value                = oPedido.iPedido;
  $('iCgsPaciente').value              = oPedido.iCgs;
  $('sNomePaciente').value             = oPedido.sCgs.urlDecode();
  $('iCodigoPrestadora').value         = oPedido.iPrestadora;
  $('sNomePrestadora').value           = oPedido.sPrestadora.urlDecode();
  $('iCodigoCentral').value            = oPedido.iCentralAgendamento;
  $('sDestinoPrestadora').value        = oPedido.sDestino.urlDecode();
  $('iDestinoPrestadora').value        = oPedido.iDestino;
  $('dtAgendamentoPrestadora').value   = oPedido.dtAgendamento;
  $('horaAgendamentoPrestadora').value = oPedido.sHoraAgendamento;
  $('dtSaida').value                   = oPedido.dtSaida;

  /**
   * Sempre que existir data de saida e estiver configurado para usar grade de horario, devemos buscar
   * os horarios da data de saida
   */
  $('horaSaida').value                 = oPedido.sHoraSaida;
  if (oPedido.dtSaida !='' && lUsaGradeHorario) {

    js_buscaHorariosData();
    $('selectHoraSaida').value = oPedido.sHoraSaida;
  }

  $('sLocalSaida').value               = oPedido.sLocalSaida.urlDecode();
  $('dtRetorno').value                 = oPedido.dtRetorno;
  $('horaRetorno').value               = oPedido.sHoraRetorno;
  $('iCodigoVeiculo').value            = oPedido.iVeiculo;
  $('sPlacaVeiculo').value             = oPedido.sPlaca.urlDecode();
  $('iCodigoMotorista').value          = oPedido.iMotorista;
  $('sNomeMotorista').value            = oPedido.sMotorista.urlDecode();

  js_buscaAcompanhantes();
  if (oPedido.iVeiculo != '' && oPedido.iLotacaoVeiculo != '' && oPedido.dtSaida != '' && oPedido.sHoraSaida != '') {

    $('totalLugares').value            = oPedido.iLotacaoVeiculo;
    $('totalLugaresDisponiveis').value = oPedido.iLotacaoVeiculo;
    js_buscaVagasOcupadasVeiculo();
  }

};

/**
 * Busca os acompanhates do paciente
 * @return {void}
 */
function js_buscaAcompanhantes() {

  var oParametros = {'sExecucao' : 'getAcompanhantes', 'iPedido' : oPedido.iPedido};
  js_divCarregando(_M( MSG_AGENDASAIDA + "vericando_acompanhantes"), "msgBox");

  var oObjeto          = {};
  oObjeto.method       = 'post';
  oObjeto.parameters   = 'json='+Object.toJSON(oParametros);
  oObjeto.asynchronous = false;
  oObjeto.onComplete   = function(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval('(' + oAjax.responseText + ')');

    oGridPassageiros.clearAll(true);

    var aLinha = [];
    aLinha.push(oPedido.iCgs);
    aLinha.push(oPedido.sCgs.urlDecode());
    aLinha.push( js_criaCheckBoxFica( oPedido.iCgs, oPedido.iFica ).outerHTML );

    oGridPassageiros.addRow(aLinha, false, false, true);

    oRetorno.aAcompanhantes.each( function (oAcopanhante ) {

      var lMarcaLinha = oAcopanhante.lVinculadoCarro;
      var aLinha      = [];

      aLinha.push(oAcopanhante.iAcompanhante);
      aLinha.push(oAcopanhante.sAcompanhante.urlDecode());
      aLinha.push( js_criaCheckBoxFica( oAcopanhante.iAcompanhante, oAcopanhante.iFica ).outerHTML );
      oGridPassageiros.addRow(aLinha, false, false, lMarcaLinha);
    });

    oGridPassageiros.renderRows();
    document.getElementById(oGridPassageiros.aRows[0].aCells[0].getId() ).firstChild.setAttribute('disabled', 'disabled');

    var iLinhas = oGridPassageiros.aRows.length;
    /**
     * Acrescenta fun��o a partir da segunda linha da grid
     */
    for (var i = 1; i < iLinhas; i++) {

      var oCheckGrid = document.getElementById(oGridPassageiros.aRows[i].aCells[0].getId() ).firstChild;
      oCheckGrid.observe('click', function() {

        if ($F('iCodigoVeiculo') == '') {

          alert( _M(MSG_AGENDASAIDA + "veiculo_nao_informado") );
          this.checked = false;
          return false;
        }

        if ( this.checked ) {

          $('numeroAcompanhantes').value     = parseInt($F('numeroAcompanhantes')) + 1;
          $('totalLugaresDisponiveis').value = parseInt($F('totalLugaresDisponiveis')) - 1;
        } else {

          $('numeroAcompanhantes').value     = parseInt($F('numeroAcompanhantes')) - 1;
          $('totalLugaresDisponiveis').value = parseInt($F('totalLugaresDisponiveis')) + 1;
        }
      });
    }
  };

  new Ajax.Request(sRPC, oObjeto);

}

/**
 * Cria o checbox para grid, marcado sigifica que o paciente ficar� no destino
 * @param  {int} iCgs
 * @param  {int} iFica
 * @return {HTMLINPUTCHECKBOX}
 */
function js_criaCheckBoxFica(iCgs, iFica) {

  var oCheckBox  = document.createElement('input');
  oCheckBox.type = 'checkbox';
  oCheckBox.id   = 'fica#'+iCgs;
  oCheckBox.value = iCgs;

  if ( parseInt(iFica) == 1) {
    oCheckBox.setAttribute('checked','checked');
  }

  // oCheckBox.setAttribute( "onclick", 'console.log(this.checked)' );
  return oCheckBox;
}

/**
 * Busca prestadora
 * @param  {boolean} lMostra
 * @return {void}
 */
function js_pesquisaPrestadora(lMostra) {

  /**
   * Variavel iCodigoPrestadora guarda o codigo digitado da prestadora, para que quando seja executado: js_removerAgendamento()
   * Ap�s recarregar os dados, ainda termos o c�digo digitado pelo usu�rio, e buscar os dados da prestadora digitada
   */
  var iCodigoPrestadora = $F('iCodigoPrestadora');
  if (oPedido.iCodigoAgenda != '') {

    if (confirm( _M(MSG_AGENDASAIDA + "pedido_possui_agenda_trocar_prestadora_excluir_agenda") ) ) {
      js_removerAgendamento();
    } else {

      if (!lMostra) {
        $('iCodigoPrestadora').value = oPedido.iPrestadora;
      }
      return false;
    }
  }

  var sUrl    = 'func_tfd_prestadoracentralagend.php';
  var sIframe = 'db_iframe_tfd_prestadoracentralagend';

  if($F('iCodigoCentral') == '') {

    alert(_M( MSG_AGENDASAIDA + "pedido_sem_central_atendimento") );
    return false;
  }

  sUrl += '?chave_tf10_i_centralagend='+$F('iCodigoCentral');
  sUrl += '&funcao_js=parent.js_mostraPrestadora|tf10_i_prestadora|z01_nome|z01_munic|tf25_i_destino|tf10_i_codigo';

  if (lMostra) {
    js_OpenJanelaIframe('', sIframe, sUrl, 'Pesquisa Prestadora', true);
  } else {

    if( $F('iCodigoPrestadora') != '') {

      sUrl += '&chave_tf10_i_prestadora='+iCodigoPrestadora+'&nao_mostra=true';
      js_OpenJanelaIframe('', sIframe, sUrl, 'Pesquisa Prestadora',false);

    } else {

      $('iCodigoPrestadora').value         = '';
      $('sNomePrestadora').value           = '';
      $('sDestinoPrestadora').value        = '';
      $('iDestinoPrestadora').value        = '';
      $('iVinculoCentralPrestadora').value = '';
    }
  }
}

function js_mostraPrestadora(iPrestadora, sPrestadora, sMunicipio, iDestino, iVinculoCentralPrestadora) {



  if (iPrestadora == '') {
    $('sNomePrestadora').value = sPrestadora;
    return false;
  }
  $('iVinculoCentralPrestadora').value = iVinculoCentralPrestadora;
  $('iCodigoPrestadora').value         = iPrestadora;
  $('sNomePrestadora').value           = sPrestadora;
  $('sDestinoPrestadora').value        = sMunicipio;
  $('iDestinoPrestadora').value        = iDestino;
  db_iframe_tfd_prestadoracentralagend.hide();

  oPedido.iPrestadora = iPrestadora;
  oPedido.sPrestadora = sPrestadora;
  oPedido.sDestino    = sMunicipio;

}


/**
 * Valida as informa��es do agendamento
 * @return {boolean}
 */
function js_validaDadosAgendamento() {

  if ($F('dtSaida') == '' ) {

    alert( _M(MSG_AGENDASAIDA + "data_saida_nao_informada") )
    return false;
  }

  if ($F('horaSaida') == '' ) {

    alert( _M(MSG_AGENDASAIDA + "hora_saida_nao_informada") )
    return false;
  }

  if ($F('dtRetorno') == '' ) {

    alert( _M(MSG_AGENDASAIDA + "data_retorno_nao_informada") )
    return false;
  }

  if ($F('horaRetorno') == '' ) {

    alert( _M(MSG_AGENDASAIDA + "hora_retorno_nao_informada") )
    return false;
  }
  return true;
}

var lVeiculoAlterado = false;

/**
 * Pesquisa os ve�culos dispon�veis para o m�dulo
 * @param  {boolean} lMostra
 * @return {void}
 */
function js_pesquisaVeiculo(lMostra) {

  if (!js_validaDadosAgendamento()) {

    $('iCodigoVeiculo').value = '';
    return false;
  }

  if ( $F('iCodigoAgenda') != '' && oPedido.iVeiculo != '' && !lVeiculoAlterado) {

    if ( confirm( _M(MSG_AGENDASAIDA + "confirma_troca_veiculo") ) ) {

      js_desmarcaPassageiros();
      js_removePassageirosVeiculo();
    } else {

      if (!lMostra) {
        $('iCodigoVeiculo').value = oPedido.iVeiculo;
      }
      return false;
    }
  }


  var sUrl = 'func_veiculosalt.php';

  if (lMostra) {

    sUrl += '?funcao_js=parent.js_mostraVeiculo1|ve01_codigo|ve01_quantcapacidad|ve01_placa'
    js_OpenJanelaIframe('', 'db_iframe_veiculos', sUrl, 'Pesquisa Ve�culos', true);
  } else {

    if ($F('iCodigoVeiculo') != '') {

      sUrl += '?funcao_js=parent.js_mostraVeiculo&iParam=1';
      sUrl += '&pesquisa_chave=' + $F('iCodigoVeiculo');

      js_OpenJanelaIframe('', 'db_iframe_veiculos', sUrl, 'Pesquisa Ve�culos', false);

    } else {

      $('iCodigoVeiculo').value = '';
      $('sPlacaVeiculo').value = '';
    }
  }
}

function js_mostraVeiculo(sPlaca, iCapacidade, lErro) {

  $('sPlacaVeiculo').value           = sPlaca;
  $('totalLugares').value            = iCapacidade;
  $('totalLugaresDisponiveis').value = iCapacidade;
  if ( lErro ) {

    $('iCodigoVeiculo').focus();
    $('iCodigoVeiculo').value          = '';
    $('totalLugares').value            = 0;
    $('totalLugaresDisponiveis').value = 0;
    $('numeroPacientes').value         = 0;
    $('numeroAcompanhantes').value     = 0;
    return false;
  }

  js_buscaVagasOcupadasVeiculo();

}

function js_mostraVeiculo1(iVeiculo, iCapacidade, sPlaca) {

  $('iCodigoVeiculo').value          = iVeiculo;
  $('sPlacaVeiculo').value           = sPlaca;
  $('totalLugares').value            = iCapacidade;
  $('totalLugaresDisponiveis').value = iCapacidade;
  db_iframe_veiculos.hide();
  js_buscaVagasOcupadasVeiculo();

}

/**
 * Busca as vagas ocupadas pelo ve�culo
 * @return {void}
 */
function js_buscaVagasOcupadasVeiculo () {

  var oParametros         = {'sExecucao' : 'getVagasVeiculo'};
  oParametros.dtSaida     = $F('dtSaida');
  oParametros.dtRetorno   = $F('dtRetorno');
  oParametros.horaSaida   = $F('horaSaida');
  oParametros.horaRetorno = $F('horaRetorno');
  oParametros.iVeiculo    = $F('iCodigoVeiculo');
  oParametros.iDestino    = $F('iDestinoPrestadora');

  js_divCarregando( _M( MSG_AGENDASAIDA + "vericando_vagas_veiculo"), "msgBox");

  var oObjeto          = {};
  oObjeto.method       = 'post';
  oObjeto.parameters   = 'json='+Object.toJSON(oParametros);
  oObjeto.asynchronous = false;
  oObjeto.onComplete   = function(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval('(' + oAjax.responseText + ')');

    if (parseInt(oRetorno.iStatus) == 2) {

      alert(oRetorno.sMensagem.urlDecode());
      return false;
    }

    /**
     * Quando o temos a informa��o do c�digo da agenda, o paciente j� esta contabilizado na soma dos passageiros
     * por isso zeramos a variavel: iPacienteAtual
     */
    var iPacienteAtual = 1;

    if ($F('iCodigoAgenda') != '' && $F('iCodigoVeiculo') == oPedido.iVeiculo) {
      iPacienteAtual = 0;
    }

    $('numeroPacientes').value         = oRetorno.oVagas.iPassageiros + iPacienteAtual;
    $('numeroAcompanhantes').value     = oRetorno.oVagas.iAcompanhantes;
    $('totalLugaresDisponiveis').value = (parseInt($F('totalLugaresDisponiveis') - oRetorno.oVagas.iTotal)) - iPacienteAtual;

    return true
  };

  new Ajax.Request(sRPC, oObjeto);
}

function js_desmarcaPassageiros () {


  var iLinhas = oGridPassageiros.aRows.length;

  /**
   * Desmarca os acompanhantes informados na grid
   */
  for (var i = 1; i < iLinhas; i++) {

    $(oGridPassageiros.aRows[i].sId).removeClassName('marcado');
    $(oGridPassageiros.aRows[i].sId).addClassName('normal');
    var oCheckGrid = document.getElementById(oGridPassageiros.aRows[i].aCells[0].getId() ).firstChild;
    oCheckGrid.checked = false
  }
}

function js_pesquisaMotorista(lMostra) {

  var sUrl = 'func_veicmotoristasalt.php';
  if (lMostra) {

    sUrl += '?funcao_js=parent.js_mostraMotorista1|ve05_codigo|z01_nome';
    js_OpenJanelaIframe('', 'db_iframe_veicmotoristas', sUrl, 'Pesquisa Motorista', true);

  } else if ($F('iCodigoMotorista') != '') {

      sUrl += '?funcao_js=parent.js_mostraMotorista';
      sUrl += '&pesquisa_chave=' + $F('iCodigoMotorista');
      js_OpenJanelaIframe('', 'db_iframe_veicmotoristas', sUrl, 'Pesquisa Motorista', false );
  } else {

      $('iCodigoMotorista').value = '';
      $('sNomeMotorista').value   = '';
  }
}

function js_mostraMotorista(sNomeMotorista, sErro) {

  $('sNomeMotorista').value = sNomeMotorista;
  if (sErro) {

    $('iCodigoMotorista').value = '';
    $('iCodigoMotorista').focus();
  }
}

function js_mostraMotorista1(iCodigoMotorista, sNomeMotorista) {

  $('iCodigoMotorista').value = iCodigoMotorista;
  $('sNomeMotorista').value   = sNomeMotorista;
  db_iframe_veicmotoristas.hide();
}

function js_validaDataPedido() {

  if (js_comparadata($F('dtAgendamentoPrestadora'), oGet.dataPedido, '<')) {

    alert(_M(MSG_AGENDASAIDA + "data_saida_menor_data_pedido"));
    $('dtAgendamentoPrestadora').value = '';
    return false;
  }

  if ($F('dtSaida') != '' && js_comparadata($F('dtAgendamentoPrestadora'), $F('dtSaida'), '<') ) {

    alert(_M(MSG_AGENDASAIDA + "data_agendamento_menor_data_saida"));
    $('dtAgendamentoPrestadora').value = '';
    return false;
  }
  return true;
}

/**
 * Valida se a data de sa�da � maior que a data agendada
 * @return {[type]} [description]
 */
function js_validaDataSaida() {

  if (js_comparadata($F('dtSaida'), $F('dtAgendamentoPrestadora'), '>')) {

    alert( _M(MSG_AGENDASAIDA + "data_saida_maior_data_agenda_prestadora") );
    $('dtSaida').value = '';
    return false;
  }

  if (lUsaGradeHorario) {
    js_buscaHorariosData();
  }
  return true;
}

function js_validaDataRetorno() {

  if ( $F('dtSaida') == '' && $F('dtRetorno') == '') {
    return false;
  }

  if (js_comparadata($F('dtRetorno'), $F('dtSaida'), '<')) {

    alert(_M(MSG_AGENDASAIDA + "data_retorno_menor_data_saida"));
    $('dtSaida').value = '';
    return false;
  }

  return true;
}


function js_buscaHorariosData() {

  var oParametros = {'sExecucao' : 'getHorasGradeHorario', 'dtSaida' : $F('dtSaida'), 'iDestino' : $F('iDestinoPrestadora') };

  js_divCarregando(_M( MSG_AGENDASAIDA + "buscando_horarios_grade"), "msgBox");

  var oObjeto          = {};
  oObjeto.method       = 'post';
  oObjeto.parameters   = 'json='+Object.toJSON(oParametros);
  oObjeto.asynchronous = false;
  oObjeto.onComplete   = function(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval('(' + oAjax.responseText + ')');

    if (parseInt(oRetorno.iStatus) == 2) {

      alert(oRetorno.sMensagem.urlDecode());
      return false;
    }

    $('selectHoraSaida').style.display = '';
    $('selectHoraSaida').add(new Option(' ', ''));
    oRetorno.aHorarios.each(function(sHora) {
      $('selectHoraSaida').add(new Option(sHora, sHora));
    });
  };

  new Ajax.Request(sRPC, oObjeto);
}

/**
 * Quando estiver configurado para utilizar grade de horarios, ao selecionar um horario
 * atualiza o input horaSaida. Assim n�o h� nescessidade de alterar todas as fun��es para validar a hora de saida
 * de um input e de um select
 * @return {void}
 */
function js_atualizaHoraSaida() {

  $('horaSaida').value =  $('selectHoraSaida').value;
}

/**
 * Realiza a valida��o dos campos obrigat�rios
 * @return {boolean}
 */
function validaCamposObrigatorios () {

  if ($F('iCodigoPrestadora') == '') {

    alert( _M(MSG_AGENDASAIDA + "prestadora_nao_informada") );
    return false;
  }

  if ($F('dtAgendamentoPrestadora') == '') {

    alert( _M(MSG_AGENDASAIDA + "data_agendamento_nao_informada") );
    return false;
  }

  if ($F('horaAgendamentoPrestadora') == '') {

    alert( _M(MSG_AGENDASAIDA + "hora_agendamento_nao_informada") );
    return false;
  }

  if ( !js_validaDadosAgendamento() ) {
    return false;
  }

  if ($F('sLocalSaida') == '') {

    alert( _M(MSG_AGENDASAIDA + "local_saida_nao_informado") );
    return false;
  }

  if ($F('iCodigoVeiculo') == '') {

    alert( _M(MSG_AGENDASAIDA + "veiculo_nao_informado") );
    return false;
  }

  return true;

}

$('salvar').observe('click', function() {
  js_salvar();
});

function js_salvar() {

  if (!validaCamposObrigatorios()) {
    return false;
  }

  /*aqui vai um Plugin SMSTFD*/


  /* INFORMA SE O VEICULO JA ESTA LOTADO */
  if (parseInt($F('totalLugaresDisponiveis')) < 0) {
    alert( _M( MSG_AGENDASAIDA + "veiculo_sem_vagas_selecione_outro_veiculo", {sCampo : $F('sPlacaVeiculo')}) );
  }

  var sMensagemDivCarregando = _M( MSG_AGENDASAIDA + "incluindo_agenda" );
  var oParametros            = { 'sExecucao' : 'salvar'};

  oParametros.iCodigoAgendamentoPrestadora = oPedido.iCodigoAgendamentoPrestadora;
  oParametros.iCodigoAgenda                = $F('iCodigoAgenda');
  oParametros.iPedidoTFD                   = $F('iPedidoTFD');
  oParametros.iCgsPaciente                 = $F('iCgsPaciente');
  oParametros.iCodigoPrestadora            = $F('iCodigoPrestadora');
  oParametros.iCodigoCentral               = $F('iCodigoCentral');
  oParametros.iVinculoCentralPrestadora    = $F('iVinculoCentralPrestadora');
  oParametros.iDestinoPrestadora           = $F('iDestinoPrestadora');
  oParametros.dtAgendamentoPrestadora      = $F('dtAgendamentoPrestadora');
  oParametros.horaAgendamentoPrestadora    = $F('horaAgendamentoPrestadora');
  oParametros.dtSaida                      = $F('dtSaida');
  oParametros.horaSaida                    = $F('horaSaida');
  oParametros.sLocalSaida                  = encodeURIComponent(tagString($F('sLocalSaida')));
  oParametros.dtRetorno                    = $F('dtRetorno');
  oParametros.horaRetorno                  = $F('horaRetorno');
  oParametros.iCodigoVeiculo               = $F('iCodigoVeiculo');
  oParametros.iCodigoMotorista             = $F('iCodigoMotorista');

  oParametros.aPassageiros                 = [];

  var aSelecionadosGrade = oGridPassageiros.getSelection();
  aSelecionadosGrade.each( function(aLinha) {

    var iCgsGrade    = aLinha[0];
    var lFicaDestino = $('fica#'+iCgsGrade).checked;
    var oPassageiro  = {iCgs : iCgsGrade, lPaciente : iCgsGrade == oParametros.iCgsPaciente, lFica : lFicaDestino};

    oParametros.aPassageiros.push(oPassageiro);
  });

  js_divCarregando( sMensagemDivCarregando , "msgBox");

  var oObjeto          = {};
  oObjeto.method       = 'post';
  oObjeto.parameters   = 'json='+Object.toJSON(oParametros);
  oObjeto.asynchronous = false;
  oObjeto.onComplete   = function(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval('(' + oAjax.responseText + ')');

    alert(oRetorno.sMensagem.urlDecode());
    if (parseInt(oRetorno.iStatus) == 2) {
      return false;
    }

    /**
     * Para n�o recarregar os dados, seta algumas vari�veis com os dados salvos
     */
    $('iCodigoAgenda').value   = oRetorno.iCodigoAgenda;
    oPedido.iCodigoAgenda      = oRetorno.iCodigoAgenda;
    oPedido.iVeiculo           = $F('iCodigoVeiculo');
    $('salvar').value          = "Alterar";
    $('excluir').style.display = '';
    lVeiculoAlterado           = false;
    parent.db_iframe_saida.hide();
    return true
  };

  new Ajax.Request(sRPC, oObjeto);
}

$('excluir').observe('click', function () {
  js_removerAgendamento();
});

/**
 * @todo implementar... exluir tudo vinculado  tabela tfd_agendasaida e v�nculo do pedido com o carro
 */
function js_removerAgendamento() {

  var oParametros            = { 'sExecucao' : 'removerAgendamento'};
  oParametros.iCodigoAgenda  = $F('iCodigoAgenda');
  oParametros.iPedidoTFD     = $F('iPedidoTFD');

  js_divCarregando( _M( MSG_AGENDASAIDA + "removendo_agenda" ) , "msgBox");

  var oObjeto          = {};
  oObjeto.method       = 'post';
  oObjeto.parameters   = 'json='+Object.toJSON(oParametros);
  oObjeto.asynchronous = false;
  oObjeto.onComplete   = function(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval('(' + oAjax.responseText + ')');

    alert(oRetorno.sMensagem.urlDecode());
    if (parseInt(oRetorno.iStatus) == 2) {
      return false;
    }

    $('iCodigoAgenda').value   = '';
    $('salvar').value          = "Incluir";
    $('excluir').style.display = 'none';
    js_buscaDadosPedido($F('iPedidoTFD'));

    return true
  };

  new Ajax.Request(sRPC, oObjeto);
}


function js_removePassageirosVeiculo() {

  var oParametros        = { 'sExecucao' : 'removerPassageirosVeiculo'};
  oParametros.iPedidoTFD = $F('iPedidoTFD');

  js_divCarregando( _M( MSG_AGENDASAIDA + "removendo_passageiros" ) , "msgBox");

  var oObjeto          = {};
  oObjeto.method       = 'post';
  oObjeto.parameters   = 'json='+Object.toJSON(oParametros);
  oObjeto.asynchronous = false;
  oObjeto.onComplete   = function(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval('(' + oAjax.responseText + ')');

    alert(oRetorno.sMensagem.urlDecode());
    if (parseInt(oRetorno.iStatus) == 2) {
      return false;
    }

    lVeiculoAlterado = true;
    return true
  };

  new Ajax.Request(sRPC, oObjeto);
}

</script>