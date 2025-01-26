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
    <title>Contass Gest�o e Tecnologia Ltda - P&aacute;gina Inicial</title>
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
</head>
<body class="body-default">
<div class="container">
    <form name="form1">
        <input name="pesquisar" type="submit" id="pesquisar"  title="Atualiza a Consulta" value="Atualizar" >
        <fieldset class="form-container">
            <legend>Documentos</legend>
            <fieldset style='width:900px; margin-top: 10px;'>
                <legend>Pendentes de Assinatura</legend>
                <div id='ctnGridDocumentos'></div>

            </fieldset>
            <input name="assinar" type="button" id="assinar"  title="Assinar todos" value="Assinar Selecionados" onclick="js_assinarVarios();">
            <fieldset style='width:900px; margin-top: 10px;'>
                <legend>Documentos Assinados</legend>
                <div id='ctnGridDocumentosAssinados'></div>
            </fieldset>
        </fieldset>
    </form>
</div>
</body>
<?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
<script>
    var aCargos = [ 'Outros', 'Ordenador Despesa', 'Ordenador Liquida��o', 'Ordenador Pagamento', 'Contador', 'Tesoureiro', 'Gestor', 'Controle Interno' ];
    var aDocumentos = [ 'Empenho', 'Liquida��o',  'Pagamento', 'Pagamento Extra' ];
    var sRpc = 'con1_assinaturaDigitalDocumentos.RPC.php';

    var oGridDocumentos          = new DBGrid('oGridDocumentos');
    oGridDocumentos.nameInstance = 'oGridDocumentos';
    oGridDocumentos.setCheckbox(0);
    oGridDocumentos.setSelectAll(true);
    oGridDocumentos.setHeader( [  'UUID', 'Nome Arquivo', 'Arquivo',  'A��o' ] );
    oGridDocumentos.setCellWidth( [ '30%', '50%', '10%', '10%' ] );
    oGridDocumentos.setCellAlign( [ 'center', 'left', 'center', 'center' ] );
    oGridDocumentos.setHeight(130);
    oGridDocumentos.hasTotalizador = false;
    oGridDocumentos.show($("ctnGridDocumentos"));

    var oGridDocumentosAssinados          = new DBGrid('gridDocumentosAssinados');
    oGridDocumentosAssinados.nameInstance = 'oGridDocumentosAssinados';
    oGridDocumentosAssinados.setCellWidth( [ '80%', '20%' ] );
    oGridDocumentosAssinados.setHeader( [  'Nome Arquivo', 'Arquivo' ] );
    oGridDocumentosAssinados.setCellAlign( [ 'left', 'center' ] );
    oGridDocumentosAssinados.setHeight(130);
    oGridDocumentosAssinados.show($("ctnGridDocumentosAssinados"));

    init = function() {
        js_getDocumentos();
    };

    function js_getDocumentos() {
        js_divCarregando('Aguarde, carregando os dados!', 'msgBox');
        oGridDocumentos.clearAll(true);
        oGridDocumentosAssinados.clearAll(true);
        var oParametros = new Object();
        oParametros.sExecuta = 'getDocumentos';
        var oAjax = new Ajax.Request(sRpc, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametros),
            onComplete: function(oResponse) {
                var oRetorno = eval("(" + oResponse.responseText + ")");
                var aDocumentos = [];
                if(oRetorno.aDocumentos.length > 0) {
                    aDocumentos = JSON.parse(oRetorno.aDocumentos).ocs.data.data;
                }
                sUrlBase = oRetorno.link_base;
                if (oRetorno.erro) {
                    alert("Erro: "+oRetorno.sMensagem);
                    js_removeObj('msgBox');
                    return;
                }
                js_carregarTabelas(aDocumentos, sUrlBase);
                js_removeObj('msgBox');
            }
        });
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
        aDocumentos = JSON.parse(oRetorno.aDocumentos).ocs.data.data;
        alert('Documento assinado com sucesso!');
        var sUrlBase = oRetorno.link_base;
        js_carregarTabelas(aDocumentos, sUrlBase);
    }

    function js_carregarTabelas(aDocumentos, sUrlBase) {
        var iTotalItensAssinados   = 0;
        var iTotalItensNaoAssinados   = 0;
        oGridDocumentos.clearAll(true);
        oGridDocumentosAssinados.clearAll(true);

        aDocumentos.each(function(oDocumento, iSeq) {
            var sign = js_getSignerUUID(oDocumento.signers);
            if(!sign.signed){
                var aLinha = new Array();
                aLinha[0] = sign.sign_uuid;
                aLinha[1] = oDocumento.name;
                aLinha[2] = "<a href="+sUrlBase+oDocumento.file+" target='_blank' >Link Arquivo</a>";
                aLinha[3] = "<input type='button' style='background: green; color: white;' onclick=js_Assinar(\'" + sign.sign_uuid + "\') value='Assinar'/>";
                oGridDocumentos.addRow(aLinha);
                iTotalItensNaoAssinados++
                return;
            }
        });
        oGridDocumentos.renderRows();
        oGridDocumentos.setNumRows(iTotalItensNaoAssinados);

        aDocumentos.each(function(oDocumento, iSeq) {
            var sign = js_getSignerUUID(oDocumento.signers);
            if( sign.signed){
                var aLinhaAssinados = new Array();
                aLinhaAssinados[0] = oDocumento.name;
                aLinhaAssinados[1] = "<a href="+sUrlBase+oDocumento.file+" target='_blank' >Link Arquivo</a>";
                oGridDocumentosAssinados.addRow(aLinhaAssinados);
                iTotalItensAssinados++
                return;
            }
        });

        oGridDocumentosAssinados.renderRows();
        oGridDocumentosAssinados.setNumRows(iTotalItensAssinados);
    }

    function js_getSignerUUID(signers){
        for (let i = 0; i < signers.length; i++) {
            if (signers[i].me == true) {
                return {sign_uuid: signers[i].sign_uuid, signed: signers[i].signed};
            }
        }
        return null;
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
            onComplete: js_completarAssinarVarios
        });
    }

    function js_completarAssinarVarios(oAjax) {
        js_removeObj('msgBox');
        var oRetorno = eval("(" + oAjax.responseText + ")");
        if (oRetorno.erro) {
            alert("Erro: "+oRetorno.sMensagem);
            return;
        }
        aDocumentos = JSON.parse(oRetorno.aDocumentos).ocs.data.data;
        alert('Documento assinado com sucesso!');
        var sUrlBase = oRetorno.link_base;
        js_carregarTabelas(aDocumentos, sUrlBase);
    }

    init();
</script>
