<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js,dbautocomplete.widget.js");
db_app::load("dbmessageBoard.widget.js, prototype.js, dbtextField.widget.js, dbcomboBox.widget.js, widgets/DBHint.widget.js");
db_app::load("estilos.css, grid.style.css");
db_app::load("time.js");

?>
<!DOCTYPE html>
<html>

<head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <style>
        body {
            background-color: #CCCCCC;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh; /* Para ocupar a altura completa da janela */
        }
        .container {
            margin-top: 20px; /* Espaço acima do container */
            background-color: #FFFFFF;
            padding: 20px;
            max-width: 967px; /* Largura máxima do conteúdo */
            width: 100%; /* Para garantir responsividade */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra leve */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .tdleft {
            text-align: left;
        }
        .tdright {
            text-align: right;
        }
        #pc80_codproc,#pc80_codprocexcluir {
            width: 115px;
        }
        #pc80_resumo{
            width: 835px;
            height: 86px;
        }
        form {
            margin-top: 10px;
        }
    </style>
</head>

<body>
<div class="container">
    <form name="form1" method="post" action="">
        <table>
            <tr>
                <td class="tdright">
                    <strong>Licitação:</strong>
                </td>
                <td class="tdleft">
                    <?php
                    db_input('licitacao', 15, 'l20_codigo', true, 'text', 3);
                    ?>
                </td>
            </tr>
            <tr>
                <td class="tdright">
                    <strong>Processo de compra:</strong>
                </td>
                <td class="tdleft">
                    <select name="pc80_codproc" id="pc80_codproc" onchange="js_getDadosProcessoCompras(this.value)">
                        <option value="0">Selecione</option>
                    </select>

                    <strong>Processos Vinculados:</strong>

                    <select name="pc80_codprocexcluir" id="pc80_codprocexcluir">
                    </select>
                </td>
            </tr>
            <tr>
                <td class="tdright">
                    <strong>Data:</strong>
                </td>
                <td class="tdleft">
                    <?php
                    db_input('pc80_data', 15, 'pc80_data', true, 'text', 3);
                    ?>
                </td>
            </tr>
            <tr>
                <td class="tdright">
                    <strong>Usuário:</strong>
                </td>
                <td class="tdleft">
                    <?php
                    db_input('usuario', 60, 'usuario', true, 'text', 3);
                    ?>
                </td>
            </tr>
            <tr>
                <td class="tdright">
                    <strong>Resumo:</strong>
                </td>
                <td class="tdleft">
                    <textarea id="pc80_resumo" style="background-color: #DEB887; color: black" disabled></textarea>
                </td>
            </tr>
        </table>
        <div style="padding: 10px">
            <input type="button" value="Salvar" onclick="js_salvarProcesso();">
            <input type="button" value="Excluir" onclick="js_excluirProcesso();">
            <input type="button" value="Exportar CSV" onclick="js_exportarcsv();">
        </div>
        <fieldset>
            <legend><b>Itens</b></legend>
            <div id='cntgriditens'></div>
        </fieldset>
    </form>
</div>
</body>

</html>

