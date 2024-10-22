<?php
// Atribui valores às variáveis ou usa valores padrão
$id = isset($variaveisComponents['id']) ? $variaveisComponents['id'] : 'default-datepicker';
$placeholder = isset($variaveisComponents['placeholder']) ? $variaveisComponents['placeholder'] : 'Select date';
$inputClass = isset($variaveisComponents['inputClass']) ? $variaveisComponents['inputClass'] : 'datepicker-input';
$label = isset($variaveisComponents['label']) ? $variaveisComponents['label'] : '';
$name = isset($variaveisComponents['name']) ? $variaveisComponents['name'] : 'date';
$value = isset($variaveisComponents['value']) ? $variaveisComponents['value'] : '';
$required = isset($variaveisComponents['required']) && $variaveisComponents['required'] ? 'required' : false;
$size = isset($variaveisComponents['size']) ? 'datepicker-container-'.$variaveisComponents['size'] : 'datepicker-container-md';

$customAttributes = '';
if (isset($variaveisComponents['attributes']) && is_array($variaveisComponents['attributes'])) {
    foreach ($variaveisComponents['attributes'] as $key => $value) {
        $customAttributes .= htmlspecialchars($key) . '="' . htmlspecialchars($value) . '" ';
    }
}
?>

<div class="datepicker-container">
    <label for="<?= $id ?>" class="label">
        <?= $label ?>
        <?php if ($required): ?>
            <i class="icon asterisk"></i>
        <?php endif; ?>
    </label>

    <input
        id="<?= $id ?>"
        type="date"
        name="<?= $name ?>"
        value="<?= $value ?>"
        class="<?= $inputClass ?> <?= $size ?>"
        placeholder="<?= $placeholder ?>"
        <?= $required ?>
        <?= $customAttributes ?>
    >
</div>

<?php
// Limpar variáveis após uso
unset($id, $placeholder, $containerClass, $inputClass, $label, $name, $value, $required, $customAttributes);
?>