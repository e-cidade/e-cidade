<?php
    // Atribui um valor à variável $id ou define um valor padrão 'tooltip-default-F071PM'
    $id =  isset($variaveisComponents['id']) 
        ? $variaveisComponents['id'] 
        : 'tooltip-default-F071PM';

    // Atribui o valor de 'body' à variável $body ou deixa como string vazia se não for definido
    $body = isset($variaveisComponents['body']) 
        ? $variaveisComponents['body'] 
        : '';

    // Atribui o valor de 'size' à variável $size ou define 'auto' como padrão
    $size =  isset($variaveisComponents['size']) 
        ? $variaveisComponents['size'] 
        : 'auto';

    includeOnceAsset('css', '/libs/renderComponents/src/components/tooltip/defaultLegacy/defaultLegacy.css');
?>

<div id="<?= $id ?>" class="tooltip-default-F071PM">
    <?= $body ?>
    <span class="tooltip-default-F071PM-text tooltip-default-F071PM-<?= $size ?>" style="<?= ($size != 'auto')?: 'white-space: nowrap;'; ?>"></span>
</div>

<?php
    // Limpar variáveis após uso
    unset($id, $body, $size);
?>