import { GetValuesUi } from './GetValuesUi.js';

export class ManipulationDom {
    /**
     * Alterna o estado de carregamento da interface, exibindo ou ocultando elementos relacionados.
     * 
     * Essa função mostra ou oculta os elementos de carregamento e os elementos principais da interface
     * dependendo do valor do parâmetro `isLoading`. Ela manipula as classes CSS para exibir ou ocultar
     * as seções de carregamento e de conteúdo conforme necessário.
     *
     * @param {boolean} isLoading - Indica se a interface está em estado de carregamento (verdadeiro) ou não (falso).
     */
    static toggleLoadingState(isLoading) {
        let bidCard = GetValuesUi.getBidCard();
        let bidCardWait = GetValuesUi.getBidCardWait();
        let table = GetValuesUi.getTable();
        let tableWait = GetValuesUi.getTableWait();

        bidCard.classList.toggle('d-none', isLoading);
        bidCardWait.classList.toggle('d-none', !isLoading);

        table.classList.toggle('d-none', isLoading);
        tableWait.classList.toggle('d-none', !isLoading);
    }

    /**
     * Atualiza os estados dos botões com base nas condições fornecidas.
     * 
     * Essa função ajusta a ativação e desativação de botões na interface do usuário
     * de acordo com os valores das condições recebidas como parâmetros.
     *
     * @param {Object} conditions - Um objeto com as seguintes propriedades:
     *   - {boolean} allStatus2: Se verdadeiro, desativa todas as funções.
     *   - {boolean} allStatus3: Se verdadeiro, ativa o botão 'finalizar' e desativa os botões 'lance' e 'semLance'.
     *   - {boolean} anyStatus2: Se verdadeiro, afeta a ativação de botões relacionados a reverter e limpar lances.
     *   - {boolean} anyLanceNotNull: Se verdadeiro, também afeta a ativação de botões relacionados a reverter e limpar lances.
     */
    static updateButtonStates({ allStatus2, allStatus3, anyStatus2, anyLanceNotNull }) {

        let buttons = GetValuesUi.getButtons();

        if (allStatus2) {
            this.disableAllFunctions();
        } else if (allStatus3) {
            this.enableElement(buttons.finalizar);
            this.disableElement(buttons.lance);
            this.disableElement(buttons.semLance);
        } else {
            this.disableElement(buttons.finalizar);
            this.enableElement(buttons.lance);
            this.enableElement(buttons.semLance);
        }

        if ((anyLanceNotNull || anyStatus2) && !allStatus2) {
            this.enableElement(buttons.reverterLance);
            this.enableElement(buttons.limpaLances);
        } else {
            this.disableElement(buttons.reverterLance);
            this.disableElement(buttons.limpaLances);
        }
    }

    /**
     * Adiciona a classe 'disabled' ao elemento e define o atributo 'disabled'.
     * 
     * Essa função serve para desativar um elemento na interface do usuário.
     * - A classe 'disabled' pode ser usada para estilizar o elemento visualmente como desativado.
     * - O atributo 'disabled' desativa a interação funcional do elemento, 
     *   como impedir cliques em botões ou entrada de dados em campos de formulário.
     *
     * @param {HTMLElement} element - O elemento a ser desativado.
     */
    static disableElement(element) {
        element.classList.add('disabled');
        element.setAttribute('disabled', true);
    }

    /**
     * Remove a classe 'disabled' do elemento e exclui o atributo 'disabled'.
     * 
     * Essa função reativa um elemento na interface do usuário.
     * - A remoção da classe 'disabled' permite que o elemento volte ao estado visual normal.
     * - A exclusão do atributo 'disabled' permite que o elemento recupere a interação funcional,
     *   como aceitar cliques ou entrada de dados.
     *
     * @param {HTMLElement} element - O elemento a ser reativado.
     */
    static enableElement(element) {
        element.classList.remove('disabled');
        element.removeAttribute('disabled');
    }

    /**
     * Desativa todas as funções e elementos interativos relacionados.
     * 
     * Essa função percorre um conjunto de botões e outros elementos específicos,
     * aplicando a desativação para impedir interações do usuário.
     * - Os botões armazenados no objeto `this.buttons` são desativados usando a função `disableElement`.
     * - Um campo de entrada específico e um cartão visual são identificados por seus IDs únicos e desativados:
     *   - O campo de entrada recebe a desativação funcional via `disableElement`.
     *   - O cartão recebe apenas a classe 'disabled' para estilização visual.
     */
    static disableAllFunctions() {
        let buttons = GetValuesUi.getButtons();

        Object.values(buttons).forEach(button => this.disableElement(button));

        let bidInput = GetValuesUi.getBidInput();
        let bidCard = GetValuesUi.getBidCard();

        bidInput.classList.add('bid-input-5151BB7-disabled');
        bidInput.setAttribute('disabled', true);

        bidCard.classList.add('bid-card-2D80BA98-disabled');
    }

    /**
     * Função estática `controlBidsLessThanZero` que desativa elementos da interface
     * quando uma determinada condição é atendida (neste caso, lances menores que zero).
     * 
     * Essa função utiliza métodos da classe `GetValuesUi` para acessar elementos da interface
     * e aplica classes de estilo e atributos para indicar o estado desativado.
     */
    static controlBidsLessThanZero() {
        let buttons = GetValuesUi.getButtons();
        let bidInput = GetValuesUi.getBidInput();
        let bidCard = GetValuesUi.getBidCard();

        bidInput.classList.add('bid-input-5151BB7-disabled');
        bidInput.setAttribute('disabled', true);

        bidCard.classList.add('bid-card-2D80BA98-disabled');
        
        this.disableElement(buttons.lance);
    }

    /**
     * Função estática `enableBids` que reativa elementos da interface 
     * desativados pela função `controlBidsLessThanZero`.
     * 
     * Essa função utiliza métodos da classe `GetValuesUi` para acessar 
     * elementos da interface e remove classes de estilo e atributos 
     * para reativar os elementos.
     */
    static enableBids() {
        let buttons = GetValuesUi.getButtons();
        let bidInput = GetValuesUi.getBidInput();
        let bidCard = GetValuesUi.getBidCard();

        // Remove a classe de estilo e reativa o input
        bidInput.classList.remove('bid-input-5151BB7-disabled');
        bidInput.removeAttribute('disabled');

        // Remove a classe de estilo do cartão
        bidCard.classList.remove('bid-card-2D80BA98-disabled');
        
        // Reativa o botão
        this.enableElement(buttons.lance);
    }

    /**
     * Limpa o valor do campo de entrada de lance.
     * 
     * Obtém o campo de entrada de lance através do método `GetValuesUi.getBidInput` 
     * e redefine seu valor para uma string vazia.
     */
    static cleanBidInput() {
        let bidInput = GetValuesUi.getBidInput();
        bidInput.value = "";
    }
}