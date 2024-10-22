<?php
db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js,dbautocomplete.widget.js");
db_app::load("dbmessageBoard.widget.js, prototype.js, dbtextField.widget.js, dbcomboBox.widget.js, widgets/DBHint.widget.js");
db_app::load("estilos.css, grid.style.css");
?>
<form name="form1" method="post" action="">
    <table border="0">
        <tr>
            <td>
                <strong>Tipo: </strong>
            </td>
            <td>
                <select name="tipo" id="tipo" style="width: 91px;" onchange="js_verificatipo();">
                    <option value="0">Selecione</option>
                    <option value="1">Inclusão</option>
                    <option value="2">Retificação</option>
                    <option value="3">Exclusão</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <?
                db_ancora('Contrato:', "js_pesquisacontrato(true);", $db_opcao);
                ?>
            </td>
            <td>
                <?
                db_input('ac16_sequencial', 10, $Iac16_sequencial, true, 'text', 3, "");
                ?>
                <?
                db_input('ac16_objeto', 80, $Iac16_objeto, true, 'text', 3, '');
                ?>
            </td>
        </tr>
    </table>

    <div style="width: 100%; display: flex; justify-content: center;">
        <?php $component->render('buttons/solid', [
            'designButton' => 'success',
            'type' => 'button',
            'onclick' => "js_getTermos()",
            'message' => 'Processar',
            'size' => 'md'
        ]); ?>
    </div>

    <table style="width: 100%">
        <tr>
            <td colspan="2">
                <strong>Ambiente: </strong>
                <select name="ambiente" id="ambiente">
                    <option value="1">Ambiente de Homologao Externa</option>
                </select>
            </td>
        </tr>
    </table>
    <fieldset>
        <legend><b>Itens</b></legend>
        <div id='cntgriditens'></div>
    </fieldset>
    </br>

    <div style="width: 100%; display: flex; justify-content: center;">
        <?php $component->render('buttons/solid', [
            'designButton' => 'success',
            'type' => 'button',
            'onclick' => "js_clickSendPNCP()",
            'message' => 'Enviar para PNCP',
            'size' => 'md'
        ]); ?>
    </div>
</form>

<?php $component->render('modais/simpleModal/startModal', [
    'title' => 'Justificativa para o PNCP',
    'id' => 'justificativaModal',
    'size' => 'lg'
], true); ?>

    <?php db_textarea('justificativapncp', 10, 48, false, true, 'text', $db_opcao, "", "", "justificativapncp", "255"); ?>
       
    <div style="width: 100%; display: flex; justify-content: center;">
        <?php $component->render('buttons/solid', [
            'designButton' => 'success',
            'onclick' => 'js_enviarTermo();',
            'message' => 'Salvar justificativa PNCP',
            'size' => 'md'
        ]); ?>
    </div>
<?php $component->render('modais/simpleModal/endModal', [], true); ?>

<style>
  #justificativapncp {
    width: 100%;
    margin-bottom: 7px;
    font-size: 1rem;
  }
</style>

