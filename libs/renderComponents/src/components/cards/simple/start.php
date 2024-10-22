<?php
    // Atribui valores às variáveis ou usa valores padrão
    $id = isset($variaveisComponents['id']) ? $variaveisComponents['id'] : '';
    $class = isset($variaveisComponents['class']) ? $variaveisComponents['class'] : 'simple-card-one';
    $title = isset($variaveisComponents['title']) ? $variaveisComponents['title'] : '';
?>

<div id="<?= $id ?>" class="<?= $class ?>">
    <h5><?= $title ?></h5>

<?php
    // Limpar variáveis após uso
    unset($id, $class, $title);
?>
