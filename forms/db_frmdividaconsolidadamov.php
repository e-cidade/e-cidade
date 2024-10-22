<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
//MODULO: contabilidade
$clrotulo = new rotulocampo;
$clrotulo->label("op01_sequencial");
$clrotulo->label("op01_numerocontratoopc");
$clrotulo->label("op01_dataassinaturacop");
db_app::load("widgets/windowAux.widget.js, widgets/dbmessageBoard.widget.js");
?>
<style>
  .cabecTable {
    font-size: 10;
    color: darkblue;
    background-color: #aacccc;
    border: none;
    width: 90px;
    text-align: center;
  }

  .corpoTable {
    font-size: 10;
    color: black;
    background-color: #ccddcc;
    width: 90px;
    text-align: center;
  }

  .anchor {
    text-decoration: none;
    color: blue;
    font-weight: bold;
  }

  .anchor:active {
    color: black;
    font-weight: bold;
  }

  .anchor:hover {
    color: black;
  }
</style>
<form id="frm_divida_consolidada" name="form1" method="post" action="">
  <center>
    <fieldset>
      <legend>
        <b>Nova Movimentação</b>
      </legend>
      <table border="0">
        <tr>
          <td><strong>Divida: </strong></td>
          <td>
            <?
            db_input('op01_sequencial', 13, $Iop01_sequencial, true, 'text', 3);
            db_input('op01_numerocontratoopc', 16 , $Iop01_numerocontratoopc, true, 'text', 3);
            db_input('op01_dataassinaturacop', 14, $Iop01_dataassinaturacop, true, 'text', 3);
            ?>
          </td>
        </tr>

        <tr>
          <td><strong>Credor: </strong></td>
          <td>
            <?
            db_input('op01_credor', 13, 0, true, 'text', 3);
            db_input('op01_credor_descr', 32, 0, true, 'text', 3);
            ?>
          </td>
        </tr>

        <tr>
          <td><strong>Saldo: </strong></td>
          <td>
            <?
            db_input('iSaldo', 13, 0, true, 'text', 3);            
            ?>
          </td>
        </tr>

        <tr>
          <td><strong>Movimentação: </strong></td>
          <td>
            <?
            $clempparametro = new cl_empparametro;
            $rsParametro = $clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu"), "e30_obrigadivida"));
            if (db_utils::fieldsMemory($rsParametro)->e30_obrigadivida != 't') {
              $aMovimentacoes = array(0 => 'Selecione', 1 => 'Contratação', 2 => 'Amortização', 3 => 'Cancelamento', 4 => 'Encapação', 5 => 'Atualização');
            } else {
              $aMovimentacoes = array(0 => 'Selecione', 1 => 'Contratação', 3 => 'Cancelamento', 4 => 'Encapação', 5 => 'Atualização');
            }
            db_select('op02_movimentacao', $aMovimentacoes, 1, 1, "onclick='js_validaTipo()'");
            ?>
          </td>
        </tr>

        <tr>
          <td><strong>Tipo: </strong></td>
          <td>
            <?
            $aTipo = array(0 => 'Selecione', 1 => 'Principal', 2 => 'Encargos');
            db_select('op02_tipo', $aTipo, 1, 1, "disabled='true' style='width:106px'");
            ?>
          </td>
        </tr>

        <tr>
          <td><strong>Data: </strong></td>
          <td>
            <?
            db_inputdata('op02_data', '', '', '', true, 'text', 1);
            ?>
          </td>
        </tr>

        <tr>
          <td><strong>Justificativa: </strong></td>
          <td>
            <?
            db_textarea('op02_justificativa', 3, 54, 5, true, 'text', 1, "style='width: 100%;'")
            ?>
          </td>
        </tr>

        <tr>
          <td><strong>Valor: </strong></td>
          <td>
            <?
            db_input('op02_valor', 13, 4, true, 'text', 1);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
  </center>
  <br>
  <input type="button" id="incluir" name="incluir" value="Incluir" onclick="js_incluiMovimentacao()">
  <input type="button" id="pesquisar" name="pesquisar" value="Pesquisar" onclick="js_pesquisa()">
