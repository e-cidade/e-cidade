<?php
    /*
        |-------------|-----------|--------------|----------------------------------------------------------------------------------|
        | Parâmetro:  | Tipo:     | Obrigatório: | Descrição:                                                                       |
        |-------------|-----------|--------------|----------------------------------------------------------------------------------|
        | targetEl    | Elemento  | Sim          | Elemento de conteúdo da dica de ferramenta, mostrado/ocultado pelo gatilho.      |
        | triggerEl   | Elemento  | Sim          | Elemento que aciona a dica de ferramenta ao clicar ou passar o mouse.            |
        | body        | String    | Sim          | Define a mensagem que será exibida no corpo do tooltip. Aceita conteúdo em HTML. |
        |-------------|-----------|--------------|----------------------------------------------------------------------------------|
    */
    $targetEl = isset($variaveisComponents['targetEl']) 
        ? $variaveisComponents['targetEl'] 
        : null;

    $triggerEl = isset($variaveisComponents['triggerEl']) 
        ? $variaveisComponents['triggerEl'] 
        : null;

    $body = isset($variaveisComponents['body']) 
        ? $variaveisComponents['body'] 
        : null;

    /*
        |-------------|----------|-------------|---------------------------------------------------------------------------------|
        | Parâmetro:  | Tipo:    | Obrigatório:| Descrição:                                                                      |
        |-------------|----------|-------------|---------------------------------------------------------------------------------|
        | options     | Array    | Não         | Define posicionamento, tipo de gatilho, deslocamentos e outras configurações.   |
        |-------------|----------|-------------|---------------------------------------------------------------------------------|
        |/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\|
        |-------------|----------|-----------------------------------------------------------------------------------------------|
        | Opções:     | Tipo:    | Descrição:                                                                                    |
        |-------------|----------|-----------------------------------------------------------------------------------------------|
        | placement   | String   | Define a posição da dica de ferramenta em relação ao gatilho.                                 |
        |             |          | Opções: 'top', 'right', 'bottom', 'left'.                                                     |
        |-------------|----------|-----------------------------------------------------------------------------------------------|
        | triggerType | String   | Define o tipo de evento que aciona a dica de ferramenta.                                      |
        |             |          | Opções: 'hover', 'click', 'none'.                                                             |
        |-------------|----------|-----------------------------------------------------------------------------------------------|
        | onHide      | Função   | Função de callback chamada quando a dica de ferramenta é ocultada.                            |
        | onShow      | Função   | Função de callback chamada quando a dica de ferramenta é exibida.                             |
        | onToggle    | Função   | Função de callback chamada ao alternar a visibilidade da dica de ferramenta.                  |
        |-------------|----------|-----------------------------------------------------------------------------------------------|
    */
    $options = isset($variaveisComponents['options']) 
        ? json_encode($variaveisComponents['options'])
        : '{placement: "top", triggerType: "hover"}';

    $placement = $variaveisComponents['options']['placement'] ?? 'top';

    /*
        |-------------|----------|-------------|---------------------------------------------------------------------------------|
        | Parâmetro:  | Tipo:    | Obrigatório:| Descrição:                                                                      |
        |-------------|----------|-------------|---------------------------------------------------------------------------------|
        | size        | String   | Não         | Define a largura maxima do tooltip.                                             |
        |-------------|----------|-------------|---------------------------------------------------------------------------------|
    */
    $size = isset($variaveisComponents['size']) 
        ? $variaveisComponents['size'] 
        : 'auto';

    /*
        |-----------------|----------|-------------|---------------------------------------------------------------------------------|
        | Parâmetro:      | Tipo:    | Obrigatório:| Descrição:                                                                      |
        |-----------------|----------|-------------|---------------------------------------------------------------------------------|
        | backgroundColor | String   | Não         | Define a cor de fundo do tooltip.                                               |
        |-----------------|----------|-------------|---------------------------------------------------------------------------------|
    */
    $backgroundColor = isset($variaveisComponents['backgroundColor']) 
        ? $variaveisComponents['backgroundColor'] 
        : '#dbdbdb';

    /*
        |-------------|----------|-------------|---------------------------------------------------------------------------------|
        | Parâmetro:  | Tipo:    | Obrigatório:| Descrição:                                                                      |
        |-------------|----------|-------------|---------------------------------------------------------------------------------|
        | fontColor   | String   | Não         | Define a cor da fonte do tooltip maxima do tooltip.                             |
        |-------------|----------|-------------|---------------------------------------------------------------------------------|
    */
    $fontColor = isset($variaveisComponents['fontColor']) 
        ? $variaveisComponents['fontColor'] 
        : '#333';

    // Inclui o arquivos CSS
    includeOnceAsset('css', '/libs/renderComponents/src/components/tooltip/default/default.css');
?>

<style>
    .tooltip-default-K835A {
        color: <?= $fontColor ?>;
        background-color: <?= $backgroundColor ?>;
    }

    .bottom-K835A::after {
        border-color: transparent transparent <?= $backgroundColor ?> transparent;
    }

    .right-K835A::after {
        border-color: transparent <?= $backgroundColor ?> transparent transparent;
    }

    .left-K835A::after {
        border-color: transparent transparent transparent <?= $backgroundColor ?>;
    }

    .top-K835A::after {
        border-color: <?= $backgroundColor ?> transparent transparent transparent;
    }
</style>
    
<span id="<?= $targetEl ?>" class="tooltip-default-K835A <?= $placement ?>-K835A <?= $size ?>" style="display: none; <?= ($size != 'auto')?: 'white-space: nowrap;'; ?>">
    <?= $body ?>
</span>

<script type="module">
    import { TooltipRC } from '<?= $this->baseUrl ?>/libs/renderComponents/src/components/tooltip/default/TooltipRC.js';
    
    document.addEventListener("DOMContentLoaded", () => {
        const triggerElement = document.getElementById("<?= $triggerEl ?>");
        const tooltipElement = document.getElementById("<?= $targetEl ?>");
                
        /*
            |--------------------------|-------------------------------------------------------------------------------------------|
            | Métodos:                 | Descrição:                                                                                |
            |--------------------------|-------------------------------------------------------------------------------------------|
            | show()                   | Exibe o conteúdo da dica de ferramenta.                                                   |
            |--------------------------|-------------------------------------------------------------------------------------------|
            | hide()                   | Oculta o conteúdo da dica de ferramenta.                                                  |
            |--------------------------|-------------------------------------------------------------------------------------------|
            | toggle()                 | Alterna a visibilidade do conteúdo da dica de ferramenta.                                 |
            |--------------------------|-------------------------------------------------------------------------------------------|
            | updateOnShow(callback)   | Define uma função de callback personalizada quando a dica de ferramenta for exibida.      |
            |--------------------------|-------------------------------------------------------------------------------------------|
            | updateOnHide(callback)   | Define uma função de callback personalizada quando a dica de ferramenta estiver oculta.   |
            |--------------------------|-------------------------------------------------------------------------------------------|
            | updateOnToggle(callback) | Define uma função de callback personalizada quando a visibilidade for alternada.          |
            |--------------------------|-------------------------------------------------------------------------------------------|
        */
        new TooltipRC(tooltipElement, triggerElement, <?= $options ?>);
    });
</script>

<?php
    // Limpar variáveis após uso
    unset($id, $body, $size);
?>