<script>
    const url = 'lic1_licitaitens.RPC.php';
    js_getProcessodecompras();
    js_getProcessosdecomprasVinculados();
    js_showGrid();

    function js_showGrid() {
        oGridItens = new DBGrid('gridItens');
        oGridItens.nameInstance = 'oGridItens';
        oGridItens.setCheckbox(0);
        oGridItens.setCellAlign(new Array("center","center", "center", "center", 'center', 'center', 'center', 'center'));
        oGridItens.setCellWidth(new Array("8%" , "6%"     , "30%"     , '30%'          ,   '6%'    , '6%'        , '6%', '6%'));
        oGridItens.setHeader(new Array("Código","Nº Item", "Descrição", "Complemento do Item", "Qtde","Unidade", "ME/EPP", "Sigiloso",""));
        oGridItens.setHeight(200);
        oGridItens.hasTotalValue = false;
        oGridItens.show($('cntgriditens'));

        var width = $('cntgriditens').scrollWidth - 30;
        $("table" + oGridItens.sName + "header").style.width = width;
        $(oGridItens.sName + "body").style.width = width;
        $("table" + oGridItens.sName + "footer").style.width = width;
    }

    function js_getProcessodecompras() {
        let oParam = {};
        oParam.exec = "getProcessosdecompras";
        oParam.l20_codigo = document.getElementById('licitacao').value;
        let oAjax = new Ajax.Request(url, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoProcessosdecompras
        });
    }

    function js_retornoProcessosdecompras(oAjax){
        let oRetorno = eval("(" + oAjax.responseText + ")");
        let selectpcproc = document.getElementById('pc80_codproc');

        let aPcproc = oRetorno.pcproc;

        aPcproc.each(function(oPcproc, iSeq) {
            let option = document.createElement("option");
            option.value = oPcproc.pc80_codproc;
            option.text = oPcproc.pc80_codproc;
            selectpcproc.appendChild(option);
        });
    }

    function js_getProcessosdecomprasVinculados() {
        let oParam = {};
        let l20_codigo = document.getElementById('licitacao').value;
        oParam.exec = "getProcessosdecomprasVinculados";
        oParam.l20_codigo = l20_codigo;
        let oAjax = new Ajax.Request(url, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoProcessosdeComprasVinculados
        });
    }

    function js_retornoProcessosdeComprasVinculados(oAjax){
        let oRetorno = eval("(" + oAjax.responseText + ")");
        let selectpcproc = document.getElementById('pc80_codprocexcluir');
        let aPcproc = oRetorno.pcproc;
        aPcproc.each(function(oPcproc, iSeq) {
            let option = document.createElement("option");
            option.value = oPcproc.pc81_codproc;
            option.text = oPcproc.pc81_codproc;
            selectpcproc.appendChild(option);
        });
    }

    function js_getDadosProcessoCompras(codproc){
        oGridItens.clearAll(true);
        js_divCarregando('Aguarde Carregando Itens !','msgBox');
        let oParam = {};
        oParam.exec = "getDadosProcessoCompras";
        oParam.codproc = codproc;
        let oAjax = new Ajax.Request(url, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoProcessodeCompras
        });
    }

    function js_retornoProcessodeCompras(oAjax){
        let oRetorno = eval("(" + oAjax.responseText + ")");
        js_removeObj("msgBox");
        let oPcproc = oRetorno.pcproc;
        document.getElementById('usuario').value = oPcproc.usuario;
        document.getElementById('pc80_data').value = oPcproc.pc80_data;
        document.getElementById('pc80_resumo').value = oPcproc.pc80_resumo;

        let aPcprocItens = oRetorno.itens;
        aPcprocItens.each(function (oLinha, iLinha){
            let aLinha  = [];
            aLinha[0] = oLinha.pc01_codmater;
            aLinha[1] = oLinha.pc11_seq;
            aLinha[2] = oLinha.pc01_descrmater;
            aLinha[3] = oLinha.pc01_complmater;
            aLinha[4] = oLinha.pc11_quant;
            aLinha[5] = oLinha.m61_descr;
            aLinha[6] = new DBComboBox('meEpp' + iLinha, 'meEpp' + iLinha,null,'100%');
            if(oLinha.pc11_reservado === true){
                aLinha[6].addItem('1', 'Sim');
            }else{
                aLinha[6].addItem('0', 'Não');
            }
            aLinha[6].lDisabled = true;
            aLinha[7] = new DBComboBox('Sigilo' + iLinha, 'Sigilo' + iLinha,null,'100%');
            aLinha[7].addItem('f', 'Não');
            aLinha[7].addItem('t', 'Sim');
            aLinha[8] = oLinha.pc81_codprocitem;

            oGridItens.addRow(aLinha, false,false,false);
        });
        oGridItens.renderRows();
    }

    function js_salvarProcesso(){

        let itens = oGridItens.getSelection("object");

        if (itens.length === 0) {
            alert('Nenhum Item Selecionado !');
            return false;
        }
        //js_divCarregando('Aguarde Salvando Itens !','msgBox');

        let oParam = {};
        oParam.exec = "salvarProcesso";
        oParam.l20_codigo = document.getElementById('licitacao').value;
        oParam.pc80_codproc = document.getElementById('pc80_codproc').value;
        oParam.aItens = [];

        for (let i = 0; i < itens.length; i++) {

            with(itens[i]) {
                let item = {};
                item.pc01_codmater = aCells[0].getValue();
                item.l21_ordem = aCells[2].getValue();
                item.l21_reservado = aCells[7].getValue();
                item.l21_sigilo = aCells[8].getValue();
                item.pc81_codprocitem = aCells[9].getValue();
                oParam.aItens.push(item);
            }
        }

        let oAjax = new Ajax.Request(url, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoSalvarProcesso
        });
    }

    function js_retornoSalvarProcesso(oAjax){
        let oRetorno = eval("(" + oAjax.responseText + ")");
        js_removeObj("msgBox");
        if (oRetorno.status === 2){
            alert(oRetorno.message.urlDecode());
        }else{
            oGridItens.clearAll(true);
            js_getProcessosdecomprasVinculados();

            select = document.getElementById('pc80_codproc');
            let pcproc = document.getElementById('pc80_codproc').value;

            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].value === pcproc) {
                    select.remove(i);
                    break;
                }
            }

            select.value = '0';

            alert("Salvo com Sucesso !");
        }
        location.reload();
    }

    function js_excluirProcesso(){
        let oParam = {};
        oParam.exec = "excluirProcesso";
        oParam.processo = document.getElementById('pc80_codprocexcluir').value;
        oParam.l20_codigo = document.getElementById('licitacao').value;

        let oAjax = new Ajax.Request(url, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoExcluirProcesso
        });
    }

    function js_retornoExcluirProcesso(oAjax){
        let licitacao = <?= $licitacao; ?>;
        let oRetorno = eval("(" + oAjax.responseText + ")");
        if (oRetorno.status === 2){
            alert(oRetorno.message.urlDecode());
        }else{
            document.location.href = 'lic1_liclicitemalt001.php?licitacao='+licitacao;
            alert("Processo excluido com Sucesso !");
        }
    }

    function js_exportarcsv(){
        let licitacao = <?= $licitacao; ?>;
        jan = window.open('lic2_relitenhtml002.php?l20_codigo=' + licitacao + '&separador=;&delimitador=1&layout=1&ocultaCabecalho=true',
            '', 'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
        jan.moveTo(0, 0);
    }
</script>