</form>
<fieldset id='fieldsetMovimentacaoIncluir'>
  <legend><b>Movimentações</b></legend>
  <table id="movimentacaoTable" border="1">
    <thead>
      <tr>
        <th style="display: none">Sequencial</th>
        <th class="cabecTable">Movimentação</th>
        <th class="cabecTable">Tipo</th>
        <th class="cabecTable">Data</th>
        <th class="cabecTable">Valor</th>
        <th style="display: none">Justificativa</th>
        <th class="cabecTable">Ações</th>
      </tr>
    </thead>
    <tbody id="movimentacaoTableBody"></tbody>
  </table>
</fieldset>
<script>

  function js_validaTipo(alterar = false) {
    if ($('op02_movimentacao').value == 3 || $('op02_movimentacao').value == 5) {
      $('op02_tipo' + (alterar == true ? '_alterar' : '')).disabled = false;
    } else {
      $('op02_tipo' + (alterar == true ? '_alterar' : '')).disabled = true;
    }
  }

  function js_pesquisa() {
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_db_operacaodecredito', 'func_db_operacaodecredito.php?movimentacoes=1&funcao_js=parent.js_mostraDados|op01_sequencial|op01_numerocontratoopc|op01_dataassinaturacop|db_op01_credor|db_z01_nome|op01_descricaodividaconsolidada|op01_dataquitacao|dl_saldo', 'Pesquisa', true);
  }

  function js_mostraDados(op01_sequencial, op01_numerocontratoopc, op01_dataassinaturacop, db_op01_credor, z01_nome, op01_descricaodividaconsolidada, op01_dataquitacao, iSaldo) {
    db_iframe_db_operacaodecredito.hide();

    $("op01_sequencial").value = op01_sequencial;
    $("op01_numerocontratoopc").value = op01_numerocontratoopc;
    $("op01_dataassinaturacop").value = js_formatar(op01_dataassinaturacop, 'd');
    $("op01_credor").value = db_op01_credor;
    $("op01_credor_descr").value = z01_nome;
    $("iSaldo").value = js_formatar(iSaldo, 'f');
    movimentacaoTableBody.innerHTML = "";
    js_verificaMovimentacaoDivida(op01_sequencial)
  }

  function js_verificaMovimentacaoDivida(op01_sequencial) {
    var oParam = new Object();
    oParam.exec = "verificaMovimentos";
    oParam.iOperacaoCredito = op01_sequencial;
    let oAjax = new Ajax.Request("con4_operacaodecredito.RPC.php", {
      method: 'post',
      parameters: 'json=' + Object.toJSON(oParam),
      onComplete: js_preencheTabelaMovimentacao
    });
  }

  function js_preencheTabelaMovimentacao(oAjax) {
    oRetorno = eval("(" + oAjax.responseText + ")");
    if (oRetorno.status == 1) {
      aMovimentos = oRetorno.movimentos;
      for (i = 0; i < aMovimentos.length; i++) {
        js_incluirMovimentacaoTabela(aMovimentos[i]);
      }
    }
  }

  function js_incluiMovimentacao() {
    if ($("op02_movimentacao").value == 0) {
      alert("Campo Movimentação obrigatório.");
      return false;
    } else if ($("op02_data").value == '') {
      alert("Campo Data obrigatório.");
      return false;
    } else if ($("op02_valor").value == '') {
      alert("Campo Valor obrigatório.");
      return false;
    }

    if ($("op02_movimentacao").value == 3 && $("op02_justificativa").value == '') {
      alert("Campo Justificativa obrigatório para Movimentação de Cancelamento.");
      return false;
    }

    let oParam = {
      "exec": "incluiMovimentacao",
      "op02_operacaodecredito" : $("op01_sequencial").value,
      "op02_movimentacao"      : $("op02_movimentacao").value,
      "op02_tipo"              : $("op02_tipo").value,
      "op02_data"              : $("op02_data").value,
      "op02_justificativa"     : $("op02_justificativa").value,
      "op02_valor"             : $("op02_valor").value,
    }    

    oAjax = new Ajax.Request("con4_operacaodecredito.RPC.php", {
      method: 'post',
      parameters: 'json=' + Object.toJSON(oParam),
      onComplete: function(oAjax) {
        const oRetorno = eval("(" + oAjax.responseText + ")");
        alert(oRetorno.message);
        if (oRetorno.status == 1) {
          let iSaldo = 0;
          const aMovSoma = ['1', '5'];
          js_incluirMovimentacaoTabela(oRetorno.movimento)

          if (aMovSoma.includes(oRetorno.movimento.movimentacao)) {
            iSaldo = parseFloat($("iSaldo").value) + parseFloat(oRetorno.movimento.op02_valor)
          } else {
            iSaldo = parseFloat($("iSaldo").value) - parseFloat(oRetorno.movimento.op02_valor)
          }
          $("iSaldo").value = js_formatar(iSaldo, 'f');
        }
      }
    });
  }

  function js_apagaCamposEstabelecimento() {
    const aCampos = ['op02_movimentacao', 'op02_tipo', 'op02_data', 'op02_justificativa', 'op02_valor']
    for (let i = 0; i < aCampos.length; i++) {
      if (i == 0 || i == 1) {
        $(aCampos[i]).value = 0;
      } else {
        $(aCampos[i]).value = '';
      }
    }
  }

  function js_incluirMovimentacaoTabela(item) {
    js_apagaCamposEstabelecimento();
    const table = $("movimentacaoTableBody");
    const novaMovimentacao = table.insertRow();

    const sequencial = novaMovimentacao.insertCell(0);
    sequencial.innerHTML = item.op02_sequencial;
    sequencial.style = "display: none";

    const movimentacao = novaMovimentacao.insertCell(1);
    movimentacao.innerHTML = unescape(item.op02_movimentacao);
    movimentacao.className = "corpoTable";

    const tipo = novaMovimentacao.insertCell(2);
    tipo.innerHTML = item.op02_tipo == 1 ? 'Principal' : (item.op02_tipo == 2 ? 'Encargos' : '');
    tipo.className = "corpoTable";

    const data = novaMovimentacao.insertCell(3);
    data.innerHTML = js_formatar(item.op02_data, 'd');
    data.className = "corpoTable";

    const valor = novaMovimentacao.insertCell(4);
    valor.innerHTML = js_formatar(item.op02_valor, 'f');
    valor.className = "corpoTable";

    const justificativa = novaMovimentacao.insertCell(5);
    justificativa.innerHTML = item.op02_justificativa;
    justificativa.style = "display: none";

    const opcoes = novaMovimentacao.insertCell(6);
    opcoes.className = "corpoTable";
    opcoes.style = "display: flex; justify-content: space-around;"

    const botaoAlterar = document.createElement("a");
    botaoAlterar.className = 'anchor';
    botaoAlterar.innerHTML = "A";
    botaoAlterar.href = "#";
    botaoAlterar.addEventListener("click", function() {
      js_alterar(novaMovimentacao, table);
    });

    const botaoExcluir = document.createElement("a");
    botaoExcluir.className = 'anchor';
    botaoExcluir.innerHTML = "E";
    botaoExcluir.href = "#";
    botaoExcluir.addEventListener("click", function() {
      js_excluir(novaMovimentacao.rowIndex - 1, table, item.op02_sequencial, item.movimentacao, item.op02_valor, item.op02_data);
    });
    opcoes.appendChild(botaoAlterar);
    opcoes.appendChild(botaoExcluir);
  }

  function js_excluir(numLinha, table, iSequencial, iMov, iValor, sDataMov) {
    oAjax = new Ajax.Request(
      "con4_operacaodecredito.RPC.php", {
        method: 'post',
        parameters: 'json=' + Object.toJSON({
          "exec": "excluiMovimentacao",
          "op02_sequencial": iSequencial,
          "op02_movimentacao": iMov,
          "op02_valor": iValor,
          "op02_data": sDataMov
        }),
        onComplete: function(oAjax) {
          let iSaldo = 0;
          const aMovSoma = ['1', '5'];
          const oRetorno = eval("(" + oAjax.responseText + ")");
          alert(oRetorno.message);
          if (oRetorno.status == 1) {
            table.deleteRow(numLinha);
          }

          if (aMovSoma.includes(oRetorno.item.op02_movimentacao)) {
            iSaldo = parseFloat($("iSaldo").value) - parseFloat(oRetorno.item.op02_valor)
          } else {
            iSaldo = parseFloat($("iSaldo").value) + parseFloat(oRetorno.item.op02_valor)
          }
          $("iSaldo").value = js_formatar(iSaldo, 'f');
        }
      });
  }

  function js_alterar(linha, table) {
    if ($('windowwndmov_btnclose')) {
      $('windowwndmov_btnclose').click()
    }
 
    windowMov = new windowAux('wndmov', 'Alterar Movimentação', 550, 250);

    sContent = "  <table style='width: 500px; margin: 0 auto;'>"
    sContent += "    <tr>"
    sContent += "      <td>"
    sContent += "        <fieldset>"
    sContent += "          <legend>Alterar Movimentação</legend>"
    sContent += "          <table>"
    sContent += "          <tr>";
    sContent += "            <td><strong>Tipo: </strong></td>";
    sContent += "            <td>"
    sContent += `<? $aTipo = array(0 => 'Selecione', 1 => 'Principal', 2 => 'Encargos');db_select('op02_tipo_alterar', $aTipo, 1, 1); ?>`
    sContent += "</td>";
    sContent += "          </tr>";
    sContent += "          <tr>";
    sContent += "            <td><strong>Data: </strong></td>";
    sContent += "            <td>"
    sContent += "            <input name='op02_data_alterar' type='text' id='op02_data_alterar' value='' size='10' maxlength='10' autocomplete='off' onblur='js_validaDbData(this);' onkeyup='return js_mascaraData(this,event)' onfocus='js_validaEntrada(this);' onpaste='return false' ondrop='return false' class='' tabindex='3'>";
    sContent += "            <input name='op02_data_alterar_dia' type='hidden' title='' id='op02_data_alterar_dia' value='' size='2' maxlength='2'>";
    sContent += "            <input name='op02_data_alterar_mes' type='hidden' title='' id='op02_data_alterar_mes' value='' size='2' maxlength='2'>";
    sContent += "            <input name='op02_data_alterar_ano' type='hidden' title='' id='op02_data_alterar_ano' value='' size='4' maxlength='4'>  ";
    sContent += "            <input value='D' type='button' id='dtjs_op02_data_alterar' name='dtjs_op02_data_alterar' onclick=\"pegaPosMouse(event);show_calendar('op02_data_alterar','none');js_corrigeCalendario()\" tabindex='0'>";
    sContent += "</td>";
    sContent += "          </tr>";
    sContent += "          <tr>";
    sContent += "            <td><strong>Justificativa: </strong></td>";
    sContent += "            <td>"
    sContent += `<? db_textarea('op02_justificativa_alterar', 3, 54, 5, true, 'text', 1, "style='width: 100%;'") ?>`
    sContent += "</td>";
    sContent += "          </tr>";
    sContent += "          <tr>";
    sContent += "            <td><strong>Valor: </strong></td>";
    sContent += "            <td>"
    sContent += `<? db_input('op02_valor_alterar', 10, 4, true, 'text', 1); ?>`
    sContent += "</td>";
    sContent += "          </tr>";
    sContent += "          </table>"
    sContent += "        </fieldset>"
    sContent += "      </td>"
    sContent += "    </tr>"
    sContent += "    <tr>"
    sContent += "      <td class='text-center'>"
    sContent += "        <input name='alterar' type='button' id='alterar' value='Alterar' onclick='js_alterarMovimentacao(" + linha.cells[0].innerHTML + ",\"" + linha.cells[1].innerHTML + "\"," + linha.cells[4].innerHTML + "," + linha.sectionRowIndex + ")'/>"
    sContent += "      </td>"
    sContent += "    </tr>"
    sContent += "  </table>"

    windowMov.setContent(sContent);

    if (linha.cells[2].innerText == 0) {
      $("op02_tipo_alterar").disabled = true;
    }
    $("op02_tipo_alterar").value = linha.cells[2].innerText == 'Principal' ? 1 : (linha.cells[2].innerText == 'Encargos' ? 2 : 0);
    $("op02_data_alterar").value = linha.cells[3].innerText;
    $("op02_valor_alterar").value = parseFloat(linha.cells[4].innerText);
    $("op02_justificativa_alterar").value = linha.cells[5].innerText;

    windowMov.show();

    $('window' + windowMov.idWindow + '_btnclose').observe("click", function() {
      windowMov.destroy();
    });
  }

  function js_comparaDatasop02_data_alterar(dia, mes, ano) {
    var objData = document.getElementById('op02_data_alterar');
    objData.value = dia + "/" + mes + '/' + ano;
  }

  function js_corrigeCalendario() {
    $('Janiframe_data_op02_data_alterar').style.zIndex = "501";
  }

  function js_alterarMovimentacao(iSequencial, sMov, iValorAtual, linha) {

    if ($("op02_data_alterar").value == '') {
      alert("Campo Data obrigatório.");
      return false;
    } else if ($("op02_valor_alterar").value == '') {
      alert("Campo Valor obrigatório.");
      return false;
    }

    if ($("op02_movimentacao").value == 3 && $("op02_justificativa_alterar").value == '') {
      alert("Campo Justificativa obrigatório para Movimentação de Cancelamento.");
      return false;
    }


    // let iSaldo = 0;
    // const aMovSoma = ['Contratação', 'Atualização'];
    // if (aMovSoma.includes(sMov)) {
    //   iSaldo = parseFloat($("iSaldo").value) + parseFloat(iValorAtual)
    // } else {
    //   iSaldo = parseFloat($("iSaldo").value) - parseFloat(iValorAtual)
    // }
    // $("iSaldo").value = js_formatar(iSaldo, 'f');

    oAjax = new Ajax.Request(
      "con4_operacaodecredito.RPC.php", {
        method: 'post',
        parameters: 'json=' + Object.toJSON({
          "exec": "alteraMovimentacao",
          "op02_sequencial": iSequencial,
          "op02_movimentacao": sMov,
          "op02_tipo": $("op02_tipo_alterar").value,
          "op02_data": $("op02_data_alterar").value,
          "op02_justificativa": $("op02_justificativa_alterar").value,
          "op02_valor": $("op02_valor_alterar").value,
        }),
        onComplete: function(oAjax) {
          let iSaldo = 0;
          const aMovSoma = ['Contratação', 'Atualização'];
          const oRetorno = eval("(" + oAjax.responseText + ")");
          if (oRetorno.status == 1) {
            $("movimentacaoTableBody").rows[linha].cells[4].innerHTML = js_formatar(oRetorno.item.op02_valor, 'f');
            $("movimentacaoTableBody").rows[linha].cells[2].innerHTML = oRetorno.item.op02_tipo == 1 ? 'Principal' : (oRetorno.item.op02_tipo == 2 ? 'Encargos' : '');
            $("movimentacaoTableBody").rows[linha].cells[3].innerHTML = oRetorno.item.op02_data;
            $("movimentacaoTableBody").rows[linha].cells[5].innerHTML = oRetorno.item.op02_justificativa;
          }
          alert(oRetorno.message);
          $('windowwndmov_btnclose').click();
          if (aMovSoma.includes(oRetorno.item.op02_movimentacao)) {
            iSaldo = parseFloat($("iSaldo").value) - (iValorAtual - parseFloat(oRetorno.item.op02_valor));
          } else {
            iSaldo = parseFloat($("iSaldo").value) + (iValorAtual - parseFloat(oRetorno.item.op02_valor));
          }
          $("iSaldo").value = js_formatar(iSaldo, 'f');
        }
      });
  }
</script>