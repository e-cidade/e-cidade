export class ReadjustProposalUi {
    /**
     * Obtém o botão de salvar no DOM.
     * @returns {HTMLElement|null} Elemento do botão com id 'salvarBtn' ou null se não encontrado.
     */
    static getSalvarBtn() {
        return document.getElementById('salvarBtn1');
    }

    /**
     * Obtém o tooltip associado ao botão de salvar.
     * @returns {HTMLElement|null} Elemento do tooltip com id 'salvarBtnTooltip' ou null se não encontrado.
     */
    static getSalvarBtnTooltip() {
        return document.getElementById('salvarBtnTooltip');
    }

    /**
     * Seleciona todos os campos de entrada de valores unitários.
     * @returns {NodeListOf<HTMLInputElement>} Lista de inputs com o atributo name 'vlrUnitario'.
     */
    static getInputsValores() {
        return document.querySelectorAll('input[name="vlrUnitario"]');
    }

    /**
     * Seleciona todos os campos de entrada de marcas.
     * @returns {NodeListOf<HTMLInputElement>} Lista de inputs com o atributo name 'marca'.
     */
    static getInputsMarcas() {
        return document.querySelectorAll('input[name="marca"]');
    }

    /**
     * Obtém o corpo da tabela de itens.
     * @returns {HTMLElement|null} Elemento tbody com id 'tbodyItens' ou null se não encontrado.
     */
    static getTbodyItens() {
        return document.getElementById('tbodyItens');
    }

    /**
     * Obtém o contador de itens da tabela.
     * @returns {HTMLElement|null} Elemento com id 'contadorItens' ou null se não encontrado.
     */
    static getContadorItens() {
        return document.getElementById('contadorItens');
    }

    /**
     * Obtém o valor restante disponível no DOM.
     * @returns {HTMLElement|null} Elemento com id 'valorRestante' ou null se não encontrado.
     */
    static getValorRestante() {
        return document.getElementById('valorRestante');
    }

    /**
     * Obtém o valor da licitação a partir do input correspondente.
     * @returns {string|null} Valor da licitação ou null se o elemento não for encontrado.
     */
    static getLicitacao() {
        const licitacaoElement = document.getElementById('licitacao');
        return licitacaoElement ? licitacaoElement.value : null;
    }

    /**
     * Obtém o valor do lote a partir do input correspondente.
     * @returns {string|null} Valor do lote ou null se o elemento não for encontrado.
     */
    static getLote() {
        const loteElement = document.getElementById('lote');
        return loteElement ? loteElement.value : null;
    }

    /**
     * Obtém o código do fornecedor a partir do elemento de input com o id 'fornecedor'.
     * @returns {string|null} O código do fornecedor ou null se o elemento não existir.
     */
    static getFornecedorCodigo() {
        const fornecedorElement = document.getElementById('fornecedor');
        return fornecedorElement ? fornecedorElement.value : null;
    }

    /**
     * Obtém a razão social a partir do elemento de input com o id 'razaoSocial'.
     * @returns {string|null} A razão social ou null se o elemento não existir.
     */
    static getRazaoSocial() {
        const razaoSocialElement = document.getElementById('razaoSocial');
        return razaoSocialElement ? razaoSocialElement.value : null;
    }

    /**
     * Obtém o CNPJ a partir do elemento de input com o id 'cnpj'.
     * @returns {string|null} O CNPJ ou null se o elemento não existir.
     */
    static getCnpj() {
        const cnpjElement = document.getElementById('cnpj');
        return cnpjElement ? cnpjElement.value : null;
    }
}