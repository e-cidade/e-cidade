<?php

require_once(modification("libs/db_stdlib.php"));
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_libdicionario.php");
require_once("libs/db_liborcamento.php");
require_once("model/protocolo/AssinaturaDigital.model.php");
$oAssintaraDigital =  new AssinaturaDigital();

?>
<html>
<head>
    <title>Contass Gestão e Tecnologia Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("scripts.js,
                  prototype.js,
                  strings.js,
                  arrays.js,
                  windowAux.widget.js,
                  datagrid.widget.js,
                  dbmessageBoard.widget.js,
                  dbcomboBox.widget.js,
                  dbtextField.widget.js,
                  DBInputHora.widget.js,
                  datagrid/plugins/DBOrderRows.plugin.js,
                  datagrid/plugins/DBHint.plugin.js,
                  AjaxRequest.js");
    db_app::load("estilos.css, grid.style.css");
    ?>
    <style>
        .pendente {
            background-color: rgb(232, 232, 26);
        }
        #tabela-assinantes-detalhado {
            border-collapse: collapse;
            width: 100%;
            padding: 5px;
        }
        #tabela-assinantes-detalhado th{
            background-color: #057518;
            color: #FFFFFF;
        }
        #tabela-assinantes-detalhado tr {
            background-color: #fff;
        }

        #tabela-assinantes-detalhado td, #tabela-assinantes-detalhado th {
            border: 1px solid #ddd;
            padding-left: 5px;
        }
    </style>
</head>
<body class="body-default">
<div class="container">
    <form name="form1">

        <fieldset class="form-container">
            <div>

            </div>
            <strong>Status Documentos: </strong>
            <select name="statusDocumentos" id="statusDocumentos" onchange="js_SelectDocumentos()">
                <option value="1">Pendentes de Assinatura</option>
                <option value="2">Assinados</option>
            </select>
            <legend>Documentos</legend>
            <fieldset style='width:900px; margin-top: 10px;' id=ctnGridDocumentosPedentesField>
                <legend>Pendentes de Assinatura</legend>
                <div id='ctnGridDocumentos' style="padding: 5px"></div>
                <input name="anterior" type="button" id="anterior" title="Anterior" value="Anterior" onclick="js_getAnterior()">
                <input name="proximo" type="button" id="proximo" title="Próximo" value="Próximo" onclick="js_getProximo()" >
                <input name="assinar" type="button" id="assinar"  title="Assinar todos" value="Assinar Selecionados" onclick="js_assinarVarios();">
                <input name="pesquisar" type="submit" id="pesquisar" title="Atualiza a Consulta" value="Atualizar" >

            </fieldset>

            <input type="hidden" value="0" id="pagina-anterior"/>
            <input type="hidden" value="0" id="pagina-proxima"/>




            <fieldset style='width:900px; margin-top: 10px;' id='ctnGridDocumentosAssinadosField'>
                <legend>Documentos Assinados</legend>
                <div id='ctnGridDocumentosAssinados' ></div>
                <input name="anterior" type="button" id="anterior" title="Anterior" value="Anterior" onclick="js_getAnterior()">
                <input name="proximo" type="button" id="proximo" title="Próximo" value="Próximo" onclick="js_getProximo()" >
                <div style="padding: 5px">
                    <b>Os documentos destacados em amarelo possuem assinaturas pendentes.</b>
                </div>
            </fieldset>



        </fieldset>
    </form>
