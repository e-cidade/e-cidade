<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php require_once 'libs/renderComponents/doc.php'; ?>

  <div style="display: flex; justify-content: center; align-items: center; align-content: center; gap: 5px;">

    <?php $component->render('inputs/date/simple', [
      'id' => 'data_inicial',
      'placeholder' => 'Escolha uma data inicial',
      'name' => 'data_inicial',
      'required' => true,
      'label' => 'Data Inicial:'
    ]) ?>

    <?php $component->render('inputs/date/simple', [
      'id' => 'data_final',
      'placeholder' => 'Escolha uma data final',
      'name' => 'data_final',
      'required' => true,
      'label' => 'Data Final:'
    ]) ?>

  </div>

  <div style="display: flex; justify-content: center; align-items: center; align-content: center; gap: 5px;">

    <?php $component->render('inputs/date/simple', [
      'id' => 'data_inicial',
      'name' => 'data_inicial',
      'label' => 'Data Inicial:'
    ]) ?>

    <?php $component->render('inputs/date/simple', [
      'id' => 'data_final',
      'name' => 'data_final',
      'label' => 'Data Final:'
    ]) ?>

  </div>

  <div style="display: flex; justify-content: center; align-items: center; align-content: center; gap: 5px;">

    <?php $component->render('inputs/date/simple', [
      'id' => 'data_inicial',
      'name' => 'data_inicial',
      'disabled' => true,
      'label' => 'Data Inicial:'
    ]) ?>

    <?php $component->render('inputs/date/simple', [
      'id' => 'data_final',
      'name' => 'data_final',
      'disabled' => true,
      'label' => 'Data Final:'
    ]) ?>

  </div>
</body>

</html>