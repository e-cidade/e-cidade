<?php
    // Atribui um valor à variável $id ou define um valor padrão 'tooltip-default'
    $id =  isset($variaveisComponents['id']) 
        ? $variaveisComponents['id'] 
        : 'tooltip-default';

    // Atribui o valor de 'body' à variável $body ou deixa como string vazia se não for definido
    $body = isset($variaveisComponents['body']) 
        ? $variaveisComponents['body'] 
        : '';

    // Atribui o valor de 'size' à variável $size ou define 'auto' como padrão
    $size =  isset($variaveisComponents['size']) 
        ? $variaveisComponents['size'] 
        : 'auto';

    // Inclui o arquivos CSS
    includeOnceAsset('css', '/libs/renderComponents/src/components/tooltip/defaultRightLegacy/defaultRightLegacy.css');
?>

<div id="<?=$id?>" class="tooltip-09343">
    <?= $body ?> 
    <div id="<?=$id?>-body" class="tooltip-text-09343 tooltip-default-09343-<?= $size ?>" style="<?= ($size != 'auto')?: 'white-space: nowrap;'; ?>"></div>
</div>

<?php
    // Limpar variáveis após uso
    unset($id, $body, $size);
?>