<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");

$db_opcao = 1;

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
  <title>DBSeller Inform�tica Ltda</title>
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("windowAux.widget.js");
  db_app::load("strings.js");
  db_app::load("dbtextField.widget.js");
  db_app::load("dbViewAvaliacoes.classe.js");
  db_app::load("dbmessageBoard.widget.js");
  db_app::load("dbautocomplete.widget.js");
  db_app::load("dbcomboBox.widget.js");
  db_app::load("datagrid.widget.js");
  db_app::load("AjaxRequest.js");
  db_app::load("widgets/DBLookUp.widget.js");
  db_app::load("estilos.css,grid.style.css");
  ?>
</head>

<body>
  <form id="formPesquisarEsocial" method="POST" action="eso4_preenchimento001.php" class="container">
    <fieldset>
      <legend>Confer�ncia dos dados informados pelo servidor:</legend>
      <table class="form-container">
        <tr>
          <td style="width: 22%;">
            <strong>Tipo identificador:</strong>
          </td>
          <td>
            <select name="tpid" id="tpid" style="width: 25%;" onchange="js_alt_identificador(this)">
              <option value="2">Matricula</option>
              <option value="1">CGM</option>
            </select>
          </td>
        </tr>
      </table>
      <table class="form-container">
        <tr class="linha_cgm" style="display: none">
          <td nowrap title="<?php echo $Tz01_numcgm; ?>">
            <a id="lbl_z01_numcgm" for="cgm"><?= $Lz01_numcgm ?></a>
          </td>
          <td>
            <?php db_input('z01_numcgm', 10, $Iz01_numcgm, true, "text", 1, "", "", "", "width: 16%"); ?>
            <?php db_input('z01_nomecgm', 50, $Iz01_nomecgm, true, "text", 3, "", "", "", "width: 61%"); ?>
            <input type="button" name="adicionar" value="Adicionar" onclick="js_adicionar_cgm()" />
          </td>
        </tr>
        <tr class="linha_cgm" style="display: none">
          <td>
            <strong>Cgm</strong>
          </td>
          <td style="width:100%">
            <select multiple="multiple" name="cgms" id="cgms" style="width: 78%;" ondblclick="js_remover_cgm(this);">
            </select>
          </td>
        </tr>

        <tr class="linha_matricula">
          <td nowrap title="<?php echo $Trh01_regist; ?>">
            <a id="lbl_rh01_regist" for="matricula"><?= $Lrh01_regist ?></a>
          </td>
          <td>
            <?php db_input('rh01_regist', 10, $Irh01_regist, true, "text", 1, "", "", "", "width: 16%"); ?>
            <?php db_input('z01_nome', 50, $Iz01_nome, true, "text", 3, "", "", "", "width: 61%"); ?>
            <input type="button" name="adicionar" value="Adicionar" onclick="js_adicionar_matric()" />
          </td>
        </tr>
        <tr class="linha_matricula">
          <td>
            <strong>Matr�culas</strong>
          </td>
          <td style="width:100%">
            <select multiple="multiple" name="matriculas" id="matriculas" style="width: 78%;"
              ondblclick="js_remover_matric(this);">
            </select>
          </td>
        </tr>
      </table>
      <table class="form-container">
        <tr style="display: none;">
          <td align="left"><label for="cboEmpregador">Empregador:</label></td>
          <td>
            <select name="empregador" id="cboEmpregador" style="width: 78%;">
              <option value="">selecione</option>
            </select>
          </td>
        </tr>
        <tr>
          <td align="right"><label for="tpAmb">Ambiente:</label></td>
          <td>
            <select name="tpAmb" id="tpAmb" style="width: 78%;">
              <option value="1">Produ��o</option>
              <option value="2">Produ��o restrita - dados reais</option>
              <option value="3">Produ��o restrita - dados fict�cios</option>
            </select>
          </td>
        </tr>
        <tr>
          <td align="right"><label for="modo">Tipo:</label></td>
          <td>
            <select name="modo" id="modo" style="width: 78%;">
              <option value="INC">Inclus�o</option>
              <option value="ALT">Altera��o</option>
              <option value="EXC">Exclus�o</option>
            </select>
          </td>
        </tr>
        <tr>
          <td align="left"><label>Compet�ncia:</label></td>
          <td>
            <?php
            db_input('anofolha', 4, 1, true, 'text', 2, "class='field-size1' onkeyup='moveFocusToMesfolha(event)'", "", "", "", 4);
            db_input('mesfolha', 2, 1, true, 'text', 2, "class='field-size1'", "", "", "", 2);
            ?>
          </td>
        </tr>
        <tr>
          <td align="right"><label for="evento">Evento:</label></td>
          <td>
            <select name="evento" id="evento" style="width: 78%;" onchange="js_alt_evento()">
              <option value="">selecione</option>
              <option value="S2200">S-2200 - Cadastramento Inicial do V�nculo e Admiss�o/Ingresso de Trabalhador
              </option>
              <option value="S2205">S-2205 - Altera��o de Dados Cadastrais do Trabalhador</option>
              <option value="S2206">S-2206 - Altera��o de Contrato de Trabalho/Rela��o Estatut�ria</option>
              <option value="S2300">S-2300 - Trabalhador Sem V�nculo de Emprego/Estatut�rio - In�cio</option>
              <option value="S2230">S-2230 - Afastamento Tempor�rio</option>
              <option value="S2306">S-2306 - Trabalhador Sem V�nculo de Emprego/Estatut�rio - Altera��o Contratual
              </option>
              <option value="S2400">S-2400 - Cadastro de Benefici�rio - Entes P�blicos - In�cio</option>
              <option value="S2410">S-2410 - Cadastro de Benef�cio - Entes P�blicos - In�cio</option>
              <option value="S2299">S-2299 - Desligamento</option>
              <option value="S1200">S-1200 - Remunera��o de Trabalhador vinculado ao Regime Geral de Previd. Social
              </option>
              <option value="S1202">S-1202 - Remunera��o de Servidor vinculado ao Regime Pr�prio de Previd</option>
              <option value="S1207">S-1207 - Benef�cios - Entes P�blicos</option>
              <option value="S1210">S-1210 - Pagamentos de Rendimentos do Trabalho</option>
            </select>
          </td>
        </tr>
      </table>
      <table>
        <tr id="indapuracao_col" style="display: none;">
          <td align="right"><label for="indapuracao"><strong>Apura��o:</strong></label></td>
          <td>
            <select name="indapuracao" id="indapuracao" style="width: 78%;">
              <option value="1">Mensal</option>
              <option value="2">Anual</option>
            </select>
          </td>
        </tr>
        <tr id="dtalteracao" style="display:none">
          <td align="left"><label><strong>Data Altera��o:</strong></label>

            <input name="dt_alteracao" type="text" id="dt_alteracao" value="" size="10" maxlength="10"
              autocomplete="off" onblur="js_validaDbData(this);" onkeyup="return js_mascaraData(this,event)"
              onfocus="js_validaEntrada(this);" onpaste="return false" ondrop="return false">
            <input name="dt_alteracao_dia" type="hidden" title="" id="dt_alteracao_dia" value="" size="2" maxlength="2">
            <input name="dt_alteracao_mes" type="hidden" title="" id="dt_alteracao_mes" value="" size="2" maxlength="2">
            <input name="dt_alteracao_ano" type="hidden" title="" id="dt_alteracao_ano" value="" size="4" maxlength="4">
            <script>
              var PosMouseY, PosMoudeX;

              function js_comparaDatasdt_alteracao(dia, mes, ano) {
                var objData = document.getElementById('dt_alteracao');
                objData.value = dia + "/" + mes + '/' + ano;
              }
            </script>

            <input value="D" type="button" id="dtjs_dt_alteracao" name="dtjs_dt_alteracao"
              onclick="pegaPosMouse(event);show_calendar('dt_alteracao','none')">
          </td>
        </tr>
        <tr id="tppgto_col" style="display:none">
          <td><label><strong>Tipo de Pagamento:</strong></label></td>
          <td><select name="tppgto" id="tppgto" style="width: 100%;">
              <option value="1">Pagamento de remunera��o, conforme apurado em
                ideDmDev do S-1200
              </option>
              <option value="2">Pagamento de verbas rescis�rias conforme apurado
                em ideDmDev do S-2299
              </option>
              <option value="3">Pagamento de verbas rescis�rias conforme apurado
                em ideDmDev do S-2399
              </option>
              <option value="4">Pagamento de remunera��o conforme apurado em
                ideDmDev do S-1202
              </option>
              <option value="5">Pagamento de benef�cios previdenci�rios, conforme
                apurado em ideDmDev do S-1207
              </option>
            </select>
          </td>
        </tr>
        <tr>
          <td align="left" id="tipo_col" style="display:none"><label><strong>Tipo de evento:</strong></label>
            <select name="tpevento" id="tpevento" style="width: 25%;" onchange="js_tpevento()">
              <option value="1">RH</option>
              <option value="2">Contabilidade</option>
            </select>
          </td>
        </tr>
        <tr id="dtpgto" style="display: none;">
          <td align="left">
            <label><strong>Data de Pagamento:</strong></label>
            <?php
            $Sdt_pgto = "Data Pagamento";
            db_inputdata('data_pgto', null, null, null, true, 'text', 1);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" id="pesquisar" name="pesquisar" value="Pesquisar" />

    <br>
    <br>
    <input type="button" id="envioESocial" name="envioESocial" value="Enviar para eSocial" />
    <input type="button" id="btnConsultar" value="Consultar Envio" onclick="js_consultar();" />
  </form>

  <div id="questionario"></div>
  <?php db_menu(); ?>
