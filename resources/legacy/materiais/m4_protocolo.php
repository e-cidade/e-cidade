<?php

require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_usuariosonline.php';
require_once 'libs/db_utils.php';
require_once 'dbforms/db_funcoes.php';
require_once ("classes/db_protprocesso_classe.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($_POST);

$clprotprocesso = new cl_protprocesso;
$rotulo         = new rotulocampo();
$rotulo->label("p58_codproc");
$rotulo->label("p58_obs");

$anousu = db_getsession('DB_anousu');
$instit = db_getsession('DB_instit');

$sqlerro = false;

$sContass = explode(".", db_getsession("DB_login"));
?>

<html>
	<head>
		<title>DBSeller Informtica Ltda - Pgina Inicial</title>
  		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <?php
            db_app::load('scripts.js');
            db_app::load("strings.js");
            db_app::load('prototype.js');
            db_app::load('datagrid.widget.js');
            db_app::load("dbcomboBox.widget.js");
            db_app::load('dbtextField.widget.js');
            db_app::load("widgets/DBHint.widget.js");
            db_app::load("widgets/dbautocomplete.widget.js");
            db_app::load("widgets/windowAux.widget.js");
            db_app::load('grid.style.css');
            db_app::load('estilos.css');
            db_app::load("mask.js");
            db_app::load("form.js");
            db_app::load("estilos.bootstrap.css");
            db_app::load("sweetalert.js");
            db_app::load("just-validate.js");
            db_app::load('messageboard.widget.js');
            db_app::load('windowAux.widget.js');
            db_app::load('dbtextFieldData.widget.js');
        ?>
	  <style>
    	 .temdesconto
    	 {
      	 	background-color: #D6EDFF
    	 }
        .container {
            margin-top: 20px; /* Espa?o acima do container */
            padding: 20px;
            max-width: 100%; /* Largura m?xima do conte?do */
            width: 1240px; /* Para garantir responsividade */
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
        fieldset{
            padding: 10px;
        }

        a{
            cursor: pointer;
        }

        .pl-0{
            padding-left: 0;
        }
	  </style>
	</head>

    <?php if ($sContass[1] != 'contass'): ?>
                <body bgcolor="#CCCCCC">
                    <p>
                        <center>
                            <br>
                            <h2>Essa rotina apenas pode ser usada por usurios da contass</h2>
                        </center>
                    </p>
                </body>
            </html>
    <?php else: ?>
    <body class="container">
        <form name='form1' method="post" id="frmAtualizarProtocolo" action="">
            <input type="hidden" name="exec" value="atualizarProtocolo">
            <fieldset>
                <legend>Manuteno Protocolo</legend>
                <div class="row">
                    <div class="col-12 col-sm-6 form-group mb-2">
                        <label for="p58_codproc">
                            <?php db_ancora("Protocolo: ", "js_pesquisap58_codproc(true);", 1); ?>
                        </label>
                        <?php
                            db_input(
                                'p58_codproc',
                                10,
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
                                    'validate-required'                 => "true",
                                    'validate-integer'                  => 'true',
                                    'validate-required-message'         => "O campo protocolo no foi informado",
                                    'validate-integer-message'          => 'O campo protocolo deve conter apenas numeros'
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-3 form-group mb-2">
                        <label for="protocolo_geral">Protocolo Geral:</label>
                        <?php
                            db_input(
                                'protocolo_geral',
                                40,
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
                    <div class="col-12 col-sm-3 form-group mb-2">
                        <label for="p58_dtproc">Data do Processo:</label>
                        <?php
                            db_input(
                                'p58_dtproc',
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
                                    'validate-date-message' => 'Informe uma data vlida'
                                ]
                            );
                        ?>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12 col-sm-6 form-group mb-2">
                        <label for="p58_coddepto">
                            <?php db_ancora('Departamento:', 'pesquisaDepartamento(true);', 1) ?>
                        </label>
                        <div class="row">
                            <div class="col-12 col-sm-2 mb-2 pl-0" style="padding-left: 0">
                                <?php
                                    db_input(
                                        'p58_coddepto',
                                        4,
                                        '',
                                        true,
                                        'text',
                                        1,
                                        " onchange='pesquisaDepartamento(false);' ",
                                        '',
                                        '',
                                        '',
                                        '',
                                        'form-control',
                                        [
                                            'validate-required'                 => "true",
                                            'validate-minlength'                => "1",
                                            'validate-maxlength'                => '10',
                                            'validate-no-special-chars'         => 'true',
                                            'validate-integer'                  => 'true',
                                            'validate-required-message'         => "O campo departamento no foi informado",
                                            'validate-minlength-message'        => "O cdigo deve ter pelo menos 1 caracteres",
                                            'validate-maxlength-message'        => 'O Cd. Sequencial deve ter no mximo 10 caracteres',
                                            'validate-no-special-chars-message' => 'O Cd. Sequencial no deve conter aspas simples, ponto e vrgula ou porcentagem',
                                            'validate-integer-message'          => 'O campo Cd. Sequencial deve conter apenas numeros'
                                        ]
                                    );
                                ?>
                            </div>
                            <div class="col-12 col-sm-10 mb-2 pl-0 pr-0">
                                <?php
                                    db_input(
                                        'p58_desccoddepto',
                                        40,
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
                                            'validate-required-message'  => "O campo departamento no foi informado",
                                            'validate-minlength-message' => "O nome deve ter pelo menos 1 caracteres",
                                        ]
                                    );
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 form-group">
                        <label for="">
                            <?php db_ancora("Tipo:","pesquisaCodigoTipo(true);", 1); ?>
                        </label>
                        <div class="row">
                            <div class="col-12 col-sm-2 mb-2 pl-0">
                                <?php
                                    db_input(
                                        'p58_codigo',
                                        5,
                                        '',
                                        true,
                                        'text',
                                        1,
                                        " onchange='pesquisaCodigoTipo(false);' ",
                                        '',
                                        '',
                                        '',
                                        '',
                                        'form-control',
                                        [
                                            'validate-required'                 => "true",
                                            'validate-minlength'                => "1",
                                            'validate-maxlength'                => '10',
                                            'validate-no-special-chars'         => 'true',
                                            'validate-integer'                  => 'true',
                                            'validate-required-message'         => "O campo tipo no foi informado",
                                            'validate-minlength-message'        => "O tipo deve ter pelo menos 1 caracteres",
                                            'validate-maxlength-message'        => 'O tipo deve ter no mximo 10 caracteres',
                                            'validate-no-special-chars-message' => 'O tipo no deve conter aspas simples, ponto e vrgula ou porcentagem',
                                            'validate-integer-message'          => 'O campo tipo deve conter apenas numeros'
                                        ]
                                    );
                                ?>
                            </div>
                            <div class="col-12 col-sm-10 mb-2 pr-0">
                                <?php
                                    db_input(
                                        'p51_descr',
                                        40,
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
                                            'validate-required-message'  => "O campo tipo no foi informado",
                                            'validate-minlength-message' => "O nome deve ter pelo menos 1 caracteres",
                                        ]
                                    );
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6 form-group">
                        <label for="">
                            <?php db_ancora("Titular:","pesquisaNumCgm(true);", 1); ?>
                        </label>
                        <div class="row">
                            <div class="col-12 col-sm-2 mb-2 pl-0">
                                <?php
                                    db_input(
                                        'p58_numcgm',
                                        5,
                                        '',
                                        true,
                                        'text',
                                        3,
                                        " onchange='pesquisaNumCgm(false);' ",
                                        '',
                                        '',
                                        '',
                                        '',
                                        'form-control',
                                        [
                                            'validate-required'                 => "true",
                                            'validate-minlength'                => "1",
                                            'validate-maxlength'                => '10',
                                            'validate-no-special-chars'         => 'true',
                                            'validate-integer'                  => 'true',
                                            'validate-required-message'         => "O campo titular no foi informado",
                                            'validate-minlength-message'        => "O titular deve ter pelo menos 1 caracteres",
                                            'validate-maxlength-message'        => 'O titular deve ter no mximo 10 caracteres',
                                            'validate-no-special-chars-message' => 'O titular no deve conter aspas simples, ponto e vrgula ou porcentagem',
                                            'validate-integer-message'          => 'O campo titular deve conter apenas numeros'
                                        ]
                                    );
                                ?>
                            </div>
                            <div class="col-12 col-sm-10 mb-2 pl-0 pr-0">
                                <?php
                                    db_input(
                                        'z01_nome',
                                        40,
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
                                            'validate-required-message'  => "O campo tipo no foi informado",
                                            'validate-minlength-message' => "O nome deve ter pelo menos 1 caracteres",
                                        ]
                                    );
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 form-group">
                        <label for="p58_requer">Requerente</label>
                        <?php
                            db_input(
                                'p58_requer',
                                40,
                                '',
                                true,
                                'text',
                                1,
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
                <div class="row">
                    <div class="col-12 col-sm-12 form-group mb-2">
                        <label for="p58_obs">Observao</label>
                        <?php
                            db_textarea(
                                'p58_obs',
                                0,
                                1,
                                '',
                                true,
                                'text',
                                '1',
                                'onkeydown="disabledQuebraLinha(event)"',
                                '',
                                '',
                                1100,
                                'form-control-plaintext opcional form-control-lg',
                                [
                                    'validate-required' => 'true',
                                    'validate-minlength' => '10',
                                    'validate-no-special-chars' => 'true',
                                    'validate-required-message' => 'O campo observao no foi informado',
                                    'validate-minlength-message' => 'O observao deve ter pelo menos 10 caracteres',
                                    'validate-no-special-chars-message' => 'O observao no deve conter aspas simples, ponto e vrgula ou porcentagem'
                                ]
                            );
                        ?>
                    </div>
                </div>
            </fieldset>
            <div class="row">
                <div class="col-12 text-center mt-4 mb-2">
                    <button type="submit" class="btn btn-success" id="btnAlterarProtocolo">Alterar</button>
                    <button type="reset" class="btn btn-danger" id="btnCancelarProtocolo">Cancelar</button>
                    <button type="button" class="btn btn-warning" id="btnPesquisarProtocolo">Pesquisar</button>
                </div>
            </div>
        </form>
    </body>
    <?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>

    </html>

    <script>
        const url = 'm4_protocolo.RPC.php';
        const btnPesquisarProtocolo = document.getElementById('btnPesquisarProtocolo');
        const btnAlterarProtocolo = document.getElementById('btnAlterarProtocolo');
        const validator = initializeValidation('#frmAtualizarProtocolo');

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

        function js_pesquisap58_codproc(mostra){
            if(mostra==true){
                js_OpenJanelaIframe(
                    '',
                    'db_iframe_protprocesso',
                    'func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|p58_numero|DB_p58_numcgm|z01_nome|p58_dtproc|p51_descr|p58_obs|DB_p58_requer|p58_coddepto|p58_codigo|descrdepto',
                    'Pesquisa',
                    true
                );
            }
        }

        function js_mostraprotprocesso1(p58_codproc, p58_numero, p58_numcgm, z01_nome, p58_dtproc, p51_descr, p58_obs, p58_requer, p58_coddepto, p58_codigo, descrdepto){
            document.getElementById('p58_codproc').value = p58_codproc;
            document.getElementById('protocolo_geral').value = p58_numero;
            document.getElementById('p58_dtproc').value = p58_dtproc;
            document.getElementById('p58_coddepto').value = p58_coddepto;
            document.getElementById('p58_desccoddepto').value = descrdepto;
            document.getElementById('p58_codigo').value = p58_codigo;
            document.getElementById('p51_descr').value = p51_descr;
            document.getElementById('p58_numcgm').value = p58_numcgm;
            document.getElementById('z01_nome').value = z01_nome;
            document.getElementById('p58_requer').value = p58_requer;
            document.getElementById('p58_obs').value = p58_obs;
            db_iframe_protprocesso.hide();
        }

        function js_pesquisa() {
            db_iframe.jan.location.href = 'func_procarquiv.php?funcao_js=parent.js_preenchepesquisa|0';
            db_iframe.mostraMsg();
            db_iframe.show();
            db_iframe.focus();
        }

        function js_preenchepesquisa(chave) {
            db_iframe.hide();
            location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
        }

        function disabledQuebraLinha(event){
            if(event.key === 'Enter'){
                event.preventDefault();
            }
        }

        function pesquisaDepartamento(mostra){
            if(mostra){
                var sUrl = 'func_db_depart.php?funcao_js=parent.carregaDepartamento|coddepto|descrdepto';
                if(typeof db_iframe_departamento != 'undefined'){
                    db_iframe_departamento.jan.location.href = sUrl;
                    db_iframe_departamento.show();
                    return false;
                }

                let frame = js_OpenJanelaIframe(
                    '',
                    'db_iframe_departamento',
                    sUrl,
                    'Pesquisar Departamento',
                    false,
                    '0'
                );
                if(frame){
                    frame.setAltura("100%");
                    frame.setLargura("100%");
                    frame.setPosX("0");
                    frame.setPosY("0");
                    frame.hide();
                    db_iframe_departamento.show();
                }
            } else {
                if(document.getElementById('p58_coddepto').value != '' && validateChangeInteger('p58_coddepto')){
                    js_OpenJanelaIframe(
                        '',
                        'db_iframe_departamento',
                        'func_db_depart.php?pesquisa_chave=' + document.getElementById('p58_coddepto').value + '&funcao_js=parent.carregaDepartamento',
                        'Pesquisar licitao Outro rgo',
                        false,
                        '0'
                    );
                }
            }

        }

        function carregaDepartamento(cod, desc, error = false){
            if(!desc){
                document.getElementById('p58_desccoddepto').value = cod;
                validateChangeInteger('p58_coddepto')
                db_iframe_departamento.hide();
                return false;
            }

            if(error){
                Swal.fire({
                    icon: 'warning',
                    title: 'Ateno!',
                    text: 'Nenhum departamento foi encontrado.',
                });
                validateChangeInteger('p58_coddepto');
                document.getElementById('p58_coddepto').value = '';
                document.getElementById('p58_desccoddepto').value = '';
                return false;
            }

            document.getElementById('p58_coddepto').value = cod;
            document.getElementById('p58_desccoddepto').value = desc;
            validateChangeInteger('p58_coddepto');
            db_iframe_departamento.hide();
        }

        function pesquisaCodigoTipo(mostra){
            if(mostra){
                let sUrl = 'func_tipoproc.php?grupo=1&funcao_js=parent.mostraTipoAncora|0|1';
                if(typeof db_iframe_tipo != 'undefined'){
                    db_iframe_tipo.jan.location.href = sUrl;
                    db_iframe_tipo.show();
                    return false;
                }

                let frame = js_OpenJanelaIframe(
                    '',
                    'db_iframe_tipo',
                    sUrl,
                    'Tipo',
                    false,
                    '0'
                );
                if(frame){
                    frame.setAltura("100%");
                    frame.setLargura("100%");
                    frame.setPosX("0");
                    frame.setPosY("0");
                    frame.hide();
                    db_iframe_tipo.show();
                }
            } else {
                let p58_codigo = document.getElementById('p58_codigo').value;
                let sUrl = 'func_tipoproc.php?grupo=1&pesquisa_chave='+p58_codigo+'&funcao_js=parent.mostraTipo';
                js_OpenJanelaIframe(
                    '',
                    'db_iframe_tipo',
                    sUrl,
                    'Tipo',
                    false,
                    '0'
                );
            }
        }

        function mostraTipoAncora(chave1, chave2){
            document.getElementById('p58_codigo').value = chave1;
            document.getElementById('p51_descr').value = chave2;
            db_iframe_tipo.hide();
        }

        function mostraTipo(chave, erro){
            if(erro == true){
                document.getElementById('p58_codigo').focus();
                document.getElementById('p58_codigo').value = '';
                document.getElementById('p51_descr').value = '';
                return false;
            }
            document.getElementById('p51_descr').value = chave;
        }

        function pesquisaNumCgm(mostra){
            if(mostra){
                let sUrl = 'func_nome.php?funcao_js=parent.mostraTitularAncora|0|1&testanome=true&incproc=true';
                if(typeof db_iframe_titular != 'undefined'){
                    db_iframe_titular.jan.location.href = sUrl;
                    db_iframe_titular.show();
                    return false;
                }

                let frame = js_OpenJanelaIframe(
                    '',
                    'db_iframe_titular',
                    sUrl,
                    'Titular',
                    false,
                    '0'
                );
                if(frame){
                    frame.setAltura("100%");
                    frame.setLargura("100%");
                    frame.setPosX("0");
                    frame.setPosY("0");
                    frame.hide();
                    db_iframe_titular.show();
                }
            } else {
                let p58_numcgm = document.getElementById('p58_numcgm').value;
                let sUrl = 'func_nome.php?pesquisa_chave='+p58_numcgm+'&funcao_js=parent.mostraTitular';
                js_OpenJanelaIframe(
                    '',
                    'db_iframe_titular',
                    sUrl,
                    'Tipo',
                    false,
                    '0'
                );
            }
        }

        function mostraTitularAncora(chave1, chave2){
            document.getElementById('p58_numcgm').value = chave1;
            document.getElementById('z01_nome').value = chave2;
            document.getElementById('p58_requer').value = chave2;
            db_iframe_titular.hide();
        }

        function mostraTitular(erro, chave){
            if(erro == true){
                document.getElementById('p58_numcgm').focus();
                document.getElementById('p58_numcgm').value = '';
                document.getElementById('z01_nome').value = '';
                document.getElementById('p58_requer').value = '';
                return false;
            }

            document.getElementById('z01_nome').value = chave;
            document.getElementById('p58_requer').value = chave;
        }

        if(btnPesquisarProtocolo != null){
            btnPesquisarProtocolo.addEventListener('click', function(e){
                e.preventDefault();
                js_pesquisap58_codproc(true);
            });
        }

         if(btnAlterarProtocolo != null){
             btnAlterarProtocolo.addEventListener('click', function (e) {
                 e.preventDefault();
                 const formData = serializarFormulario(document.getElementById('frmAtualizarProtocolo'));
                 const isValid = validator.validate();
                 if(!isValid){
                     Swal.fire({
                         icon: 'warning',
                         title: 'Ateno!',
                         text: 'Preencha todos os campos corretamente.',
                     });
                     return false;
                 }
                Swal.fire({
                     title: 'Os dados do Protocolo sero atualizados, deseja continuar?',
                     text: "Essa ao no pode ser desfeita!",
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonText: 'Sim, atualizars!',
                     cancelButtonText: 'Cancelar',
                 }).then((result) => {
                     if (result.isConfirmed) {
                         Swal.fire({
                             title: 'Aguarde...',
                             text: 'Estamos processando sua solicitao.',
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
                                            text: oRetorno.message,
                                        });
                                        // document.getElementById('p58_codproc').value      = '';
                                        // document.getElementById('protocolo_geral').value  = '';
                                        // document.getElementById('p58_dtproc').value       = '';
                                        // document.getElementById('p58_coddepto').value     = '';
                                        // document.getElementById('p58_desccoddepto').value = '';
                                        // document.getElementById('p58_codigo').value       = '';
                                        // document.getElementById('p51_descr').value        = '';
                                        // document.getElementById('p58_numcgm').value       = '';
                                        // document.getElementById('z01_nome').value         = '';
                                        // document.getElementById('p58_requer').value       = '';
                                        // document.getElementById('p58_obs').value          = '';
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

        onLoad=document.form1.p58_codproc.select();
        onLoad=document.form1.p58_codproc.focus();
    </script>

<?php endif; ?>
