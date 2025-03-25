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
<style>
    .table-body tr td:nth-child(3) i{
        font-size: 16px;
    }
</style>
<fieldset>
    <legend>Dotação</legend>
    <form action="" name="form1" id="frmDotacao" method="post">
        <input type="hidden" name="o50_estrutdespesa" id="o50_estrutdespesa">
        <div class="row">
            <div class="col-12 col-sm-8 mb-2 form-group">
                <label for="lotes"><?= db_ancora('Dotações:', 'pesquisaDotacao(true);', 1, '', 'pc13_coddot') ?></label>
                <div class="row">
                    <div class="col-12 col-sm-3 mb-2 form-group pl-0">
                        <?php
                            db_input(
                                'o58_coddot',
                                8,
                                '',
                                true,
                                'text',
                                1,
                                " onchange='pesquisaDotacao(false)' ",
                                '',
                                '',
                                '',
                                '',
                                'form-control',
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-9 mb-2 form-group">
                        <?php
                            db_input(
                                'o56_descr',
                                50,
                                '',
                                true,
                                'text',
                                3,
                                '',
                                '',
                                '',
                                '',
                                '',
                                'form-control'
                            );
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</fieldset>
<div class="row mb-2">
    <div class="col-12 col-sm-12 mt-4 form-group contain-buttons">
        <button type="button" id="btnAdicionarDotacao" class="btn btn-success">Incluir</button>
    </div>
</div>
<fieldset>
    <legend>Dotações Vinculadas</legend>
    <div class="row">
        <div class="col-12">
            <div id="gridDotacoesVinculadas"></div>
        </div>
    </div>
</fieldset>

<script>
    (function(){
        const btnAdicionarDotacao = document.getElementById('btnAdicionarDotacao');
        const formatter = (new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 4, maximumFractionDigits: 4 }));
        let cod_elementos = '';
        let coddepto = '';
        let o78_pactoplano = '';
        let isPaginate = false;
        let oParam;
        let reduzido = [];

        const oGridDotacoesVinculadas = new DBGrid('gridDotacoesVinculadas');
        window.oGridDotacoesVinculadas = oGridDotacoesVinculadas;

        const loadings = {
            itens: false
        };

        function closeSwal(){
            Swal.close();
        }

        function verificaEstados(loadings){
            return Object.values(loadings).every(valor => valor === true);
        }

        const handler = {
            set(target, prop, value){
                target[prop] = value;

                if(verificaEstados(target)){
                    closeSwal();
                }
                return true;
            }
        };

        const estadosProxy = new Proxy(loadings, handler);

        if (o58_coddot) {
            o58_coddot.addEventListener('input', function (e) {
                if (event.target.value === '') {
                    document.getElementById('o56_descr').value = '';
                    return false;
                }
                validateChangeInteger('o58_coddot');
            });
        }   

        function validateChangeInteger(id, integer = true) {
            const inputElement = document.getElementById(id);

            if (!inputElement) return false;

            const validator = initializeValidationInput(inputElement);

            const isValid = validator.validate();
            if (!isValid) {
                if(integer){
                    inputElement.value = inputElement.value.replace(/\D/g, '').substr(0, 10);
                }
                return false;
            }

            return true;
        }


        function initGridDotacoesVinculadas(){
            oGridDotacoesVinculadas.nameInstance = 'oGridDotacoesVinculadas';
            oGridDotacoesVinculadas.setCellAlign([
                'center',
                'center',
                'center'
            ]);

            oGridDotacoesVinculadas.setCellWidth([
                '20%',
                '70%',
                '10%'
            ]);

            oGridDotacoesVinculadas.setHeader([
                'Reduzido',
                'Estrutural',
                'Ação',
                'json'
            ]);

            oGridDotacoesVinculadas.setHeight(250);
            oGridDotacoesVinculadas.aHeaders[3].lDisplayed = false;
            oGridDotacoesVinculadas.show(document.getElementById('gridDotacoesVinculadas'));
            oGridDotacoesVinculadas.inicializaTooltip();
            oGridDotacoesVinculadas.clearAll(true);
        }

        function loadingDotacoesVinculadas(offset = 1){
            let oParam = {};
            oParam.exec = 'getDotacoesProcItens';
            oParam.l20_codigo = l20_codigo;
            oParam.offset = offset;
            oParam.limit = limit;
            oParam.isPaginate = isPaginate;
            oGridDotacoesVinculadas.showLoading();
            paginaAtualLotes = offset;
            new Ajax.Request(
                url,
                {
                    method: 'post',
                    parameters: 'json=' + Object.toJSON(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText)
                        oGridDotacoesVinculadas.clearAll(true);
                        if(oRetorno.data.total.length <= 0){
                            oGridDotacoesVinculadas.hideLoading();
                            estadosProxy.itens = true;
                            return false;
                        }
                        oRetorno.data.dotacao.forEach(function(oValue, iSeq){
                            let aLinha = new Array();
                            aLinha[0] = oValue.o58_coddot;
                            aLinha[1] = `<div class="tooltip-target" data-tooltip="${oValue.o50_estrutdespesa}">${oValue.o50_estrutdespesa}</div>`;
                            aLinha[2] = `
                                <a
                                    href="#"
                                    style="margin: 0 5px; color: #dc3545; font-weigth: bold;"
                                    onclick="openExclusaoDotacao(
                                        event,
                                        '${oValue.o58_coddot}',
                                        '${oValue.o50_estrutdespesa}'
                                    )"
                                ><i class="fa fa-trash"></i></a>
                            `;
                            aLinha[3] = JSON.stringify(oValue);
                            oGridDotacoesVinculadas.addRow(aLinha, false, false, false);
                            reduzido.push(oValue.o58_coddot);
                        });

                        let totalPages = (oRetorno.data.total) ? Math.ceil(oRetorno.data.total / limit) : 0;
                        oGridDotacoesVinculadas.setTotalItens(oRetorno.data.total || 0);
                        oGridDotacoesVinculadas.renderRows();
                        if(isPaginate){
                            oGridDotacoesVinculadas.renderPagination(totalPages, offset);
                        }
                        oGridDotacoesVinculadas.hideLoading();
                        estadosProxy.itens = true;
                    }
                }
            );
        }

        function pesquisaDotacao(mostra){
            let sUrl = 'com4_materialsolicitacao.RPC.php';
            let oParam = {};
            oParam.exec = 'getElementos';
            oParam.licitacao = l20_codigo;
            new Ajax.Request(
                sUrl,
                {
                    method: 'post',
                    parameters: 'json=' + Object.toJSON(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);

                        if(oRetorno.quantidade == 0){
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção!',
                                text: 'Processo(s) de compra não vinculado a licitação ou sem item(ns) cadastrado(s)',
                            });
                            return false;
                        }

                        let dotacao = document.getElementById('o58_coddot').value;
                        let aux = [];
                        oRetorno.aItens.forEach((oValue, iSeq) => {
                            aux.push(oValue.elemento.toString().substring(0, 7));
                        });

                        cod_elementos = aux.join(',');
                        cod_elementos = cod_elementos.substring(0, cod_elementos.length - 1);

                        let search = 'obriga_depto=sim';
                        search += '&cod_elementos=' + cod_elementos;
                        if(oParam && oParam.pc30_passadepart){
                            search += `&departamento=${coddepto}`;
                        }
                        search += '&retornadepart=true';
                        search += '&pactoplano=' + o78_pactoplano;

                        if(mostra){
                            search += '&funcao_js=parent.mostraAncoraDotacao|o58_coddot|o41_descr';
                            js_OpenJanelaIframe(
                                '',
                                'iframe_dotacoesnovo',
					            'func_permorcdotacao.php?' + search,
                                'Pesquisa',
                                true,
                                '0'
                            );
                            return false;
                        }

                        search += '&pesquisa_chave=' + dotacao;
                        js_OpenJanelaIframe(
                            '',
					        'db_iframe_orcdotacao',
					        'func_permorcdotacao.php?' + search + '&funcao_js=parent.js_mostraorcdotacao',
                            'Pesquisa',
                            false
                        );
                    }
                }
            );
        }

        function mostraAncoraDotacao(chave1, chave2, chave3){
            document.getElementById('o58_coddot').value = chave1;
            document.getElementById('o56_descr').value = chave2;
            document.getElementById('o50_estrutdespesa').value = chave3;
            iframe_dotacoesnovo.hide();
        }

        function js_mostraorcdotacao(chave1, chave2, erro){
            if(erro){
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'Dotação não encontrada',
                });
                document.getElementById('o58_coddot').value = '';
                document.getElementById('o56_descr').value = '';
                document.getElementById('o50_estrutdespesa').value = '';
                return false;
            }
            document.getElementById('o56_descr').value = chave1;
            document.getElementById('o50_estrutdespesa').value = chave2;

            db_iframe_orcdotacao.hide();
        }

        function openExclusaoDotacao(event, o58_coddot, o50_estrutdespesa){
            event.preventDefault();
            Swal.fire({
                title: `Deseja remover a Dotação ${o58_coddot}?`,
                text: "Essa ação não pode ser desfeita!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
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
                    oParam.exec = 'removeDotacao';
                    oParam.o58_coddot = o58_coddot;
                    oParam.l20_codigo = l20_codigo;
                    oParam.o50_estrutdespesa = o50_estrutdespesa;
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
                                        text: oRetorno.message,
                                    });

                                    let index = reduzido.findIndex(item => (item == o58_coddot));
                                    if(index !== -1){
                                        reduzido.splice(index, 1);
                                    }
                                    loadingDotacoesVinculadas();
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

        if(btnAdicionarDotacao != null){
            btnAdicionarDotacao.addEventListener('click', function(e){
                e.preventDefault();
                if(!document.getElementById("o58_coddot").value){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção!',
                        text: 'Dotação não informada!',
                    });
                    return false;
                }

                let o58_coddot = document.getElementById('o58_coddot').value;
                let index = reduzido.findIndex(item => (item == o58_coddot));
                if(index !== -1){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção!',
                        text: 'Dotação já incluída!',
                    });
                    return false;
                }

                let oParam = {};
                oParam.exec = 'salvarDotacao';
                oParam.l20_codigo = l20_codigo;
                oParam.o58_coddot = o58_coddot;

                Swal.fire({
                    title: 'Aguarde...',
                    text: 'Estamos processando sua solicitação.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                new Ajax.Request(
                    url,
                    {
                        method: 'post',
                        parameters: 'json=' + Object.toJSON(oParam),
                        onComplete: (oAjax) => {
                            let oRetorno = JSON.parse(oAjax.responseText);
                            if(oRetorno.status == 200){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sucesso!',
                                    text: oRetorno.message,
                                });
                                itensChangePorLote = [];
                                document.getElementById('frmDotacao').reset();
                                loadingDotacoesVinculadas();
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
            })
        }

        window.pesquisaDotacao = pesquisaDotacao;
        window.mostraAncoraDotacao = mostraAncoraDotacao;
        window.js_mostraorcdotacao = js_mostraorcdotacao;
        window.openExclusaoDotacao = openExclusaoDotacao;
        function init(){
            initGridDotacoesVinculadas();
            loadingDotacoesVinculadas();
        }

        async function getParam() {
            Swal.fire({
                title: 'Aguarde...',
                text: 'Estamos processando sua solicitação.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            let oParam = {};
            oParam.exec = 'listagemPcParam';
            let oAjax = await new Ajax.Request(
                url,
                {
                    method: 'post',
                    asynchronous: true,
                    parameters: 'json=' + JSON.stringify(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        oParam = oRetorno.data;
                        coddepto = oRetorno.coddepto || false;
                        o78_pactoplano = oRetorno.o78_pactoplano || '';
                        init();
                    }
                }
            );
        }
        getParam();

    })();
</script>
