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
<form action="" method="post" name="form1" id="frmLicitacoes">
    <input type="hidden" name="exec" value="<?= !empty($l20_codigo)? 'updateLicitacoes' : 'inserirLicitacoes' ?>">
    <input type="hidden" name="l20_tipoprocesso" id="l20_tipoprocesso">
    <fieldset>
        <legend>Licitações</legend>
        <div class="row">
            <div class="col-12 col-sm-2 form-group mb-2">
                <label for="l20_codigo">Cód. Sequencial:</label>
                <?php
                    db_input(
                        'l20_codigo',
                        10,
                        $l20_codigo,
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
            <div class="col-12 col-sm-10 form-group">
                <label for="l20_codepartamento">
                    <?php db_ancora('Cód. Departamento:', 'pesquisaDepartamento(true);', 1) ?>
                </label>
                <div class="row">
                    <div class="col-12 col-sm-3 mb-2 pl-0">
                        <?php
                            db_input(
                                'l20_codepartamento',
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
                                    'validate-required-message'         => "O campo departamento não foi informado",
                                    'validate-minlength-message'        => "O código deve ter pelo menos 1 caracteres",
                                    'validate-maxlength-message'        => 'O campo departamento deve ter no máximo 10 caracteres',
                                    'validate-no-special-chars-message' => 'O campo departamento não deve conter aspas simples, ponto e vírgula ou porcentagem',
                                    'validate-integer-message'          => 'O campo departamento deve conter apenas numeros'
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-9 mb-2 pr-0">
                        <?php
                            db_input(
                                'l20_descricaodep',
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
                                    'validate-required-message'  => "O campo departamento não foi informado",
                                    'validate-minlength-message' => "O campo departamento deve ter pelo menos 1 caracteres",
                                ]
                            );
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6 form-group">
                <label for="respAutocodigo">
                    <?php db_ancora('Resp. Aut. da Abertura:', "pesquisaCgm(true, 'respAutocodigo', 'respAutonome');", 1) ?>
                </label>
                <div class="row">
                    <div class="col-12 col-sm-3 mb-2 pl-0">
                        <?php
                            db_input(
                                'respAutocodigo',
                                10,
                                '',
                                true,
                                'text',
                                1,
                                " onchange=\"pesquisaCgm(false, 'respAutocodigo', 'respAutonome');\" ",
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
                                    'validate-required-message'         => "O campo Responsável pela abertura do processo licitatório não foi informado",
                                    'validate-minlength-message'        => "O código deve ter pelo menos 1 caracteres",
                                    'validate-maxlength-message'        => 'O Responsável pela abertura do processo licitatório deve ter no máximo 10 caracteres',
                                    'validate-no-special-chars-message' => 'O Responsável pela abertura do processo licitatório não deve conter aspas simples, ponto e vírgula ou porcentagem',
                                    'validate-integer-message'          => 'O campo Responsável pela abertura do processo licitatório deve conter apenas numeros',
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-9 mb-2 pr-0">
                        <?php
                            db_input(
                                'respAutonome',
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
                                    'validate-required-message'  => "O campo Responsável pela abertura do processo licitatório não foi informado",
                                    'validate-minlength-message' => "O nome do Responsável pela abertura do processo licitatório deve ter pelo menos 1 caracteres",
                                ]
                            );
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 form-group">
                <label for="">
                    <?php db_ancora("Resp.Emissão Edital:", "pesquisaCgm(true,'respEditalcodigo','respEditalnome');", 1); ?>
                </label>
                <div class="row">
                    <div class="col-12 col-sm-3 mb-2 pl-0">
                        <?php
                            db_input(
                                'respEditalcodigo',
                                10,
                                '',
                                true,
                                'text',
                                1,
                                " onchange=\"pesquisaCgm(false, 'respEditalcodigo', 'respEditalnome');\" ",
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
                                    'validate-required-message'         => "O campo Responsável pela emissão do edital  não foi informado",
                                    'validate-minlength-message'        => "O código deve ter pelo menos 1 caracteres",
                                    'validate-maxlength-message'        => 'O campo Responsável pela emissão do edital  deve ter no máximo 10 caracteres',
                                    'validate-no-special-chars-message' => 'O campo Responsável pela emissão do edital  não deve conter aspas simples, ponto e vírgula ou porcentagem',
                                    'validate-integer-message'          => 'O campo Responsável pela emissão do edital  deve conter apenas numeros',
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-9 mb-2 pr-0">
                        <?php
                            db_input(
                                'respEditalnome',
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
                                    'validate-required-message'  => "O campo Responsável pela emissão do edital não foi informado",
                                    'validate-minlength-message' => "O campo Responsável pela emissão do edital deve ter pelo menos 1 caracteres",
                                ]
                            );
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6 form-group">
                <label for="l20_leidalicitacao">Lei de Licitações:</label>
                <select
                    name="l20_leidalicitacao"
                    id="l20_leidalicitacao"
                    class="custom-select"
                    data-validate-required="true"
                    data-validate-required-message="O campo Lei de Licitações não foi informado"
                >
                    <option value="">Selecione</option>
                    <option value="1">1 - Lei 14.133/2021</option>
                    <option value="2">2 - Lei 8.666/1993 e outras</option>
                </select>
            </div>
            <div class="col-12 col-sm-6 form-group">
                <label for="l20_codtipocom">Modalidade:</label>
                <select
                    name="l20_codtipocom"
                    id="l20_codtipocom"
                    class="custom-select"
                    data-validate-required="true"
                    data-validate-required-message="O campo Modalidade não foi informado"
                >
                    <option value="">Selecione</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-4 form-group mb-2">
                <label for="l20_edital">Processo Licitatório:</label>
                <?php
                    db_input(
                        'l20_edital',
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
                        'form-control',
                        [
                            'validate-required'                 => 'true',
                            'validate-maxlength'                => '16',
                            'validate-no-special-chars'         => 'true',
                            'validate-integer'                  => 'true',
                            'validate-required-message'         => 'O campo Processo Licitatório não foi informado',
                            'validate-maxlength-message'        => 'O campo Processo Licitatório deve ter no máximo 16 caracteres',
                            'validate-no-special-chars-message' => 'O campo Processo Licitatório não deve conter aspas simples, ponto e vírgula ou porcentagem',
                            'validate-integer-message'          => 'O campo Processo Licitatório deve conter apenas numeros'
                        ]
                    );
                ?>
            </div>
            <div class="col-12 col-lg-4 form-group mb-2">
                <label for="l20_numero">Numeração:</label>
                <?php
                    db_input(
                        'l20_numero',
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
                        'form-control',
                        [
                            'validate-required'                 => 'true',
                            'validate-maxlength'                => '10',
                            'validate-no-special-chars'         => 'true',
                            'validate-integer'                  => 'true',
                            'validate-required-message'         => 'O campo Numeração não foi informado',
                            'validate-maxlength-message'        => 'O campo Numeração deve ter no máximo 16 caracteres',
                            'validate-no-special-chars-message' => 'O campo Numeração não deve conter aspas simples, ponto e vírgula ou porcentagem',
                            'validate-integer-message'          => 'O campo Numeração deve conter apenas numeros'
                        ]
                    );
                ?>
            </div>
            <div class="col-12 col-lg-4 form-group mb-2">
                <label for="l20_nroedital">Edital:</label>
                <?php
                    db_input(
                        'l20_nroedital',
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
                        'form-control',
                        [
                            'validate-required'                 => 'true',
                            'validate-no-special-chars'         => 'true',
                            'validate-integer'                  => 'true',
                            'validate-required-message'         => 'O campo Edital não foi informado',
                            'validate-maxlength-message'        => 'O campo Edital deve ter no máximo 16 caracteres',
                            'validate-no-special-chars-message' => 'O campo Edital não deve conter aspas simples, ponto e vírgula ou porcentagem',
                            'validate-integer-message'          => 'O campo Edital deve conter apenas numeros'
                        ]
                    );
                ?>
            </div>
        </div>
        <div class="row">
            <div id="containNaturezaObjeto" class="col-12 col-sm-6 form-group mb-2" style="display: none;">
                <label for="l20_naturezaobjeto">Natureza do Objeto:</label>
                <select
                    name="l20_naturezaobjeto"
                    id="l20_naturezaobjeto"
                    class="custom-select"
                    data-validate-required="false"
                    data-validate-required-message="O campo Natureza do Objeto não foi informado"
                >
                    <option value="">Selecione</option>
                </select>
            </div>
            <div class="col-12 col-sm-4 form-group">
                <label for="l20_execucaoentrega">Unid.Exec/Entrega:</label>
                <div class="row">
                    <div class="col-12 col-sm-6 mb-2 pl-0">
                        <?=
                            db_input(
                                'l20_execucaoentrega',
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
                                    'validate-required'                 => "true",
                                    'validate-minlength'                => "1",
                                    'validate-maxlength'                => '4',
                                    'validate-no-special-chars'         => 'true',
                                    'validate-integer'                  => 'true',
                                    'validate-required-message'         => "O campo Unidade de Execução/Entrega não foi informado",
                                    'validate-minlength-message'        => "O campo Unidade de Execução/Entrega deve ter pelo menos 1 caracteres",
                                    'validate-maxlength-message'        => 'O campo Unidade de Execução/Entrega deve ter no máximo 4 caracteres',
                                    'validate-no-special-chars-message' => 'O campo Unidade de Execução/Entrega não deve conter aspas simples, ponto e vírgula ou porcentagem',
                                    'validate-integer-message'          => 'O campo Unidade de Execução/Entrega deve conter apenas numeros'
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-6 mb-2">
                        <select
                            name="l20_diames"
                            id="l20_diames"
                            class="custom-select"
                            data-validate-required="true"
                            data-validate-required-message="O campo dias/mês não foi informado"
                        >
                            <option value="1">Dia(s)</option>
                            <option value="2">Mes(es)</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-2 form-group mb-2" id="containNumeroConvidados" style="display: none;">
                <label for="l20_numeroconvidado">Número de Convidados:</label>
                <?=
                    db_input(
                        'l20_numeroconvidado',
                        3,
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
                            'validate-required'                 => "false",
                            // 'validate-minlength'                => "1",
                            // 'validate-maxlength'                => '3',
                            'validate-no-special-chars'         => 'false',
                            'validate-integer'                  => 'false',
                            'validate-required-message'         => "O campo Número de Convidados não foi informado",
                            'validate-minlength-message'        => "O campo Número de Convidados deve ter pelo menos 1 caracteres",
                            'validate-maxlength-message'        => 'O campo Número de Convidados deve ter no máximo 3 caracteres',
                            'validate-no-special-chars-message' => 'O campo Número de Convidados não deve conter aspas simples, ponto e vírgula ou porcentagem',
                            'validate-integer-message'          => 'O campo Número de Convidados deve conter apenas numeros'
                        ]
                    );
                ?>
            </div>
            <div class="col-12 col-sm-6 form-group" id="containRespOrcObrasServicos" style="display: none;">
                <label for="respObrascodigo">
                    <?php db_ancora('Resp.Orc.Obras/Serviço:', "pesquisaCgm(true, 'respObrascodigo', 'respObrasnome');", 1); ?>
                </label>
                <div class="row">
                    <div class="col-12 col-sm-4 mb-2 pl-0">
                        <?php
                            db_input(
                                'respObrascodigo',
                                10,
                                '',
                                true,
                                'text',
                                1,
                                " onchange=\"pesquisaCgm(false, 'respObrascodigo', 'respObrasnome');\" ",
                                '',
                                '',
                                '',
                                '',
                                'form-control',
                                [
                                    'validate-no-special-chars'         => 'true',
                                    'validate-integer'                  => 'true',
                                    'validate-required'                 => "true",
                                    'validate-required-message'         => "O campo Resp.Orc.Obras/Serviço não foi informado",
                                    'validate-maxlength-message'        => 'O Resp.Orc.Obras/Serviço deve ter no máximo 10 caracteres',
                                    'validate-no-special-chars-message' => 'O Resp.Orc.Obras/Serviço não deve conter aspas simples, ponto e vírgula ou porcentagem',
                                    'validate-integer-message'          => 'O campo Resp.Orc.Obras/Serviço deve conter apenas numeros',
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-8 mb-2">
                        <?php
                            db_input(
                                'respObrasnome',
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
                                    'validate-required-message'  => "O campo descrição Resp.Orc.Obras/Serviço não foi informado",
                                ]
                            );
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6 form-group">
                <label for="l20_codepartamento">
                    <?php db_ancora('Comissão:', 'pesquisaEquipePregao(true);', 1) ?>
                </label>
                <div class="row">
                    <div class="col-12 col-sm-3 mb-2 pl-0">
                        <?php
                            db_input(
                                'l20_equipepregao',
                                10,
                                '',
                                true,
                                'text',
                                1,
                                " onchange='pesquisaEquipePregao(false);' ",
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
                                    'validate-required-message'         => "A Comissão não foi informado",
                                    'validate-minlength-message'        => "A Comissão deve ter pelo menos 1 caracteres",
                                    'validate-maxlength-message'        => 'A Comissão deve ter no máximo 10 caracteres',
                                    'validate-no-special-chars-message' => 'A Comissão não deve conter aspas simples, ponto e vírgula ou porcentagem',
                                    'validate-integer-message'          => 'A Comissão deve conter apenas numeros'
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-9 mb-2 pr-0">
                        <?php
                            db_input(
                                'l20_codepartamentodesc',
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
                                    'validate-required-message'  => "A Comissão não foi informado",
                                    'validate-minlength-message' => "A Comissão deve ter pelo menos 1 caracteres",
                                ]
                            );
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 form-group">
                <label for="respConducodigo">
                    <?php db_ancora("Resp.Condução Licit.:", "pesquisaCgm(true,'respConducodigo','respConducodigonome');", 1); ?>
                </label>
                <div class="row">
                    <div class="col-12 col-sm-3 mb-2 pl-0">
                        <?php
                            db_input(
                                'respConducodigo',
                                10,
                                '',
                                true,
                                'text',
                                1,
                                " onchange=\"pesquisaCgm(false, 'respConducodigo', 'respConducodigonome');\" ",
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
                                    'validate-required-message'         => "O Resp.Condução Licit. não foi informado",
                                    'validate-minlength-message'        => "O Resp.Condução Licit. deve ter pelo menos 1 caracteres",
                                    'validate-maxlength-message'        => 'O Resp.Condução Licit. deve ter no máximo 10 caracteres',
                                    'validate-no-special-chars-message' => 'O Resp.Condução Licit. não deve conter aspas simples, ponto e vírgula ou porcentagem',
                                    'validate-integer-message'          => 'O Resp.Condução Licit. deve conter apenas numeros',
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-9 mb-2 pr-0">
                        <?php
                            db_input(
                                'respConducodigonome',
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
                                    'validate-required-message'  => "O Resp.Condução Licit. não foi informado",
                                    'validate-minlength-message' => "O Resp.Condução Licit. deve ter pelo menos 1 caracteres",
                                ]
                            );
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="containRegimeExecucao" class="col-12 col-sm-6 form-group mb-2" style="display: none">
                <label for="l20_regimexecucao">Regime de Execução:</label>
                <select
                    name="l20_regimexecucao"
                    id="l20_regimexecucao"
                    class="custom-select"
                    data-validate-required="false"
                    data-validate-required-message="O campo Regime de Execução não foi informado"
                >
                    <option value="">Selecione</option>
                </select>
            </div>
            <div class="col-12 col-sm-2 form-group mb-2">
                <label for="lprocsis">Processo do Sistema:</label>
                <select
                    name="lprocsis"
                    id="lprocsis"
                    class="custom-select"
                >
                    <option value="s">Sim</option>
                    <option value="n" selected>Não</option>
                </select>
            </div>
            <div class="col-12 col-sm-4 form-group mb-2">
                <label for="l20_procadmin">Processo Administrativo:</label>
                <?php
                        db_input(
                        'l20_procadmin',
                        4,
                        '',
                        true,
                        'text',
                        1,
                        "",
                        '',
                        '',
                        150,
                        '',
                        'form-control opcional',
                        [
                            'validate-maxlength'                => '150',
                            'validate-minlength-message'        => "O Processo Administrativo deve ter pelo menos 1 caracteres",
                            'validate-maxlength-message'        => 'O Processo Administrativo ter no máximo 150 caaracteres',
                        ]
                    );
                ?>
            </div>
            <div class="col-12 col-sm-8 form-group mb-2" id="containProcessoLicitacao" style="display: none;">
                <label for="l34_protprocesso">
                    <?php db_ancora('Processo da Licitação:', "pesquisaProtProcesso(true);", 1); ?>
                </label>
                <div class="row">
                    <div class="col-12 col-sm-4 mb-2 pl-0">
                        <?php
                            db_input(
                                'l34_protprocesso',
                                10,
                                '',
                                true,
                                'text',
                                1,
                                " onchange=\"pesquisaProtProcesso(false);\" ",
                                '',
                                '',
                                '',
                                '',
                                'form-control',
                                [
                                    // 'validate-maxlength'                => '10',
                                    'validate-no-special-chars'         => 'false',
                                    'validate-integer'                  => 'false',
                                    'validate-required'                 => "false",
                                    'validate-required-message'         => "O campo Processo da Licitação não foi informado",
                                    'validate-minlength-message'        => "O código deve ter pelo menos 1 caracteres",
                                    'validate-maxlength-message'        => 'O Processo da Licitação deve ter no máximo 10 caracteres',
                                    'validate-no-special-chars-message' => 'O Processo da Licitação não deve conter aspas simples, ponto e vírgula ou porcentagem',
                                    'validate-integer-message'          => 'O campo Processo da Licitação deve conter apenas numeros'
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-8 mb-2 pr-0">
                        <?php
                            db_input(
                                'l34_protprocessodescr',
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
                                    'validate-required-message'  => "O campo Processo da Licitação não foi informado",
                                ]
                            );
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <div class="accordion">
        <div class="accordion-item">
            <div class="accordion-header">Datas</div>
            <div class="accordion-content">
                <div class="row" style="justify-content: center;">
                    <div class="col-12 col-sm-3 form-group mb-2">
                        <label for="l20_datacria">Abertura Proc. Adm:</label>
                        <?php
                            db_input(
                                'l20_datacria',
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
                                    'validate-date-message' => 'O campo Data de Abertura Proc. Adm não foi informado'
                                ],
                                date('Y-m-d', db_getsession('DB_datausu'))
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-2 form-group mb-2">
                        <label for="l20_horacria">Hora Criação:</label>
                        <?php
                            db_input(
                                'l20_horacria',
                                4,
                                '',
                                true,
                                'time',
                                1,
                                "",
                                '',
                                '',
                                '',
                                '',
                                'form-control',
                                [
                                    'validate-required' => 'true',
                                    'validate-time' => 'true',
                                    'validate-time-message' => 'O campo Hora Criação de Abertura Proc. Adm não foi informado',
                                    'validate-required-message' => 'O campo Hora Criação de Abertura Proc. Adm não foi informado'
                                ],
                                date('H:i')
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-1"></div>
                    <div id="containDataAbertura" class="col-12 col-sm-3 form-group mb-2" style="display: none;">
                        <label for="l20_dataaberproposta">Início Recebimento Proposta:</label>
                        <?php
                            db_input(
                                'l20_dataaberproposta',
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
                                    'validate-date' => 'false',
                                    'validate-date-message' => 'O campo Início Recebimento Proposta não foi informado',
                                    'validate-required-message' => 'O campo Início Recebimento Proposta não foi informado'
                                ]
                            );
                        ?>
                    </div>
                    <div id="containHorariosAbertura" class="col-12 col-sm-2 form-group mb-2" style="display: none;">
                        <label for="l20_horaaberturaprop">Hora Abertura:</label>
                        <?php
                            db_input(
                                'l20_horaaberturaprop',
                                4,
                                '',
                                true,
                                'time',
                                1,
                                "",
                                '',
                                '',
                                '',
                                '',
                                'form-control',
                                [
                                    'validate-time' => 'false',
                                    'validate-time-message' => 'O campo Hora Abertura não foi informado',
                                    'validate-required-message' => 'O campo Hora Abertura não foi informado'
                                ],
                                date('H:i')
                            );
                        ?>
                    </div>
                </div>
                <div class="row" style="justify-content: center;">
                    <div class="col-12 col-sm-3 form-group mb-2">
                        <label for="l20_dataaber">Emissão/Alt.Edital/Convite:</label>
                        <?php
                            db_input(
                                'l20_dataaber',
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
                                    'validate-date-message' => 'O campo de Data de Emissão/Alteração de Edital/Convite não foi informado',
                                    'validate-required-message' => 'O campo de Data de Emissão/Alteração de Edital/Convite não foi informado'
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-2 form-group mb-2">
                        <label for="l20_horaaber">Hora Emissão/Alt:</label>
                        <?php
                            db_input(
                                'l20_horaaber',
                                4,
                                '',
                                true,
                                'time',
                                1,
                                "",
                                '',
                                '',
                                '',
                                '',
                                'form-control',
                                [
                                    'validate-required' => 'true',
                                    'validate-time' => 'true',
                                    'validate-time-message' => 'O campo de Hora de Emissão/Alteração de Edital/Convite não foi informado',
                                    'validate-required-message' => 'O campo de Hora de Emissão/Alteração de Edital/Convite não foi informado'
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-1"></div>
                    <div id="containDataEncerramento" class="col-12 col-sm-3 form-group mb-2" style="display: none;">
                        <label for="l20_dataencproposta">Final Recebimento Proposta:</label>
                        <?php
                            db_input(
                                'l20_dataencproposta',
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
                                    'validate-date' => 'false',
                                    'validate-date-message' => 'O campo Final Recebimento Proposta não foi informado',
                                    'validate-required-message' => 'O campo Final Recebimento Proposta não foi informado'
                                ]
                            );
                        ?>
                    </div>
                    <div id="containHorariosEncerramento" class="col-12 col-sm-2 form-group mb-2" style="display: none;">
                        <label for="l20_horaencerramentoprop">Hora Encerramento:</label>
                        <?php
                            db_input(
                                'l20_horaencerramentoprop',
                                4,
                                '',
                                true,
                                'time',
                                1,
                                "",
                                '',
                                '',
                                '',
                                '',
                                'form-control',
                                [
                                    'validate-required' => 'false',
                                    'validate-time' => 'false',
                                    'validate-time-message' => 'O campo Hora Encerramento está inválido',
                                    'validate-required-message' => 'O campo Hora Encerramento não foi informado'
                                ],
                                date('H:i')
                            );
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <div class="accordion-header">Detalhamento do Julgamento</div>
            <div class="accordion-content">
                <div class="row">
                    <div class="col-12 col-sm-3 form-group mb-2">
                        <label for="l20_tipojulg">Tipo de Julgamento:</label>
                        <select
                            name="l20_tipojulg"
                            id="l20_tipojulg"
                            class="custom-select"
                            data-validate-required="true"
                            data-validate-required-message="O campo Tipo de Julgamento não foi informado"
                        >
                            <option value="">Selecione</option>
                            <option value="1">Por item</option>
                            <option value="3">Por lote</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-3 form-group mb-2">
                        <label for="l20_tipliticacao">Critério de Julgamento:</label>
                        <select
                            name="l20_tipliticacao"
                            id="l20_tipliticacao"
                            class="custom-select"
                            data-validate-required="true"
                            data-validate-required-message="O campo Critério de Julgamento não foi informado"
                        >
                            <option value="">Selecione</option>
                        </select>
                    </div>
                    <div id="containNaturezaProcedimento" class="col-12 col-sm-3 form-group mb-2">
                        <label for="l20_tipnaturezaproced">Natureza do Procedimento:</label>
                        <select
                            name="l20_tipnaturezaproced"
                            id="l20_tipnaturezaproced"
                            class="custom-select"
                            data-validate-required="true"
                            data-validate-required-message="O campo Natureza do Procedimento não foi informado"
                        >
                            <option value="">Selecione</option>
                        </select>
                    </div>
                    <div id="containCriterioAdjudicacao" class="col-12 col-sm-3 form-group mb-2">
                        <label for="l20_criterioadjudicacao">Critério de Adjudicação:</label>
                        <select
                            name="l20_criterioadjudicacao"
                            id="l20_criterioadjudicacao"
                            class="custom-select"
                            data-validate-required="true"
                            data-validate-required-message="O campo Critério de Adjudicação não foi informado"
                        >
                            <option value="">Selecione</option>
                        </select>
                    </div>
                    <div id="containModoDisputa" class="col-12 col-sm-3 form-group mb-2" style="display:none;">
                        <label for="l20_mododisputa">Modo de Disputa:</label>
                        <select
                            name="l20_mododisputa"
                            id="l20_mododisputa"
                            class="custom-select"
                            data-validate-required="true"
                            data-validate-required-message="O campo Modo de Disputa não foi informado"
                        >
                            <option value="">Selecione</option>
                            <option value="1">Aberto</option>
                            <option value="2">Fechado</option>
                            <option value="3">Conjunto</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-4 form-group mb-2">
                        <label for="l20_inversaofases">Inversão de Fases:</label>
                        <select
                            name="l20_inversaofases"
                            id="l20_inversaofases"
                            class="custom-select"
                            data-validate-required="true"
                            data-validate-required-message="O campo Inversão de Fases não foi informado"
                        >
                            <option value="">Selecione</option>
                            <option value="1">1 - Julgamento antes da habilitação</option>
                            <option value="2">2 - Habilitação antes do julgamento</option>
                        </select>
                    </div>
                    <div id="containDescricaCriterioAdjudicacao" class="col-12 col-sm-12 form-group mb-2">
                        <label for="l20_descrcriterio">Descrição Critério de Adjudicação:</label>
                        <?php
                            db_textarea(
                                'l20_descrcriterio',
                                0,
                                1,
                                '',
                                true,
                                'text',
                                '1',
                                'onkeydown="disabledQuebraLinha(event)"',
                                '',
                                '',
                                150,
                                'form-control-plaintext form-control-lg',
                                [
                                    'validate-required' => 'true',
                                    'validate-minlength' => '10',
                                    'validate-no-special-chars' => 'true',
                                    'validate-required-message' => 'O campo Descrição Critério de Adjudicação não foi informado',
                                    'validate-minlength-message' => 'O Descrição Critério de Adjudicação deve ter pelo menos 10 caracteres',
                                    'validate-no-special-chars-message' => 'O Descrição Critério de Adjudicação não deve conter aspas simples, ponto e vírgula ou porcentagem'
                                ]
                            );
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item" id="containDetalhamentoPNCP" style="display: none;">
            <div class="accordion-header">Detalhamento PNCP</div>
            <div class="accordion-content">
                <div class="row">
                    <div class="col-12 col-sm-4 form-group mb-2">
                        <label for="l20_amparolegal">Amparo Legal:</label>
                        <select
                            name="l20_amparolegal"
                            id="l20_amparolegal"
                            class="custom-select"
                            data-validate-required="false"
                            data-validate-required-message="O campo Amparo Legal não foi informado"
                        >
                            <option value="">Selecione</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-4 form-group mb-2">
                        <label for="l20_categoriaprocesso">Categoria do Processo:</label>
                        <select
                            name="l20_categoriaprocesso"
                            id="l20_categoriaprocesso"
                            class="custom-select"
                            data-validate-required="false"
                            data-validate-required-message="O campo Categoria do Processo não foi informado"
                        >
                            <option value="">Selecione</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-4 form-group mb-2">
                        <label for="l20_receita">Receita:</label>
                        <select
                            name="l20_receita"
                            id="l20_receita"
                            class="custom-select"
                            data-validate-required="false"
                            data-validate-required-message="O campo Receita não foi informado"
                        >
                            <option value="">Selecione</option>
                            <option value="t" selected>Despesa</option>
                            <option value="f">Receita</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <div class="accordion-header">Detalhamento para ME e EPP</div>
            <div class="accordion-content">
                <div class="row">
                    <div class="col-12 col-sm-3 mb-2 form-group">
                        <label for="l20_critdesempate">Critério de Desempate:</label>
                        <select
                            name="l20_critdesempate"
                            id="l20_critdesempate"
                            class="custom-select"
                            data-validate-required="true"
                            data-validate-required-message="O campo Critério de Desempate não foi informado"
                        >
                            <option value="">Selecione</option>
                            <option value="1">Sim</option>
                            <option value="2">Não</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-3 mb-2 form-group">
                        <label for="l20_destexclusiva">Destinação Exclusiva:</label>
                        <select
                            name="l20_destexclusiva"
                            id="l20_destexclusiva"
                            class="custom-select"
                            data-validate-required="true"
                            data-validate-required-message="O campo Destinação Exclusiva não foi informado"
                        >
                            <option value="">Selecione</option>
                            <option value="1">Sim</option>
                            <option value="2">Não</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-3 mb-2 form-group">
                        <label for="l20_subcontratacao">Subcontratação:</label>
                        <select
                            name="l20_subcontratacao"
                            id="l20_subcontratacao"
                            class="custom-select"
                            data-validate-required="true"
                            data-validate-required-message="O campo Subcontratação não foi informado"
                        >
                            <option value="">Selecione</option>
                            <option value="1">Sim</option>
                            <option value="2">Não</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-3 mb-2 form-group">
                        <label for="l20_limitcontratacao">Limite de Contratação:</label>
                        <select
                            name="l20_limitcontratacao"
                            id="l20_limitcontratacao"
                            class="custom-select"
                            data-validate-required="true"
                            data-validate-required-message="O campo Limite de Contratação não foi informado"
                        >
                            <option value="">Selecione</option>
                            <option value="1">Sim</option>
                            <option value="2">Não</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <div class="accordion-header">Outras Informações</div>
            <div class="accordion-content">
                <div class="row">
                    <div class="col-12 col-sm-12 form-group mb-2">
                        <label for="l20_objeto">Objeto:</label>
                        <?php
                            db_textarea(
                                'l20_objeto',
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
                                    'validate-required' => 'true',
                                    'validate-minlength' => '10',
                                    'validate-no-special-chars' => 'true',
                                    'validate-required-message' => 'O campo Objeto não foi informado',
                                    'validate-minlength-message' => 'O Objeto deve ter pelo menos 10 caracteres',
                                    'validate-no-special-chars-message' => 'O Objeto não deve conter aspas simples, ponto e vírgula ou porcentagem'
                                ]
                            );
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 form-group mb-2">
                        <label for="l20_condicoespag">Condições de Pagamento:</label>
                        <?php
                            db_textarea(
                                'l20_condicoespag',
                                0,
                                1,
                                '',
                                true,
                                'text',
                                '1',
                                'onkeydown="disabledQuebraLinha(event)"',
                                '',
                                '',
                                80,
                                'form-control-plaintext form-control-sm',
                                [
                                    'validate-required' => 'true',
                                    'validate-no-special-chars' => 'true',
                                    'validate-required-message' => 'O campo Condições de Pagamento não foi informado',
                                    'validate-no-special-chars-message' => 'O campo Condições de Pagamento não deve conter aspas simples, ponto e vírgula ou porcentagem'
                                ]
                            );
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 form-group mb-2">
                        <label for="l20_clausulapro">Cláusula Prorrogação:</label>
                        <?php
                            db_textarea(
                                'l20_clausulapro',
                                0,
                                1,
                                '',
                                true,
                                'text',
                                '1',
                                'onkeydown="disabledQuebraLinha(event)"',
                                '',
                                '',
                                250,
                                'form-control-plaintext form-control-sm opcional',
                                [
                                    'validate-no-special-chars' => 'true',
                                    'validate-no-special-chars-message' => 'O campo Cláusula Prorrogação não deve conter aspas simples, ponto e vírgula ou porcentagem'
                                ]
                            );
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 form-group mb-2">
                        <label for="l20_aceitabilidade">Critério de Aceitabilidade:</label>
                        <?php
                            db_textarea(
                                'l20_aceitabilidade',
                                0,
                                1,
                                '',
                                true,
                                'text',
                                '1',
                                'onkeydown="disabledQuebraLinha(event)"',
                                '',
                                '',
                                80,
                                'form-control-plaintext form-control-sm opcional',
                                [
                                    'validate-no-special-chars' => 'true',
                                    'validate-no-special-chars-message' => 'O campo Critério de Aceitabilidade não deve conter aspas simples, ponto e vírgula ou porcentagem'
                                ]
                            );
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 form-group mb-2">
                        <label for="l20_validadeproposta">Validade da Proposta:</label>
                        <?php
                            db_textarea(
                                'l20_validadeproposta',
                                0,
                                1,
                                '',
                                true,
                                'text',
                                '1',
                                'onkeydown="disabledQuebraLinha(event)"',
                                '',
                                '',
                                250,
                                'form-control-plaintext form-control-sm opcional',
                                [
                                    'validate-no-special-chars' => 'true',
                                    'validate-no-special-chars-message' => 'O campo Validade da Proposta não deve conter aspas simples, ponto e vírgula ou porcentagem'
                                ]
                            );
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 form-group mb-2">
                        <label for="l20_prazoentrega">Prazo Entrega:</label>
                        <?php
                            db_textarea(
                                'l20_prazoentrega',
                                0,
                                1,
                                '',
                                true,
                                'text',
                                '1',
                                'onkeydown="disabledQuebraLinha(event)"',
                                '',
                                '',
                                80,
                                'form-control-plaintext form-control-sm opcional',
                                [
                                    'validate-no-special-chars' => 'true',
                                    'validate-no-special-chars-message' => 'O campo Prazo Entrega não deve conter aspas simples, ponto e vírgula ou porcentagem'
                                ]
                            );
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 form-group mb-2">
                        <label for="l20_local">Local da Licitação:</label>
                        <?php
                            db_textarea(
                                'l20_local',
                                0,
                                1,
                                '',
                                true,
                                'text',
                                '1',
                                'onkeydown="disabledQuebraLinha(event)"',
                                '',
                                '',
                                80,
                                'form-control-plaintext form-control-sm opcional',
                                [
                                    'validate-no-special-chars' => 'true',
                                    'validate-no-special-chars-message' => 'O campo Local da Licitação não deve conter aspas simples, ponto e vírgula ou porcentagem'
                                ]
                            );
                        ?>
                    </div>
                </div>
                <div class="row" id="containJustificativaProcessoPresencial" style="display: none;">
                    <div class="col-12 col-sm-12 form-group mb-2">
                        <label for="l20_justificativapncp">
                            Justificativa Processo Presencial <div id="tooltip-default-info-justificativa" class="tooltip-default tooltip-default-right" style="display: inline-block">
                                                <i class="icon exclamation-circle info" style="margin: 0px;"></i>
                                                <span class="tooltip-default-text tooltip-default-sm">
                                                    Justificativa pela escolha da modalidade presencial.
                                                </span>
                                            </div>:
                        </label>
                        <?php
                            db_textarea(
                                'l20_justificativapncp',
                                0,
                                1,
                                '',
                                true,
                                'text',
                                '1',
                                'onkeydown="disabledQuebraLinha(event)"',
                                '',
                                '',
                                80,
                                'form-control-plaintext form-control-lg',
                                [
                                    'validate-required' => 'false',
                                    // 'validate-minlength' => '10',
                                    'validate-no-special-chars' => 'false',
                                    'validate-required-message' => 'O campo Justificativa Processo Presencial não foi informado',
                                    'validate-minlength-message' => 'A Justificativa Processo Presencial deve ter pelo menos 10 caracteres',
                                    'validate-no-special-chars-message' => 'A Justificativa Processo Presencial não deve conter aspas simples, ponto e vírgula ou porcentagem'
                                ]
                            );
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center mt-4 mb-2">
            <?php if(!empty($l20_codigo)): ?>
                <button type="submit" class="btn btn-success" id="btnAtualizarLicitacao">Alterar</button>
            <?php else: ?>
                <button type="submit" class="btn btn-success" id="btnSalvarLicitacao">Incluir</button>
            <?php endif; ?>
            <button class="btn btn-danger" type="button" id="btnCancelarLicitacao">Cancelar</button>
        </div>
    </div>
</form>
<!-- opcional -->
<script>
    (function (){
        const btnSalvar = document.getElementById('btnSalvarLicitacao');
        const btnAtualizar = document.getElementById('btnAtualizarLicitacao');
        const btnCancelar = document.getElementById('btnCancelarLicitacao');

        const selectModalidade = document.getElementById('l20_codtipocom');
        const selectNaturezaObjeto = document.getElementById('l20_naturezaobjeto');
        const selectLeiLicitacao = document.getElementById('l20_leidalicitacao');
        const selectRegimeExecucao = document.getElementById('l20_regimexecucao');
        const selectProcessoSistema = document.getElementById('lprocsis');
        const selectCategoriaProcesso = document.getElementById('l20_categoriaprocesso');
        const selectAmparoLegal = document.getElementById('l20_amparolegal');
        const selectCriterioJulgamento = document.getElementById('l20_tipliticacao');
        const selectNaturezaProcedimento = document.getElementById('l20_tipnaturezaproced');
        const selectCriterioAdjudicacao = document.getElementById('l20_criterioadjudicacao');

        const inputProcessoLicitatorio = document.getElementById('l20_numero');
        const inputNumerocao = document.getElementById('l20_edital');
        const inputEdital = document.getElementById('l20_nroedital');

        const inputUnidEntrega = document.getElementById('l20_execucaoentrega');
        const inputNumeroConvidado = document.getElementById('l20_numeroconvidado');
        const inputProcesso = document.getElementById('l34_protprocesso');
        const inputProcessoAdm = document.getElementById('l20_procadmin');

        const validator = initializeValidation('#frmLicitacoes');
        const eventChange = new Event('change');
        const eventInput = new Event('input');
        const formatter = (new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 4, maximumFractionDigits: 4 }));

        let oParamNumManual = null;

        const loadings = {
            modalidade: false,
            natureza_objeto: false,
            regime_execucao: false,
            amparo_legal: false,
            categoria_processo: false,
            criterio_adjudicacao: false,
            natureza_procedimento: false,
            creterio_julgamento: false,
            licitacao: false,
            verify: true
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

                if(verificaEstados(target) && loadings.verify){
                    loadings.verify = false;
                    closeSwal();
                }
                return true;
            }
        };

        const estadosProxy = new Proxy(loadings, handler);

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

        function validateChange(id, value = true) {
            const inputElement = document.getElementById(id);

            if (!inputElement) return false;

            const validator = initializeValidationInput(inputElement);

            const isValid = validator.validate();
            if (!isValid) {
                return false;
            }

            return true;
        }

        if (l20_codepartamento) {
            l20_codepartamento.addEventListener('input', function (e) {
                if (event.target.value === '') {
                    document.getElementById('l20_descricaodep').value = '';
                    return false;
                }
                validateChangeInteger('l20_codepartamento');
            });
        }

        if (respConducodigo) {
            respConducodigo.addEventListener('input', function (e) {
                if (event.target.value === '') {
                    document.getElementById('respConducodigonome').value = '';
                    return false;
                }
                validateChangeInteger('respConducodigo');
            });
        }

        if (respAutocodigo) {
            respAutocodigo.addEventListener('input', function (e) {
                if (event.target.value === '') {
                    document.getElementById('respAutonome').value = '';
                    return false;
                }
                validateChangeInteger('respAutocodigo');
            });
        }

        if (respObrascodigo) {
            respObrascodigo.addEventListener('input', function (e) {
                if (event.target.value === '') {
                    document.getElementById('respObrasnome').value = '';
                    return false;
                }
                validateChangeInteger('respObrascodigo');
            });
        }

        if (respEditalcodigo) {
            respEditalcodigo.addEventListener('input', function (e) {
                if (event.target.value === '') {
                    document.getElementById('respEditalnome').value = '';
                    return false;
                }
                validateChangeInteger('respEditalcodigo');
            });
        }

        if (inputProcesso) {
            inputProcesso.addEventListener('input', function (e) {
                if (event.target.value === '') {
                    document.getElementById('l34_protprocessodescr').value = '';
                    return false;
                }
                validateChangeInteger('l34_protprocesso');
            });
        }

        if(inputUnidEntrega){
            inputUnidEntrega.addEventListener('input', function(e){
                if(event.target.value.length > 4){
                    validateChangeInteger('l20_execucaoentrega');
                    event.target.value = event.target.value.substring(0, 4);
                    return false;
                }
                validateChangeInteger('l20_execucaoentrega');
            });
        }

        if(inputNumeroConvidado){
            inputNumeroConvidado.addEventListener('input', function(e){
                if(event.target.value.length > 3){
                    validateChangeInteger('l20_numeroconvidado');
                    event.target.value = event.target.value.substring(0, 3);
                    return false;
                }
                validateChangeInteger('l20_numeroconvidado');
            });
        }

        if(inputProcessoAdm){
            inputProcessoAdm.addEventListener('input', function(e){
                if(event.target.value.length > 150){
                    validateChange('l20_procadmin');
                    event.target.value = event.target.value.substring(0, 150);
                    return false;
                }
                validateChange('l20_procadmin');
            });
        }

        function disabledQuebraLinha(event){
            if(event.key === 'Enter'){
                event.preventDefault();
            }
        }

        function pesquisaDepartamento(mostra){
            if (mostra == true) {
                var sUrl = 'func_db_depart.php?funcao_js=parent.carregaDepartamento|coddepto|descrdepto';
                if(typeof db_iframe_db_depart != 'undefined'){
                    db_iframe_db_depart.jan.location.href = sUrl;
                    db_iframe_db_depart.setAltura("100%");
                    db_iframe_db_depart.setLargura("100%");
                    db_iframe_db_depart.setPosX("0");
                    db_iframe_db_depart.setPosY("0");
                    db_iframe_db_depart.setCorFundoTitulo('#316648');
                    db_iframe_db_depart.show();
                    return false;
                }

                let frame = js_OpenJanelaIframe(
                    '',
                    'db_iframe_db_depart',
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
                    frame.setCorFundoTitulo('#316648');
                    db_iframe_db_depart.show();
                }
            } else {
                if (document.form1.l20_codepartamento.value != '' && validateChangeInteger('l20_codepartamento')) {
                    js_OpenJanelaIframe(
                        '',
                        'db_iframe_db_depart',
                        'func_db_depart.php?pesquisa_chave=' + document.form1.l20_codepartamento.value + '&funcao_js=parent.carregaDepartamento',
                        'Pesquisar licitação Outro Órgão',
                        false,
                        '0'
                    );
                }
            }
        }

        function carregaDepartamento(cod, desc, error = false){
            if(typeof desc == 'boolean' && !desc){
                document.getElementById('l20_descricaodep').value = cod;
                validateChangeInteger('l20_codepartamento')
                db_iframe_db_depart.hide();
                return false;
            }

            if(typeof desc == 'boolean' && desc){
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'Nenhum departamento foi encontrado.',
                });
                document.getElementById('l20_codepartamento').value = '';
                document.getElementById('l20_descricaodep').value = '';
                return false;
            }

            document.getElementById('l20_codepartamento').value = cod;
            document.getElementById('l20_descricaodep').value = desc;
            validateChangeInteger('l20_codepartamento');
            db_iframe_db_depart.hide();
        }

        function pesquisaCgm(mostra, num, nome){
            numCampo = num;
            nomeCampo = nome;
            if(mostra == true){
                let sUrl = 'func_nome.php?funcao_js=parent.carregaCgmAncora|z01_numcgm|z01_nome&filtro=1';
                if(typeof db_iframe_licitacoes != 'undefined'){
                    db_iframe_licitacoes.jan.location.href = sUrl;
                    db_iframe_licitacoes.setAltura("100%");
                    db_iframe_licitacoes.setLargura("100%");
                    db_iframe_licitacoes.setPosX("0");
                    db_iframe_licitacoes.setPosY("0");
                    db_iframe_licitacoes.setCorFundoTitulo('#316648');
                    db_iframe_licitacoes.show();
                    return false;
                }

                let frame = js_OpenJanelaIframe(
                    '',
                    'db_iframe_licitacoes',
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
                    frame.setCorFundoTitulo('#316648');
                    db_iframe_licitacoes.show();
                }
            } else{
                if(!validateChangeInteger(numCampo)){
                    return false;
                }

                numcgm = document.getElementById(numCampo).value;
                if (numcgm != '') {
                    js_OpenJanelaIframe(
                        '',
                        'db_iframe_licitacoes',
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
            db_iframe_licitacoes.hide();
        }

        function carregaCgm(erro, chave){
            document.getElementById(nomeCampo).value = chave;
            if (erro == true) {
                document.getElementById(numCampo).value = "";
                document.getElementById(nomeCampo).value = "";
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'Responsável não encontrado!',
                });
            }
        }

        function pesquisaEquipePregao(mostra){
            if(mostra == true){
                let sUrl = 'func_licpregao.php?funcao_js=parent.carregaPregaoAncora|l45_sequencial|l45_tipo';
                if(typeof db_iframe_licpregao != 'undefined'){
                    db_iframe_licpregao.jan.location.href = sUrl;
                    db_iframe_licpregao.setAltura("100%");
                    db_iframe_licpregao.setLargura("100%");
                    db_iframe_licpregao.setPosX("0");
                    db_iframe_licpregao.setPosY("0");
                    db_iframe_licpregao.setCorFundoTitulo('#316648');
                    db_iframe_licpregao.show();
                    return false;
                }

                let frame = js_OpenJanelaIframe(
                    '',
                    'db_iframe_licpregao',
                    sUrl,
                    'Pesquisar Comissão',
                    false,
                    '0'
                );

                if(frame){
                    frame.setAltura("100%");
                    frame.setLargura("100%");
                    frame.setPosX("0");
                    frame.setPosY("0");
                    frame.hide();
                    db_iframe_licpregao.setCorFundoTitulo('#316648')
                    db_iframe_licpregao.show();
                }
            } else if(document.getElementById('l20_equipepregao').value != ""){
                let sUrl = `func_licpregao.php?pesquisa_chave=${document.getElementById('l20_equipepregao').value}&funcao_js=parent.carregaPregao&sCampoRetorno=l20_equipepregao`
                if(typeof db_iframe_licpregao_desc != 'undefined'){
                    db_iframe_licpregao_desc.jan.location.href = sUrl;
                    return false;
                }

                let frame = js_OpenJanelaIframe(
                    '',
                    'db_iframe_licpregao_desc',
                    sUrl,
                    'Pesquisar Comissão',
                    false,
                    '0'
                );

            } else {
                document.getElementById('l20_codepartamentodesc').value = '';
            }
        }

        function carregaPregao(iCodigoProcesso, lErro, sNome){
            if(lErro){
                document.getElementById('l20_equipepregao').value = '';
                document.getElementById('l20_codepartamentodesc').value = '';
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'Comissão não encontrada!',
                });
                return false;
            }
            document.getElementById('l20_equipepregao').value = iCodigoProcesso;
            document.getElementById('l20_codepartamentodesc').value = sNome;
            validaPregao();
        }

        function carregaPregaoAncora(iCodigoProcesso, sNome, lErro){
            db_iframe_licpregao.hide();
            document.getElementById('l20_equipepregao').value = iCodigoProcesso;
            document.getElementById('l20_codepartamentodesc').value = sNome;
            validaPregao();
        }

        async function validaPregao(){
            let modalidade = selectModalidade.options[selectModalidade.selectedIndex].getAttribute('l03_pctipocompratribunal');
            let lei = selectLeiLicitacao.value;
            let aModalidades = {
                52 : "pregao",
                53 : "pregao",
                48 : "outros",
                49 : "outros",
                50 : "outros",
                51 : "outros",
                // 54 : "outros",
                // 104: "outros",
                // 110: "outros"
            };
            let descModalidade = aModalidades[modalidade];

            if(!lei || !document.getElementById('l20_equipepregao').value || !descModalidade){
                return false;
            }

            if(lei != '1'){
                verificaMembrosModalidade(descModalidade)
                return;
            }

            let validaModalidade = await validaMembrosModalidadeLei(descModalidade);

            if(!validaModalidade){
                if(descModalidade == 'pregao'){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção!',
                        text: 'Para as modalidades Pregão Presencial e Pregão Eletrônico é necessário que a Comissão de Licitação tenha o tipo Pregoeiro.',
                    });
                    document.getElementById('l20_equipepregao').value = '';
                    document.getElementById('l20_codepartamentodesc').value = '';
                    return false;
                }

                if(descModalidade == 'outros'){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção!',
                        text: 'Para as modalidades Concurso e Concorrência é necessário que a Comissão de Licitação tenha os tipos Agente de Contratação ou Comissão de Contratação.',
                    });
                    document.getElementById('l20_equipepregao').value = '';
                    document.getElementById('l20_codepartamentodesc').value = '';
                    return false;
                }
            }
        }

        async function verificaMembrosModalidade(modalidade){
            let oParam = {};
            oParam.exec = 'VerificaMembrosModalidade',
            oParam.equipepregao = document.getElementById('l20_equipepregao').value;
            oParam.modalidade = modalidade;

            await new Ajax.Request(
                'lic4_licitacao.RPC.php',
                {
                    method: 'post',
                    parameters: 'json=' + Object.toJSON(oParam),
                    asynchronous: false,
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        if(modalidade == 'pregao' && oRetorno.validaMod == 0){
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção!',
                                text: 'Para as modalidades Pregão presencial e Pregão eletrônico é necessário\nque a Comissão de Licitação tenham os tipos Pregoeiro e Membro da Equipe de Apoio',
                            });
                            document.getElementById('l20_equipepregao').value = '';
                            document.getElementById('l20_codepartamentodesc').value = '';
                            return false;
                        }

                        if(modalidade == 'outros' && oRetorno.validaMod == 0){
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção!',
                                text: 'Para as modalidades Tomada de Preços, Concorrência, Concurso e Convite é necessário que a Comissão de Licitação tenham os tipos Secretário, Presidente e Membro da Equipe de Apoio.',
                            });
                            document.getElementById('l20_equipepregao').value = '';
                            document.getElementById('l20_codepartamentodesc').value = '';
                            return false;
                        }
                    }
                }
            );
        }

        async function validaMembrosModalidadeLei(modalidade){
            let oParam = {};
            oParam.exec = 'VerificaMembrosModalidadeParaLei1';
            oParam.comissao = document.getElementById('l20_equipepregao').value;
            oParam.modalidade = modalidade;
            let validacao;

            new Ajax.Request(
                'lic4_licitacao.RPC.php',
                {
                    method: 'post',
                    parameters: 'json=' + Object.toJSON(oParam),
                    asynchronous: false,
                    onComplete: function(oAjax) {
                        let oRetorno = JSON.parse(oAjax.responseText);
                        validacao = oRetorno.validacao;
                    }
                }
            );

            return validacao;
        }

        function pesquisaProtProcesso(mostra){
            if (mostra == true) {
                let sUrl = `func_protprocesso_protocolo.php?funcao_js=parent.loadProtProcessoAncora|p58_numero|p58_codproc|dl_nome_ou_razão_social`;
                if(typeof db_iframe_licpregao != 'undefined'){
                    db_iframe_licpregao.jan.location.href = sUrl;
                    db_iframe_licpregao.setAltura("100%");
                    db_iframe_licpregao.setLargura("100%");
                    db_iframe_licpregao.setPosX("0");
                    db_iframe_licpregao.setPosY("0");
                    db_iframe_licpregao.setCorFundoTitulo('#316648');
                    db_iframe_licpregao.show();
                    return false;
                }

                let frame = js_OpenJanelaIframe(
                    '',
                    'db_iframe_licpregao',
                    sUrl,
                    'Pesquisa Processo da Licitação',
                    false,
                    "0"
                );

                if(frame){
                    frame.setAltura("100%");
                    frame.setLargura("100%");
                    frame.setPosX("0");
                    frame.setPosY("0");
                    frame.hide();
                    frame.setCorFundoTitulo('#316648');
                    db_iframe_licpregao.show();
                }
            } else {
                let sUrl = `func_protprocesso_protocolo.php?pesquisa_chave=' + document.form1.l34_protprocesso.value + '&funcao_js=parent.loadProtProcesso&sCampoRetorno=p58_codproc&sCampoPesquisa=p58_codproc&sDesconsideraAno=t`;
                if(typeof db_iframe_licpregao != 'undefined'){
                    db_iframe_licpregao.jan.location.href = sUrl;
                    return false;
                }

                let frame = js_OpenJanelaIframe(
                    '',
                    'db_iframe_licpregao',
                    sUrl,
                    'Pesquisa Processo da Licitação',
                    false
                );
            }
        }

        function loadProtProcessoAncora(iNumeroProcesso, iCodigoProcesso, sNome){
            document.getElementById('l34_protprocesso').value = iCodigoProcesso;
            document.getElementById('l34_protprocessodescr').value = sNome;
            db_iframe_licpregao.hide();
        }

        function loadProtProcesso(iCodigoProcesso, sNome, lErro){
            document.getElementById('l34_protprocessodescr').value = sNome;

            if (lErro) {
                document.getElementById('l34_protprocesso').value = '';
                return false;
            }

            document.getElementById('l34_protprocesso').value = iCodigoProcesso;

            db_iframe_licpregao.hide();
        }

        async function getModalidade(l20_codtipocom = null, triggerChange = false, l20_leidalicitacao = null){
            let oParam = {};
            oParam.exec = 'listagemModalidade';
            if(l20_leidalicitacao == '1'){
                oParam.l03_pctipocompratribunal = [51, 50, 110, 54, 53, 52];
            } else if(l20_leidalicitacao == '2'){
                oParam.l03_pctipocompratribunal = [48, 51, 50, 54, 53, 52, 49, 104];
            } else {
                oParam.l03_pctipocompratribunal = [48, 51, 50, 110, 54, 53, 52, 49, 104];
            }

            await new Ajax.Request(
                url,
                {
                    method: 'post',
                    asynchronous: true,
                    parameters: 'json=' + JSON.stringify(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        selectModalidade.innerHTML = '<option value="">Selecione</option>';

                        oRetorno.data.tipoprocesso.forEach((oValue, iSeq) => {
                            const option = document.createElement('option');
                            option.value = oValue.l03_codigo;
                            option.text = oValue.l03_descr;
                            option.setAttribute('l03_pctipocompratribunal', oValue.l03_pctipocompratribunal);
                            option.setAttribute('l03_presencial', oValue.l03_presencial);

                            if(l20_codtipocom == oValue.l03_codigo){
                                option.selected = true;
                            }

                            selectModalidade.appendChild(option);
                        });

                        if(triggerChange){
                            selectModalidade.dispatchEvent(eventChange);
                        }

                        estadosProxy.modalidade = true;
                    }
                }
            );
        }

        async function getNaturezaObjeto(l20_naturezaobjeto = null, triggerChange = false){
            let oParam = {};
            oParam.exec = 'listagemNaturezaObjeto';
            await new Ajax.Request(
                url,
                {
                    method: 'post',
                    asynchronous: true,
                    parameters: 'json=' + JSON.stringify(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        Object.entries(oRetorno.data).forEach(([iSeq, oValue]) => {
                            const option = document.createElement('option');
                            option.value = iSeq;
                            option.text = oValue;

                            if(l20_naturezaobjeto == iSeq){
                                option.selected = true;
                            }

                            selectNaturezaObjeto.appendChild(option);
                        })

                        if(triggerChange){
                            selectNaturezaObjeto.dispatchEvent(eventChange);
                        }

                        estadosProxy.natureza_objeto = true;
                    }
                }
            );
        }

        async function getRegimeExecucao(l20_regimexecucao = null, l20_leidalicitacao = null, triggerChange = false){
            selectRegimeExecucao.innerHTML = '<option value="">Selecione</option>';

            let oParam = {};
            oParam.exec = 'listagemRegimeExecucao';
            oParam.l20_leidalicitacao = l20_leidalicitacao;
            let oAjax = await new Ajax.Request(
                'lic_dispensasinexigibilidades.RPC.php',
                {
                    method: 'post',
                    asynchronous: true,
                    parameters: 'json=' + JSON.stringify(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        Object.entries(oRetorno.data).forEach(([iSeq, oValue]) => {
                            const option = document.createElement('option');
                            option.value = iSeq;
                            option.text = oValue;

                            if(l20_regimexecucao == parseInt(iSeq)){
                                option.selected = true;
                            }

                            selectRegimeExecucao.appendChild(option);
                        });

                        if(triggerChange){
                            selectRegimeExecucao.dispatchEvent(eventChange);
                        }

                        estadosProxy.regime_execucao = true;
                    }
                }
            )
        }

        async function getNumeros(l20_codtipocom){
            let oParam = {};
            oParam.exec = 'getNumeros';
            oParam.l20_codtipocom = l20_codtipocom;
            oParam.l20_codigo = l20_codigo;
            await new Ajax.Request(
                'lic_dispensasinexigibilidades.RPC.php',
                {
                    method: 'post',
                    asynchronous: true,
                    parameters: 'json=' + JSON.stringify(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        inputProcessoLicitatorio.value = oRetorno.data.processo || '0';
                        inputNumerocao.value = oRetorno.data.numeracao || '0';
                        inputEdital.value = oRetorno.data.edital || '0';


                    }
                }
            );
        }

        async function getCategoriaProcesso(l20_categoriaprocesso = null, triggerChange = false) {
            let oParam = {};
            oParam.exec = 'listagemCategoriaProcesso';
            let oAjax = await new Ajax.Request(
                'lic_dispensasinexigibilidades.RPC.php',
                {
                    method: 'post',
                    asynchronous: true,
                    parameters: 'json=' + JSON.stringify(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        Object.entries(oRetorno.data).forEach(([iSeq, oValue]) => {
                            const option = document.createElement('option');
                            option.value = iSeq;
                            option.text = oValue;

                            if(l20_categoriaprocesso == iSeq){
                                option.selected = true;
                            }

                            selectCategoriaProcesso.appendChild(option);
                        })

                        if(triggerChange){
                            selectCategoriaProcesso.dispatchEvent(eventChange);
                        }

                        estadosProxy.categoria_processo = true;
                    }
                }
            )
        }

        async function getAmparoLegal(l20_codtipocom = null, l20_amparolegal = null, triggerChange = false){
            let oParam = {};
            oParam.exec = 'listagemAmparoLegal';
            oParam.l20_codtipocom = l20_codtipocom;
            await new Ajax.Request(
                'lic_dispensasinexigibilidades.RPC.php',
                {
                    method: 'post',
                    asynchronous: true,
                    parameters: 'json=' + JSON.stringify(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        selectAmparoLegal.innerHTML = '<option value="">Selecione</option>';

                        if(!oRetorno.data || oRetorno.data.length <= 0){
                            estadosProxy.amparo_legal = true;
                            return false;
                        }

                        oRetorno.data.amparolegal.forEach((oValue, iSeq) => {
                            const option = document.createElement('option');
                            option.value = oValue.l212_codigo;
                            option.text = oValue.l212_lei;

                            if(l20_amparolegal == oValue.l212_codigo){
                                option.selected = true;
                            }

                            selectAmparoLegal.appendChild(option);
                        });

                        if(triggerChange){
                            selectAmparoLegal.dispatchEvent(eventChange);
                        }

                        estadosProxy.amparo_legal = true;
                    }
                }
            );
        }

        async function getCriterioJulgamento(l20_tipliticacao = null, triggerChange = false, l03_pctipocompratribunal = null, l20_leidalicitacao = null) {
            selectCriterioJulgamento.innerHTML = '<option value="">Selecione</option>';
            let oParam = {};
            oParam.exec = 'listagemCriterioJugamento';
            oParam.l03_pctipocompratribunal = l03_pctipocompratribunal;
            oParam.l20_leidalicitacao = l20_leidalicitacao;
            await new Ajax.Request(
                'lic_dispensasinexigibilidades.RPC.php',
                {
                    method: 'post',
                    asynchronous: true,
                    parameters: 'json=' + JSON.stringify(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        Object.entries(oRetorno.data).forEach(([iSeq, oValue]) => {
                            const option = document.createElement('option');
                            option.value = iSeq;
                            option.text = oValue;

                            if(l20_tipliticacao == iSeq){
                                option.selected = true;
                            }

                            selectCriterioJulgamento.appendChild(option);
                        });

                        if(triggerChange){
                            selectCriterioJulgamento.dispatchEvent(eventChange);
                        }

                        if(Object.entries(oRetorno.data).length == 1){
                            selectCriterioJulgamento.selectedIndex = 1;
                        }

                        estadosProxy.creterio_julgamento = true;
                    }
                }
            )
        }

        async function getNaturezaProcedimento(l20_tipliticacao = null, triggerChange = false) {
            let oParam = {};
            oParam.exec = 'listagemNaturezaProcedimento';
            await new Ajax.Request(
                'lic_dispensasinexigibilidades.RPC.php',
                {
                    method: 'post',
                    asynchronous: true,
                    parameters: 'json=' + JSON.stringify(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        Object.entries(oRetorno.data).forEach(([iSeq, oValue]) => {
                            const option = document.createElement('option');
                            option.value = iSeq;
                            option.text = oValue;

                            if(l20_tipliticacao && l20_tipliticacao == iSeq){
                                option.selected = true;
                            }

                            selectNaturezaProcedimento.appendChild(option);
                        })

                        if(triggerChange){
                            selectNaturezaProcedimento.dispatchEvent(eventChange);
                        }

                        estadosProxy.natureza_procedimento = true;
                    }
                }
            )
        }

        async function getCriterioAdjudicacao(l20_criterioadjudicacao = 3, triggerChange = false) {
            let oParam = {};
            oParam.exec = 'listagemCriterioAdjudicacao';
            await new Ajax.Request(
                'lic_dispensasinexigibilidades.RPC.php',
                {
                    method: 'post',
                    asynchronous: true,
                    parameters: 'json=' + JSON.stringify(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        Object.entries(oRetorno.data).forEach(([iSeq, oValue]) => {
                            const option = document.createElement('option');
                            option.value = iSeq;
                            option.text = oValue;

                            if(l20_criterioadjudicacao == iSeq){
                                option.selected = true;
                            }

                            selectCriterioAdjudicacao.appendChild(option);
                        })

                        if(triggerChange){
                            selectCriterioAdjudicacao.dispatchEvent(eventChange);
                        }

                        estadosProxy.criterio_adjudicacao = true;
                    }
                }
            )
        }

        function validateToggleObras(processo, enabledEdit = true){
            const containRespObras = document.getElementById('containRespOrcObrasServicos').querySelectorAll('input');
            if(processo == 1){
                document.getElementById('containRespOrcObrasServicos').style.display = 'block';
                containRespObras.forEach(input => {
                    if(input.id == 'respObrascodigo'){
                        input.setAttribute('data-validate-integer', true);
                        input.setAttribute('data-validate-maxlength', 10);
                    }

                    input.setAttribute('data-validate-no-special-chars', true);
                    input.setAttribute('data-validate-required', true);
                });
                return false;
            }

            document.getElementById('containRespOrcObrasServicos').style.display = 'none';
            const inputs = document.getElementById('containRespOrcObrasServicos').querySelectorAll('input');
            const selects = document.getElementById('containRespOrcObrasServicos').querySelectorAll('select');
            containRespObras.forEach(input => {
                if(input.id == 'respObrascodigo'){
                    input.setAttribute('data-validate-integer', false);
                    input.removeAttribute('data-validate-maxlength');
                }

                input.setAttribute('data-validate-no-special-chars', false);
                input.setAttribute('data-validate-required', false);
            });

            inputs.forEach(input => {
                if(enabledEdit){
                    input.value = '';
                }
            });

            selects.forEach(select => {
                select.selectedIndex = 0;
            });
        }

        function validateToggleConvidados(l03_pctipocompratribunal = null, enabledEdit = true){
            const inputs = document.getElementById('containNumeroConvidados').querySelectorAll('input');
            if(['48'].includes(l03_pctipocompratribunal)){
                document.getElementById('containNumeroConvidados').style.display = 'block';
                inputs.forEach(input => {
                    input.setAttribute('data-validate-required', true);
                    input.setAttribute('data-validate-no-special-chars', true);
                    input.setAttribute('data-validate-integer', true);
                    input.setAttribute('data-validate-minlength', 1);
                    input.setAttribute('data-validate-maxlength', 3);
                });
                return false;
            }

            document.getElementById('containNumeroConvidados').style.display = 'none';
            inputs.forEach(input => {
                if(enabledEdit){
                    input.value = '';
                }

                input.setAttribute('data-validate-required', false);
                input.setAttribute('data-validate-no-special-chars', false);
                input.setAttribute('data-validate-integer', false);
                input.removeAttribute('data-validate-minlength');
                input.removeAttribute('data-validate-maxlength');
            });
        }

        function validateToggleProcessoLicitacao(processo, enabledEdit = true){
            const inputs = document.getElementById('containProcessoLicitacao').querySelectorAll('input');
            const selects = document.getElementById('containProcessoLicitacao').querySelectorAll('select');

            if(processo == 's'){
                document.getElementById('containProcessoLicitacao').style.display = 'block';
                document.getElementById('l20_procadmin').readOnly = true;
                inputs.forEach(input => {
                    if(input.id == 'l34_protprocesso'){
                        input.setAttribute('data-validate-no-special-chars', true);
                        input.setAttribute('data-validate-integer', true);
                        input.setAttribute('data-validate-minlength', 1);
                        input.setAttribute('data-validate-maxlength', 10);
                    }
                    input.setAttribute('data-validate-required', true);
                });
                return false;
            }

            document.getElementById('containProcessoLicitacao').style.display = 'none';
            document.getElementById('l20_procadmin').readOnly = false;
            inputs.forEach(input => {
                if(enabledEdit){
                    input.value = '';
                }

                if(input.id == 'l34_protprocesso'){
                    input.setAttribute('data-validate-no-special-chars', false);
                    input.setAttribute('data-validate-integer', false);
                    input.removeAttribute('data-validate-minlength');
                    input.removeAttribute('data-validate-maxlength');
                }
                input.setAttribute('data-validate-required', false);
            });

            selects.forEach(select => {
                select.selectedIndex = 0;
            });
        }

        function validateToggleDetalhamentoPncp(hide = false){
            if(hide){
                document.getElementById('containDetalhamentoPNCP').style.display = 'none';
                const containSelectDetalhamento = document.getElementById('containDetalhamentoPNCP').querySelectorAll('select');
                containSelectDetalhamento.forEach(select => {
                    select.selectedIndex = 0;
                    select.setAttribute('data-validate-required', false);
                });
                return false;
            }

            document.getElementById('containDetalhamentoPNCP').style.display = 'block';
            const containSelectDetalhamento = document.getElementById('containDetalhamentoPNCP').querySelectorAll('select');
            containSelectDetalhamento.forEach(select => {
                if(select.id == 'l20_receita'){
                    select.selectedIndex = 1;
                } else {
                    select.selectedIndex = 0;
                }
                select.setAttribute('data-validate-required', true);
            });
            return false;
        }

        function validateToggleNaturezaProcedimento(l20_leidalicitacao, enabledEdit = true){
            const selects = document.getElementById('containNaturezaProcedimento').querySelectorAll('select');
            if(['1'].includes(l20_leidalicitacao)){
                document.getElementById('containNaturezaProcedimento').style.display = 'block';
                selects.forEach(select => {
                    select.setAttribute('data-validate-required', true);
                });
                return false;
            }

            document.getElementById('containNaturezaProcedimento').style.display = 'none';
            selects.forEach(select => {
                if(enabledEdit){
                    select.selectedIndex = 0;
                }
                select.setAttribute('data-validate-required', false);
            });
        }

        function validateToggleModoDisputa(l20_leidalicitacao, enabledEdit = true){
            const selects = document.getElementById('containModoDisputa').querySelectorAll('select');
            if(['1'].includes(l20_leidalicitacao)){
                document.getElementById('containModoDisputa').style.display = 'block';
                selects.forEach(select => {
                    if(enabledEdit){
                        select.selectedIndex = 0;
                    }
                    select.setAttribute('data-validate-required', true);
                });
                return false;
            }

            document.getElementById('containModoDisputa').style.display = 'none';
            selects.forEach(select => {
                if(enabledEdit){
                    select.selectedIndex = 0;
                }
                select.setAttribute('data-validate-required', false);
            });
        }

        function validadeToggleCriterioAdjudicacao(processo = null, enabledEdit = true){
            const selects = document.getElementById('containCriterioAdjudicacao').querySelectorAll('select');
            if(processo && ['2'].includes(processo)){
                document.getElementById('containCriterioAdjudicacao').style.display = 'block';
                selects.forEach(select => {
                    if(enabledEdit){
                        select.selectedIndex = 3;
                    }
                    select.setAttribute('data-validate-required', true);
                });

                return false;
            }

            document.getElementById('containCriterioAdjudicacao').style.display = 'none';
            selects.forEach(select => {
                if(enabledEdit){
                    select.selectedIndex = 0;
                }
                select.setAttribute('data-validate-required', false);
            });
        }

        function validateToggleDataAbertura(l20_leidalicitacao, enabledEdit = true){
            const inputs = document.getElementById('containDataAbertura').querySelectorAll('input');
            if(oParamNumManual.l12_pncp && ['1'].includes(l20_leidalicitacao)){
                document.getElementById('containDataAbertura').style.display = 'block';
                inputs.forEach(input => {
                    input.setAttribute('data-validate-required', true);
                });
                return false;
            }
            document.getElementById('containDataAbertura').style.display = 'none';
            inputs.forEach(input => {
                if(enabledEdit){
                    input.value = '';
                }
                input.setAttribute('data-validate-required', false);
            });
        }

        function validateToggleDataEncerramento(l20_leidalicitacao, enabledEdit = true){
            const inputs = document.getElementById('containDataEncerramento').querySelectorAll('input');
            if(oParamNumManual.l12_pncp && ['1'].includes(l20_leidalicitacao)){
                document.getElementById('containDataEncerramento').style.display = 'block';
                inputs.forEach(input => {
                    input.setAttribute('data-validate-required', true);
                });
                return false;
            }
            document.getElementById('containDataEncerramento').style.display = 'none';
            inputs.forEach(input => {
                if(enabledEdit){
                    input.value = '';
                }
                input.setAttribute('data-validate-required', false);
            });
        }

        function validateToggleHorariosAbertura(l20_leidalicitacao, enabledEdit = true){
            const inputs = document.getElementById('containHorariosAbertura').querySelectorAll('input');
            if(oParamNumManual.l12_pncp && ['1'].includes(l20_leidalicitacao)){
                document.getElementById('containHorariosAbertura').style.display = 'block';
                inputs.forEach(input => {
                    input.setAttribute('data-validate-required', true);
                    input.setAttribute('data-validate-time', true);
                });
                return false;
            }
            document.getElementById('containHorariosAbertura').style.display = 'none';
            inputs.forEach(input => {
                if(enabledEdit){
                    input.value = '';
                }
                input.setAttribute('data-validate-required', false);
                input.setAttribute('data-validate-time', false);
            });
        }

        function validateToggleHorariosEncerramento(l20_leidalicitacao, enabledEdit = true){
            const inputs = document.getElementById('containHorariosEncerramento').querySelectorAll('input');
            if(oParamNumManual.l12_pncp && ['1'].includes(l20_leidalicitacao)){
                document.getElementById('containHorariosEncerramento').style.display = 'block';
                inputs.forEach(input => {
                    input.setAttribute('data-validate-required', true);
                    input.setAttribute('data-validate-time', true);
                });
                return false;
            }
            document.getElementById('containHorariosEncerramento').style.display = 'none';
            inputs.forEach(input => {
                if(enabledEdit){
                    input.value = '';
                }
                input.setAttribute('data-validate-required', false);
                input.setAttribute('data-validate-time', false);
            });
        }

        function validateToggleNaturezaObjeto(l03_pctipocompratribunal = null, enabledEdit = true){
            const selects = document.getElementById('containNaturezaObjeto').querySelectorAll('select');
            if(['48','50','110','54','53','52','49','104'].includes(l03_pctipocompratribunal)){
                document.getElementById('containNaturezaObjeto').style.display = 'block';
                selects.forEach(select => {
                    if(enabledEdit){
                        // select.value = '';
                        select.dispatchEvent(eventChange);
                    }

                    select.setAttribute('data-validate-required', true);
                });
                return false;
            }

            document.getElementById('containNaturezaObjeto').style.display = 'none';
            selects.forEach(select => {
                if(enabledEdit){
                    select.value = '';
                    select.selectedIndex = 0;
                    select.dispatchEvent(eventChange);
                }

                select.setAttribute('data-validate-required', false);
            });
        }

        function validateToggleProcessoPresencial(l20_leidalicitacao, l03_presencial, enabledEdit = true){
            const textareaProcessoPresencial = document.getElementById('containJustificativaProcessoPresencial').querySelectorAll('textarea');
            if(oParamNumManual.l12_pncp && ['1'].includes(l20_leidalicitacao) && l03_presencial == 'true'){
                document.getElementById('containJustificativaProcessoPresencial').style.display = 'block';
                textareaProcessoPresencial.forEach(input => {
                    input.setAttribute('data-validate-required', true);
                    input.setAttribute('data-validate-no-special-chars', true);
                    input.setAttribute('data-validate-minlength', 10);
                });

                return false;
            }

            document.getElementById('containJustificativaProcessoPresencial').style.display = 'none';
            textareaProcessoPresencial.forEach(input => {
                if(enabledEdit){
                    input.value = '';
                }
                input.setAttribute('data-validate-required', false);
                input.setAttribute('data-validate-no-special-chars', false);
                input.removeAttribute('data-validate-minlength');
            });
        }

        function validateToggleRegimeExecucao(processo, enabledEdit = true){
            const selects = document.getElementById('containRegimeExecucao').querySelectorAll('select');
            if(['1', '7'].includes(processo)){
                document.getElementById('containRegimeExecucao').style.display = 'block';
                selects.forEach(select => {
                    if(enabledEdit){
                        select.selectedIndex = 0;
                    }
                    select.setAttribute('data-validate-required', true);
                });
                return false;
            }

            document.getElementById('containRegimeExecucao').style.display = 'none';
            selects.forEach(select => {
                if(enabledEdit){
                    select.selectedIndex = 0;
                }
                select.setAttribute('data-validate-required', false);
            });
        }

        function validateToggleDescricaoCriteriAdjudicaca(l20_criterioadjudicacao, enabledEdit = true){
            const texteareaDescricaCriteriAdjudicacao = document.getElementById('containDescricaCriterioAdjudicacao').querySelectorAll('textarea');
            if(['3'].includes(l20_criterioadjudicacao)){
                document.getElementById('containDescricaCriterioAdjudicacao').style.display = 'block';
                texteareaDescricaCriteriAdjudicacao.forEach(input => {
                    input.setAttribute('data-validate-required', true);
                    input.setAttribute('data-validate-no-special-chars', true);
                    input.setAttribute('data-validate-minlength', 10);
                });

                return false;
            }

            document.getElementById('containDescricaCriterioAdjudicacao').style.display = 'none';
            texteareaDescricaCriteriAdjudicacao.forEach(input => {
                if(enabledEdit){
                    input.value = '';
                }
                input.setAttribute('data-validate-required', false);
                input.setAttribute('data-validate-no-special-chars', false);
                input.removeAttribute('data-validate-minlength');
            });
        }

        if(selectModalidade != null){
            selectModalidade.addEventListener('change', function(){
                const value = selectModalidade.value;
                inputProcessoLicitatorio.value = '';
                inputNumerocao.value = '';
                inputEdital.value = '';

                if(value == ''){
                    return false;
                }

                validateToggleConvidados(
                    selectModalidade.options[selectModalidade.selectedIndex].getAttribute('l03_pctipocompratribunal')
                );
                getCriterioJulgamento(
                    selectCriterioJulgamento.value,
                    false,
                    selectModalidade.options[selectModalidade.selectedIndex].getAttribute('l03_pctipocompratribunal'),
                    selectLeiLicitacao.value
                );
                validateToggleNaturezaObjeto(
                    selectModalidade.options[selectModalidade.selectedIndex].getAttribute('l03_pctipocompratribunal')
                );

                validateToggleProcessoPresencial(
                    selectLeiLicitacao.value,
                    selectModalidade.options[selectModalidade.selectedIndex].getAttribute('l03_presencial')
                );

                getNumeros(value);
                getAmparoLegal(
                    value,
                    selectAmparoLegal.value
                );

                validaPregao();
            });
        }

        if(selectNaturezaObjeto != null){
            selectNaturezaObjeto.addEventListener('change', function(e){
                e.preventDefault();
                const value = selectNaturezaObjeto.value;
                validateToggleObras(value);
                validateToggleRegimeExecucao(value);
            });
        }

        if(selectProcessoSistema != null){
            selectProcessoSistema.addEventListener('change', function(e){
                e.preventDefault();
                const value = selectProcessoSistema.value;
                document.getElementById('l20_procadmin').value = '';
                validateToggleProcessoLicitacao(value);
            });
        }

        if(selectLeiLicitacao != null){
            selectLeiLicitacao.addEventListener('change', function(e){
                e.preventDefault();
                const value = selectLeiLicitacao.value;

                if(oParamNumManual.l12_pncp){
                    if(value == 2){
                        validateToggleDetalhamentoPncp(true);
                    } else {
                        validateToggleDetalhamentoPncp();
                    }
                }

                // validateToggleNaturezaProcedimento(value);
                getModalidade(null, true, value);

                validateToggleModoDisputa(value);
                validateToggleDataAbertura(value);
                validateToggleDataEncerramento(value);
                validateToggleHorariosAbertura(value);
                validateToggleHorariosEncerramento(value);
                validateToggleProcessoPresencial(
                    selectLeiLicitacao.value,
                    selectModalidade.options[selectModalidade.selectedIndex].getAttribute('l03_presencial')
                );
                getCriterioJulgamento(
                    null,
                    false,
                    selectModalidade.options[selectModalidade.selectedIndex].getAttribute('l03_pctipocompratribunal'),
                    selectLeiLicitacao.value
                )

                getRegimeExecucao(null, selectLeiLicitacao.value);
                validaPregao();
            })
        }

        if(selectNaturezaProcedimento != null){
            selectNaturezaProcedimento.addEventListener('change', function(e){
                e.preventDefault();
                const value = selectNaturezaProcedimento.value;
                // validadeToggleCriterioAdjudicacao(value);
                validateToggleDescricaoCriteriAdjudicaca(selectCriterioAdjudicacao.value);
            });
        }

        if(selectCriterioAdjudicacao != null){
            selectCriterioAdjudicacao.addEventListener('change', function(e){
                e.preventDefault();
                validateToggleDescricaoCriteriAdjudicaca(selectCriterioAdjudicacao.value);
            });
        }

        if(btnSalvar != null){
            btnSalvar.addEventListener('click', function(e){
                e.preventDefault();
                const formData = serializarFormulario(document.getElementById('frmLicitacoes'));
                const isValid = validator.validate();
                if(!isValid){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção!',
                        text: 'Preencha todos os campos corretamente.',
                    });
                    return false;
                }

                if(!validaDatas()){
                    return false;
                }

                if(!validaRegimeExecucao()){
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
                            let oRetorno = JSON.parse(oAjax.responseText);
                            if(oRetorno.status == 200){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sucesso!',
                                    text: 'Licitação salva com sucesso!',
                                });

                                let currentUrl = new URL(window.location.href);
                                parent.loadingLicitacoes();
                                currentUrl.searchParams.set('l20_codigo', oRetorno.data.licitacao.l20_codigo);
                                window.location.href = currentUrl.toString();
                                return false;
                            }

                            if(oRetorno.status == 400){
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Atenção!',
                                    html: oRetorno.message,
                                });
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

        if(btnAtualizar != null){
            btnAtualizar.addEventListener('click', function(e){
                e.preventDefault();
                const formData = serializarFormulario(document.getElementById('frmLicitacoes'));
                const isValid = validator.validate();
                if(!isValid){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção!',
                        text: 'Preencha todos os campos corretamente.',
                    });
                    return false;
                }

                if(!validaDatas()){
                    return false;
                }

                if(!validaRegimeExecucao()){
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
                                    text: 'Licitação salva com sucesso!',
                                });

                                let currentUrl = new URL(window.location.href);
                                parent.loadingLicitacoes();
                                currentUrl.searchParams.set('l20_codigo', oRetorno.data.licitacao.l20_codigo);
                                window.location.href = currentUrl.toString();
                                return false;
                            }

                            if(oRetorno.status == 400){
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Atenção!',
                                    html: oRetorno.message,
                                });
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

        if(btnCancelar != null){
            btnCancelar.addEventListener('click', function(e){
                e.preventDefault();
                parent.closeLicitacao(false);
                return false;
            });
        }

        function init(){
            getModalidade();
            getNaturezaObjeto();
            getRegimeExecucao();
            getCriterioJulgamento();
            getNaturezaProcedimento();
            getCriterioAdjudicacao();
            getCategoriaProcesso();
            getAmparoLegal();
            estadosProxy.licitacao = true
        }

        function loadEdit(){
            let oParam = {};
            oParam.exec = 'getLicitacaoByCodigo';
            oParam.l20_codigo = l20_codigo;
            new Ajax.Request(
                url,
                {
                    method: 'post',
                    parameters: 'json=' + Object.toJSON(oParam),
                    onComplete: async function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        let licitacao = oRetorno.data.licitacao;

                        getModalidade(licitacao.l20_codtipocom, false, (licitacao.l20_leidalicitacao ? licitacao.l20_leidalicitacao.toString() : null));

                        document.getElementById('l20_codigo').value = licitacao.l20_codigo;
                        document.getElementById('l20_codepartamento').value = licitacao.l20_codepartamento;
                        document.getElementById('l20_descricaodep').value = licitacao.l20_descricaodep;
                        document.getElementById('respAutocodigo').value = licitacao.respAutocodigo;
                        document.getElementById('respAutonome').value = licitacao.respAutonome;
                        document.getElementById('respEditalcodigo').value = licitacao.respEditalcodigo;
                        document.getElementById('respEditalnome').value = licitacao.respEditalnome;
                        document.getElementById('l20_leidalicitacao').value = licitacao.l20_leidalicitacao;

                        document.getElementById('l20_edital').value = licitacao.l20_edital;
                        document.getElementById('l20_numero').value = licitacao.l20_numero;
                        document.getElementById('l20_nroedital').value = licitacao.l20_nroedital;

                        getNaturezaObjeto(licitacao.l20_naturezaobjeto);

                        document.getElementById('l20_execucaoentrega').value = licitacao.l20_execucaoentrega;
                        document.getElementById('l20_diames').value = licitacao.l20_diames;
                        document.getElementById('l20_numeroconvidado').value = licitacao.l20_numeroconvidado;

                        document.getElementById('respObrascodigo').value = licitacao.respObrascodigo;
                        document.getElementById('respObrasnome').value = licitacao.respObrasnome;

                        document.getElementById('l20_equipepregao').value = licitacao.l45_sequencial;
                        document.getElementById('l20_codepartamentodesc').value = licitacao.l45_tipo;

                        document.getElementById('respConducodigo').value = licitacao.respConducaocodigo;
                        document.getElementById('respConducodigonome').value = licitacao.respConducaonome;

                        getRegimeExecucao(licitacao.l20_regimexecucao, licitacao.l20_leidalicitacao.toString(), false);

                        document.getElementById('lprocsis').value = (licitacao.l34_protprocessodescr != null) ? 's' : 'n';

                        document.getElementById('l20_procadmin').value = licitacao.l20_procadmin;

                        document.getElementById('l34_protprocesso').value = licitacao.p58_numero;
                        document.getElementById('l34_protprocessodescr').value = licitacao.l34_protprocessodescr;

                        document.getElementById('l20_datacria').value = licitacao.l20_datacria;
                        document.getElementById('l20_horacria').value = licitacao.l20_horacria;
                        document.getElementById('l20_dataaberproposta').value = licitacao.l20_dataaberproposta;
                        document.getElementById('l20_horaaberturaprop').value = licitacao.l20_horaaberturaprop;
                        document.getElementById('l20_dataencproposta').value = licitacao.l20_dataencproposta;
                        document.getElementById('l20_horaencerramentoprop').value = licitacao.l20_horaencerramentoprop;
                        document.getElementById('l20_dataaber').value = licitacao.l20_dataaber;
                        document.getElementById('l20_horaaber').value = licitacao.l20_horaaber;

                        document.getElementById('l20_tipojulg').value = licitacao.l20_tipojulg;

                        getCriterioJulgamento(
                            licitacao.l20_tipliticacao,
                            false,
                            (licitacao.l03_pctipocompratribunal ? licitacao.l03_pctipocompratribunal.toString() : null),
                            (licitacao.l20_leidalicitacao ? licitacao.l20_leidalicitacao.toString() : null)
                        )
                        getNaturezaProcedimento(licitacao.l20_tipnaturezaproced);
                        getCriterioAdjudicacao(licitacao.l20_criterioadjudicacao || 3);
                        document.getElementById('l20_mododisputa').value = licitacao.l20_mododisputa;
                        document.getElementById('l20_inversaofases').value = licitacao.l20_inversaofases;
                        document.getElementById('l20_descrcriterio').value = licitacao.l20_descrcriterio;

                        getAmparoLegal(licitacao.l20_codtipocom, licitacao.l20_amparolegal);
                        getCategoriaProcesso(licitacao.l20_categoriaprocesso);
                        document.getElementById('l20_receita').value = licitacao.l20_receita ? 't' : 'f';

                        document.getElementById('l20_critdesempate').value = licitacao.l20_critdesempate;
                        document.getElementById('l20_destexclusiva').value = licitacao.l20_destexclusiva;
                        document.getElementById('l20_subcontratacao').value = licitacao.l20_subcontratacao;
                        document.getElementById('l20_limitcontratacao').value = licitacao.l20_limitcontratacao;

                        document.getElementById('l20_objeto').value = licitacao.l20_objeto;
                        document.getElementById('l20_condicoespag').value = licitacao.l20_condicoespag;
                        document.getElementById('l20_clausulapro').value = licitacao.l20_clausulapro;
                        document.getElementById('l20_aceitabilidade').value = licitacao.l20_aceitabilidade;
                        document.getElementById('l20_validadeproposta').value = licitacao.l20_validadeproposta;
                        document.getElementById('l20_prazoentrega').value = licitacao.l20_prazoentrega;
                        document.getElementById('l20_local').value = licitacao.l20_local;
                        document.getElementById('l20_justificativapncp').value = licitacao.l20_justificativapncp;

                        document.getElementById('l20_objeto').dispatchEvent(eventInput)
                        document.getElementById('l20_condicoespag').dispatchEvent(eventInput)
                        document.getElementById('l20_clausulapro').dispatchEvent(eventInput)
                        document.getElementById('l20_aceitabilidade').dispatchEvent(eventInput)
                        document.getElementById('l20_validadeproposta').dispatchEvent(eventInput)
                        document.getElementById('l20_prazoentrega').dispatchEvent(eventInput)
                        document.getElementById('l20_local').dispatchEvent(eventInput)
                        document.getElementById('l20_justificativapncp').dispatchEvent(eventInput)


                        validateToggleObras(licitacao.l20_naturezaobjeto ? licitacao.l20_naturezaobjeto.toString(): null, false);
                        validateToggleConvidados((licitacao.l03_pctipocompratribunal ? licitacao.l03_pctipocompratribunal.toString() : null), false);
                        validateToggleProcessoLicitacao((licitacao.l34_protprocessodescr != null) ? 's' : 'n', false);
                        // validateToggleNaturezaProcedimento((licitacao.l20_leidalicitacao ? licitacao.l20_leidalicitacao.toString() : null), false);
                        validateToggleModoDisputa((licitacao.l20_leidalicitacao ? licitacao.l20_leidalicitacao.toString() : null), false);
                        // validadeToggleCriterioAdjudicacao((licitacao.l20_tipnaturezaproced ? licitacao.l20_tipnaturezaproced.toString() : null), false);
                        validateToggleDataAbertura((licitacao.l20_leidalicitacao ? licitacao.l20_leidalicitacao.toString() : null), false);
                        validateToggleDataEncerramento((licitacao.l20_leidalicitacao ? licitacao.l20_leidalicitacao.toString() : null), false);
                        validateToggleHorariosAbertura((licitacao.l20_leidalicitacao ? licitacao.l20_leidalicitacao.toString() : null), false);
                        validateToggleHorariosEncerramento((licitacao.l20_leidalicitacao ? licitacao.l20_leidalicitacao.toString() : null), false);
                        validateToggleNaturezaObjeto((licitacao.l03_pctipocompratribunal ? licitacao.l03_pctipocompratribunal.toString() : null), false);
                        validateToggleProcessoPresencial(
                            (licitacao.l20_leidalicitacao ? licitacao.l20_leidalicitacao.toString() : null),
                            (licitacao.l03_presencial ? licitacao.l03_presencial.toString() : null),
                            false
                        );
                        validateToggleRegimeExecucao(licitacao.l20_naturezaobjeto ? licitacao.l20_naturezaobjeto.toString(): null, false);
                        validateToggleDescricaoCriteriAdjudicaca((licitacao.l20_criterioadjudicacao ? licitacao.l20_criterioadjudicacao.toString() : 3), false);
                        getProcessosVinculadosValidacao(licitacao.l20_tipojulg != 3);
                        if(oParamNumManual.l12_pncp){
                            if(licitacao.l20_leidalicitacao == 2){
                                validateToggleDetalhamentoPncp(true);
                            } else {
                                validateToggleDetalhamentoPncp();
                            }
                        }
                        validateToggleBlockTabLote((licitacao.l20_tipojulg ? licitacao.l20_tipojulg.toString() : null), oParamNumManual, licitacao.l20_tipnaturezaproced == 2);
                        
                        estadosProxy.licitacao = true
                    }
                }
            )
        }

        function validaDatas(){
            const dataAberturaProc = document.getElementById('l20_datacria').value + ' ' + document.getElementById('l20_horacria').value;
            const dataEmissaoEdital = document.getElementById('l20_dataaber').value + ' ' + document.getElementById('l20_horaaber').value;
            const dataInicioRecebimento = document.getElementById('l20_dataaberproposta').value + ' ' + document.getElementById('l20_horaaberturaprop').value;
            const dataFinalRecebimento = document.getElementById('l20_dataencproposta').value + ' ' + document.getElementById('l20_horaencerramentoprop').value;

            const dataPropostaVisible = ['flex', 'block'].includes(document.getElementById('containDataAbertura').style.display);
            const horaaberturaVisible = ['flex', 'block'].includes(document.getElementById('containHorariosAbertura').style.display);
            const horariosEncerramentoVisible = ['flex', 'block'].includes(document.getElementById('containHorariosEncerramento').style.display);

            if(
                dataPropostaVisible
                && horaaberturaVisible
                && dataAberturaProc
                && dataInicioRecebimento
                && dataInicioRecebimento < dataAberturaProc
            ){
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'A data e hora inseridas em \'Final Recebimento Proposta\' não podem ser anteriores à data e hora inseridas em \'Início Recebimento Proposta\'.',
                });
                return false;
            }

            if(
                dataPropostaVisible
                && horaaberturaVisible
                && horariosEncerramentoVisible
                && dataInicioRecebimento
                && dataFinalRecebimento
                && dataInicioRecebimento > dataFinalRecebimento
            ){
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'A data e hora inseridas em \'Final Recebimento Proposta\' não podem ser anteriores à data e hora inseridas em \'Início Recebimento Proposta\'.',
                });
                return false;
            }

            if(
                dataPropostaVisible
                && horaaberturaVisible
                && horariosEncerramentoVisible
                && dataInicioRecebimento
                && dataFinalRecebimento
                && dataInicioRecebimento == dataFinalRecebimento
            ){
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'As datas e horas inseridas nos campos \'Início Recebimento de Propostas\' e \'Final Recebimento de Propostas\' não podem ser iguais.',
                });
                return false;
            }

            if(
                dataEmissaoEdital
                && dataAberturaProc
                && dataAberturaProc > dataEmissaoEdital
            ){
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'A data e hora inseridas em \'Emissão/Alteração Edital/Convite\' não podem ser anteriores à data e hora inseridas em \'Abertura Proc. Adm\'.',
                });
                return false;
            }

            return true;
        }

        function validaRegimeExecucao(){
            const regimeExecucaoVisible = ['flex', 'block'].includes(document.getElementById('containRegimeExecucao').style.display);
            if(
                regimeExecucaoVisible 
                && ['1'].includes(document.getElementById('l20_naturezaobjeto').value)
                && ['5'].includes(document.getElementById('l20_regimexecucao').value)
            ){
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'O Regime de Execução selecionado não poderá ser utilizado para quando a Natureza do Objeto for Obras e Serviços de Engenharia.',
                });
                return false;
            }

            return true;
        }

        async function getParam(){
            Swal.fire({
                title: 'Aguarde...',
                text: 'Estamos processando sua solicitação.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            let oParam = {};
            oParam.exec = 'listagemLiclicitaParam';
            await new Ajax.Request(
                'lic_dispensasinexigibilidades.RPC.php',
                {
                    method: 'post',
                    asynchronous: true,
                    parameters: 'json=' + JSON.stringify(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        oParamNumManual = oRetorno.data;

                        if(oParamNumManual.l12_numeracaomanual){
                            inputProcessoLicitatorio.readOnly = false;
                            inputProcessoLicitatorio.style.backgroundColor = '';

                            inputNumerocao.readOnly = false;
                            inputNumerocao.style.backgroundColor = '';

                            inputEdital.readOnly = false;
                            inputEdital.style.backgroundColor = '';
                        }

                        if(l20_codigo != null && l20_codigo != ''){
                            loadEdit();
                        } else {
                            init();
                        }
                    }
                }
            );
        }

        getParam();

        window.pesquisaDepartamento = pesquisaDepartamento;
        window.carregaDepartamento = carregaDepartamento;
        window.pesquisaCgm = pesquisaCgm;
        window.carregaCgmAncora = carregaCgmAncora;
        window.carregaCgm = carregaCgm;
        window.pesquisaEquipePregao = pesquisaEquipePregao;
        window.carregaPregao = carregaPregao;
        window.carregaPregaoAncora = carregaPregaoAncora;
        window.pesquisaProtProcesso = pesquisaProtProcesso;
        window.loadProtProcessoAncora = loadProtProcessoAncora;
        window.loadProtProcesso = loadProtProcesso;
        window.disabledQuebraLinha = disabledQuebraLinha;
    })();
</script>
