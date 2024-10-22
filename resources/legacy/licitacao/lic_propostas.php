<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js, dbautocomplete.widget.js");
db_app::load("dbmessageBoard.widget.js, prototype.js, dbtextField.widget.js, dbcomboBox.widget.js, widgets/DBHint.widget.js");
db_app::load("grid.style.css");
db_app::load("estilos.bootstrap.css");
db_app::load("time.js");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBFileUpload.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <style>
        /* CSS Atualizado */

        .container {
            margin-top: 10px;
            background-color: #F5FFFB;
            padding: 10px;
            max-width: 1400px;
            width: 100%;
            font-size: 12px;
        }

        .legend {
            font-family: 'Arial, Helvetica, sans-serif';
            font-size: 11px;
            background-color: #c0bfff;
        }

        .container input.form-control,
        .container select.form-control {
            font-size: 12px;
            box-sizing: border-box;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .alinha-input {
            margin-bottom: 10px;
        }

        .alinha-input strong {
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
        }

        .fornecedor-container {
            display: flex;
            gap: 10px;
        }

        .hidden-column {
            display: none;
        }

        .custom-container {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .custom-container>* {
            height: 30px;
            font-size: 12px;
        }

        .btnUploadFile {
            display: inline-block;
            font-weight: 700;
            color: #fff;
            text-align: center;
            vertical-align: middle;
            user-select: none;
            background-color: #6c757d;
            border: 1px solid #6c757d;
            padding: 0.375rem 0.75rem;
            font-size: 12px;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            font-family: Arial;
            cursor: pointer;
        }

        .tooltip {
            position: absolute;
            background-color: black;
            color: white;
            padding: 5px;
            border-radius: 4px;
            font-size: 12px;
            display: none;
            z-index: 1000;
        }

        .tooltip.visible {
            display: block;
        }

        .input-descricao {
            border: none;
            background: none;
            box-shadow: none;
            outline: none;
        }

        .DBGrid {
            width: 100%;
            border: 1px solid #888;
            margin: 20px 0;
            font-size: 12px;
        }

        /* Estilos adicionais */
        .flex-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .flex-item {
            flex: 1;
            font-size: 12px;
        }
    </style>
</head>

<body style="font-family: Arial; background-color: #F5FFFB">
    <div class="container">
        <form name="form1" method="post" action="">
            <fieldset>
                <legend><b>Dados da Proposta</b></legend>
                <table>
                    <tr class="flex-container">
                        <td class="flex-item">
                            <strong>Licitação:</strong>
                            <?php
                            db_input('l20_codigo', '', 'l20_codigo', true, 'text', 3, '', '', '', '', '', 'form-control');
                            ?>
                        </td>
                        <td class="flex-item">
                            <strong>Código da Proposta:</strong>
                            <?php
                            db_input('l224_codigo', "15", 'l224_codigo', true, 'text', 3, '', '', '', '', '', 'form-control');
                            ?>
                        </td>
                        <td class="flex-item">
                            <strong>Fornecedor:</strong>
                            <div class="fornecedor-container">
                                <select name="l224_forne" class="form-control"  id="l224_forne" onchange="atualizaCodigoFornecedor();js_getDadosItenseLicitacao()">
                                </select>
                                <select type="text" class="form-control" name="fornecedor" id="fornecedor" onchange="atualizaNomeFornecedor();js_getDadosItenseLicitacao()" style="width: 400px;">
                                </select>
                                <input type="text" class="form-control" name="cnpj" tabindex="-1" id="cnpj" readonly style="width: 200px;">
                                <div id="autocomplete-list" class="autocomplete-items"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td id="id-lote" class="alinha-input">
                            <strong>Lote:</strong>
                            <select name="l04_descricao" class="form-control" id="l04_descricao" onchange="js_getDadosItenseLicitacao();"></select>
                        </td>
                    </tr>
                    <tr>
                        <td class="alinha-input">
                            <strong>Exportar xlsx:</strong>
                            <input type="button" class="btn btn-secondary" name="Exportar xls" value="Exportar xlsx" onclick='js_gerarxlsproposta()'>
                        </td>
                    </tr>
                    <tr>
                        <td class="alinha-input">
                            <strong>Importar xlsx:</strong>
                            <div class="custom-container">
                                <div id="ctnImportacao" tabindex="6"></div>
                                <input id="btnProcessar" type="button" class="btnUploadFile" value="Processar" onclick="processar();" disabled="disabled" />
                            </div>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <fieldset>
                <legend class="center-text"><b>LANÇAR VALORES DA PROPOSTA</b></legend>
                <table class="DBGrid" id="tabelaItens">
                    <thead>
                        <tr>
                            <th class="table_header" style="width: 30px; cursor: pointer;" onclick="marcarTodos();">M</th>
                            <th class="table_header" style="width: 20px;">Ordem</th>
                            <th class="table_header" style="width: 50px;">Item</th>
                            <th class="table_header" style="width: 220px;">Descrição</th>
                            <th class="table_header cabecalho_lote" style="width: 80px;">Lote</th> <!-- Classe atualizada -->
                            <th class="table_header" style="width: 80px;">Unidade</th>
                            <th class="table_header coluna_quantidade" style="width: 70px;">Quantidade</th>
                            <th class="table_header coluna_percentual" style="width: 70px;">Percentual</th>
                            <th class="table_header coluna_vlrunitario" style="width: 70px;">Vlr Unitário</th>
                            <th class="table_header coluna_vlrtotal" style="width: 70px;">Vlr Total</th>
                            <th class="table_header coluna_marca" style="width: 110px;">Marca</th>
                            <th class="table_header coluna_propitem" style="width: 110px; display: none;">Propitem</th>

                    </thead>

                    <tbody>
                        <!-- As linhas da tabela serão inseridas aqui -->
                    </tbody>
                </table>
            </fieldset>

            <fieldset>
                <legend><b>Legenda</b></legend>
                <table>
                    <tr>
                        <td align="center" class="legend" height="15px" width="100px"><b>Itens ME/EPP</b></td>
                    </tr>
                </table>
            </fieldset>

            <br>
            <div class="button-container">
                <input id="incluirB" name="incluir" type="button" class="btn btn-success" value="Incluir" onclick="salvarProposta()">
                <input id="alterarB" name="alterar" type="button" class="btn btn-primary" value="Alterar" onclick="alterarProposta()">
                <input name="voltar" type="button" class="btn btn-secondary" value="Voltar" onclick="js_pesquisa();">
                <input id="excluirB" name="excluir" type="button" class="btn btn-danger" value="Excluir" onclick="excluirProposta()">
            </div>
        </form>
    </div>
</body>

</html>

<script>
    const url = 'lic1_licpropostas.RPC.php';
    let fornecedores = [];
    let lote = [];

    js_pesquisa();

    function js_gerarxlsproposta() {
        let codLicitacao = document.getElementById('l20_codigo').value;
        let fornecedor = document.getElementById('l224_forne').value;
        let lote = document.getElementById('l04_descricao').value;
        let descrforne = document.getElementById('fornecedor').value;
        let cnpjforne = document.getElementById('cnpj').value;
        let query = `l20_codigo=${codLicitacao}&fornecedor=${fornecedor}&lote=${lote}&descrforne=${descrforne}&cnpjforne=${cnpjforne}`;
        window.open(`licproposta_gerarxlsproposta.php?${query}`);
    }

    function js_pesquisa() {
        js_OpenJanelaIframe('', 'db_iframe_propostas', 'func_propostas.php?funcao_js=parent.js_preenchepesquisa|l20_codigo', 'Pesquisa', true);
    }

    function js_preenchepesquisa(l20_codigo) {
        document.getElementById('l20_codigo').value = l20_codigo;
        db_iframe_propostas.hide();
        js_getDadosItenseFornecedores();
    }

    // Função para obter dados de itens de licitação
    function js_getDadosItenseLicitacao() {
        js_divCarregando("Aguarde, pesquisando propostas.", "msgBox");
        let oParam = {};
        let l20_codigo = document.getElementById('l20_codigo').value;
        oParam.exec = "getDadosItensLicitacao";
        oParam.l20_codigo = l20_codigo;
        oParam.fornecedor = document.getElementById('l224_forne').value;
        oParam.lote = document.getElementById('l04_descricao').value;
        new Ajax.Request(url, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoItensLicitacao
        });
    }



    function js_retornoItensLicitacao(oAjax) {
    js_removeObj("msgBox");
    let oRetorno = JSON.parse(oAjax.responseText);

    let criterio = oRetorno.criterio;
    let proposta = oRetorno.proposta;
    document.getElementById('l224_codigo').value = oRetorno.proposta;
    let tipojulg = oRetorno.tipojulg;
    let taxa = oRetorno.pc01_taxa;
    let tabela = oRetorno.pc01_tabela;
    let precoreferencia = oRetorno.preco; // Array de preços

    document.getElementById('id-lote').style.display = tipojulg == 3 ? '' : 'none';
    document.getElementById('incluirB').style.display = proposta ? 'none' : '';
    document.getElementById('excluirB').style.display = proposta ? '' : 'none';
    document.getElementById('alterarB').style.display = proposta ? '' : 'none';

    let aItens = oRetorno.itens;
    let tableBody = document.querySelector('#tabelaItens tbody');
    tableBody.innerHTML = '';

    let totalItens = 0;
    let totalValor = 0;

    aItens.forEach((item, index) => {
        totalItens++; // Incrementa o total de itens

        let descricaoCompleta = item.pc01_descrmater;
        if (item.pc01_complmater) {
            descricaoCompleta += " || " + item.pc01_complmater;
        }

        let valorUnitario = item.vlr_unitario ? item.vlr_unitario.replace(',', '.') : '';
        let quantidade = item.quantidade ? parseFloat(item.quantidade.replace(',', '.')) || '' : '';

        let valorTotal, valorTotalDisplay;

        // Verifica se deve usar o valor de precoreferencia
        if ((criterio == 1 || criterio == 2) && tabela == '1' && item.pc01_tabela == '1') {
            valorTotal = precoreferencia[index] ? precoreferencia[index].replace(',', '.') : '';
        } else {
            valorTotal = item.vlr_total ? item.vlr_total.replace(',', '.') : '';
        }

        valorTotalDisplay = valorTotal ? parseFloat(valorTotal).toFixed(4) : '';
        // Acumula o valor total
        totalValor += parseFloat(valorTotal) || 0;

        let row = document.createElement('tr');
    row.innerHTML =
    `<td class="table_header">
        <input type="checkbox"  tabindex="-1" class="marca_itens" name="aItensMarcados[]" value="${item.pc01_codmater}">
    </td>
    <td tabindex="-1" class="linhagrid coluna_ordem">${item.l21_ordem}</td>
    <td tabindex="-1" class="linhagrid coluna_item">${item.pc01_codmater}</td>
    <td tabindex="-1" style="text-align: left" class="linhagrid coluna_descricao" onmouseover="showTooltip(event)" onmouseout="hideTooltip(event)">
        ${item.pc01_descrmater}
        <div class="tooltip">${descricaoCompleta}</div>
    </td>
    <td tabindex="-1" class="linhagrid coluna_lote">${item.l04_descricao.urlDecode()}</td>
    <td tabindex="-1" class="linhagrid coluna_unidade">${item.unidade}</td>
    <td class="linhagrid coluna_quantidade">
        <input type="text" tabindex="-1" class="form-control" name="quantidade_${item.pc01_codmater}" value="${item.quantidade}" readonly style="text-align: center; background-color: #DEB887;">
    </td>
    <td class="linhagrid coluna_percentual">
        <input type="text" class="form-control campo_percentual" style="text-align: center" name="percentual_${item.pc01_codmater}"
            value="${item.percentual ? item.percentual.replace(',', '.') : ''}"
            oninput="validarCampoNumerico(this)"
            ${((item.l20_criterioadjudicacao == 1 || item.l20_criterioadjudicacao == 2) && (item.pc01_taxa == '1' || item.pc01_tabela =='1')) ? '' : 'readonly tabindex="-1" style="text-align: center; background-color: #DEB887;"' }>
    </td>
    <td class="linhagrid coluna_vlrunitario">
        <input type="text" class="form-control" style="text-align: center" name="vlrunitario_${item.pc01_codmater}"
            value="${item.vlr_unitario ? item.vlr_unitario.replace(',', '.') : ''}"
            oninput="validarCampoNumerico(this)"
            ${((item.l20_criterioadjudicacao == 1 || item.l20_criterioadjudicacao == 2) && (item.pc01_taxa == '1' || item.pc01_tabela =='1')) ? 'readonly tabindex="-1" style="text-align: center; background-color: #DEB887;"' : ''}>
    </td>
    <td class="linhagrid coluna_vlrtotal">
        <input type="text" class="form-control" name="vlrtotal_${item.pc01_codmater}" value="${valorTotalDisplay}"
            style="text-align: center; background-color: #DEB887;"
            oninput="validarCampoNumerico(this)"
            readonly tabindex="-1">
    </td>
    <td class="linhagrid coluna_marca">
        <input type="text" class="form-control" name="marca_${item.pc01_codmater}" value="${item.marca ? item.marca.urlDecode() : ''}" maxlength="30">
    </td>
    <td class="linhagrid coluna_propitem" name="propitem_${item.pc01_codmater}" style="width: 110px; display: none;">${item.pc81_codprocitem}</td>
    <td class="linhagrid coluna_taxa" name="taxa_${item.pc01_codmater}" style="width: 110px; display: none;">${item.pc01_taxa}</td>
    <td class="linhagrid coluna_taxa" name="tabela_${item.pc01_codmater}" style="width: 110px; display: none;">${item.pc01_tabela}</td>`;

        tableBody.appendChild(row);

        marcarCheckbox(item.pc01_codmater);
    });

    // Adiciona o rodapé com total de itens e valor total
    let footerRow = document.createElement('tr');
    footerRow.innerHTML = `
    <td colspan="3" class="table_header th_footer">Total de Itens: ${totalItens}</td>
    <td colspan="8" class="table_header th_footer" style="text-align: right">Valor Total: ${totalValor.toFixed(4)}</td>
    `;
    tableBody.appendChild(footerRow);

    vincularEventos();
    tratarCampos(criterio, tipojulg);
}



function validarCampoNumerico(campo) {
    // Armazena o valor atual do campo antes de qualquer modificação
    const valorAnterior = campo.value;

    // Verifica se o campo possui a classe 'campo_percentual'
    if (campo.classList.contains('campo_percentual')) {
        // Permitir números, vírgulas, pontos e o sinal de menos apenas no início
        campo.value = campo.value.replace(/[^0-9.,-]/g, '');

        // Garantir que o sinal de menos seja permitido apenas no início
        if (campo.value.indexOf('-') > 0) {
            campo.value = campo.value.replace('-', '');
        }
    } else {
        // Para outros campos, permitir apenas números, vírgulas e pontos
        campo.value = campo.value.replace(/[^0-9.,]/g, '');
    }

    // Substituir vírgula por ponto
    const novoValor = campo.value.replace(',', '.');

    // Verifica se já existe um ponto no novo valor
    const possuiPonto = novoValor.indexOf('.') !== -1;

    // Remover pontos adicionais, mantendo apenas o primeiro ponto encontrado
    if (possuiPonto) {
        const partes = novoValor.split('.');
        // Recria o valor, permitindo apenas o primeiro ponto
        campo.value = partes[0] + '.' + partes.slice(1).join('');
    } else {
        // Se não houver ponto, atualiza o valor normalmente
        campo.value = novoValor;
    }

    // Verifica se houve alteração no valor
    if (valorAnterior !== campo.value) {
        // Se o valor anterior continha vírgula e foi convertido em ponto, não exibe alerta
        if (valorAnterior.includes(',') && campo.value === novoValor) {
            return; // Retorna sem exibir alerta
        }
        // Se houve um ponto no valor anterior, exibe o alerta
        if (possuiPonto && valorAnterior !== campo.value) {
            alert("Decimal já digitado!");
            return; // Retorna para não executar o próximo alerta
        }
        // Alerta para informar que o campo deve ser preenchido somente com números decimais
        if (valorAnterior.indexOf('.') === -1) {
            alert("Este campo deve ser preenchido somente com números decimais.");
        }
    }
}


// Função para formatar o campo para 4 casas decimais ao sair do campo
function formatarCampoParaQuatroDecimais(campo) {
    if (campo.name.startsWith("vlrunitario_") && campo.value && !isNaN(campo.value)) {
        campo.value = parseFloat(campo.value).toFixed(4);
    }
}

document.addEventListener('focusout', function(event) {
    if (event.target.matches('input[name^="vlrunitario_"]')) {
        formatarCampoParaQuatroDecimais(event.target);
    }
}, true);


    let atualizando = false;

function atualizarTodosOsItens() {
    if (atualizando) return;
    atualizando = true;

    let codMateriais = document.querySelectorAll('input[name^="quantidade_"]');

    codMateriais.forEach(input => {
        let codmater = input.name.split('_')[1];
        js_somaTotal(codmater);
    });

    atualizando = false;
}

function js_somaTotal(codmater) {
    let quantidadeInput = document.querySelector(`input[name="quantidade_${codmater}"]`);
    let valorUnitarioInput = document.querySelector(`input[name="vlrunitario_${codmater}"]`);
    let valorTotalInput = document.querySelector(`input[name="vlrtotal_${codmater}"]`);
    let percentualInput = document.querySelector(`input[name="percentual_${codmater}"]`);
    let taxaCell = document.querySelector(`td[name="taxa_${codmater}"]`);
    let tabela = document.querySelector(`td[name="tabela_${codmater}"]`);

    // Verifica se o valor total já foi definido por outra função
    if (tabela && tabela.textContent.trim().toLowerCase() === 'true' && valorTotalInput.value.trim() !== "" && !isNaN(parseFloat(valorTotalInput.value))) {
        return; // Se já estiver preenchido, sai da função
    }

    let quantidade = parseFloat(quantidadeInput.value.replace(',', '.')) || 0;
    let valorUnitario = parseFloat(valorUnitarioInput.value.replace(',', '.')) || 0;

    // Adiciona verificação se o percentual é 0 e se o item é taxa
    if (taxaCell && taxaCell.textContent.trim().toLowerCase() === 'true') {
        if (percentualInput && percentualInput.value.trim() === "0") {
            valorUnitarioInput.value = "0";  // Define o valor unitário como 0
            valorTotalInput.value = "0";      // Define o valor total como 0
            marcarCheckbox(codmater); // Marca o checkbox se necessário
            return; // Sai da função, pois já ajustou os valores
        }
    }

    let valorTotal;
    if (taxaCell && taxaCell.textContent.trim().toLowerCase() === 'true') {
        if (percentualInput && percentualInput.value.trim() !== "") {
            let percentual = parseFloat(percentualInput.value.replace(',', '.')) || 0;
            let totalValorGrid = calcularTotalValorGrid();
            valorTotal = (totalValorGrid * percentual / 100).toFixed(2);
            valorUnitarioInput.value = valorTotal;
        } else {
            valorTotal = (valorUnitario * quantidade).toFixed(2);
        }
    } else {
        valorTotal = (valorUnitario * quantidade).toFixed(2);
    }

    valorTotalInput.value = (parseFloat(valorTotal) === 0) ? "" : valorTotal;

    // Certifica-se de que o checkbox será marcado corretamente após atualizar os campos
    marcarCheckbox(codmater);
}
document.addEventListener('change', function(event) {
    if (event.target.matches('input[name^="quantidade_"], input[name^="vlrunitario_"], input[name^="percentual_"]')) {
        atualizarTodosOsItens();
    }
});


// Função para calcular o valor total de todos os itens no grid
function calcularTotalValorGrid() {
    let valorTotalGrid = 0;
    document.querySelectorAll('input[name^="vlrtotal_"]').forEach(input => {
        valorTotalGrid += parseFloat(input.value.replace(',', '.')) || 0;
    });
    return valorTotalGrid;
}

// Função para vincular eventos aos campos da tabela
function vincularEventos() {
    // Adiciona o evento de mudança somente aos campos relevantes
    document.querySelectorAll('.linhagrid input[name^="percentual_"], .linhagrid input[name^="vlrunitario_"]').forEach(input => {
        input.addEventListener('change', function() {
            let codmater = this.name.split('_')[1];
            // Atualiza o valor percentual e unitário para garantir que vírgulas sejam convertidas para pontos
            let percentualInput = document.querySelector(`input[name="percentual_${codmater}"]`);
            percentualInput.value = percentualInput.value.replace(',', '.');

            let valorUnitarioInput = document.querySelector(`input[name="vlrunitario_${codmater}"]`);
            valorUnitarioInput.value = valorUnitarioInput.value.replace(',', '.');

            // Recalcula o valor total sempre que o valor unitário ou percentual muda
            js_somaTotal(codmater);
        });
    });

    // Adiciona o evento de mudança aos campos de marca
    document.querySelectorAll('.linhagrid input[name^="marca_"]').forEach(input => {
        input.addEventListener('change', function() {
            let codmater = this.name.split('_')[1];
            marcarCheckbox(codmater);
        });
    });
}

    // Função para marcar ou desmarcar o checkbox correspondente baseado nos dados preenchidos
    function marcarCheckbox(codigoItem) {
    let checkbox = document.querySelector(`input[name="aItensMarcados[]"][value="${codigoItem}"]`);
    let percentualInput = document.querySelector(`input[name="percentual_${codigoItem}"]`);
    let valorUnitarioInput = document.querySelector(`input[name="vlrunitario_${codigoItem}"]`);
    let valorTotalInput = document.querySelector(`input[name="vlrtotal_${codigoItem}"]`);
    let marcaInput = document.querySelector(`input[name="marca_${codigoItem}"]`);

    if (checkbox) {
        // Verifica se qualquer campo está preenchido ou se o percentual/valor unitário é 0
        let preenchido = percentualInput.value.trim() !== '' ||
                        parseFloat(percentualInput.value) === 0 ||
                        valorUnitarioInput.value.trim() !== '' ||
                        parseFloat(valorUnitarioInput.value) === 0 ||
                        valorTotalInput.value.trim() !== '' ||
                        marcaInput.value.trim() !== '';

        checkbox.checked = preenchido; // Marca o checkbox se algum campo estiver preenchido ou se o percentual/valor unitário for 0
    }
}


    // Função para retornar todos os itens marcados
    function aItens() {
        var itensNum = document.querySelectorAll('.marca_itens');
        return Array.prototype.map.call(itensNum, function(item) {
            return item;
        });
    }

    // Função para marcar ou desmarcar todos os checkboxes
    function marcarTodos() {
        aItens().forEach(function(item) {
            var check = item.classList.contains('marcado');
            if (check) {
                item.classList.remove('marcado');
            } else {
                item.classList.add('marcado');
            }
            item.checked = !check;
        });
    }


    function showTooltip(event) {
        const tooltip = event.target.querySelector('.tooltip');
        if (tooltip) {
            tooltip.style.display = 'block';
            tooltip.classList.add('visible');
            const rect = event.target.getBoundingClientRect();
            tooltip.style.top = `${rect.top + window.scrollY + event.target.offsetHeight}px`;
            tooltip.style.left = `${rect.left + window.scrollX}px`;
        }
    }

    function hideTooltip(event) {
        const tooltip = event.target.querySelector('.tooltip');
        if (tooltip) {
            tooltip.style.display = 'none';
            tooltip.classList.remove('visible');
        }
    }

    function js_getDadosItenseFornecedores() {
        let oParam = {};
        let l20_codigo = document.getElementById('l20_codigo').value;
        oParam.exec = "getDadosItenseFornecedores";
        oParam.l20_codigo = l20_codigo;
        new Ajax.Request(url, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoItenseFornecedores
        });
    }

    function js_retornoItenseFornecedores(oAjax) {
        let oRetorno = JSON.parse(oAjax.responseText);
        fornecedores = oRetorno.fornecedores;

        let selectForne = document.getElementById('l224_forne');
        let selectFornecedor = document.getElementById('fornecedor');
        selectForne.innerHTML = '';
        selectFornecedor.innerHTML = '';

        fornecedores.forEach(function(oForne) {
            let option = document.createElement("option");
            option.value = oForne.z01_numcgm;
            option.text = oForne.z01_numcgm;
            selectForne.appendChild(option);

            let option2 = document.createElement("option");
            option2.value = oForne.z01_nome;
            option2.text = oForne.z01_nome;
            selectFornecedor.appendChild(option2);

        });

        atualizaCodigoFornecedor();
        js_getItensLote();
        atualizaNomeFornecedor();
    }

    function atualizaNomeFornecedor() {
        let selectnomeforne = document.getElementById('fornecedor');
        let selectnomevalue = selectnomeforne.value;

        let fornecedorNome = fornecedores.find(f => f.z01_nome == selectnomevalue);

        if (fornecedorNome) {
            document.getElementById('l224_forne').value = fornecedorNome.z01_numcgm;
            document.getElementById('cnpj').value = fornecedorNome.z01_cgccpf;
        } else {
            document.getElementById('l224_forne').value = '';
            document.getElementById('cnpj').value = '';
        }
        let inputUpload = document.querySelector('.inputUploadFile');
        if (inputUpload) {
            inputUpload.value = ''; // Limpa o valor do input
        }
    }

    function atualizaCodigoFornecedor() {
        let selectforne = document.getElementById('l224_forne');
        let selectedValue = selectforne.value;

        let fornecedorCodigo = fornecedores.find(f => f.z01_numcgm == selectedValue);

        if (fornecedorCodigo) {
            document.getElementById('fornecedor').value = fornecedorCodigo.z01_nome;
            document.getElementById('cnpj').value = fornecedorCodigo.z01_cgccpf;
        } else {
            document.getElementById('fornecedor').value = '';
            document.getElementById('cnpj').value = '';
        }
        let inputUpload = document.querySelector('.inputUploadFile');
        if (inputUpload) {
            inputUpload.value = ''; // Limpa o valor do input
        }
    }
    function obterProximoFornecedor() {
        // Suponha que você tenha a lista de fornecedores globalmente disponível
        let selectforne = document.getElementById('l224_forne');
        let fornecedores = Array.from(selectforne.options).map(option => ({
            z01_numcgm: option.value,
            z01_nome: option.text,
            z01_cgccpf: option.getAttribute('data-cnpj')
        }));

        let fornecedorAtual = selectforne.value;
        let indexAtual = fornecedores.findIndex(f => f.z01_numcgm === fornecedorAtual);

        if (indexAtual !== -1 && indexAtual < fornecedores.length - 1) {
            return fornecedores[indexAtual + 1].z01_numcgm;
        } else {
            // Se o fornecedor atual for o último ou não estiver na lista, retornar o primeiro ou um valor padrão
            return fornecedores[0].z01_numcgm; // Ou outro valor adequado
        }
    }

    function salvarProposta() {
    let oParam = {};
    oParam.exec = "salvarProposta";
    oParam.l20_codigo = document.getElementById('l20_codigo').value;
    oParam.l224_forne = document.getElementById('l224_forne').value;
    oParam.aItens = [];

    // Seleciona todos os checkboxes marcados
    let checkedItems = document.querySelectorAll('input[name="aItensMarcados[]"]:checked');

    // Seleciona todos os checkboxes (marcados e desmarcados)
    let allItems = document.querySelectorAll('input[name="aItensMarcados[]"]');

    // Array para coletar os números de ordem dos itens inválidos
    let itensInvalidos = [];

    checkedItems.forEach(checkbox => {
        let item = {};
        // Obtém o valor do item a partir do checkbox
        let oItem = checkbox.value;
        let row = checkbox.closest('tr');
        let ordem = document.querySelector(`tr input[value="${oItem}"]`).closest('tr').querySelector('td.coluna_ordem').textContent.trim();

        // Obtém os valores dos inputs na linha
        item.l224_propitem = row.querySelector(`td[name="propitem_${oItem}"]`).textContent;
        item.l224_quant = row.querySelector(`input[name="quantidade_${oItem}"]`).value;
        item.l224_vlrun = row.querySelector(`input[name="vlrunitario_${oItem}"]`).value;
        item.l224_valor = row.querySelector(`input[name="vlrtotal_${oItem}"]`).value;
        item.l224_porcent = row.querySelector(`input[name="percentual_${oItem}"]`).value;
        item.l224_marca = row.querySelector(`input[name="marca_${oItem}"]`).value;
        item.l224_forne = document.getElementById('l224_forne').value;

        // Verifica se o campo l224_valor está vazio
        if (item.l224_valor === "") {
            itensInvalidos.push(ordem); // Adiciona o número de ordem do item ao array de itens inválidos
            return;
        }

        oParam.aItens.push(item);
    });

    // Exibe um único alerta se houver itens inválidos
    if (itensInvalidos.length > 0) {
        alert(`Desmarque os itens sem valores lançados. Itens ordem: ${itensInvalidos.join(", ")}`);
        return;
    }

    // Verifica se há itens desmarcados
    let uncheckedItems = Array.from(allItems).filter(item => !item.checked);

    if (uncheckedItems.length > 0) {
        let confirmacao = confirm("Existem itens não marcados. Caso necessário, revise os itens. Deseja continuar?");
        if (!confirmacao) {
            return;
        }
    }



    // Verifica se há itens válidos e faz a chamada AJAX
    if (oParam.aItens.length > 0) {
        let oAjax = new Ajax.Request(url, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoSalvarProposta
        });
    }
}


    function js_retornoSalvarProposta(oAjax,cnpj,nomefornecedor) {
        let oRetorno = JSON.parse(oAjax.responseText);

        if (oRetorno.status === 1) {
            // Atualiza o código da proposta
            document.getElementById('l224_codigo').value = oRetorno.proposta;

            // Obtém o próximo fornecedor e atualiza o campo
            let proximoFornecedor = obterProximoFornecedor(); // Função a ser definida
            document.getElementById('l224_forne').value = proximoFornecedor;

            // Atualiza as informações do fornecedor
            atualizaCodigoFornecedor();

            alert('Salvo com sucesso!');

            // Carrega os dados do próximo fornecedor
            js_getDadosItenseLicitacao();
        } else {
            alert(oRetorno.message.urlDecode());
        }
    }

    function alterarProposta() {
    let oParam = {};
    oParam.exec = "alterarProposta";
    oParam.l20_codigo = document.getElementById('l20_codigo').value;
    oParam.l224_codigo = document.getElementById('l224_codigo').value;
    oParam.aItens = [];

    // Seleciona todos os checkboxes marcados
    let checkedItems = document.querySelectorAll('input[name="aItensMarcados[]"]:checked');

    // Seleciona todos os checkboxes (marcados e desmarcados)
    let allItems = document.querySelectorAll('input[name="aItensMarcados[]"]');

    // Flag para controlar se algum item está inválido
    let itensInvalidos = [];

    // Verifica os itens marcados
    checkedItems.forEach(checkbox => {
        let item = {};

        // Obtém o valor do item a partir do valor do checkbox
        let oItem = checkbox.value;
        let row = checkbox.closest('tr');
        let ordem = document.querySelector(`tr input[value="${oItem}"]`).closest('tr').querySelector('td.coluna_ordem').textContent.trim();

        // Obtém os valores dos inputs na linha
        item.l224_propitem = row.querySelector(`td[name="propitem_${oItem}"]`).textContent;
        item.l224_quant = row.querySelector(`input[name="quantidade_${oItem}"]`).value;
        item.l224_vlrun = row.querySelector(`input[name="vlrunitario_${oItem}"]`).value;
        item.l224_valor = row.querySelector(`input[name="vlrtotal_${oItem}"]`).value;
        item.l224_porcent = row.querySelector(`input[name="percentual_${oItem}"]`).value;
        item.l224_marca = row.querySelector(`input[name="marca_${oItem}"]`).value;

        // Verifica se o campo l224_valor está vazio
        if (item.l224_valor === "") {
            itensInvalidos.push(ordem); // Adiciona o número de ordem do item ao array de itens inválidos
            return;
        }
        oParam.aItens.push(item);
    });

    // Exibe um único alerta se houver itens inválidos
    if (itensInvalidos.length > 0) {
        alert(`Desmarque os itens sem valores lançados. Itens ordem: ${itensInvalidos.join(", ")}`);
        return;
    }

    // Verifica se há itens desmarcados
    let uncheckedItems = Array.from(allItems).filter(item => !item.checked);

    if (uncheckedItems.length > 0) {
        let confirmacao = confirm("Existem itens não marcados. Caso necessário, revise os itens. Deseja continuar?");
        if (!confirmacao) {
            return;
        }
    }

    // Verifica se há itens válidos e faz a chamada AJAX
    if (oParam.aItens.length > 0) {
        let oAjax = new Ajax.Request(url, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoAlterarProposta
        });
    }
}


    function js_retornoAlterarProposta(oAjax) {
        let oRetorno = JSON.parse(oAjax.responseText);
        if (oRetorno.status === 1) {
            document.getElementById('l224_codigo').value = oRetorno.proposta;
            alert('Alterado com sucesso!');
            js_getDadosItenseLicitacao();
        } else {
            alert(oRetorno.message.urlDecode());
        }
    }

    function excluirProposta(l224_codigo) {
        if (confirm('Tem certeza que deseja excluir esta proposta?')) {
            let oParam = {};
            oParam.exec = "excluirProposta";
            oParam.l224_codigo = document.getElementById('l224_codigo').value;
            oParam.l223_codigo = document.getElementById('l224_codigo').value;

            new Ajax.Request(url, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: function(response) {
                    let oRetorno = JSON.parse(response.responseText);
                    if (oRetorno.status === 1) {
                        alert('Proposta excluída com sucesso!');
                        js_getDadosItenseLicitacao();
                    } else {
                        alert('Erro ao excluir proposta: ' + oRetorno.message);
                    }
                }
            });
        }
    }

    function js_getItensLote() {
        let oParam = {};
        oParam.exec = "getDadosItenseLote";
        oParam.l20_codigo = document.getElementById('l20_codigo').value;
        oParam.fornecedor = document.getElementById('l224_forne').value;

        new Ajax.Request(url, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoItenseLote
        });
    }

    function js_retornoItenseLote(oAjax) {
        let oRetorno = JSON.parse(oAjax.responseText);
        let lote = oRetorno.lote;

        let selectLote = document.getElementById('l04_descricao');
        selectLote.innerHTML = '';

        let Todos = document.createElement("option");
        Todos.value = "Todos";
        Todos.text = "Todos";
        selectLote.appendChild(Todos);

        lote.forEach(function(oLote) {
            let option = document.createElement("option");
            option.value = oLote.l04_descricao;
            option.text = oLote.l04_descricao;
            selectLote.appendChild(option);
        });
        js_getDadosItenseLicitacao();
    }

    function tratarCampos(criterio, tipojulg,pc01_taxa,pc01_tabela) {
        // Seleciona todas as células das colunas "Lote" e "Percentual"
        let colunasLote = document.querySelectorAll('.coluna_lote');
        let colunasPercentual = document.querySelectorAll('.coluna_percentual');
        let colunaValorUnitario = document.querySelectorAll('.coluna_vlrunitario');

        // Seleciona os cabeçalhos das colunas "Lote" e "Percentual"
        let cabecalhoLote = document.querySelector('.cabecalho_lote');

        colunasLote.forEach(coluna => {
            if (tipojulg == 3) {
                coluna.style.display = '';
            } else {
                coluna.style.display = 'none';
            }
        });

        if (cabecalhoLote) {
            if (tipojulg == 3) {
                cabecalhoLote.style.display = '';
            } else {
                cabecalhoLote.style.display = 'none';
            }
        }
    }

    var oWindowAuxVinculos = '';
    var oGridVinculos = '';
    var sRpc = 'lic1_licpropostas.RPC.php';
    var lTemInconsistencia = false;

    var oFileUpload = new DBFileUpload({
        callBack: retornoEnvioArquivo
    });
    oFileUpload.show($('ctnImportacao'));

    function retornoEnvioArquivo(oRetorno) {

        if (oRetorno.error) {

            alert(oRetorno.error);
            $('btnProcessar').disabled = true;
            return false;
        }

        if (oRetorno.extension.toLowerCase() != 'xlsx') {

            alert('Arquivo invalido!');
            let inputUpload = document.querySelector('.inputUploadFile');
            if (inputUpload) {
                inputUpload.value = ''; // Limpa o valor do input
            }
            $('btnProcessar').disabled = true;
            return false;
        }

        $('btnProcessar').disabled = false;
    }

    /**
     * Processa o arquivo que foi feito upload, enviando os dados para o RPC
     */
    function processar() {

        var oParametros = {};
        oParametros.exec = 'importarProposta';
        oParametros.sNomeArquivo = oFileUpload.file;
        oParametros.sCaminhoArquivo = oFileUpload.filePath;

        var oAjaxRequest = new AjaxRequest(sRpc, oParametros, retornoProcessar);
        oAjaxRequest.setMessage('Aguarde!');
        oAjaxRequest.execute();
    }

    function retornoProcessar(oRetorno, lErro) {
    let dadosimport = oRetorno.import[0]; // Acessa o primeiro chunk de dados importados
    let cnpj = document.getElementById('cnpj').value;
    let proposta = document.getElementById('l224_codigo').value;
    let cnpjRpc = oRetorno.cnpj;
    let nomefornecedor = oRetorno.nome;

    if (cnpj !== cnpjRpc) {
        alert(`A proposta não pertence ao fornecedor selecionado, verifique o CNPJ da proposta: ${cnpjRpc}`);
        return;
    }

    if (proposta) {
        alert(`Fornecedor ${cnpjRpc} - ${nomefornecedor} já possui proposta lançada. Para importar nova proposta é necessário excluir a proposta!`);
        return;
    }

    let elementos = document.querySelectorAll('.coluna_ordem');

    // Cria um array com os valores de ordem dos elementos
    let ordemElementos = Array.from(elementos).map(elemento => parseInt(elemento.textContent.trim()));

    if (Array.isArray(dadosimport)) {
        // Ordena os dados importados de acordo com a ordem dos elementos
        dadosimport.sort((a, b) => ordemElementos.indexOf(a.ordem) - ordemElementos.indexOf(b.ordem));

        dadosimport.forEach(function(item) {
            // Encontrar a linha correspondente usando a ordem do item
            let row = document.querySelector(`#tabelaItens tbody tr:nth-child(${ordemElementos.indexOf(item.ordem) + 1})`);

            if (row) {
                // Preenche os campos da linha com os dados importados
                let vlr_unit = row.querySelector(`input[name^="vlrunitario_"]`);
                let vlr_total = row.querySelector(`input[name^="vlrtotal_"]`);
                let percentual = row.querySelector(`input[name^="percentual_"]`);
                let marca = row.querySelector(`input[name^="marca_"]`);
                let checkbox = row.querySelector(`input[name^="aItensMarcados[]"]`);

                // Preenche os campos com dados importados, se disponíveis
                if (vlr_unit) {
                    vlr_unit.value = item.vlr_unit || '';
                }

                // Verifica se o valor total já está preenchido antes de sobrescrevê-lo
                if (vlr_total && vlr_total.value.trim() === '') {
                    vlr_total.value = item.vlr_total || ''; // Só preenche se estiver vazio
                }

                if (percentual) {
                    percentual.value = item.percentual || '';
                }
                if (marca) {
                    marca.value = (item.marca || '').substring(0, 30);
                }

                // Marca a checkbox somente se pelo menos um dos campos foi preenchido
                if (checkbox) {
                    checkbox.checked = (
                        (vlr_unit && vlr_unit.value) ||
                        (vlr_total && vlr_total.value) ||
                        (percentual && percentual.value) ||
                        (marca && marca.value)
                    );
                }
            }
        });

        // Chama a função para recalcular todos os itens após a importação
        atualizarTodosOsItens();

        // Chama a função para salvar a proposta após a importação
        salvarProposta();
    }

 else {
        alert('Falha ao importar!');
    }

    let inputUpload = document.querySelector('.inputUploadFile');
    if (inputUpload) {
        inputUpload.value = ''; // Limpa o valor do input
    }
}


</script>
