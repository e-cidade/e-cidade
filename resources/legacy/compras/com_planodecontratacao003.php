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
        <title>DBSeller Informática Ltda - Página Inicial</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="Expires" CONTENT="0">
        <link href="../../../FrontController.php" rel="stylesheet" type="text/css">
        <?php
            db_app::load("scripts.js", date('YmdHis'));
            db_app::load("strings.js");
            db_app::load("prototype.js");
            db_app::load("datagrid.widget.js");
            db_app::load("widgets/dbautocomplete.widget.js");
            db_app::load("widgets/windowAux.widget.js");
            db_app::load("estilos.css");
            db_app::load("mask.js");
            db_app::load("form.js");
            db_app::load("estilos.bootstrap.css");
            db_app::load("sweetalert.js");
            db_app::load("just-validate.js");
        ?>
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
                margin-top: 20px; /* Espaï¿½o acima do container */
                background-color: #FFFFFF;
                padding: 20px;
                max-width: 967px; /* Largura mï¿½xima do conteï¿½do */
                width: 100%; /* Para garantir responsividade */
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra leve */
            }
            label{
                display: block;
                text-align: left;
            }
            .contain-inputs-pesquisa-item{
                display: flex;
                align-content: center;
                gap: 10px;
            }
            #div_lista_mpc02_descsubgrupo,
            #div_lista_mpc02_descmater{
                top: calc(3.5em + 0.75rem + 2px) !important;
                left: 0 !important;
            }
            .contain-input-data{
                display: flex;
                gap: 6px;
                align-items: center;
            }

            .DBJanelaIframeTitulo{
                text-align: left;
            }
        </style>
    </head>
    <body class="container" style="min-height: auto;">
        <form action="" method="post" name="form1" id="frmItemPca" style="margin-top: 10px;">
            <fieldset>
                <legend><b><?= !empty($mpc02_codigo)? 'Atualizar Item' : 'Inserir Item' ?></b></legend>
                <input type="hidden" name="mpc01_usuario" id="mpc01_usuario" value="<?= db_getsession("DB_id_usuario") ?>">
                <input type="hidden" name="exec" id="exec" value="<?= !empty($mpc02_codigo)? 'updateItemPlanoContratacao' : 'createItemPlanoContratacao'; ?>">
                <input type="hidden" name="mpc02_codigo" value="<?= $mpc02_codigo ?? ''?>">
                <input type="hidden" name="mpc01_codigo" value="<?= $mpc01_codigo ?? ''?>">
                <input type="hidden" id="mpcpc01_codigo" name="mpcpc01_codigo" value="">
                <div class="row">
                    <div class="col-12 col-sm-2 form-group">
                        <label for="mpc02_codmater"><b>Item:</b></label>
                        <?php
                            db_input(
                                'mpc02_codmater',
                                8,
                                '',
                                true,
                                'text',
                                1,
                                " onchange='pesquisa_codmater();'",
                                "",
                                "",
                                "",
                                null,
                                "form-control",
                                [
                                    'validate-required'          => "true",
                                    'validate-minlength'         => "1",
                                    // 'validate-maxlength'         => "30",
                                    'validate-required-message'  => "O código do item é obrigatório",
                                    'validate-minlength-message' => "O código deve ter pelo menos 1 caracteres",
                                    // 'validate-maxlength-message' => "O nome não pode ter mais de 30 caracteres"
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-6 form-group" style="position: relative;">
                        <label for=""></label>
                        <?php
                            db_input(
                                'mpc02_descmater',
                                50,
                                '',
                                true,
                                'text',
                                1,
                                "",
                                "",
                                "",
                                "margin-top: 0.9em;",
                                null,
                                "form-control",
                                [
                                    'validate-required'          => "true",
                                    'validate-minlength'         => "1",
                                    // 'validate-maxlength'         => "30",
                                    'validate-required-message'  => "A descrição do item é obrigatório",
                                    'validate-minlength-message' => "O nome deve ter pelo menos 1 caracteres",
                                    // 'validate-maxlength-message' => "O nome não pode ter mais de 30 caracteres"
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-4 form-group">
                        <label for="mpcpc01_datap"><b>Previsão:</b></label>
                        <?php
                            db_input(
                                'mpcpc01_datap',
                                "",
                                '',
                                true,
                                'date',
                                1,
                                "",
                                "",
                                "",
                                "",
                                null,
                                "form-control",
                                [
                                    'validate-required' => 'true',
                                    'validate-date' => 'true',
                                    'validate-date-message' => 'Informe uma data válida'
                                ]
                            );
                        ?>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-12 col-sm-3 form-group">
                        <label for="mpc02_categoria"><b>Categoria:</b></label>
                        <select
                            name="mpc02_categoria"
                            id="mpc02_categoria"
                            class="custom-select"
                            data-validate-required="true"
                            data-validate-required-message="A categoria é obrigatória"
                        >
                            <option value="">Selecione</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-3 form-group">
                        <label for=""><b>Qtd.:</b></label>
                        <?php
                            db_input(
                                'mpcpc01_qtdd',
                                10,
                                '',
                                true,
                                'number',
                                1,
                                "",
                                "",
                                "",
                                "",
                                null,
                                "form-control",
                                [
                                    'validate-required' => 'true',
                                    'validate-min' => '1',
                                    'validate-min-message' => 'A Quantidade deve ser maior que 0',
                                    'validate-required-message'  => 'A quantidade é obrigatório',
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-3 form-group">
                        <label for=""><b>Vlr. Unit.:</b></label>
                        <?php
                            db_input(
                                'mpcpc01_vlrunit',
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
                                "form-control",
                                [
                                    'validate-required'          => "true",
                                    'validate-minlength'         => "1",
                                    // 'validate-maxlength'         => "30",
                                    'validate-required-message'  => "O valor unitário é obrigatório",
                                    'validate-minlength-message' => "O valor unitário deve ter pelo menos 1 caracteres",
                                    // 'validate-maxlength-message' => "O nome não pode ter mais de 30 caracteres"
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-3 form-group">
                        <label for=""><b>Vlr Total:</b></label>
                        <?php
                            db_input(
                                'mpcpc01_vlrtotal',
                                10,
                                '',
                                true,
                                'text',
                                3,
                                "",
                                "",
                                "",
                                "",
                                null,
                                "form-control"
                            );
                        ?>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-12 col-sm-3 form-group">
                        <label for="mpc02_un"><b>Unidade:</b></label>
                        <select
                            name="mpc02_un"
                            id="mpc02_un"
                            class="custom-select"
                            data-validate-required="true"
                            data-validate-required-message="A unidade é obrigatória"
                        >
                            <option value="">Selecione</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-9 form-group">
                        <label for="mpc02_depto"><b>Departamento:</b></label>
                        <select
                            name="mpc02_depto"
                            id="mpc02_depto"
                            class="custom-select disabled"
                            data-validate-required="true"
                            data-validate-required-message="O departamento é obrigatória"
                        >
                            <option value="">Selecione</option>
                        </select>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-12 col-sm-6 form-group">
                        <label for="mpc02_catalogo"><b>Catálogo:</b></label>
                        <select
                            name="mpc02_catalogo"
                            id="mpc02_catalogo"
                            class="custom-select"
                            data-validate-required="true"
                            data-validate-required-message="O catálogo é obrigatória"
                        >
                            <option value="">Selecione</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 form-group">
                        <label for="mpc02_tproduto"><b>Tipo de produto:</b></label>
                        <select
                            name="mpc02_tproduto"
                            id="mpc02_tproduto"
                            class="custom-select"
                            data-validate-required="true"
                            data-validate-required-message="A tipo do produto é obrigatória"
                        >
                            <option value="">Selecione</option>
                        </select>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-12 col-sm-2">
                        <label for="mpc02_subgrupo"><b>Grupo:</b></label>
                        <?php
                            db_input(
                                'mpc02_subgrupo',
                                8,
                                '',
                                true,
                                'text',
                                1,
                                " onchange='pesquisa_subgrupo();'",
                                "",
                                "",
                                "",
                                null,
                                "form-control",
                                [
                                    'validate-required'          => "true",
                                    'validate-minlength'         => "1",
                                    'validate-required-message'  => "O código do grupo é obrigatório",
                                    'validate-minlength-message' => "O código do grupo deve ter pelo menos 1 caracteres",
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-6" style="position: relative;">
                        <?php
                            db_input(
                                'mpc02_descsubgrupo',
                                50,
                                '',
                                true,
                                'text',
                                1,
                                "",
                                "",
                                "",
                                " margin-top: 0.9em;",
                                null,
                                "form-control",
                                [
                                    'validate-required'          => "true",
                                    'validate-minlength'         => "1",
                                    'validate-required-message'  => "A descrição do grupo é obrigatório",
                                    'validate-minlength-message' => "A descrição do grupo deve ter pelo menos 1 caracteres",
                                ]
                            );
                        ?>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px; margin-bottom: 15px;">
                    <div class="col-12 text-center">
                        <button class="btn btn-danger" type="button" id="btnCancelarItem" style="margin: 0 8px;">Cancelar</button>
                        <?php if(!empty($mpc02_codigo)): ?>
                            <button class="btn btn-success" type="button" id="btnAtualizarItem" style="margin: 0 8px;">Atualizar</button>
                        <?php else: ?>
                            <button class="btn btn-success" type="button" id="btnInserirItem" style="margin: 0 8px;">Inserir</button>
                        <?php endif; ?>
                    </div>
                </div>
            </fieldset>
        </form>
    </body>
</html>
<script>
    const url = 'com_planodecontratacao.RPC.php';
    const url_pcmater = 'com_pcmater.RPC.php';
    const url_descsubgrupo = 'com_pcsubgrupo.RPC.php';
    const mpc01_uncompradora = '<?= $mpc01_uncompradora ?>';
    const selectCategorias = document.getElementById('mpc02_categoria');
    const selectUnidade = document.getElementById('mpc02_un');
    const selectDepto = document.getElementById('mpc02_depto');
    const selectCatalogo = document.getElementById('mpc02_catalogo');
    const selectTipoProduto = document.getElementById('mpc02_tproduto');
    const mpc02_codigo = <?= !empty($mpc02_codigo) ? $mpc02_codigo : "null"; ?>;
    const mpc01_codigo = <?= !empty($mpc01_codigo) ? $mpc01_codigo : "null"; ?>;
    const validator = initializeValidation('#frmItemPca');
    const formatter = (new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 4, maximumFractionDigits: 4 }));

    const btnIncluir = document.getElementById('btnInserirItem');
    const btnAtualizarItem = document.getElementById('btnAtualizarItem');
    const btnCancelarItem = document.getElementById('btnCancelarItem');

    let currentUrlPcMater = new URL(url_pcmater, window.location.href);
    currentUrlPcMater.searchParams.set('exec', 'listagemPcMater');
    const oAutoComplete = new dbAutoComplete(document.getElementById('mpc02_descmater'), currentUrlPcMater.toString());
    oAutoComplete.setTxtFieldId(document.getElementById('mpc02_codmater'));
    oAutoComplete.show();

    let currentUrlPcSubGrupo = new URL(url_descsubgrupo, window.location.href);
    currentUrlPcSubGrupo.searchParams.set('exec', 'listagemPcSubGrupo');
    const oAutoCompleteGrupo = new dbAutoComplete(document.getElementById('mpc02_descsubgrupo'), currentUrlPcSubGrupo.toString());
    oAutoCompleteGrupo.setTxtFieldId(document.getElementById('mpc02_subgrupo'));
    oAutoCompleteGrupo.show();

    restrictAndFormatInput(document.getElementById('mpcpc01_vlrunit'), 4);

    document.getElementById('mpcpc01_qtdd').addEventListener('blur', calcValorTotal);
    document.getElementById('mpcpc01_vlrunit').addEventListener('blur', calcValorTotal);

    function calcValorTotal(){
        let qtd = parseFloat(document.getElementById('mpcpc01_qtdd').value.replace('.', '').replaceAll(',', '.'));
        let valorUnit = parseFloat(document.getElementById('mpcpc01_vlrunit').value.replace('.', '').replaceAll(',', '.'));
        let valorTotal = (qtd || 0) * (valorUnit || 0);
        document.getElementById('mpcpc01_vlrtotal').value = (new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 4, maximumFractionDigits: 4 })).format(valorTotal);
    }

    function pesquisa_codmater() {
        if (document.getElementById('mpc02_codmater').value != '') {
            let currentUrl = new URL('func_pcmatersolicita.php', window.location.href);
            currentUrl.searchParams.set('pesquisa_chave', document.getElementById('mpc02_codmater').value);
            currentUrl.searchParams.set('funcao_js', "parent.mostrapcmater");

            js_OpenJanelaIframe(
                '',
                'db_iframe_pcmater',
                currentUrl.toString(),
                'Item',
                false,
                '0'
            );
        } else {
            $('mpc02_codmater').value = '';
        }
    }

    function mostrapcmater(sDescricaoMaterial, Erro) {
        document.getElementById('mpc02_descmater').value = sDescricaoMaterial;
        if (Erro == true) {
            document.getElementById('mpc02_descmater').value = "";
        }
    }

    function pesquisa_subgrupo(){
        if(document.getElementById('mpc02_subgrupo').value != ''){
            let currentUrl = new URL('func_pcsubgrupo.php', window.location.href);
            currentUrl.searchParams.set('pesquisa_chave', document.getElementById('mpc02_subgrupo').value);
            currentUrl.searchParams.set('funcao_js', 'parent.mostrasubgrupo')
            js_OpenJanelaIframe(
                '',
                'db_iframe_pcsubgrupo',
                currentUrl.toString(),
                'Grupo',
                false,
                '0'
            );
        } else {
            $('mpc02_subgrupo').value = '';
        }
    }

    function mostrasubgrupo(sDescricaoMaterial, Erro) {
        document.getElementById('mpc02_descsubgrupo').value = sDescricaoMaterial;
        if (Erro == true) {
            document.getElementById('mpc02_descsubgrupo').value = "";
        }
    }

    async function getCategoria(mpc03_codigo = null){
        let oParam = {};
        oParam.exec = "listagemCategorias"
        let oAjax = await new Ajax.Request(
            url,
            {
                method: 'post',
                asynchronous: true,
                parameters: 'json=' + JSON.stringify(oParam),
                onComplete: function(oAjax){
                    let oRetorno = JSON.parse(oAjax.responseText)

                    oRetorno.pccategorias.forEach((oValue, iSeq) => {
                        const option = document.createElement('option');
                        option.value = oValue.mpc03_codigo;
                        option.text = oValue.mpc03_pcdesc;

                        if(mpc03_codigo == oValue.mpc03_codigo){
                            option.selected = true;
                        }

                        selectCategorias.appendChild(option);
                    });
                }
            }
        );
    }

    async function getUnidade(m61_codmatunid = null){
        let oParam = {};
        oParam.exec = "listagemUnidade"
        let oAjax = await new Ajax.Request(
            url,
            {
                method: 'post',
                asynchronous: true,
                parameters: 'json=' + JSON.stringify(oParam),
                onComplete: function(oAjax){
                    let oRetorno = JSON.parse(oAjax.responseText)
                    oRetorno.matunid.forEach(function(oValue, iSeq){
                        const option = document.createElement('option');
                        option.value = oValue.m61_codmatunid;
                        option.text = oValue.m61_descr;

                        if(m61_codmatunid == oValue.m61_codmatunid){
                            option.selected = true;
                        }

                        selectUnidade.appendChild(option);
                    });
                }
            }
        )
    }

    async function getDepartamento(codigo = null){
        let oParam = {};
        oParam.exec = "listagemDepartamento"
        let oAjax = await new Ajax.Request(
            url,
            {
                method: 'post',
                asynchronous: true,
                parameters: 'json=' + JSON.stringify(oParam),
                onComplete: function(oAjax){
                    let oRetorno = JSON.parse(oAjax.responseText)
                    oRetorno.dbconfig.forEach(function(oValue, iSeq){
                        const option = document.createElement('option');
                        option.value = oValue.codigo;
                        option.text = oValue.nomeinst;

                        if(codigo == oValue.codigo || oValue.codigo == mpc01_uncompradora){
                            option.selected = true;
                        }

                        selectDepto.appendChild(option);
                    });
                }
            }
        );
    }

    async function getCatalogo(mpc04_codigo = null){
        let oParam = {};
        oParam.exec = "listagemCatalogo"
        let oAjax = await new Ajax.Request(
            url,
            {
                method: 'post',
                parameters: 'json=' + JSON.stringify(oParam),
                asynchronous: true,
                onComplete: function(oAjax){
                    let oRetorno = JSON.parse(oAjax.responseText)
                    oRetorno.pccatalogo.forEach(function(oValue, iSeq){
                        const option = document.createElement('option');
                        option.value = oValue.mpc04_codigo;
                        option.text = oValue.mpc04_pcdesc;

                        if(mpc04_codigo == oValue.mpc04_codigo){
                            option.selected = true;
                        }

                        selectCatalogo.appendChild(option);
                    });
                }
            }
        )
    }

    async function getTipoProduto(mpc05_codigo = null){
        let oParam = {};
        oParam.exec = 'listagemTipoProduto'
        let oAjax = await new Ajax.Request(
            url,
            {
                method: 'post',
                asynchronous: true,
                parameters: 'json=' + JSON.stringify(oParam),
                onComplete: function(oAjax){
                    let oRetorno = JSON.parse(oAjax.responseText)
                    oRetorno.pctipoproduto.forEach(function(oValue, iSeq){
                        const option = document.createElement('option');
                        option.value = oValue.mpc05_codigo;
                        option.text = oValue.mpc05_pcdesc;

                        if(mpc05_codigo == oValue.mpc05_codigo){
                            option.selected = true;
                        }

                        selectTipoProduto.appendChild(option);
                    });
                }
            }
        )
    }

    if(btnIncluir != null){
        btnIncluir.addEventListener('click', function(e){
            e.preventDefault();
            const formData = serializarFormulario(document.getElementById('frmItemPca'));
            const isValid = validator.validate();
            if(!isValid){
                return false;
            }

            Swal.fire({
                title: 'Aguarde...',
                text: 'Estamos processando sua solicitação.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            let oAjax = new Ajax.Request(
                url,
                {
                    method: 'post',
                    parameters: 'json=' + formData,
                    onComplete: (oAjax) => {
                        let oResponse = JSON.parse(oAjax.responseText);
                        if(oResponse.pcpcitem != null){
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso!',
                                text: 'Item salvo com sucesso!',
                            });
                            document.getElementById('frmItemPca').reset();
                            parent.closePlanoContratacaoItem(true);
                            return false;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: oResponse.message,
                        });
                    }
                }
            );
        })
    }

    if(btnAtualizarItem != null){
        btnAtualizarItem.addEventListener('click', function(e){
            e.preventDefault();
            const formData = serializarFormulario(document.getElementById('frmItemPca'));
            const isValid = validator.validate();
            if(!isValid){
                return false;
            }

            Swal.fire({
                title: 'Aguarde...',
                text: 'Estamos processando sua solicitação.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            let oAjax = new Ajax.Request(
                url,
                {
                    method: 'post',
                    parameters: 'json=' + formData,
                    onComplete: (oAjax) => {
                        let oResponse = JSON.parse(oAjax.responseText);
                        if(oResponse.pcpcitem != null){
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso!',
                                text: 'Item salvo com sucesso!',
                            });
                            document.getElementById('frmItemPca').reset();
                            parent.closePlanoContratacaoItem(true);
                            return false;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: oResponse.message,
                        });
                    }
                }
            );
        });
    }

    btnCancelarItem.addEventListener('click', function(e){
        e.preventDefault();
        parent.closePlanoContratacaoItem(false);
        return false;
    })

    function loadSelects(){
        getCategoria();
        getUnidade();
        getDepartamento();
        getCatalogo();
        getTipoProduto();
    }

    function loadEditItem(){
        let oParam = {};
        oParam.exec = 'getPlanoContratacaoItemByCodigo';
        oParam.mpc02_codigo = mpc02_codigo;
        oParam.mpc01_codigo = mpc01_codigo;
        js_divCarregando("Aguarde, carregando informações...", 'msgbox');

        let oAjax = new Ajax.Request(
            url,
            {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: async function(oAjax){
                    let oRetorno = JSON.parse(oAjax.responseText);
                    let planoContratacaoItem = oRetorno.planoContratacaoItem;

                    document.getElementById('mpcpc01_codigo').value = planoContratacaoItem.mpcpc01_codigo;
                    document.getElementById('mpc02_codmater').value = planoContratacaoItem.pc01_codmater;
                    document.getElementById('mpc02_descmater').value = planoContratacaoItem.pc01_descrmater;
                    document.getElementById('mpcpc01_datap').value = planoContratacaoItem.mpcpc01_datap;
                    document.getElementById('mpcpc01_qtdd').value = parseInt(planoContratacaoItem.mpcpc01_qtdd);
                    document.getElementById('mpcpc01_vlrunit').value = formatter.format(planoContratacaoItem.mpcpc01_vlrunit);
                    document.getElementById('mpcpc01_vlrtotal').value = formatter.format(planoContratacaoItem.mpcpc01_vlrtotal);
                    document.getElementById('mpc02_subgrupo').value = planoContratacaoItem.pc04_codsubgrupo;
                    document.getElementById('mpc02_descsubgrupo').value = planoContratacaoItem.pc04_descrsubgrupo;
                    await getCategoria(planoContratacaoItem.mpc03_codigo);
                    await getUnidade(planoContratacaoItem.m61_codmatunid);
                    await getDepartamento(planoContratacaoItem.codigo);
                    await getCatalogo(planoContratacaoItem.mpc04_codigo);
                    await getTipoProduto(planoContratacaoItem.mpc05_codigo);

                    js_removeObj('msgbox');
                }
            }
        );
    }

    if(mpc02_codigo != null && typeof mpc02_codigo == 'number'){
        loadEditItem();
    } else {
        loadSelects()
    }
</script>
