<?php
$id = isset($variaveisComponents['id']) ? htmlspecialchars($variaveisComponents['id']) : 'customModal';
$class = isset($variaveisComponents['class']) ? htmlspecialchars($variaveisComponents['class']) : 'simple-modal';

$idContent = isset($variaveisComponents['idContent']) ? htmlspecialchars($variaveisComponents['idContent']) : '';
$classContent = isset($variaveisComponents['classContent']) ? htmlspecialchars($variaveisComponents['classContent']) : 'simple-modal-content ' . (isset($variaveisComponents['size']) ? htmlspecialchars($variaveisComponents['size']) : 'md');

$classClose = isset($variaveisComponents['classClose']) ? htmlspecialchars($variaveisComponents['classClose']) : 'close';
$idClose = isset($variaveisComponents['idClose']) ? htmlspecialchars($variaveisComponents['idClose']) : '';
$title = isset($variaveisComponents['title']) ? htmlspecialchars($variaveisComponents['title']) : '';

$closeModalFunction = "closeModal('$id')";
?>

<div id="<?= $id ?>" class="<?= $class ?>">
    <div class="<?= $idContent ?> <?= $classContent ?>">
        <span class="<?= $classClose ?>" id="<?= $idClose ?>" onclick="<?= $closeModalFunction ?>">
            &times;
        </span>
        <?php if ($title) : ?>
            <div class="simple-modal-content-title">
                <h2><?= $title ?></h2>
            </div>
        <?php endif; ?>

        <div class="form-modal-body-render-components">
<?php
    unset($id, $class, $idContent, $classContent, $classClose, $idClose, $title, $closeModalFunction);
?>