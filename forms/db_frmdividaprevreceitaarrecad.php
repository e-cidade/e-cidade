<?php
use App\Repositories\Contabilidade\PrevisaoOperacaoCreditoRepository;

$previsaoOperacaoCreditoRepository = new PrevisaoOperacaoCreditoRepository();
$valor_atribuir = ($fValorPrevAno - $previsaoOperacaoCreditoRepository->totalPrevisto($c242_fonte, db_getsession('DB_anousu')));

?>
<style type="text/css">
    .linhagrid.center {
        text-align: center;
    }

    .linhagrid input[type='text'] {
        width: 100%;
    }

    .normal:hover {
        background-color: #eee;
    }

    .DBGrid {
        width: 100%;
        border: 1px solid #888;
        margin: 20px 0;
    }
</style>
<form name="form1" method="post" action="">
    <fieldset style="width: 600px;">
        <legend><b>Associação de Operação de Crédito à Previsão da Receita</b></legend>
        <center>
            <table border="0">
                <tr>
                    <td nowrap title="Receita">
                        <b>Receita:</b>
                    </td>
                    <td colspan="2">
                        <?
                        db_input('c229_fonte', 0, '', true, 'hidden', 3, "");
                        db_input('fonte', 0, '', true, 'hidden', 3, "");
                        db_input('total_assinado', 0, '', true, 'hidden', 3, "");
                        db_input('fValorPrevAno', 0, '', true, 'hidden', 3, "");
                        db_input('sReceita', 100, '', true, 'text', 3, "")
                        ?>
                        <input name="iNumItensGrid" type="hidden" id="iNumItensGrid" value="0">
                    </td>
                </tr>
                <tr>
                    <td nowrap title="<?= @$Tc242_operacaocredito ?>">
                        <?
                        db_ancora('Operação de Crédito', "js_pesquisaOperacaoCredito(true);", $db_opcao);
                        ?>
                    </td>
                    <td>
                        <?
                        db_input('iOperacaoCreditoTemp', 0, '', true, 'hidden', 3, "");
                        db_input('c242_operacaocredito', 8, $Tc242_operacaocredito, true, 'text', $db_opcao, "onchange='js_pesquisaOperacaoCredito(false);'");
                        ?>
                    </td>
                    <td>
                        <?
                        db_input('sObjeto', 90, '', true, 'text', 3, "");
                        ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="<?= @$Tc242_vlprevisto ?>">
                        <b>Saldo a Atribuir:</b>
                    </td>
                    <td>
                        <?
                        db_input('valor_atribuir', 8, 'Valor a Atribuir', true, 'float', 3, "");
                        ?>
                    </td>
                </tr>
            </table>
        </center>
    </fieldset>
    <table>
        <tr>
            <input name="incluir" type="button" id="db_opcao" value="Incluir" onclick="js_inclui();">
            <input name="pesquisar" type="button" id="limpar" value="Limpar" onclick="js_limpa();">
            <input name="pesquisar" type="button" id="pesquisar" value="Fechar" onclick="js_fecha();">
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>

    <fieldset style="width: 820px;">
        <legend align="center"><b>OPERAÇÕES DE CRÉDITO ASSOCIADAS</b></legend>
        <table class="DBGrid" id="gridConv">
            <tr>
                <th class="table_header" style="width: 33px; cursor: pointer;" onclick="marcarTodos(<?= $iFonte ?>);" id="marcarTodos">M</th>
                <th class="table_header" style="width: 70px;">Código do Dívida</th>
                <th class="table_header" style="width: 70px;">Número do Contrato</th>
                <th class="table_header" style="width: 240px;">Objeto</th>
                <th class="table_header" style="width: 100px;">Data da Assinatura</th>
                <th class="table_header" style="width: 110px;">Valor Previsto</th>
                <th class="table_header" style="width: 100px;">Valor Arrecadado</th>
            </tr>
        </table>
    </fieldset>

    <table>
        <tr>
            <input name="salvarGeral" type="button" id="salvarGeral" value="Salvar" onclick="js_salvaGeral();">
            <input name="exclui" type="button" id="exclui" value="Excluir" onclick="js_exclui();">
        </tr>
    </table>

