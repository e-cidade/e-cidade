<?php
// Atribui valores �s vari�veis ou usa valores padr�o
$id = isset($variaveisComponents['id']) ? $variaveisComponents['id'] : 'simple-choice';
$class = isset($variaveisComponents['class']) ? $variaveisComponents['class'] : 'select';
$name = isset($variaveisComponents['name']) ? $variaveisComponents['name'] : 'state';
$label = isset($variaveisComponents['label']) ? $variaveisComponents['label'] : 'Selecione uma op��o';
$options = isset($variaveisComponents['options']) ? $variaveisComponents['options'] : [];
$selected = isset($variaveisComponents['selected']) ? $variaveisComponents['selected'] : '';
$required = isset($variaveisComponents['required']) ? $variaveisComponents['required'] : false;
$size = isset($variaveisComponents['size']) ? 'simple-choice-'.$variaveisComponents['size'] : 'simple-choice-md';

$startIndex = $variaveisComponents['startIndex'];
$options = isset($startIndex) && $startIndex ?
    array_combine(range($startIndex, $startIndex + count($options) - 1), $options) :
    $options;

$customAttributes = '';
if (isset($variaveisComponents['attributes']) && is_array($variaveisComponents['attributes'])) {
    foreach ($variaveisComponents['attributes'] as $key => $value) {
        $customAttributes .= htmlspecialchars($key) . '="' . htmlspecialchars($value) . '" ';
    }
}
?>

<div class="simple-choice <?= $size ?>">
    <div class="choice-content">
        <label for="<?= $id ?>" class="label">
            <?= $label ?>
            <?php if ($required): ?>
                <i class="icon asterisk"></i>
            <?php endif; ?>
        </label>

        <select
            id="<?= $id ?>"
            class="select"
            name="<?= $name ?> 
            <?php ($required) ?: "required"; ?>"
            <?= $customAttributes ?>
        >
            <option value="">Selecione uma op��o</option>
            <?php foreach ($options as $value => $text): ?>
                <option value="<?= $value ?>" <?= $value == $selected ? 'selected' : '' ?>>
                    <?= $text ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        const maxDuration = 10000; // 10 segundos em milissegundos
        const checkInterval = 100; // Intervalo de 100ms
        let elapsedTime = 0; // Tempo decorrido

        // Fun��o para verificar se Choices.js foi carregado
        function initializeChoices() {
            if (typeof Choices !== 'undefined') {
                const selectElement = document.getElementById("<?= $id ?>");
                const choices = new Choices(selectElement, {
                    ...choicesTranslations,
                    searchEnabled: true, // Habilita o campo de busca
                    itemSelectText: '', // Texto mostrado ao selecionar um item (vazio neste caso)
                    shouldSort: false, // Mant�m a ordem original dos elementos
                    position: 'bottom', // Garante que o dropdown abra para baixo
                    resetScrollPosition: false, // Opcional: desabilita o reset da posi��o do scroll
                });
            } else if (elapsedTime < maxDuration) {
                // Tenta novamente ap�s o intervalo se Choices.js ainda n�o estiver carregado
                elapsedTime += checkInterval;
                setTimeout(initializeChoices, checkInterval);
            } else {
                console.error('Choices.js n�o foi carregado ap�s 10 segundos.');
            }
        }

        // Inicia a verifica��o de carregamento de Choices.js
        initializeChoices();
    });
</script>

<?php
// Limpar vari�veis ap�s uso
unset($id, $class, $name, $label, $options, $selected, $required, $startIndex, $options, $customAttributes);
?>