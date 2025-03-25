<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php require_once 'libs/renderComponents/doc.php'; ?>

    <?php $component->render('tooltip/defaultRightLegacy', [
        'id' => 'tooltip-default',
        'body' => 'Custom message tooltip',
        'size' => 'lg'
    ]) ?>
</body>

</html>