</div>
</body>
<?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
<script>
    var aCargos = [ 'Outros', 'Ordenador Despesa', 'Ordenador Liquidação', 'Ordenador Pagamento', 'Contador', 'Tesoureiro', 'Gestor', 'Controle Interno' ];
    var aDocumentos = [ 'Empenho', 'Liquidação',  'Pagamento', 'Pagamento Extra' ];
    var sRpc = 'con1_assinaturaDigitalDocumentos.RPC.php';
    var oGridDocumentos          = new DBGrid('oGridDocumentos');
    var oGridDocumentosAssinados = new DBGrid('gridDocumentosAssinados');

    document.getElementById("ctnGridDocumentosAssinadosField").style.display = "none";

    init = function() {
        js_getDocumentosPendentes(1);
    };

    function js_getAnterior() {
        var status = document.getElementById("statusDocumentos");
        if(status.value == 1) {
            js_getDocumentosPendentes(document.getElementById("pagina-anterior").value);
        }
        js_getDocumentosAssinados(document.getElementById("pagina-anterior").value);
    }

    function js_getProximo() {
        var status = document.getElementById("statusDocumentos");
        if(status.value == 1) {
            js_getDocumentosPendentes(document.getElementById("pagina-proxima").value);
        }
        js_getDocumentosAssinados(document.getElementById("pagina-proxima").value);
    }

    function js_SelectDocumentos(){
        var status = document.getElementById("statusDocumentos");
        if(status.value == 1){
            document.getElementById("ctnGridDocumentosAssinadosField").style.display = "none";
            document.getElementById("ctnGridDocumentosPedentesField").style.display = "block";
            js_getDocumentosPendentes();
            return;
        }
        document.getElementById("ctnGridDocumentosAssinadosField").style.display = "block";
        document.getElementById("ctnGridDocumentosPedentesField").style.display = "none";
        js_getDocumentosAssinados();
    }

    function contains500(str) {
        return str.includes("<statuscode>500</statuscode>");
    }
    function js_getDocumentosPendentes(iPagina) {
        js_divCarregando('Aguarde, carregando os dados!', 'msgBox');
        oGridDocumentos.clearAll(true);
        var oParametros = new Object();
        oParametros.sExecuta = 'getDocumentosPendentes';
        oParametros.iPagina = iPagina;
        var oAjax = new Ajax.Request(sRpc, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametros),
            onComplete: function(oResponse) {
                var oRetorno = eval("(" + oResponse.responseText + ")");
                if (oRetorno.erro) {
                    alert("Erro: "+oRetorno.sMensagem);
                    js_removeObj('msgBox');
                    return;
                }
                var aDocumentosTela = [];
                var ocsSemAssinatura = {};
                var ocsParcialmenteAssinado = {};

                if(!contains500(oRetorno.aDocumentos.aDocumentosSemAssinatura)){
                    ocsSemAssinatura = JSON.parse(oRetorno.aDocumentos.aDocumentosSemAssinatura).ocs;
                }
                if(!contains500(oRetorno.aDocumentos.aDocumentosParcialmenteAssinados)) {
                    ocsParcialmenteAssinado = JSON.parse(oRetorno.aDocumentos.aDocumentosParcialmenteAssinados).ocs;
                }

                if ( Object.keys(ocsSemAssinatura).length !== 0 && ocsSemAssinatura.data.data.length > 0) {
                    js_getPaginacao(ocsSemAssinatura);
                    aDocumentosTela = ocsSemAssinatura.data.data;
                }
                if (Object.keys(ocsParcialmenteAssinado).length !== 0 &&  ocsParcialmenteAssinado.data.data.length > 0) {
                    js_getPaginacao(ocsParcialmenteAssinado);
                    aDocumentosTela = [...aDocumentosTela, ...ocsParcialmenteAssinado.data.data].filter((item, i, array) => i === array.findIndex((t) => t.nodeId === item.nodeId));
                }
                sUrlBase = oRetorno.link_base;
                js_carregarTabelasSemAssinatura(aDocumentosTela, sUrlBase);
                js_removeObj('msgBox');
            }
        });
    }

    function js_getDocumentosAssinados(iPagina) {
        js_divCarregando('Aguarde, carregando os dados!', 'msgBox');
        oGridDocumentosAssinados.clearAll(true);
        var oParametros = new Object();
        oParametros.sExecuta = 'getDocumentosAssinados';
        oParametros.iPagina = iPagina;
        var oAjax = new Ajax.Request(sRpc, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametros),
            onComplete: function(oResponse) {
                var oRetorno = eval("(" + oResponse.responseText + ")");
                if (oRetorno.erro) {
                    alert("Erro: "+oRetorno.sMensagem);
                    js_removeObj('msgBox');
                    return;
                }
                var aDocumentosAssinadosTela = [];
                var ocsParcialmenteAssinado = {};
                var ocsAssinados = {};

                if(!contains500(oRetorno.aDocumentosAssindados.aDocumentosParcialmenteAssinados)) {
                    ocsParcialmenteAssinado = JSON.parse(oRetorno.aDocumentosAssindados.aDocumentosParcialmenteAssinados).ocs;
                }
                if(!contains500(oRetorno.aDocumentosAssindados.aDocumentosAssinados)) {
                    ocsAssinados = JSON.parse(oRetorno.aDocumentosAssindados.aDocumentosAssinados).ocs;
                }

                if (Object.keys(ocsParcialmenteAssinado).length !== 0 && ocsParcialmenteAssinado.data.data.length > 0) {
                    js_getPaginacao(ocsParcialmenteAssinado);
                    aDocumentosAssinadosTela = ocsParcialmenteAssinado.data.data;
                }

                if (Object.keys(ocsAssinados).length !== 0 &&  ocsAssinados.data.data.length > 0) {
                    js_getPaginacao(ocsAssinados);
                    aDocumentosAssinadosTela = [...aDocumentosAssinadosTela, ...ocsAssinados.data.data].filter((item, i, array) => i === array.findIndex((t) => t.nodeId === item.nodeId));
                }

                sUrlBase = oRetorno.link_base;

                js_carregarTabelasAssinados(aDocumentosAssinadosTela, sUrlBase);
                js_removeObj('msgBox');
            }
        });
    }

    function js_getPaginacao(aDocumentos)
    {
        sPaginacao = aDocumentos.data.pagination;
        iPaginaAnterior = sPaginacao.prev ? sPaginacao.prev.split('?')[1].split('&')[1].split('=')[1] : 1;
        iPaginaProxima = sPaginacao.next ? sPaginacao.next.split('?')[1].split('&')[1].split('=')[1] : 2;
        iPaginaAtual =  sPaginacao.current ? sPaginacao.current.split('?')[1].split('&')[0].split('=')[1] : 2;
        iPaginaFinal =  sPaginacao.last ? sPaginacao.last.split('?')[1].split('&')[0].split('=')[1] : iPaginaAtual;
        document.getElementById("pagina-anterior").value = iPaginaAnterior;
        document.getElementById("pagina-proxima").value = iPaginaProxima;

        js_AtivarPaginacao((iPaginaFinal == iPaginaAtual), "proximo");
        js_AtivarPaginacao((iPaginaAnterior == 1 && iPaginaProxima == 2), "anterior");
    }

    function js_AtivarPaginacao(bCondicao, sBotao)
    {
        if (bCondicao) {
            document.getElementById(sBotao).disabled = true;
            return;
        }
        document.getElementById(sBotao).disabled = false;
        return;
    }

    function js_Assinar(uuid) {
        js_divCarregando('Aguarde, Assinando Documento!', 'msgBox');
        var oParametros = new Object();
        oParametros.sExecuta = 'assinarDocumento';
        oParametros.sUuid = uuid;
        var oAjax = new Ajax.Request(sRpc, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametros),
            onComplete: js_completarAssinar
        });
    }

    function js_completarAssinar(oAjax) {
        js_removeObj('msgBox');
        var oRetorno = eval("(" + oAjax.responseText + ")");
        if (oRetorno.erro) {
            alert("Erro: "+oRetorno.sMensagem);
            return;
        }
        var aDocumentosTela = [];
        var ocsSemAssinatura = {};
        var ocsParcialmenteAssinado = {};

        if(!contains500(oRetorno.aDocumentos.aDocumentosSemAssinatura)){
            ocsSemAssinatura = JSON.parse(oRetorno.aDocumentos.aDocumentosSemAssinatura).ocs;
        }
        if(!contains500(oRetorno.aDocumentos.aDocumentosParcialmenteAssinados)) {
            ocsParcialmenteAssinado = JSON.parse(oRetorno.aDocumentos.aDocumentosParcialmenteAssinados).ocs;
        }

        if ( Object.keys(ocsSemAssinatura).length !== 0 && ocsSemAssinatura.data.data.length > 0) {
            js_getPaginacao(ocsSemAssinatura);
            aDocumentosTela = ocsSemAssinatura.data.data;
        }
        if (Object.keys(ocsParcialmenteAssinado).length !== 0 &&  ocsParcialmenteAssinado.data.data.length > 0) {
            js_getPaginacao(ocsParcialmenteAssinado);
            aDocumentosTela = [...aDocumentosTela, ...ocsParcialmenteAssinado.data.data].filter((item, i, array) => i === array.findIndex((t) => t.nodeId === item.nodeId));
        }

        alert('Documento assinado com sucesso!');
        var sUrlBase = oRetorno.link_base;
        js_carregarTabelasSemAssinatura(aDocumentosTela, sUrlBase);
    }

    function js_carregarTabelasSemAssinatura(aDocumentos, sUrlBase) {
        var iTotalItensNaoAssinados   = 0;
        oGridDocumentos              = new DBGrid('oGridDocumentos');
        oGridDocumentos.nameInstance = 'oGridDocumentos';
        oGridDocumentos.setCheckbox(0);
        oGridDocumentos.setSelectAll(true);
        oGridDocumentos.setHeader( [  'UUID', 'Nome Arquivo', 'Arquivo',  'Ação' ] );
        oGridDocumentos.setCellWidth( [ '20', '540', '100', '100' ] );
        oGridDocumentos.setCellAlign( [ 'center', 'left', 'center', 'center' ] );
        oGridDocumentos.setHeight(130);
        oGridDocumentos.hasTotalizador = false;
        oGridDocumentos.show($("ctnGridDocumentos"));
        oGridDocumentos.clearAll(true);
        aDocumentos.each(function(oDocumento, iSeq) {

            var sign = js_getSignerUUID(oDocumento.signers);
            if(!sign.signed){
                var aLinha = new Array();
                aLinha[0] = sign.sign_uuid;
                aLinha[1] = oDocumento.name;
                var todoAssinado =  "<a href="+sUrlBase+oDocumento.file+" target='_blank' >Link Arquivo</a>";
                if(oDocumento.status !== 3){
                    todoAssinado = `<a href="javascript:js_imprimirSemAssinar('${oDocumento.uuid}')" >Link Arquivo</a>`;
                }
                aLinha[2] = todoAssinado;
                aLinha[3] = "<input type='button' style='background: green; color: white;' onclick=js_Assinar(\'" + sign.sign_uuid + "\') value='Assinar'/>";
                oGridDocumentos.addRow(aLinha);
                iTotalItensNaoAssinados++
                return;
            }
        });
        oGridDocumentos.renderRows();
        oGridDocumentos.setNumRows(iTotalItensNaoAssinados);

    }

    function js_carregarTabelasAssinados(aDocumentos, sUrlBase) {

        var iTotalItensAssinados   = 0;
        oGridDocumentosAssinados          = new DBGrid('oGridDocumentosAssinados');
        oGridDocumentosAssinados.nameInstance = 'oGridDocumentosAssinados';
        oGridDocumentosAssinados.setHeader( [  'Nome Arquivo', 'Assinantes','Arquivo' ] );
        oGridDocumentosAssinados.setCellWidth( [ '700', '100', '100' ] );
        oGridDocumentosAssinados.setCellAlign( [ 'left', 'center', 'center' ] );
        oGridDocumentosAssinados.setHeight(130);
        oGridDocumentosAssinados.show($("ctnGridDocumentosAssinados"));
        oGridDocumentosAssinados.clearAll(true);

        for (var iNotas = 0; iNotas < aDocumentos.length; iNotas++) {
            if(js_getAssinadoNaoPorUsuarioLogado(aDocumentos[iNotas].signers)) {
                continue;
            }
            var aLinhaAssinados = [];
            aLinhaAssinados[0] = aDocumentos[iNotas].name;
            aLinhaAssinados[1] =  "<a href='#' onclick='js_Assinantes("+JSON.stringify(aDocumentos[iNotas].signers)+")'>(+) Assinates</a>";
            var todoAssinado = "<a href=" + sUrlBase + aDocumentos[iNotas].file + " target='_blank' >Link Arquivo</a>";
            if (aDocumentos[iNotas].status !== 3) {
                todoAssinado = `<a href="javascript:js_imprimirSemAssinar('${aDocumentos[iNotas].uuid}')" >Link Arquivo</a>`;
            }
            aLinhaAssinados[2] = todoAssinado;
            oGridDocumentosAssinados.addRow(aLinhaAssinados);
            iTotalItensAssinados++
            js_linha_pendencia(aDocumentos[iNotas].name, aDocumentos[iNotas].status);

        }
        oGridDocumentosAssinados.renderRows();
        oGridDocumentosAssinados.setNumRows(iTotalItensAssinados);
    }

    function js_getSignerUUID(signers){
        for (let i = 0; i < signers.length; i++) {
            if (signers[i].me) {
                return {sign_uuid: signers[i].sign_uuid, signed: signers[i].signed};
            }
        }
        return null;
    }

    function js_getAssinadoNaoPorUsuarioLogado(signers){
        for (let i = 0; i < signers.length; i++) {
            if (signers[i].me && !signers[i].signed) {
                return true;
            }
        }
        return false;
    }

    function js_imprimirSemAssinar(uuid)
    {
        var oParametros = new Object();
        oParametros.method = 'post';
        oParametros.uuid = uuid;
        oParametros.sExecuta = 'imprimirDocumentoSemAssinatura';
        window.open('con1_assinaturaDigitalDocumentos.RPC.php?json=' + encodeURIComponent(JSON.stringify(oParametros)), "_blank");
    }

    function js_assinarVarios(){
        var aDocumentosSecionados = oGridDocumentos.getSelection("object");
        if(aDocumentosSecionados.length <= 0){
            alert("Selecione pelo menos 1 arquivo!");
            return;
        }
        var aDocumentos = [];
        for(let oDocumento of aDocumentosSecionados){
            aDocumentos.push(oDocumento.aCells[0].getValue());
        }
        js_divCarregando('Aguarde, Assinando Documentos!', 'msgBox');
        var oParametros = new Object();
        oParametros.sExecuta = 'assinarDocumentoLote';
        oParametros.aDocumentos = aDocumentos;
        var oAjax = new Ajax.Request(sRpc, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametros),
            onComplete: js_completarAssinar
        });
    }

    function js_linha_pendencia(row, status) {
        if(oGridDocumentosAssinados.aRows && status !== 3){
            let elemento = oGridDocumentosAssinados.aRows.find( linha => linha.aCells[0].content === row);
            elemento.setClassName('pendente');
        }
    }

    function js_Assinantes(aAssinates) {
        windowAssinantes = new windowAux('wndAssinantes', 'Assinantes Detalhados', 800, 350);
        var sContent = "<div class='grid_detalhamentos' id='grid_detalhamentos' style='width: 100%; text-align: center; overflow: hidden; '>";
        sContent += "       <table id='tabela-assinantes-detalhado'>";
        sContent += "           <thead>";
        sContent += "               <tr>";
        sContent += "                   <th style='width: 70%;'>Nome</th>";
        sContent += "                   <th style='width: 30%;'>Status</th>";
        sContent += "               </tr>";
        sContent += "           </thead>";
        sContent += "           <tbody>";
        aAssinates.forEach(function (data, index) {
        sContent += "           <tr>";
        sContent += "              <td >" + data.displayName + "</td>";
        sContent += "              <td>" + (data.signed ? 'ASSINADO' : 'SEM ASSINATURA') + "</td>";
        sContent += "           </tr>";
        });
        sContent += "           </tbody>";
        sContent += "       </table>";
        sContent += "</div>";

        windowAssinantes.setContent(sContent);

        windowAssinantes.setShutDownFunction(function () {
            windowAssinantes.destroy();
        });

        var w = ((screen.width - 800) / 2);
        var h = ((screen.height / 2) - 300);
        windowAssinantes.setIndex(5);
        windowAssinantes.allowDrag(false);
        windowAssinantes.show(h, w);
    }

    init();
</script>
