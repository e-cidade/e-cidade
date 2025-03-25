<?php
    require("libs/db_stdlib.php");
    require("libs/db_conecta.php");
    include("libs/db_sessoes.php");
    include("libs/db_usuariosonline.php");
    include("dbforms/db_funcoes.php");
    include("dbforms/db_classesgenericas.php");
    include("classes/db_pcparam_classe.php");

    db_postmemory($HTTP_GET_VARS);
    db_postmemory($HTTP_POST_VARS);

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <?php
        db_app::load("scripts.js", date('YmdHis'));
        db_app::load("strings.js");
        db_app::load("prototype.js");
        db_app::load("datagrid.widget.js");
        db_app::load("dbcomboBox.widget.js");
        db_app::load("dbmessageBoard.widget.js");
        db_app::load("dbtextField.widget.js");
        db_app::load("widgets/DBHint.widget.js");
        db_app::load("widgets/dbautocomplete.widget.js");
        db_app::load("widgets/windowAux.widget.js");
        db_app::load("estilos.css, grid.style.css");
        db_app::load("mask.js");
        db_app::load("form.js");
        db_app::load("estilos.bootstrap.css");
        db_app::load("sweetalert.js");
        db_app::load("just-validate.js");
        db_app::load("tabsmanager.js");
        db_app::load("accordion.js");
        db_app::load("dynamicloader.js");
    ?>
    <style>
        body {
            background-color: #CCCCCC;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }
        .container {
            margin-top: 20px; /* Espaï¿½o acima do container */
            background-color: #FFFFFF;
            padding: 20px;
            max-width: 100%; /* Largura mï¿½xima do conteï¿½do */
            width: 1024px; /* Para garantir responsividade */
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
        form {
            margin-top: 10px;
        }
        select{
            width: 100%;
        }
        .DBJanelaIframeTitulo{
            text-align: left;
        }
        label{
            font-weight: bold;
        }
        .contain-buttons{
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
        }

        #tablegridListagemPorItemheader{
            width: calc(100% - 18px) !important;
        }

        #gridListagemPorItembody{
            width: 100% !important;
        }
        #gridListagemPorItembody tbody tr td:first-child{
            text-align: center !important;
        }
        #gridItensPorLotebody tbody tr td:first-child{
            text-align: center !important;
        }

        fieldset{
            padding: 10px;
        }

    </style>
</head>
<body class="container">
    <ul class="nav nav-pills" id="dynamicNav"></ul>
    <div class="mt-3" id="contentArea"></div>
</body>
</html>
<script>
    const url = 'lic_dispensasinexigibilidades.RPC.php';
    const l20_codigo = '<?= $l20_codigo ?>';
    const isDisabledButtonsLote = <?= isset($isDisabledButtonsLote) && $isDisabledButtonsLote == 1 ? 'true' : 'false' ?>;
    const isProcessoItem = <?= isset($isProcessoItem) && $isProcessoItem == 1 ? 'true' : 'false' ?>;
    const isProcessoLote = <?= isset($isProcessoLote) && $isProcessoLote == 1 ? 'true' : 'false' ?>;

    let limit = 10;

    let currentUrlForm = new URL('lic_frm_dispensasinexigibilidades001.php', window.location.href);
    currentUrlForm.searchParams.set('l20_codigo', l20_codigo);

    let currentUrlTabItem = new URL('lic_frm_dispensasinexigibilidades002.php', window.location.href);
    currentUrlTabItem.searchParams.set('l20_codigo', l20_codigo);

    let currentUrlTabLote = new URL('lic_frm_dispensasinexigibilidades003.php', window.location.href);
    currentUrlTabLote.searchParams.set('l20_codigo', l20_codigo);

    let currentUrlTabDotacoes = new URL('lic_frm_dispensasinexigibilidades004.php', window.location.href);
    currentUrlTabDotacoes.searchParams.set('l20_codigo', l20_codigo);

    let currentUrlTabPublicacoes = new URL('lic_frm_dispensasinexigibilidades005.php', window.location.href);
    currentUrlTabPublicacoes.searchParams.set('l20_codigo', l20_codigo);

    const menus = [];
    if(l20_codigo != null && l20_codigo != ''){
        menus.push({ label: 'Dispensas/Inexigibilidades', url: currentUrlForm.toString() });
        menus.push({ label: 'Itens', url: currentUrlTabItem.toString() });
        menus.push({ label: 'Lotes', url: currentUrlTabLote.toString() });
        menus.push({ label: 'Publicações', url: currentUrlTabPublicacoes.toString() });
    } else {
        menus.push({ label: 'Dispensas/Inexigibilidades', url: currentUrlForm.toString() },);
    }

    const dynamicLoader = new DynamicLoader('dynamicNav', 'contentArea', 'iso-8859-1');
    const accordion = new Accordion('.accordion', { singleOpen: false });
    dynamicLoader.initialize(menus);

    document.getElementById('contentArea').addEventListener('contentLoaded', (event) => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(event.detail.content, 'text/html');
        // parent.resizeFrame();
    });

    document.addEventListener('eventProcessoCompraAdd', (event) => {
        dynamicLoader.enableMenu('Lotes');
    });

    document.addEventListener('eventProcessoCompraRemove', (event) => {
        dynamicLoader.disableMenu('Lotes');
    });

    function validateToggleBlockTabLote(l20_tipojulg, oParamNumManual, isRegistroPreco = false){
        if(oParamNumManual.pc30_permsemdotac && !isRegistroPreco){
            dynamicLoader.addMenu('Dotações', currentUrlTabDotacoes.toString());
        }

        if(l20_tipojulg == '1'){
            dynamicLoader.removeMenu('Lotes')
            return false;
        }
    }

    function closeLote(reloading = true, isNotClose = false, reloadItens = false){
        if(typeof db_iframe_lote_dispensa != 'undefined' && !isNotClose){
            db_iframe_lote_dispensa.hide();
            window.scroll({
                top: document.body.scrollHeight,
                left: 0,
                behavior: 'smooth' // Opções: 'auto' ou 'smooth'
            });
        }

        if(reloading){
            if(typeof loadLotes == 'function'){
                loadLotes();
            }

            if(typeof loadingPorLotes == 'function' && reloadItens){
                loadingPorLotes();
            }
        }
    }

    function getProcessosVinculadosValidacao(){
        let oParam = {};
        oParam.exec = 'getProcessosComprasVinculados';
        oParam.l20_codigo = l20_codigo;

        let oAjax = new Ajax.Request(url, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: function(oAjax){
                let oRetorno = JSON.parse(oAjax.responseText);
                const tipoJulgamento = document.getElementById('l20_tipojulg');
                if(oRetorno.data.length > 0){
                    dynamicLoader.enableMenu('Lotes');
                    if(tipoJulgamento != null && tipoJulgamento.value){
                        tipoJulgamento.classList.add('disabled');
                    }
                } else {
                    dynamicLoader.disableMenu('Lotes');
                    if(tipoJulgamento != null && tipoJulgamento.value){
                        tipoJulgamento.classList.remove('disabled');
                    }
                }
            }
        });
    }
</script>
