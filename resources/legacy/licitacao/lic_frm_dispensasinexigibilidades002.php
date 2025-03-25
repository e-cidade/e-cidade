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
    #tablegridListagemPorItemheader tr td:first-child{
        width: 3% !important;
    }
</style>
<div class="row">
    <div class="col-12 col-sm-4 mb-2 form-group">
        <label for="pc80_codproc">Processo de Compra:</label>
        <select name="pc80_codproc" id="pc80_codproc" class="custom-select">
            <option value="">Selecione</option>
        </select>
    </div>
    <div class="col-12 col-sm-4 mb-2 form-group">
        <label for="pc80_codprocexcluir">Processos de Compras Vinculados:</label>
        <select name="pc80_codprocexcluir" id="pc80_codprocexcluir" class="custom-select" disabled>
            <option value="">Selecione</option>
        </select>
    </div>
    <div class="col-12 col-sm-4 mb-2 form-group">
        <label for="pc80_data">Data:</label>
        <?php
            db_input(
            'pc80_data',
            4,
            '',
            true,
            'date',
            3,
            "",
            '',
            '',
            '',
            '',
            'form-control'
        );
        ?>
    </div>
</div>
<div class="row">
    <div class="col-12 mb-2 form-group">
        <label for="usuario">Usuário:</label>
        <?php
            db_input(
                'usuario',
                4,
                '',
                true,
                'text',
                3,
                "",
                '',
                '',
                '',
                '',
                'form-control'
            );
        ?>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-12 mb-2 form-group">
        <label for="pc80_resumo">Resumo:</label>
        <?php
            db_textarea(
                'pc80_resumo',
                0,
                2,
                '',
                true,
                'text',
                3,
                '',
                '',
                '',
                null,
                'form-control-plaintext form-control-lg',
                [],
                'min-height: 110px; height:110px;'
            );
        ?>
    </div>
</div>
<div class="row">
    <div class="col-12 mt-4">
        <div id="gridListagemPorItem"></div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-12 col-sm-12 form-group d-flex gap-2 contain-buttons">
        <button type="button" id="btnAdicionarItens" class="btn btn-success">Adicionar</button>
        <button type="button" id="btnExcluirItens" class="btn btn-danger" disabled>Excluir Processo de Compra</button>
        <button type="button" id="btnExcluirProcessosVinculados" class="btn btn-warning" disabled>Excluir Todos Processos de Compras</button>
    </div>
