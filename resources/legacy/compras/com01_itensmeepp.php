<?php
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js,dbautocomplete.widget.js");
db_app::load("dbmessageBoard.widget.js, prototype.js, dbtextField.widget.js, dbcomboBox.widget.js, widgets/DBHint.widget.js");
db_app::load("grid.style.css");
db_app::load("time.js");
db_app::load("estilos.bootstrap.css");
if($si01_impjustificativa == "f"){
    $si01_impjustificativa='false';
}else{
    $si01_impjustificativa='true';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<style>
    #pc80_codproc {
        width: 93px;
    }
    #pc80_resumo {
        width: 600px;
    }
    .container {
        margin-top: 20px; /* Espaço acima do container */
        background-color: #f5fffb;
        padding: 20px;
        max-width: 1500px; /* Largura máxima do conteudo */
        width: 100%; /* Para garantir responsividade */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra leve */
        font-size: 12px;
    }

    .btn{
        font-size: 12px;
    }

    .contain-buttons-plano{
        display: flex;
        align-items: center;
    }
    .contain-buttons-plano{
        display: flex;
        gap: 10px;
        align-items: center;
        justify-content: center;
        margin: 10px;
    }

    .contain-inputs-plano{
        display: flex;
        gap: 10px;
        margin: 10px;
    }

    #gridItensrow0checkbox{
        width: 30px !important;
    }

    #col1{
        width: 30px !important;
    }

    .linhagrid{
        font-size: 12px;
    }
    body {
        background-color: #f5fffb !important;
    }
    .linhacota{
        background-color: #c0bfff;
    }
    .linhacotanaosalvo{
        background-color: red;
    }
</style>
<body >
<div class="container">
    <form name="form1" method="post" action="">
        <fieldset style="font-family: Arial">
            <legend><b>Item ME/EPP</b></legend>
            <div class="contain-inputs-plano">
                    <input title="" style="display: none" name="pc80_codproc" type="text" id="pc80_codproc" value="<?=$pc80_codproc?>">
            </div>
        <fieldset>
            <legend><b>Itens</b></legend>
            <div id='cntgriditens'></div>
            <div style="text-align: right;">
                <h3>Valor Total:</h3>
            </div>
        </fieldset>
        </fieldset>
        <div class="col-12 contain-buttons-plano">
            <input type="button" class="btn btn-success" value="Salvar" onclick="js_salvarItensCota();">
            <input type="button" class="btn btn-danger" value="Excluir" onclick="js_excluirItemCota();">
            <input name="imprimir" class="btn btn-Secondary" type="button" value="Imprimir PDF" onclick="js_imprimirRelatorioPDF();">
        </div>
    </form>
</div>
</body>
</html>