</form>
<script>
    // VALIDADA
    const sRPC = 'con4_previsaoreceita.RPC.php';
    buscarOperacoesDeCredito();

    /**
     * Buscar Operações de Crédito Associadas a Receita
     */
    function buscarOperacoesDeCredito() {
        var oParam = new Object();
        oParam.exec = 'buscaOperacoesDeCredito';
        oParam.iCodRec = document.form1.c229_fonte.value;

        js_divCarregando('Aguarde, buscando registros', 'div_aguarde');

        var oAjax = new Ajax.Request(sRPC, {
            method:'post',
            parameters:'json=' + Object.toJSON(oParam),
            onComplete: js_retornoBuscaOperacoesDeCredito
        });
    }
    
    /**
     * Recebe o retorno da busca das operações de crédito
     */
    function js_retornoBuscaOperacoesDeCredito(oAjax) {
        js_removeObj('div_aguarde');
        var oRetorno = eval("("+oAjax.responseText+")");
        var iMes = oRetorno.iMes;

        if (oRetorno.status == 1) {
            document.form1.iNumItensGrid.value = oRetorno.aItens.length;
            oRetorno.aItens.each(function(oOperacaoCredito, iLinha) {
                js_adicionaLinha(oOperacaoCredito, iLinha);
            });
        }
    }

    /** 
     * Adiciona linhas no GRID
     */
    function js_adicionaLinha(oPrevisao = null, iLinha = null) {
        var sLinhaTabela = "";

        sLinhaTabela += "<tr id='" + iLinha + "' class='normal'>";
        sLinhaTabela += "   <th class='table_header'>";
        sLinhaTabela += "       <input type='checkbox' class='marca_itens' name='aItensMarcados[]' value='" + iLinha + "' >";
        sLinhaTabela += "       <input type='hidden' name='aItensOperacaoCredito[" + iLinha + "][c242_fonte]' value='" + oPrevisao.iReceita + "' >";
        sLinhaTabela += "       <input type='hidden' name='aItensOperacaoCredito[" + iLinha + "][c242_operacaocredito]' value='" + oPrevisao.iCodigoDivida + "' >";
        sLinhaTabela += "       <input type='hidden' name='aItensOperacaoCredito[" + iLinha + "][op01_numerocontratoopc]' value='" + oPrevisao.iNumeroContrato + "' >";
        sLinhaTabela += "       <input type='hidden' name='aItensOperacaoCredito[" + iLinha + "][op01_objetocontrato]' value='" + oPrevisao.sObjeto + "' >";
        sLinhaTabela += "       <input type='hidden' name='aItensOperacaoCredito[" + iLinha + "][op01_dataassinaturacop]' value='" + oPrevisao.dDataAssinatura + "' >";
        sLinhaTabela += "       <input type='hidden' name='aItensOperacaoCredito[" + iLinha + "][bArrecadado]' value='" + oPrevisao.fVlArrecadado + "' >";
        sLinhaTabela += "   </th>";
        sLinhaTabela += "   <td class='linhagrid'>" + oPrevisao.iCodigoDivida + "</td>";
        sLinhaTabela += "   <td class='linhagrid'>" + oPrevisao.iNumeroContrato + "</td>";
        sLinhaTabela += "   <td class='linhagrid'>" + oPrevisao.sObjeto.urlDecode() + "</td>";
        sLinhaTabela += "   <td class='linhagrid'>" + js_formatar(oPrevisao.dDataAssinatura, 'd') + "</td>";
        sLinhaTabela += "   <td class='linhagrid'>";
        sLinhaTabela += "       <input type='text' name='aItensOperacaoCredito[" + iLinha + "][c242_vlprevisto]' value='" + js_formatar(oPrevisao.fVlPrevisto, 'f') + "' style='width: 70px; text-align:right' >";
        sLinhaTabela += "   </td>";
        sLinhaTabela += "   <td class='linhagrid'>";
        sLinhaTabela += "       <input type='text' name='aItensOperacaoCredito[" + iLinha + "][valor_arrecadado]' value='" + js_formatar(oPrevisao.fVlArrecadado, 'f') + "' style='width: 70px; background: #DEB887; text-align:right' readonly='true' >";
        sLinhaTabela += "   </td>";
        sLinhaTabela += "</tr>";

        valorAtribuir = document.form1.valor_atribuir.value.replace('.', '');

        document.form1.valor_atribuir.value = js_formatar(parseFloat(valorAtribuir.replace(',', '.')) - oPrevisao.fVlPrevisto, 'f');

        document.getElementById("gridConv").innerHTML += sLinhaTabela;
    }

    /**
     * Inclui a operação de crédito
     */
    function js_inclui() {
        if (document.form1.c242_operacaocredito.value == '') {
            alert('Informe a Operação de Crédito');
            return false;
        }

        var iNumItensGrid = parseInt(document.form1.iNumItensGrid.value);
        var bContinua = true;

        for (i = 0; i <= iNumItensGrid - 1; i++) {
            if (document.form1.c242_operacaocredito.value == document.form1['aItensOperacaoCredito[' + i + '][c242_operacaocredito]'].getAttribute('value')) {
                alert("Operação de Crédito já associado a esta receita!");
                bContinua = false;
                return false;
            }
        }

        if (bContinua) {
            try {
                var oParam = new Object();

                oParam.exec = 'salvaOperacaoCredito';
                oParam.iOperacaoCredito = document.form1.c242_operacaocredito.value;
                oParam.iReceita = document.form1.c229_fonte.value;

                js_divCarregando('Aguarde', 'div_aguarde');
  
                var oAjax = new Ajax.Request(sRPC, {
                    method: 'post',
                    parameters: 'json=' + Object.toJSON(oParam),
                    onComplete: js_retornoInclui
                });
            } catch (e) {
                alert(e.toString());
            }
        }
    }

    /**
     * Retorno do metodo incluir
     */
    function js_retornoInclui(oAjax) {
        js_removeObj('div_aguarde');
        var oRetorno = eval("(" + oAjax.responseText + ")");
        if (oRetorno.status == 1) {
            var iNumItensGrid = parseInt(document.form1.iNumItensGrid.value);
            js_adicionaLinha(oRetorno, iNumItensGrid);
            document.form1.iNumItensGrid.value = iNumItensGrid + 1;
            document.form1.c242_operacaocredito.value = '';
            document.form1.sObjeto.value = '';
        } else {
            alert(oRetorno.sMensagem.urlDecode());
        }
    }

    /**
     * Controle da Ancora de Operacao de Credito
     */
    function js_pesquisaOperacaoCredito(mostra) {
        if (mostra) {
            js_OpenJanelaIframe('', 'db_iframe_db_operacaodecredito', 'func_db_operacaodecredito.php?tipos_lancamento=3,4,6,7&funcao_js=parent.js_preenchepesquisa|op01_sequencial|op01_objetocontrato|op01_numerocontratoopc', 'Pesquisa', true);
        } else {
            if (document.form1.c242_operacaocredito.value != '') {
                js_OpenJanelaIframe('', 'db_iframe_db_operacaodecredito', 'func_db_operacaodecredito.php?pesquisa_chave=' + document.form1.c242_operacaocredito.value + '&tipos_lancamento=3,4,6,7&funcao_js=parent.js_preenchepesquisa1', 'Pesquisa', false);
            } else {
                document.form1.c242_operacaocredito.value = "";
            }
        }
    }

    function js_preenchepesquisa(sequencial, objeto, numero) {
        db_iframe_db_operacaodecredito.hide();
        document.form1.c242_operacaocredito.value = sequencial;
        document.form1.sObjeto.value = objeto;
        document.form1.fonte.value = numero;
    }

    function js_preenchepesquisa1(divida, erro, objeto) {
        document.form1.sObjeto.value = objeto;
        if (erro == true) {
            document.form1.sObjeto.value = divida;
            document.form1.c242_operacaocredito.focus();
            document.form1.c242_operacaocredito.value = '';
        }
    }

    document.form1.valor_atribuir.value = js_formatar(document.form1.valor_atribuir.value, 'f');

    function js_salvaGeral() {
        var iNumItensGrid = parseInt(document.form1.iNumItensGrid.value);
        if (iNumItensGrid < 1) {
            alert('Adicione uma Operação de Crédito.');
            return false;
        }

        var aItensEnviar = [];

        try {
            for (i = 0; i <= iNumItensGrid - 1; i++) {
                var elemento = 'aItensOperacaoCredito[' + i + ']';

                var valorPrevisto = document.form1[elemento + '[c242_vlprevisto]'].value;
                valorPrevisto = isNaN(Number(valorPrevisto)) ? valorPrevisto.replace(/\./g, '').replace(/\,/, '.') : valorPrevisto

                var valorPrevistoAnterior = document.form1[elemento + '[c242_vlprevisto]'].getAttribute('value');
                valorPrevistoAnterior = isNaN(Number(valorPrevistoAnterior)) ? valorPrevistoAnterior.replace(/\./g, '').replace(/\,/, '.') : valorPrevistoAnterior

                if (valorPrevisto > 0 || valorPrevistoAnterior > 0) {

                    var novoItem = {
                        iItem: Number(i),
                        c242_fonte: document.form1[elemento + '[c242_fonte]'].getAttribute('value'),
                        c242_operacaocredito: document.form1[elemento + '[c242_operacaocredito]'].getAttribute('value'),
                        c242_vlprevisto: valorPrevisto,
                        c242_arrecadado: document.form1[elemento + '[bArrecadado]'].getAttribute('value'),
                    }

                    aItensEnviar.push(novoItem);
                }
            }

            var oParam = new Object();
            oParam.exec = 'salvaOperacoesCredito';
            oParam.aItens = aItensEnviar;
            oParam.fValorPrevAno = document.form1.fValorPrevAno.getAttribute('value');

            js_divCarregando('Aguarde', 'div_aguarde');

            var oAjax = new Ajax.Request(sRPC, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_retornoSalvaGeral
            });

        } catch (e) {
            alert(e.toString());
        }

    }

    function js_retornoSalvaGeral(oAjax) {

        js_removeObj('div_aguarde');

        var oRetorno = eval("(" + oAjax.responseText + ")");

        if (oRetorno.status == 1) {
            alert(oRetorno.sMensagem.urlDecode());
            location.reload();
        } else {
            alert(oRetorno.sMensagem.urlDecode());
        }

    }

    function aItens() {
        var itensNum = document.querySelectorAll('.marca_itens');

        return Array.prototype.map.call(itensNum, function(item) {
            return item;
        });

    }

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

    function getItensMarcados() {
        return aItens().filter(function(item) {
            return item.checked;
        });
    }

    function js_exclui() {
        var itens = getItensMarcados();

        if (itens.length < 1) {
            alert('Selecione pelo menos um item da lista.');
            return;

        }

        itens = itens.map(function(item) {
            return Number(item.value);
        });

        try {
            var aItensExcluir = [];
            var bExclui = true;

            itens.forEach(function(item) {

                var elemento = 'aItensOperacaoCredito[' + item + ']';

                var valor_arrecadado = document.form1[elemento + '[valor_arrecadado]'].getAttribute('value').replace(/\./g, '').replace(/\,/, '.');

                if (Number(valor_arrecadado) > 0) {
                    alert('Não é possível excluir uma operação de crédito associada que possua valor arrecadado.');
                    bExclui = false;
                    return false;
                }

                var novoItem = {
                    iItem: Number(item),
                    c242_fonte: document.form1[elemento + '[c242_fonte]'].getAttribute('value'),
                    c242_operacaocredito: document.form1[elemento + '[c242_operacaocredito]'].getAttribute('value')
                }

                aItensExcluir.push(novoItem);
            });

            if (bExclui) {
                var oParam = new Object();
                oParam.exec = 'excluiOperacaoCredito';
                oParam.aItens = aItensExcluir;
                js_divCarregando('Aguarde', 'div_aguarde');

                var oAjax = new Ajax.Request(sRPC, {
                    method: 'post',
                    parameters: 'json=' + Object.toJSON(oParam),
                    onComplete: js_retornoExclui
                });
            }
        } catch (e) {
            alert(e.toString());
        }

    }

    function js_retornoExclui(oAjax) {

        js_removeObj('div_aguarde');
        var oRetorno = eval("(" + oAjax.responseText + ")");

        if (oRetorno.status == 1) {
            alert(oRetorno.sMensagem.urlDecode());
            location.reload();
        } {
            alert(oRetorno.sMensagem.urlDecode());
        }
    }
    
    function js_fecha() {
        parent.db_iframe_conconvprevrec.hide();
    }
    
    parent.$('fechardb_iframe_conconvprevrec').onclick = function() {
        js_fecha();
    }
    
    function js_limpa() {
        document.form1.c242_operacaocredito.value = '';
        document.form1.sObjeto.value = '';
    }
</script>