<script type="text/javascript">
    var form = document.getElementById('justificativaForm');

    loadComponents([
        'buttonsSolid',
        'simpleModal'
    ]);

    function js_showGrid() {
        let opcao = "<?= $db_opcao ?>";
        oGridItens = new DBGrid('gridItens');
        oGridItens.nameInstance = 'oGridItens';
        if (opcao != 2) {
            oGridItens.setCheckbox(0);
        }
        oGridItens.setCellAlign(new Array("center", "center", "center", "center", "center", 'center', 'center'));
        oGridItens.setCellWidth(new Array("5%", "10%", "5%", '25%', '15%','5%', '35%'));
        oGridItens.setHeader(new Array("Código", "Vigência", "Número", "Tipo de Alteração", "Data de inclusão", "Nº Termo PNCP","Justificativa"));
        oGridItens.hasTotalValue = false;
        oGridItens.show($('cntgriditens'));

        var width = $('cntgriditens').scrollWidth - 30;
        $("table" + oGridItens.sName + "header").style.width = width;
        $(oGridItens.sName + "body").style.width = width;
        $("table" + oGridItens.sName + "footer").style.width = width;
    }

    function js_pesquisacontrato(mostra) {
        let tipo = document.getElementById('tipo').value;

        if (mostra == true && tipo != 0) {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_liclicita', 'func_contratacaopncp.php?funcao_js=parent.js_mostraContrato1|ac16_sequencial|ac16_objeto', 'Pesquisa', true);
        }else{
            alert('Selecione o Tipo.');
        }
    }

    function js_mostraContrato1(chave1, chave2) {
        document.form1.ac16_sequencial.value = chave1;
        document.form1.ac16_objeto.value = chave2;
        db_iframe_liclicita.hide();
    }

    function js_getTermos() {

        oGridItens.clearAll(true);
        var oParam = new Object();
        oParam.iContrato = $F('ac16_sequencial');
        oParam.iTipo = $F('tipo');
        oParam.exec = "getTermos";
        js_divCarregando('Aguarde, pesquisando Itens', 'msgBox');
        var oAjax = new Ajax.Request(
            'aco1_enviotermospncp.RPC.php', {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_retornoGetItens
            }
        );
    }

    function js_retornoGetItens(oAjax) {

        js_removeObj('msgBox');
        oGridItens.clearAll(true);
        var aEventsIn = ["onmouseover"];
        var aEventsOut = ["onmouseout"];
        aDadosHintGrid = new Array();
        aDadosHintGridlote = new Array();

        var oRetornoitens = JSON.parse(oAjax.responseText);

        var nTotal = new Number(0);

        if (oRetornoitens.status == 1) {

            var seq = 0;
            oRetornoitens.dados.each(function(oLinha, iLinha) {
                seq++;
                var aLinha = new Array();
                aLinha[0] = oLinha.codigo;
                aLinha[1] = oLinha.vigencia.urlDecode();
                aLinha[2] = oLinha.numeroAditamento;
                aLinha[3] = oLinha.situacao.urlDecode();
                aLinha[4] = oLinha.data;
                if(oLinha.numtermopncp){
                    aLinha[5] = Number(oLinha.numtermopncp);
                }else{
                    aLinha[5] = '';
                }
                aLinha[6] = oLinha.Justificativa.urlDecode();
                oGridItens.addRow(aLinha);

            });
            oGridItens.renderRows();

            aDadosHintGrid.each(function(oHint, id) {
                var oDBHint = eval("oDBHint_" + id + " = new DBHint('oDBHint_" + id + "')");
                oDBHint.setText(oHint.sText);
                oDBHint.setShowEvents(aEventsIn);
                oDBHint.setHideEvents(aEventsOut);
                oDBHint.setPosition('B', 'L');
                oDBHint.setUseMouse(true);
                oDBHint.make($(oHint.idLinha), 3);
            });

            aDadosHintGridlote.each(function(oHintlote, id) {
                var oDBHintlote = eval("oDBHintlote_" + id + " = new DBHint('oDBHintlote_" + id + "')");
                oDBHintlote.setText(oHintlote.sTextlote);
                oDBHintlote.setShowEvents(aEventsIn);
                oDBHintlote.setHideEvents(aEventsOut);
                oDBHintlote.setPosition('B', 'L');
                oDBHintlote.setUseMouse(true);
                oDBHintlote.make($(oHintlote.idLinha), 4);
            });

        }
    }

    js_showGrid();

    function js_enviarTermo() {
        var aTermo = oGridItens.getSelection("object");
        let justificativa = document.getElementById('justificativapncp').value.trim();

        if (aTermo.length == 0) {
            alert('Nenhum item Selecionado');
            return false;
        }

        let tipo = $F('tipo');
        var oParam = new Object();
        oParam.ambiente = $F('ambiente');
        oParam.aTermo = new Array();
        oParam.iContrato = $F('ac16_sequencial');

        switch (tipo) {
            case '1':
                oParam.exec = "enviarTermo";
                for (var i = 0; i < aTermo.length; i++) {
                    with(aTermo[i]) {
                        var iTermos = new Object();
                        iTermos.codigo = Number(aCells[1].getValue());
                        iTermos.numeroaditamento = Number(aCells[3].getValue());
                        oParam.aTermo.push(iTermos);
                    }
                }
                break;

            case '2':
                oParam.exec = "retificarTermo";
                oParam.justificativa = justificativa;

                if (justificativa === '') {
                    alert('A justificativa não pode estar vazia');
                    return false;
                }

                for (var i = 0; i < aTermo.length; i++) {
                    with(aTermo[i]) {
                        var iTermos = new Object();
                        iTermos.codigo = Number(aCells[1].getValue());
                        iTermos.numeroaditamento = Number(aCells[3].getValue());
                        iTermos.codigotermo = Number(aCells[6].getValue());
                        oParam.aTermo.push(iTermos);
                    }
                }
                break;

            case '3':
                oParam.exec = "excluirTermo";
                oParam.justificativa = justificativa;
                
                if (justificativa === '') {
                    alert('A justificativa não pode estar vazia');
                    return false;
                }

                for (var i = 0; i < aTermo.length; i++) {
                    with(aTermo[i]) {
                        var iTermos = new Object();
                        iTermos.codigotermo = Number(aCells[6].getValue());
                        oParam.aTermo.push(iTermos);
                    }
                }
                break;

            default:
                // Código a ser executado se nenhum dos casos acima for verdadeiro
        }

        js_divCarregando('Aguarde, Processando', 'msgBox');
        var oAjax = new Ajax.Request(
            'aco1_enviotermospncp.RPC.php', {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_returnEnvPncp
            }
        );
    }

    function js_returnEnvPncp(oAjax) {
        closeModal('justificativaModal');
        clearModaFieldsRenderComponents();
        js_removeObj('msgBox');
        var oRetornoResultado = eval('(' + oAjax.responseText + ")");
        alert(oRetornoResultado.message.urlDecode());
        js_verificatipo();
    }

    function js_verificatipo(){
        js_getTermos();
    }

    function js_clickSendPNCP() {

        let tipo = $F('tipo');

        if (tipo == 0) {
            alert('Selecione um Tipo');
            return false;
        }

        if (tipo != 1) {
            openModal('justificativaModal');
        } else {
            js_enviarTermo();
        }

    }

</script>
