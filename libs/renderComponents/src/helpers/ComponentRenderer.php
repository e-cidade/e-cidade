<?php

    // Inclua a definição da interface se ainda não foi incluída
    include 'libs/renderComponents/src/interfaces/RenderableInterface.php';

    /**
     * Class ComponentRenderer
     *
     * Esta classe implementa a interface RenderableInterface e é responsável por renderizar componentes.
     */
    class ComponentRenderer implements RenderableInterface {
        /**
         * @var string O caminho base onde os componentes estão localizados.
         */
        private $baseUrl;

        /**
         * @var string O caminho completo do arquivo atual.
         */
        private $filePath;

        /**
         * @var string O diretório onde o arquivo atual está localizado.
         */
        private $folderPath;

        /**
         * @var string O diretório atual de trabalho.
         */
        private $projectPath;

        /**
         * @var string O host HTTP da solicitação.
         */
        private $httpHost;

        /**
         * @var string O esquema de solicitação (http ou https).
         */
        private $requestScheme;

        /**
         * @var string A uri de solicitação.
         */
        private $requestUri;

        /**
         * @var string O diretório raiz do documento.
         */
        private $documentRoot;

        /**
         * ComponentRenderer constructor.
         *
         * @param string $baseUrl O caminho base onde os componentes estão localizados. Default é './components/'.
         */
        public function __construct($api = false)
        {
            // Caminho completo do arquivo atual
            $this->filePath = __FILE__; // EX: /var/www/{projectName}/libs/renderComponents/src/helpers/ComponentRenderer.php

            // Diretório do arquivo atual
            $this->folderPath = __DIR__; // EX: /var/www/{projectName}/libs/renderComponents/src/helpers

            // Diretório atual de trabalho
            $this->projectPath = getcwd(); // EX: /var/www/{projectName}
            
            // Definindo HTTP_HOST e REQUEST_SCHEME REQUEST_URI a partir das variáveis globais do servidor
            $this->httpHost = $_SERVER['HTTP_HOST'] ?? 'localhost'; // EX: [HTTP_HOST] => 10.250.30.8
            $this->requestScheme = $_SERVER['REQUEST_SCHEME'] ?? 'http'; // EX: [REQUEST_SCHEME] => http
            $this->requestUri = $_SERVER['REQUEST_URI'] ?? '1'; // EX: [REQUEST_URI] => /{project}}/w/1/{file}.php
        
            // Diretório raiz do documento
            $this->documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? ''; // EX: [DOCUMENT_ROOT] => /var/www

            if ($api) {
                // Define a URL base para os componentes
                $this->baseUrl = $this->requestScheme . "://" . $this->httpHost . "/" . $this->relativePath() . "/w/0";
            } else {
                // Define a URL base para os componentes
                $this->baseUrl = $this->requestScheme . "://" . $this->httpHost . "/" . $this->relativePath() . "/w/" . $this->uriNumberWindow();
            }

            // Injeta o script JavaScript para carregar os componentes
            echo "<script src='{$this->baseUrl}/libs/renderComponents/public/loadComponents.js'></script>";
            echo "<script>const baseUrl = '{$this->baseUrl}';</script>";
        }

        /**
         * Renderiza um componente especificado.
         *
         * @param string $componentName O nome do componente a ser renderizado.
         * @param array $variables Variáveis que serão passadas para o componente.
         * @param bool|string $fileName O nome do arquivo do componente, se necessário.
         * @return void
         */
        public function render($componentName, $variables = [], $fileName = false) {
            // Determine o caminho do arquivo do componente
            if (!$fileName) {
                $parts = explode('/', $componentName);
                $filePath = $this->projectPath .'/libs/renderComponents/src/components/'. implode('/', $parts) . '/index.php';
            } else {
                $parts = explode('/', $componentName);
                $filePath = $this->projectPath .'/libs/renderComponents/src/components/'. implode('/', $parts) . '.php';
            }

            // Verifique se o arquivo existe
            if (file_exists($filePath)) {
                // Torne as variáveis acessíveis no escopo do componente
                $variaveisComponents = $variables;

                // Inclua o arquivo do componente
                include $filePath;
            } else {
                echo "O componente '$componentName' não foi encontrado.";
            }
        }

        /**
         * Extrai e retorna um identificador numérico da URI da solicitação.
         *
         * Esta função utiliza uma expressão regular para corresponder e extrair um valor numérico
         * seguindo o padrão 'w/{número}' dentro da URI da solicitação. Este identificador é 
         * comumente usado para referenciar janelas ou seções específicas na aplicação.
         *
         * @return int O identificador numérico extraído da URI da solicitação.
         */
        private function uriNumberWindow()
        {
            preg_match('/w\/(\d+)/', $this->requestUri, $matches);
            $numero = $matches[1];
            return $numero;
        }

        /**
         * Calcula e retorna o caminho relativo do projeto em relação ao DOCUMENT_ROOT.
         *
         * Esta função calcula o caminho relativo do diretório do projeto em relação
         * ao DOCUMENT_ROOT do servidor. Ela remove a porção absoluta do caminho que 
         * corresponde ao DOCUMENT_ROOT, garantindo que o resultado seja um caminho 
         * relativo limpo, começando a partir do diretório raiz do projeto. Qualquer 
         * barra inicial é removida para manter a consistência.
         *
         * @return string O caminho relativo calculado do projeto.
         */
        private function relativePath()
        {
            // Calcula o caminho relativo do projeto em relação ao DOCUMENT_ROOT
            $relativePath = str_replace($this->documentRoot, '', $this->projectPath);
            $relativePath = ltrim($relativePath, '/'); // Remove barra inicial, se existir
            return $relativePath;
        }
    }
?>
