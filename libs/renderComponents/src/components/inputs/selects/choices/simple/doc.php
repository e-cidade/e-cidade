<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>    
    <?php require_once 'libs/renderComponents/doc.php'; ?>

    <?php $component->render('inputs/selects/choices/simple', [
        'id' => 'custom-id-lg',
        'name' => 'custom-name',
        'label' => 'Custom label lg:',
        'startIndex' => 1,
        'size' => 'lg',
        'options' => [
            '1 - Um',
            '2 - Dois',
            '3 - Três',
            '4 - Quatro',
            '5 - Cinco',
            '6 - Seis',
            '7 - Sete',
            '8 - Oito',
            '9 - Nove',
            '10 - Dez'
        ],
    ]) ?>

    <hr>

    <?php $component->render('inputs/selects/choices/simple', [
        'id' => 'custom-id-md',
        'name' => 'custom-name',
        'label' => 'Custom label md:',
        'startIndex' => 1,
        'size' => 'md',
        'options' => [
            '1 - Um',
            '2 - Dois',
            '3 - Três',
            '4 - Quatro',
            '5 - Cinco',
            '6 - Seis',
            '7 - Sete',
            '8 - Oito',
            '9 - Nove',
            '10 - Dez'
        ],
    ]) ?>

    <hr>

    <?php $component->render('inputs/selects/choices/simple', [
        'id' => 'custom-id-sm',
        'name' => 'custom-name',
        'label' => 'Custom label sm:',
        'startIndex' => 1,
        'size' => 'sm',
        'options' => [
            '1 - Um',
            '2 - Dois',
            '3 - Três',
            '4 - Quatro',
            '5 - Cinco',
            '6 - Seis',
            '7 - Sete',
            '8 - Oito',
            '9 - Nove',
            '10 - Dez'
        ],
    ]) ?>

    <hr>
</body>

</html>