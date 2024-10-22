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

    if(!empty($mpc01_data)){
        $m40_data_dia = date('d', strtotime($mpc01_data));
        $m40_data_mes = date('m', strtotime($mpc01_data));
        $m40_data_ano = date('Y', strtotime($mpc01_data));
    }

?>
<!DOCTYPE html>
<html>

<head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../../FrontController.php" rel="stylesheet" type="text/css">
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script> -->
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
        db_app::load("form.js");
        db_app::load("chart.js");
        db_app::load("chartjs-randcolors.js");
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
        .contain-buttons-plano{
            display: flex;
            align-items: center;
        }

        .contain-input-data{
            display: flex;
            gap: 6px;
            align-items: center;
        }
        .contain-buttons-plano{
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: center;
        }

        .qtde-itens,
        .valor-estimado{
            font-size: 30px;
            padding: 15px 10px;
            display: block;
            color: white;
            font-weight: bold;
        }

        .valor-estimado {
            background: #689E39;
        }

        .qtde-itens {
            background: #2296F3;
        }

        .DBJanelaIframeTitulo{
            text-align: left;
        }

        /* div.body-container{
            min-height: 250px;
            overflow-y: visible !important;
            height: auto !important;
        } */
    </style>
</head>
    <body class="container">
        <form name="form1" id="frmPca" method="post" style="margin-top: 10px;">
            <fieldset>
                <legend><b>Plano de Contratação</b></legend>
                <input type="hidden" name="mpc01_usuario" id="mpc01_usuario" value="<?= db_getsession("DB_id_usuario") ?>">
                <input type="hidden" name="exec" id="exec" value="<?= !empty($mpc01_codigo)? 'updatePlanoContratacao' : 'createPlanoContratacao' ?>">
                <div class="row">
                    <div class="col-sm-2 form-group tdleft">
                        <label for="mpc01_codigo"><b>Código: </b></label>
                        <?php
                            db_input(
                                'mpc01_codigo',
                                10,
                                $mpc01_codigo,
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
                    <div class="col-sm-2 form-group tdleft">
                        <label for="mpc01_ano"><b>Ano: </b></label>
                        <select name="mpc01_ano" id="mpc01_ano" class="custom-select disabled">
                            <option value="0">Selecione</option>
                        </select>
                    </div>
                    <div class="col-sm-6 form-group tdleft">
                        <label for=""><b>Unidade Compradora: </b></label>
                        <select name="mpc01_uncompradora" id="mpc01_uncompradora" class="custom-select disabled">
                            <option value="0">Selecione</option>
                        </select>
                    </div>
                    <div class="col-sm-2 tdleft">
                    <label for="mpc01_data"><b>Publicação: </b></label>
                        <div class="contain-input-data">
                        <?php
                            db_input(
                                'mpc01_data',
                                "",
                                (!empty($mpc01_data)? $mpc01_data : ''),
                                true,
                                'date',
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
                </div>
                <div class="row" style="margin-top: 2em;">
                    <div class="col-12 contain-buttons-plano">
                        <?php if(empty($mpc01_codigo)): ?>
                            <button class="btn btn-success" type="button" id="btnSalvar" disabled>Salvar</button>
                        <?php else: ?>
                            <button class="btn btn-success" type="button" id="btnPublicar" <?= !empty($mpc01_is_send_pncp) ? 'disabled' : '' ?>>Publicar</button>
                            <button class="btn btn-danger" type="button" id="btnExcluir">Excluir</button>
                        <?php endif; ?>
                    </div>
                </div>
            </fieldset>
        </form>
        <br>
        <?php if(!empty($mpc01_codigo)): ?>
            <fieldset>
                <legend><b>Demonstrativo de dados</b></legend>
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <table>
                            <tr>
                                <td width="30%" style="text-align: left;"><b>Valor estimado:</b></td>
                                <td><span id="valor_estimado" class="valor-estimado"></span></td>
                            </tr>
                        </table>
                        <table>
                            <tr>
                                <td width="30%" style="text-align: left;"><b>Qtde. de Itens:</b></td>
                                <td><span id="qtde_itens" class="qtde-itens"></span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-12 col-sm-6" style="margin-top: 10px;">
                        <div
                            id="graficoPca"
                            data-bg = "d3f0df"
                            data-border = "8cd8aa"
                        >
                            <canvas></canvas>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12" style="text-align: left; margin-bottom: 1em;">
                        <button class="btn btn-primary" type="button" id="btnDownloadArquivo">Baixar Arquivo</button>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend><b>Itens PCA</b></legend>
                <form id="frmPcaItens" method="post">
                    <div class="row" style="align-items: end;">
                        <div class="col-12 col-sm-2 tdleft">
                            <label for=""><b>Exercicio: </b></label>
                            <select name="exercicio" id="exercicio" class="custom-select">
                                <option value="">Selecione</option>
                            </select>
                        </div>

                        <div class="col-sm-2 form-group tdleft">
                            <label for="mpc02_datap"><b>Previsão: </b></label>
                            <div class="contain-input-data">
                                <?php
                                    db_input(
                                        'mpc02_datap',
                                        "",
                                        "",
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
                        <div class="col-12 col-sm-3">
                            <button class="btn btn-primary" type="button" id="btnProcessar" style="margin: 0 8px;">Processar</button>
                            <button class="btn btn-success" type="button" id="btnNovoItem" style="margin: 0 8px;">Novo Item</button>
                        </div>
                        <div class="col-12 col-sm-5">
                            <fieldset>
                                <legend>Legenda</legend>
                                <div class="row">
                                    <div class="col-12 col-sm-4" style="background: #fff; font-weight: bold; padding: 15px;">
                                        Itens sem PCA
                                    </div>
                                    <div class="col-12 col-sm-4" style="background: #D1F07C; padding: 15px; font-weight: bold;">
                                        Itens vinculados ao PCA
                                    </div>
                                    <div class="col-12 col-sm-4" style="background: #4D4DFF; padding: 15px; font-weight: bold;">
                                        Itens publicados PNCP
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <div class="row" style="margin-top: 10px; margin-bottom: 20px;">
                    <div class="col-12" id="containTabelaItensPlanoContratacao">
                    </div>
                </div>
                <div class="row" style="margin-bottom: 1em;">
                    <div class="col-12 contain-buttons-plano">
                        <button class="btn btn-success" type="button" id="btnSalvarItem">Salvar Itens</button>
                        <button class="btn btn-danger" type="button" id="btnExcluirItem">Remover Itens</button>
                        <button class="btn btn-warning " type="button" id="btnRetificar">Retificar Itens</button>
                    </div>
                </div>
            </fieldset>
        <?php endif; ?>
    </body>
</html>
<script>
    const mpc01_codigo = '<?= $mpc01_codigo ?>';
    const mpc01_ano = '<?= $mpc01_ano ?>';
    const mpc01_uncompradora = '<?= $mpc01_uncompradora ?>';
    const btnSalvar = document.getElementById('btnSalvar');
    const btnPublicar = document.getElementById('btnPublicar');
    const btnExcluir = document.getElementById('btnExcluir');
    const btnRetificar = document.getElementById('btnRetificar');
    const btnNovoItem = document.getElementById('btnNovoItem');
    const btnProcessar = document.getElementById('btnProcessar');
    const btnSalvarItem = document.getElementById('btnSalvarItem');
    const btnExcluirItem = document.getElementById('btnExcluirItem');
    const btnDownloadArquivo = document.getElementById('btnDownloadArquivo');
    const charts = document.getElementById('graficoPca');
    const formatter = (new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 4, maximumFractionDigits: 4 }));
    const eventoClick = new Event('click');
    const oGridPlanoContratacaoItem = new DBGrid('containTabelaItensPlanoContratacao');
    const url = 'com_planodecontratacao.RPC.php';
    const colorEmptyChange = '#fff';
    const colorSendPncp = '#4D4DFF';
    const colorChangePncp = '#D1F07C';
    let itensChange = [];
    let dataChat = null;
    let limit = 15;
    let paginaAtual = 1;

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
        let selectAno = document.getElementById('mpc01_ano');
        let selectExercicio = document.getElementById('exercicio');

        let anos = oRetorno.anos;
        const anoAtual = new Date().getFullYear();
        Object.entries(anos).forEach(([oAno, iSeq]) => {
            const option = document.createElement('option');
            option.value = iSeq;
            option.text = oAno;

            if(iSeq == mpc01_ano){
                option.selected = true;
            }
            selectAno.appendChild(option);

            if(selectExercicio != null && oAno <= anoAtual){
                const optionExercicio = document.createElement('option');
                optionExercicio.value = iSeq;
                optionExercicio.text = oAno;

                selectExercicio.appendChild(optionExercicio);
            }

            checkButtonSalvar();
        })
    }

    // ****************************** UNIDADE COMPRADORA ****************************
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

        let selectUnidadeCompradora = document.getElementById('mpc01_uncompradora');

        let unidades = oRetorno.unidadeCompradora;
        unidades.forEach((oValue, iSeq) => {
            const option = document.createElement('option');
            option.value = oValue.codigo;
            option.text = oValue.nomeinst;

            if(mpc01_uncompradora == oValue.codigo){
                option.selected = true;
            }

            selectUnidadeCompradora.appendChild(option);
        });
        checkButtonSalvar();
    }

    // ****************************** DADOS PCA ****************************
    if(btnSalvar != null){
        btnSalvar.addEventListener('click', function(e){
            e.preventDefault();
            const formData = serializarFormulario(document.getElementById('frmPca'));
            let oAjax = new Ajax.Request(
                url,
                {
                    method: 'post',
                    parameters: 'json=' + formData,
                    onComplete: (oAjax) => {
                        let oRetorno = JSON.parse(oAjax.responseText);
                        let currentUrl = new URL(window.location.href);

                        parent.loadingPlanoContratacoes();
                        currentUrl.searchParams.set('mpc01_codigo', oRetorno.planoContratacao.mpc01_codigo);
                        currentUrl.searchParams.set('mpc01_ano', oRetorno.planoContratacao.mpc01_ano);
                        currentUrl.searchParams.set('mpc01_uncompradora', oRetorno.planoContratacao.mpc01_uncompradora);
                        currentUrl.searchParams.set('mpc01_data', oRetorno.planoContratacao.mpc01_data);
                        currentUrl.searchParams.set('mpc01_usuario', oRetorno.planoContratacao.mpc01_usuario);
                        window.location.href = currentUrl.toString();
                    }
                }
            );
        });
    }

    if(btnPublicar != null){
        btnPublicar.addEventListener("click", function(e){
            e.preventDefault();
            let oParam = {};
            oParam.exec = "publicarPlanoContratacao";
            oParam.mpc01_codigo = mpc01_codigo;

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
                    parameters: 'json=' + Object.toJSON(oParam),
                    onComplete: (oAjax) => {
                        let oRetorno = JSON.parse(oAjax.responseText);
                        if(oRetorno.status == 201 || oRetorno.status == 200){
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso!',
                                text: oRetorno.message,
                            });
                            btnPublicar.disabled = true;
                            btnExcluir.disabled = false;
                            btnDownloadArquivo.disabled = false;
                            loadingPlanoContratacaosItens();
                            parent.loadingPlanoContratacoes();
                            checkPlano();
                            return false;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            html: oRetorno.message,
                        });
                    }
                }
            );
        });
    }

    if(btnExcluir != null){
        btnExcluir.addEventListener("click", function(e){
            e.preventDefault();
            Swal.fire({
                title: 'O plano de contratação será removido do PNCP, deseja continuar?',
                text: "Essa ação não pode ser desfeita!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar',
                input: 'textarea',
                inputLabel: 'Justificativa',
                inputPlaceholder: 'Digite a justificativa...',
                inputAttributes: {
                    'aria-label': 'Digite sua justificativa'
                },
                inputValidator: (value) => {
                    if (!value) {
                        return 'O texto não pode estar vazio!';
                    } else if (value.length > 255) {
                        return 'O texto não pode exceder 255 caracteres!';
                    }
                },
                inputAttributes: {
                    'aria-label': 'Digite sua justificativa'
                },
                didOpen: () => {
                    const textarea = Swal.getInput();
                    // Cria um elemento para exibir a contagem de caracteres
                    const charCountDisplay = document.createElement('div');

                    charCountDisplay.style.marginTop = '0.5em';
                    charCountDisplay.style.marginLeft = '3em';
                    charCountDisplay.style.marginRight = '3em';
                    charCountDisplay.style.marginBottom = '3 px';
                    charCountDisplay.style.fontSize = '12px';
                    charCountDisplay.style.color = '#666';
                    charCountDisplay.style.textAlign = 'right';
                    charCountDisplay.textContent = `Caracteres Digitados: ${textarea.value.length} - Limite 255`;

                    // Adiciona o contador logo após o textarea
                    textarea.insertAdjacentElement('afterend',charCountDisplay);

                    // Atualiza a contagem de caracteres em tempo real
                    const updateCharCount = () => {
                        charCountDisplay.textContent = `Caracteres Digitados: ${textarea.value.length} - Limite 255`;
                    };

                    textarea.addEventListener('input', updateCharCount);
                },
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
                    oParam.exec = 'RemovePlanoContratacaoPncp';
                    oParam.mpc01_codigo = mpc01_codigo;
                    oParam.justificativa = result.value;

                    new Ajax.Request(
                        url,
                        {
                            method: 'post',
                            parameters: 'json=' + Object.toJSON(oParam),
                            onComplete: (oAjax) => {
                                let oRetorno = JSON.parse(oAjax.responseText);
                                if(oRetorno.status == 200 || oRetorno.status == 201){
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Sucesso!',
                                        text: oRetorno.message,
                                    });
                                    btnPublicar.disabled = false;
                                    btnExcluir.disabled = true;
                                    btnDownloadArquivo.disabled = true;
                                    loadingPlanoContratacaosItens();
                                    parent.loadingPlanoContratacoes();
                                    checkPlano();
                                    return false;
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro!',
                                    text: oRetorno.message
                                });
                            }
                        }
                    );
                }
            });
        });
    }

    if(btnNovoItem != null){
        btnNovoItem.addEventListener('click', function(e){
            e.preventDefault();
            let currentUrl = new URL('com_planodecontratacao003.php', window.location.href);
            currentUrl.searchParams.set('mpc01_codigo', mpc01_codigo);
            currentUrl.searchParams.set('mpc01_uncompradora', mpc01_uncompradora);

            if(typeof db_iframe_plano_item_plano_contratacao != 'undefined'){
                window.scroll({
                    top: 0,
                    left: 0,
                    behavior: 'smooth' // Opções: 'auto' ou 'smooth'
                })
                db_iframe_plano_item_plano_contratacao.jan.location.href = currentUrl.toString();
                db_iframe_plano_item_plano_contratacao.show();
                return false;
            }

            let frame = js_OpenJanelaIframe(
                '',
                'db_iframe_plano_item_plano_contratacao',
                currentUrl.toString(),
                'Plano de Contratação Item > Inclusão',
                false
            );

            if(frame){
                frame.setAltura("100%");
                frame.setLargura("100%");
                frame.hide();
                btnNovoItem.dispatchEvent(eventoClick);
            }
        });
    }

    if(btnProcessar != null){
        btnProcessar.addEventListener('click', function(e){
            e.preventDefault();
            loadingPlanoContratacaosItens();
        });
    }

    if(btnSalvarItem != null){
        btnSalvarItem.addEventListener('click', async function(e){
            e.preventDefault();
            if(typeof document.getElementById('mpc02_datap') == 'undefined' || document.getElementById('mpc02_datap').value.length <= 0){
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'Para salvar os itens é necessário informar a previsão.',
                });
                return false;
            }

            // let itensChange = getItensChange();
            if(itensChange.length <= 0){
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'Nenhum item selecionado.',
                });
                return false;
            }

            if(!isValidDate(document.getElementById('mpc02_datap').value)){
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'Informe uma data válida',
                });
                document.getElementById('mpc02_datap').value = '';
                return false;
            }

            let oParam = {};
            oParam.exec = 'createPlanoContratacaoPcPcItem';
            oParam.mpc01_codigo = mpc01_codigo;
            oParam.itens = itensChange;
            oParam.mpc02_datap = document.getElementById('mpc02_datap').value;

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
                        if(oRetorno.planoContratacaoPcPcItem != null && oRetorno.planoContratacaoPcPcItem.length > 0){
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso!',
                                text: 'Item(s) salvo(s) com sucesso!',
                            });
                            // document.getElementById('exercicio').selectedIndex = 0;
                            // document.getElementById('mpc02_datap').value = '';
                            itensChange = [];
                            loadingPlanoContratacaosItens(paginaAtual);
                            return false;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: 'Ops, ocorreu um erro ao salvar os itens por favor tente novamente!',
                        });
                    }
                }
            );
        });
    }

    if(btnRetificar != null){
        btnRetificar.addEventListener("click", function(e){
            e.preventDefault();
            // let itensChange = getItensChange();
            if(itensChange.length <= 0){
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'Nenhum item selecionado.',
                });
                return false;
            }

            Swal.fire({
                title: 'Deseja realmente realizar a retificação dos itens?',
                input: 'textarea',
                inputLabel: 'Justificativa',
                inputPlaceholder: 'Digite a justificativa...',
                inputValidator: (value) => {
                    if (!value) {
                        return 'O texto não pode estar vazio!';
                    } else if (value.length > 255) {
                        return 'O texto não pode exceder 255 caracteres!';
                    }
                },
                inputAttributes: {
                    'aria-label': 'Digite sua justificativa'
                },
                didOpen: () => {
                    const textarea = Swal.getInput();
                    // Cria um elemento para exibir a contagem de caracteres
                    const charCountDisplay = document.createElement('div');

                    charCountDisplay.style.marginTop = '0.5em';
                    charCountDisplay.style.marginLeft = '3em';
                    charCountDisplay.style.marginRight = '3em';
                    charCountDisplay.style.marginBottom = '3 px';
                    charCountDisplay.style.fontSize = '12px';
                    charCountDisplay.style.color = '#666';
                    charCountDisplay.style.textAlign = 'right';
                    charCountDisplay.textContent = `Caracteres Digitados: ${textarea.value.length} - Limite 255`;

                    // Adiciona o contador logo após o textarea
                    textarea.insertAdjacentElement('afterend',charCountDisplay);

                    // Atualiza a contagem de caracteres em tempo real
                    const updateCharCount = () => {
                        charCountDisplay.textContent = `Caracteres Digitados: ${textarea.value.length} - Limite 255`;
                    };

                    textarea.addEventListener('input', updateCharCount);
                },
                showCancelButton: true,
                confirmButtonText: 'Enviar',
                cancelButtonText: 'Cancelar',
                preConfirm: (inputValue) => {
                    if (!inputValue) {
                        Swal.showValidationMessage('A justificativa é obrigatória.');
                    }
                    return inputValue;
                }
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
                        oParam.exec = 'retificarPlanoItemContratacao';
                        oParam.mpc01_codigo = mpc01_codigo;
                        oParam.justificativa = result.value;
                        oParam.itens = itensChange;

                        let oAjax = new Ajax.Request(
                            url,
                            {
                                method: 'post',
                                parameters: 'json=' + Object.toJSON(oParam),
                                onComplete: (oAjax) => {
                                    let oRetorno = JSON.parse(oAjax.responseText);
                                    if(oRetorno.status == 201 || oRetorno.status == 200){
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Sucesso!',
                                            text: oRetorno.message,
                                        });

                                        itensChange = [];
                                        loadingPlanoContratacaosItens(paginaAtual);
                                        return false;
                                    }

                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Erro!',
                                        html: oRetorno.message,
                                    });
                                }
                            }
                        );
                    }
                });
        });
    }

    if(btnExcluirItem != null){
        btnExcluirItem.addEventListener('click', function(e){
            e.preventDefault();
            // let itens = getItensChange();
            if(itensChange.length <= 0){
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'Nenhum item selecionado.',
                });
                return false;
            }

            let bIsSendPcnp = itensChange.some(function(item){
                const { mpcpc01_is_send_pncp } = item
                return mpcpc01_is_send_pncp == 1;
            });

            let json = {};
            if(bIsSendPcnp){
                json = {
                    title: 'O item também será removido do PNCP, deseja continuar?',
                    text: "Essa ação não pode ser desfeita!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar',
                    input: 'textarea',
                    inputLabel: 'Justificativa',
                    inputPlaceholder: 'Digite a justificativa...',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'O texto não pode estar vazio!';
                        } else if (value.length > 255) {
                            return 'O texto não pode exceder 255 caracteres!';
                        }
                    },
                    inputAttributes: {
                        'aria-label': 'Digite sua justificativa'
                    },
                    didOpen: () => {
                        const textarea = Swal.getInput();
                        // Cria um elemento para exibir a contagem de caracteres
                        const charCountDisplay = document.createElement('div');

                        charCountDisplay.style.marginTop = '0.5em';
                        charCountDisplay.style.marginLeft = '3em';
                        charCountDisplay.style.marginRight = '3em';
                        charCountDisplay.style.marginBottom = '3 px';
                        charCountDisplay.style.fontSize = '12px';
                        charCountDisplay.style.color = '#666';
                        charCountDisplay.style.textAlign = 'right';
                        charCountDisplay.textContent = `Caracteres Digitados: ${textarea.value.length} - Limite 255`;

                        // Adiciona o contador logo após o textarea
                        textarea.insertAdjacentElement('afterend',charCountDisplay);

                        // Atualiza a contagem de caracteres em tempo real
                        const updateCharCount = () => {
                            charCountDisplay.textContent = `Caracteres Digitados: ${textarea.value.length} - Limite 255`;
                        };

                        textarea.addEventListener('input', updateCharCount);
                    }
                };
            } else {
                json = {
                    title: 'Você tem certeza que deseja excluir o(s) item(s)?',
                    text: "Essa ação não pode ser desfeita!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar',
                }
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
                    oParam.exec = 'removePlanoContratacaoPcPcItem';
                    oParam.mpc01_codigo = mpc01_codigo;
                    oParam.itens = itensChange;
                    oParam.justificativa = result.value;

                    new Ajax.Request(
                        url,
                        {
                            method: 'post',
                            parameters: 'json=' + Object.toJSON(oParam),
                            onComplete: (oAjax) => {
                                let oRetorno = JSON.parse(oAjax.responseText);
                                if(oRetorno.message != null && oRetorno.message.length > 0){
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Sucesso!',
                                        text: 'Item(s) removido(s) com sucesso!',
                                    });
                                    itensChange = [];
                                    loadingPlanoContratacaosItens();
                                    return false;
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro!',
                                    text: 'Ops, ocorreu um erro ao remover os itens por favor tente novamente!',
                                });
                            }
                        }
                    );
                }
            });
        });
    }

    if(btnDownloadArquivo != null){
        btnDownloadArquivo.addEventListener('click', function(e){
            e.preventDefault();
            Swal.fire({
                title: 'Aguarde...',
                text: 'Estamos processando sua solicitação.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            let oParam = {};
            oParam.exec = 'downloadArquivosPlanoContratacao';
            oParam.mpc01_codigo = mpc01_codigo;
            new Ajax.Request(
                url,
                {
                    method: 'post',
                    parameters: 'json=' + Object.toJSON(oParam),
                    onComplete: (oAjax) => {
                        let oRetorno = JSON.parse(oAjax.responseText);
                        if(oRetorno.arquivo && oRetorno.arquivo.length > 0){
                            const csvContent = oRetorno.arquivo;
                            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                            const url = URL.createObjectURL(blob);

                            // Cria um link de download e simula um clique
                            const link = document.createElement("a");
                            link.href = url;
                            link.download = oRetorno.name;
                            link.style.visibility = "hidden";
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);

                            // Revoga o URL para liberar memória
                            URL.revokeObjectURL(url);

                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso!',
                                text: 'Download realizado com sucesso!',
                            });
                            return false;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: 'Ops, ocorreu um erro ao realizar o download por favor tente novamente!',
                        });
                    }
                }
            );
        })
    }

    // ****************************** TABELA ****************************´

    function js_gridPlanoContratacoesItens(){
        oGridPlanoContratacaoItem.nameInstance = 'oGridPlanoContratacaoItem';
        oGridPlanoContratacaoItem.setCheckbox(0);
        oGridPlanoContratacaoItem.setCellAlign([
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

        oGridPlanoContratacaoItem.setCellWidth([
            '1%',
            '10%',
            '35%',
            '18%',
            '6%',
            '10%',
            '9%',
            '8%',
            '1%',
            '1%'
        ]);

        oGridPlanoContratacaoItem.setHeader([
            'checkbox',
            'Código',
            'Descrição',
            'Unidade',
            'Qtd.',
            'Vlr. Unitário',
            'Vlr Total',
            'Opção',
            'lineColor',
            'json'
        ]);

        oGridPlanoContratacaoItem.allowSelectColumns(true);
        oGridPlanoContratacaoItem.setHeight(250);
        oGridPlanoContratacaoItem.aHeaders[1].lDisplayed = false;
        oGridPlanoContratacaoItem.aHeaders[9].lDisplayed = false;
        oGridPlanoContratacaoItem.aHeaders[10].lDisplayed = false;
        oGridPlanoContratacaoItem.show(document.getElementById('containTabelaItensPlanoContratacao'));
        oGridPlanoContratacaoItem.clearAll(true);
        oGridPlanoContratacaoItem.inicializaTooltip();

        oGridPlanoContratacaoItem.selectSingle = function(oCheckbox, sRow, oRow){
            getItensChange(oRow);
        }

        oGridPlanoContratacaoItem.paginatorInitialize((pageNumber) => {
            loadingPlanoContratacaosItens(pageNumber, false);
        });
    }

    function loadingPlanoContratacaosItens(offset = 1, reloadGrafico = true){
        let oParam = {};
        oParam.exec = 'listagemPlanoContratacaoItens';
        oParam.exercicio = document.getElementById('exercicio').value;
        oParam.mpc01_codigo = mpc01_codigo;
        oParam.offset = offset;
        oParam.limit = limit;
        oGridPlanoContratacaoItem.showLoading();
        paginaAtual = offset;
        new Ajax.Request(
            url,
            {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: function(oAjax){
                    let oRetorno = JSON.parse(oAjax.responseText)
                    oGridPlanoContratacaoItem.clearAll(true);
                    if((oRetorno.planoContratacaoItens == null || oRetorno.planoContratacaoItens.length <= 0) && document.getElementById('exercicio').value){
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção!',
                            text: 'Ops, não existem itens para o exercicio selecionado!',
                        });
                        oGridPlanoContratacaoItem.hideLoading();
                        return false;
                    }
                    oRetorno.planoContratacaoItens.forEach(function(oValue, iSeq){
                        let aLinha = new Array();
                        aLinha[0] = iSeq;
                        aLinha[1] = oValue.mpc02_codmater || '-';
                        aLinha[2] = `<div class="tooltip-target" data-tooltip="${oValue.pc01_descrmater}">${oValue.pc01_descrmater}</div>`;
                        aLinha[3] = oValue.m61_descr;
                        aLinha[4] = parseInt(oValue.mpcpc01_qtdd || oValue.mpc02_qtdd);
                        aLinha[5] = formatter.format(oValue.mpcpc01_vlrunit || oValue.mpc02_vlrunit);
                        aLinha[6] = formatter.format(oValue.mpcpc01_vlrtotal || oValue.mpc02_vlrtotal);
                        aLinha[7] = (oValue.mpc02_codigo != null && oValue.mpcpc01_codigo != null) ? `
                            <a
                                href="#"
                                style="margin: 0 5px; color: #000; font-weigth: bold;"
                                onclick="openEdicao(
                                    event,
                                    ${oValue.mpc02_codigo},
                                    ${mpc01_codigo}
                                )"
                            >A</a>
                            <a
                                href="#"
                                style="margin: 0 5px; color: #000; font-weigth: bold;"
                                onclick="openExclusao(
                                    event,
                                    ${oValue.mpc02_codigo},
                                    '${oValue.pc01_descrmater}',
                                    ${oValue.mpcpc01_is_send_pncp || 0},
                                    ${oValue.mpcpc01_codigo}
                                )"
                            >E</a>
                        ` : '';

                        let corLinha = colorEmptyChange;
                        if(oValue.mpcpc01_codigo != null){
                            corLinha = colorChangePncp;
                            if(oValue.mpcpc01_is_send_pncp === 1){
                                corLinha = colorSendPncp;
                            }
                        }

                        aLinha[8] = corLinha;
                        aLinha[9] = JSON.stringify(oValue);
                        oGridPlanoContratacaoItem.addRow(aLinha);
                    });

                    oGridPlanoContratacaoItem.renderRows();
                    oGridPlanoContratacaoItem.aRows.each(function(aRow, iIndice){
                        const corLinha = aRow.aCells[9].getValue();
                        const id = aRow.sId;
                        const json = JSON.parse(aRow.aCells[10].getValue());

                        document.getElementById(id).style.backgroundColor = corLinha;
                        const index = itensChange.findIndex(
                            item => (
                                item.mpc02_codmater === json.mpc02_codmater
                                && item.mpc02_un === json.mpc02_un
                            ));

                        if(index !== -1){
                            document.getElementById(id).querySelector('input[type=checkbox]').checked = true;
                        }
                    });

                    js_removeObj('msgbox');
                    let totalPages = (oRetorno.total) ? Math.ceil(oRetorno.total / limit) : 0;
                    oGridPlanoContratacaoItem.renderPagination(totalPages, offset);

                    if(reloadGrafico){
                        loadingDadosValores();
                        loadingDadosGrafico();
                    }
                    oGridPlanoContratacaoItem.hideLoading();
                }
            }
        )
    }

    function openEdicao(event, mpc02_codigo, mpc01_codigo){
        event.preventDefault();
        let currentUrl = new URL('com_planodecontratacao003.php', window.location.href);
        currentUrl.searchParams.set('mpc02_codigo', mpc02_codigo);
        currentUrl.searchParams.set('mpc01_codigo', mpc01_codigo);
        currentUrl.searchParams.set('mpc01_uncompradora', mpc01_uncompradora);

        if(typeof db_iframe_plano_item_plano_contratacao_edit != 'undefined'){
            window.scroll({
                top: 0,
                left: 0,
                behavior: 'smooth' // Opções: 'auto' ou 'smooth'
            });
            db_iframe_plano_item_plano_contratacao_edit.jan.location.href = currentUrl.toString();
            db_iframe_plano_item_plano_contratacao_edit.show();
            return false;
        }

        let frame = js_OpenJanelaIframe(
            '',
            'db_iframe_plano_item_plano_contratacao_edit',
            currentUrl.toString(),
            'Atualização Item PCA',
            false
        );

        if(frame){
            frame.setAltura("100%");
            frame.setLargura("100%");
            frame.hide();
            event.target.dispatchEvent(eventoClick);
        }
    }

    function openExclusao(event, mpc02_codigo, pc01_descrmater, mpcpc01_is_send_pncp, mpcpc01_codigo){
        let json = {};
        if(mpcpc01_is_send_pncp == 1){
            json = {
                title: 'O item também será removido do PNCP, deseja continuar?',
                text: "Essa ação não pode ser desfeita!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar',
                input: 'textarea',
                inputLabel: 'Justificativa',
                inputPlaceholder: 'Digite a justificativa...',
                inputValidator: (value) => {
                    if (!value) {
                        return 'O texto não pode estar vazio!';
                    } else if (value.length > 255) {
                        return 'O texto não pode exceder 250 caracteres!';
                    }
                },
                inputAttributes: {
                    'aria-label': 'Digite sua justificativa'
                },
                didOpen: () => {
                    const textarea = Swal.getInput();
                    const charCountDisplay = document.createElement('div');
                    charCountDisplay.style.marginTop = '10px';
                    charCountDisplay.style.fontSize = '12px';
                    charCountDisplay.style.color = '#666';
                    textarea.parentNode.appendChild(charCountDisplay);

                    const updateCharCount = () => {
                        const charCount = textarea.value.length;
                        charCountDisplay.textContent = `Caracteres Digitados: ${charCount} - Limite 255`;
                    };

                    textarea.addEventListener('input', updateCharCount);
                    updateCharCount(); // Inicializa a contagem de caracteres
                }
            };
        } else {
            json = {
                title: 'Deseja remover o item?',
                text: "Essa ação não pode ser desfeita!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar',
            }
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
                oParam.exec = 'removeItemPlanoContratacao';
                oParam.mpc01_codigo = mpc01_codigo;
                oParam.mpc02_codigo = mpc02_codigo;
                oParam.mpcpc01_codigo = mpcpc01_codigo;
                oParam.justificativa = result.value;
                new Ajax.Request(
                    url,
                    {
                        method: 'post',
                        parameters: 'json=' + Object.toJSON(oParam),
                        onComplete: (oAjax) => {
                            let oRetorno = JSON.parse(oAjax.responseText);
                            if(oRetorno.message != null && oRetorno.message.length > 0){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sucesso!',
                                    text: 'Item removido com sucesso!',
                                });
                                loadingPlanoContratacaosItens();
                                return false;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: 'Ops, ocorreu um erro ao remover o item por favor tente novamente!',
                            });
                        }
                    }
                );
            }
        });
    }

    function closePlanoContratacaoItem(refresh = false){
        if(typeof db_iframe_plano_item_plano_contratacao != 'undefined'){
            db_iframe_plano_item_plano_contratacao.hide();
            window.scroll({
                top: document.body.scrollHeight,
                left: 0,
                behavior: 'smooth' // Opções: 'auto' ou 'smooth'
            });
        }

        if(typeof db_iframe_plano_item_plano_contratacao_edit != 'undefined'){
            db_iframe_plano_item_plano_contratacao_edit.hide();
            window.scroll({
                top: document.body.scrollHeight,
                left: 0,
                behavior: 'smooth' // Opções: 'auto' ou 'smooth'
            });
        }

        if(typeof db_iframe_plano_item_plano_contratacao_remove != 'undefined'){
            db_iframe_plano_item_plano_contratacao_remove.hide();
        }

        if(typeof db_iframe_plano_contratacao_item_remove != 'undefined'){
            db_iframe_plano_contratacao_item_remove.hide();
            window.scroll({
                top: document.body.scrollHeight,
                left: 0,
                behavior: 'smooth' // Opções: 'auto' ou 'smooth'
            });
        }

        if(typeof db_iframe_plano_contratacao_item_retificacao != 'undefined'){
            db_iframe_plano_contratacao_item_retificacao.hide();
            window.scroll({
                top: document.body.scrollHeight,
                left: 0,
                behavior: 'smooth' // Opções: 'auto' ou 'smooth'
            });
        }

        if(refresh){
            loadingPlanoContratacaosItens();
        }
    }

    function loadingDadosGrafico(){
        let oParam = {};
        oParam.exec = 'getDadosGraficoPlanoContratacao';
        oParam.mpc01_codigo = mpc01_codigo;
        let oAjax = new Ajax.Request(
            url,
            {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: async function(oAjax){
                    let oRetorno = JSON.parse(oAjax.responseText);
                    let data = [];
                    let label = [];
                    if(oRetorno.data != null){
                        Object.entries(oRetorno.data).forEach(([key, value]) => {
                            data.push(value);
                            label.push(key);
                        });
                        loadChart(data, label);
                        return false;
                    }
                    loadChart(data, label);
                }
            }
        );
    }

    function loadingDadosValores(){
        let oParam = {};
        oParam.exec = 'getDadosValoresPlanoContratacao';
        oParam.mpc01_codigo = mpc01_codigo;
        let oAjax = new Ajax.Request(
            url,
            {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: async function(oAjax){
                    let oRetorno = JSON.parse(oAjax.responseText);
                    if(!oRetorno.valores){
                        document.getElementById('valor_estimado').textContent = 'R$ ' + formatter.format(0);
                        document.getElementById('qtde_itens').textContent = 0;
                        return false;
                    }
                    document.getElementById('valor_estimado').textContent = 'R$ ' + formatter.format(oRetorno.valores.vlrestimado);
                    document.getElementById('qtde_itens').textContent = oRetorno.valores.qtdeitens;
                }
            }
        );
    }

    function loadChart(data, labels){
        if (dataChat) {
            dataChat.destroy();
        }

        let canvas = charts.querySelector('canvas');
        let ctx = canvas.getContext('2d');

        let pieChartData = {
            labels : labels,
            datasets : [
                {
                    backgroundColor: generateColors(data.length),
                    data: data,
                }
            ]
        }

        dataChat = new Chart(ctx, {
            type: "pie",
            data: pieChartData,
            options: {
                legend: {
                    position: 'right',
                    display: true,
                }
            }
        });
    }

    function getItensChange(oRow = null){
        oGridPlanoContratacaoItem.aRows.each(function(aRow, iIndice){
            let checked = aRow.aCells[0].getValue();
            let id = aRow.sId;
            const json = JSON.parse(aRow.aCells[10].getValue());

            delete json.m61_descr;
            delete json.mpc03_pcdesc;
            delete json.mpc04_pcdesc;
            delete json.nomeinst;
            delete json.pc01_descrmater;
            delete json.pc04_descrsubgrupo;
            delete json.mpc05_pcdesc;

            const index = itensChange.findIndex(
                item => (
                    item.mpc02_codmater === json.mpc02_codmater
                    && item.mpc02_un === json.mpc02_un
                ))

            if(checked){
                if(index === -1){
                    itensChange.push(json);
                }
            } else {
                if(index !== -1){
                    itensChange.splice(index, 1);
                }
            }
        });
        // return itensChange;
    }

    function checkPlano(){
        let oParam = {};
        oParam.exec = 'getPlanoDeContratacao';
        oParam.mpc01_codigo = mpc01_codigo;
        let oAjax = new Ajax.Request(
            url,
            {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: async function(oAjax){
                    let oRetorno = JSON.parse(oAjax.responseText);
                    if(oRetorno.planoContratacao){
                        document.getElementById('mpc01_codigo').value = oRetorno.planoContratacao.mpc01_codigo || '';
                        document.getElementById('mpc01_ano').value = oRetorno.planoContratacao.mpc01_ano || '';
                        document.getElementById('mpc01_uncompradora').value = oRetorno.planoContratacao.mpc01_uncompradora || '';
                        document.getElementById('mpc01_data').value = oRetorno.planoContratacao.mpc01_data || '';
                    }
                    if(oRetorno.planoContratacao && oRetorno.planoContratacao.mpc01_is_send_pncp != 1){
                        btnPublicar.disabled = false;
                        btnExcluir.disabled = true;
                        btnDownloadArquivo.disabled = true;
                        return false;
                    }

                    btnPublicar.disabled = true;
                    btnExcluir.disabled = false;
                    btnDownloadArquivo.disabled = false;
                }
            }
        );
    }

    function checkButtonSalvar(){
        if(document.getElementById('mpc01_ano').value > 0 && document.getElementById('mpc01_uncompradora').value > 0 && btnSalvar != null){
            btnSalvar.disabled = false;
        } else if(btnSalvar != null){
            btnSalvar.disabled = true;
        }
    }

    function isValidDate(dateString) {
      if (!dateString) {
        return false;
      }

      const regex = /^\d{4}-\d{2}-\d{2}$/;
      if (!regex.test(dateString)) {
        return false;
      }

      const date = new Date(dateString);

      if (date.toString() === 'Invalid Date') {
        return false;
      }

      const [year, month, day] = dateString.split('-');
      if (date.getUTCFullYear() !== parseInt(year) ||
          date.getUTCMonth() + 1 !== parseInt(month) ||
          date.getUTCDate() !== parseInt(day)) {
        return false;
      }

      if (year < 1900 || year > 2099) {
        return false;
      }

      return true;
    }

    if(mpc01_codigo && mpc01_codigo.length > 0){
        js_gridPlanoContratacoesItens();
        loadingPlanoContratacaosItens();
        // loadingDadosGrafico();
        checkPlano();
    }

    js_getAnos();
    js_getUnidadeCompradoras();
</script>
