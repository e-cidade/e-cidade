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

db_postmemory($HTTP_POST_VARS);
$oPost = db_utils::postMemory($_POST);
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

</head>
<style>
    .container {
        margin-top: 10px;
        background-color: #F5FFFB;
        padding: 10px;
        max-width: 800px;
        width: 100%;
        font-size: 12px;
    }

    .button-container {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 10px;
    }

    fieldset>div {
        margin-bottom: 10px;
        /* Espaçamento entre as linhas */
    }
</style>

<body style="font-family: Arial; background-color: #F5FFFB">
    <div class="container">
        <fieldset>
            <legend><strong>Prazo de Entrega</strong></legend>
            <div>
                <label for="sequencial"><strong>Sequencial:</strong></label>
                <?php db_input('sequencial', '', '', true, 'text', 3, 'style="width: 80px;"', '', '', '', '', 'form-control'); ?>
            </div>
            <div>
                <label for="descricao"><strong>Descrição:</strong></label>
                <input type="text" name="descricao" id="descricao" style="width: 700px; text-transform: uppercase;" class="form-control" oninput="this.value = this.value.toUpperCase();">
                </div>
            <div>
                <label for="ativo"><strong>Ativo:</strong></label>
                <select class="form-control" name="ativo" id="ativo" style="width: 80px;">
                    <option value="true">Sim</option>
                    <option value="false">Não</option>
                </select>
            </div>
        </fieldset>
        <div class="button-container">
            <input name="incluir" type="submit" class="btn btn-primary" value="Alterar" onclick="alteraPrazo()">
            <input name="incluir" type="submit" class="btn btn-secondary" value="Pesquisar" onclick="js_pesquisa()">
        </div>

    </div>
</body>

</html>

<script>
    const url = 'com_prazoentrega.RPC.php';

    js_pesquisa();

    function js_pesquisa() {
    js_OpenJanelaIframe('', 'db_iframe_prazos', 'func_prazos.php?funcao_js=parent.js_preenchepesquisa|Sequencial', 'Pesquisa', true);
    }


    function js_preenchepesquisa(Sequencial) {
    document.getElementById('sequencial').value = Sequencial;
    db_iframe_prazos.hide();
    js_getPrazosCadastrados();
    }

    function js_getPrazosCadastrados() {
        let oParam = {};
        let pc97_sequencial = document.getElementById('sequencial').value;
        oParam.exec = "getPrazos";
        oParam.sequencial = pc97_sequencial;
        new Ajax.Request(url, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoPrazosCadastrados
        });
    }

    function js_retornoPrazosCadastrados(oAjax) {
        let oRetorno = JSON.parse(oAjax.responseText);
        let prazoentrega = oRetorno.prazo;

        document.getElementById('descricao').value = prazoentrega[0].pc97_descricao;
        document.getElementById('ativo').value = prazoentrega[0].pc97_ativo;
    }

    function alteraPrazo() {
        let oParam = {};
        oParam.aItens = [];

        let item = {};
        item.sequencial = document.getElementById('sequencial').value;
        item.descricao = document.getElementById('descricao').value;
        item.ativo = document.getElementById('ativo').value;


        oParam.aItens.push(item);

        oParam.exec = 'alteraPrazo';
        const oAjax = new Ajax.Request(
            url, {
                parameters: 'json=' + Object.toJSON(oParam),
                asynchronous: false,
                method: 'post',
                onComplete: js_retornoalteraPrazo
            });
    }

    function js_retornoalteraPrazo(oAjax) {
        const oRetorno = eval('(' + oAjax.responseText + ")");

        alert('Alterado com sucesso!');
        document.getElementById('sequencial').value = '';
        document.getElementById('descricao').value = '';
        document.getElementById('ativo').value = 't';
        js_pesquisa();
    }
</script>