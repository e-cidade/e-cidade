<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php require_once 'libs/renderComponents/doc.php'; ?>

    <!-- Primary -->
    <?= $component->render('buttons/solid', [
        'designButton' => 'primary',
        'size' => 'xl'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'primary',
        'size' => 'lg'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'primary',
        'size' => 'md'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'primary',
        'size' => 'sm'
    ]); ?>

    <hr>

    <!-- Secondary -->
    <?= $component->render('buttons/solid', [
        'designButton' => 'secondary',
        'size' => 'xl'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'secondary',
        'size' => 'lg'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'secondary',
        'size' => 'md'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'secondary',
        'size' => 'sm'
    ]); ?>

    <hr>

    <!-- Success -->
    <?= $component->render('buttons/solid', [
        'designButton' => 'success',
        'size' => 'xl'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'success',
        'size' => 'lg'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'success',
        'size' => 'md'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'success',
        'size' => 'sm'
    ]); ?>

    <hr>

    <!-- Danger -->
    <?= $component->render('buttons/solid', [
        'designButton' => 'danger',
        'size' => 'xl'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'danger',
        'size' => 'lg'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'danger',
        'size' => 'md'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'danger',
        'size' => 'sm'
    ]); ?>

    <hr>

    <!-- Warning -->
    <?= $component->render('buttons/solid', [
        'designButton' => 'warning',
        'size' => 'xl'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'warning',
        'size' => 'lg'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'warning',
        'size' => 'md'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'warning',
        'size' => 'sm'
    ]); ?>

    <hr>

    <!-- Info -->
    <?= $component->render('buttons/solid', [
        'designButton' => 'info',
        'size' => 'xl'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'info',
        'size' => 'lg'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'info',
        'size' => 'md'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'info',
        'size' => 'sm'
    ]); ?>

    <hr>

    <!-- Disabled -->
    <?= $component->render('buttons/solid', [
        'designButton' => 'disabled',
        'size' => 'xl'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'disabled',
        'size' => 'lg'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'disabled',
        'size' => 'md'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'designButton' => 'disabled',
        'size' => 'sm'
    ]); ?>

    <hr>

    <!-- Disabled -->
    <?= $component->render('buttons/solid', [
        'size' => 'xl'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'size' => 'lg'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'size' => 'md'
    ]); ?>

    <?= $component->render('buttons/solid', [
        'size' => 'sm'
    ]); ?>
</body>

</html>