</div>
<script>
    (function(){
        const oGridPorItens = new DBGrid('gridListagemPorItem');
        const btnAdicionarItens = document.getElementById('btnAdicionarItens');
        const btnExcluirItens = document.getElementById('btnExcluirItens');
        const btnExcluirProcessosVinculados = document.getElementById('btnExcluirProcessosVinculados');
        const selectProcessoCompra = document.getElementById('pc80_codproc');
        const selectProcessoVinculados = document.getElementById('pc80_codprocexcluir');
        let itensChangePorItem = [];
        let paginaAtualItens = 0;
        let isPaginate = false;
        let totalItens = 0;
        const eventChange = new Event('change');
        const eventProcessoCompraAdd = new CustomEvent('eventProcessoCompraAdd', {
            detail: {
                message: 'Processo Compra Adicionado'
            }
        });
        const eventProcessoCompraRemove = new CustomEvent('eventProcessoCompraRemove', {
            detail: {
                message: 'Processo Compra Removido'
            }
        });

        window.oGridPorItens = oGridPorItens;

        const loadings = {
            processo_compra: false,
            processo_vinculados: false,
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

        function getProcessoCompra(){
            document.getElementById('pc80_codproc').selectedIndex = 0;
            selectProcessoCompra.innerHTML = '<option value="">Selecione</option>';

            let oParam = {};
            oParam.exec = 'getProcessoCompras';
            oParam.l20_codigo = l20_codigo;
            let oAjax = new Ajax.Request(
                url,
                {
                    method: 'post',
                    asynchronous: true,
                    parameters: 'json=' + JSON.stringify(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        oRetorno.data.forEach((oValue, iSeq) => {
                            const option = document.createElement('option');
                            option.value = oValue.pc80_codproc;
                            option.text = oValue.pc80_codproc;

                            selectProcessoCompra.appendChild(option);
                        });

                        estadosProxy.processo_compra = true;
                    }
                }
            );
        }

        function getProcessosVinculados(){
            document.getElementById('pc80_codprocexcluir').selectedIndex = 0;
            selectProcessoVinculados.innerHTML = '<option value="">Selecione</option>';

            let oParam = {};
            oParam.exec = 'getProcessosComprasVinculados';
            oParam.l20_codigo = l20_codigo;
            let oAjax = new Ajax.Request(url, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: function(oAjax){
                    let oRetorno = JSON.parse(oAjax.responseText);
                    oRetorno.data.forEach((oValue, iSeq) => {
                        const option = document.createElement('option');
                        option.value = oValue.pc81_codproc;
                        option.text = oValue.pc81_codproc;

                        selectProcessoVinculados.appendChild(option);
                    });

                    if(oRetorno.data.length > 0){
                        btnExcluirProcessosVinculados.disabled = false;
                        document.dispatchEvent(eventProcessoCompraAdd);
                    } else {
                        btnExcluirProcessosVinculados.disabled = true;
                        document.dispatchEvent(eventProcessoCompraRemove);
                    }

                    if(oRetorno.data.length > 1){
                        selectProcessoVinculados.disabled = false;
                        btnExcluirItens.disabled = false;
                    } else {
                        selectProcessoVinculados.disabled = true;
                        btnExcluirItens.disabled = true;
                    }

                    if(oRetorno.data.length == 1){
                        document.getElementById('pc80_codprocexcluir').selectedIndex = 1;
                        selectProcessoVinculados.dispatchEvent(eventChange)
                    }

                    estadosProxy.processo_vinculados = true;
                }
            });
        }

        function initGridPorItem(){
            oGridPorItens.nameInstance = 'oGridPorItens';
            oGridPorItens.setCheckbox(0);
            oGridPorItens.setCellAlign([
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

            oGridPorItens.setCellWidth([
                "8%",
                "8%",
                "25%",
                "25%",
                "7%",
                "8%",
                "8%",
                "8%",
            ]);

            oGridPorItens.setHeader([
                'Código',
                'Nº Item',
                'Item',
                'Descrição',
                'Qtde',
                'Unidade',
                'ME/EPP',
                'Sigiloso',
                'json'
            ]);

            oGridPorItens.allowSelectColumns(true);
            oGridPorItens.setHeight(300);
            oGridPorItens.aHeaders[9].lDisplayed = false;
            oGridPorItens.showV2(document.getElementById('gridListagemPorItem'));
            oGridPorItens.clearAll(true);
            oGridPorItens.inicializaTooltip();

            oGridPorItens.selectSingle = function(oCheckbox, sRow, oRow){
                getItensChangePorItem(oRow);
            }

            if(isPaginate){
                oGridPorItens.paginatorInitialize((pageNumber) => {
                    loadingPorItens(pageNumber);
                });
            }
        }

        function loadingPorItens(offset = 1){
            let oParam = {};
            oParam.exec = 'getItensProcessoCompra';
            oParam.codproc = selectProcessoCompra.value || null;
            oParam.l20_codigo = l20_codigo;
            oParam.offset = offset;
            oParam.limit = limit;
            oParam.isPaginate = isPaginate;
            oGridPorItens.showLoading();
            paginaAtualItens = offset;
            new Ajax.Request(
                url,
                {
                    method: 'post',
                    parameters: 'json=' + Object.toJSON(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        oGridPorItens.clearAll(true);
                        if(oRetorno.data.length <= 0){
                            oGridPorItens.hideLoading();
                            estadosProxy.itens = true;
                            return false;
                        }

                        oRetorno.data.forEach(function(oValue, iSeq){
                            let aLinha = new Array();
                            aLinha[0] = oValue.pc01_codmater;
                            // aLinha[1] = oValue.pc11_seq;
                            aLinha[1] = (iSeq + 1);
                            aLinha[2] = `<div class="tooltip-target" data-tooltip="${oValue.pc01_descrmater}">${oValue.pc01_descrmater}</div>`;
                            aLinha[3] = `<div class="tooltip-target" data-tooltip="${oValue.pc01_complmater}">${oValue.pc01_complmater}</div>`;
                            aLinha[4] = oValue.pc11_quant;
                            aLinha[5] = oValue.m61_descr;
                            aLinha[6] = oValue.reservado;
                            aLinha[7] = new DBComboBox('Sigilo' + iSeq, 'Sigilo' + iSeq, null, '100%');

                            if(oValue.l21_codigo){
                                if(oValue.l21_sigilo === true){
                                    aLinha[7].addItem('t', 'Sim');
                                } else {
                                    aLinha[7].addItem('f', 'Não');
                                }
                                aLinha[7].lDisabled = true;
                            } else {
                                aLinha[7].addItem('f', 'Não');
                                aLinha[7].addItem('t', 'Sim');
                            }
                            aLinha[8] = JSON.stringify(oValue);

                            oGridPorItens.addRow(aLinha, false, false, false);
                        });

                        let totalPages = (oRetorno.total) ? Math.ceil(oRetorno.total / limit) : 0;
                        oGridPorItens.setTotalItens(oRetorno.total || 0);
                        oGridPorItens.renderRows();
                        oGridPorItens.aRows.each(function(aRow, iIndice){
                            let corLinhaChange = '#D1F07C';
                            const id = aRow.sId;
                            const json = JSON.parse(aRow.aCells[9].getValue());

                            if(json.l21_codigo){
                                document.getElementById(id).style.backgroundColor = corLinhaChange;
                            }
                        });

                        if(isPaginate){
                            oGridPorItens.renderPagination(totalPages, offset);
                        }

                        oGridPorItens.hideLoading();
                        oGridPorItens.fixedColumns(0, 0);
                        estadosProxy.itens = true;
                    }
                }
            );
        }

        if(selectProcessoCompra != null){
            selectProcessoCompra.addEventListener('change', function(e){
                e.preventDefault();
                let oParam = {};
                oParam.exec = 'getDadosProcessoCompras';
                oParam.codproc = selectProcessoCompra.value || null;
                let oAjax = new Ajax.Request(url, {
                    method: 'post',
                    parameters: 'json=' + Object.toJSON(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        document.getElementById('usuario').value = oRetorno.data.nome || '';
                        document.getElementById('pc80_data').value = oRetorno.data.pc80_data || '';
                        document.getElementById('pc80_resumo').value = oRetorno.data.pc80_resumo || '';
                        itensChangePorItem = [];
                        loadingPorItens();
                    }
                });

            });
        }

        if(selectProcessoVinculados != null){
            selectProcessoVinculados.addEventListener('change', function(e){
                e.preventDefault();
                let oParam = {};
                oParam.exec = 'getDadosProcessoCompras';
                oParam.codproc = selectProcessoVinculados.value || null;
                let oAjax = new Ajax.Request(url, {
                    method: 'post',
                    parameters: 'json=' + Object.toJSON(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        document.getElementById('usuario').value = oRetorno.data.nome || '';
                        document.getElementById('pc80_data').value = oRetorno.data.pc80_data || '';
                        document.getElementById('pc80_resumo').value = oRetorno.data.pc80_resumo || '';
                    }
                });

            });
        }

        if(btnAdicionarItens != null){
            btnAdicionarItens.addEventListener('click', async function(e){
                e.preventDefault();

                if(itensChangePorItem.length <= 0){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção!',
                        text: 'Nenhum item selecionado.',
                    });
                    return false;
                }

                let itensChange = itensChangePorItem.filter((item) => !item.l21_codigo);
                if(itensChange.length <= 0){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção!',
                        text: 'Os itens selecionados ja foram adicionados.',
                    });
                    return false;
                }

                if(oGridPorItens.getTotalItens() != itensChange.length){
                    const result = await Swal.fire({
                        title: 'A quantidade de itens selecionados não corresponde ao total do processo de compras',
                        text: "Deseja continuar mesmo assim?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sim, confirmar!',
                        cancelButtonText: 'Cancelar',
                    });

                    if(result.dismiss === Swal.DismissReason.cancel){
                        return false;
                    }
                }

                let oParam = {};
                oParam.exec = 'salvarItensProcesso';
                oParam.l20_codigo = l20_codigo;
                oParam.itens = itensChange;
                oParam.processocompra = selectProcessoCompra.value

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

                                
                                itensChangePorItem = [];
                                cleanInputs();
                                getProcessoCompra();
                                getProcessosVinculados();
                                loadingPorItens();
                                
                                if(isProcessoItem){
                                    if(typeof isLicitacao != 'undefined' && isLicitacao){
                                        // parent.closeLicitacao(false, true);
                                        return false;
                                    }
                                    parent.closeDispensasInexigibilidades(false, true);
                                }

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

            });
        }

        if(btnExcluirItens != null){
            btnExcluirItens.addEventListener('click', function(e){
                e.preventDefault();
                if(!selectProcessoVinculados.value){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção!',
                        text: 'Selecione o Processo de Compra desejado.',
                    });
                    return false;
                }

                let json = {};
                if(checkDelecaoPrecessoCompra()){
                    json = {
                        title: 'Você tem certeza de que deseja excluir o Processo de Compra? Os lotes associados a ele também serão excluídos.',
                        text: "Essa ação não pode ser desfeita!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sim, excluir!',
                        cancelButtonText: 'Cancelar',
                    };
                } else {
                    json = {
                        title: 'Você tem certeza que deseja excluir o Processo de Compra?',
                        text: "Essa ação não pode ser desfeita!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sim, excluir!',
                        cancelButtonText: 'Cancelar',
                    };
                }

                Swal.fire(json).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Aguarde...',
                            text: 'Estamos processando sua solicitação.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        let oParam = {};
                        oParam.exec = 'excluirProcessoCompra';
                        oParam.l20_codigo = l20_codigo;
                        oParam.pc80_codprocexcluir = selectProcessoVinculados.value;

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
                                        itensChangePorItem = [];
                                        getProcessoCompra();
                                        getProcessosVinculados();
                                        loadingPorItens();
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
            })
        }

        if(btnExcluirProcessosVinculados != null){
            btnExcluirProcessosVinculados.addEventListener('click', function (e) {
                e.preventDefault();

                let json = {};
                if(checkDelecaoPrecessoCompra()){
                    json = {
                        title: 'Você tem certeza que deseja excluir TODOS os processos de compras? Os lotes associados também serão deletados.',
                        text: "Essa ação não pode ser desfeita!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sim, excluir!',
                        cancelButtonText: 'Cancelar',
                    };
                } else {
                    json = {
                        title: 'Você tem certeza que deseja excluir TODOS os processos de compras?',
                        text: "Essa ação não pode ser desfeita!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sim, excluir!',
                        cancelButtonText: 'Cancelar',
                    };
                }

                Swal.fire(json).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Aguarde...',
                            text: 'Estamos processando sua solicitação.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        let oParam = {};
                        oParam.exec = 'excluirProcessosVinculados';
                        oParam.l20_codigo = l20_codigo;
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
                                            text: ' Processo(s) removido(s) com sucesso!',
                                        });
                                        itensChangePorItem = [];
                                        cleanInputs();
                                        getProcessoCompra();
                                        getProcessosVinculados();
                                        loadingPorItens();
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
            });
        }

        function getItensChangePorItem(oRow){
            oGridPorItens.aRows.each(function(aRow, iIndice){
                let checked = document.getElementById(aRow.aCells[0].getId()).querySelector('input[type="checkbox"]').checked;
                let id = aRow.sId;
                const json = JSON.parse(aRow.aCells[9].getValue());

                delete json.pc01_complmater;
                delete json.pc01_descrmater;
                delete json.m61_descr;

                json.l21_sigilo = aRow.aCells[8].getValue() == 't';

                const index = itensChangePorItem.findIndex(
                    item => (
                        item.pc81_codprocitem == json.pc81_codprocitem
                        && item.pc01_codmater == json.pc01_codmater
                        && item.m61_codmatunid == json.m61_codmatunid
                    )
                );

                if(checked){
                    if(index === -1){
                        itensChangePorItem.push(json);
                    }
                } else {
                    if(index !== -1){
                        itensChangePorItem.splice(index, 1)
                    }
                }
            });
        }

        function checkDelecaoPrecessoCompra(processocompra = null){
            itens = [];
            oGridPorItens.aRows.each(function(aRow, iIndice){
                let json = JSON.parse(aRow.aCells[9].getValue());
                if(
                    (processocompra == null && json.l04_descricao != null && !json.l04_descricao.startsWith('LOTE_AUTOITEM_'))
                    || (processocompra == json.pc81_codproc && json.l04_descricao != null && !json.l04_descricao.startsWith('LOTE_AUTOITEM_'))
                ){
                    itens.push(json);
                }
            });

            return itens.length > 0;
        }

        function cleanInputs(){
            document.getElementById('usuario').value = '';
            document.getElementById('pc80_data').value = '';
            document.getElementById('pc80_resumo').value = '';
        }

        function init(){
            Swal.fire({
                title: 'Aguarde...',
                text: 'Estamos processando sua solicitação.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            getProcessoCompra();
            getProcessosVinculados();

            initGridPorItem();
            loadingPorItens();
        }

        init();
    })();

</script>
