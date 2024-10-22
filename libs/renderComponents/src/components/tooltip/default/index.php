<?php
    $id =  isset($variaveisComponents['id']) ? $variaveisComponents['id'] : 'tooltip-default';
    $body = isset($variaveisComponents['body']) ? $variaveisComponents['body'] : '';
    $size =  isset($variaveisComponents['size']) ? $variaveisComponents['size'] : 'auto';
?>

<div id="<?= $id ?>" class="tooltip-default">
    <?= $body ?>
    <span class="tooltip-default-text tooltip-default-<?= $size ?>" style="<?= ($size != 'auto')?: 'white-space: nowrap;'; ?>"></span>
</div>