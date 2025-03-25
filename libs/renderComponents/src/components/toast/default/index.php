<?php
    // Atribui valores a variáveis ou usa valores padrão
    $id = isset($variaveisComponents['id']) ? $variaveisComponents['id'] : '';

    // Define a classe CSS com base nos valores de $variaveisComponents
    $class = isset($variaveisComponents['class']) 
        ? $variaveisComponents['class'] 
        : 'btn solid ' . (isset($variaveisComponents['design']) ? $variaveisComponents['design'] : '');

    // Atribui uma mensagem ou deixa em branco caso não exista a chave 'message'
    $message = isset($variaveisComponents['message']) 
        ? $variaveisComponents['message'] 
        : '';

    // Inclui o arquivos CSS
    includeOnceAsset('css', '/libs/renderComponents/src/components/toast/default/default.css');

    // Inclui o arquivos JavaScript
    includeOnceAsset('js', '/libs/renderComponents/src/components/toast/default/default.js');
?>

<div id="toast-container-98P6m"></div>

<?php
    // Limpar variáveis após uso
    unset($id, $class, $message);
?>