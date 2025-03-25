<?php
include("dbforms/db_classesgenericas.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clconvdetalhaconcedentes = new cl_convdetalhaconcedentes;

db_app::load('strings.js');

//MODULO: contabilidade
$clconvconvenios->rotulo->label();
if ($db_opcao == 1) {
  $db_action = "con1_convconvenios004.php";
} else if ($db_opcao == 2 || $db_opcao == 22) {
  $db_action = "con1_convconvenios005.php";
} else if ($db_opcao == 3 || $db_opcao == 33) {
  $db_action = "con1_convconvenios006.php";
}

require_once "classes/db_orctiporec_classe.php";

$clorctiporec = new cl_orctiporec;

$ano = db_getsession("DB_anousu");
$campos = " o15_codigo, o15_codtri, o15_descr ";
$ordem = " o15_codigo";

$sSQL = $clorctiporec->validaFontesAno($ano, $campos, $ordem);

$tpCadastros = db_utils::getCollectionByRecord(db_query($sSQL));

$aTpCadastros = array("" => "Selecione");
foreach ($tpCadastros as $tpCadastro) {
  $aTpCadastros += array($tpCadastro->o15_codigo => "$tpCadastro->o15_codtri - $tpCadastro->o15_descr");
}

$detalhamentosConvenio = null;

if(!empty($c206_sequencial)) {
  $detalhamentosConvenio = $clconvdetalhaconcedentes->getDetalhesByConvenio($c206_sequencial);
}

?>

<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>

<style>
  .cabec {
    text-align: center;
    font-size: 11;
    color: darkblue;
    border: 1px solid rgb(134, 134, 134);
    background-color: #AACCCC;
  }

  .corpo {
    text-align: center;
    font-size: 11;
    color: black;
    border: 1px solid rgb(134, 134, 134);
    background-color: #CCDDCC;
  }

  .table-container {
    width: 490px; 
    height: 150px;
    overflow-y: scroll; 
    border: 1px solid #ccc;
  }

  .table-container table {
    width: 100%;
    border-collapse: collapse;
  }

  .btn-excluir {
    font-size: 11px;
    background-color: #CCDDCC;
    text-decoration: underline;
    border-radius: 3px;
    border: 2px;
    border-color: black;
    cursor: pointer;
    color: blue;
  }

  .btn-alterar {
    font-size: 11px;
    background-color: #CCDDCC;
    text-decoration: underline;
    border-radius: 3px;
    border: 2px;
    border-color: black;
    cursor: pointer;
    color: blue;
  }

  .vlr {
    text-align: right;
  }
</style>

<form name="form1" method="post" action="<?= $db_action ?>">
  <center>
    <table border="0">
      <tr>
        <td nowrap title="<?= @$Tc206_sequencial ?>">
          <?= @$Lc206_sequencial ?>
        </td>
        <td>
          <?php
          db_input('c206_sequencial', 10, $Ic206_sequencial, true, 'text', 3, "")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?= @$Tc206_datacadastro ?>">
          <?= @$Lc206_datacadastro ?>
        </td>
        <td>
          <?php
          db_inputdata('c206_datacadastro', @$c206_datacadastro_dia, @$c206_datacadastro_mes, @$c206_datacadastro_ano, true, 'text', $db_opcao, "")
          ?>
        </td>
      </tr>
      <?php
      $c206_instit = db_getsession("DB_instit");
      db_input('c206_instit', 12, $Ic206_instit, true, 'hidden', $db_opcao, "")
      ?>
      <tr>
        <td nowrap title="<?= @$Tc206_nroconvenio ?>">
          <?= @$Lc206_nroconvenio ?>
        </td>
        <td>
          <?php
          db_input('c206_nroconvenio', 51, $Ic206_nroconvenio, true, 'text', $db_opcao, "")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?= @$Tc206_objetoconvenio ?>">
          <?= @$Lc206_objetoconvenio ?>
        </td>
        <td>
          <?php
          db_textarea('c206_objetoconvenio', 6, 50, '', true, "text", $db_opcao, "onkeyup='alterarContador(this);'", "", "", 500);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?= @$Tc206_tipocadastro ?>">
          <?= @$Lc206_tipocadastro ?>
        </td>
        <td>
          <?php
          db_select("c206_tipocadastro", $aTpCadastros, true, $iOpcao, 'style="width: 373px;"');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?= @$Tc206_dataassinatura ?>">
          <?= @$Lc206_dataassinatura ?>
        </td>
        <td>
          <?php
          db_inputdata('c206_dataassinatura', @$c206_dataassinatura_dia, @$c206_dataassinatura_mes, @$c206_dataassinatura_ano, true, 'text', $db_opcao, "")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?= @$Tc206_datainiciovigencia ?>">
          <?= @$Lc206_datainiciovigencia ?>
        </td>
        <td>
          <?php
          db_inputdata('c206_datainiciovigencia', @$c206_datainiciovigencia_dia, @$c206_datainiciovigencia_mes, @$c206_datainiciovigencia_ano, true, 'text', $db_opcao, "")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?= @$Tc206_datafinalvigencia ?>">
          <?= @$Lc206_datafinalvigencia ?>
        </td>
        <td>
          <?php
          db_inputdata('c206_datafinalvigencia', @$c206_datafinalvigencia_dia, @$c206_datafinalvigencia_mes, @$c206_datafinalvigencia_ano, true, 'text', $db_opcao, "")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?= @$Tc206_vlconvenio ?>">
          <?= @$Lc206_vlconvenio ?>
        </td>
        <td>
          <?php
          db_input('c206_vlconvenio', 14, $Ic206_vlconvenio, true, 'text', $db_opcao, "")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?= @$Tc206_vlcontrapartida ?>">
          <?= @$Lc206_vlcontrapartida ?>
        </td>
        <td>
          <?php
          db_input('c206_vlcontrapartida', 14, $Ic206_vlcontrapartida, true, 'text', $db_opcao, "")
          ?>
        </td>
      </tr>

      <tr>
        <td>&nbsp;</td>
      </tr>

      <tr>
        <td><b>Detalhamento de Concedentes:</b></td>
        <td></td>
      </tr>

      <tr>
        <td nowrap title="<?= isset($Tc207_valorconcedido) ? $Tc207_valorconcedido : null ?>">
          <b>Sequencial:</b>
        </td>
        <td>
          <input id="c207_sequencial" type="text" readonly="" style="background-color:#DEB887;" size="14">
        </td>
      </tr>

      <tr>
        <td nowrap title="c207_nrodocumento">
          <?php db_ancora('Número do Documento:', "js_pesquisaz01_numcgm(true);", 1); ?>
        </td>
        <td>
          <?php db_input('c207_nrodocumento', 14, $Ic207_nrodocumento, true, 'text', 3, "") ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="c207_esferaconcedente">
          <b>Esfera do Concedente:</b>
        </td>
        <td>
          <?php
          $x = array("1" => "Federal", "2" => "Estadual", "3" => "Municipal", "4" => "Exterior", "5" => "Instituição Privada");

          if (db_getsession('DB_anousu') > 2021) {
            $x = array("1" => "Federal", "2" => "Estadual", "3" => "Municipal", "5" => "Instituição Privada");
          }

          db_select('c207_esferaconcedente', $x, true, $db_opcao, 'style="width: 6.75rem;"');
          ?>

        </td>
      </tr>

      <tr>
        <td nowrap title="<?= isset($Tc207_valorconcedido) ? $Tc207_valorconcedido : null ?>">
          <b>Valor a ser concedido:</b>
        </td>
        <td>
          <input type="text" value="" name="c207_valorconcedido" id="c207_valorconcedido" maxlength="14" size="14" 
            onblur="js_ValidaMaiusculo(this, 'f', event);" oninput="js_ValidaCampos(this, 4, 'Valor a ser concedido', 'f', 'f', event);"
            onkeydown="return js_controla_tecla_enter(this, event);" autocomplete="off" 
            style="<?php echo ($db_opcao == 3) ? 'background-color:#DEB887;' : null ?>"
            <?php echo ($db_opcao == 3) ? 'readonly=""' : null?>
          > &nbsp;
          <input name="incluir_detalhamento" type="button" id="incluir_detalhamento" value="Inserir Detalhamento" 
            onclick="appendDetail()" 
            <?php if($db_opcao == 3) { ?> 
              style="display: none" <?php
            } ?>
          >
        </td>
      </tr>

      <tr>
        <td>&nbsp;</td>
      </tr>

      <input type="hidden" id="db_option_hidden" value="<?php echo $db_opcao ?>"> 
      
    </table>

    <table>
      <tr>
        <td valign="top" align="center">
          <fieldset>
            <legend align="center"> <b>DETALHAMENTOS LANÇADOS</b> </legend>

            <div class="table-container">
              <table class="table-detalhamento" cellspacing="0px">

                <tr>
                  <th class="cabec"> Sequencial </th>
                  <th class="cabec"> Número do Documento </th>
                  <th class="cabec"> Esfera do Concedente </th>
                  <th class="cabec"> Valor a ser concedido </th>
                  <th class="cabec"> Opções</th>
                </tr>

              </table>
            </div>

          </fieldset>
        </td>
      </tr>

      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>

  </center>
  <input id="actionButton" name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>" type="button" id="db_opcao" value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?>>
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
  <input type="hidden" name="pacote_detalhamentos" id="pacote_detalhamentos">
</form>
<script>

  document.addEventListener("DOMContentLoaded", function () {

    var btnAction = document.getElementById('actionButton');
    var db_option = <?php echo $db_opcao ?>;

    btnAction.addEventListener('click', function() {

      var pacote_detalhamentos = document.getElementById("pacote_detalhamentos").value;
      var message = db_option == 1 ? 'Inclusão' : 'Alteração';
      var action  = '';

      if(pacote_detalhamentos == ''){

        if(db_option == 1 || db_option == 2 || db_option == 22) {
          alert(message + ' abortada \n\n Detalhamento dos concedentes não informado.');
          btnAction.type = 'button';
          return false;
        }
      }

      if(pacote_detalhamentos != ''){
        btnAction.type = 'submit';
        btnAction.click();
      }
    
    });

    function loadDetalhamentos() {

      var detalhamentos = <?php echo json_encode($detalhamentosConvenio) ?>;

      if(detalhamentos != false) {

        const table  = document.querySelector(".table-detalhamento");

        var dbOption = document.getElementById("db_option_hidden").value;
        var disableButton = null;

        if(dbOption == 3) {
          disableButton = 'disabled';
        }

        detalhamentos.forEach(function (index) {

          switch (index["c207_esferaconcedente"]) {
            case '1': index["c207_esferaconcedente"] = 'Federal';
              break;
            case '2': index["c207_esferaconcedente"] = 'Estadual';
              break;
            case '3': index["c207_esferaconcedente"] = 'Municipal';
              break;
            case '4': index["c207_esferaconcedente"] = 'Exterior';
              break;
            case '5': index["c207_esferaconcedente"] = 'Instituição Privada';
              break;
            default:  index["c207_esferaconcedente"] = 'Federal';
              break;
          }

          var newRow   = document.createElement("tr");
          
          newRow.innerHTML = `
            <td class="corpo">` + index["c207_sequencial"] + `</td>
            <td class="corpo">` + index["c207_nrodocumento"] + `</td>
            <td class="corpo">` + index["c207_esferaconcedente"] + `</td>
            <td class="corpo vlr">` + js_formatar(index["c207_valorconcedido"], "f") + `</td>
            <td class="corpo">
                <button class="btn-alterar" ` + disableButton + ` onclick="alterDetail(this)">A</button>
                <button class="btn-excluir" ` + disableButton + ` onclick="removeDetail(this)">E</button>
            </td>
          `;

          table.appendChild(newRow);
        });

        updateJsonDetails();
      }
    }

    loadDetalhamentos();
  });

  function js_pesquisa() {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_convconvenios', 'db_iframe_convconvenios', 'func_convconvenios.php?funcao_js=parent.js_preenchepesquisa|c206_sequencial', 'Pesquisa', true, '0', '1');
  }

  function js_preenchepesquisa(chave) {
    db_iframe_convconvenios.hide();
    <? if ($db_opcao != 1) {
      echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
    } ?>
  }

  function alterarContador(valor) {
    var qnt = valor.length;
    if (qnt >= 500) {
      document.getElementById('c206_objetoconvenioobsdig').value = 500;
    }
  }

  alterarContador(document.getElementById('c206_objetoconvenio').value)

  function appendDetail() {

    const table = document.querySelector(".table-detalhamento");

    var c207_sequencial       = document.getElementById("c207_sequencial").value;
    var c207_nrodocumento     = document.getElementById("c207_nrodocumento").value;
    var seletor_esfera        = document.getElementById("c207_esferaconcedente");
    var c207_esferaconcedente = seletor_esfera.options[seletor_esfera.selectedIndex].text;
    var c207_valorconcedido   = document.getElementById("c207_valorconcedido").value;

    if(c207_nrodocumento == "" || c207_nrodocumento == 'undefined') {
      alert("Número do Documento é obrigatório!");
      return false;
    }

    if(c207_valorconcedido == "" || c207_valorconcedido == 'undefined') {
      alert("Valor a ser concedido é obrigatório!");
      return false;
    }

    const newRow = document.createElement("tr");

    newRow.innerHTML = `
      <td class="corpo">` + c207_sequencial + `</td>
      <td class="corpo">` + c207_nrodocumento + `</td>
      <td class="corpo">` + c207_esferaconcedente + `</td>
      <td class="corpo vlr">` + js_formatar(c207_valorconcedido,"f") + `</td>
      <td class="corpo">
          <button class="btn-alterar" onclick="alterDetail(this)">A</button>
          <button class="btn-excluir" onclick="removeDetail(this)">E</button>
      </td>
    `;

    table.appendChild(newRow);

    clearFields();
    updateJsonDetails();
  }

  function removeDetail(selector) {

    var dbOption = document.getElementById('db_option_hidden').value;

    if(dbOption != 3) {
      var row = selector.closest('tr');

      if(row) {
        row.remove();
        updateJsonDetails();
      } 
    }
  }

  function alterDetail(selector) {

    var dbOption = document.getElementById('db_option_hidden').value;

    if(dbOption != 3) {

      var row = selector.closest('tr');

      if(row) {

        const cells = row.querySelectorAll("td");

        var data = [];

        if(cells.length > 0) { 
          cells.forEach((cell) => {
            data.push(cell.textContent.trim());
          });
          row.remove();
        }

        var esfera = 1;

        switch (data[2]) {
          case 'Federal':   esfera = 1;
            break;
          case 'Estadual':  esfera = 2;
            break;
          case 'Municipal': esfera = 3;
            break;
          case 'Exterior':  esfera = 4;
            break;
          case 'Instituição Privada': esfera = 5;
            break;
          default: esfera = 1;
            break;
        }

        document.getElementById("c207_sequencial").value       = data[0];
        document.getElementById("c207_nrodocumento").value     = data[1];
        document.getElementById("c207_esferaconcedente").value = esfera;
        document.getElementById("c207_valorconcedido").value   = data[3].replace(/\./g, '').replace(/,/g, '.');

        updateJsonDetails();
      } 
    }
  }

  function js_pesquisaz01_numcgm(mostra) {
    if(mostra==true) {
      js_OpenJanelaIframe('','func_nome','func_nome.php?funcao_js=parent.js_mostranumcgm1|z01_cgccpf&z01_tipcre_cnpj','Pesquisa',true);
    }
  }

  function js_mostranumcgm1(chave1) {
    document.form1.c207_nrodocumento.value = chave1;
    func_nome.hide();
  }

  function clearFields() {
    document.getElementById("c207_sequencial").value = ""; 
    document.getElementById("c207_nrodocumento").value = ""; 
    document.getElementById("c207_esferaconcedente").selectedIndex = 0;
    document.getElementById("c207_valorconcedido").value = "";
  }

  function updateJsonDetails() {

    var package = document.getElementById("pacote_detalhamentos");

    const table = document.querySelector(".table-detalhamento");
    const rows  = table.querySelectorAll("tbody tr");

    const tableData = [];

    rows.forEach((row) => {

      const cells   = row.querySelectorAll("td");
      const rowData = [];

      if(cells.length > 0) { 

        cells.forEach((cell) => {
          rowData.push(cell.textContent.trim());
        });

        tableData.push( formatRowData(rowData) );
      }
    });

    package.value = tableData;
  }

  function formatRowData(rowData) {

    var rowFormatted = {
      'c207_sequencial':       rowData[0],
      'c207_nrodocumento':     rowData[1],
      'c207_esferaconcedente': rowData[2], 
      'c207_valorconcedido':   rowData[3]
    };

    return JSON.stringify(rowFormatted);
  }

</script>