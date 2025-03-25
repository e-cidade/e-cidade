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
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link rel="stylesheet" href="<?= url()->to('/') . '/' . $firstSegment ?>/public/assets/css/lic_fasedelance.css">
    <link rel="stylesheet" href="<?= url()->to('/') . '/' . $firstSegment ?>/public/assets/css/lic_fasedelance003.css">
</head>

<body bgcolor=#f5fffb leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">

    <?php $component->render('toast/default') ?>
    <?php $component->render('icons') ?>

    <div class="container">
        <div class="row">

            <div class="col-12">
                <?php $component->render('buttons/solid', [
                    'id' => 'btnCancelar',
                    'message' => '<i class="icon arrow-uturn-left"></i> Voltar',
                    'size' => 'sm',
                    'designButton' => 'secondary',
                    'onclick' => 'window.location.href = `' . url()->to('/') . '/' . $firstSegment .'/w/0/web/patrimonial/licitacoes/procedimentos/julgamento-por-lance/fase-de-lances?redirect=' . $data['licitacaoCodigo'] .'`;'
                ]); ?>
            </div>

            <div class="col-12">
                <div class="divider-row">
                    <span class="divider-label">Dados da Proposta</span>
                </div>
            </div>

            <input id="tipojulg" type="hidden">

            <div class="col xs-12 sm-12 md-6 lg-4 xl-4">
                <?php $component->render('inputs/text/simple', [
                    'id' => 'licitacao',
                    'placeholder' => StringHelper::toUtf8('Preenchimento da código da licitação'),
                    'label' => StringHelper::toUtf8('Código da Licitação:'),
                    'size' => 'md',
                    'disabled' => true,
                    'value' => $data['licitacaoCodigo']
                ]) ?>
            </div>

            <div class="col xs-12 sm-12 md-6 lg-4 xl-4">
                <?php $component->render('inputs/text/simple', [
                    'id' => 'lote',
                    'placeholder' => StringHelper::toUtf8('Preenchimento do código do lote'),
                    'label' => StringHelper::toUtf8('Código do Lote:'),
                    'size' => 'md',
                    'disabled' => true,
                    'value' => $data['numeroLoteCodigo'],
                ]) ?>
            </div>

            <div class="col xs-12 sm-12 md-6 lg-4 xl-4">
                <?php $component->render('inputs/text/simple', [
                    'id' => 'proposta',
                    'placeholder' => StringHelper::toUtf8('Preenchimento do código da proposta'),
                    'label' => StringHelper::toUtf8('Código da Proposta:'),
                    'size' => 'md',
                    'disabled' => true,
                    'value' => $data['propostaCodigo']
                ]) ?>
            </div>

            <div class="col-12">
                <div class="divider-row">
                    <span class="divider-label">Dados da Fornecedor</span>
                </div>
            </div>

            <div class="col xs-12 sm-12 md-6 lg-4 xl-4">
                <?php $component->render('inputs/text/simple', [
                    'id' => 'fornecedor',
                    'placeholder' => StringHelper::toUtf8('Preenchimento do código da fornecedor'),
                    'label' => StringHelper::toUtf8('Código:'),
                    'size' => 'md',
                    'disabled' => true,
                    'value' => $data['fornecedoresDados']->pc21_orcamforne
                ]) ?>
            </div>

            <div class="col xs-12 sm-12 md-6 lg-4 xl-4">
                <?php $component->render('inputs/text/simple', [
                    'id' => 'razaoSocial',
                    'placeholder' => StringHelper::toUtf8('Preenchimento da razão social do fornecedor'),
                    'label' => StringHelper::toUtf8('Razão Social:'),
                    'size' => 'md',
                    'disabled' => true,
                    'value' => $data['fornecedoresDados']->z01_nomecomple
                ]) ?>
            </div>

            <div class="col xs-12 sm-12 md-6 lg-4 xl-4">
                <?php $component->render('inputs/text/simple', [
                    'id' => 'cnpj',
                    'placeholder' => StringHelper::toUtf8('Preenchimento do cnpj do fornecedor'),
                    'label' => StringHelper::toUtf8('Cnpj:'),
                    'size' => 'md',
                    'disabled' => true,
                    'value' => StringHelper::formatCnpjCpf($data['fornecedoresDados']->z01_cgccpf)
                ]) ?>
            </div>

            <div class="col-12">
                <div class="divider-row">
                    <span class="divider-label">Itens da Proposta</span>
                </div>
            </div>

            <div class="col-12">
                <div class="header-table-6734N">
                    <div class="header-table-row-6734N row">
                        <div class="col xs-12 sm-12 md-12 lg-6 xl-6 status-itens-VE02C1">
                            <div>
                                <span style="color: gray; font-size: 0.8rem;"><?= StringHelper::toUtf8('Itens restantes para preenchimento: ') ?></span>
                                <strong id="contadorItens">0</strong>
                            </div>

                            <div>
                                <span style="color: gray; font-size: 0.8rem;"><?= StringHelper::toUtf8('Valor disponível restante: ') ?></span>
                                <strong id="valorRestante">R$ <?= number_format($data['lanceVencedor'], 2, ',', '.') ?></strong>
                                <span style="color: gray; font-size: 0.7rem;"> | De: R$ <?= number_format($data['lanceVencedor'], 2, ',', '.') ?></span>
                            </div>
                        </div>
                        
                        <div class="col xs-12 sm-12 md-12 lg-6 xl-6 botoes-itens-VE02C1">
                            <?php $component->render('buttons/solid', [
                                'id' => 'salvarBtn1',
                                'message' => '<i class="icon device-floppy"></i> Salvar',
                                'designButton' => 'success',
                                'disabled' => true,
                                'size' => 'sm',
                                'onclick' => 'salvarProposta()'
                            ]); ?>
                            
                            <?php $component->render('tooltip/default', [
                                'targetEl' => 'salvarBtnTooltip',
                                'triggerEl' => 'salvarBtn1',
                                'options' => [
                                    'placement' => 'bottom'
                                ],
                                'body' => '...',
                                'size' => 'auto'
                            ]) ?>

                            <?php $component->render('buttons/solid', [
                                'id' => 'limparBtn',
                                'message' => '<i class="icon trash"></i> Limpar',
                                'designButton' => 'danger',
                                'size' => 'sm',
                                'onclick' => 'deletarProposta()'
                            ]); ?>

                            <?php $component->render('buttons/solid', [
                                'id' => 'importarBtn',
                                'message' => '<i class="icon arrow-up-tray"></i> Importar',
                                'onclick' => 'importarItens()',
                                'size' => 'sm'
                            ]); ?>
                            
                            <?php $component->render('buttons/solid', [
                                'id' => 'exportarBtn',
                                'message' => '<i class="icon arrow-down-tray"></i> Exportar',
                                'size' => 'sm',
                                'onclick' => 'exportarItens()'
                            ]); ?>
                        </div>
                    </div>
                </div>

                <div class="table-container-VE02C1">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 55px;"><?= StringHelper::toUtf8('Ordem') ?></th>
                                <th style="width: 60px;"><?= StringHelper::toUtf8('Item') ?></th>
                                <th style="width: 130px;"><?= StringHelper::toUtf8('Descrição') ?></th>
                                <th style="width: 130px;"><?= StringHelper::toUtf8('Lote') ?></th>
                                <th style="width: 90px;"><?= StringHelper::toUtf8('Unidade') ?></th>
                                <th style="width: 115px;"><?= StringHelper::toUtf8('Quantidade') ?></th>
                                <th style="width: 115px;"><?= StringHelper::toUtf8('Percentual') ?></th>
                                <th style="width: 200px;"><?= StringHelper::toUtf8('Vlr Unitário') ?> </th>
                                <th style="width: 130px;"><?= StringHelper::toUtf8('Vlr Total') ?></th>
                                <th style="width: 70px;"><?= StringHelper::toUtf8('Marca') ?></th>
                            </tr>
                        </thead>
                        <tbody id="tbodyItens">
                            <?php foreach ($data['itens'] as $i => $item): ?>
                                <tr>
                                    <td><span class="ordem" data-orcamitem="<?= $item->pc22_orcamitem ?>"><?= $item->l21_ordem ?></span></td>
                                    <td><?= $item->pc01_codmater ?></td>
                                    <td><?= $item->pc01_descrmater ?></td>
                                    <td><?= $item->l04_descricao ?></td>
                                    <td><?= $item->unidade ?></td>
                                    <td><span class="quantidade" data-orcamitem="<?= $item->pc22_orcamitem ?>"><?= $item->quantidade ?></span></td>
                                    <td><?= $item->percentual ?? '-' ?></td>
                                    <td><input type="text" name="vlrUnitario" id="vlrUnitario<?= $i ?>" class="vlrUnitario" data-orcamitem="<?= $item->pc22_orcamitem ?>" placeholder="R$ 0,00" value="<?= $item->vlr_unitario ?? '' ?>" style="border-radius: 0.5rem; border: 1px solid gray; padding: 5px; text-align: center;"></td>
                                    <td class="valorTotal"><?= $item->vlr_total ?? 'R$ ' . number_format(0, 2, ',', '.') ?></td>
                                    <td><input type="text" name="marca" id="marca" class="marca" data-orcamitem="<?= $item->pc22_orcamitem ?>" disabled value="<?= $item->marca ?? '' ?>" style="width: 100px; border-radius: 0.5rem; border: 1px solid gray; padding: 5px; text-align: center; background: #e7e7e7; cursor: not-allowed;"></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php $component->render('mask/cleave') ?>

    <script>
        const lanceVencedor = '<?= $data['lanceVencedor'] ?>';

        function resetRoute(urlPathRouteApp) {
            let partUrlPathRouteApp = urlPathRouteApp.split('/web');
            return `${partUrlPathRouteApp[0]}/${specificPartUrlPathCurrent}/web${partUrlPathRouteApp[1]}`;
        }

        let urlPathCurrent = window.location.pathname;
        let partUrlPathCurrent = urlPathCurrent.split('/');
        const specificPartUrlPathCurrent = partUrlPathCurrent[1];

        var propostaExistente = <?= $data['propostaExistente'] ? 'true' : 'false'; ?>;
        var limparBtn = document.getElementById("limparBtn");
        limparBtn.style.display = propostaExistente ? "block" : "none";

        const routeObterItensDaReadequecaoDeProposta = resetRoute('<?= route('readequarProposta.obterItensDaReadequecaoDeProposta', ['codigoLicitacao' => $data['licitacaoCodigo'], 'codigoOrcamforne' => $data['fornecedoresDados']->pc21_orcamforne, 'codigoLote' => $data['numeroLoteCodigo']]) ?>');
        const routeVerificarPropostaExistente = resetRoute('<?= route('readequarProposta.verificarPropostaExistente') ?>');
        const routeSalvarProposta = resetRoute('<?= route('readequarProposta.salvarProposta') ?>');
        const routeDeletarProposta = resetRoute('<?= route('readequarProposta.deletarProposta') ?>');
        const routeExportarItens = '<?= url()->to('/') . '/' . $firstSegment ?>/w/0/';
        const routeImportarItens = resetRoute('<?= route('readequarProposta.importarItens') ?>');
    </script>
    
    <script type="module" src='<?= url()->to('/') . '/' . $firstSegment ?>/scripts/classes/faseDeLances/readequarProposta.js'></script>
</body>

</html>