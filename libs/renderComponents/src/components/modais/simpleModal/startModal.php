<?php
    // Define o valor de `$id` com base em `$variaveisComponents['id']`, se estiver definido, e aplica `htmlspecialchars` para evitar XSS. 
    // Caso contrário, define como 'customModal'.
    $id = isset($variaveisComponents['id']) 
        ? htmlspecialchars($variaveisComponents['id']) 
        : 'customModal';

    // Define o valor de `$class` com base em `$variaveisComponents['class']`, com proteção contra XSS. 
    // Caso contrário, usa a classe padrão 'simple-modal'.
    $class = isset($variaveisComponents['class']) 
        ? htmlspecialchars($variaveisComponents['class']) 
        : 'simple-modal';

    // Define `$idContent` com base em `$variaveisComponents['idContent']`, aplicando `htmlspecialchars` para segurança. 
    // Caso contrário, deixa vazio.
    $idContent = isset($variaveisComponents['idContent']) 
        ? htmlspecialchars($variaveisComponents['idContent']) 
        : '';

    // Define `$classContent` combinando uma classe padrão 'simple-modal-content' com o valor de `$variaveisComponents['size']`, se estiver definido. 
    // Proteção contra XSS é aplicada. Caso contrário, define o tamanho padrão como 'md'.
    $classContent = isset($variaveisComponents['classContent']) 
        ? htmlspecialchars($variaveisComponents['classContent']) 
        : 'simple-modal-content ' . (isset($variaveisComponents['size']) 
            ? htmlspecialchars($variaveisComponents['size']) 
            : 'md');

    // Define `$classClose` com base em `$variaveisComponents['classClose']`, com proteção contra XSS. 
    // Caso contrário, usa a classe padrão 'close'.
    $classClose = isset($variaveisComponents['classClose']) 
        ? htmlspecialchars($variaveisComponents['classClose']) 
        : 'close';

    // Define `$idClose` com base em `$variaveisComponents['idClose']`, aplicando `htmlspecialchars` para segurança. 
    // Caso contrário, deixa vazio.
    $idClose = isset($variaveisComponents['idClose']) 
        ? htmlspecialchars($variaveisComponents['idClose']) 
        : '';

    // Define `$title` com base em `$variaveisComponents['title']`. 
    // Não é aplicado `htmlspecialchars` aqui, o que pode ser uma escolha intencional, mas deve-se tomar cuidado com entradas não confiáveis.
    $title = isset($variaveisComponents['title']) 
        ? $variaveisComponents['title'] 
        : '';

    // Define uma string JavaScript para fechar o modal, passando o `$id` como argumento para a função `closeModal`.
    $closeModalFunction = "closeModal('$id')";

    // Inclui o arquivos CSS
    includeOnceAsset('css', '/libs/renderComponents/src/components/modais/simpleModal/simpleModal.css');

    // Inclui o arquivos JavaScript
    includeOnceAsset('js', '/libs/renderComponents/src/components/modais/simpleModal/simpleModal.js');
    includeOnceAsset('js', '/libs/renderComponents/src/components/modais/modais.js');
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