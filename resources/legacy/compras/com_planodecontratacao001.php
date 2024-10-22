<?php
    require("libs/db_stdlib.php");
    require("libs/db_conecta.php");
    include("libs/db_sessoes.php");
    include("libs/db_usuariosonline.php");
    include("dbforms/db_funcoes.php");
    include("dbforms/db_classesgenericas.php");
    include("classes/db_pcparam_classe.php");

    // $result = null;
    // parse_str($HTTP_SERVER_VARS['QUERY_STRING'], $result);
    // db_postmemory($HTTP_GET_VARS);

?>
<!DOCTYPE html>
<html>

<head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href='estilos.css' rel='stylesheet' type='text/css'>
    <?php
        db_app::load("scripts.js");
        db_app::load("prototype.js");
        db_app::load("datagrid.widget.js");
        db_app::load("strings.js");
        db_app::load("grid.style.css");
        db_app::load("estilos.css");
        db_app::load("classes/dbViewAvaliacoes.classe.js");
        db_app::load("widgets/windowAux.widget.js");
        db_app::load("widgets/dbmessageBoard.widget.js");
        db_app::load("dbcomboBox.widget.js");
        db_app::load("estilos.bootstrap.css");
        db_app::load("sweetalert.js");
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
            max-width: 1024px; /* Largura mï¿½xima do conteï¿½do */
            width: 100%; /* Para garantir responsividade */
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
    </style>
</head>
    <body class="container">
        <form name="form1" id="frmFiltroPlanoContratacao" method="post" style="margin-top: 10px;">
            <div class="row">
                <div class="col-12" style="margin-bottom: 10px;">
                    <button type="button" class="btn btn-success" id="btnFiltroPlano" style="margin-top: 20px; margin-bottom: 15px; cursor:pointer">Novo PCA</button>
                </div>
                <div class="col-12 col-sm-4 form-group tdleft">
                    <label for="mpc01_ano"><b>Ano: </b></label>
                    <select name="mpc01_ano" id="mpc01_ano" class="custom-select">
                        <option value="0" selected>Selecione</option>
                    </select>
                </div>
                <div class="col-12 col-sm-8 form-group tdleft">
                    <label for="mpc01_uncompradora"><b>Unidade Compradora: </b></label>
                    <select name="mpc01_uncompradora" id="mpc01_uncompradora" class="custom-select">
                        <option value="0">Selecione</option>
                    </select>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-12" id="containTabelaPlanoContratacao" style="margin-top: 20px;"></div>
        </div>
    </body>
