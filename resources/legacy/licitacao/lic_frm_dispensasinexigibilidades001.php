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
<form action="" method="post" name="form1" id="frmDispensasInexigibilidades">
    <input type="hidden" name="exec" value="<?= !empty($l20_codigo)? 'updateDispensasInexigibilidade' : 'inserirDispensasInexigibilidade' ?>">
    <input type="hidden" id="l20_tipoprocesso" name="l20_tipoprocesso">
    <fieldset>
        <legend>Dispensas/Inexigibilidades</legend>
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
                                    'validate-maxlength-message'        => 'O Cód. Departamento deve ter no máximo 10 caracteres',
                                    'validate-no-special-chars-message' => 'O Cód. Departamento não deve conter aspas simples, ponto e vírgula ou porcentagem',
                                    'validate-integer-message'          => 'O campo Cód. Departamento deve conter apenas numeros'
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-9 mb-2">
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
                                    'validate-minlength-message' => "O nome deve ter pelo menos 1 caracteres",
                                ]
                            );
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-8 form-group">
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
                                    'validate-required-message'         => "O campo Resp. Aut. da Abertura não foi informado",
                                    'validate-minlength-message'        => "O código deve ter pelo menos 1 caracteres",
                                    'validate-maxlength-message'        => 'O Resp. Aut. da Abertura deve ter no máximo 10 caracteres',
                                    'validate-no-special-chars-message' => 'O Resp. Aut. da Abertura não deve conter aspas simples, ponto e vírgula ou porcentagem',
                                    'validate-integer-message'          => 'O campo Resp. Aut. da Abertura deve conter apenas numeros',
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
                                    'validate-required-message'  => "O campo Responsável pela autorização para abertura do procedimento de dispensa ou inexigibilidade não foi informado",
                                    'validate-minlength-message' => "O código deve ter pelo menos 1 caracteres",
                                ]
                            );
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4 form-group mb-2">
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
        </div>
        <div class="row">
            <div class="col-12 col-sm-6 form-group mb-2">
                <label for="l20_codtipocom">Tipo de Processo:</label>
                <select
                    name="l20_codtipocom"
                    id="l20_codtipocom"
                    class="custom-select"
                    data-validate-required="true"
                    data-validate-required-message="O campo Tipo de Processo não foi informado"
                >
                    <option value="">Selecione</option>
                </select>
            </div>
            <div class="col-12 col-lg-2 form-group mb-2">
                <label for="l20_edital">Número Processo:</label>
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
                            'validate-required-message'         => 'O campo Número Processo não foi informado',
                            'validate-maxlength-message'        => 'O Número Processo deve ter no máximo 16 caracteres',
                            'validate-no-special-chars-message' => 'O Número Processo não deve conter aspas simples, ponto e vírgula ou porcentagem',
                            'validate-integer-message'          => 'O campo Número Processo deve conter apenas numeros'
                        ]
                    );
                ?>
            </div>
            <div class="col-12 col-lg-2 form-group mb-2">
                <label for="l20_numero">Numeração Modalidade:</label>
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
                            'validate-maxlength-message'        => 'A Numeração deve ter no máximo 16 caracteres',
                            'validate-no-special-chars-message' => 'A Numeração não deve conter aspas simples, ponto e vírgula ou porcentagem',
                            'validate-integer-message'          => 'O campo Numeração deve conter apenas numeros'
                        ]
                    );
                ?>
            </div>
            <div id="containEdital" class="col-12 col-lg-2 form-group mb-2" style="display: none;">
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
                            'validate-required'                 => 'false',
                            'validate-no-special-chars'         => 'true',
                            'validate-integer'                  => 'true',
                            'validate-required-message'         => 'O campo Edital não foi informado',
                            'validate-maxlength-message'        => 'O Edital deve ter no máximo 16 caracteres',
                            'validate-no-special-chars-message' => 'O Edital não deve conter aspas simples, ponto e vírgula ou porcentagem',
                            'validate-integer-message'          => 'O campo Edital deve conter apenas numeros'
                        ]
                    );
                ?>
            </div>
        </div>
        <div class="row">
            <div id="containDispensaPorValor" class="col-12 col-sm-2 form-group mb-2" style="display: none;">
                <label for="l20_dispensaporvalor">Dispensa por Valor:</label>
                <select
                    name="l20_dispensaporvalor"
                    id="l20_dispensaporvalor"
                    class="custom-select"
                    data-validate-required="false"
                    data-validate-required-message="O campo Dispensa por Valor não foi informado"
                >
                    <option value="">Selecione</option>
                    <option value="t">Sim</option>
                    <option value="f">Não</option>
                </select>
            </div>
            <div class="col-12 col-sm-4 form-group mb-2">
                <label for="l20_naturezaobjeto">Natureza do Objeto:</label>
                <select
                    name="l20_naturezaobjeto"
                    id="l20_naturezaobjeto"
                    class="custom-select"
                    data-validate-required="true"
                    data-validate-required-message="O campo Natureza do Objeto não foi informado"
                >
                    <option value="">Selecione</option>
                </select>
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
            <div id="containRegimeExecucao" class="col-12 col-sm-4 form-group mb-2" style="display: none;">
                <label for="l20_regimexecucao">Regime de Execução</label>
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
            <div class="col-12 col-sm-2 form-group mb-2">
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
                        'form-control opcional'
                    );
                ?>
            </div>
            <div class="col-12 col-sm-8 form-group" id="containProcessoLicitacao" style="display: none;">
                <label for="l34_protprocesso"><?php db_ancora('Processo da Licitação:', "pesquisaProtProcesso(true);", 1); ?></label>
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
                                    'validate-maxlength'                => '10',
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
                    <div class="col-12 col-sm-8 mb-2">
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
                <div class="row">
                    <div class="col-12 col-sm-4 form-group mb-2">
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
                                    'validate-date-message' => 'Informe uma data válida'
                                ],
                                date('Y-m-d', db_getsession('DB_datausu'))
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-4 form-group mb-2">
                        <label for="l20_horacria">Hora Criação:</label>
                        <?php
                            db_input(
                                'l20_horacria',
                                4,
                                '',
                                true,
                                'time',
                                3,
                                "",
                                '',
                                '',
                                '',
                                '',
                                'form-control',
                                [
                                    'validate-time' => 'true',
                                    'validate-time-message' => 'Por favor, insira uma hora válida'
                                ],
                                date('H:i')
                            );
                        ?>
                    </div>
                </div>
                <div id="containRecebimento" class="row" style="display: none;">
                    <div class="col-12 col-sm-3 form-group mb-2">
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
                    <div id="containHorariosAbertura" class="col-12 col-sm-3 form-group mb-2" style="display: none;">
                        <label for="l20_horaaberturaprop">Hora inicio:</label>
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
                                    'validate-time-message' => 'O campo Hora inicio não foi informado',
                                    'validate-required-message' => 'O campo Hora inicio não foi informado'
                                ],
                                date('H:i')
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-3 form-group mb-2">
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
                    <div id="containHorariosEncerramento" class="col-12 col-sm-3 form-group mb-2" style="display: none;">
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
                            data-validate-required-message="O Tipo de Julgamento é obrigatório"
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
                    <div id="containNaturezaProcedimento" class="col-12 col-sm-3 form-group mb-2" style="display: none;">
                        <label for="l20_tipnaturezaproced">Natureza do Procedimento:</label>
                        <select
                            name="l20_tipnaturezaproced"
                            id="l20_tipnaturezaproced"
                            class="custom-select"
                            data-validate-required="false"
                            data-validate-required-message="O campo Natureza do Procedimento não foi informado"
                        >
                            <option value="">Selecione</option>
                        </select>
                    </div>
                    <div id="containCriterioAdjudicacao" class="col-12 col-sm-3 form-group mb-2" style="display: none;">
                        <label for="l20_criterioadjudicacao">Critério de Adjudicação:</label>
                        <select
                            name="l20_criterioadjudicacao"
                            id="l20_criterioadjudicacao"
                            class="custom-select"
                            data-validate-required="false"
                            data-validate-required-message="O campo Critério de Adjudicação não foi informado"
                        >
                            <option value="">Selecione</option>
                        </select>
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
                    <div class="col-12 col-sm-6 form-group mb-2">
                        <label for="l20_justificativa">
                            Justificativa  <div id="tooltip-default-info-justificativa" class="tooltip-default tooltip-default-right" style="display: inline-block">
                                                <i class="icon exclamation-circle info" style="margin: 0px;"></i>
                                                <span class="tooltip-default-text tooltip-default-sm">
                                                    Preencher a justificativa e fundamentação legal para contratação mediante Dispensa ou Inexigibilidade.
                                                </span>
                                            </div>:
                        </label>
                        <?php
                            db_textarea(
                                'l20_justificativa',
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
                                    'validate-required-message' => 'O campo Justificativa não foi informado',
                                    'validate-minlength-message' => 'A Justificativa deve ter pelo menos 10 caracteres',
                                    'validate-no-special-chars-message' => 'A Justificativa não deve conter aspas simples, ponto e vírgula ou porcentagem'
                                ]
                            );
                        ?>
                    </div>
                    <div class="col-12 col-sm-6 form-group mb-2">
                        <label for="l20_razao">
                            Razão <div id="tooltip-default-info-justificativa" class="tooltip-default tooltip-default-right" style="display: inline-block">
                                                <i class="icon exclamation-circle info" style="margin: 0px;"></i>
                                                <span class="tooltip-default-text tooltip-default-sm">
                                                    Preencher a razão da escolha do fornecedor ou executante.
                                                </span>
                                            </div>:
                        </label>
                        <?php
                            db_textarea(
                                'l20_razao',
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
                                    'validate-required-message' => 'O campo Razão não foi informado',
                                    'validate-minlength-message' => 'A Razão deve ter pelo menos 10 caracteres',
                                    'validate-no-special-chars-message' => 'A Razão não deve conter aspas simples, ponto e vírgula ou porcentagem'
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
                <button type="submit" class="btn btn-success" id="btnAtualizarDispensasInexigibilidades">Alterar</button>
            <?php else: ?>
                <button type="submit" class="btn btn-success" id="btnSalvarDispensasInexigibilidades">Incluir</button>
            <?php endif; ?>
            <button class="btn btn-danger" type="button" id="btnCancelarDispensasInexigibilidades">Cancelar</button>
        </div>
    </div>
</form>

<script>
    (function (){
        //estás váriaveis estão vindo do arquivo pai que chama o dynamic loader
        // const url = 'lic_dispensasinexigibilidades.RPC.php';
        // const l20_codigo = '<?= $l20_codigo ?>';
        const btnSalvarDispensasInexigibilidades = document.getElementById('btnSalvarDispensasInexigibilidades');
        const btnAtualizarDispensasInexigibilidades = document.getElementById('btnAtualizarDispensasInexigibilidades');
        const btnCancelarDispensasInexigibilidades = document.getElementById('btnCancelarDispensasInexigibilidades');

        const selectTipoProcesso = document.getElementById('l20_codtipocom');
        const selectNatureza = document.getElementById('l20_naturezaobjeto');
        const selectRegimeExecucao = document.getElementById('l20_regimexecucao');
        const selectCriterioJulgamento = document.getElementById('l20_tipliticacao');
        const selectNaturezaProcedimento = document.getElementById('l20_tipnaturezaproced');
        const selectCriterioAdjudicacao = document.getElementById('l20_criterioadjudicacao');
        const selectCategoriaProcesso = document.getElementById('l20_categoriaprocesso');
        const selectAmparoLegal = document.getElementById('l20_amparolegal');
        const selectProcessoSistema = document.getElementById('lprocsis');
        const selectTipoJulgamento = document.getElementById('l20_tipojulg');

        const selectLeiLicitacao = document.getElementById('l20_leidalicitacao');
        const inputProcessoLicitatorio = document.getElementById('l20_numero');
        const inputNumerocao = document.getElementById('l20_edital');
        const inputEdital = document.getElementById('l20_nroedital');
        const validator = initializeValidation('#frmDispensasInexigibilidades');
        const eventChange = new Event('change');
        const eventInput = new Event('input');
        const formatter = (new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 4, maximumFractionDigits: 4 }));
        const l20_codepartamento = document.getElementById('l20_codepartamento');
        const respAutocodigo = document.getElementById('respAutocodigo');
        const respObrascodigo = document.getElementById('respObrascodigo');
        const loadings = {
            dispensa: false,
            tipo_processo: false,
            natureza_objeto: false,
            regime_execucao: false,
            natureza_procedimento: false,
            criterio_adjudicacao: false,
            amparo_legal: false,
            categoria_processo: false,
            creterio_julgamento: false,
            verify: true
        };
        let oParamNumManual = null;
        let numCampo;
        let nomeCampo;

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

        function disabledQuebraLinha(event){
            if(event.key === 'Enter'){
                event.preventDefault();
            }
        }

        if (l20_codepartamento) {
            l20_codepartamento.addEventListener('input', function (e) {
                validateChangeInteger('l20_codepartamento');
            });
        }

        if (respAutocodigo) {
            respAutocodigo.addEventListener('input', function (e) {
                validateChangeInteger('respAutocodigo');
            });
        }

        if (respObrascodigo) {
            respObrascodigo.addEventListener('input', function (e) {
                validateChangeInteger('respObrascodigo');
            });
        }

        function pesquisaDepartamento(mostra){
            if (mostra == true) {
                var sUrl = 'func_db_depart.php?funcao_js=parent.carregaDepartamento|coddepto|descrdepto';
                if(typeof db_iframe_db_depart != 'undefined'){
                    db_iframe_db_depart.jan.location.href = sUrl;
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
                if(typeof db_iframe_dispensas_inexigibilidades != 'undefined'){
                    db_iframe_dispensas_inexigibilidades.jan.location.href = sUrl;
                    db_iframe_dispensas_inexigibilidades.show();
                    return false;
                }

                let frame = js_OpenJanelaIframe(
                    '',
                    'db_iframe_dispensas_inexigibilidades',
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
                    db_iframe_dispensas_inexigibilidades.show();
                }
            } else{
                if(!validateChangeInteger(numCampo)){
                    return false;
                }

                numcgm = document.getElementById(numCampo).value;
                if (numcgm != '') {
                    js_OpenJanelaIframe(
                        '',
                        'db_iframe_dispensas_inexigibilidades',
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
            db_iframe_dispensas_inexigibilidades.hide();
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

        function pesquisaProtProcesso(mostra){
            if (mostra == true) {
                js_OpenJanelaIframe('', 'db_iframe_proc', 'func_protprocesso_protocolo.php?funcao_js=parent.loadProtProcessoAncora|p58_numero|p58_codproc|dl_nome_ou_razão_social', 'Pesquisa', true, "0");
            } else {
                js_OpenJanelaIframe('', 'db_iframe_proc', 'func_protprocesso_protocolo.php?pesquisa_chave=' + document.form1.l34_protprocesso.value + '&funcao_js=parent.loadProtProcesso&sCampoRetorno=p58_codproc&sCampoPesquisa=p58_codproc&sDesconsideraAno=t', 'Pesquisa', false);
            }
        }

        function loadProtProcessoAncora(iNumeroProcesso, iCodigoProcesso, sNome){
            document.form1.l34_protprocesso.value = iCodigoProcesso;
            document.form1.l34_protprocessodescr.value = sNome;
            db_iframe_proc.hide();
        }

        function loadProtProcesso(iCodigoProcesso, sNome, lErro){
            document.form1.l34_protprocessodescr.value = sNome;

            if (lErro) {
                document.form1.l34_protprocesso.value = '';
                return false;
            }

            document.form1.l34_protprocesso.value = iCodigoProcesso;

            db_iframe_proc.hide();
        }

        function validateToggleEdital(l03_pctipocompratribunal){
            if(['102', '103'].includes(l03_pctipocompratribunal)){
                document.getElementById('containEdital').style.display = 'block';
                document.getElementById('l20_nroedital').setAttribute('data-validate-required', true);
                document.getElementById('l20_nroedital').setAttribute('data-validate-no-special-chars', true);
                document.getElementById('l20_nroedital').setAttribute('data-validate-integer', true);
                document.getElementById('l20_nroedital').setAttribute('data-validate-maxlength', 10);
                return false;
            }

            document.getElementById('l20_nroedital').setAttribute('data-validate-required', false);
            document.getElementById('l20_nroedital').setAttribute('data-validate-no-special-chars', false);
            document.getElementById('l20_nroedital').setAttribute('data-validate-integer', false);
            document.getElementById('l20_nroedital').removeAttribute('data-validate-maxlength');

            document.getElementById('containEdital').style.display = 'none';
        }

        function validateToggleDispensaValor(l20_leidalicitacao, l03_pctipocompratribunal, l20_tipnaturezaproced, enabledEdit = true){
            const selects = document.getElementById('containDispensaPorValor').querySelectorAll('select');
            if(['1'].includes(l20_leidalicitacao) && ['101'].includes(l03_pctipocompratribunal) && l20_tipnaturezaproced != 2){
                document.getElementById('containDispensaPorValor').style.display = 'block';
                selects.forEach(select => {
                    if(enabledEdit){
                        select.selectedIndex = 0;
                    }
                    select.setAttribute('data-validate-required', true);
                });
                return false;
            }

            selects.forEach(select => {
                if(enabledEdit){
                    select.selectedIndex = 0;
                }
                select.setAttribute('data-validate-required', false);
            });
            document.getElementById('containDispensaPorValor').style.display = 'none';
        }

        function validateToggleNaturezaProcedimento(l20_leidalicitacao, enabledEdit = true){
            const selectNaturezaProcedimento = document.getElementById('containNaturezaProcedimento').querySelectorAll('select');
            if(['1'].includes(l20_leidalicitacao)){
                document.getElementById('containNaturezaProcedimento').style.display = 'block';
                selectNaturezaProcedimento.forEach(select => {
                    if(enabledEdit){
                        select.selectedIndex = 0;
                    }
                    select.setAttribute('data-validate-required', true);
                });
                return false;
            }

            document.getElementById('containNaturezaProcedimento').style.display = 'none';
            selectNaturezaProcedimento.forEach(select => {
                if(enabledEdit){
                    select.selectedIndex = 0;
                }
                select.setAttribute('data-validate-required', false);
            });
        }

        function validateToggleRecebimento(l20_leidalicitacao, enabledEdit = true){
            const inputs = document.getElementById('containRecebimento').querySelectorAll('input');
            if(oParamNumManual.l12_pncp && ['1'].includes(l20_leidalicitacao)){
                document.getElementById('containRecebimento').style.display = 'flex';
                inputs.forEach(input => {
                    if(enabledEdit){
                        input.value = '';
                    }
                    input.setAttribute('data-validate-required', true);
                    if(input.id == 'l20_dataaberproposta' || input.id == 'l20_dataencproposta'){
                        input.setAttribute('data-validate-date', true);
                    }
                });
                return false;
            }

            document.getElementById('containRecebimento').style.display = 'none';
            inputs.forEach(input => {
                if(enabledEdit){
                    input.value = '';
                }
                input.setAttribute('data-validate-required', false);
                if(input.id == 'l20_dataaberproposta' || input.id == 'l20_dataencproposta'){
                    input.setAttribute('data-validate-date', false);
                }
            });
        }

        function validateToggleHorariosAbertura(l20_leidalicitacao, enabledEdit = true){
            const containHorariosAbertura = document.getElementById('containHorariosAbertura').querySelectorAll('input');
            if(oParamNumManual.l12_pncp && ['1'].includes(l20_leidalicitacao)){
                document.getElementById('containHorariosAbertura').style.display = 'block';
                containHorariosAbertura.forEach(input => {
                    input.setAttribute('data-validate-required', true);
                    input.setAttribute('data-validate-time', true);
                });
                return false;
            }

            document.getElementById('containHorariosAbertura').style.display = 'none';
            containHorariosAbertura.forEach(input => {
                input.setAttribute('data-validate-required', false);
                input.setAttribute('data-validate-time', false);
            });
        }

        function validateToggleHorariosEncerramento(l20_leidalicitacao, enabledEdit = true){
            const containHorariosEncerramento = document.getElementById('containHorariosEncerramento').querySelectorAll('input');
            if(oParamNumManual.l12_pncp && ['1'].includes(l20_leidalicitacao)){
                document.getElementById('containHorariosEncerramento').style.display = 'block';
                containHorariosEncerramento.forEach(input => {
                    input.setAttribute('data-validate-required', true);
                    input.setAttribute('data-validate-time', true);
                });
                return false;
            }

            document.getElementById('containHorariosEncerramento').style.display = 'none';
            containHorariosEncerramento.forEach(input => {
                input.setAttribute('data-validate-required', false);
                input.setAttribute('data-validate-time', false);
            });
        }

        function validateToggleProcessoLicitacao(processo, enabledEdit = true){
            const containProcessoLicitacao = document.getElementById('containProcessoLicitacao').querySelectorAll('input');
            if(processo == 's'){
                document.getElementById('containProcessoLicitacao').style.display = 'block';
                document.getElementById('l20_procadmin').readOnly = true;
                containProcessoLicitacao.forEach(input => {
                    if(input.id == 'l34_protprocesso'){
                        input.setAttribute('data-validate-no-special-chars', true);
                        input.setAttribute('data-validate-integer', true);
                        input.setAttribute('data-validate-minlength', 1);
                    }
                    input.setAttribute('data-validate-required', true);
                });
                return false;
            }

            document.getElementById('containProcessoLicitacao').style.display = 'none';
            document.getElementById('l20_procadmin').readOnly = false;
            const inputs = document.getElementById('containProcessoLicitacao').querySelectorAll('input');
            const selects = document.getElementById('containProcessoLicitacao').querySelectorAll('select');
            containProcessoLicitacao.forEach(input => {
                if(input.id == 'l34_protprocesso'){
                    input.setAttribute('data-validate-no-special-chars', false);
                    input.setAttribute('data-validate-integer', false);
                    input.removeAttribute('data-validate-minlength');
                }
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

        function validadeToggleCriterioAdjudicacao(processo = null, enabledEdit = true){
            const inputsCriterioAdjudicacao = document.getElementById('containCriterioAdjudicacao').querySelectorAll('input');
            if(processo && ['2'].includes(processo)){
                document.getElementById('containCriterioAdjudicacao').style.display = 'block';
                inputsCriterioAdjudicacao.forEach(input => {
                    if(enabledEdit){
                        input.value = '';
                    }
                    input.setAttribute('data-validate-required', true);
                });

                return false;
            }

            document.getElementById('containCriterioAdjudicacao').style.display = 'none';
            inputsCriterioAdjudicacao.forEach(input => {
                if(enabledEdit){
                    input.value = '';
                }
                input.setAttribute('data-validate-required', false);
            });
        }

        function validateToggleProcessoPresencial(l20_leidalicitacao, l03_presencial, enabledEdit = true){
            const textareaProcessoPresencial = document.getElementById('containJustificativaProcessoPresencial').querySelectorAll('textarea');
            if(oParamNumManual.l12_pncp && ['1'].includes(l20_leidalicitacao) && l03_presencial == 'true'){
                document.getElementById('containJustificativaProcessoPresencial').style.display = 'block';
                textareaProcessoPresencial.forEach(input => {
                    if(enabledEdit){
                        input.value = '';
                    }
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

        function toggleDispensaValor(hide = false){
            if(hide){
                document.getElementById('containDispensaPorValor').style.display = 'none';
                document.getElementById('l20_dispensaporvalor').selectedIndex = 2;
                document.getElementById('l20_dispensaporvalor').setAttribute('data-validate-required', false);
                return false;
            }

            document.getElementById('containDispensaPorValor').style.display = 'block';
            document.getElementById('l20_dispensaporvalor').selectedIndex = 2;
            document.getElementById('l20_dispensaporvalor').setAttribute('data-validate-required', true);
        }

        function toggleDetalhamentoPncp(hide = false){
            if(hide){
                document.getElementById('containDetalhamentoPNCP').style.display = 'none';
                const containSelectDetalhamento = document.getElementById('containDetalhamentoPNCP').querySelectorAll('select');
                containSelectDetalhamento.forEach(select => {
                    select.setAttribute('data-validate-required', false);
                });
                return false;
            }

            document.getElementById('containDetalhamentoPNCP').style.display = 'block';
            const containSelectDetalhamento = document.getElementById('containDetalhamentoPNCP').querySelectorAll('select');
            containSelectDetalhamento.forEach(select => {
                select.setAttribute('data-validate-required', true);
                if(select.id == 'l20_receita'){
                    select.selectedIndex = 1;
                }
            });
            return false;
        }

        async function getNumeros(l20_codtipocom){
            let oParam = {};
            oParam.exec = 'getNumeros';
            oParam.l20_codtipocom = l20_codtipocom;
            oParam.l20_codigo = l20_codigo;
            let oAjax = await new Ajax.Request(
                url,
                {
                    method: 'post',
                    asynchronous: true,
                    parameters: 'json=' + JSON.stringify(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        inputProcessoLicitatorio.value = oRetorno.data.processo || '0';
                        inputNumerocao.value = oRetorno.data.numeracao || '0';

                        if(['102', '103'].includes(selectTipoProcesso.options[selectTipoProcesso.selectedIndex].getAttribute('l03_pctipocompratribunal'))){
                            inputEdital.value = oRetorno.data.edital || '0';
                        }
                    }
                }
            );
        }

        /* Carregamento de Selects */
        async function getTipoProcesso(l20_codtipocom = null, triggerChange = false){
            let oParam = {};
            oParam.exec = 'listagemTipoProcesso';
            oParam.l03_pctipocompratribunal = [100, 101, 102, 103];
            let oAjax = await new Ajax.Request(
                url,
                {
                    method: 'post',
                    asynchronous: true,
                    parameters: 'json=' + JSON.stringify(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        oRetorno.data.tipoprocesso.forEach((oValue, iSeq) => {
                            const option = document.createElement('option');
                            option.value = oValue.l03_codigo;
                            option.text = oValue.l03_descr;
                            option.setAttribute('l03_pctipocompratribunal', oValue.l03_pctipocompratribunal);
                            option.setAttribute('l03_presencial', oValue.l03_presencial);

                            if(l20_codtipocom == oValue.l03_codigo){
                                option.selected = true;
                            }

                            selectTipoProcesso.appendChild(option);
                        });

                        estadosProxy.tipo_processo = true;

                        if(triggerChange){
                            selectTipoProcesso.dispatchEvent(eventChange);
                        }
                    }
                }
            );
        }

        async function getNaturezaObjeto(l20_naturezaobjeto = null, triggerChange = false){
            let oParam = {};
            oParam.exec = 'listagemNaturezaObjeto';
            let oAjax = await new Ajax.Request(
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

                            selectNatureza.appendChild(option);
                        })

                        estadosProxy.natureza_objeto = true;

                        if(triggerChange){
                            selectNatureza.dispatchEvent(eventChange);
                        }
                    }
                }
            );
        }

        async function getRegimeExecucao(l20_regimexecucao = null, l20_leidalicitacao = null, triggerChange = false) {
            selectRegimeExecucao.innerHTML = '<option value="">Selecione</option>';

            let oParam = {};
            oParam.exec = 'listagemRegimeExecucao';
            oParam.l20_leidalicitacao = l20_leidalicitacao;

            let oAjax = await new Ajax.Request(
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

                            if(l20_regimexecucao == parseInt(iSeq)){
                                option.selected = true;
                            }

                            selectRegimeExecucao.appendChild(option);
                        });

                        estadosProxy.regime_execucao = true;

                        if(triggerChange){
                            selectRegimeExecucao.dispatchEvent(eventChange);
                        }
                    }
                }
            )
        }

        async function getCriterioJulgamento(l20_tipliticacao = null, triggerChange = false, l03_pctipocompratribunal = null) {
            selectCriterioJulgamento.innerHTML = '<option value="">Selecione</option>';
            let oParam = {};
            oParam.exec = 'listagemCriterioJugamento';
            oParam.l03_pctipocompratribunal = l03_pctipocompratribunal;
            let oAjax = await new Ajax.Request(
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
            let oAjax = await new Ajax.Request(
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

                            if(l20_tipliticacao && l20_tipliticacao == iSeq){
                                option.selected = true;
                            }

                            selectNaturezaProcedimento.appendChild(option);
                        })

                        estadosProxy.natureza_procedimento = true;

                        if(triggerChange){
                            selectNaturezaProcedimento.dispatchEvent(eventChange);
                        }
                    }
                }
            )
        }

        async function getCriterioAdjudicacao(l20_criterioadjudicacao = 3, triggerChange = false) {
            let oParam = {};
            oParam.exec = 'listagemCriterioAdjudicacao';
            let oAjax = await new Ajax.Request(
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

                            if(l20_criterioadjudicacao == iSeq){
                                option.selected = true;
                            }

                            selectCriterioAdjudicacao.appendChild(option);
                        })

                        estadosProxy.criterio_adjudicacao = true;

                        if(triggerChange){
                            selectCriterioAdjudicacao.dispatchEvent(eventChange);
                        }
                    }
                }
            )
        }

        async function getCategoriaProcesso(l20_categoriaprocesso = null, triggerChange = false) {
            let oParam = {};
            oParam.exec = 'listagemCategoriaProcesso';
            let oAjax = await new Ajax.Request(
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

                            if(l20_categoriaprocesso == iSeq){
                                option.selected = true;
                            }

                            selectCategoriaProcesso.appendChild(option);
                        })

                        estadosProxy.categoria_processo = true;

                        if(triggerChange){
                            selectCategoriaProcesso.dispatchEvent(eventChange);
                        }
                    }
                }
            )
        }

        function getAmparoLegal(l20_codtipocom = null, l20_amparolegal = null, triggerChange = false){
            let oParam = {};
            oParam.exec = 'listagemAmparoLegal';
            oParam.l20_codtipocom = l20_codtipocom;
            let oAjax = new Ajax.Request(
                url,
                {
                    method: 'post',
                    asynchronous: true,
                    parameters: 'json=' + JSON.stringify(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
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

                        estadosProxy.amparo_legal = true;

                        if(triggerChange){
                            selectAmparoLegal.dispatchEvent(eventChange);
                        }
                    }
                }
            );
        }

        function loadSelects(){
            getTipoProcesso();
            getNaturezaObjeto();
            getRegimeExecucao();
            getCriterioJulgamento();
            getNaturezaProcedimento();
            getCriterioAdjudicacao();
            getCategoriaProcesso();
            getAmparoLegal();
            estadosProxy.dispensa = true;
        }

        function loadEdit(){
            let oParam = {};
            oParam.exec = 'getDispensasInexibilidadesByCodigo';
            oParam.l20_codigo = l20_codigo;
            let oAjax = new Ajax.Request(
                url,
                {
                    method: 'post',
                    parameters: 'json=' + Object.toJSON(oParam),
                    onComplete: async function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        let licitacao = oRetorno.data.licitacao;
                        getTipoProcesso(licitacao.l20_codtipocom);

                        document.getElementById('l20_codigo').value = licitacao.l20_codigo;
                        document.getElementById('l20_codepartamento').value = licitacao.l20_codepartamento;
                        document.getElementById('l20_descricaodep').value = licitacao.l20_descricaodep;
                        document.getElementById('respAutocodigo').value = licitacao.respAutocodigo;
                        document.getElementById('respAutonome').value = licitacao.respAutonome;
                        document.getElementById('l20_leidalicitacao').value = licitacao.l20_leidalicitacao;

                        document.getElementById('l20_numero').value = licitacao.l20_numero;
                        document.getElementById('l20_edital').value = licitacao.l20_edital;
                        document.getElementById('l20_nroedital').value = licitacao.l20_nroedital;

                        document.getElementById('l20_dispensaporvalor').value = (licitacao.l20_dispensaporvalor ? 't' : 'f');

                        getNaturezaObjeto(licitacao.l20_naturezaobjeto);

                        document.getElementById('respObrascodigo').value = licitacao.respObrascodigo;
                        document.getElementById('respObrasnome').value = licitacao.respObrasnome;

                        getRegimeExecucao(licitacao.l20_regimexecucao, licitacao.l20_leidalicitacao.toString(), false);

                        document.getElementById('lprocsis').value = (licitacao.l34_protprocessodescr != null) ? 's' : 'n';

                        document.getElementById('l20_procadmin').value = licitacao.l20_procadmin;

                        document.getElementById('l34_protprocesso').value = licitacao.p58_numero;
                        document.getElementById('l34_protprocessodescr').value = licitacao.l34_protprocessodescr;

                        document.getElementById('l20_datacria').value = licitacao.l20_datacria;
                        document.getElementById('l20_dataaberproposta').value = licitacao.l20_dataaberproposta;
                        document.getElementById('l20_dataencproposta').value = licitacao.l20_dataencproposta;
                        document.getElementById('l20_horacria').value = licitacao.l20_horacria;
                        document.getElementById('l20_horaaberturaprop').value = licitacao.l20_horaaberturaprop;
                        document.getElementById('l20_horaencerramentoprop').value = licitacao.l20_horaencerramentoprop;

                        document.getElementById('l20_tipojulg').value = licitacao.l20_tipojulg;

                        getNaturezaProcedimento(licitacao.l20_tipnaturezaproced);
                        getCriterioAdjudicacao(licitacao.l20_criterioadjudicacao || 3);
                        getAmparoLegal(licitacao.l20_codtipocom, licitacao.l20_amparolegal);
                        getCategoriaProcesso(licitacao.l20_categoriaprocesso);

                        document.getElementById('l20_receita').value = (licitacao.l20_receita) ? 't' : 'f'
                        document.getElementById('l20_objeto').value = licitacao.l20_objeto;
                        document.getElementById('l20_justificativa').value = licitacao.l20_justificativa;
                        document.getElementById('l20_razao').value = licitacao.l20_razao;
                        document.getElementById('l20_justificativapncp').value = licitacao.l20_justificativapncp;

                        document.getElementById('l20_objeto').dispatchEvent(eventInput);
                        document.getElementById('l20_justificativa').dispatchEvent(eventInput);
                        document.getElementById('l20_razao').dispatchEvent(eventInput);
                        document.getElementById('l20_justificativapncp').dispatchEvent(eventInput);

                        validateToggleEdital((licitacao.l03_pctipocompratribunal ? licitacao.l03_pctipocompratribunal.toString() : null), false);
                        validateToggleDispensaValor(
                            (licitacao.l20_leidalicitacao ? licitacao.l20_leidalicitacao.toString() : null),
                            (licitacao.l03_pctipocompratribunal ? licitacao.l03_pctipocompratribunal.toString() : null),
                            (licitacao.l20_tipnaturezaproced ? licitacao.l20_tipnaturezaproced : null),
                            false
                        );
                        validateToggleNaturezaProcedimento((licitacao.l20_leidalicitacao ? licitacao.l20_leidalicitacao.toString() : null), false)
                        validateToggleRecebimento((licitacao.l20_leidalicitacao ? licitacao.l20_leidalicitacao.toString() : null), false);
                        validateToggleHorariosAbertura((licitacao.l20_leidalicitacao ? licitacao.l20_leidalicitacao.toString() : null), false);
                        validateToggleHorariosEncerramento((licitacao.l20_leidalicitacao ? licitacao.l20_leidalicitacao.toString() : null), false);
                        getCriterioJulgamento(licitacao.l20_tipliticacao, false, (licitacao.l03_pctipocompratribunal ? licitacao.l03_pctipocompratribunal.toString() : null));

                        validateToggleProcessoLicitacao((licitacao.l34_protprocessodescr != null) ? 's' : 'n', false);
                        validateToggleObras(licitacao.l20_naturezaobjeto ? licitacao.l20_naturezaobjeto.toString(): null, false);
                        validateToggleRegimeExecucao((licitacao.l20_naturezaobjeto ? licitacao.l20_naturezaobjeto.toString() : null), false);
                        validadeToggleCriterioAdjudicacao(licitacao.l20_tipnaturezaproced ? licitacao.l20_tipnaturezaproced.toString() : null, false);
                        document.getElementById('l20_tipoprocesso').value = licitacao.l03_pctipocompratribunal;
                        validateToggleBlockTabLote((licitacao.l20_tipojulg ? licitacao.l20_tipojulg.toString() : null), oParamNumManual, licitacao.l20_tipnaturezaproced == 2);
                        getProcessosVinculadosValidacao();
                        validateToggleProcessoPresencial(
                            (licitacao.l20_leidalicitacao ? licitacao.l20_leidalicitacao.toString() : null),
                            (licitacao.l03_presencial ? licitacao.l03_presencial.toString() : null),
                            false
                        )
                        if(oParamNumManual.l12_pncp){
                            if(licitacao.l20_leidalicitacao == 2){
                                toggleDetalhamentoPncp(true);
                            } else {
                                toggleDetalhamentoPncp();
                            }
                        }

                        estadosProxy.dispensa = true;
                    }
                }
            );
        }

        function isElementHidden(el) {
            while (el) {
                const style = window.getComputedStyle(el);
                if (style.display === 'none') {
                    return true;
                }
                el = el.parentElement;
            }
            return false;
        }

        function validaDatas(){
            const dataAberturaProc = document.getElementById('l20_datacria').value + ' ' + document.getElementById('l20_horacria').value;
            const dataInicioRecebimento = document.getElementById('l20_dataaberproposta').value + ' ' + document.getElementById('l20_horaaberturaprop').value;
            const dataFinalRecebimento = document.getElementById('l20_dataencproposta').value + ' ' + document.getElementById('l20_horaencerramentoprop').value;

            const dataPropostaVisible = ['flex', 'block'].includes(document.getElementById('containRecebimento').style.display);
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
                    text: 'A data de inicio de recebimento de propostas não pode ser anterior a data de abertura do processo administrativo.',
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
                    text: 'A data e hora final de recebimento de propostas não pode ser anterior a data e hora de inicio de recebimento de propostas.',
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
                    text: 'As datas de inicio e final de recebimento de proposta não podem ser iguais.',
                });
                return false;
            }

            return true;
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
            oParam.exec = 'listagemLiclicitaParam';
            let oAjax = await new Ajax.Request(
                url,
                {
                    method: 'post',
                    asynchronous: true,
                    parameters: 'json=' + JSON.stringify(oParam),
                    onComplete: function(oAjax){
                        let oRetorno = JSON.parse(oAjax.responseText);
                        oParamNumManual = oRetorno.data;
                        if(oParamNumManual.l12_numeracaomanual){
                            inputProcessoLicitatorio.readOnly = false;
                            inputNumerocao.readOnly = false;
                            inputEdital.readOnly = false;

                            inputProcessoLicitatorio.style.backgroundColor = '';
                            inputNumerocao.style.backgroundColor = '';
                            inputEdital.style.backgroundColor = '';
                        }

                        const selects = document.getElementById('containDetalhamentoPNCP').querySelectorAll('select');
                        if(oParamNumManual.l12_pncp){
                            document.getElementById('containDetalhamentoPNCP').style.display = 'block';
                            selects.forEach(select => {
                                select.value = '';
                                select.setAttribute('data-validate-required', true);
                                if(select.id == 'l20_receita'){
                                    select.selectedIndex = 1;
                                }
                            });
                        } else {
                            document.getElementById('containDetalhamentoPNCP').style.display = 'none';
                            selects.forEach(select => {
                                select.value = '';
                                select.setAttribute('data-validate-required', false);
                            });
                        }

                        if(l20_codigo != null && l20_codigo != ''){
                            loadEdit();
                        } else {
                            loadSelects();
                        }
                    }
                }
            );
        }

        getParam();

        if(selectTipoProcesso != null){
            selectTipoProcesso.addEventListener('change', function(e){
                e.preventDefault();
                const value = selectTipoProcesso.value;
                selectAmparoLegal.innerHTML = '<option value="">Selecione</option>';
                inputProcessoLicitatorio.value = '';
                inputNumerocao.value = '';
                inputEdital.value = '';
                document.getElementById('l20_tipoprocesso').value = '';
                if(value == ''){
                    return false;
                }

                document.getElementById('l20_tipoprocesso').value = selectTipoProcesso.options[selectTipoProcesso.selectedIndex].getAttribute('l03_pctipocompratribunal');
                validateToggleEdital(selectTipoProcesso.options[selectTipoProcesso.selectedIndex].getAttribute('l03_pctipocompratribunal'));
                validateToggleDispensaValor(
                    selectLeiLicitacao.value,
                    selectTipoProcesso.options[selectTipoProcesso.selectedIndex].getAttribute('l03_pctipocompratribunal'),
                    selectNaturezaProcedimento.value
                );
                getCriterioJulgamento(null, false, selectTipoProcesso.options[selectTipoProcesso.selectedIndex].getAttribute('l03_pctipocompratribunal'));
                validateToggleProcessoPresencial(
                    selectLeiLicitacao.value,
                    selectTipoProcesso.options[selectTipoProcesso.selectedIndex].getAttribute('l03_presencial')
                )
                getNumeros(value);
                getAmparoLegal(value);
            })
        }

        if(selectProcessoSistema != null){
            selectProcessoSistema.addEventListener('change', function(e){
                e.preventDefault();
                const value = selectProcessoSistema.value;
                validateToggleProcessoLicitacao(value);
            });
        }

        if(selectNatureza != null){
            selectNatureza.addEventListener('change', function(e){
                e.preventDefault();
                const value = selectNatureza.value;
                validateToggleObras(value);
                validateToggleRegimeExecucao(value);
            });
        }

        if(selectNaturezaProcedimento != null){
            selectNaturezaProcedimento.addEventListener('change', function(e){
                e.preventDefault();
                const value = selectNaturezaProcedimento.value;
                validadeToggleCriterioAdjudicacao(value);
                if(value == 2){
                    toggleDispensaValor(true);
                } else {
                    toggleDispensaValor();
                }
            });
        }

        if(selectLeiLicitacao != null){
            selectLeiLicitacao.addEventListener('change', function(e){
                e.preventDefault();
                const value = selectLeiLicitacao.value;

                if(oParamNumManual.l12_pncp){
                    if(value == 2){
                        toggleDetalhamentoPncp(true);
                    } else {
                        toggleDetalhamentoPncp();
                    }
                }

                validateToggleDispensaValor(
                    value,
                    selectTipoProcesso.options[selectTipoProcesso.selectedIndex].getAttribute('l03_pctipocompratribunal'),
                    selectNaturezaProcedimento.value
                );
                validateToggleNaturezaProcedimento(value)
                validateToggleRecebimento(value);
                validateToggleHorariosAbertura(value);
                validateToggleHorariosEncerramento(value);
                validateToggleProcessoPresencial(
                    selectLeiLicitacao.value,
                    selectTipoProcesso.options[selectTipoProcesso.selectedIndex].getAttribute('l03_presencial')
                );

                getRegimeExecucao(null, selectLeiLicitacao.value);
            })
        }

        if(btnSalvarDispensasInexigibilidades != null){
            btnSalvarDispensasInexigibilidades.addEventListener("click", function (e) {
                e.preventDefault();
                const formData = serializarFormulario(document.getElementById('frmDispensasInexigibilidades'));
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
                                    text: 'Dispensas/Inexigibilidades salvo com sucesso!',
                                });

                                let currentUrl = new URL(window.location.href);
                                parent.loadingDispensas();
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

        if(btnAtualizarDispensasInexigibilidades != null){
            btnAtualizarDispensasInexigibilidades.addEventListener('click', function(e){
                e.preventDefault();
                const formData = serializarFormulario(document.getElementById('frmDispensasInexigibilidades'));
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
                                    text: 'Dispensas/Inexigibilidades salvo com sucesso!',
                                });

                                let currentUrl = new URL(window.location.href);
                                parent.loadingDispensas();
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

        if(btnCancelarDispensasInexigibilidades != null){
            btnCancelarDispensasInexigibilidades.addEventListener('click', function(e){
                e.preventDefault();
                parent.closeDispensasInexigibilidades(false);
                return false;
            });
        }

        window.pesquisaDepartamento = pesquisaDepartamento;
        window.carregaDepartamento = carregaDepartamento;
        window.pesquisaCgm = pesquisaCgm;
        window.carregaCgmAncora = carregaCgmAncora;
        window.carregaCgm = carregaCgm;
        window.pesquisaProtProcesso = pesquisaProtProcesso;
        window.loadProtProcessoAncora = loadProtProcessoAncora;
        window.loadProtProcesso = loadProtProcesso;
        window.disabledQuebraLinha = disabledQuebraLinha;
    })();


</script>
