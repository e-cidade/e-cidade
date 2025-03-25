<?php
    // Atribui o valor de 'id' se estiver definido em $variaveisComponents, senão atribui uma string vazia
    $id = isset($variaveisComponents['id']) 
        ? $variaveisComponents['id'] 
        : '';

    // Atribui o valor de 'class' se estiver definido em $variaveisComponents, senão atribui o valor padrão 'simple-card-559YV'
    $class = isset($variaveisComponents['class']) 
        ? $variaveisComponents['class'] 
        : 'simple-card-559YV';
    
    // Atribui o valor de 'classTitle' se estiver definido em $variaveisComponents, senão atribui o valor padrão 'simple-card-559YV'
    $classTitle = isset($variaveisComponents['classTitle']) 
        ? $variaveisComponents['classTitle'] 
        : 'simple-card-title-559YV ' .
            (isset($variaveisComponents['size']) 
                ? $variaveisComponents['size'] . '-559YV' 
                : 'md-559YV');

    // Atribui o valor de 'title' se estiver definido em $variaveisComponents, senão atribui uma string vazia
    $title = isset($variaveisComponents['title']) 
        ? $variaveisComponents['title'] 
        : '';

    // Inclui o arquivos CSS
    includeOnceAsset('css', '/libs/renderComponents/src/components/cards/simple/simple.css');

    // Inclui o arquivos JavaScript
    includeOnceAsset('js', '/libs/renderComponents/src/components/cards/simple/simple.js');
?>

<div id="<?= $id ?>" class="<?= $class ?>">
    <?php if($title): ?>
        <p class="<?= $classTitle ?>">
            <?= $title ?>
        </p>
    <?php endif; ?>
    
<?php
    // Limpar variáveis após uso
    unset($id, $class, $title);
?>
