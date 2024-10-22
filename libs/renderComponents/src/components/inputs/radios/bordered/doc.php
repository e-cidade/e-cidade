<?php require_once 'libs/renderComponents/doc.php'; ?>

<script type="text/javascript">
  loadComponents([
    'radiosBordered',
  ]);
</script>

<div style="display: flex; justify-content: center; align-items: center; align-content: center; gap: 5px;">
    <?php $component->render('inputs/radios/bordered', ['label' => 'Pepsi', 'id' => 'pepsi', 'value' => 'pepsi', 'name' => 'refrigerante']) ?>
    <?php $component->render('inputs/radios/bordered', ['label' => 'Coca-cola', 'id' => 'coca-cola', 'value' => 'coca-cola', 'name' => 'refrigerante', 'checked' => true]) ?>
</div>