<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php require_once 'libs/renderComponents/doc.php'; ?>

    <div style="display: flex; justify-content: space-around; margin-top: 50px;">
        <!-- Bottom -->
        <?= $component->render('buttons/solid', [
            'designButton' => 'primary',
            'id' => 'button1',
            'message' => 'Exibir tooltip bottom',
            'size' => 'md'
        ]); ?>
        <?php $component->render('tooltip/default', [
            'targetEl' => 'tooltip1',
            'triggerEl' => 'button1',
            'options' => [
                'placement' => 'bottom'
            ],
            'body' => 'Dica de mensagem personalizada no fundo.',
            'size' => 'auto'
        ]) ?>

        <!-- Top -->
        <?= $component->render('buttons/solid', [
            'designButton' => 'success',
            'id' => 'button2',
            'message' => 'Exibir tooltip top',
            'size' => 'md'
        ]); ?>
        <?php $component->render('tooltip/default', [
            'targetEl' => 'tooltip2',
            'triggerEl' => 'button2',
            'options' => [
                'placement' => 'top'
            ],
            'body' => 'Dica de mensagem personalizada no topo.',
            'size' => 'auto'
        ]) ?>

        <!-- Right -->
        <?= $component->render('buttons/solid', [
            'designButton' => 'info',
            'id' => 'button3',
            'message' => 'Exibir tooltip right',
            'size' => 'md'
        ]); ?>
        <?php $component->render('tooltip/default', [
            'targetEl' => 'tooltip3',
            'triggerEl' => 'button3',
            'options' => [
                'placement' => 'right'
            ],
            'body' => 'Dica de mensagem personalizada na direita.',
            'size' => 'auto'
        ]) ?>

        <!-- Left -->
        <?= $component->render('buttons/solid', [
            'designButton' => 'warning',
            'id' => 'button4',
            'message' => 'Exibir tooltip left',
            'size' => 'md'
        ]); ?>
        <?php $component->render('tooltip/default', [
            'targetEl' => 'tooltip4',
            'triggerEl' => 'button4',
            'options' => [
                'placement' => 'left'
            ],
            'body' => 'Dica de mensagem personalizada na esquerda.',
            'size' => 'auto'
        ]) ?>
    </div>

    <hr>

    <div style="display: flex; justify-content: center; margin-top: 50px;">

        <!-- Bottom -->
        <?= $component->render('buttons/solid', [
            'designButton' => 'danger',
            'id' => 'button5',
            'message' => 'Exibir tooltip onHide e onShow',
            'size' => 'md'
        ]); ?>
        <?php $component->render('tooltip/default', [
            'targetEl' => 'tooltip5',
            'triggerEl' => 'button5',
            'options' => [
                'placement' => 'bottom',
                'onHide' => 'testOnHide()',
                'onShow' => 'testOnShow()'
            ],
            'body' => 'Mensagem personalizada de dica de ferramenta na parte inferior, com funções onHide e onShow (abra o console do navegador).',
            'size' => 'auto'
        ]) ?>

        <script>
            function testOnShow() {
                console.log('onShow');
            }

            function testOnHide() {
                console.log('onHide');
            }
        </script>
    </div>
</body>

</html>