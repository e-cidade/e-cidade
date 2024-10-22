<?php require_once 'libs/renderComponents/doc.php'; ?>

<script type="text/javascript" defer>
  loadComponents([
    'selectsChoicesSimple',
  ]);
</script>

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
