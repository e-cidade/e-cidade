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
        <?php $component->render('inputs/radios/bordered', [
            'label' => 'Pepsi', 
            'id' => 'pepsi', 
            'value' => 'pepsi', 
            'required' => true, 
            'name' => 'refrigerante'
        ]) ?>
    
        <?php $component->render('inputs/radios/bordered', [
            'label' => 'Coca-cola', 
            'id' => 'coca-cola', 
            'value' => 'coca-cola', 
            'required' => true, 
            'name' => 'refrigerante', 
            'checked' => true
        ]) ?>
    </div>
    
    <div style="display: flex; justify-content: center; align-items: center; align-content: center; gap: 5px;">
        <?php $component->render('inputs/radios/bordered', [
            'label' => 'Android', 
            'id' => 'android', 
            'value' => 'android', 
            'name' => 'sistema'
        ]) ?>
    
        <?php $component->render('inputs/radios/bordered', [
            'label' => 'Iphone', 
            'id' => 'iphone', 
            'value' => 'iphone', 
            'name' => 'sistema', 
        ]) ?>
    </div>
</body>

</html>
