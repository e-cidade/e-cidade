<style>
    .tablegrid tbody tr td:first-child{
        text-align: center !important;
    }

    .disabled {
        opacity: 0.5;
        pointer-events: none;
    }
</style>
<fieldset>
    <legend>Lote</legend>
    <div class="row">
        <div class="col-12 col-sm-3 mb-2 form-group">
            <label for="lotes">Lote:</label>
            <select
                name="lotes"
                id="lotes"
                class="custom-select"
            >
                <option value="">Selecione</option>
            </select>
        </div>
    </div>
</fieldset>
<div class="row mb-2">
    <div class="col-12 col-sm-12 mt-4 form-group contain-buttons">
        <button type="button" id="btnAdicionarLote" class="btn btn-success">Novo</button>
        <button type="button" id="btnExcluirLote" class="btn btn-danger">Excluir</button>
    </div>
</div>
<fieldset>
    <legend>Itens do Processo de Compras</legend>
    <div class="row">
        <div class="col-12">
            <div id="gridItensPorLote"></div>
        </div>
    </div>
</fieldset>
<div class="row mt-4">
    <div class="col-12 col-sm-12 form-group d-flex gap-2 contain-buttons">
        <button type="button" id="btnAdicionarItensLote" class="btn btn-success">Salvar</button>
        <button type="button" id="btnExcluirItensLote" class="btn btn-danger">Excluir Iten(s) do Lote</button>
    </div>
</div>

