<?php
    // Define o ID único do botão de rádio ou usa um valor padrão
    $id = isset($variaveisComponents['id']) 
        ? $variaveisComponents['id'] 
        : 'default-radio';

    // Define o nome do grupo de botões de rádio ou usa 'radio-group' como padrão
    $name = isset($variaveisComponents['name']) 
        ? $variaveisComponents['name'] 
        : 'radio-group';

    // Verifica se o botão deve estar marcado inicialmente
    $checked = isset($variaveisComponents['checked']) && $variaveisComponents['checked'] 
        ? 'checked' 
        : '';

    // Define o texto do rótulo associado ao botão de rádio
    $label = isset($variaveisComponents['label']) 
        ? $variaveisComponents['label'] 
        : 'Default radio';

    // Define as classes CSS para o container do botão de rádio ou usa um valor padrão
    $containerClass = isset($variaveisComponents['containerClass']) 
        ? $variaveisComponents['containerClass'] 
        : 'radio-container-075RN';

    // Define as classes CSS para o elemento `<input>` do botão de rádio
    $inputClass = isset($variaveisComponents['inputClass']) 
        ? $variaveisComponents['inputClass'] 
        : 'radio-input-075RN';

    // Define as classes CSS para o rótulo associado ao botão de rádio
    $labelClass = isset($variaveisComponents['labelClass']) 
        ? $variaveisComponents['labelClass'] 
        : 'radio-label-075RN';

    // Define o valor do botão de rádio ou usa um valor vazio por padrão
    $value = isset($variaveisComponents['value']) 
        ? $variaveisComponents['value'] 
        : '';

    // Verifica se o botão de rádio deve estar desabilitado
    $disabled = isset($variaveisComponents['disabled']) && $variaveisComponents['disabled'] 
        ? 'disabled' 
        : false;

    // Verifica se o botão de rádio é obrigatório
    $required = isset($variaveisComponents['required']) && $variaveisComponents['required'] 
        ? 'required' 
        : false;

    // Inicializa uma string para armazenar atributos personalizados
    $customAttributes = '';
    if (isset($variaveisComponents['attributes']) && is_array($variaveisComponents['attributes'])) {
        foreach ($variaveisComponents['attributes'] as $key => $value) {
            // Escapa caracteres especiais para evitar vulnerabilidades (exemplo: XSS)
            $customAttributes .= htmlspecialchars($key) . '="' . htmlspecialchars($value) . '" ';
        }
    }

    // Inclui o arquivos CSS
    includeOnceAsset('css', '/libs/renderComponents/src/components/inputs/radios/bordered/bordered.css');
    (!$required) ?: includeOnceAsset('css', '/libs/renderComponents/src/components/icons/asterisk/asterisk.css');

    // Inclui o arquivos JavaScript
    includeOnceAsset('js', '/libs/renderComponents/src/components/inputs/radios/bordered/bordered.js');
?>

<div class="<?= $containerClass ?> <?= (!$disabled) ?: "disabled" ?>" for="<?= $id ?>">
    <input
        id="<?= $id ?>"
        type="radio"
        value="<?= $value ?>"
        name="<?= $name ?>"
        class="<?= $inputClass ?>"
        <?= $checked ?>
        <?= $disabled ?>
        <?= $required ?>
        <?= $customAttributes ?>
    >

    <label for="<?= $id ?>" class="<?= $labelClass ?> <?= (!$disabled) ?: "disabled-label" ?>">
        <?= $label ?>
        <?php if ($required): ?>
            <i class="icon asterisk"></i>
        <?php endif; ?>
    </label>
</div>

<?php
    // Limpar variáveis após uso
    unset($id, $name, $checked, $label, $containerClass, $inputClass, $labelClass, $value, $disabled, $required, $customAttributes);
?>