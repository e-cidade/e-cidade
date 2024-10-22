/**
 * Carrega os componentes especificados, incluindo seus arquivos CSS e JavaScript.
 *
 * @param {Array<string>} componentNames - Lista de nomes dos componentes a serem carregados.
 */
function loadComponents(componentNames) {

    /**
     * Verifica se uma folha de estilo já foi carregada.
     *
     * @param {string} href - O caminho do arquivo CSS.
     * @return {boolean} Retorna true se a folha de estilo já foi carregada, caso contrário, false.
     */
    function isStylesheetLoaded(href) {
        return Array.from(document.getElementsByTagName('link')).some(link => link.getAttribute('href') === href);
    }

    /**
     * Verifica se um script já foi carregado.
     *
     * @param {string} src - O caminho do arquivo JavaScript.
     * @return {boolean} Retorna true se o script já foi carregado, caso contrário, false.
     */
    function isScriptLoaded(src) {
        return Array.from(document.getElementsByTagName('script')).some(script => script.getAttribute('src') === src);
    }

    /**
     * Carrega a configuração dos componentes a partir de um arquivo JSON.
     *
     * @return {Promise<Object>} Retorna uma Promise que resolve com a configuração dos componentes.
     */
    function fetchComponentConfig() {
        return fetch(baseUrl + '/libs/renderComponents/config/components-config.json')
            .then(response => response.json());
    }

    // Carrega a configuração dos componentes
    fetchComponentConfig().then(config => {
        // Itera sobre cada componente e carrega os arquivos necessários
        for (const componentName of componentNames) {
            const component = config[componentName];

            if (!component) {
                console.error(`Component ${componentName} not found.`);
                continue;
            }

            // Carrega os arquivos CSS
            if (component.css) {
                component.css.forEach(href => {
                    if (!isStylesheetLoaded(href)) {
                        const link = document.createElement('link');
                        link.rel = 'stylesheet';
                        link.href = baseUrl + href;
                        document.head.appendChild(link);
                    }
                });
            }

            // Carrega os arquivos JavaScript
            if (component.js) {
                component.js.forEach(src => {
                    if (!isScriptLoaded(src)) {
                        const script = document.createElement('script');
                        script.src = baseUrl + src;
                        document.head.appendChild(script);
                    }
                });
            }
        }
    }).catch(error => {
        console.error('Failed to load component configuration:', error);
    });
}
