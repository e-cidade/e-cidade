<?php require_once 'libs/renderComponents/doc.php'; ?>

<script type="text/javascript">
  loadComponents([
    'dateSimple',
  ]);
</script>

<div style="display: flex; justify-content: space-around; align-items: center; align-content: center; gap: 5px;">

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
    'label' => 'Data Final:'
  ]) ?>

</div>