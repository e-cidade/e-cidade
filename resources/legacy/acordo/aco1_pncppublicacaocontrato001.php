<?php
    require("libs/db_stdlib.php");
    require("libs/db_conecta.php");
    include("libs/db_sessoes.php");
    include("libs/db_usuariosonline.php");
    include("dbforms/db_funcoes.php");
    require_once("libs/renderComponents/index.php");

    db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js,dbautocomplete.widget.js");
    db_app::load("dbmessageBoard.widget.js, prototype.js, dbtextField.widget.js, dbcomboBox.widget.js, widgets/DBHint.widget.js");
    db_app::load("estilos.css, grid.style.css");
    db_app::load("time.js");
?>

<html>
    <head>
        <title>Contass Contabilidade Ltda - Pgina Inicial</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="Expires" CONTENT="0">
        <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
        <link href="estilos.css" rel="stylesheet" type="text/css">
    </head>

    <body bgcolor=#CCCCCC>
        <form action="">
            <fieldset style="margin-top:50px;">
                <legend>Publiï¿½ï¿½o Contratos PNCP</legend>
                <table style="width:100%">
                    <tr>
                        <td colspan="2">
                            <strong>Ambiente: </strong>
                            <select name="ambiente" id="ambiente">
                                <option value="1">Ambiente de Homologao Externa</option>
                            </select>

                            <strong>Tipo: </strong>
                            <select name="tipo" id="tipo" onchange="js_getContratos()">
                                <option value="1">Inclusão</option>
                                <option value="2">Retificação</option>
                                <option value="3">Exclusão</option>
                            </select>

                            </select>
                                <strong>Ano competência: </strong>
                                <input type="number" name="anocompetencia" id="anocompetencia" onclick="js_getContratos()" min="1111" max="9999" />
                            </select>
                        </td>
                    </tr>
                    <tr>

                    </tr>
                    <tr>
                        <td colspan="2">
                            <div id='cntgridcontratos'></div>
                        </td>
                    </tr>
                </table>
            </fieldset>

            </br>

            <div id='cntgridcontratos'></div>
        </form>

        <div style="width: 100%; display: flex; justify-content: center;">
            <?php $component->render('buttons/solid', [
                'designButton' => 'success',
                'onclick' => 'js_clickSendPNCP()',
                'message' => 'Enviar para PNCP',
                'size' => 'md'
            ]); ?>
        </div>

        <?php $component->render('modais/simpleModal/startModal', [
            'title' => 'Justificativa para o PNCP',
            'id' => 'justificativaModal',
            'size' => 'lg'
        ], true); ?>
            <?php db_textarea('justificativapncp', 10, 48, false, true, 'text', $db_opcao, "", "", "justificativapncp", "255"); ?>

            <div style="width: 100%; display: flex; justify-content: center;">
                <?php $component->render('buttons/solid', [
                    'designButton' => 'success',
                    'type' => 'submit',
                    'message' => 'Salvar justificativa PNCP',
                    'onclick' => "js_saveJustificativaPNCP()",
                    'size' => 'md'
                ]); ?>
            </div>
        <?php $component->render('modais/simpleModal/endModal', [], true); ?>

        <?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
    </body>
</html>

<style>
    #justificativapncp {
        width: 100%;
        margin-bottom: 7px;
        font-size: 1rem;
    }
</style>

