<?php
    require("libs/db_stdlib.php");
    require("libs/db_conecta.php");
    include("libs/db_sessoes.php");
    include("libs/db_usuariosonline.php");
    include("dbforms/db_funcoes.php");
    include("dbforms/db_classesgenericas.php");
    include("classes/db_pcparam_classe.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="Expires" CONTENT="0">
        <title>DBSeller Informática Ltda - Página Inicial</title>
        <?php
            db_app::load("scripts.js");
            db_app::load("prototype.js");
            db_app::load("datagrid.widget.js");
            db_app::load("strings.js");
            db_app::load("grid.style.css");
            db_app::load("classes/dbViewAvaliacoes.classe.js");
            db_app::load("widgets/windowAux.widget.js");
            db_app::load("widgets/dbmessageBoard.widget.js");
            db_app::load("dbcomboBox.widget.js");
            db_app::load("estilos.bootstrap.css");
            db_app::load("sweetalert.js");
            db_app::load("just-validate.js");
            db_app::load("estilos.css", date('YmdHis'));
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
                margin-top: 20px;
                background-color: #FFFFFF;
                padding: 20px;
                max-width: 100%;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            table {
                width: 100%;
                border-collapse: collapse;
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
        </style>
    </head>
    <body class="container">
        <form id="frmFiltro" method="post" style="margin-top: 10px">
            <fieldset class="p-4">
                <legend><b>Pesquisa</b></legend>
                <div class="row">
                    <div class="col-12 col-sm-3 form-group" style="margin-bottom: 10px;">
                        <label for="l20_codigo"><b>Cód. Sequencial: </b></label>
                        <?= db_input(
                            'l20_codigo',
                            10,
                            '',
                            true,
                            'text',
                            44,
                            "",
                            "",
                            "",
                            "",
                            null,
                            'form-control',
                            [
                                'validate-maxlength' => '10',
                                'validate-no-special-chars' => 'true',
                                'validate-integer' => 'true',
                                'validate-maxlength-message' => 'O Cód. Sequencial deve ter no máximo 10 caracteres',
                                'validate-no-special-chars-message' => 'O Cód. Sequencial não deve conter aspas simples, ponto e vírgula ou porcentagem',
                                'validate-integer-message' => 'O campo Cód. Sequencial deve conter apenas numeros'
                            ]
                        ); ?>
                    </div>
                    <div class="col-12 col-sm-3 form-group" style="margin-bottom: 10px;">
                        <label for="l20_edital"><b>Número Processo: </b></label>
                        <?= db_input(
                            'l20_edital',
                            16,
                            '',
                            true,
                            'text',
                            44,
                            "",
                            "",
                            "",
                            "",
                            null,
                            'form-control',
                            [
                                'validate-maxlength' => '16',
                                'validate-no-special-chars' => 'true',
                                'validate-integer' => 'true',
                                'validate-maxlength-message' => 'O Número Processo deve ter no máximo 16 caracteres',
                                'validate-no-special-chars-message' => 'O Número Processo não deve conter aspas simples, ponto e vírgula ou porcentagem',
                                'validate-integer-message' => 'O campo Número Processo deve conter apenas numeros'
                            ]
                        ); ?>
                    </div>
                    <div class="col-12 col-sm-3 form-group" style="margin-bottom: 10px;">
                        <label for="l20_numero"><b>Numeração Modalidade: </b></label>
                        <?= db_input(
                            'l20_numero',
                            10,
                            '',
                            true,
                            'text',
                            44,
                            "",
                            "",
                            "",
                            "",
                            null,
                            'form-control',
                            [
                                'validate-maxlength' => '10',
                                'validate-no-special-chars' => 'true',
                                'validate-integer' => 'true',
                                'validate-maxlength-message' => 'A Numeração deve ter no máximo 10 caracteres',
                                'validate-no-special-chars-message' => 'A Numeração não deve conter aspas simples, ponto e vírgula ou porcentagem',
                                'validate-integer-message' => 'O campo Numeração deve conter apenas numeros'
                            ]
                        ); ?>
                    </div>
                    <div class="col-12 col-sm-3 form-group" style="margin-bottom: 10px;">
                        <label for="l20_nroedital"><b>Edital: </b></label>
                        <?= db_input(
                            'l20_nroedital',
                            10,
                            '',
                            true,
                            'text',
                            44,
                            "",
                            "",
                            "",
                            "",
                            null,
                            'form-control',
                            [
                                'validate-maxlength' => '10',
                                'validate-no-special-chars' => 'true',
                                'validate-integer' => 'true',
                                'validate-maxlength-message' => 'O Edital deve ter no máximo 10 caracteres',
                                'validate-no-special-chars-message' => 'O Edital não deve conter aspas simples, ponto e vírgula ou porcentagem',
                                'validate-integer-message' => 'O campo Edital deve conter apenas numeros'
                            ]
                        ); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-10 form-group" style="margin-bottom: 10px;">
                        <label for="l20_codtipocom"><b>Descrição do tipo: </b></label>
                        <?=
                            db_input(
                                'l20_codtipocom',
                                50,
                                '',
                                true,
                                'text',
                                44,
                                "",
                                "",
                                "",
                                "",
                                null,
                                'form-control',
                                [
                                    'validate-maxlength' => '50',
                                    'validate-no-special-chars' => 'true',
                                    'validate-maxlength-message' => 'Descrição do tipo deve ter no máximo 50 caracteres',
                                    'validate-no-special-chars-message' => 'Descrição do tipo não deve conter aspas simples, ponto e vírgula ou porcentagem',
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-2 form-group" style="margin-bottom: 10px;">
                        <label for="l20_anousu"><b>Ano: </b></label>
                        <?= db_input(
                            'l20_anousu',
                            10,
                            '',
                            true,
                            'text',
                            44,
                            "",
                            "",
                            "",
                            "",
                            null,
                            'form-control',
                            [
                                'validate-maxlength' => '4',
                                'validate-integer' => 'true',
                                'validate-year' => 'true',
                                'validate-maxlength-message' => 'O Ano deve ter no máximo 4 caracteres',
                                'validate-integer-message' => 'O campo Ano deve conter apenas numeros',
                                'validate-year-message' => 'O Ano informado é inválido'
                            ]
                        ); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="button" class="btn btn-success" id="btnNovo" style="margin: 0 2px;">Novo</button>
                        <button class="btn btn-primary" type="button" id="btnFiltrar" style="margin: 0 2px;">Pesquisar</button>
                        <button class="btn btn-danger" type="button" id="btnLimpar" style="margin: 0 2px;">Limpar</button>
                    </div>
                </div>
            </fieldset>
        </form>
        <div class="inner-container">
            <div class="row mt-4">
                <div class="col-12" id="containTabelaListagemLicitacoes" style="padding: 0px;"></div>
            </div>
        </div>
    </body>
</html>
<script>
    const url = 'lic_licitacoes.RPC.php';
    const form = document.getElementById('frmFiltro');
    const btnFiltrar = document.getElementById('btnFiltrar');
    const btnLimpar = document.getElementById('btnLimpar');
    const btnNovo = document.getElementById('btnNovo');
    const l20_codigo = document.getElementById('l20_codigo');
    const l20_numero = document.getElementById('l20_numero');
    const l20_nroedital = document.getElementById('l20_nroedital');
    const l20_edital = document.getElementById('l20_edital');
    const l20_codtipocom = document.getElementById('l20_codtipocom');
    const l20_anousu = document.getElementById('l20_anousu');
    const validator = initializeValidation('#frmFiltro');
    const oGridLicitacoes = new DBGrid('containTabelaListagemLicitacoes');
    const formatData = new Intl.DateTimeFormat('pt-BR', { dateStyle: 'short', timeZone: 'America/Sao_Paulo' })
    let limit = 30;

    function validateChangeInteger(id, integer = true) {
        const inputElement = document.getElementById(id);

        if (!inputElement) return false;

        const validator = initializeValidationInput(inputElement);
        const isValid = validator.validate();
        if (!isValid) {
            if(integer){
                inputElement.value = inputElement.value.replace(/\D/g, '');
            }
            return false;
        }

        return true;
    }

    if (l20_codigo) {
        l20_codigo.addEventListener('input', function (e) {
            validateChangeInteger('l20_codigo');
        });
    }
    if (l20_numero) {
        l20_numero.addEventListener('input', function (e) {
            validateChangeInteger('l20_numero');
        });
    }
    if (l20_codtipocom) {
        l20_codtipocom.addEventListener('input', function (e) {
            validateChangeInteger('l20_codtipocom', false);
        });
    }
    if (l20_anousu) {
        l20_anousu.addEventListener('input', function (e) {
            validateChangeInteger('l20_anousu');
        });
    }
    if (l20_nroedital) {
        l20_nroedital.addEventListener('input', function (e) {
            validateChangeInteger('l20_nroedital');
        });
    }
    if (l20_edital) {
        l20_edital.addEventListener('input', function (e) {
            validateChangeInteger('l20_edital');
        });
    }

    function initGrid(){
        oGridLicitacoes.nameInstance = 'oGridLicitacoes';
        oGridLicitacoes.setCellAlign([
            'center',
            'center',
            'center',
            'center',
            'center',
            'center',
            'center',
            'center',
            'center',
            'center',
            'center'
        ]);

        oGridLicitacoes.setCellWidth([
            '140px',
            '150px',
            '100px',
            '300px',
            '150px',
            '100px',
            '150px',
            '300px',
            '150px',
            '160px',
            '100px',
        ]);

        oGridLicitacoes.setHeaderOrderable([
            {orderable: true, slug: 'l20_codigo'},
            {orderable: true, slug: 'l20_anousu', default: true, order: 'desc'},
            {orderable: true, slug: 'l20_edital', default: true, order: 'desc'},
            {orderable: true, slug: 'l03_descr'},
            {orderable: true, slug: 'l20_numero'},
            {orderable: true, slug: 'l20_nroedital'},
            {orderable: true, slug: 'l20_datacria'},
            {orderable: true, slug: 'l20_objeto'},
            {orderable: true, slug: 'dl_situacao'},
            {orderable: true, slug: 'l20_tipojulg_desc'},
            {orderable: false, slug: ''}
        ]);

        oGridLicitacoes.setHeader([
            'Cód. Sequencial', // l20_codigo
            'Número Processo', //l20_edital;
            'Exercício', //l20_anousu
            'Tipo de Processo', //l03_descr;
            'Numeração', //l20_numero
            'Edital', //l20_nroedital
            'Data de Abertura', //l20_datacria
            'Objeto', //l20_objeto
            'Situação', //dl_situacao
            'Tipo de Julgamento', //l20_tipojulg_desc
            'Ações'
        ]);

        oGridLicitacoes.allowSelectColumns(true);
        oGridLicitacoes.setHeight(450);
        oGridLicitacoes.showV2(document.getElementById('containTabelaListagemLicitacoes'));
        oGridLicitacoes.clearAll(true);
        oGridLicitacoes.inicializaTooltip();
        oGridLicitacoes.paginatorInitialize((pageNumber) => {
            loadingLicitacoes(pageNumber);
        });
        oGridLicitacoes.orderableInitialize((activeSortColumns) => {
            loadingLicitacoes(1, activeSortColumns);
        });
        oGridLicitacoes.initializeInpuSearch((search) => {
            loadingLicitacoes(1);
        });
    }

    function loadingLicitacoes(offset = 1, activeSortColumns = [], search = ''){
        let oParam = new Object();
        oParam.exec = 'listagemLicitacoes';
        oParam.l20_codigo = document.getElementById('l20_codigo').value;
        oParam.l20_numero = document.getElementById('l20_numero').value;
        oParam.l20_codtipocom = document.getElementById('l20_codtipocom').value;
        oParam.l20_anousu = document.getElementById('l20_anousu').value;
        oParam.l20_nroedital = document.getElementById('l20_nroedital').value;
        oParam.l20_edital = document.getElementById('l20_edital').value;
        oParam.offset = offset;
        oParam.limit = limit;
        oParam.orderable = activeSortColumns.length > 0 ? activeSortColumns : oGridLicitacoes.getActiveSortColumns();
        oParam.search = oGridLicitacoes.getInputSearch();
        oGridLicitacoes.showLoading();
        new Ajax.Request(
            url,
            {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: (oAjax) => {
                    let oRetorno = JSON.parse(oAjax.responseText);
                    oGridLicitacoes.clearAll(true);

                    if(oRetorno.data.licitacoes.length <= 0){
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção!',
                            text: 'Ops, nenhuma Licitação foi encontrada.',
                        });
                    }

                    oRetorno.data.licitacoes.forEach(function(oValue, iSeq){
                        let aLinha = new Array();
                        aLinha[0] = oValue.l20_codigo || '';
                        aLinha[1] = oValue.l20_edital || '';
                        aLinha[2] = oValue.l20_anousu || '';
                        aLinha[3] = oValue.l03_descr.trim() || '';
                        aLinha[4] = oValue.l20_numero || '';
                        aLinha[5] = oValue.l20_nroedital || '';
                        aLinha[6] = (oValue.l20_datacria && oValue.l20_datacria.length > 0) ? formatData.format(new Date(oValue.l20_datacria + 'T00:00:00')) : '';
                        aLinha[7] = `<div class="tooltip-target" data-tooltip="${oValue.l20_objeto.trim() || ''}">${oValue.l20_objeto.trim() || ''}</div>`;
                        aLinha[8] = oValue.dl_situacao || '';
                        aLinha[9] = oValue.l20_tipojulg_desc || '';
                        aLinha[10] = `
                            <a
                                href="#"
                                style="margin: 0 5px; color: #007bff; font-weigth: bold;"
                                onclick="openEdicao(
                                    event,
                                    ${oValue.l20_codigo},
                                    '${oValue.l20_tipojulg}'
                                )"
                            ><i class="fa fa-pen"></i></a>
                            <a
                                href="#"
                                style="margin: 0 5px; color: #dc3545; font-weigth: bold;"
                                onclick="openExclusao(
                                    event,
                                    ${oValue.l20_codigo},
                                    '${oValue.dl_situacao}',
                                    ${oValue.l20_licsituacao == 0},
                                    ${oValue.l47_sequencial != null},
                                    ${oValue.l21_codigo != null},
                                    ${oValue.has_fornecedor	== 1}
                                )"
                            ><i class="fa fa-trash"></i></a>
                        `;

                        oGridLicitacoes.addRow(aLinha);
                    });

                    let totalPages = (oRetorno.data.total) ? (oRetorno.data.total / limit) : 0;
                    oGridLicitacoes.setTotalItens(oRetorno.total || 0);
                    oGridLicitacoes.renderRows();
                    oGridLicitacoes.renderPagination(totalPages, offset);
                    oGridLicitacoes.hideLoading();
                    oGridLicitacoes.fixedColumns(0, 1);
                }
            }
        );
    }

    btnFiltrar.addEventListener('click', function(event){
        event.preventDefault();

        const inputs = document.getElementById('frmFiltro').querySelectorAll('input')
        inputs.forEach(input => {
            const event = new Event('input', { bubbles: true });
            input.dispatchEvent(event);
        });
        
        if(document.querySelectorAll('input.error').length > 0){
            return false;
        }
        oGridLicitacoes.resetOrderableColumns((activeSortColumns) => {
            loadingLicitacoes(1, activeSortColumns);
        })
    });

    btnLimpar.addEventListener('click', function(event){
        event.preventDefault();
        document.getElementById('frmFiltro').reset();
    })

    btnNovo.addEventListener('click', function(event){
        event.preventDefault();
        let sUrl = 'lic_licitacoes002.php';

        if(typeof db_iframe_licitacoes != 'undefined'){
            db_iframe_licitacoes.jan.location.href = sUrl;
            db_iframe_licitacoes.setCorFundoTitulo('#316648');
            db_iframe_licitacoes.setAltura("100%");
            db_iframe_licitacoes.setLargura("100%");
            db_iframe_licitacoes.setPosX("0");
            db_iframe_licitacoes.setPosY("0");
            db_iframe_licitacoes.show();
            document.querySelector(`div.DBJanelaIframe`).style.position = 'fixed';
            return false;
        }

        let frame = js_OpenJanelaIframe(
            'CurrentWindow.corpo',
            'db_iframe_licitacoes',
            sUrl,
            'Licitações > Inclusão',
            false
        );

        if(frame){
            frame.setAltura("100%");
            frame.setLargura("100%");
            frame.setPosX("0");
            frame.setPosY("0");
            frame.hide();
            db_iframe_licitacoes.setCorFundoTitulo('#316648');
            db_iframe_licitacoes.show();
            document.querySelector(`div.DBJanelaIframe`).style.position = 'fixed';
        }
    });

    function closeLicitacao(refresh = true, redirect = false){
        if(redirect){
            window.location.href = 'lic4_editalabas.php';
            return false;
        }

        if(typeof db_iframe_licitacoes != 'undefined'){
            db_iframe_licitacoes.hide();
        }

        if(typeof db_iframe_licitacoes_edit != 'undefined'){
            db_iframe_licitacoes_edit.hide();
        }

        if(refresh){
            loadingLicitacoes();
        }
    }

    function openExclusao(event, l20_codigo, descricao, isDelete, isEdital, isItem, isFornecedor){
        if(!isDelete){
            Swal.fire({
                icon: 'warning',
                title: 'Atenção!',
                text: `A Licitação não pode ser removida devido sua situação. Situação atual: ${descricao}`
            });
            return false;
        }

        if(isFornecedor){
            Swal.fire({
                icon: 'warning',
                title: 'Atenção!',
                text: `Usuário: A exclusão da licitação não é permitida, pois há fornecedores vinculados a ela. Verifique e ajuste antes de prosseguir.`
            });
            return false;
        }

        json = {
            title: 'Deseja remover a Licitação?',
            text: 'Essa ação não pode ser desfeita!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar',
        };

        if(isEdital && isItem){
            json.title = 'A Licitação possui edital e itens vinculados, estes também deverão ser removidos!';
        } else if(isEdital){
            json.title = 'A Licitação possui edital vinculado e também será ser removido!';
        } else if(isItem){
            json.title = 'A Licitação possui itens vinculados e também serão ser removidos!';
        }

        Swal.fire(json).then((result) => {
            if(result.isConfirmed){
                Swal.fire({
                    title: 'Aguarde...',
                    text: 'Estamos processando sua solicitação.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                let oParam = {};
                oParam.exec = 'removeLicitacao';
                oParam.l20_codigo = l20_codigo;
                new Ajax.Request(
                    url,
                    {
                        method: 'post',
                        parameters: 'json=' + Object.toJSON(oParam),
                        onComplete: function(oAjax){
                            let oRetorno = JSON.parse(oAjax.responseText);
                            if(oRetorno.status != null && oRetorno.status == 200){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sucesso!',
                                    text: 'Licitação removida com sucesso!',
                                });
                                loadingLicitacoes();
                                return false;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: oRetorno.message,
                            });
                        }
                    }
                );
            }
        });
    }

    function openEdicao(event, l20_codigo, l20_tipojulg){
        event.preventDefault();
        let currentUrl = new URL('lic_licitacoes002.php', window.location.href);
        currentUrl.searchParams.set('l20_codigo', l20_codigo);
        currentUrl.searchParams.set('isProcessoItem', l20_tipojulg == '1' ? 1 : 0);
        currentUrl.searchParams.set('isProcessoLote', l20_tipojulg == '3' ? 1 : 0);

        if(typeof db_iframe_licitacoes_edit != 'undefined'){
            window.scroll({
                top:0,
                left:0,
                behavior: 'smooth'
            });
            db_iframe_licitacoes_edit.setCorFundoTitulo('#316648');
            db_iframe_licitacoes_edit.jan.location.href = currentUrl.toString();
            db_iframe_licitacoes_edit.setAltura("100%");
            db_iframe_licitacoes_edit.setLargura("100%");
            db_iframe_licitacoes_edit.setPosX("0");
            db_iframe_licitacoes_edit.setPosY("0");
            db_iframe_licitacoes_edit.show();
            document.querySelector(`div.DBJanelaIframe`).style.position = 'fixed';
            return false;
        }

        let frame = js_OpenJanelaIframe(
            '',
            'db_iframe_licitacoes_edit',
            currentUrl.toString(),
            'Atualização Licitações',
            true
        );

        if(frame){
            frame.setAltura("100%");
            frame.setLargura("100%");
            frame.setPosX("0");
            frame.setPosY("0");
            frame.hide();
            db_iframe_licitacoes_edit.setCorFundoTitulo('#316648');
            db_iframe_licitacoes_edit.show();
            document.querySelector(`div.DBJanelaIframe`).style.position = 'fixed';
        }
    }

    function init(){
        initGrid();
        loadingLicitacoes();
    }

    init();

</script>
