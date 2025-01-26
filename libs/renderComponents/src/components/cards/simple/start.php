<?php
    // Atribui valores �s vari�veis ou usa valores padr�o
    $id = isset($variaveisComponents['id']) ? $variaveisComponents['id'] : '';
    $class = isset($variaveisComponents['class']) ? $variaveisComponents['class'] : 'simple-card-one';
    $title = isset($variaveisComponents['title']) ? $variaveisComponents['title'] : '';
?>

<div id="<?= $id ?>" class="<?= $class ?>">
    <h5><?= $title ?></h5>

<?php
    // Limpar vari�veis ap�s uso
    unset($id, $class, $title);
?>
