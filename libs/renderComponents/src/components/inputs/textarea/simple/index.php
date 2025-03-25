<?php
    // Atribui um valor à variável $id ou usa 'default-textarea' como valor padrão, caso não esteja definido
    $id = isset($variaveisComponents['id']) 
        ? $variaveisComponents['id'] 
        : 'default-textarea';

    // Atribui um valor à variável $placeholder ou usa 'Write your thoughts here...' como valor padrão
    $placeholder = isset($variaveisComponents['placeholder']) 
        ? $variaveisComponents['placeholder'] 
        : 'Write your thoughts here...';

    // Atribui um valor à variável $inputClass ou usa 'textarea-input' como valor padrão
    $inputClass = isset($variaveisComponents['inputClass']) 
        ? $variaveisComponents['inputClass'] 
        : 'textarea-input';

    // Atribui um valor à variável $label ou usa 'Your message' como valor padrão
    $label = isset($variaveisComponents['label']) 
        ? $variaveisComponents['label'] 
        : 'Your message';

    // Atribui um valor à variável $name ou usa uma string vazia como valor padrão
    $name = isset($variaveisComponents['name']) 
        ? $variaveisComponents['name'] 
        : '';

    // Atribui um valor à variável $value ou usa uma string vazia como valor padrão
    $value = isset($variaveisComponents['value']) 
        ? $variaveisComponents['value'] 
        : '';

    // Define a variável $required como 'required' se a chave correspondente for verdadeira, ou como falso caso contrário
    $required = isset($variaveisComponents['required']) && $variaveisComponents['required'] 
        ? 'required' 
        : false;

    // Define o valor da classe de tamanho com base na chave 'size', ou usa 'textarea-container-md' como padrão
    $size = isset($variaveisComponents['size']) 
        ? 'textarea-container-'.$variaveisComponents['size'] 
        : 'textarea-container-md';

    // Define a variável $disabled como 'disabled' se o valor correspondente for verdadeiro, ou como uma string vazia caso contrário
    $disabled = (isset($variaveisComponents['disabled']) && $variaveisComponents['disabled']) 
        ? 'disabled' 
        : '';

    // Inicializa a variável $customAttributes como uma string vazia
    $customAttributes = '';

    if (isset($variaveisComponents['attributes']) && is_array($variaveisComponents['attributes'])) {
        foreach ($variaveisComponents['attributes'] as $key => $value) {
            // Adiciona cada atributo e valor à string $customAttributes, escapando caracteres especiais
            $customAttributes .= htmlspecialchars($key) . '="' . htmlspecialchars($value) . '" ';
        }
    }

    // Inclui o arquivos CSS
    includeOnceAsset('css', '/libs/renderComponents/src/components/inputs/textarea/simple/simple.css');

    // Inclui o arquivos JavaScript
    includeOnceAsset('js', '/libs/renderComponents/src/components/inputs/textarea/simple/simple.js');
?>

<div class="textarea-container">
    <label for="<?= $id ?>" class="label">
        <?= $label ?>
        <?php if ($required): ?>
            <i class="icon asterisk"></i>
        <?php endif; ?>
    </label>

    <textarea
        <?= (!empty($disabled))?'style="cursor: not-allowed;"':''?>
        id="<?= $id ?>"
        name="<?= $name ?>"
        rows="4"
        class="<?= $inputClass ?> <?= $size ?>"
        placeholder="<?= $placeholder ?>"
        <?= $disabled ?>
        <?= $required ?>
        <?= $customAttributes ?>
    ><?= htmlspecialchars($value) ?></textarea>
</div>

<?php
    // Limpar variáveis após uso
    unset($id, $placeholder, $inputClass, $label, $name, $value, $required, $size, $disabled, $customAttributes);
?>