</body>

</html>

<script>
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


  (function() {

    $('pesquisar').observe("click", function pesquisar() {

      var iMatricula = $F('rh01_regist');

      if (iMatricula.trim() == '' || iMatricula.trim().match(/[^\d]+/g)) {

        alert('Informe um n�mero de Matr�cula v�lido para pesquisar.');
        return;
      }

      this.form.submit();
    });

    var oLookUpCgm = new DBLookUp($('lbl_z01_numcgm'), $('z01_numcgm'), $('z01_nomecgm'), {
      'sArquivo': 'func_cgmesocial.php',
      'oObjetoLookUp': 'func_nome'
    });

    var oLookUpMatricula = new DBLookUp($('lbl_rh01_regist'), $('rh01_regist'), $('z01_nome'), {
      'sArquivo': 'func_rhpessoal.php',
      'oObjetoLookUp': 'func_nome'
    });

    $('envioESocial').addEventListener('click', async function() {

      if ($F('anofolha').length < 4 || parseInt($("mesfolha").value) < 1 || parseInt($("mesfolha").value) > 12) {
        alert("Campo 'Compet�ncia' deve ser preenchido.");
        return false;
      }

      if ($F('tpAmb') == '') {
        alert('Selecione o ambiente de envio.');
        return;
      }

      if ($F('modo') == '') {
        alert('Selecione o tipo de envio.');
        return;
      }

      if ($F('evento') == '') {
        alert('Selecione um evento.');
        return;
      }

      const eventoValue = document.getElementById('evento').value;

      if (eventoValue === 'S1210') {
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

      const message = '<span style="color: red; font-weight: bold; font-size: 16px;">ATEN��O!</span><br><br>' +
        '<span style="font-size: 14px;">Deseja confirmar o envio do m�s <span style="font-weight: bold;">' +
        mes + '/' + ano +
        '</span></span>?';

      const result = await showCustomConfirm(message);
      if (!result) {
        return false;
      }

      let aArquivosSelecionados = new Array();
      aArquivosSelecionados.push($F('evento'));

      var tpid = document.getElementById("tpid");
      var aMatriculas = [];
      var aCgms = [];

      if (tpid.value == 2) {
        var selectobject = document.getElementById("matriculas");
        for (var iCont = 0; iCont < selectobject.length; iCont++) {
          aMatriculas.push(selectobject.options[iCont].value);
        }

        if (aMatriculas.length == 0) {
          alert('Selecione pelo menos uma matr�cula.');
          return;
        }
      }

      if (tpid.value == 1) {
        var selectobject = document.getElementById("cgms");
        for (var iCont = 0; iCont < selectobject.length; iCont++) {
          aCgms.push('' + selectobject.options[iCont].value + '');
        }

        if (aCgms.length == 0) {
          alert('Selecione pelo menos um cgm.');
          return;
        }
      }

      var parametros = {
        'exec': 'transmitir',
        'arquivos': aArquivosSelecionados,
        'empregador': $F('cboEmpregador'),
        'modo': $F('modo'),
        'tpAmb': $F('tpAmb'),
        'iAnoValidade': $F('anofolha'),
        'iMesValidade': $F('mesfolha'),
        'indapuracao': $F('indapuracao'),
        'tppgto': $F('tppgto'),
        'tpevento': $F("tpevento"),
        'matricula': aMatriculas.join(','),
        'cgm': aCgms.map(aCgms => `'${aCgms}'`).join(','), // aCgms.join(',').join(),
        'tipoid': tpid.value,
        'dtpgto': $("data_pgto").value,
      }; //Codigo Tipo::CADASTRAMENTO_INICIAL
      new AjaxRequest('eso4_esocialapi.RPC.php', parametros, function(retorno) {

        alert(retorno.sMessage);
        if (retorno.erro) {
          return false;
        }
      }).setMessage('Agendando envio para o eSocial').execute();
    });
  })();

  function js_consultar() {

    js_OpenJanelaIframe('top.corpo', 'iframe_consulta_envio', 'func_consultaenvioesocial.php', 'Pesquisa', true);
  }

  function js_adicionar_matric() {
    var selectobject = document.getElementById("matriculas");
    for (var iCont = 0; iCont < selectobject.length; iCont++) {
      if (selectobject.options[iCont].value == $F('rh01_regist')) {
        js_limpar_matric();
        return;
      }
    }
    if (!$F('z01_nome') || $F('z01_nome').toLowerCase().indexOf("n�o encontrado") > 0) {
      return;
    }
    var opt = document.createElement('option');
    opt.value = $F('rh01_regist');
    opt.innerHTML = $F('rh01_regist') + ' - ' + $F('z01_nome');
    selectobject.appendChild(opt);
    js_limpar_matric();
  }

  function js_remover_matric(select) {
    var selectobject = document.getElementById("matriculas");
    for (var iCont = 0; iCont < selectobject.length; iCont++) {
      if (selectobject.options[iCont].value == select.value) {
        selectobject.remove(iCont);
      }
    }
  }

  function js_adicionar_cgm() {
    var selectobject = document.getElementById("cgms");
    for (var iCont = 0; iCont < selectobject.length; iCont++) {
      if (selectobject.options[iCont].value == $F('z01_numcgm')) {
        js_limpar_cgm();
        return;
      }
    }
    if (!$F('z01_nomecgm') || $F('z01_nomecgm').toLowerCase().indexOf("n�o encontrado") > 0) {
      return;
    }
    var opt = document.createElement('option');
    opt.value = $F('z01_numcgm');
    opt.innerHTML = $F('z01_numcgm') + ' - ' + $F('z01_nomecgm');
    selectobject.appendChild(opt);
    js_limpar_cgm();
  }

  function js_remover_cgm(select) {
    var selectobject = document.getElementById("cgms");
    for (var iCont = 0; iCont < selectobject.length; iCont++) {
      if (selectobject.options[iCont].value == select.value) {
        selectobject.remove(iCont);
      }
    }
  }

  function js_limpar_matric() {
    $('rh01_regist').value = '';
    $('z01_nome').value = '';
  }

  function js_limpar_cgm() {
    $('z01_numcgm').value = '';
    $('z01_nomecgm').value = '';
  }

  function js_alt_evento() {
    const eventoValue = document.getElementById('evento').value;
    const indapuracaoCol = document.getElementById('indapuracao_col');
    const tppgtoCol = document.getElementById('tppgto_col');
    const tipoCol = document.getElementById('tipo_col');
    const dtpgto = document.getElementById('dtpgto');

    if (eventoValue === 'S1200' || eventoValue === 'S1202' || eventoValue === 'S1207' || eventoValue === 'S1299') {
      if (indapuracaoCol.style.display === 'none') {
        indapuracaoCol.style.display = 'inline';
      }
      if (tipoCol.style.display === 'none') {
        tipoCol.style.display = 'inline';
      }
      dtpgto.style.display = 'none';
    } else if (eventoValue === 'S1210') {
      if (tppgtoCol.style.display === 'none') {
        tppgtoCol.style.display = 'inline';
      }
      if (tipoCol.style.display === 'none') {
        tipoCol.style.display = 'inline';
      }
      dtpgto.style.display = 'inline';
    } else {
      indapuracaoCol.style.display = 'none';
      tppgtoCol.style.display = 'none';
      tipoCol.style.display = 'none';
      dtpgto.style.display = 'none';
    }
  }

  function js_dataalt() {
    if (document.getElementById('dtalteracao').style.display == 'none') {
      document.getElementById('dtalteracao').style.display = 'inline';
      return true;
    }
    document.getElementById('dtalteracao').style.display = 'none';
  }

  function js_alt_identificador(e) {
    if (e.value == 1) {
      document.getElementsByClassName('linha_cgm')[0].style.display = 'inline';
      document.getElementsByClassName('linha_cgm')[1].style.display = 'inline';
      document.getElementsByClassName('linha_matricula')[0].style.display = 'none';
      document.getElementsByClassName('linha_matricula')[1].style.display = 'none';
      document.getElementById('cgms').style.width = '78%';
    }

    if (e.value == 2) {
      document.getElementsByClassName('linha_cgm')[0].style.display = 'none';
      document.getElementsByClassName('linha_cgm')[1].style.display = 'none';
      document.getElementsByClassName('linha_matricula')[0].style.display = 'inline';
      document.getElementsByClassName('linha_matricula')[1].style.display = 'inline';
      document.getElementById('matriculas').style.width = '78%';

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
      confirmNoButton.textContent = 'N�o';
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