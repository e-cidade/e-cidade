<?php
// Atribui valores a variáveis ou usa valores padrão
$type = isset($variaveisComponents['type']) ? $variaveisComponents['type'] : '';
$id = isset($variaveisComponents['id']) ? $variaveisComponents['id'] : '';
$class = isset($variaveisComponents['class']) 
    ? $variaveisComponents['class'] 
    : 'btn solid ' . $variaveisComponents['designButton'] . ' ' . (isset($variaveisComponents['size']) ? $variaveisComponents['size'] : 'md');
$name = isset($variaveisComponents['name']) ? $variaveisComponents['name'] : '';
$value = isset($variaveisComponents['value']) ? $variaveisComponents['value'] : '';
$disabled = ($variaveisComponents['disabled']) ? 'disabled ' : '';
$onclick = isset($variaveisComponents['onclick']) ? 'onclick="' . $variaveisComponents['onclick'] . '" ' : '';
$ariaLabel = isset($variaveisComponents['aria-label']) ? 'aria-label="' . $variaveisComponents['aria-label'] . '" ' : '';
$message = isset($variaveisComponents['message']) 
    ? $variaveisComponents['message'] 
    : ucfirst($variaveisComponents['designButton']) . ' Button';

$customAttributes = '';
if (isset($variaveisComponents['attributes']) && is_array($variaveisComponents['attributes'])) {
    foreach ($variaveisComponents['attributes'] as $key => $value) {
        $customAttributes .= htmlspecialchars($key) . '="' . htmlspecialchars($value) . '" ';
    }
}
?>

<button 
    type="<?= $type ?>"
    id="<?= $id ?>"
    class="<?= $class ?>"
    name="<?= $name ?>"
    value="<?= $value ?>"
    <?= $disabled ?>
    <?= $onclick ?>
    <?= $ariaLabel ?>
    <?= $customAttributes ?>
>

    <div id="<?= $id ?>-message">
        <?= $message ?>
    </div>
</button>

<?php
// Limpar variáveis após uso
unset($type, $id, $class, $name, $value, $disabled, $onclick, $ariaLabel, $message, $customAttributes);
?>