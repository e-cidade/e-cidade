<?php
    // Gera um identificador único (UUID) hexadecimal de 32 caracteres.
    // Esse UUID será utilizado para garantir que o id seja único e válido para o HTML
    $uuid = bin2hex(random_bytes(16));
    
    // Atribui um ID ao componente. Se o ID não for fornecido, um valor padrão é utilizado.
    $id = isset($variaveisComponents['id']) 
        ? $variaveisComponents['id'] 
        : 'default-textfield-anchor';
    
    // Atribui um valor para o placeholder. Se não for fornecido, o valor será uma string vazia.
    $placeholder = isset($variaveisComponents['placeholder']) 
        ? $variaveisComponents['placeholder'] 
        : '';
    
    // Atribui uma classe CSS personalizada para o input. Se não for fornecida, a classe padrão 'textfield-anchor-input' é usada.
    $inputClass = isset($variaveisComponents['inputClass']) 
        ? $variaveisComponents['inputClass'] 
        : 'textfield-anchor-input';
    
    // Atribui um valor para o label do campo. Caso não seja fornecido, o valor será uma string vazia.
    $label = isset($variaveisComponents['label']) 
        ? $variaveisComponents['label'] 
        : '';
    
    // Atribui o nome do campo de entrada, que será usado no envio do formulário. Caso não seja fornecido, o valor será uma string vazia.
    $name = isset($variaveisComponents['name']) 
        ? $variaveisComponents['name'] 
        : '';
    
    // Atribui o valor inicial do campo de entrada. Se não for fornecido, o valor será uma string vazia.
    $value = isset($variaveisComponents['value']) 
        ? $variaveisComponents['value'] 
        : '';
    
    // Verifica se o campo é obrigatório. Se for, o atributo 'required' será adicionado ao campo.
    $required = isset($variaveisComponents['required']) && $variaveisComponents['required'] 
        ? 'required' 
        : false;
    
    // Atribui o tamanho do campo de entrada, conforme especificado nas variáveis de componentes. Se não for fornecido, um tamanho médio é utilizado.
    $size = isset($variaveisComponents['size']) 
        ? 'textfield-anchor-container-'.$variaveisComponents['size'] 
        : 'textfield-anchor-container-md';
    
    // Verifica se o campo está desabilitado. Se for, o atributo 'disabled' será adicionado ao campo.
    $disabled = (isset($variaveisComponents['disabled']) && $variaveisComponents['disabled']) 
        ? 'disabled' 
        : '';

    // Atribui o ID do modal (se fornecido). Caso contrário, o valor será uma string vazia.
    $idModal = (isset($variaveisComponents['idModal'])) 
        ? $variaveisComponents['idModal'] 
        : '';
    
    // Atribui o ID da grid (se fornecido), realizando a sanitização para garantir que ele contenha apenas caracteres alfanuméricos.
    $idGrid = (isset($variaveisComponents['idGrid'])) 
        ? strtolower(preg_replace('/[^a-z0-9]/', '', $variaveisComponents['idGrid'])) 
        : '';

    // Constrói atributos personalizados (como data-* ou outras customizações)
    $customAttributes = '';
    if (isset($variaveisComponents['attributes']) && is_array($variaveisComponents['attributes'])) {
        foreach ($variaveisComponents['attributes'] as $key => $value) {
            // Adiciona cada atributo personalizado (escapando as chaves e valores) para a string customAttributes
            $customAttributes .= htmlspecialchars($key) . '="' . htmlspecialchars($value) . '" ';
        }
    }
    
    // Inclui o arquivos CSS
    includeOnceAsset('css', '/libs/renderComponents/src/components/inputs/text/anchor/simple.css');
    includeOnceAsset('css', '/libs/renderComponents/src/components/modais/simpleModal/simpleModal.css');
    
    // Inclui o arquivos JavaScript
    includeOnceAsset('js', '/libs/renderComponents/src/components/inputs/text/anchor/simple.js');
    includeOnceAsset('js', '/libs/renderComponents/src/components/modais/simpleModal/simpleModal.js');
    includeOnceAsset('js', '/libs/renderComponents/src/components/modais/modais.js');
?>

<div class="textfield-anchor-container">
    <label for="<?= $id ?>" class="label">
        <a onclick="eventAnchor<?=$uuid?>();">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="10" height="10" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
                <?= $label ?>
            </span>

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20" height="20" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
            </svg>
        </a>
        <?php if ($required): ?>
            <i class="icon asterisk"></i>
        <?php endif; ?>
    </label>

    <div style="position: relative; display: inline-block; cursor: crosshair; width: 100%;">
        <input
            style="position: relative; z-index: 1;"
            id="<?=$id?>"
            type="text"
            name="<?= $name ?>"
            value="<?= $value ?>"
            class="<?= $inputClass ?> <?= $size ?>"
            placeholder="<?= $placeholder ?>"
            <?= $disabled ?>
            <?= $required ?>
            <?= $customAttributes ?>
        >

        <div 
            onclick="eventAnchor<?=$uuid?>()"
            style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 2; background: transparent;"
        ></div>
    </div>
</div>

<script>
    function eventAnchor<?=$uuid?>() {
        openModal('<?=$idModal?>');
        refreshGrid<?=$idGrid?>('{}');
    }
</script>

<?php
    // Limpar variáveis após uso
    unset($uuid, $id, $placeholder, $containerClass, $inputClass, $label, $name, $value, $required, $size, $disabled, $idModal, $idGrid, $customAttributes);
?>