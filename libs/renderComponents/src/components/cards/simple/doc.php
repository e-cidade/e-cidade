
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php require_once 'libs/renderComponents/doc.php'; ?>
    
    <?php $component->render('cards/simple/start', ['title' => 'Contratações Diretas', 'size' => 'sm'], true) ?>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cum, aspernatur! Autem deserunt maxime soluta, suscipit vitae est delectus eius eveniet. Illum in quae consectetur suscipit sunt maxime reprehenderit quo blanditiis!</p>
    <?php $component->render('cards/simple/end', [], true) ?>
    
    <div style="margin: 30px;"></div>
    
    <?php $component->render('cards/simple/start', ['title' => 'Contratações Diretas', 'size' => 'md'], true) ?>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cum, aspernatur! Autem deserunt maxime soluta, suscipit vitae est delectus eius eveniet. Illum in quae consectetur suscipit sunt maxime reprehenderit quo blanditiis!</p>
    <?php $component->render('cards/simple/end', [], true) ?>
    
    <div style="margin: 30px;"></div>
    
    <?php $component->render('cards/simple/start', ['title' => 'Contratações Diretas', 'size' => 'lg'], true) ?>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cum, aspernatur! Autem deserunt maxime soluta, suscipit vitae est delectus eius eveniet. Illum in quae consectetur suscipit sunt maxime reprehenderit quo blanditiis!</p>
    <?php $component->render('cards/simple/end', [], true) ?>
    
    <div style="margin: 30px;"></div>
    
    <?php $component->render('cards/simple/start', ['title' => 'Contratações Diretas'], true) ?>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cum, aspernatur! Autem deserunt maxime soluta, suscipit vitae est delectus eius eveniet. Illum in quae consectetur suscipit sunt maxime reprehenderit quo blanditiis!</p>
    <?php $component->render('cards/simple/end', [], true) ?>
</body>

</html>
