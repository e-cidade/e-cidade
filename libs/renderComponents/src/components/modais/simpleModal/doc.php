<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php require_once 'libs/renderComponents/doc.php'; ?>

    <!-- Modal Full -->
    <?php $component->render('modais/simpleModal/startModal', [
        'title' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit',
        'id' => 'customModalFull',
        'size' => 'full'
    ], true); ?>
        <div>Lorem ipsum dolor...</div>
    <?php $component->render('modais/simpleModal/endModal', [], true); ?>

    <?php $component->render('buttons/solid', [
        'designButton' => 'primary',
        'onclick' => "openModal('customModalFull')",
        'message' => 'Modal full',
    ]); ?>

    <!-- Modal Xl -->
    <?php $component->render('modais/simpleModal/startModal', [
        'title' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit',
        'id' => 'customModalXl',
        'size' => 'xl'
    ], true); ?>
        <div>Lorem ipsum dolor...</div>
    <?php $component->render('modais/simpleModal/endModal', [], true); ?>

    <?php $component->render('buttons/solid', [
        'designButton' => 'primary',
        'onclick' => "openModal('customModalXl')",
        'message' => 'Modal xl',
    ]); ?>

    <!-- Modal Lg -->
    <?php $component->render('modais/simpleModal/startModal', [
        'title' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit',
        'id' => 'customModalLg',
        'size' => 'lg'
    ], true); ?>
        <div>Lorem ipsum dolor...</div>
    <?php $component->render('modais/simpleModal/endModal', [], true); ?>

    <?php $component->render('buttons/solid', [
        'designButton' => 'primary',
        'onclick' => "openModal('customModalLg')",
        'message' => 'Modal lg',
    ]); ?>

    <!-- Modal Md -->
    <?php $component->render('modais/simpleModal/startModal', [
        'title' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit',
        'id' => 'customModalMd',
        'size' => 'md'
    ], true); ?>
        <div>Lorem ipsum dolor...</div>
    <?php $component->render('modais/simpleModal/endModal', [], true); ?>

    <?php $component->render('buttons/solid', [
        'designButton' => 'primary',
        'onclick' => "openModal('customModalMd')",
        'message' => 'Modal md',
    ]); ?>

    <!-- Modal Sm -->
    <?php $component->render('modais/simpleModal/startModal', [
        'title' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit',
        'id' => 'customModalSm',
        'size' => 'sm'
    ], true); ?>
        <div>Lorem ipsum dolor...</div>
    <?php $component->render('modais/simpleModal/endModal', [], true); ?>

    <?php $component->render('buttons/solid', [
        'designButton' => 'primary',
        'onclick' => "openModal('customModalSm')",
        'message' => 'Modal sm',
    ]); ?>

    <hr>

    <!-- Modal Lg Custtom Close-->
    <?php $component->render('modais/simpleModal/startModal', [
        'title' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit',
        'id' => 'customModalLgCusttomClose',
        'size' => 'lg'
    ], true); ?>
        <div>Lorem ipsum dolor...</div>

        <?php $component->render('buttons/solid', [
            'designButton' => 'info',
            'onclick' => "closeModal('customModalLgCusttomClose')",
            'message' => 'Fechar',
        ]); ?>
    <?php $component->render('modais/simpleModal/endModal', [], true); ?>

    <?php $component->render('buttons/solid', [
        'designButton' => 'primary',
        'onclick' => "openModal('customModalLgCusttomClose')",
        'message' => 'Modal Custom Close',
    ]); ?>
</body>

</html>