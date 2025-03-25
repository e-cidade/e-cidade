<?php
    use App\Helpers\StringHelper;
    require_once("libs/renderComponents/index.php");
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <title>E-Cidade Contass - Fase de Lances</title>
    <meta charset="ISO-8859-1">
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link rel="stylesheet" href="<?= url()->to('/') ?>/public/assets/css/lic_fasedelance.css">
</head>

<body>
    <?php $component->render('toast/default') ?>
    <?php $component->render('icons') ?>

    <?php $component->render('modais/simpleModal/startModal', [
        'id' => 'avisodeacoestabela',
        'size' => 'sm',
    ], true); ?>
        <div class="row">
            <div class="col-12" style="display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 15px; color: gray;">

                <div>
                    <i class="icon cursor-arrow-rays"></i>
                    <?= StringHelper::toUtf8('Quando este ícone estiver presente, a tabela permitirá a execução de ações ao clicar em suas linhas.') ?>
                </div>

                <div>
                    <i class="icon cursor-arrow-ripple"></i>
                    <?= StringHelper::toUtf8('Quando este ícone estiver presente, a tabela permitirá a execução de ações ao dar um duplo clique em suas linhas.') ?>
                </div>

                <div style="display: flex;">
                    <?php $component->render('buttons/solid', [
                        'id' => 'avisodeacoestabela',
                        'designButton' => 'success',
                        'onclick' => "closeModal('avisodeacoestabela')",
                        'message' => '<i class="icon checks"></i> ' . StringHelper::toUtf8('Confirmar')
                    ]); ?>
                </div>
            </div>
        </div>  
    <?php $component->render('modais/simpleModal/endModal', [], true); ?>

    <!-- Alerta de confirmação para limpar lances -->
    <?php $component->render('modais/simpleModal/startModal', [
        'id' => 'limparLancesAlerta',
        'size' => 'sm',
    ], true); ?>
        <div class="row">
            <div class="col-12" style="display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 15px; color: gray;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 70px; color: #dc3545;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>

                <p style="font-size: 1.3rem; text-align: center; margin: 0px; width: 90%;">
                    Tem certeza de que deseja apagar os registros dos lances do Julgamento?
                </p>

                <div style="display: flex;">
                    <?php $component->render('buttons/solid', [
                        'designButton' => 'danger',
                        'onclick' => 'limparLancesAlerta()',
                        'message' => StringHelper::toUtf8('Sim, limpar lances')
                    ]); ?>

                    <?php $component->render('buttons/solid', [
                        'onclick' => 'closeModal(`limparLancesAlerta`)',
                        'message' => StringHelper::toUtf8('Não')
                    ]); ?>
                </div>
            </div>
        </div>  
    <?php $component->render('modais/simpleModal/endModal', [], true); ?>

    <!-- Alterar status do item -->
    <?php $component->render('modais/simpleModal/startModal', [
        'id' => 'situacaoLicitacaoItens',
        'size' => 'md'
    ], true); ?>
        <div style="margin-top: 10px;"></div>
        <div class="row">
            <input type="hidden" id="idsStatusItem">

            <div id="tooltip-badges-div" class="col-12">
                <?php $component->render('tooltip/defaultRightLegacy', [
                    'id' => 'tooltip-badges',
                    'body' => '<i class="icon exclamation-circle info"></i>',
                    'color' => '',
                    'size' => 'md'
                ]) ?>
            </div>

            <div id="situacaoItemCodigoDiv" class="col-4">
                <?php $component->render('inputs/text/simple', [
                    'id' => 'situacaoItemCodigo',
                    'placeholder' => StringHelper::toUtf8('Código'),
                    'label' => StringHelper::toUtf8('Código do item'),
                    'size' => 'md',
                    'disabled' => true,
                ]) ?>
            </div>

            <div id="situacaoItemDescDiv" class="col-8">
                <?php $component->render('inputs/text/simple', [
                    'id' => 'situacaoItemDesc',
                    'placeholder' => StringHelper::toUtf8('Descrição'),
                    'label' => StringHelper::toUtf8('Descrição do item'),
                    'size' => 'md',
                    'disabled' => true,
                ]) ?>
            </div>

            <div class="col-12">
                <?php $component->render('inputs/selects/choices/simple', [
                    'id' => 'categoriasstatusitem',
                    'name' => 'categorias',
                    'label' => 'Categorias:',
                    'size' => 'md',
                    'options' => $statusItensLabels,
                ]) ?>
            </div>

            <div class="col-12">
                <?php $component->render('inputs/textarea/simple', [
                    'id' => 'motivoStatusItem',
                    'placeholder' => 'Motivo do processo',
                    'label' => 'Motivo',
                    'name' => 'motivo',
                    'size' => 'md',
                ]) ?>
            </div>

            <div class="col-12" style="display: flex; justify-content: center;">
                <?php $component->render('buttons/solid', [
                    'id' => 'alterarSituacoesDosItens',
                    'designButton' => 'success',
                    'message' => 'Salvar',
                    'onclick' => 'alterarSituacoesDosItens()'
                ]); ?>
            </div>
        </div>  
    <?php $component->render('modais/simpleModal/endModal', [], true); ?>

    <div class="container">
        <div class="row">

            <div class="col-12">
                <div class="divider-row">
                    <span class="divider-label">Dados do Processo</span>
                </div>
            </div>

            <input id="tipojulg" type="hidden">

            <div class="col xs-12 sm-12 md-6 lg-4 xl-2">
                <?php $component->render('inputs/text/anchor', [
                    'id' => 'codigo',
                    'idModal' => 'customModal',
                    'idGrid' => 'licitacoes07896768',
                    'placeholder' => 'Selecione processo',
                    'label' => StringHelper::toUtf8('Código:'),
                    'size' => 'md',
                    'disabled' => true,
                ]) ?>
            </div>

            <?php $component->render('modais/simpleModal/startModal', [
                'id' => 'customModal',
                'size' => 'full'
            ], true); ?>
                <div style="margin-top: 40px;"></div>
                <?php $component->render('tables/gridJs/serverSideLegacy', [
                    'id' => 'licitacoes07896768',
                    'columns' => $columns,
                    'apiUrl' => route('datagrid.getLiclicita'),
                    'perPage' => 10,
                    'search' => true,
                    'sort' => true,
                    'fixedHeader' => true,
                    'multiSelect' => true,
                    'rowDoubleClick' => true,
                    'rowDoubleClickFunction' => 'licitacoesDoubleClick',
                    'loadFunction' => 'objetoDescFunction',
                ]); ?>
            <?php $component->render('modais/simpleModal/endModal', [], true); ?>

            <div class="col xs-12 sm-12 md-6 lg-4 xl-4">
                <?php $component->render('inputs/text/simple', [
                    'id' => 'modalidade',
                    'placeholder' => 'Preenchimento da modalidade',
                    'label' => 'Modalidade:',
                    'size' => 'md',
                    'disabled' => true,
                ]) ?>
            </div>

            <div class="col xs-12 sm-12 md-6 lg-4 xl-2">
                <?php $component->render('inputs/text/simple', [
                    'id' => 'numero',
                    'placeholder' => StringHelper::toUtf8('Preenchimento do número'),
                    'label' => StringHelper::toUtf8('Número:'),
                    'size' => 'md',
                    'disabled' => true,
                ]) ?>
            </div>

            <div class="col xs-12 sm-12 md-6 lg-6 xl-2">
                <?php $component->render('inputs/text/simple', [
                    'id' => 'data',
                    'placeholder' => StringHelper::toUtf8('Preenchimento da data de apuração'),
                    'label' => StringHelper::toUtf8('Data da apuração:'),
                    'size' => 'md',
                    'disabled' => true,
                ]) ?>
            </div>

            <div class="col xs-12 sm-12 md-6 lg-6 xl-2">
                <?php $component->render('inputs/text/simple', [
                    'id' => 'hora',
                    'placeholder' => StringHelper::toUtf8('Hora da apuração'),
                    'label' => StringHelper::toUtf8('Hora da apuração:'),
                    'size' => 'md',
                    'disabled' => true,
                ]) ?>
            </div>

            <div class="col-12">
                <?php $component->render('inputs/textarea/simple', [
                    'id' => 'objeto',
                    'placeholder' => 'Objeto do processo',
                    'label' => 'Objeto:',
                    'name' => 'objeto',
                    'size' => 'md',
                    'disabled' => true,
                ]) ?>
            </div>

            <div class="col-12">
                <div class="divider-row">
                    <span class="divider-label">Itens do Processo</span>
                </div>
            </div>

        </div>

        <?php $component->render('tables/gridJs/serverSideLegacy', [
            'id' => 'licitacoes0789676811132',
            'emptyTableFirstLoadMessage' => 'Para exibir os itens da licitação, selecione uma licitação.',
            'search' => true,
            'sort' => true,
            'fixedHeader' => true,
            'rowClick' => true,
            'multiSelect' => true,
            'rowClickFunction' => 'clickNaLinhaFunction',
            'rowDoubleClick' => true,
            'rowDoubleClickFunction' => 'licitacoesItensSituacoes',
            'loadFunction' => 'descricaoDescFunction',
        ]); ?>

        <div id="complemento-descricao-67HGS"></div>

        <div class="group-of-buttons-bid-phase" style="margin-top: 20px; max-width: 100%; flex-wrap: wrap;">
            <?php $component->render('buttons/solid', [
                'id' => 'classificar',
                'designButton' => 'success',
                'message' => 'Classificar',
                'disabled' => true,
                'onclick' => "classificarLicitacoesItens()"
            ]); ?>

            <?php $component->render('buttons/solid', [
                'id' => 'btnFinalizar',
                'designButton' => 'success',
                'message' => 'Finalizar',
                'disabled' => true,
                'attributes' => [
                    'style' => 'display: none;'
                ],
                'onclick' => "finalizar()"
            ]); ?>

            <?php $component->render('buttons/solid', [
                'id' => 'situacao',
                'designButton' => 'info',
                'message' => StringHelper::toUtf8('Situação'),
                'disabled' => true,
                'onclick' => "licitacoesItensSituacoes()"
            ]); ?>

            <?php $component->render('buttons/solid', [
                'id' => 'proposta',
                'message' => 'Proposta',
                'onclick' => "CurrentWindow.corpo.document.location.href=`" . url()->to('/') . "/w/1/lic_propostas.php`"
            ]); ?>

            <?php $component->render('buttons/solid', [
                'id' => 'limparLance',
                'designButton' => 'danger',
                'disabled' => true,
                'message' => 'Limpar Lances',
                'onclick' => "openModal(`limparLancesAlerta`)"
            ]); ?>

            <?php $component->render('buttons/solid', [
                'id' => 'readequarPropostaBT',
                'designButton' => 'secondary',
                'disabled' => true,
                'message' => 'Readequar Proposta',
                'onclick' => "readequarPropostaLotes()"
            ]); ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (!empty($redirect)): ?>
                licitacoesItensRedirect(<?= $redirect ?>);
            <?php endif; ?>

            <?php if ($param->l13_avisodeacoestabela): ?>
                if (!sessionStorage.getItem("avisodeacoestabela")) {
                    openModal('avisodeacoestabela', false);
                }

                document.getElementById("avisodeacoestabela").addEventListener("click", function () {
                    sessionStorage.setItem("avisodeacoestabela", "true");
                    closeModal('avisodeacoestabela');
                });
            <?php endif; ?>
        });


        function limparLancesAlerta() {
            closeModal('limparLancesAlerta');
            limparLances();
        }

        const routeBase = '<?= url()->to('/') ?>';
        const routeDatagridGetLiclicita = '<?= route('datagrid.getLiclicita') ?>';
        const routeDatagridGetLiclicitaItens = '<?= route('datagrid.getLiclicitaItens') ?>';
        const routeDatagridGetLiclicitaLotes = '<?= route('datagrid.getLiclicitaLotes') ?>';
        const routeFinalizar = '<?= route('faseDeLances.finalizar') ?>';
        const routeAlterarStatusItem = '<?= route('faseDeLances.alterarStatusItem') ?>';
        const routeLimparLances = '<?= route('faseDeLances.limparLances') ?>';
        const routeJulgamento = '<?= route('julgamento.index', ['codigoLicitacao' => ':codigoLicitacao', 'codigoLicitacaoItem' => ':codigoLicitacaoItem']) ?>';
        const routeFornecedoresPropostasTemp = '<?= route('julgamento.obterFornecedoresProposta', ['codigoLicitacao' => ':codigoLicitacao', 'codigoLicitacaoItem' => ':codigoLicitacaoItem']) ?>';
        const routeReadequarProposta = '<?= route('readequarProposta.index', ['codigoLicitacao' => ':codigoLicitacao', 'codigoLicitacaoItem' => ':codigoLicitacaoItem']) ?>';
    </script>
    <script type="module" src="<?= url()->to('/') ?>/scripts/classes/faseDeLances/faseDeLances.js"></script>
</body>

</html>