<script>
    (function(){
        const btnAdicionarLote = document.getElementById('btnAdicionarLote');
        const btnExcluirLote = document.getElementById('btnExcluirLote');
        const btnAdicionarItensLote = document.getElementById('btnAdicionarItensLote');
        const btnExcluirItensLote = document.getElementById('btnExcluirItensLote');
        const formatter = (new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 4, maximumFractionDigits: 4 }));
        let isPaginate = false;
        const selectLotes = document.getElementById('lotes');

        const oGridPorLotes = new DBGrid('gridItensPorLote');
        let paginaAtualLotes = 0;
        let itensChangePorLote = [];

        window.oGridPorLotes = oGridPorLotes;

        const loadings = {
            lotes: false,
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

        function initGridPorLote(){
            oGridPorLotes.nameInstance = 'oGridPorLotes';
            oGridPorLotes.setCheckbox(0);
            oGridPorLotes.setCellAlign([
                'center',
                'center',
                'center',
                'center',
                'center',
                'center',
                'center',
                'center'
            ]);

            oGridPorLotes.setCellWidth([
                '9%',
                '12%',
                '35%',
                '10%',
                '10%',
                '8%',
                '12%',
            ]);

            oGridPorLotes.setHeader([
                'Item',
                'Cód. do Material',
                'Material',
                'Qtde.',
                'Vlr. Médio',
                'ME/EPP',
                'Lote',
                'json'
            ]);

            oGridPorLotes.allowSelectColumns(true);
            oGridPorLotes.setHeight(350);
            oGridPorLotes.aHeaders[8].lDisplayed = false;
            oGridPorLotes.showV2(document.getElementById('gridItensPorLote'));
            oGridPorLotes.clearAll(true);
            oGridPorLotes.inicializaTooltip();

            oGridPorLotes.selectSingle = function(oCheckbox, sRow, oRow){
                getItensChangePorLote(oRow);
            }

            if(isPaginate){
                oGridPorLotes.paginatorInitialize((pageNumber) => {
                    loadingPorLotes(pageNumber);
                });
            }

        }

        function loadingPorLotes(offset = 1){
            let oParam = {};
            oParam.exec = 'getItensLotes';
            oParam.l20_codigo = l20_codigo;
            oParam.offset = offset;
            oParam.limit = limit;
            oParam.isPaginate = isPaginate;
            oGridPorLotes.showLoading();
            paginaAtualLotes = offset;
            new Ajax.Request(
                url,
                {
                    method: 'post',
                    parameters: 'json=' + Object.toJSON(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText)
                        oGridPorLotes.clearAll(true);
                        if(oRetorno.data.length <= 0){
                            oGridPorLotes.hideLoading();
                            estadosProxy.itens = true;
                            return false;
                        }
                        oRetorno.data.forEach(function(oValue, iSeq){
                            let aLinha = new Array();
                            aLinha[0] = oValue.pc81_codprocitem;
                            aLinha[1] = oValue.pc01_codmater;
                            aLinha[2] = `<div class="tooltip-target" data-tooltip="${oValue.pc01_descrmater}">${oValue.pc01_descrmater}</div>`;
                            aLinha[3] = parseFloat(oValue.pc11_quant);
                            aLinha[4] = formatter.format(oValue.vlrun);
                            aLinha[5] = oValue.reservado;
                            aLinha[6] = `<div class="tooltip-target" data-tooltip="${oValue.l04_descricao || ''}">${oValue.l04_descricao || ''}</div>`;
                            aLinha[7] = JSON.stringify(oValue);
                            oGridPorLotes.addRow(aLinha, false, false, false);
                        });

                        let totalPages = (oRetorno.total) ? Math.ceil(oRetorno.total / limit) : 0;
                        oGridPorLotes.setTotalItens(oRetorno.total || 0);
                        oGridPorLotes.renderRows();
                        if(isPaginate){
                            oGridPorLotes.renderPagination(totalPages, offset);
                        }
                        oGridPorLotes.hideLoading();
                        oGridPorLotes.fixedColumns(0,0);
                        renderLineLoteTable();
                        estadosProxy.itens = true;
                    }
                }
            );
        }

        if(btnAdicionarLote != null){
            btnAdicionarLote.addEventListener('click', function(e){
                e.preventDefault();
                let currentUrl = new URL('lic_dispensasinexigibilidades003.php', window.location.href);
                currentUrl.searchParams.set('l20_codigo', l20_codigo);

                if(typeof db_iframe_lote_dispensa != 'undefined'){
                    window.scroll({
                        top: 0,
                        left: 0,
                        behavior: 'smooth'
                    });
                    db_iframe_lote_dispensa.jan.location.href = currentUrl.toString();
                    db_iframe_lote_dispensa.setCorFundoTitulo('#316648');
                    db_iframe_lote_dispensa.setAltura("100%");
                    db_iframe_lote_dispensa.setLargura("100%");
                    db_iframe_lote_dispensa.setPosX("0");
                    db_iframe_lote_dispensa.setPosY("0");
                    db_iframe_lote_dispensa.show();
                    return false;
                }

                let frame = js_OpenJanelaIframe(
                    '',
                    'db_iframe_lote_dispensa',
                    currentUrl.toString(),
                    (typeof isLicitacao != 'undefined' && isLicitacao) ? 'Licitação > Lote > Inclusão' : 'Dispensas/Inexigibilidades > Lote > Inclusão',
                    false
                );

                if(frame){
                    frame.setAltura('100%');
                    frame.setLargura('100%');
                    frame.hide();
                    btnAdicionarLote.dispatchEvent((new Event('click')))
                }

            })
        }

        if(btnExcluirLote != null){
            btnExcluirLote.addEventListener('click', function(e){
                e.preventDefault();
                let lote = selectLotes.value;
                if(!lote){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção!',
                        text: 'Nenhum lote selecionado.',
                    });
                    return false;
                }

                Swal.fire({
                    title: 'Os itens serão removidos do lote, deseja continuar?',
                    text: "Essa ação não pode ser desfeita!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
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
                        oParam.exec = 'excluirLote';
                        oParam.l20_codigo = l20_codigo;
                        oParam.lote = lote;

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
                                        loadLotes();
                                        if(oRetorno.data.is_reload){
                                            loadingPorLotes();
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
                    }
                });
            });
        }

        if(btnAdicionarItensLote != null){
            btnAdicionarItensLote.addEventListener('click', function(e){
                e.preventDefault();
                const itensLote = getItensSaveLote();

                if(itensLote.length <= 0){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção!',
                        text: 'Nenhum item selecionado.',
                    });
                    return false;
                }

                let oParam = {};
                oParam.exec = 'salvarItensLote';
                oParam.l20_codigo = l20_codigo;
                oParam.itens = itensLote;

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
                                loadingPorLotes();

                                if(oRetorno.data.redirect){
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
            })
        }

        if(btnExcluirItensLote != null){
            btnExcluirItensLote.addEventListener('click', function(e){
                e.preventDefault();

                const itensChangeDelete = getItensSaveLote(selectLotes.value);
                if(itensChangeDelete.length <= 0){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção!',
                        text: 'Nenhum item selecionado.',
                    });
                    return false;
                }

                let itensChange = itensChangeDelete.filter((item) => item.l04_codigo);
                if(itensChange.length <= 0){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção!',
                        text: 'Os itens selecionados não foram adicionados.',
                    });
                    return false;
                }

                Swal.fire({
                    title: 'Os itens serão removidos do lote, deseja continuar?',
                    text: "Essa ação não pode ser desfeita!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
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
                        oParam.exec = 'excluirItensLote';
                        oParam.l20_codigo = l20_codigo;
                        oParam.itens = itensChange;

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
                                        loadingPorLotes();
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
                    } else {
                        loadingPorLotes(1);
                    }
                });
            })
        }

        function getItensChangePorLote(oRow){
            const lote_selected = document.getElementById('lotes').value;
            oGridPorLotes.aRows.each(function(aRow, iIndice){
                let checked = document.getElementById(aRow.aCells[0].getId()).querySelector('input[type="checkbox"]').checked;
                let id = aRow.sId;
                const json = JSON.parse(aRow.aCells[8].getValue());
                delete json.pc01_descrmater;
                delete json.l04_descricao;
                delete json.reservado;

                if(lote_selected && json.lote_id && lote_selected != json.lote_id) {
                    document.getElementById(aRow.aCells[0].getId()).querySelector('input[type="checkbox"]').checked = false;
                    return false;
                }

                const index = itensChangePorLote.findIndex(
                    item => (item.l21_codigo == json.l21_codigo)
                );
                
                if(checked){
                    if(index === -1){
                        itensChangePorLote.push(json);
                    }
                } else {
                    if(index !== -1){
                        itensChangePorLote.splice(index, 1)
                    }
                }

            });

            loadLoteInTable();
        }

        function getItensSaveLote(lote = null){
            let aData = [];
            oGridPorLotes.aRows.each(function(aRow, iIndice){
                let checked = document.getElementById(aRow.aCells[0].getId()).querySelector('input[type="checkbox"]').checked;
                if(!checked) return;
                const json = JSON.parse(aRow.aCells[8].getContent());
                delete json.pc01_descrmater;
                delete json.l04_descricao;
                delete json.reservado;

                if((lote == null && json.lote_id != undefined) || (lote != null && json.lote_id == lote)){
                    aData.push(json);
                }
            });

            return aData;
        }

        function loadLotes(){
            selectLotes.innerHTML = '<option value="">Selecione</option>';
            let oParam = {};
            oParam.exec = 'getLotes';
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
                            option.value = oValue.l24_codigo;
                            option.text = oValue.l24_pcdesc;

                            selectLotes.appendChild(option);
                        });

                        estadosProxy.lotes = true;
                    }
                }
            );
        }

        if(selectLotes != null){
            selectLotes.addEventListener('change', function(e){
                e.preventDefault();
                document.getElementById('gridgridItensPorLote').querySelector('table tbody').querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                });

                renderLineLoteTable();
                loadLoteInTable();
            });
        }

        function renderLineLoteTable(){
            const loteValue = selectLotes.options[selectLotes.selectedIndex].value;
            oGridPorLotes.aRows.each(function(aRow, iIndice){
                let id = aRow.sId;
                const json = JSON.parse(aRow.aCells[8].getContent());
                if(json.lote_id != undefined && json.lote_id != loteValue){
                    document.getElementById(id).classList.add('disabled');
                } else if(json.lote_id != undefined && json.lote_id == loteValue){
                    document.getElementById(id).classList.remove('disabled');
                    document.getElementById(id).querySelector('input[type=checkbox]').checked = true;
                }
            });
        }

        function loadLoteInTable(){
            if(!selectLotes.value){
                return false;
            }

            const lote = selectLotes.options[selectLotes.selectedIndex].text;
            const loteValue = selectLotes.options[selectLotes.selectedIndex].value;
            oGridPorLotes.aRows.each(function(aRow, iIndice){
                let checked = document.getElementById(aRow.aCells[0].getId()).querySelector('input[type="checkbox"]').checked;
                let id = aRow.sId;
                const json = JSON.parse(aRow.aCells[8].getContent());

                if(checked){
                    document.getElementById(id).querySelector('td:nth-child(8) .tooltip-target').setAttribute('data-tooltip', lote);
                    document.getElementById(id).querySelector('td:nth-child(8) .tooltip-target').textContent = lote;
                    json.lote_id = loteValue;
                    json.lote = lote;
                    aRow.aCells[8].setContent(JSON.stringify(json));
                } else if(!checked && json.l04_descricao != null){
                    document.getElementById(id).querySelector('td:nth-child(8) .tooltip-target').setAttribute('data-tooltip', json.l04_descricao);
                    document.getElementById(id).querySelector('td:nth-child(8) .tooltip-target').textContent = json.l04_descricao;
                    // delete json.lote_id;
                    // delete json.lote;
                    aRow.aCells[8].setContent(JSON.stringify(json));
                }else if(!checked && json.lote_id == loteValue) {
                    document.getElementById(id).querySelector('td:nth-child(8) .tooltip-target').setAttribute('data-tooltip', '');
                    document.getElementById(id).querySelector('td:nth-child(8) .tooltip-target').textContent = '';
                    // delete json.lote_id;
                    // delete json.lote;
                    aRow.aCells[8].setContent(JSON.stringify(json));
                }
            });
        }

        window.loadLotes = loadLotes;
        window.loadingPorLotes = loadingPorLotes;

        function init(){
            Swal.fire({
                title: 'Aguarde...',
                text: 'Estamos processando sua solicitação.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            loadLotes();
            initGridPorLote();
            loadingPorLotes();

            if(typeof isDisabledButtonsLote != 'undefined' && isDisabledButtonsLote){
                document.querySelectorAll('button').forEach(button => {
                    button.disabled = true;
                })
            } else {
                document.querySelectorAll('button').forEach(button => {
                    button.disabled = false;
                })
            }
        }

        init();
    })();
</script>
