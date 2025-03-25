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
<link href="libs/renderComponents/src/components/icons/asterisk/asterisk.css" rel='stylesheet' type='text/css'>
<link href="libs/renderComponents/src/components/icons/exclamation-triangle/exclamation-triangle.css" rel='stylesheet' type='text/css'>
<link href="libs/renderComponents/src/components/icons/exclamation-circle/exclamation-circle.css" rel='stylesheet' type='text/css'>

<form action="" method="post" name="form1" id="frmPublicacao" style="display: none;">
    <input type="hidden" name="exec" value="atualizarPublicacao">
    <input type="hidden" name="l20_codigo" value="<?= $l20_codigo ?>">
    <fieldset style="margin-bottom: 1.5em;">
        <legend>Publicação</legend>
        <div class="row">
            <div class="col-12 col-sm-3 form-group mb-2">
                <label for="l20_dtpulicacaoedital">Data Publicação Edital:</label>
                <?php
                    db_input(
                        'l20_dtpulicacaoedital',
                        4,
                        '',
                        true,
                        'date',
                        1,
                        "",
                        '',
                        '',
                        '',
                        '',
                        'form-control',
                        [
                            'validate-required' => 'false',
                            'validate-date' => 'true',
                            'validate-date-message' => 'Informe uma data válida'
                        ]
                    );
                ?>
            </div>
            <div class="col-12 col-sm-6 form-group mb-2">
                <label for="l20_linkedital">Link de Publicação Edital:</label>
                <?php
                    db_input(
                        'l20_linkedital',
                        4,
                        '',
                        true,
                        'text',
                        1,
                        "",
                        '',
                        '',
                        '',
                        '',
                        'form-control',
                        [
                            'validate-required'         => 'false',
                            'validate-required-message' => 'O campo Link de Publicação Edital não foi informado'
                        ]
                    );
                ?>
            </div>
            <div class="col-12 col-sm-3 form-group mb-2">
                <label for="l20_diariooficialdivulgacao">Diário Oficial da Divulgação:</label>
                <select
                    name="l20_diariooficialdivulgacao"
                    id="l20_diariooficialdivulgacao"
                    class="custom-select"
                    data-validate-required="false"
                    data-validate-required-message="O Diário Oficial da Divulgação é obrigatório"
                >
                    <option value="">Selecione</option>
                    <option value="1">Município</option>
                    <option value="2">Estado</option>
                    <option value="3">União</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-3 form-group mb-2">
                <label for="l20_dtpublic">Data Publicação DO:</label>
                <?php
                    db_input(
                        'l20_dtpublic',
                        4,
                        '',
                        true,
                        'date',
                        1,
                        "",
                        '',
                        '',
                        '',
                        '',
                        'form-control',
                        [
                            'validate-required' => 'true',
                            'validate-date' => 'true',
                            'validate-date-message' => 'Informe uma data válida',
                            'validate-required-message' => 'O campo Data Publicação DO é obrigatório'
                        ]
                    );
                ?>
            </div>
            <div class="col-12 col-sm-3 form-group mb-2">
                <label for="l20_dtpulicacaopncp">Data de Publicação PNCP:</label>
                <?php
                    db_input(
                        'l20_dtpulicacaopncp',
                        4,
                        '',
                        true,
                        'date',
                        1,
                        "",
                        '',
                        '',
                        '',
                        '',
                        'form-control',
                        [
                            'validate-required' => 'false',
                            'validate-date' => 'true',
                            'validate-date-message' => 'Informe uma data válida'
                        ]
                    );
                ?>
            </div>
            <div class="col-12 col-sm-6 form-group mb-2">
                <label for="l20_linkpncp">Link no PNCP:</label>
                <?php
                    db_input(
                        'l20_linkpncp',
                        4,
                        '',
                        true,
                        'text',
                        1,
                        "",
                        '',
                        '',
                        '',
                        '',
                        'form-control',
                        [
                            'validate-required'         => 'false',
                            'validate-required-message' => 'O campo Link no PNCP não foi informado'
                        ]
                    );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-9 form-group">
                <label for="respPubliccodigo">
                    <?php db_ancora('Resp. pela Publicação:', "pesquisaCgm(true, 'respPubliccodigo', 'respPublicnome');", 1) ?>
                </label>
                <div class="row">
                    <div class="col-12 col-sm-4 mb-2 pl-0">
                        <?php
                            db_input(
                                'respPubliccodigo',
                                10,
                                '',
                                true,
                                'text',
                                1,
                                " onchange=\"pesquisaCgm(false, 'respPubliccodigo', 'respPublicnome');\" ",
                                '',
                                '',
                                '',
                                '',
                                'form-control',
                                [
                                    'validate-maxlength'                => '10',
                                    'validate-no-special-chars'         => 'true',
                                    'validate-integer'                  => 'true',
                                    'validate-required'                 => "true",
                                    'validate-minlength'                => "1",
                                    'validate-required-message'         => "O campo Resp. pela Publicação não foi informado",
                                    'validate-minlength-message'        => "O campo Resp. pela Publicação deve ter pelo menos 1 caracteres",
                                    'validate-maxlength-message'        => 'O campo Resp. pela Publicação deve ter no máximo 10 caracteres',
                                    'validate-no-special-chars-message' => 'O campo Resp. pela Publicação não deve conter aspas simples, ponto e vírgula ou porcentagem',
                                    'validate-integer-message'          => 'O campo Resp. pela Publicação deve conter apenas numeros'
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-8 mb-2 pr-0">
                        <?php
                            db_input(
                                'respPublicnome',
                                45,
                                '',
                                true,
                                'text',
                                3,
                                '',
                                '',
                                '',
                                '',
                                '',
                                'form-control',
                                [
                                    'validate-required'          => "true",
                                    'validate-minlength'         => "1",
                                    'validate-required-message'  => "O campo Responsável pela autorização para abertura do procedimento de dispensa ou inexigibilidade não foi informado",
                                    'validate-minlength-message' => "O código deve ter pelo menos 1 caracteres",
                                ]
                            );
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-4 form-group mb-2">
                <label for="l20_datapublicacao1">Publicação Veículo 1:</label>
                <?php
                    db_input(
                        'l20_datapublicacao1',
                        4,
                        '',
                        true,
                        'date',
                        1,
                        "",
                        '',
                        '',
                        '',
                        '',
                        'form-control',
                        [
                            'validate-required' => 'false',
                            'validate-date' => 'true',
                            'validate-date-message' => 'Informe uma data válida'
                        ]
                    );
                ?>
            </div>
            <div class="col-12 col-sm-8 form-group mb-2">
                <label for="l20_nomeveiculo1">Veículo Divulgação 1:</label>
                <?php
                    db_input(
                        'l20_nomeveiculo1',
                        4,
                        '',
                        true,
                        'text',
                        1,
                        "",
                        '',
                        '',
                        '',
                        50,
                        'form-control',
                        [
                            'validate-required'         => 'false',
                            'validate-required-message' => 'O campo Veículo Divulgação 1 não foi informado'
                        ]
                    );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-4 form-group mb-2">
                <label for="l20_datapublicacao2">Publicação Veículo 2:</label>
                <?php
                    db_input(
                        'l20_datapublicacao2',
                        4,
                        '',
                        true,
                        'date',
                        1,
                        "",
                        '',
                        '',
                        '',
                        50,
                        'form-control',
                        [
                            'validate-required' => 'false',
                            'validate-date' => 'true',
                            'validate-date-message' => 'Informe uma data válida'
                        ]
                    );
                ?>
            </div>
            <div class="col-12 col-sm-8 form-group mb-2">
                <label for="l20_nomeveiculo2">Veículo Divulgação 2:</label>
                <?php
                    db_input(
                        'l20_nomeveiculo2',
                        4,
                        '',
                        true,
                        'text',
                        1,
                        "",
                        '',
                        '',
                        '',
                        50,
                        'form-control',
                        [
                            'validate-required'         => 'false',
                            'validate-required-message' => 'O campo Veículo Divulgação 2 não foi informado'
                        ]
                    );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center mt-4 mb-2">
                <button type="submit" class="btn btn-success" id="btnAtualizarPublicacao">Atualizar</button>
            </div>
        </div>
    </fieldset>
</form>
<form action="" name="form2" id="frmExtratoPublicacao"  method="post">
    <fieldset style="margin-bottom: 1.5em;">
        <legend>Extrato da Publicação</legend>
        <div class="row">
            <div class="col-12 col-sm-12 form-group">
                <label><?php db_ancora("Texto Padrão:", "pesquisaTextoPadrao(true);", 1) ?></label>
                <div class="row">
                    <div class="col-12 col-sm-3 form-group mb-2 pl-0">
                        <?php
                            db_input(
                                'l214_sequencial',
                                10,
                                '',
                                true,
                                'text',
                                1,
                                " onchange=\"pesquisaTextoPadrao(false);\" ",
                                '',
                                '',
                                '',
                                '',
                                'form-control',
                                [
                                    'validate-maxlength'                => '10',
                                    'validate-no-special-chars'         => 'true',
                                    'validate-integer'                  => 'true',
                                    'validate-required'                 => "false",
                                    'validate-minlength'                => "1",
                                    'validate-required-message'         => "O campo Texto Padrão não foi informado",
                                    'validate-minlength-message'        => "O código deve ter pelo menos 1 caracteres",
                                    'validate-maxlength-message'        => 'O Texto Padrão deve ter no máximo 10 caracteres',
                                    'validate-no-special-chars-message' => 'O Texto Padrão não deve conter aspas simples, ponto e vírgula ou porcentagem',
                                    'validate-integer-message'          => 'O campo Texto Padrão deve conter apenas numeros'
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-9 form-group mb-2">
                        <?php
                            db_input(
                                'l214_tipo',
                                45,
                                '',
                                true,
                                'text',
                                3,
                                '',
                                '',
                                '',
                                '',
                                '',
                                'form-control',
                                [
                                    'validate-required'          => "false",
                                    'validate-minlength'         => "1",
                                    'validate-required-message'  => "O campo Texto Padrão não foi informado",
                                    'validate-minlength-message' => "O código deve ter pelo menos 1 caracteres",
                                ]
                            );
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 form-group mb-2">
                <?php
                    db_textarea(
                        'l214_texto',
                        0,
                        1,
                        '',
                        true,
                        'text',
                        '1',
                        'onkeydown="disabledQuebraLinha(event)"',
                        '',
                        '',
                        1000,
                        'form-control-plaintext form-control-lg',
                        [
                            'validate-required' => 'false',
                            'validate-minlength' => '10',
                            'validate-no-special-chars' => 'true',
                            'validate-required-message' => 'O campo Texo não foi informado',
                            'validate-minlength-message' => 'O Texo deve ter pelo menos 10 caracteres',
                            'validate-no-special-chars-message' => 'O Texo não deve conter aspas simples, ponto e vírgula ou porcentagem'
                        ]
                    );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center mt-4 mb-2">
                <button type="submit" class="btn btn-success" id="btnImprimirTextoPadrao">Imprimir</button>
            </div>
        </div>
    </fieldset>
</form>
<form action="" name="form3" id="frmEnviarPortalCompras" method="post" style="display: none;">
    <input type="hidden" name="exec" value="enviarPregao">
    <input type="hidden" name="l20_codigo" value="<?= $l20_codigo ?>">
    <fieldset>
        <legend>Enviar Publicação para Plataforma</legend>
        <div class="row">
            <div class="col-12 text-center mt-4 mb-2">
                <button type="submit" class="btn btn-success" id="btnEnviarPortalCompras">Enviar</button>

                <div id="tooltip-default-triangle-pcp" class="tooltip-default">
                    <i class="icon exclamation-triangle warning"></i>
                    <span class="tooltip-default-text tooltip-default-lg"></span>
                </div>

                <div id="tooltip-default-circle-pcp" class="tooltip-default">
                    <i class="icon exclamation-circle info"></i>
                    <span class="tooltip-default-text tooltip-default-lg"></span>
                </div>

            </div>
        </div>
    </fieldset>
</form>
<script>
    (function(){
        let numCampo;
        let nomeCampo;
        let tribunal = 53;
        const respPubliccodigo = document.getElementById('respPubliccodigo');
        const btnAtualizarPublicacao = document.getElementById('btnAtualizarPublicacao');
        const btnImprimirTextoPadrao = document.getElementById('btnImprimirTextoPadrao');
        const btnEnviarPortalCompras = document.getElementById('btnEnviarPortalCompras');
        const validator = initializeValidation('#frmPublicacao');

        if (respPubliccodigo) {
            respPubliccodigo.addEventListener('input', function (e) {
                if (event.target.value === '') {
                    document.getElementById('respPublicnome').value = '';
                    return false;
                }
                validateChangeInteger('respPubliccodigo');
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

        function pesquisaCgm(mostra, num, nome){
            numCampo = num;
            nomeCampo = nome;
            if(mostra == true){
                let sUrl = 'func_nome.php?funcao_js=parent.carregaCgmAncora|z01_numcgm|z01_nome&filtro=1';
                if(typeof db_iframe_cgm != 'undefined'){
                    db_iframe_cgm.jan.location.href = sUrl;
                    db_iframe_cgm.show();
                    return false;
                }

                let frame = js_OpenJanelaIframe(
                    '',
                    'db_iframe_cgm',
                    sUrl,
                    'Pesquisa',
                    false,
                    '0',
                    '1'
                );

                if(frame){
                    frame.setAltura("100%");
                    frame.setLargura("100%");
                    frame.setPosX("0");
                    frame.setPosY("0");
                    frame.hide();
                    db_iframe_cgm.show();
                }
            } else{
                if(!validateChangeInteger(numCampo)){
                    return false;
                }

                numcgm = document.getElementById(numCampo).value;
                if (numcgm != '') {
                    js_OpenJanelaIframe(
                        '',
                        'db_iframe_cgm',
                        'func_nome.php?pesquisa_chave=' + numcgm + '&funcao_js=parent.carregaCgm&filtro=1',
                        'Pesquisa',
                        false
                    );
                } else {
                    document.getElementById(numCampo).value = "";
                }
            }
        }

        function carregaCgmAncora(cod, desc){
            document.getElementById(numCampo).value = cod;
            document.getElementById(nomeCampo).value = desc;
            validateChangeInteger(numCampo)
            db_iframe_cgm.hide();
        }

        function carregaCgm(erro, chave){
            document.getElementById(nomeCampo).value = chave;
            if (erro == true) {
                document.getElementById(numCampo).value = "";
                document.getElementById(nomeCampo).value = "";
                alert("Responsável não encontrado!");
            }
        }

        function pesquisaTextoPadrao(mostra){
            texto2 = false;
            if(mostra){
                js_OpenJanelaIframe(
                    '',
                    'db_iframe_liclicpublicacoes',
                    'func_lictextopublicacao.php?texto2=' + texto2 + '&funcao_js=parent.preenchePesquisaAncora|0|1|2',
                    'Pesquisa',
                    true
                );

                return false;
            }

            sequencial = document.getElementById('l214_sequencial').value
            js_OpenJanelaIframe(
                '',
                'db_iframe_liclicpublicacoes',
                'func_lictextopublicacao.php?texto2=' + texto2 + '&publicacao=true&pesquisa_chave=' + sequencial + '&funcao_js=parent.preenchePesquisa',
                'Pesquisa',
                false
            );
        }

        function preenchePesquisaAncora(chave, chave2, chave3){
            db_iframe_liclicpublicacoes.hide();
            document.getElementById('l214_sequencial').value = chave;
            document.getElementById('l214_tipo').value = chave2;
            document.getElementById('l214_texto').value = chave3;
        }

        function preenchePesquisa(chave, chave2, chave3){
            db_iframe_liclicpublicacoes.hide();
            if (chave3 == undefined) {
                document.getElementById('l214_sequencial').value = "";
                document.getElementById('l214_tipo').value = "";
                document.getElementById('l214_texto').value = "";
            } else {
                document.getElementById('l214_sequencial').value = chave;
                document.getElementById('l214_tipo').value = chave2;
                document.getElementById('l214_texto').value = chave3;
            }
        }

        function disabledQuebraLinha(event){
            if(event.key === 'Enter'){
                event.preventDefault();
            }
        }

        function getDadosTribunal(){
            let oParam = {};
            oParam.exec = 'getTribunal';
            oParam.l20_codigo = l20_codigo;
            let oAjax = new Ajax.Request(
                url,
                {
                    method: 'post',
                    asynchronous: true,
                    parameters: 'json=' + JSON.stringify(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        if(typeof isLicitacao != 'undefined' && isLicitacao /*oRetorno.data.tribunal.l44_sequencial && [100, 101, 102, 103].includes(oRetorno.data.tribunal.l44_sequencial)*/){
                            document.getElementById('frmPublicacao').style.display = 'block';
                        }

                        loadEdit();
                    }
                }
            );
        }

        function getAcessoPcp(){
            let oParam = {};
            oParam.exec = 'listagemLiclicitaParam';
            new Ajax.Request(
                url,
                {
                    method: 'post',
                    asynchronous: true,
                    parameters: 'json=' + JSON.stringify(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        if(oRetorno.data.l12_acessoapipcp){
                            document.getElementById('frmEnviarPortalCompras').style.display = 'block';
                        }
                    }
                }
            );
        }

        function loadEdit(){
            let oParam = {};
            oParam.exec = 'getPublicacaoByCodigo';
            oParam.l20_codigo = l20_codigo;
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
                    onComplete: async function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);

                        document.getElementById('l20_dtpulicacaoedital').value = oRetorno.data.publicacao.l20_dtpulicacaoedital;
                        document.getElementById('l20_linkedital').value = oRetorno.data.publicacao.l20_linkedital;
                        if(oRetorno.data.publicacao.l20_diariooficialdivulgacao){
                            document.getElementById('l20_diariooficialdivulgacao').value = oRetorno.data.publicacao.l20_diariooficialdivulgacao;
                        }
                        document.getElementById('l20_dtpublic').value = oRetorno.data.publicacao.l20_dtpublic;
                        document.getElementById('l20_dtpulicacaopncp').value = oRetorno.data.publicacao.l20_dtpulicacaopncp;
                        document.getElementById('l20_linkpncp').value = oRetorno.data.publicacao.l20_linkpncp;
                        document.getElementById('l20_datapublicacao1').value = oRetorno.data.publicacao.l20_datapublicacao1;
                        document.getElementById('l20_nomeveiculo1').value = oRetorno.data.publicacao.l20_nomeveiculo1;
                        document.getElementById('l20_datapublicacao2').value = oRetorno.data.publicacao.l20_datapublicacao2;
                        document.getElementById('l20_nomeveiculo2').value = oRetorno.data.publicacao.l20_nomeveiculo2;
                        document.getElementById('respPubliccodigo').value = oRetorno.data.publicacao.respPubliccodigo;
                        document.getElementById('respPublicnome').value = oRetorno.data.publicacao.respPublicnome;

                        Swal.close();
                    }
                }
            );
        }

        getDadosTribunal();
        getAcessoPcp();

        if(btnAtualizarPublicacao != null){
            btnAtualizarPublicacao.addEventListener('click', function(e){
                e.preventDefault();

                const formData = serializarFormulario(document.getElementById('frmPublicacao'));
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

                new Ajax.Request(
                    url,
                    {
                        method: 'post',
                        parameters: 'json=' + formData,
                        onComplete: (oAjax) => {
                            let oRetorno = JSON.parse(oAjax.responseText);
                            if(oRetorno.status == 200){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sucesso!',
                                    text: 'Publicação atualizada com sucesso!',
                                });

                                if(typeof isLicitacao != 'undefined' && isLicitacao){
                                    parent.closeLicitacao(false, true);
                                    return false;
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

        if(btnImprimirTextoPadrao != null){
            btnImprimirTextoPadrao.addEventListener('click', function(e){
                e.preventDefault();
                let l214_tipo = document.getElementById('l214_tipo').value;
                let l214_texto = document.getElementById('l214_texto').value.replace(/\r\n|\r|\n/g, "<br />");
                let l214_sequencial = document.getElementById('l214_sequencial').value;

                if(!l214_sequencial){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção!',
                        text: 'Selecione o Texto Padrão para realizar a impressão!',
                    });
                    return false;
                }

                let query = `l214_tipo=${l214_tipo}&l214_texto=${l214_texto}&l214_sequencial=${l214_sequencial}&licitacao=${l20_codigo}`;

                jan = window.open(
                        'lic1_liclicpublicacaorelatorio.php?' + query,
                        '',
                        'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 '
                    );

                jan.moveTo(0, 0);
            })
        }

        if(btnEnviarPortalCompras != null){
            btnEnviarPortalCompras.addEventListener('click', function(e){
                e.preventDefault();
                Swal.fire({
                    title: 'Aguarde...',
                    text: 'Estamos processando sua solicitação.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                let sUrl = 'lic1_enviointegracaocompraspublicas.RPC.php';
                let oParam = new Object();
                oParam.codigo = l20_codigo;
                oParam.exec = "ValidaPregao";

                new Ajax.Request(
                    sUrl,
                    {
                        method: 'post',
                        parameters: 'json=' + JSON.stringify(oParam),
                        onComplete: (oAjax) => {
                            retornoValidarPortalDeCompras(oAjax);
                        }
                    }
                );
            })
        }

        function retornoValidarPortalDeCompras(oAjax){
            const response = JSON.parse(oAjax.responseText); // Supondo que a resposta seja JSON.
            const tooltipRdcTriangle = document.getElementById('tooltip-default-triangle-pcp');
            const tooltipTextDivTriangle = tooltipRdcTriangle.querySelector('.tooltip-default-text');

            const tooltipRdcCircle = document.getElementById('tooltip-default-circle-pcp');
            const tooltipTextDivCircle = tooltipRdcCircle.querySelector('.tooltip-default-text');
            let tooltipBody = "";

            if(response.status === 4){
                const mensagens = response.siglasInvalidas.map(sigla => {
                    return `${sigla}<hr class="tooltip-line">`;
                }).join('');

                const mensagensIncorretaCorreta = response.siglasIncorretasECorretas.map(item => {
                    const { descricao, incorreta, correta } = item;
                    return `A unidade de medida "${descricao}" está com a sigla incorreta "${incorreta}". A sigla correta é "${correta}".<br>`;
                }).join('');

                tooltipBody += '<strong>' + response.message + '</strong> <hr class="tooltip-line">';
                tooltipBody += mensagens;
                tooltipBody += mensagensIncorretaCorreta;

                tooltipTextDivTriangle.innerHTML = tooltipBody;
                tooltipRdcTriangle.style.display = 'inline-block';

                Swal.close();
            } else if(response.status === 3){
                let mensagens = '';

                if(response.siglasFaltantesNoPcp && Object.values(response.siglasFaltantesNoPcp).length > 0){
                    Object.values(response.siglasFaltantesNoPcp).forEach(sigla => {
                        mensagens += `${sigla}`;
                    });
                }

                if(mensagens !== ''){
                    tooltipBody += '<strong>' + response.message + '</strong> <hr class="tooltip-line">';
                    tooltipBody += mensagens;

                    tooltipTextDivCircle.innerHTML = tooltipBody;
                    tooltipRdcCircle.style.display = 'inline-block';
                }

                enviarPortalDeCompras();

            } else if(response.status === 2){
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: response.message,
                });
            } else if(response.status === 1){
                enviarPortalDeCompras();
            }
        }

        function enviarPortalDeCompras(){
            let sUrl = 'lic1_enviointegracaocompraspublicas.RPC.php';
            let oParam = new Object();
            oParam.codigo = l20_codigo;
            oParam.exec = "EnviarPregao";

            new Ajax.Request(
                sUrl,
                {
                    method: 'post',
                    parameters: 'json=' + JSON.stringify(oParam),
                    onComplete: (oAjax) => {
                        let oRetorno = JSON.parse(oAjax.responseText);
                        if(oRetorno.status === 1) {
                            const tooltipRdcTriangle = document.getElementById('tooltip-default-triangle-pcp');
                            const tooltipRdcCircle = document.getElementById('tooltip-default-circle-pcp');
                            tooltipRdcTriangle.style.display = 'none';
                            tooltipRdcCircle.style.display = 'none';

                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso!',
                                text: oRetorno.message,
                            });
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

        window.pesquisaCgm = pesquisaCgm;
        window.carregaCgmAncora = carregaCgmAncora;
        window.carregaCgm = carregaCgm;
        window.pesquisaTextoPadrao = pesquisaTextoPadrao;
        window.preenchePesquisaAncora = preenchePesquisaAncora;
        window.preenchePesquisa = preenchePesquisa;
        window.disabledQuebraLinha = disabledQuebraLinha;
    })()
</script>
