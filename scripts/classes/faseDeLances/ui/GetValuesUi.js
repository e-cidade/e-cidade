export class GetValuesUi {

    /**
     * Função estática `getRowDataId` que retorna o valor do atributo `data-id` de uma célula (`td`) 
     * dentro da linha destacada (com a classe `highlight-AEF1E83`).
     * 
     * A função localiza a linha destacada utilizando o método `getRowHighlight()`. 
     * Em seguida, busca por uma célula (`td`) dentro dessa linha que possua o atributo `data-id`.
     * Se a célula for encontrada, retorna o valor do atributo `data-id`. Caso contrário, retorna `null`.
     * Caso a linha destacada não seja encontrada, a função retorna `null` e exibe um erro no console.
     * 
     * @returns {string|null} - Retorna o valor do atributo `data-id` da célula se encontrada,
     *                          ou `null` caso a linha ou a célula não seja encontrada.
     */
    static getRowDataId() {
        const rowHighlight = this.getRowHighlight();
        if (rowHighlight) {
            const tdWithDataId = rowHighlight.querySelector('td[data-id]');
            return tdWithDataId ? tdWithDataId.getAttribute('data-id') : null;
        }
        console.error('Linha com a classe "highlight-AEF1E83" não encontrada.');
        return null;
    }

    /**
     * Função estática `getBidValue` que retorna o valor do lance inserido no campo de entrada.
     * 
     * A função localiza o campo de entrada do lance utilizando o método `getBidInput()`.
     * Em seguida, a função tenta processar o valor inserido, removendo pontos (usados como separadores de milhar)
     * e substituindo a vírgula por um ponto para garantir que o valor seja corretamente interpretado como número decimal.
     * Caso o valor inserido não seja válido ou o campo não seja encontrado, a função retorna `null`.
     * 
     * @returns {number|null} - Retorna o valor do lance como um número de ponto flutuante se válido,
     *                          ou `null` se o valor for inválido ou o campo não for encontrado.
     */
    static getBidValue() {
        let bidInput = this.getBidInput();

        if (bidInput) {
            let valorLance = bidInput.value.replace(/\./g, '').replace(',', '.');
            valorLance = parseFloat(valorLance);
            if (isNaN(valorLance)) {
                console.error('Valor do bid é inválido.');
                return null;
            }
            return valorLance;
        }
        console.error('Elemento com ID "bid-input-5151BB7" não encontrado.');
        return null;
    }

    /**
     * Função estática `getRowHighlight` que retorna a linha da tabela que está
     * destacada com a classe `highlight-AEF1E83`.
     * 
     * Essa função utiliza o método `document.querySelector` para localizar a linha (`tr`)
     * com a classe `highlight-AEF1E83`, que indica que a linha está em destaque.
     * 
     * @returns {HTMLElement|null} - Retorna a linha (`tr`) com a classe `highlight-AEF1E83`
     *                               se encontrada, ou `null` caso a linha destacada não exista no DOM.
     */
    static getRowHighlight() {
        return document.querySelector('tr.highlight-AEF1E83');
    }

    /**
     * Função estática `getValueTdHighlight` que retorna o valor da célula de uma linha
     * destacada (com a classe `highlight-AEF1E83`) em uma tabela, especificamente a célula
     * com o ID `value`.
     * 
     * Essa função utiliza o método `document.querySelector` para localizar a célula (`td`) com o
     * ID `value` dentro de uma linha (`tr`) que tenha a classe `highlight-AEF1E83`.
     * 
     * @returns {HTMLElement|null} - Retorna o elemento da célula `td#value` se encontrado,
     *                               ou `null` caso a célula ou a linha destacada não exista no DOM.
     */
    static getValueTdHighlight() {
        return document.querySelector('tr.highlight-AEF1E83 td#value');
    }

    /**
     * Função estática `getBidInput` que retorna o elemento do DOM responsável
     * pelo campo de entrada do lance.
     * 
     * Essa função utiliza o método `document.getElementById` para localizar o campo
     * de entrada do lance, identificando-o por um ID específico.
     * 
     * @returns {HTMLElement|null} - Retorna o elemento do campo de entrada do lance
     *                               se encontrado, ou `null` caso o elemento não exista no DOM.
     */
    static getBidInput() {
        return document.getElementById('bid-input-5151BB7');
    }

    /**
     * Função estática `getCodeItemOrLot` que retorna o valor do campo de entrada
     * associado ao código do item orçamentário.
     * 
     * Essa função utiliza o método `document.getElementById` para localizar o elemento
     * do campo de entrada e retorna o valor desse campo se encontrado. Caso o elemento
     * não exista, a função retorna `null`.
     * 
     * @returns {string|null} - Retorna o valor do campo `codeItemOrLot` se o elemento existir,
     *                          ou `null` caso o elemento não seja encontrado no DOM.
     */
    static getCodeItemOrLot() {
        const codeItemOrLot = document.getElementById('codigoItemOuLote');
        return codeItemOrLot ? codeItemOrLot.value : null;
    }

    static getTypeJulg() {
        const typejulg = document.getElementById('tipojulg');
        return typejulg ? typejulg.value : null;
    }

    /**
     * Função estática `getTableBody` que retorna o corpo da tabela responsável por
     * exibir os dados dinâmicos dos fornecedores.
     * 
     * Essa função utiliza o método `document.getElementById` para localizar o corpo da tabela,
     * identificando-o por um ID específico.
     * 
     * @returns {HTMLElement|null} - Retorna o elemento do corpo da tabela se encontrado,
     *                               ou `null` caso o elemento não exista no DOM.
    */
    static getTableBody() {
        return document.getElementById('dynamic-table-supplier');
    }

    /**
     * Função estática `getInputFornecedor` que retorna o elemento do DOM
     * responsável por conter o valor do fornecedor.
     * 
     * Essa função utiliza o método `document.getElementById` para localizar o campo
     * de entrada que representa o fornecedor, identificando-o por um ID específico.
     * 
     * @returns {HTMLElement|null} - Retorna o elemento de entrada para o fornecedor
     *                               se encontrado, ou `null` caso o elemento não exista no DOM.
     */
    static getInputFornecedor() {
        return document.getElementById('rsFornecedor');
    }

    /**
     * Função estática `getInputDifMinimaLance` que retorna o elemento do DOM
     * responsável por conter o valor da diferença mínima para o lance.
     * 
     * Essa função utiliza o método `document.getElementById` para localizar o campo
     * de entrada que representa a diferença mínima do lance, identificando-o por um ID específico.
     * 
     * @returns {HTMLElement|null} - Retorna o elemento de entrada para a diferença mínima do lance
     *                               se encontrado, ou `null` caso o elemento não exista no DOM.
     */
    static getInputDifMinimaLance() {
        return document.getElementById('difMinimaLance');
    }

    /**
     * Função estática `getButtons` que retorna um objeto contendo os botões da interface do usuário.
     * 
     * Essa função usa `document.getElementById` para localizar os elementos de botões com IDs específicos
     * e retorna um objeto que mapeia os nomes dos botões para seus respectivos elementos HTML.
     * 
     * @returns {Object} - Objeto contendo os botões da interface:
     *                      - `finalizar`: Botão para finalizar a operação.
     *                      - `lance`: Botão para fazer um lance.
     *                      - `semLance`: Botão para indicar que não há lance.
     *                      - `reverterLance`: Botão para reverter o lance.
     *                      - `limpaLances`: Botão para limpar os lances.
     */
    static getButtons() {
        return {
            finalizar: document.getElementById('btnFinalizar'),
            lance: document.getElementById('btnLance'),
            semLance: document.getElementById('btnSemLance'),
            reverterLance: document.getElementById('btnReverterLance'),
            limpaLances: document.getElementById('btnLimpaLances')
        };
    }

    /**
     * Função estática `getBidCard` que retorna o elemento do DOM responsável por
     * exibir o cartão de lances.
     * 
     * Essa função utiliza o método `document.getElementById` para localizar o elemento
     * que representa o cartão de lances, utilizando um ID específico.
     * 
     * @returns {HTMLElement|null} - Retorna o elemento do cartão de lances
     *                               se encontrado, ou `null` caso o elemento não exista no DOM.
     */
    static getBidCard() {
        return document.getElementById('bid-card-2D80BA98');
    }

    /**
     * Função estática `getBidCardWait` que retorna o elemento do DOM responsável por
     * exibir o indicador de carregamento (ou estado de espera) do cartão de lances.
     * 
     * Essa função utiliza o método `document.getElementById` para localizar o elemento
     * com um ID específico que representa o estado de espera do cartão de lances.
     * 
     * @returns {HTMLElement|null} - Retorna o elemento do contêiner de espera do cartão de lances
     *                               se encontrado, ou `null` caso o elemento não exista no DOM.
     */
    static getBidCardWait() {
        return document.getElementById('bid-card-wait-2D80BA98');
    }

    /**
     * Função estática `getTable` que retorna o elemento do DOM responsável por
     * conter a tabela principal da interface.
     * 
     * Essa função utiliza o método `document.getElementById` para localizar o elemento
     * que representa o contêiner da tabela através de um ID específico.
     * 
     * @returns {HTMLElement|null} - Retorna o elemento do contêiner da tabela
     *                               se encontrado, ou `null` caso o elemento não exista no DOM.
     */
    static getTable() {
        return document.getElementById('CBBF4CC-table-container');
    }

    /**
     * Função estática `getTableWait` que retorna o elemento do DOM responsável
     * por exibir o indicador de carregamento (ou estado de espera) de uma tabela.
     * 
     * Essa função utiliza o método `document.getElementById` para acessar o elemento com um ID específico.
     * 
     * @returns {HTMLElement|null} - Retorna o elemento do contêiner de espera da tabela
     *                               se encontrado, ou `null` caso o elemento não exista no DOM.
     */
    static getTableWait() {
        return document.getElementById('CBBF4CC-table-wait-container');
    }

    /**
     * Retorna a classe CSS correspondente ao status fornecido.
     * 
     * Essa função mapeia o valor do status recebido para uma classe específica que pode ser
     * usada para estilização na interface do usuário. Se o status não corresponder a nenhum caso,
     * retorna uma string vazia como padrão.
     *
     * @param {number} status - O status a ser avaliado.
     * @returns {string} - A classe CSS correspondente ao status ou uma string vazia se não houver correspondência.
     */
    static getStatusClass(status) {
        switch (status) {
            case 2: return 'bestBid-5151BB7';
            case 3: return 'noBid-5151BB7';
            case 4:
            case 5: return 'disqualified-5151BB7';
            default: return '';
        }
    }

    /**
     * Obtém o valor do elemento de entrada com o ID 'subjectValue'.
     * 
     * Essa função acessa o DOM para buscar o elemento com o identificador específico e retorna 
     * a referência a esse elemento. Pode ser usada para manipular ou obter informações diretamente 
     * do campo de entrada na interface.
     *
     * @returns {HTMLElement|null} - O elemento com o ID 'subjectValue', ou `null` se não for encontrado.
     */
    static getSubjectValue() {
        return document.getElementById('subjectValue');
    }


    /**
     * Obtém o elemento do DOM com o ID 'subjectValueDiv'.
     * 
     * Essa função retorna a referência ao elemento HTML identificado por 'subjectValueDiv'. 
     * É útil para acessar ou manipular diretamente o conteúdo ou os atributos do elemento 
     * na interface do usuário.
     *
     * Exemplo de uso:
     * ```
     * const divElement = getSubjectValueDiv();
     * if (divElement) {
     *     console.log(divElement.textContent);
     * }
     * ```
     *
     * @returns {HTMLElement|null} - O elemento com o ID 'subjectValueDiv', ou `null` se ele não existir.
     */
    static getSubjectValueDiv() {
        return document.getElementById('subjectValueDiv');
    }
}