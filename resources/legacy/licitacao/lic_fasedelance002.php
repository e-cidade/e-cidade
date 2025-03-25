<?php
    use App\Helpers\StringHelper;
    require_once("libs/renderComponents/index.php");

    $uri = $_SERVER['REDIRECT_URL'];
    $path = parse_url($uri, PHP_URL_PATH);
    $segments = explode('/', trim($path, '/'));
    $firstSegment = $segments[0];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <title>E-Cidade Contass - Fase de Lances</title>
    <meta charset="ISO-8859-1">
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link rel="stylesheet" href="<?= url()->to('/') . '/' . $firstSegment ?>/public/assets/css/lic_fasedelance.css">
    <link rel="stylesheet" href="<?= url()->to('/') . '/' . $firstSegment ?>/public/assets/css/lic_fasedelance002.css">
</head>

<body>
    <?php $component->render('toast/default') ?>

    <?php $component->render('modais/simpleModal/startModal', [
        'id' => 'situacaoFornecedor',
        'size' => 'md',
    ], true); ?>
        <div class="row">

            <div class="col-12">
                <div class="divider-row">
                    <span class="divider-label">Dados do Item</span>
                </div>
            </div>

            <div id="situacaoItemCodigoDiv" class="col xs-12 sm-12 md-12 lg-4 xl-3">
                <?php $component->render('inputs/text/simple', [
                    'id' => 'situacaoItemCodigo',
                    'placeholder' => StringHelper::toUtf8('Código do Item'),
                    'label' => StringHelper::toUtf8('Código'),
                    'size' => 'md',
                    'disabled' => true,
                ]) ?>
            </div>

            <div id="situacaoItemDescDiv" class="col xs-12 sm-12 md-12 lg-8 xl-9">
                <?php $component->render('inputs/text/simple', [
                    'id' => 'situacaoItemDesc',
                    'placeholder' => StringHelper::toUtf8('Descrição'),
                    'label' => StringHelper::toUtf8('Descrição do Item'),
                    'size' => 'md',
                    'disabled' => true,
                ]) ?>
            </div>

            <div class="col-12">
                <div class="divider-row">
                    <span class="divider-label">Dados do Fornecedor</span>
                </div>
            </div>

            <input type="hidden" id="idsStatusItem">

            <div id="situacaoFornecedorCodigoDiv" class="col xs-12 sm-12 md-12 lg-4 xl-3">
                <?php $component->render('inputs/text/simple', [
                    'id' => 'situacaoFornecedorCodigo',
                    'placeholder' => StringHelper::toUtf8('Código do Fornecedor'),
                    'label' => StringHelper::toUtf8('Código'),
                    'size' => 'md',
                    'disabled' => true,
                ]) ?>
            </div>

            <div id="situacaoFornecedorRazaoSocialDiv" class="col xs-12 sm-12 md-12 lg-8 xl-9">
                <?php $component->render('inputs/text/simple', [
                    'id' => 'situacaoFornecedorRazaoSocial',
                    'placeholder' => StringHelper::toUtf8('Nome/Razão Social'),
                    'label' => StringHelper::toUtf8('Nome/Razão Social'),
                    'size' => 'md',
                    'disabled' => true,
                ]) ?>
            </div>

            <div id="situacaoFornecedorCnpjDiv" class="col xs-12 sm-12 md-12 lg-12 xl-12">
                <?php $component->render('inputs/text/simple', [
                    'id' => 'situacaoFornecedorCnpj',
                    'placeholder' => StringHelper::toUtf8('Cnpj'),
                    'label' => StringHelper::toUtf8('Cnpj'),
                    'size' => 'md',
                    'disabled' => true,
                ]) ?>
            </div>

            <div class="col-12">
                <?php $component->render('inputs/selects/choices/simple', [
                    'id' => 'categoriasstatusfornecedor',
                    'name' => 'categorias',
                    'label' => 'Categorias:',
                    'size' => 'md',
                    'options' => $statusItensLabels,
                ]) ?>
            </div>
            
            <div class="col-12">
                <?php $component->render('inputs/textarea/simple', [
                    'id' => 'motivoStatusFornecedor',
                    'placeholder' => 'Motivo do processo...',
                    'label' => 'Motivo',
                    'name' => 'motivo',
                    'size' => 'md',
                ]) ?>
            </div>

            <div class="col-12" style="display: flex; justify-content: center;">
                <?php $component->render('buttons/solid', [
                    'designButton' => 'success',
                    'message' => 'Salvar',
                    'onclick' => 'alterarSituacaoDoFornecedor()'
                ]); ?>
            </div>
        </div>  
    <?php $component->render('modais/simpleModal/endModal', [], true); ?>

    <?php $component->render('modais/simpleModal/startModal', [
        'id' => 'leiMicroEmpresa',
        'size' => 'md',
    ], true); ?>
        <div class="row">
            <div class="col-12" style="display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 15px; color: gray;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 70px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>

                <p style="font-size: 1.3rem; text-align: center; margin: 0px; width: 90%;">A(s) Empresa(s) <span id="microEmpresasPopupText" style="font-weight: bold;"></span> tem direito a lance conforme <strong>LEI-123/2006</strong>, a(s) empresa(s) deseja dar lance(s)?</p>

                <div style="display: flex;">
                    <?php $component->render('buttons/solid', [
                        'designButton' => 'info',
                        'onclick' => 'liberarMicroEmpresas(`'.$codigoLicitacao.'-p-'.$codigoLicitacaoItem.'`)',
                        'message' => StringHelper::toUtf8('Sim, dar lance.')
                    ]); ?>
                    
                    <?php $component->render('buttons/solid', [
                        'onclick' => 'semLanceMicroEmpresa()',
                        'message' => StringHelper::toUtf8('Não, sem lance.')
                    ]); ?>
                </div>
            </div>
        </div>  
    <?php $component->render('modais/simpleModal/endModal', [], true); ?>

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

    <div class="container">

        <input id="tipojulg" type="hidden" value="<?=$tipojulg?>">
        <input id="codigoItemOuLote" type="hidden" value="<?=$codigoItemOuLote?>">

        <div class="row">
            <div class="col-12">
                <div class="divider-row">
                <span class="divider-label"><?=StringHelper::toUtf8('Parâmetros de Lance')?></span>
                </div>
            </div>

            <div class="col xs-12 sm-12 md-4 lg-4 xl-3">
                <?php $component->render('inputs/text/simple', [
                    'id' => 'difMinimaLance',
                    'placeholder' => 'Valor do lance',
                    'label' => StringHelper::toUtf8('Diferença Mínima de Lance:'),
                    'size' => 'md',
                    'disabled' => true,
                ]) ?>
            </div>

            <div class="col xs-12 sm-12 md-8 lg-8 xl-9">
                <?php $component->render('inputs/text/simple', [
                    'id' => 'rsFornecedor',
                    'placeholder' => StringHelper::toUtf8('Nome / Razão Social do Fornecedor'),
                    'label' => StringHelper::toUtf8('Fornecedor que deverá dar o lance:'),
                    'size' => 'md',
                    'disabled' => true,
                ]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="divider-row">
                    <span class="divider-label">Dados do Item</span>
                </div>
            </div>

            <div class="col <?= ($param->l13_precoref) ? 'xs-12 sm-12 md-6 lg-2 xl-3' : 'xs-12 sm-12 md-6 lg-3 xl-2' ?>">
                <?php $component->render('inputs/text/simple', [
                    'id' => 'codigoItem',
                    'placeholder' => StringHelper::toUtf8('Código'),
                    'label' => StringHelper::toUtf8('Código do Item:'),
                    'size' => 'md',
                    'disabled' => true,
                    'value' => $codigoItemOuLote
                ]) ?>
            </div>

            <?php if($selectedItem['descricao']): ?>
                <div class="col <?= ($param->l13_precoref) ? 'xs-12 sm-12 md-6 lg-5 xl-4' : 'xs-12 sm-12 md-6 lg-6 xl-6' ?>">
                    <?php $component->render('inputs/text/simple', [
                        'id' => 'descItem',
                        'placeholder' => StringHelper::toUtf8('Descrição'),
                        'label' => StringHelper::toUtf8('Descrição do Item:'),
                        'size' => 'md',
                        'disabled' => true,
                        'value' => $selectedItem['descricao']
                    ]) ?>
                </div>
            <?php endif; ?>

            <?php if($selectedItem['unidade']): ?>
                <div class="col <?= ($param->l13_precoref) ? 'xs-12 sm-12 md-6 lg-2 xl-2' : 'xs-12 sm-12 md-12 lg-3 xl-4' ?>">
                    <?php $component->render('inputs/text/simple', [
                        'id' => 'unidItem',
                        'placeholder' => StringHelper::toUtf8('Unidade'),
                        'label' => StringHelper::toUtf8('Unidade do Item:'),
                        'size' => 'md',
                        'disabled' => true,
                        'value' => $selectedItem['unidade']
                    ]) ?>
                </div>
            <?php endif; ?>

            <?php if($param->l13_precoref): ?>
                <div class="col xs-12 sm-12 md-6 lg-3 xl-3">
                    <?php $component->render('inputs/text/simple', [
                        'id' => 'valorRefItem',
                        'placeholder' => StringHelper::toUtf8('Valor de unitário referência'),
                        'label' => StringHelper::toUtf8('Valor de referência do item:'),
                        'size' => 'md',
                        'disabled' => true,
                        'value' => 'R$ ' . number_format($precoReferencia->si02_vlprecoreferencia, 2, ',', '.')
                    ]) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col xs-12 sm-12 md-12 lg-5 xl-6">
                <div class="divider-row">
                    <span class="divider-label">Lances</span>
                </div>

                <div id="bid-card-2D80BA98" for="bid-input-5151BB7" class="bid-card-2D80BA98 d-none">
                    <p style="margin-top: 60px; margin-bottom: 20px;">
                        <span>
                            R$ 
                        </span>
                        <input id="bid-input-5151BB7" type="text" class="bid-input-5151BB7" placeholder="0,00">
                    </p>

                    <div id="subjectValueDiv" style="display: flex; align-content: center; justify-content: center; align-items: center; box-shadow: 1px 1px 20px 0px lightgray; border-radius: 10px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 30px; color: #28a745;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
                        </svg>
    
                        <span>
                            <strong>
                                Valor sugerido:
                            </strong>
                            
                            <span id="subjectValue">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 13px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </span>
                        </span>
                        
                        <?php $component->render('buttons/solid', [
                            'designButton' => 'success',
                            'size' => 'sm',
                            'message' => 'Acatar',
                            'onclick' => 'subjectValueComply();'
                        ]); ?>
                    </div>
                </div>

                <div id="bid-card-wait-2D80BA98" class="bid-card-wait-2D80BA98 skeleton-line-D7457CD"></div>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>

                <script>
                    const input = document.getElementById('bid-input-5151BB7');
                    const context = document.createElement('canvas').getContext('2d');

                    new Cleave('#bid-input-5151BB7', {
                        numeral: true,
                        numeralThousandsGroupStyle: 'thousand',
                        delimiter: '.',
                        numeralDecimalMark: ',',
                        numeralDecimalScale: 2
                    });

                    function adjustBidInputWidth() {
                        let font = window.getComputedStyle(input).font;
                        context.font = font;

                        let text = input.value || input.placeholder;
                        let textWidth = context.measureText(text).width;

                        input.style.width = `${textWidth + 10}px`;
                    }

                    input.addEventListener('keydown', function(event) {
                        if (event.key === 'Enter') {
                            adicionarLance();
                        }
                    });

                    input.addEventListener('input', adjustBidInputWidth);
                </script>
            </div>

            <div class="col xs-12 sm-12 md-12 lg-7 xl-6">
                <div class="divider-row">
                    <span class="divider-label">Forncedores Classificados</span>
                </div>

                <div id="CBBF4CC-table-container" class="CBBF4CC-table-container d-none">
                    <table class="CBBF4CC-table">
                        <thead>
                            <tr>
                                <th>CNPJ</th>
                                <th><?=StringHelper::toUtf8('Nome/Razão Social')?></th>
                                <th><?=StringHelper::toUtf8('Valor Unitário')?></th>
                            </tr>
                        </thead>
                        <tbody id="dynamic-table-supplier">
                        </tbody>
                    </table>
                </div>

                <!-- Table Wait -->
                <div id="CBBF4CC-table-wait-container" class="CBBF4CC-table-wait-container wait">
                    <table class="CBBF4CC-table">
                        <thead>
                            <tr>
                                <th>CNPJ</th>
                                <th><?=StringHelper::toUtf8('Nome/Razão Social')?></th>
                                <th><?=StringHelper::toUtf8('Valor Unitário')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="skeleton-line-D7457CD"></div>
                                </td>
                                <td>
                                    <div class="skeleton-line-D7457CD"></div>
                                </td>
                                <td>
                                    <div class="skeleton-line-D7457CD"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="group-of-buttons-bid-phase" style="margin-top: 20px; flex-wrap: wrap;">
            <?php $component->render('buttons/solid', [
                'designButton' => 'success',
                'id' => 'btnFinalizar',
                'message' => 'Finalizar',
                'disabled' => true,
                'onclick' => 'leiMicroEmpresa(`'.$codigoLicitacao.'-p-'.$codigoLicitacaoItem.'`);'
            ]); ?>
            
            <?php $component->render('buttons/solid', [
                'designButton' => 'success',
                'id' => 'btnLance',
                'message' => 'Adicionar lance',
                'onclick' => 'adicionarLance();'
            ]); ?>

            <?php $component->render('buttons/solid', [
                'designButton' => 'info',
                'id' => 'btnSemLance',
                'message' => 'Sem Lance',
                'onclick' => 'adicionarLanceSemValor();'
            ]); ?>

            <?php $component->render('buttons/solid', [
                'designButton' => 'info',
                'id' => 'btnReverterLance',
                'message' => 'Reverter lance',
                'disabled' => true,
                'onclick' => 'reverterLance()'
            ]); ?>

            <?php $component->render('buttons/solid', [
                'designButton' => 'danger',
                'id' => 'btnLimpaLances',
                'message' => 'Limpar Lances',
                'disabled' => true,
                'onclick' => 'openModal(`limparLancesAlerta`)'
            ]); ?>

            <?php $component->render('buttons/solid', [
                'designButton' => 'secondary',
                'id' => 'btnCancelar',
                'message' => 'Voltar',
                'onclick' => 'window.location.href = `' . url()->to('/') . '/' . $firstSegment .'/w/0/web/patrimonial/licitacoes/procedimentos/julgamento-por-lance/fase-de-lances?redirect=' . $codigoLicitacao .'`;'
            ]); ?>
        </div>
    </div>

    <script>
        function resetRoute(urlPathRouteApp) {
            let partUrlPathRouteApp = urlPathRouteApp.split('/web');
            return `${partUrlPathRouteApp[0]}/${specificPartUrlPathCurrent}/web${partUrlPathRouteApp[1]}`;
        }

        function limparLancesAlerta() {
            closeModal('limparLancesAlerta');
            limparLances();
        }

        let urlPathCurrent = window.location.pathname;
        let partUrlPathCurrent = urlPathCurrent.split('/');
        const specificPartUrlPathCurrent = partUrlPathCurrent[1];

        const diferencaMinimaDeLance = <?= $param->l13_difminlance?>;
        const liclicitaCodigo = <?= $codigoLicitacao ?>;
        const liclicitemCodigo = <?= $codigoLicitacaoItem ?>;
        const routeJugamentoFinalizar = resetRoute('<?= route('julgamento.finalizar') ?>');
        const routeLeiMicroEmpresa = resetRoute('<?= route('julgamento.validarModeloEmpresarialDosFornecedores', ['codigoLicitacao' => $codigoLicitacao, 'codigoLicitacaoItem' => $codigoLicitacaoItem]) ?>');
        const routeLiberarMicroEmpresas = resetRoute('<?= route('julgamento.liberarMicroEmpresas') ?>');
        const routeRegistrarLance = resetRoute('<?= route('julgamento.registrarLance') ?>');
        const routeRegistrarLanceSemValor = resetRoute('<?= route('julgamento.registrarLanceSemValor') ?>');
        const routeReverterLance = resetRoute('<?= route('julgamento.reverterLance') ?>');
        const routeLimparLances = resetRoute('<?= route('julgamento.limparLances') ?>');
        const routeAlterarStatusFornecedor = resetRoute('<?= route('julgamento.alterarStatusFornecedor') ?>');
        const routeObterFornecedoresProposta = resetRoute('<?= route('julgamento.obterFornecedoresProposta', ['codigoLicitacao' => $codigoLicitacao, 'codigoLicitacaoItem' => $codigoLicitacaoItem]) ?>');
        const julgitemstatus = <?= (empty($julgitemstatus)) ? 'null' : $julgitemstatus ?>;
        var currentHighlightedRow = null;
    </script>

    <script type="module" src='<?= url()->to('/') . '/' . $firstSegment ?>/scripts/classes/faseDeLances/julgamentos.js'></script>
</body>

</html>