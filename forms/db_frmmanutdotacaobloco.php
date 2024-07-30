<style type="text/css">
  .linhagrid.left {
    text-align: left;
  }

  .linhagrid input[type='text'] {
    width: 100%;
  }

  .normal:hover {
    background-color: #eee;
  }

  .ordenador:hover {
    background-color: darkgray;
    cursor: pointer;
  }

  .main_body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    width: 1000px;
    margin: auto auto;
  }

  .DBGrid {
    width: 100%;
    border: 1px solid #888;
  }

  .buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-left: -80px;
    margin-top: 5px;
  }
</style>

<div class="main_body">
  <form action="" name="form1" method="post">
    <fieldset id="filtrosFieldset" style="padding-left: 110px">
      <legend> Filtros </legend>
      <table>
        <tr>
          <td><?= $Lo40_orgao ?></td>
          <td>
            <?
            $result = $clorcorgao->sql_record($clorcorgao->sql_query(null, null, "o40_orgao,o40_descr", "o40_orgao", "o40_anousu=" . db_getsession("DB_anousu") . " and o40_instit=" . db_getsession("DB_instit")));
            db_selectrecord("o40_orgao", $result, true, 2, "", "", "", "0", $onchange = " js_filtra('o40_orgao');");
            ?>
          </td>
        </tr>
        <tr>
          <td><?= $Lo41_unidade ?></td>
          <td>
            <?
            if (isset($o40_orgao) && $o40_orgao > 0) {
              $result = $clorcunidade->sql_record($clorcunidade->sql_query(null, null, null, "o41_unidade,o41_descr", "o41_unidade", "o41_anousu=" . db_getsession("DB_anousu") . "  and o41_orgao=$o40_orgao "));
              $o41_unidade = 0;
              db_selectrecord("o41_unidade", $result, true, 2, "", "", "", ($clorcunidade->numrows > 1 ? "0" : ""), $onchange = "  js_filtra('o41_unidade');");
            } else {
              db_input("o41_unidade", 6, 0, true, "hidden", 0);
            }
            ?>
          </td>
        </tr>
        <td><strong>Função: </strong></td>
        <td>
          <?
          $dbwhere = "";
          if (isset($o40_orgao) && $o40_orgao > 0) {
            $dbwhere .= " and o58_orgao= $o40_orgao ";
          }
          if (isset($o41_unidade) && $o41_unidade > 0) {
            $dbwhere .= " and o58_unidade = $o41_unidade ";
          }
          $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null, null, "distinct o52_funcao,o52_descr", "o52_funcao", "o58_anousu=" . db_getsession("DB_anousu") . " and o58_instit=" . db_getsession("DB_instit") . " $dbwhere"));
          db_selectrecord("o52_funcao", $result, true, 2, "", "", "", ($clorcdotacao->numrows > 1 ? "0" : ""), $onchange = "  js_filtra('o52_funcao');");
          ?>
        </td>
        <tr>
          <td><strong>SubFunção: </strong></td>
          <td>
            <?
            $dbwhere = "";
            if (isset($o40_orgao) && $o40_orgao > 0) {
              $dbwhere .= " and o58_orgao= $o40_orgao ";
            }
            if (isset($o41_unidade) && $o41_unidade > 0) {
              $dbwhere .= " and o58_unidade = $o41_unidade ";
            }
            if (isset($o52_funcao) && $o52_funcao > 0) {
              $dbwhere .= " and  o58_funcao = $o52_funcao ";
            }
            $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null, null, "distinct o53_subfuncao,o53_descr", "o53_subfuncao", "o58_anousu=" . db_getsession("DB_anousu") . " and o58_instit=" . db_getsession("DB_instit") . " $dbwhere"));
            db_selectrecord("o53_subfuncao", $result, true, 2, "", "", "", ($clorcdotacao->numrows > 1 ? "0" : ""), $onchange = "  js_filtra('o53_subfuncao');");
            ?>
          </td>
        </tr>
        <tr>
          <td><strong>Programa: </strong></td>
          <td>
            <?
            $dbwhere = "";
            if (isset($o40_orgao) && $o40_orgao > 0) {
              $dbwhere .= "  and o58_orgao= $o40_orgao ";
            }
            if (isset($o41_unidade) && $o41_unidade > 0) {
              $dbwhere .= " and o58_unidade = $o41_unidade ";
            }
            if (isset($o52_funcao) && $o52_funcao > 0) {
              if ($dbwhere != "")
                $dbwhere .= "  and o58_funcao = $o52_funcao ";
            }

            if (isset($o53_subfuncao) && $o53_subfuncao > 0) {
              $dbwhere .= "  and o58_subfuncao = $o53_subfuncao ";
            }

            $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null, null, "distinct o54_programa,o54_descr", "o54_programa", "o58_anousu=" . db_getsession("DB_anousu") . " and o58_instit=" . db_getsession("DB_instit") . " $dbwhere"));
            db_selectrecord("o54_programa", $result, true, 2, "", "", "", ($clorcdotacao->numrows > 1 ? "0" : ""), $onchange = "  js_filtra('o54_programa');");
            ?>
          </td>
        </tr>
        <tr>
          <td><?= $Lo58_projativ ?></td>
          <td>
            <?
            $dbwhere = "";
            if (isset($o40_orgao) && $o40_orgao > 0) {
              $dbwhere .= "  and o58_orgao= $o40_orgao ";
            }
            if (isset($o41_unidade) && $o41_unidade > 0) {
              $dbwhere .= " and o58_unidade = $o41_unidade ";
            }
            if (isset($o52_funcao) && $o52_funcao > 0) {
              $dbwhere .= "  and o58_funcao = $o52_funcao ";
            }
            if (isset($o53_subfuncao) && $o53_subfuncao > 0) {
              $dbwhere .= "  and o58_subfuncao = $o53_subfuncao ";
            }
            if (isset($o54_programa) && $o54_programa > 0) {
              $dbwhere .= "  and o58_programa = $o54_programa ";
            }
            $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null, null, "distinct o55_projativ,o55_descr", "o55_projativ", "o58_anousu=" . db_getsession("DB_anousu") . " and o58_instit=" . db_getsession("DB_instit") . " $dbwhere"));
            db_selectrecord("o55_projativ", $result, true, 2, "", "", "", ($clorcdotacao->numrows > 1 ? "0" : ""), $onchange = "  js_filtra('o55_projativ');");
            ?>
          </td>
        </tr>
        <tr>
          <td><?= $Lo58_codele ?></td>
          <td>
            <?
            $dbwhere = "";
            if (isset($o40_orgao) && $o40_orgao > 0) {
              $dbwhere .= " and o58_orgao= $o40_orgao ";
            }
            if (isset($o41_unidade) && $o41_unidade > 0) {
              $dbwhere .= " and o58_unidade = $o41_unidade ";
            }
            if (isset($o52_funcao) && $o52_funcao > 0) {
              $dbwhere .= " and o58_funcao = $o52_funcao ";
            }
            if (isset($o53_subfuncao) && $o53_subfuncao > 0) {
              $dbwhere .= " and  o58_subfuncao = $o53_subfuncao ";
            }
            if (isset($o54_programa) && $o54_programa > 0) {
              $dbwhere .= " and o58_programa = $o54_programa ";
            }
            if (isset($o55_projativ) && $o55_projativ > 0) {
              $dbwhere .= " and  o58_projativ = $o55_projativ ";
            }
            $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null, null, "distinct o56_elemento,o56_descr", "o56_elemento", "o58_anousu=" . db_getsession("DB_anousu") . " and o58_instit=" . db_getsession("DB_instit") . " $dbwhere"));
            db_selectrecord("o56_elemento", $result, true, 2, "", "", "", ($clorcdotacao->numrows > 1 ? "0" : ""), " js_filtra('o56_elemento');");
            ?>
          </td>
        </tr>
        <tr>
          <td><strong>Fonte: </strong></td>
          <td>
            <?
            $dbwhere = "";
            if (isset($o40_orgao) && $o40_orgao > 0) {
              $dbwhere .= " and o58_orgao= $o40_orgao ";
            }
            if (isset($o41_unidade) && $o41_unidade > 0) {
              $dbwhere .= " and o58_unidade = $o41_unidade ";
            }
            if (isset($o52_funcao) && $o52_funcao > 0) {
              $dbwhere .= " and o58_funcao = $o52_funcao ";
            }
            if (isset($o53_subfuncao) && $o53_subfuncao > 0) {
              $dbwhere .= " and o58_subfuncao = $o53_subfuncao ";
            }
            if (isset($o54_programa) && $o54_programa > 0) {
              $dbwhere .= " and o58_programa = $o54_programa ";
            }
            if (isset($o55_projativ) && $o55_projativ > 0) {
              $dbwhere .= " and o58_projativ = $o55_projativ ";
            }
            if (isset($o56_elemento) && $o56_elemento > 0) {
              $dbwhere .= " and o56_elemento = '$o56_elemento' ";
            }
            $dbwhere .= " and (o15_datalimite is null or o15_datalimite > '" . date('Y-m-d', db_getsession('DB_datausu')) . "')";
            $sSqlRecursos = $clorcdotacao->sql_query(null, null, "distinct o15_codigo,o15_descr", "o15_codigo", "o58_anousu=" . db_getsession("DB_anousu") . " and o58_instit=" . db_getsession("DB_instit") . " $dbwhere");
            $result = $clorcdotacao->sql_record($sSqlRecursos);
            db_selectrecord("o58_codigo", $result, true, 2, "", "", "", ($clorcdotacao->numrows > 1 ? "0" : ""), " js_filtra('o58_codigo');");
            ?>
          </td>
        </tr>
        <tr>
          <td width="25%" align="left" nowrap title="<?= $To58_coddot ?>">
            <?= $Lo58_coddot ?>
          </td>
          <td width="75%" align="left" nowrap>
            <? db_input("o58_coddot", 15, $Io58_coddot, true, "text", 4, "", "o58_coddot"); ?>
          </td>
        </tr>
        <tr>
        <tr>
          <td colspan="2" align="center">
            <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
            <input name="limpar" type="reset" id="limpar" value="Limpar" onclick="js_limparForm()">
          </td>
        </tr>
      </table>
    </fieldset>
    <fieldset id="edicaoFieldset" style="padding-left: 110px; margin-bottom: 5px">
      <legend>Edição</legend>
        <table>
          <tr>
            <td><strong>Alterar: </strong></td>
            <td>
              <?
              $aOpcoes = array('0' => 'Selecione...', 'Unidade' => 'Orgão/Unidade', 'Funcao' => 'Função', 'Subfuncao' => 'Subfunção', 'Programa' => 'Programa' , 'Projetos' => 'Projeto/Atividade', 'Elemento' => 'Elemento', 'Fonte' => 'Fonte');
              db_select('selectAlterar', $aOpcoes ,true,1);              
              ?>
            </td>
            <tr id="linhaUnidade" style="display:none">
              <td nowrap title="<?=@$To58_unidade?>">
                <?
                  db_ancora('Órgão/Unidade: ',"js_pesquisaUnidade(true);",1);
                ?>
              </td>
              <td> 
                <?
                  db_input('edicaoUnidade',16,$Io58_unidade,true,'text',3);
                  db_input('edicaoUnidadeDescr',55,$Io41_descr,true,'text',3,'');
                  db_input('edicaoUnidadeOrgao',11,$Io58_orgao,true,'hidden',3,'');
                ?>
              </td>
            </tr>
            <tr id="linhaFuncao" style="display:none">
              <td nowrap title="<?=@$To58_funcao?>">
                <?
                  db_ancora('Função: ',"js_pesquisaFuncao(true);",1);
                ?>
              </td>
              <td> 
                <?
                  db_input('edicaoFuncao',16,$Io58_funcao,true,'text',1," onchange='js_pesquisaFuncao(false);'");
                  db_input('edicaoFuncaoDescr',55,$Io52_descr,true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr id="linhaSubfuncao" style="display:none">
              <td nowrap title="<?=@$To58_subfuncao?>">
                <?
                  db_ancora('SubFunção: ',"js_pesquisaSubfuncao(true);",1);
                ?>
              </td>
              <td> 
                <?
                  db_input('edicaoSubfuncao',16,$Io58_subfuncao,true,'text',1," onchange='js_pesquisaSubfuncao(false);'");
                  db_input('edicaoSubfuncaoDescr',55,$Io53_descr,true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr id="linhaPrograma" style="display:none">
              <td nowrap title="<?=@$To58_programa?>">
                <?
                  db_ancora('Programa: ',"js_pesquisaPrograma(true);",1);
                ?>
              </td>
              <td> 
                <?
                  db_input('edicaoPrograma',16,$Io58_programa,true,'text',1," onchange='js_pesquisaPrograma(false);'");
                  db_input('edicaoProgramaDescr',55,$Io54_anousu,true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr id="linhaProjetos" style="display:none">
              <td nowrap title="<?=@$To58_projativ?>">
                <?
                  db_ancora('Projetos / Atividades: ',"js_pesquisaProjetos(true);",1);
                ?>
              </td>
              <td> 
                <?
                  db_input('edicaoProjetos',16,$Io58_projativ,true,'text',1," onchange='js_pesquisaProjetos(false);'");
                  db_input('edicaoProjetosDescr',55,$Io55_descr,true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr id="linhaElemento" style="display:none">
              <td nowrap title="<?=@$To58_elemento?>">
                <?
                  db_ancora('Código Elemento: ',"js_pesquisaElemento(true);",1);
                ?>
              </td>
              <td> 
                <?
                  db_input('edicaoElemento',16,$Io56_elemento,true,'text',1," onchange='js_pesquisaElemento(false);'");
                  db_input('edicaoElementoDescr',55,$Io56_descr,true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr id="linhaFonte" style="display:none">
              <td nowrap title="<?=@$To58_codigo?>">
                <?
                  db_ancora('Fonte: ',"js_pesquisaFonte(true);",1);
                ?>
              </td>
              <td> 
                <?
                  db_input('edicaoFonte',16,$Io58_codigo,true,'text',1," onchange='js_pesquisaFonte(false);'");
                  db_input('edicaoFonteDescr',55,$Io15_descr,true,'text',3,'');
                ?>
              </td>
            </tr>
          </tr>
        </table>
        <div class="buttons">
          <input name="alterarRegistro" type="button" id="alterarRegistro" value="Alterar Registros" onclick="js_alterarRegistro()">
          <input name="alterarValores" type="button" id="alterarValores" value="Alterar Valores" onclick="js_alterarValores()">
          <input name="excluir" type="button" id="excluir" value="Excluir" onclick="js_excluir()">
        </div>
    </fieldset>
    <table class="DBGrid" id="DBGrid">
      <thead>
        <tr>
          <th class="table_header ordenador" style="width: 30px; cursor: pointer;" onclick="js_marcarTodos();">M</th>
          <th class="table_header ordenador" style="width: 560px;">Estrutural da Dotação <span style="font-size:medium">&#x2195;</span></th>
          <th class="table_header ordenador" style="width: 50px;">Reduzido <span style="font-size:medium">&#x2195;</span></th>
          <th class="table_header ordenador" style="width: 100px;">Valor Previsto <span style="font-size:medium">&#x2195;</span></th>
        </tr>
      </thead>
      <tbody>
        <?php
        $aDotacoes = db_utils::getCollectionByRecord($rsDotacoes);
        $iValorTotal = 0;
        foreach ($aDotacoes as $indice => $oItem) :
          $iValorTotal += $oItem->o58_valor;
        ?>

          <tr class="normal" id="linhaDotacao[<?= $indice ?>]">
            <td class="table_header">
              <input type="checkbox" class="marca_itens" name="aItensMarcados[<?= $oItem->o58_coddot ?>]" id="aItensMarcados[<?= $oItem->o58_coddot ?>]" value="<?= $oItem->o58_coddot ?>" onchange="js_marcarItem(this)">
              <input type="hidden" id="aItensDotacao[<?= $oItem->o58_coddot ?>][indice]" value="<?= $indice ?>">
              <input type="hidden" id="linhaDotacao[<?= $indice ?>][exercicio]" value="<?= $oItem->o58_anousu ?>">
            </td>
            <td id="linhaDotacao[<?= $indice ?>][elemento]" class="linhagrid">
              <?= $oItem->dl_estrutural ?>
            <td id="linhaDotacao[<?= $indice ?>][reduzido]" class="linhagrid">
              <?= $oItem->o58_coddot ?>
            </td>
            <td id="linhaDotacao[<?= $indice ?>][valor]" class="linhagrid left">
              <?
              $orcAprovado = $o134_orcamentoaprovado == 't' ? 3 : 1;
              $varNome = 'o58_valor_' . $oItem->o58_coddot;
              $$varNome = trim(db_formatar($oItem->o58_valor, 'f'));
              db_input($varNome, 10, 5, true, 'text', $orcAprovado, 'onchange="js_validar(this,false,' . $oItem->o58_coddot . ')"')
              ?>
              <?
              $varNome = 'o58_valor_' . $oItem->o58_coddot . '_atual';
              $$varNome = trim(db_formatar($oItem->o58_valor, 'f'));
              db_input($varNome, 10, 5, true, 'hidden', 1)
              ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfooter>
        <th colspan="12" id="table_footer" class="table_header th_footer">Valor Total Previsto: <?= db_formatar($iValorTotal, 'f') ?></th>
      </tfooter>
    </table>
  </form>
</div>
<script>
  var oDBToogleFiltros = new DBToogle('filtrosFieldset', true);
  var oDBToogleFiltros = new DBToogle('edicaoFieldset', true);

  $('o40_orgao').style.width = 120;
  $('o40_orgaodescr').style.width = 440;

  $('o52_funcao').style.width = 120;
  $('o52_funcaodescr').style.width = 440;

  $('o53_subfuncao').style.width = 120;
  $('o53_subfuncaodescr').style.width = 440;

  $('o54_programa').style.width = 120;
  $('o54_programadescr').style.width = 440;

  $('o55_projativ').style.width = 120;
  $('o55_projativdescr').style.width = 440;

  $('o56_elemento').style.width = 120;
  $('o56_elementodescr').style.width = 440;

  $('o58_codigo').style.width = 120;
  $('o58_codigodescr').style.width = 440;

  $('o41_unidade').style.width = 120;
  if ($('o41_unidadedescr')) {
    $('o41_unidadedescr').style.width = 440;
  }

  $('selectAlterar').style.width = 127;

  const orcAprovado = "<? echo $o134_orcamentoaprovado; ?>"

  if (orcAprovado == 't') {
    $('excluir').disabled = 'true';
    $('alterarValores').disabled = 'true';
  }


  document.addEventListener('DOMContentLoaded', () => {
    const tabela = document.getElementById('DBGrid');
    const cabeçalhos = tabela.querySelectorAll('thead th');
    const estadoOrdenacao = Array(cabeçalhos.length).fill(false);

    cabeçalhos.forEach((th, indice) => {
      if (indice !== 0 && indice < cabeçalhos.length) {
        th.addEventListener('click', () => {
          resolverDepoisDe2Segundos();
          const ordemCrescente = estadoOrdenacao[indice];
          js_ordena(indice, ordemCrescente);
          estadoOrdenacao[indice] = !ordemCrescente;
        });
      }
    });
  });

  function js_ordena(indice, ordemCrescente = true) {
    const tbody = document.getElementById('DBGrid').querySelector('tbody');
    const linhas = Array.from(tbody.querySelectorAll('tr.normal'));
    const linhasOrdenadas = linhas.sort((a, b) => {
      let celulaA = a.children[indice];
      let celulaB = b.children[indice];

      if (!celulaA || !celulaB) return false;

      if (indice != 3) {
        celulaA = celulaA.innerText;
        celulaB = celulaB.innerText;
      } else {
        celulaA = parseFloat(celulaA.querySelector('input').value.replace(/\./g, '').replace(',', '.'));
        celulaB = parseFloat(celulaB.querySelector('input').value.replace(/\./g, '').replace(',', '.'));
      }
      let comparacao = 0;

      if (!isNaN(celulaA) && !isNaN(celulaB)) {
        comparacao = Number(celulaA) - Number(celulaB);
      } else {
        comparacao = celulaA.localeCompare(celulaB);
      }

      return ordemCrescente ? comparacao : -comparacao;
    });

    tbody.innerHTML = '';

    linhasOrdenadas.forEach(linha => tbody.appendChild(linha));
  }

  function resolverDepoisDe2Segundos() {
    js_divCarregando('', 'div_carregando');
    setTimeout(() => {
      js_removeObj('div_carregando');
    }, 2000);
  }

  function js_validar(item, retorno = false, iCodDot = null) {
    const iValorAtual = document.getElementById(item.id + "_atual").value;
    const regex = /^(\d{1,3}(\.\d{3})*|\d+),\d{2}$/;
    const regexSemVirgula = /^\d+(\.\d{3})*$/;
    let valor = item.value;
    
    if (!regex.test(valor)) {
      if (regexSemVirgula.test(valor)) {
        valor = js_formatar(valor,'f');
      } else {
        item.value = iValorAtual;
        return alert("Valor inválido!");
      }
    }

    item.value = valor;
    $(item.id + "_atual").value = valor;

    if (retorno) {
      let nValor = valor.replace(/\./g, '').replace(',', '.');
      return parseFloat(nValor);
    }
    js_marcarItem(document.getElementById('aItensMarcados[' + iCodDot + ']'), true);

    const totalizador = document.getElementById("table_footer").innerText.split(': ');
    let nValor = parseFloat(valor.replace(/\./g, '').replace(',', '.'));
    let nValorAtual = parseFloat(iValorAtual.replace(/\./g, '').replace(',', '.'));
    let iValorTotalItems = (parseFloat(totalizador[1].replace(/\./g, '').replace(',', '.')) - nValorAtual) + nValor;
    document.getElementById("table_footer").innerText = totalizador[0] + ': ' + js_formatar(iValorTotalItems, 'f');
  }

  function js_marcarTodos() {
    js_aItens().forEach(function(item) {
      var check = item.classList.contains('marcado');

      if (check) {
        item.classList.remove('marcado');
      } else {
        item.classList.add('marcado');
      }
      item.checked = !check;
    });
  }

  function js_marcarItem(item, edicao = false) {
    var check = item.classList.contains('marcado');
    if (edicao) {
      item.classList.add('marcado');
      item.checked = true;
    } else {
      if (check) {
        item.classList.remove('marcado');
      } else {
        item.classList.add('marcado');
      }
      item.checked = !check;
    }
  }

  function js_aItens() {
    var itensNum = document.querySelectorAll('.marca_itens');
    return Array.prototype.map.call(itensNum, function(item) {
      return item;
    });
  }

  function js_aItensMarcados() {
    var itensNum = document.querySelectorAll('.marcado');
    return Array.prototype.map.call(itensNum, function(item) {
      return item;
    });
  }

  function js_alterarRegistro() {
    let aDados = [];
    const sCampoAlterado = document.getElementById("selectAlterar").value;
    const sCampoAlteradoValor = document.getElementById("edicao"+sCampoAlterado).value;
    if (sCampoAlteradoValor == '') return alert('Campo de edição é obrigatório.');
    js_aItensMarcados().forEach(function(item) {
      const iIndice = document.getElementById("aItensDotacao[" + item.value + "][indice]").value;
      aItem = {
        iIndice: iIndice,
        iCodDot: document.getElementById("linhaDotacao[" + iIndice + "][reduzido]").innerText,
        iAnoDot: document.getElementById("linhaDotacao[" + iIndice + "][exercicio]").value,
      };
      aDados.push(aItem);
    });
    if (aDados.length == 0) return alert('Nenhum item selecionado.');
    if (confirm("Tem certeza que deseja alterar os itens selecionados?")) {
      js_divCarregando('Aguarde', 'div_aguarde');
      let oParam = new Object();
      oParam.sCampoAlterado = sCampoAlterado;
      oParam.sCampoAlteradoValor = sCampoAlteradoValor;
      if (sCampoAlterado == 'Unidade') {
        oParam.sCampoAlteradoOrgao = $('edicaoUnidadeOrgao').value;
      }
      oParam.aDados = aDados;
      oParam.exec = 'alterarDotacoes';
      var request = new Ajax.Request('orc4_orcdotacao.RPC.php', {
        method: 'post',
        parameters: 'json=' + JSON.stringify(oParam),
        onComplete: function(res) {
          var response = JSON.parse(res.responseText);
          alert(response.message);
          js_removeObj('div_aguarde');
          window.location.href = window.location.href;
        }
      });
    }
  }

  function js_alterarValores() {
    let aDados = [];

    js_aItensMarcados().forEach(function(item) {
      const iIndice = document.getElementById("aItensDotacao[" + item.value + "][indice]").value;
      const inputValor = document.getElementById('o58_valor_' + document.getElementById("linhaDotacao[" + iIndice + "][reduzido]").innerText);
      const iValor = js_validar(inputValor, true);
      aItem = {
        iIndice: iIndice,
        iCodDot: document.getElementById("linhaDotacao[" + iIndice + "][reduzido]").innerText,
        iAnoDot: document.getElementById("linhaDotacao[" + iIndice + "][exercicio]").value,
        iValor: iValor,
      };
      aDados.push(aItem);
    });
    if (aDados.length == 0) return alert('Nenhum item selecionado.');
    if (confirm("Tem certeza que deseja alterar os itens selecionados?")) {
      js_divCarregando('Aguarde', 'div_aguarde');
      let oParam = new Object();
      oParam.aDados = aDados;
      oParam.exec = 'alterarValorDotacoes';
      var request = new Ajax.Request('orc4_orcdotacao.RPC.php', {
        method: 'post',
        parameters: 'json=' + JSON.stringify(oParam),
        onComplete: function(res) {
          var response = JSON.parse(res.responseText);
          alert(response.message);
          js_removeObj('div_aguarde');
        }
      });
    }
  }

  function js_excluir() {
    let aDados = [];

    js_aItensMarcados().forEach(function(item) {
      const iIndice = document.getElementById("aItensDotacao[" + item.value + "][indice]").value;
      aItem = {
        iIndice: iIndice,
        iCodDot: document.getElementById("linhaDotacao[" + iIndice + "][reduzido]").innerText,
        iAnoDot: document.getElementById("linhaDotacao[" + iIndice + "][exercicio]").value,
      };
      aDados.push(aItem);
    });
    if (aDados.length == 0) return alert('Nenhum item selecionado.');
    if (confirm("Tem certeza que deseja excluir os itens selecionados?")) {
      js_divCarregando('Aguarde', 'div_aguarde');
      let oParam = new Object();
      oParam.aDados = aDados;
      oParam.exec = 'excluirDotacao';
      var request = new Ajax.Request('orc4_orcdotacao.RPC.php', {
        method: 'post',
        parameters: 'json=' + JSON.stringify(oParam),
        onComplete: js_retornoAjaxExcluir
      });
    }
  }

  function js_retornoAjaxExcluir(res) {
    var response = JSON.parse(res.responseText);

    alert(response.message);

    let iDeducao = 0;
    response.aIndice.forEach((indice) => {      
      iValor = document.getElementById("linhaDotacao[" + indice + "][valor]").firstElementChild.value.replace(/\./g, '').replace(',', '.');
      iDeducao += parseFloat(iValor);
      linha = document.getElementById("linhaDotacao[" + indice + "]")
      linha.innerHTML = '';
    })
    const totalizador = document.getElementById("table_footer").innerText.split(': ');
    iValorTotalItems = parseFloat(totalizador[1].replace(/\./g, '').replace(',', '.')) - iDeducao;
    document.getElementById("table_footer").innerText = totalizador[0] + ': ' + js_formatar(iValorTotalItems, 'f');
    js_removeObj('div_aguarde');
  }

  function js_filtra(nome) {
    ordem = new Array("o40_orgao", "o41_unidade", "o52_funcao", "o53_subfuncao", "o54_programa", "o55_projativ", "o56_elemento", "o58_codigo");
    for (i = (ordem.length - 1); i > 0; i--) {
      if (ordem[i] == nome) {
        break;
      } else {
        if (eval("document.form1." + ordem[i])) {
          eval("document.form1." + ordem[i] + ".value = '0';");
        }
        if (eval("document.form1." + ordem[i] + "descr")) {
          eval("document.form1." + ordem[i] + "descr.value = '0';");
        }
      }
    }
    document.form1.submit();
  }

  function js_limparForm() {
    $('o40_orgao').value = 0;
    $('o40_orgaodescr').value = 0;

    $('o52_funcao').value = 0;
    $('o52_funcaodescr').value = 0;

    $('o53_subfuncao').value = 0;
    $('o53_subfuncaodescr').value = 0;

    $('o54_programa').value = 0;
    $('o54_programadescr').value = 0;

    $('o55_projativ').value = 0;
    $('o55_projativdescr').value = 0;

    $('o56_elemento').value = 0;
    $('o56_elementodescr').value = 0;

    $('o58_codigo').value = 0;
    $('o58_codigodescr').value = 0;

    $('o58_coddot').value = '';

    document.form1.submit();
  }

  var selectTrocar = '0';
  $('alterarRegistro').disabled = true;
  $('selectAlterar').addEventListener('click', function () {
    selectTrocar = this.value
  });

  $('selectAlterar').addEventListener('change', function () {
    if (this.value !== '0') {
      $('alterarRegistro').disabled = false;
    } else {
      $('alterarRegistro').disabled = true;
    }

    if ($('linha'+selectTrocar)) {
      $('linha'+selectTrocar).style.display = 'none';
      $('edicao'+selectTrocar).value = '';
      $('edicao'+selectTrocar+'Descr').value = '';
    }

    if ($('linha'+this.value)) {
      $('linha'+this.value).style.display = 'table-row';
    }

  });

  function js_pesquisaUnidade(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_orcunidade', 'func_orcunidade.php?<?= @$filtrar; ?>funcao_js=parent.js_mostraUnidade1|o41_unidade|o41_descr|o41_orgao', 'Pesquisa', true);
    } else {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_orcunidade', 'func_orcunidade.php?<?= @$filtrar; ?>pesquisa_chave=' + $('edicaoUnidade').value + '&funcao_js=parent.js_mostraUnidade', 'Pesquisa', false);
    }
  }

  function js_mostraUnidade1(chave1, chave2, chave3) 
  {
    $('edicaoUnidade').value = chave1;
    $('edicaoUnidadeDescr').value = chave2;
    $('edicaoUnidadeOrgao').value = chave3;
    db_iframe_orcunidade.hide();
  }

  function js_pesquisaFuncao(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_orcfuncao', 'func_orcfuncao.php?funcao_js=parent.js_mostraFuncao1|o52_funcao|o52_descr', 'Pesquisa', true);
    } else {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_orcfuncao', 'func_orcfuncao.php?pesquisa_chave=' + $('edicaoFuncao').value + '&funcao_js=parent.js_mostraFuncao', 'Pesquisa', false);
    }
  }

  function js_mostraFuncao(chave, erro) 
  {
    $('edicaoFuncaoDescr').value = chave;
    if (erro == true) {
      $('edicaoFuncao').focus();
      $('edicaoFuncao').value = '';
    }
  }

  function js_mostraFuncao1(chave1, chave2) 
  {
    $('edicaoFuncao').value = chave1;
    $('edicaoFuncaoDescr').value = chave2;
    db_iframe_orcfuncao.hide();
  }

  function js_pesquisaSubfuncao(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_orcsubfuncao', 'func_orcsubfuncao.php?funcao_js=parent.js_mostraSubfuncao1|o53_subfuncao|o53_descr', 'Pesquisa', true);
    } else {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_orcsubfuncao', 'func_orcsubfuncao.php?pesquisa_chave=' + $('edicaoSubfuncao').value + '&funcao_js=parent.js_mostraSubfuncao', 'Pesquisa', false);
    }
  }

  function js_mostraSubfuncao(chave, erro) 
  {
    $('edicaoSubfuncaoDescr').value = chave;
    if (erro == true) {
      $('edicaoSubfuncao').focus();
      $('edicaoSubfuncao').value = '';
    }
  }

  function js_mostraSubfuncao1(chave1, chave2) 
  {
    $('edicaoSubfuncao').value = chave1;
    $('edicaoSubfuncaoDescr').value = chave2;
    db_iframe_orcsubfuncao.hide();
  }

  function js_pesquisaPrograma(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_orcprograma', 'func_orcprograma.php?funcao_js=parent.js_mostraPrograma1|o54_programa|o54_descr', 'Pesquisa', true);
    } else {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_orcprograma', 'func_orcprograma.php?pesquisa_chave=' + $('edicaoPrograma').value + '&funcao_js=parent.js_mostraPrograma', 'Pesquisa', false);
    }
  }

  function js_mostraPrograma(chave, erro) 
  {
    $('edicaoProgramaDescr').value = chave;
    if (erro == true) {
      $('edicaoPrograma').focus();
      $('edicaoPrograma').value = '';
    }
  }

  function js_mostraPrograma1(chave1, chave2) 
  {
    $('edicaoPrograma').value = chave1;
    $('edicaoProgramaDescr').value = chave2;
    db_iframe_orcprograma.hide();
  }

  function js_pesquisaProjetos(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_orcprojativ', 'func_orcprojativ.php?funcao_js=parent.js_mostraProjetos1|o55_projativ|o55_descr', 'Pesquisa', true);
    } else {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_orcprojativ', 'func_orcprojativ.php?pesquisa_chave=' + $('edicaoProjetos').value + '&funcao_js=parent.js_mostraProjetos', 'Pesquisa', false);
    }
  }

  function js_mostraProjetos(chave, erro) 
  {
    $('edicaoProjetosDescr').value = chave;
    if (erro == true) {
      $('edicaoProjetos').focus();
      $('edicaoProjetos').value = '';
    }
  }

  function js_mostraProjetos1(chave1, chave2) 
  {
    $('edicaoProjetos').value = chave1;
    $('edicaoProjetosDescr').value = chave2;
    db_iframe_orcprojativ.hide();
  }

  function js_pesquisaElemento(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_orcelemento', 'func_orcelemento.php?funcao_js=parent.js_mostraElemento1|o56_codele|o56_descr|o56_elemento&analitica=1', 'Pesquisa', true);
    } else {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_orcelemento', 'func_orcelemento.php?pesquisa_chave=' + $('edicaoElemento').value + '&funcao_js=parent.js_mostraElemento&tipo_pesquisa=1&analitica=1&busca_elemento=1', 'Pesquisa', false);
    }
  }

  function js_mostraElemento(chave, erro, chave2, chave3) 
  {
    $('edicaoElementoDescr').value = chave2+' - '+chave;
    if (erro == true) {
      $('edicaoElemento').focus();
      $('edicaoElemento').value = '';
    }
  }

  function js_mostraElemento1(chave1, chave3, chave2) 
  {
    $('edicaoElemento').value = chave1;
    $('edicaoElementoDescr').value = chave2+' - '+chave3;
    db_iframe_orcelemento.hide();
  }

  function js_pesquisaFonte(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_orctiporec', 'func_orctiporec.php?funcao_js=parent.js_mostraFonte1|o15_codigo|o15_descr', 'Pesquisa', true);
    } else {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_orctiporec', 'func_orctiporec.php?pesquisa_chave=' + $('edicaoFonte').value + '&funcao_js=parent.js_mostraFonte', 'Pesquisa', false);
    }
  }

  function js_mostraFonte(chave, erro) 
  {
    $('edicaoFonteDescr').value = chave;
    if (erro == true) {
      $('edicaoFonte').focus();
      $('edicaoFonte').value = '';
    }
  }

  function js_mostraFonte1(chave1, chave2) 
  {
    $('edicaoFonte').value = chave1;
    $('edicaoFonteDescr').value = chave2;
    db_iframe_orctiporec.hide();
  }
</script>