<script>
    const url = 'com01_itenscota.RPC.php';
    js_showGrid();
    getItens();

    function js_showGrid() {
        oGridItens = new DBGrid('gridItens');
        oGridItens.nameInstance = 'oGridItens';
        oGridItens.setCheckbox(0);
        oGridItens.setCellAlign(new Array("center","center","left"   ,"center"  ,'center'   ,'center','center'   ,'center','center'   ));
        oGridItens.setCellWidth(new Array("9%"    ,"10%"    ,"90%"      ,'16%'     ,'16%'       ,'16%' ,'12%'       ,'12%'    ,'16%'       ));

        oGridItens.setHeader(new Array(   "Item"  ,"Código","Descrição","Qtd."   ,"Vlr. Unitário", "Total", "Exclusivo","Cota"  ,"Qtd. Cota",""));
        oGridItens.setHeight(350);
        oGridItens.hasTotalValue = false;
        oGridItens.show($('cntgriditens'));

        var width = $('cntgriditens').scrollWidth - 30;
        $("table" + oGridItens.sName + "header").style.width = width;
        $(oGridItens.sName + "body").style.width = width;
        $("table" + oGridItens.sName + "footer").style.width = width;
    }

    function getItens(){
        oGridItens.clearAll(true);
        let oParam = {};
        let pc80_codproc = document.getElementById('pc80_codproc').value;
        oParam.exec = "getItensProcessodeCompras";
        oParam.pc80_codproc = pc80_codproc;
        let oAjax = new Ajax.Request(url, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoItens
        });
    }

    function js_retornoItens(oAjax){
        let oRetorno = eval("(" + oAjax.responseText + ")");
        let aEventsIn  = ["onmouseover"];
        let aEventsOut = ["onmouseout"];
        aDadosHintGrid = [];

        let aPcprocItens = oRetorno.itens;
        document.querySelector('h3').innerHTML = 'Valor Total: R$ '+oRetorno.totalgeral;
        aPcprocItens.each(function (oLinha, iLinha){
            let omeEpp = new DBComboBox("omeEpp"+oLinha.pc11_seq, "omeEpp", null, "");
            omeEpp.addItem("0", "Não");
            omeEpp.addItem("1", "Sim");
            omeEpp.addEvent("onChange", "js_liberarCota(this.value,this.name);");

            let oExclusivo = new DBComboBox("oExclusivo"+oLinha.pc11_seq, "oExclusivo", null, "");
            oExclusivo.addItem("0", "Não");
            oExclusivo.addItem("1", "Sim");
            oExclusivo.addEvent("onChange", "js_liberarExclusivo(this.value,this.name);");

            let idqtd = 'qtd'+oLinha.pc11_seq;
            let idvlrunitario = 'vlrunitario'+oLinha.pc11_seq;
            let idtotal = 'total'+oLinha.pc11_seq;
            let idqtdCota = 'qtdCota'+oLinha.pc11_seq;
            let aLinha  = [];
            aLinha[0] = oLinha.pc11_seq;
            aLinha[1] = oLinha.pc01_codmater;
            aLinha[2] = oLinha.pc01_descrmater.urlDecode().substring(0,70);
            aLinha[3] = "<input readonly style='background-color: #DEB887' class='form-control' type='text' value='"+oLinha.pc11_quant+"' id='"+idqtd+"'>";
            aLinha[4] = "<input readonly style='background-color: #DEB887' class='form-control' type='text' value='"+oLinha.vlrunitario+"' id='"+idvlrunitario+"'>";
            aLinha[5] = "<input readonly style='background-color: #DEB887' class='form-control' type='text' value='"+oLinha.total+"' id='"+idtotal+"'>";
            aLinha[6] = oExclusivo;
            aLinha[7] = omeEpp;
            aLinha[8]= "<input onchange='js_verificaqtd(this.id,this.value)' readonly style='background-color: #DEB887' class='form-control' type='text' value='' id='"+idqtdCota+"'>";
            aLinha[9] = oLinha.pc81_codprocitem;
            oGridItens.addRow(aLinha, false,false,false);

            let sText = "";

            if(oLinha.pc01_complmater.urlDecode() != ""){
                sText = oLinha.pc01_complmater.urlDecode();
            }else{
                sText = "Nenhum dado à mostrar";
            }

            let oDadosHint       = {}
            oDadosHint.idLinha   = `gridItensrowgridItens${iLinha}`;
            oDadosHint.sText = sText;
            aDadosHintGrid.push(oDadosHint);
        });
        oGridItens.renderRows();

        aDadosHintGrid.each(function(oHint, id) {
            let oDBHint    = eval("oDBHint_"+id+" = new DBHint('oDBHint_"+id+"')");
            oDBHint.setText(oHint.sText);
            oDBHint.setShowEvents(aEventsIn);
            oDBHint.setHideEvents(aEventsOut);
            oDBHint.setPosition('B', 'L');
            oDBHint.setUseMouse(true);
            oDBHint.make($(oHint.idLinha), 3);
        });

        getItensCota();
    }

    function js_liberarExclusivo(select, name) {

        const linha = name.substring(10);
        const idqtd = 'qtd' + linha;
        const idqtdcota = 'qtdCota' + linha;
        const idCota = 'omeEpp' + linha;
        const linhaInicial = linha - 1;
        const qtdTotal = document.getElementById(idqtd).value;
        const idqtdcotaElement = document.getElementById(idqtdcota);
        const selectCota = document.getElementById(idCota);
        const gridItensrowgridItens = 'gridItensrowgridItens' + linhaInicial;
        const chkgridItens = 'chkgridItens' + linha;

        if (select === "1") {
            idqtdcotaElement.value = qtdTotal;
            idqtdcotaElement.readOnly = true;
            selectCota.value = 1;
            document.getElementById(gridItensrowgridItens).classList.remove('normal');
            document.getElementById(gridItensrowgridItens).classList.add('marcado');
            document.getElementById(chkgridItens).checked = true;
            oGridItens.selectSingle(document.getElementById(chkgridItens),gridItensrowgridItens,oGridItens.aRows[linhaInicial]);
        } else {
            idqtdcotaElement.value = '';
            idqtdcotaElement.readOnly = false;
            selectCota.value = 0;
            document.getElementById(gridItensrowgridItens).classList.remove('marcado');
            document.getElementById(gridItensrowgridItens).classList.add('normal');
            document.getElementById(chkgridItens).checked = false;
            oGridItens.selectSingle(document.getElementById(chkgridItens),gridItensrowgridItens,oGridItens.aRows[linhaInicial]);
        }

    }

    function js_liberarCota(select,name){

        const linha = name.substring(6);
        const idqtdcota = 'qtdCota' + linha;
        const idqtdcotaElement = document.getElementById(idqtdcota);
        const linhaInicial = linha - 1;
        const gridItensrowgridItens = 'gridItensrowgridItens' + linhaInicial;
        const oExclusivo = 'oExclusivo' + linha;
        const chkgridItens = 'chkgridItens' + linha;

        if (select === "1") {
            idqtdcotaElement.readOnly = false;
            idqtdcotaElement.style.backgroundColor = '#FFF'
            document.getElementById(gridItensrowgridItens).classList.remove('normal');
            document.getElementById(gridItensrowgridItens).classList.add('marcado');
            document.getElementById(chkgridItens).checked = true;
            oGridItens.selectSingle(document.getElementById(chkgridItens),gridItensrowgridItens,oGridItens.aRows[linhaInicial])
        } else {
            idqtdcotaElement.readOnly = true;
            idqtdcotaElement.style.backgroundColor = '#DEB887'
            idqtdcotaElement.value = '';
            document.getElementById(gridItensrowgridItens).classList.remove('marcado');
            document.getElementById(gridItensrowgridItens).classList.add('normal');
            document.getElementById(oExclusivo).value = 0;
            document.getElementById(chkgridItens).checked = false;
            document.getElementById(chkgridItens).checked = false;
            oGridItens.selectSingle(document.getElementById(chkgridItens),gridItensrowgridItens,oGridItens.aRows[linhaInicial])
        }
    }

    function js_verificaqtd(id,qtdCota){
        const linha = id.substring(7);
        const qtd = document.getElementById('qtd'+linha).value;
        if(Number(qtdCota) > Number(qtd)){
            alert('Qtd. Cota maior que quantidade disponivel.');
            document.getElementById('qtdCota'+linha).value = '';
        }
    }

    function js_salvarItensCota(){
        let itens = oGridItens.getSelection("object");

        if (itens.length === 0) {
            alert('Nenhum Item Selecionado !');
            return false;
        }

        js_divCarregando('Aguarde Salvando Iten Cota !','msgBox');

        let oParam = {};
        oParam.exec = "salvarItensCota";
        oParam.pc80_codproc = document.getElementById('pc80_codproc').value;
        oParam.aItens = [];

        for (let i = 0; i < itens.length; i++) {

            with(itens[i]) {
                let qtdCota = aCells[9].getValue();
                if(qtdCota <= 0){
                    alert("Qtd. Cota não informada !");
                    js_removeObj("msgBox");
                    return  false;
                }
                let item = {};
                item.pc11_seq = aCells[0].getValue();
                item.pc01_codmater = aCells[2].getValue();
                item.exclusivo = aCells[7].getValue();
                item.qtdCota = aCells[9].getValue();
                item.pc81_codprocitem = aCells[10].getValue();
                oParam.aItens.push(item);
            }
        }

        let oAjax = new Ajax.Request(url, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoSalvarItensCota
        });
    }

    function js_retornoSalvarItensCota(oAjax){
        let oRetorno = eval("(" + oAjax.responseText + ")");
        js_removeObj("msgBox");
        getItensCota();
        if (oRetorno.status === 2){
            alert(oRetorno.message.urlDecode());
        }else{
            alert('salvo com sucesso!!');
            getItens();
        }
    }

    function getItensCota(){
        let oParam = {};
        let pc80_codproc = document.getElementById('pc80_codproc').value
        oParam.exec = "getItensCota";
        oParam.pc80_codproc = pc80_codproc;
        js_divCarregando('Carregando Itens !','msgBox');

        let oAjax = new Ajax.Request(url, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoItensCota
        });
    }

    function js_retornoItensCota(oAjax)
    {

        let oRetorno = eval("(" + oAjax.responseText + ")");
        js_removeObj("msgBox");
        let aPcprocItens = oRetorno.itens;

        aPcprocItens.each(function (oLinha, iLinha){
            let sExclusivo = 'oExclusivo' + oLinha.pc11_seq;
            let omeEpp = 'omeEpp' + oLinha.pc11_seq;
            const linhaInicial = oLinha.pc11_seq - 1;
            const gridItensrowgridItens = 'gridItensrowgridItens' + linhaInicial;
            let qtdCota = 'qtdCota' + oLinha.pc11_seq;
            if(oLinha.pc11_reservado === true && oLinha.pc11_exclusivo === false){
                document.getElementById(omeEpp).value = 1;
                document.getElementById(sExclusivo).value = 0;
                document.getElementById(omeEpp).disabled = true;
                document.getElementById(sExclusivo).disabled = true;
                document.getElementById(qtdCota).value = oLinha.pc11_quant;
            }

            if(oLinha.bloquear === true){
                document.getElementById(omeEpp).disabled = true;
                document.getElementById(sExclusivo).disabled = true;
            }

            if(oLinha.pc11_exclusivo === true){
                document.getElementById(sExclusivo).value = 1;
                document.getElementById(omeEpp).disabled = true;
                document.getElementById(sExclusivo).disabled = true;
                document.getElementById(qtdCota).value = oLinha.pc11_quant;
            }

            if(oLinha.pc11_reservado === true || oLinha.pc11_exclusivo === true){
                document.getElementById(gridItensrowgridItens).classList.add('linhacota');
            }

        });
    }

    function js_excluirItemCota(){
        let itens = oGridItens.getSelection("object");
        if (itens.length === 0) {
            alert('Nenhum Item Selecionado !');
            return false;
        }

        let oParam = {};
        oParam.exec = "excluirItemCota";
        oParam.pc80_codproc = document.getElementById('pc80_codproc').value;
        oParam.aItens = [];

        for (let i = 0; i < itens.length; i++) {

            with(itens[i]) {
                let item = {};
                item.pc11_seq = aCells[0].getValue();
                item.pc01_codmater = aCells[2].getValue();
                item.exclusivo = aCells[7].getValue();
                item.qtdCota = aCells[9].getValue();
                item.pc81_codprocitem = aCells[10].getValue();
                oParam.aItens.push(item);
            }
        }
        js_divCarregando('Excluindo Itens !','msgBox');

        let oAjax = new Ajax.Request(url, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoExcluirItemCota
        });
    }

    function js_retornoExcluirItemCota(oAjax){
        let oRetorno = eval("(" + oAjax.responseText + ")");
        js_removeObj("msgBox");
        getItensCota();
        if (oRetorno.status === 2){
            alert(oRetorno.message.urlDecode());
        }else{
            alert('Excluido com sucesso!!');
            getItens();
        }
    }

    function js_imprimirRelatorioPDF(){
        let tipoprecoreferencia = <?=$tipoprecoreferencia?>;
        let si01_impjustificativa = <?=$si01_impjustificativa?>;
        let si01_processocompra = document.getElementById('pc80_codproc').value;
        let si01_casasdecimais = <?=$si01_casasdecimais?>;
        jan = window.open('sic1_precoreferencia007.php?impjust='+si01_impjustificativa+'&codigo_preco='+si01_processocompra+'&quant_casas='+si01_casasdecimais+
            '&tipoprecoreferencia='+tipoprecoreferencia,
            'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
        jan.moveTo(0,0);
    }
</script>