<script>
    var btn = document.getElementById('openModalBtn');

    var anoCompetenciaInput = document.getElementById("anocompetencia");
    var anoAtual = new Date().getFullYear();
    anoCompetenciaInput.value = anoAtual;

    function js_showGrid() {
        oGridContrato = new DBGrid('gridContrato');
        oGridContrato.nameInstance = 'oGridContrato';
        oGridContrato.setCheckbox(0);
        oGridContrato.setCellAlign(new Array("center", "center", "Center", "Left", "Center", "Center"));
        oGridContrato.setCellWidth(new Array("5%", "40%", "10%", "40%", "10%", "20%"));
        oGridContrato.setHeader(new Array("Código", "Objeto", "Contrato", "Fornecedor", "Licitação", "Número de Controle"));
        oGridContrato.setHeight(400);
        oGridContrato.hasTotalValue = false;
        oGridContrato.show($('cntgridcontratos'));

        var width = $('cntgridcontratos').scrollWidth - 30;
        $("table" + oGridContrato.sName + "header").style.width = width;
        $(oGridContrato.sName + "body").style.width = width;
        $("table" + oGridContrato.sName + "footer").style.width = width;
    }
    js_showGrid();

    function js_getContratos() {
        oGridContrato.clearAll(true);
        let anocompetencia = document.getElementById("anocompetencia").value;
        let tipo = document.getElementById("tipo").value;

        var oParam = new Object();
        oParam.ano = anocompetencia;
        oParam.tipo = tipo;
        oParam.exec = "getContratos";
        js_divCarregando('Aguarde, pesquisando Contratos', 'msgBox');
        var oAjax = new Ajax.Request(
            'aco1_pncpenviocontrato.RPC.php', {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_retornogetContratos
            }
        );
    }

    function js_retornogetContratos(oAjax) {

        js_removeObj('msgBox');
        oGridContrato.clearAll(true);
        var aEventsIn = ["onmouseover"];
        var aEventsOut = ["onmouseout"];
        aDadosHintGrid = new Array();

        var oRetornoContratos = JSON.parse(oAjax.responseText);

        if (oRetornoContratos.status == 1) {

            var seq = 0;
            oRetornoContratos.contratos.each(function(oLinha, iLinha) {
                // print_r(oLinha);exit;
                seq++;
                var aLinha = new Array();
                aLinha[0] = oLinha.sequencial;
                aLinha[1] = oLinha.objeto;
                aLinha[2] = oLinha.contrato;
                aLinha[3] = oLinha.fornecedor;
                aLinha[4] = oLinha.licitacao;
                aLinha[5] = oLinha.numerocontrolepncp;
                oGridContrato.addRow(aLinha);

                var sTextEvent = " ";
                if (aLinha[1] !== '') {
                    sTextEvent += "<b>objeto: </b>" + aLinha[1];
                } else {
                    sTextEvent += "<b>Nenhum dado  mostrar</b>";
                }

                var oDadosHint = new Object();
                oDadosHint.idLinha = `gridContratorowgridContrato${iLinha}`;
                oDadosHint.sText = sTextEvent;
                aDadosHintGrid.push(oDadosHint);
            });

            oGridContrato.renderRows();
            aDadosHintGrid.each(function(oHint, id) {
                var oDBHint = eval("oDBHint_" + id + " = new DBHint('oDBHint_" + id + "')");
                oDBHint.setText(oHint.sText);
                oDBHint.setShowEvents(aEventsIn);
                oDBHint.setHideEvents(aEventsOut);
                oDBHint.setPosition('B', 'L');
                oDBHint.setUseMouse(false);
                oDBHint.make($(oHint.idLinha), 3);
            });
        }
    }
    js_getContratos();

    function js_enviar() {
        var aContratos = oGridContrato.getSelection("object");

        let tipo = $F('tipo');

        var oParam = new Object();
        if (tipo == 1) {
            oParam.exec = "enviarContrato";
        } else if (tipo == 2) {
            oParam.exec = "RetificarContrato";
        } else {
            oParam.exec = "ExcluirContrato";
        }
        oParam.ambiente = $F('ambiente');
        oParam.aContratos = new Array();

        for (var i = 0; i < aContratos.length; i++) {

            with(aContratos[i]) {
                var contrato = new Object();
                contrato.codigo = aCells[1].getValue();
                contrato.processo = aCells[2].getValue();
                contrato.sequencialpncp = aCells[6].getValue();
                oParam.aContratos.push(contrato);
            }
        }

        js_divCarregando('Aguarde, Enviando contratos', 'msgBox');
        var oAjax = new Ajax.Request(
            'aco1_pncpenviocontrato.RPC.php', {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_returnEnvPncp
            }
        );
    }

    function js_returnEnvPncp(oAjax) {
        js_removeObj('msgBox');
        var oRetornoContratos;
        try {
            oRetornoContratos = JSON.parse(oAjax.responseText);
        } catch (e) {
            console.error('Erro ao parsear o JSON:', e);
            alert('Erro ao processar a resposta do servidor.');
            return;
        }

        if (oRetornoContratos.status == '2') {
            alert(oRetornoContratos.message.urlDecode().replace(/"/g, '').replace(',error', ''));
        } else {
            let tipo = $F('tipo');
            if (tipo == 1)
                alert('Enviado com sucesso !');
            if (tipo == 2)
                alert('Retificação enviada com sucesso!');
            if (tipo == 3)
                alert('Exclusão efetuada com sucesso!');
            window.location.href = "aco1_pncppublicacaocontrato001.php";
        }
    }

    function js_clickSendPNCP() {

        let tipo = $F('tipo');
        var aContratos = oGridContrato.getSelection("object");

        if (tipo == 0) {
            alert('Selecione um Tipo');
            return false;
        }

        if (aContratos.length == 0) {
            alert('Nenhum Contrato Selecionado');
            return false;
        }

        if (tipo != 1) {
            openModal('justificativaModal');
        } else {
            js_enviar();
        }

    }

    // Manipular o envio do formulário dentro do modal
    function js_saveJustificativaPNCP() {

        var oParam = {};
        justificativa = document.getElementById('justificativapncp').value;
        oParam.justificativa = justificativa;
        oParam.exec = "AdicionarJustificativaPncp";

        if (justificativa == '') {
            alert('A justificativa nï¿½o pode estar vazia');
            return false;
        }

        var aContratos = oGridContrato.getSelection("object");

        oParam.ambiente = $F('ambiente');
        oParam.aContratos = new Array();

        for (var i = 0; i < aContratos.length; i++) {
            with(aContratos[i]) {
                var contrato = new Object();
                contrato.codigo = aCells[1].getValue();
                oParam.aContratos.push(contrato);
            }
        }

        js_divCarregando('Aguarde, Enviando contratos', 'msgBox');
        var oAjax = new Ajax.Request(
            'aco1_pncpenviocontrato.RPC.php', {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: function(xhr) {
                    js_removeObj('msgBox');
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status == '1') {
                            closeModal('justificativaModal');
                            clearModaFieldsRenderComponents();
                            js_enviar();
                        } else {
                            alert(response.message);
                        }
                    } else {
                        alert('Erro na requisição');
                    }
                }
            }
        );
    }
</script>
