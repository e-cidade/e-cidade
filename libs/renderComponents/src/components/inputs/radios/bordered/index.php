<?php
// Atribui valores às variáveis ou usa valores padrão
$id = isset($variaveisComponents['id']) ? $variaveisComponents['id'] : 'default-radio';
$name = isset($variaveisComponents['name']) ? $variaveisComponents['name'] : 'radio-group';
$checked = isset($variaveisComponents['checked']) && $variaveisComponents['checked'] ? 'checked' : '';
$label = isset($variaveisComponents['label']) ? $variaveisComponents['label'] : 'Default radio';
$containerClass = isset($variaveisComponents['containerClass']) ? $variaveisComponents['containerClass'] : 'radio-container';
$inputClass = isset($variaveisComponents['inputClass']) ? $variaveisComponents['inputClass'] : 'radio-input';
$labelClass = isset($variaveisComponents['labelClass']) ? $variaveisComponents['labelClass'] : 'radio-label';
$value = isset($variaveisComponents['value']) ? $variaveisComponents['value'] : '';
$disabled = isset($variaveisComponents['disabled']) && $variaveisComponents['disabled'] ? 'disabled' : '';
$required = isset($variaveisComponents['required']) && $variaveisComponents['required'] ? 'required' : false;

$customAttributes = '';
if (isset($variaveisComponents['attributes']) && is_array($variaveisComponents['attributes'])) {
    foreach ($variaveisComponents['attributes'] as $key => $value) {
        $customAttributes .= htmlspecialchars($key) . '="' . htmlspecialchars($value) . '" ';
    }
}
?>

<div class="<?= $containerClass ?> <?= (empty($disabled) ?: "disabled") ?>" for="<?= $id ?>">
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

    <label for="<?= $id ?>" class="<?= $labelClass ?> <?= (empty($disabled) ?: "disabled-label") ?>">
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