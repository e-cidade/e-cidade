<?php
    /**
     * Interface RenderableInterface
     *
     * Esta interface define um contrato para componentes renderizáveis. 
     * Qualquer classe que implementar esta interface deve fornecer uma implementação 
     * para o método renderComponent.
     */
    interface RenderableInterface {
        /**
         * Renderiza um componente especificado.
         *
         * @param string $componentName O nome do componente a ser renderizado.
         * @param array $variables Variáveis que serão passadas para o componente.
         * @param bool|string $fileName O nome do arquivo do componente, se necessário.
         * @return mixed A saída renderizada do componente.
         */
        public function render($componentName, $variables = [], $fileName = false);
    }
?>
