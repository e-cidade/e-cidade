<?php
    // Define o tipo do botão (ex.: "submit", "button")
    $type = isset($variaveisComponents['type']) 
        ? $variaveisComponents['type'] 
        : '';

    // Define o ID do botão
    $id = isset($variaveisComponents['id']) 
        ? $variaveisComponents['id'] 
        : '';

    // Define a classe CSS do botão. Inclui classes padrão e adicionais dependendo das opções fornecidas no array
    $class = isset($variaveisComponents['class']) 
        ? $variaveisComponents['class'] 
        : 'btn-SG136 solid-SG136 ' . 
            (isset($variaveisComponents['designButton']) ? $variaveisComponents['designButton'] . '-SG136' : '') . 
            ' ' . 
            (isset($variaveisComponents['size']) ? $variaveisComponents['size'] . '-SG136' : 'md-SG136');

    // Define o atributo "name" do botão
    $name = isset($variaveisComponents['name']) 
        ? $variaveisComponents['name'] 
        : '';

    // Define o valor do botão
    $value = isset($variaveisComponents['value']) 
        ? $variaveisComponents['value'] 
        : '';

    // Define se o botão estará desabilitado ou não
    $disabled = (isset($variaveisComponents['disabled']) && $variaveisComponents['disabled']) 
        ? 'disabled ' 
        : '';

    // Define o evento "onclick" caso seja especificado
    $onclick = isset($variaveisComponents['onclick']) 
        ? 'onclick="' . $variaveisComponents['onclick'] . '" ' 
        : '';

    // Define o atributo "aria-label" para acessibilidade, se fornecido
    $ariaLabel = isset($variaveisComponents['aria-label']) 
        ? 'aria-label="' . $variaveisComponents['aria-label'] . '" ' 
        : '';

    // Define a mensagem de texto exibida no botão. Usa a primeira letra maiúscula do valor de 'designButton' se disponível
    $message = isset($variaveisComponents['message']) 
        ? $variaveisComponents['message'] 
        : ucfirst($variaveisComponents['designButton']) . ' Button';

    // Monta uma string com atributos personalizados, se fornecidos no array 'attributes'
    $customAttributes = '';
    if (isset($variaveisComponents['attributes']) && is_array($variaveisComponents['attributes'])) {
        foreach ($variaveisComponents['attributes'] as $key => $value) {
            // Escapa caracteres especiais em nomes e valores para evitar vulnerabilidades
            $customAttributes .= htmlspecialchars($key) . '="' . htmlspecialchars($value) . '" ';
        }
    }

    // Inclui o arquivos CSS
    includeOnceAsset('css', '/libs/renderComponents/src/components/buttons/buttons.css');
    includeOnceAsset('css', '/libs/renderComponents/src/components/buttons/solid/solids.css');

    // Inclui o arquivos JavaScript
    includeOnceAsset('js', '/libs/renderComponents/src/components/buttons/buttons.js');
    includeOnceAsset('js', '/libs/renderComponents/src/components/buttons/solid/solids.js');
?>

<button 
    type="<?= $type ?>"
    id="<?= $id ?>"
    class="<?= $class ?> <?= $disabled ?>"
    name="<?= $name ?>"
    value="<?= $value ?>"
    <?= $disabled ?>
    <?= $onclick ?>
    <?= $ariaLabel ?>
    <?= $customAttributes ?>
>

    <div id="<?= $id . '-message' ?>">
        <?= $message ?>
    </div>
</button>

<?php
    // Limpar variáveis após uso
    unset($type, $id, $class, $name, $value, $disabled, $onclick, $ariaLabel, $message, $customAttributes);
?>