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

    <?php $component->render('inputs/text/anchor', [
      'id' => 'codigo',
      'idModal' => 'customModal',
      'idGrid' => 'anchorTest',
      'placeholder' => 'Selecione processo',
      'label' => 'Codigo:',
      'size' => 'md',
      'disabled' => true,
    ]) ?>

    <?php $component->render('modais/simpleModal/startModal', [
      'id' => 'customModal',
      'size' => 'full'
    ], true); ?>
      <div style="margin-top: 40px;"></div>
      <?php $component->render('tables/gridJs/serverSideLegacy', [
        'id' => 'anchorTest',
        'columns' => $columns,
        'perPage' => 10,
        'searchEnabled' => true,
        'sortEnabled' => true,
        'rowDoubleClick' => true,
        'rowDoubleClickFunction' => 'licitacoesDoubleClick',
      ]); ?>
    <?php $component->render('modais/simpleModal/endModal', [], true); ?>

  </div>
</body>

</html>