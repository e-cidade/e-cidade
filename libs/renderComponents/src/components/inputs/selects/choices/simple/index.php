<?php
    // Gera um identificador único (UUID) hexadecimal de 32 caracteres.
    // Esse UUID será utilizado para garantir que o id seja único e válido para o HTML
    $uuid = bin2hex(random_bytes(16));

    // Verifica se um ID foi fornecido, caso contrário, atribui um ID padrão ('simple-choice')
    $id = isset($variaveisComponents['id']) 
        ? $variaveisComponents['id'] 
        : 'simple-choice';

    // Sanitiza o ID para garantir que ele contenha apenas caracteres alfanuméricos em minúsculas, removendo outros caracteres.
    $idSanitize = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $id));

    // Atribui a classe CSS personalizada ou usa a classe padrão 'select' para o componente
    $class = isset($variaveisComponents['class']) 
        ? $variaveisComponents['class'] 
        : 'select';

    // Atribui o nome do campo (usado para envio do formulário) ou usa 'state' como valor padrão
    $name = isset($variaveisComponents['name']) 
        ? $variaveisComponents['name'] 
        : 'state';

    // Atribui o texto da label ou um valor padrão
    $label = isset($variaveisComponents['label']) 
        ? $variaveisComponents['label'] 
        : 'Selecione uma opÃ§Ã£o';

    // Atribui as opções para o campo select (array de pares valor-texto), ou usa um array vazio caso não seja fornecido
    $options = isset($variaveisComponents['options']) 
        ? $variaveisComponents['options'] 
        : [];

    // Define a opção selecionada inicialmente ou um valor vazio caso não haja seleção prévia
    $selected = isset($variaveisComponents['selected']) 
        ? $variaveisComponents['selected'] 
        : '';

    // Define se o campo é obrigatório ou não (não obrigatório por padrão)
    $required = isset($variaveisComponents['required']) 
        ? $variaveisComponents['required'] 
        : false;

    // Atribui o tamanho do campo de seleção com base em uma configuração fornecida, ou usa 'simple-choice-md' como valor padrão
    $size = isset($variaveisComponents['size']) 
        ? 'simple-choice-'.$variaveisComponents['size'] 
        : 'simple-choice-md';

    // Verifica se existe um índice inicial para as opções (caso haja necessidade de customização dos índices das opções)
    $startIndex = $variaveisComponents['startIndex'] ?? null;

    // Prepara atributos personalizados, como 'data-*' ou outros atributos HTML adicionais
    $options = isset($startIndex) && $startIndex 
        ? array_combine(range($startIndex, $startIndex + count($options) - 1), $options) 
        : $options;

    $customAttributes = '';
    if (isset($variaveisComponents['attributes']) && is_array($variaveisComponents['attributes'])) {
        foreach ($variaveisComponents['attributes'] as $key => $value) {
            // Escapa as chaves e valores dos atributos para evitar vulnerabilidades (exemplo: XSS)
            $customAttributes .= htmlspecialchars($key) . '="' . htmlspecialchars($value) . '" ';
        }
    }

    // Inclui o arquivos CSS
    includeOnceAsset('css', '/libs/renderComponents/src/components/inputs/selects/choices/base.min.css');
    includeOnceAsset('css', '/libs/renderComponents/src/components/inputs/selects/choices/choices-sizes.css');
    includeOnceAsset('css', '/libs/renderComponents/src/components/inputs/selects/choices/choices.min.css');
    includeOnceAsset('css', '/libs/renderComponents/src/components/inputs/selects/choices/simple/simple.css');
    
    // Inclui o arquivos JavaScript
    includeOnceAsset('js', '/libs/renderComponents/src/components/inputs/selects/choices/choices.min.js');
    includeOnceAsset('js', '/libs/renderComponents/src/components/inputs/selects/choices/simple/simple.js');
?>

<div class="simple-choice <?= $size ?>">
    <div class="choice-content">
        <label for="<?=$idSanitize?>" class="label">
            <?= $label ?>
            <?php if ($required): ?>
                <i class="icon asterisk"></i>
            <?php endif; ?>
        </label>

        <select
            id="<?=$idSanitize?>"
            class="select"
            name="<?= $name ?> 
            <?php ($required) ?: "required"; ?>"
            <?= $customAttributes ?>
        >
            <option value="">Selecione uma opÃ§Ã£o</option>
            <?php foreach ($options as $value => $text): ?>
                <option value="<?= $value ?>" <?= $value == $selected ? 'selected' : '' ?>>
                    <?= $text ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<script>
    if (!window.choicesTranslations) {
        window.choicesTranslations = {
            loadingText: 'Carregando...',
            noResultsText: 'Nenhum resultado encontrado',
            noChoicesText: 'Nenhuma opÃ§Ã£o disponÃ­vel',
            itemSelectText: 'Pressione para selecionar',
            uniqueItemText: 'Apenas valores únicos podem ser adicionados',
            customAddItemText: 'Apenas valores que atendem a condiÃ§Ãµes especÃ­ficas podem ser adicionados',
            addItemText: (value) => `Pressione Enter para adicionar <b>"${value}"</b>`,
            maxItemText: (maxItemCount) => `Somente ${maxItemCount} valores podem ser adicionados`,
            searchEnabled: true,
            itemSelectText: '',
            shouldSort: false
        };
    }
    
    window.addEventListener('load', function() {
        const maxDuration = 10000;
        const checkInterval = 100;
        let elapsedTime = 0;

        function initializeChoices<?=$uuid?>() {
            if (typeof Choices !== 'undefined') {
                const selectElement = document.getElementById("<?=$idSanitize?>");
                const choices = new Choices(selectElement, {
                    ...window.choicesTranslations,
                    searchEnabled: true,
                    itemSelectText: '',
                    shouldSort: false,
                    position: 'bottom',
                    resetScrollPosition: false,
                });
            } else if (elapsedTime < maxDuration) {
                elapsedTime += checkInterval;
                setTimeout(initializeChoices<?=$uuid?>, checkInterval);
            } else {
                console.error('Choices.js não foi carregado após 10 segundos.');
            }
        }

        initializeChoices<?=$uuid?>();
    });
</script>

<?php
    // Limpar variáveis após uso
    unset($id, $class, $name, $label, $options, $selected, $required, $startIndex, $options, $customAttributes);
?>