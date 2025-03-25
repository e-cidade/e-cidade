<!DOCTYPE html>
<html lang="pt-BR">
  
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php require_once 'libs/renderComponents/doc.php'; ?>

  <div style="display: flex; justify-content: space-around; align-items: center; align-content: center; gap: 5px;">

    <?php $component->render('inputs/text/simple', [
      'id' => 'situacaoItemCodigo',
      'placeholder' => 'Codigo',
      'label' => 'Codigo do item',
      'size' => 'lg'
    ]) ?>

    <?php $component->render('inputs/text/simple', [
      'id' => 'situacaoItemCodigo',
      'placeholder' => 'Codigo',
      'label' => 'Codigo do item',
      'size' => 'md'
    ]) ?>

    <?php $component->render('inputs/text/simple', [
      'id' => 'situacaoItemCodigo',
      'placeholder' => 'Codigo',
      'label' => 'Codigo do item',
      'size' => 'sm'
    ]) ?>

  </div>

  <div style="display: flex; justify-content: space-around; align-items: center; align-content: center; gap: 5px;">

    <?php $component->render('inputs/text/simple', [
      'id' => 'situacaoItemCodigo',
      'placeholder' => 'Codigo',
      'label' => 'Codigo do item',
      'size' => 'lg',
      'disabled' => true
    ]) ?>

    <?php $component->render('inputs/text/simple', [
      'id' => 'situacaoItemCodigo',
      'placeholder' => 'Codigo',
      'label' => 'Codigo do item',
      'size' => 'md',
      'disabled' => true
    ]) ?>

    <?php $component->render('inputs/text/simple', [
      'id' => 'situacaoItemCodigo',
      'placeholder' => 'Codigo',
      'label' => 'Codigo do item',
      'size' => 'sm',
      'disabled' => true
    ]) ?>

  </div>
</body>

</html>