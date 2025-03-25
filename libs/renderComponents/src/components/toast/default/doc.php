<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php require_once 'libs/renderComponents/doc.php'; ?>

    <?php $component->render('toast/default') ?>

    <?php $component->render('buttons/solid', [
        'designButton' => 'success',
        'message' => 'Toast Success',
        'onclick' => "createToast('Lorem ipsum dolor sit amet consectetur adipisicing elit.', 'success', 5000)"
    ]); ?>

    <?php $component->render('buttons/solid', [
        'designButton' => 'info',
        'message' => 'Toast Info',
        'onclick' => "createToast('Lorem ipsum dolor sit amet consectetur adipisicing elit.', 'info', 5000)"
    ]); ?>

    <?php $component->render('buttons/solid', [
        'designButton' => 'warning',
        'message' => 'Toast Warning',
        'onclick' => "createToast('Lorem ipsum dolor sit amet consectetur adipisicing elit.', 'warning', 5000)"
    ]); ?>

    <?php $component->render('buttons/solid', [
        'designButton' => 'danger',
        'message' => 'Toast Danger',
        'onclick' => "createToast('Lorem ipsum dolor sit amet consectetur adipisicing elit.', 'danger', 5000)"
    ]); ?>
</body>

</html>