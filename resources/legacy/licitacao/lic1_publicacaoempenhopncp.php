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
    <title>Contass Contabilidade Ltda - P�gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">

    <style>
        #justificativapncp {
            width: 100%;
            margin-bottom: 7px;
            font-size: 1rem;
        }
    </style>

    <script>
        loadComponents([
            'buttonsSolid',
            'simpleModal'
        ]);
    </script>
</head>
<style>
</style>

<body bgcolor=#CCCCCC>
    <form action="">
        <fieldset style="margin-top:50px;">
            <legend>Publi��o Empenhos PNCP</legend>
            <table style="width:100%">
                <tr>
                    <td colspan="2">
                        <strong>Ambiente: </strong>
                        <select name="ambiente" id="ambiente">
                            <option value="1">Ambiente de Homologao Externa</option>
                        </select>

                        <strong>Tipo: </strong>
                        <select name="tipo" id="tipo">
                            <option value="0">Selecione</option>
                            <option value="1">Inclus�o</option>
                            <option value="2">Retifica��o</option>
                            <option value="3">Exclus�o</option>
                        </select>
                    </td>
                </tr>
                <tr>

                </tr>
                <tr>
                    <td colspan="2">
                        <div id='cntgridempenhos'></div>
                    </td>
                </tr>
            </table>

        </fieldset>
        </br>
        <div id='cntgridempenhos'></div>

        <div style="width: 100%; display: flex; justify-content: center;">
            <?php $component->render('buttons/solid', [
                'designButton' => 'success',
                'onclick' => 'js_clickSendPNCP()',
                'type' => 'button',
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
                'type' => 'button',
                'message' => 'Salvar justificativa PNCP',
                'onclick' => "js_enviar()",
                'size' => 'md'
            ]); ?>
        </div>
    <?php $component->render('modais/simpleModal/endModal', [], true); ?>

    <?php
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
    ?>
</body>

</html>
<script>
    function js_showGrid() {
        oGridEmpenho = new DBGrid('gridEmpenho');
        oGridEmpenho.nameInstance = 'oGridEmpenho';
        oGridEmpenho.setCheckbox(0);
        oGridEmpenho.setCellAlign(new Array("center", "center", "Center", "Left", "Center", "Center"));
        oGridEmpenho.setCellWidth(new Array("5%", "40%", "10%", "40%", "10%", "20%"));
        oGridEmpenho.setHeader(new Array("C�digo", "Objeto", "Empenho", "Fornecedor", "Licita��o", "N�mero de Controle"));
        oGridEmpenho.hasTotalValue = false;
        oGridEmpenho.show($('cntgridempenhos'));

        var width = $('cntgridempenhos').scrollWidth - 30;
        $("table" + oGridEmpenho.sName + "header").style.width = width;
        $(oGridEmpenho.sName + "body").style.width = width;
        $("table" + oGridEmpenho.sName + "footer").style.width = width;
    }
    js_showGrid();

    function js_getEmpenhos() {
        oGridEmpenho.clearAll(true);
        var oParam = new Object();
        oParam.exec = "getEmpenhos";
        js_divCarregando('Aguarde, pesquisando Empenhos', 'msgBox');
        var oAjax = new Ajax.Request(
            'lic1_pncpenvioempenho.RPC.php', {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_retornogetEmpenhos
            }
        );
    }

    function js_retornogetEmpenhos(oAjax) {

        js_removeObj('msgBox');
        oGridEmpenho.clearAll(true);
        var aEventsIn = ["onmouseover"];
        var aEventsOut = ["onmouseout"];
        aDadosHintGrid = new Array();

        var oRetornoEmpenhos = JSON.parse(oAjax.responseText);

        if (oRetornoEmpenhos.status == 1) {

            var seq = 0;
            oRetornoEmpenhos.empenhos.each(function(oLinha, iLinha) {
                // print_r(oLinha);exit;
                seq++;
                var aLinha = new Array();
                aLinha[0] = oLinha.sequencial;
                aLinha[1] = oLinha.objeto;
                aLinha[2] = oLinha.empenho;
                aLinha[3] = oLinha.fornecedor;
                aLinha[4] = oLinha.licitacao;
                aLinha[5] = oLinha.numerocontrolepncp;
                oGridEmpenho.addRow(aLinha);

                var sTextEvent = " ";
                if (aLinha[1] !== '') {
                    sTextEvent += "<b>objeto: </b>" + aLinha[1];
                } else {
                    sTextEvent += "<b>Nenhum dado  mostrar</b>";
                }

                var oDadosHint = new Object();
                oDadosHint.idLinha = `gridEmpenhorowgridEmpenho${iLinha}`;
                oDadosHint.sText = sTextEvent;
                aDadosHintGrid.push(oDadosHint);
            });

            oGridEmpenho.renderRows();
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
    js_getEmpenhos();

    function js_enviar() {
        var aEmpenhos = oGridEmpenho.getSelection("object");
        let justificativa = document.getElementById('justificativapncp').value.trim();

        let tipo = $F('tipo');

        if (tipo == 0) {
            alert('Selecione um Tipo');
            return false;
        }

        if (aEmpenhos.length == 0) {
            alert('Nenhum Empenho Selecionado');
            return false;
        }

        var oParam = new Object();
        if (tipo == 1) {
            oParam.exec = "enviarEmpenho";
        } else if (tipo == 2) {
            oParam.exec = "RetificarEmpenho";
        } else {
            oParam.exec = "ExcluirEmpenho";
        }

        if (tipo != 1) {
            oParam.justificativa = justificativa;

            if (justificativa === '') {
                alert('A justificativa n�o pode estar vazia');
                return false;
            }
        }
        
        oParam.ambiente = $F('ambiente');
        oParam.aEmpenhos = new Array();

        for (var i = 0; i < aEmpenhos.length; i++) {

            with(aEmpenhos[i]) {
                var empenho = new Object();
                empenho.sequencialpncp = aCells[6].getValue();
                empenho.codigo = aCells[1].getValue();
                empenho.processo = aCells[2].getValue();

                oParam.aEmpenhos.push(empenho);
            }
        }

        js_divCarregando('Aguarde, Enviando empenhos', 'msgBox');
        var oAjax = new Ajax.Request(
            'lic1_pncpenvioempenho.RPC.php', {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_returnEnvPncp
            }
        );
    }

    function js_returnEnvPncp(oAjax) {
        js_removeObj('msgBox');
        var oRetornoEmpenhos = eval('(' + oAjax.responseText + ")");
        if (oRetornoEmpenhos.status == '2') {
            alert(oRetornoEmpenhos.message.urlDecode());
            // window.location.href = "aco1_pncppublicacaocontrato001.php";
        } else {
            let tipo = $F('tipo');
            if (tipo == 1)
                alert('Enviado com sucesso !');
            if (tipo == 2)
                alert('Retifica��o enviada com sucesso!');
            if (tipo == 3)
                alert('Exclus�o efetuada com sucesso!');
            window.location.href = "lic1_publicacaoempenhopncp.php";
        }
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
            js_enviar();
        }

    }
</script>