</html>
<script>
    const url = 'com_planodecontratacao.RPC.php';
    const form = document.getElementById('frmFiltroPlanoContratacao');
    const button = document.getElementById('btnFiltroPlano');
    const selectUnidadeCompradora = document.getElementById('mpc01_uncompradora');
    const selectAno = document.getElementById('mpc01_ano');
    const oGridPlanoContratacoes = new DBGrid('containTabelaPlanoContratacao');
    const eventoChange = new Event('change');
    const formatter = (new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 4, maximumFractionDigits: 4 }));
    const eventoClick = new Event('click');
    let limit = 15;
    // ****************************** ANOS ****************************

    js_getAnos();
    function js_getAnos(){
        let oParam = {};
        oParam.exec = "exercicio";
        oParam.loadmore = 5;
        let oAjax = new Ajax.Request(
            url,
            {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_loadAnos
            }
        );
    }

    function js_loadAnos(oAjax){
        let oRetorno = JSON.parse(oAjax.responseText)

        let anos = oRetorno.anos;
        Object.entries(anos).forEach(([oAno, iSeq]) => {
            const option = document.createElement('option');
            option.value = iSeq;
            option.text = oAno;

            // if(iSeq == oRetorno.anousu){
            //     option.selected = true;
            // }

            selectAno.appendChild(option);
        });

        selectAno.dispatchEvent(eventoChange);
    }

    // ****************************** UNIDADE COMPRADORA ****************************
    js_getUnidadeCompradoras();
    function js_getUnidadeCompradoras(){
        let oParam = {}
        oParam.exec = "unidadeCompradora"
        let oAjax = new Ajax.Request(
            url,
            {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_loadUnidadeCompradora
            }
        );
    }

    function js_loadUnidadeCompradora(oAjax) {
        let oRetorno = JSON.parse(oAjax.responseText)

        let unidades = oRetorno.unidadeCompradora;
        unidades.forEach((oValue, iSeq) => {
            const option = document.createElement('option');
            option.value = oValue.codigo;
            option.text = oValue.nomeinst;

            if(oRetorno.instit == oValue.codigo){
                option.selected = true;
            }

            selectUnidadeCompradora.appendChild(option);
        });

        selectUnidadeCompradora.dispatchEvent(eventoChange);
    }

    // ****************************** TABELA ****************************
    js_gridPlanoContratacoes();
    function js_gridPlanoContratacoes(){
        oGridPlanoContratacoes.nameInstance = 'oGridPlanoContratacoes';
        oGridPlanoContratacoes.setCellAlign([
            'center',
            'center',
            'center',
            'center',
            'center',
            'center'
        ]);
        oGridPlanoContratacoes.setCellWidth([
            '6%',
            '6%',
            '43%',
            '25%',
            '10%',
            '10%',
        ]);
        oGridPlanoContratacoes.setHeader([
            'Código',
            'Ano',
            'Unidade Compradora',
            'ID PNPC',
            'Status',
            'Opções'
        ]);
        oGridPlanoContratacoes.allowSelectColumns(true);
        oGridPlanoContratacoes.setHeight(250);
        oGridPlanoContratacoes.show(document.getElementById('containTabelaPlanoContratacao'));
        oGridPlanoContratacoes.clearAll(true);
        oGridPlanoContratacoes.paginatorInitialize((pageNumber) => {
            loadingPlanoContratacoes(pageNumber, false);
        });
    }

    function loadingPlanoContratacoes(offset = 1, reloadGrafico = true){
        const ano = document.getElementById('mpc01_ano').value;
        const unidade = document.getElementById('mpc01_uncompradora').value;

        let oParam = new Object();
        oParam.exec = 'listagemPlanoContracoes';
        oParam.ano = ano;
        oParam.unidade = unidade;
        oParam.offset = offset;
        oParam.limit = limit;
        oGridPlanoContratacoes.showLoading();
        new Ajax.Request(
            url,
            {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: (oAjax) => {
                    let oRetorno = JSON.parse(oAjax.responseText);
                    oGridPlanoContratacoes.clearAll(true);
                    oRetorno.planoContratacoes.forEach(function(oValue, iSeq){
                        let aLinha = new Array();
                        aLinha[0] = oValue.mpc01_codigo;
                        aLinha[1] = oValue.mpc01_ano;
                        aLinha[2] = oValue.nomeinst;
                        aLinha[3] = oValue.mpc01_sequencial || '';
                        aLinha[4] = oValue.mpc01_is_send_pncp == 1 ? 'Enviado' : 'Não Enviado';
                        aLinha[5] = `
                            <a
                                href="#"
                                style="margin: 0 5px"
                                onclick="openEdicao(
                                    event,
                                    ${oValue.mpc01_codigo},
                                    ${oValue.mpc01_ano},
                                    ${oValue.mpc01_uncompradora},
                                    '${oValue.mpc01_data}',
                                    '${oValue.mpc01_datacria}',
                                    ${oValue.mpc01_usuario},
                                    ${oValue.mpc01_is_send_pncp}
                                )"
                            >A</a>
                            <a
                                href="#"
                                style="margin: 0 5px"
                                onclick="openExclusao(event, ${oValue.mpc01_codigo}, ${oValue.mpc01_is_send_pncp || 0})"
                            >E</a>
                        `;
                        oGridPlanoContratacoes.addRow(aLinha);
                    })

                    oGridPlanoContratacoes.renderRows();
                    let totalPages = (oRetorno.total) ? (oRetorno.total / limit) : 0;
                    oGridPlanoContratacoes.renderPagination(totalPages, offset);
                    oGridPlanoContratacoes.hideLoading();
                }
            }
        )
    }

    function openEdicao(
        event,
        mpc01_codigo,
        mpc01_ano,
        mpc01_uncompradora,
        mpc01_data,
        mpc01_datacria,
        mpc01_usuario,
        mpc01_is_send_pncp
    ){
        event.preventDefault();
        let currentUrl = new URL('com_planodecontratacao002.php', window.location.href);
        currentUrl.searchParams.set('mpc01_codigo', mpc01_codigo);
        currentUrl.searchParams.set('mpc01_ano', mpc01_ano);
        currentUrl.searchParams.set('mpc01_uncompradora', mpc01_uncompradora);
        currentUrl.searchParams.set('mpc01_data', mpc01_data);
        currentUrl.searchParams.set('mpc01_datacria', mpc01_datacria);
        currentUrl.searchParams.set('mpc01_usuario', mpc01_usuario);
        currentUrl.searchParams.set('mpc01_is_send_pncp', mpc01_is_send_pncp);

        if(typeof db_iframe_plano_contratacao != 'undefined'){
            db_iframe_plano_contratacao.jan.location.href = currentUrl.toString();
            db_iframe_plano_contratacao.show();
            return false;
        }

        js_OpenJanelaIframe(
            'CurrentWindow.corpo',
            'db_iframe_plano_contratacao',
            currentUrl.toString(),
            'Plano de Contratação > Alteração',
            true
        )
    }

    function openExclusao(event, mpc01_codigo, mpc01_is_send_pncp){
        event.preventDefault();
        if(mpc01_is_send_pncp == 1){
            Swal.fire({
                icon: 'warning',
                title: 'Atenção!',
                text: 'Exclusão abortada, Plano de Contratações já enviados ao PNCP não podem ser excluídos, caso necessário remover primeiro do PNCP.',
            });
            return false;
        }

        Swal.fire(
            {
                title: 'Você tem certeza que deseja excluir o plano de contratação?',
                text: "Essa ação não pode ser desfeita!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar',
            }
        ).then((result) => {
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
                oParam.exec = 'removePlanoContratacao';
                oParam.mpc01_codigo = mpc01_codigo;
                let oAjax = new Ajax.Request(
                    url,
                    {
                        method: 'post',
                        parameters: 'json=' + Object.toJSON(oParam),
                        onComplete: (oAjax) => {
                            let oResponse = JSON.parse(oAjax.responseText);
                            if(oResponse.status == 200){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sucesso!',
                                    text: oResponse.message,
                                });
                                loadingPlanoContratacoes();
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
            }
        });
    }

    selectAno.addEventListener('change', loadingPlanoContratacoes);
    selectUnidadeCompradora.addEventListener('change', loadingPlanoContratacoes);
    // ****************************** FORM ****************************
    button.addEventListener('click', function(e){
        e.preventDefault();
        const ano = document.getElementById('mpc01_ano').value;
        const unidade = document.getElementById('mpc01_uncompradora').value;

        if(ano <= 0){
            Swal.fire({
                icon: 'warning',
                title: 'Atenção!',
                text: 'Campo exercicio é obrigatório!',
            });
            return false;
        }

        js_OpenJanelaIframe(
            'CurrentWindow.corpo',
            'db_iframe_plano_contratacao',
            `com_planodecontratacao002.php?mpc01_ano=${encodeURIComponent(ano)}&mpc01_uncompradora=${encodeURIComponent(unidade)}`,
            'Plano de Contratação > Inclusão',
            true
        );
    });

    function closePlanoContratacao(refresh = false){
        if(typeof db_iframe_plano_contratacao_remove != 'undefined'){
            db_iframe_plano_contratacao_remove.hide();
        }

        if(typeof db_iframe_plano_contratacao != 'undefined'){
            db_iframe_plano_contratacao.hide();
        }

        if(refresh){
            loadingPlanoContratacoes();
        }
    }
</script>
