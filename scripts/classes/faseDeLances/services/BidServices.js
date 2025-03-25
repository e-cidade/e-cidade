import { FormatterUi } from '../ui/FormatterUi.js';
import { GetValuesUi } from '../ui/GetValuesUi.js';
import { JudgmentFetch } from '../communication/JudgmentFetch.js';
import { AuctionServices } from './AuctionServices.js';
import { ManipulationDom } from '../ui/ManipulationDom.js';

export class BidServices {

    constructor() {
        this.judgmentFetch = new JudgmentFetch();
        this.auctionServices = new AuctionServices();

        this.adicionarLance = this.adicionarLance.bind(this); 
        this.adicionarLanceSemValor = this.adicionarLanceSemValor.bind(this);
        this.reverterLance = this.reverterLance.bind(this);
        this.limparLances = this.limparLances.bind(this);
    }

    /**
     * Função `finalizar` que finaliza o julgamento da licitação.
     * 
     * A função coleta os dados necessários, desabilita o botão de "Finalizar" para indicar
     * que o processo está em andamento, faz uma requisição assíncrona para finalizar o julgamento
     * e, dependendo da resposta, exibe uma mensagem de sucesso e desabilita todas as funções relacionadas.
     * Por fim, reativa o botão de "Finalizar".
     */
    async finalizar() {
        let typeJulg = GetValuesUi.getTypeJulg();

        if (typeJulg == 1) {
            
            var data = {
                tipoJulg: typeJulg,
                licitacaoCodigo: liclicitaCodigo,
                licitacaoItemCodigo: liclicitemCodigo,
                orcamentoItemCodigo: GetValuesUi.getCodeItemOrLot()
            };

        } else if (typeJulg == 3) {

            var data = {
                tipoJulg: typeJulg,
                licitacaoCodigo: liclicitaCodigo,
                numeroLoteCodigo: GetValuesUi.getCodeItemOrLot()
            };

        }

        disableTheButtonAndAddTheChargingSymbol('btnFinalizar', 'Finalizando...');

        const response = await this.judgmentFetch.finish(routeJugamentoFinalizar, data);
        const responseData = await response.json();
        const checkStatus = this.checkStatusResponse(response.status, responseData);

        if (checkStatus && responseData.finalizar) {
            createToast(responseData.message, 'success', 4000);
            ManipulationDom.disableAllFunctions();
            await this.auctionServices.fetchSuppliers(true);
        }

        await reactivateTheButtonAndRemoveTheChargingSymbol('btnFinalizar');
    }

    /**
     * Função `adicionarLance` que adiciona um lance ao item da licitação.
     * 
     * A função coleta os dados necessários para o lance, desabilita o botão de "Lance" para
     * indicar que o processo está em andamento, realiza uma requisição assíncrona para registrar o lance
     * e, dependendo da resposta, atualiza o valor exibido no DOM, exibe uma mensagem de sucesso, habilita
     * botões relacionados e realiza ações adicionais, como destacar a linha da tabela com o próximo lance.
     */
    async adicionarLance() {
        let typeJulg = GetValuesUi.getTypeJulg();

        if (typeJulg == 1) {
            var data = {
                tipoJulg: typeJulg,
                licitacaoCodigo: liclicitaCodigo,
                licitacaoItemCodigo: liclicitemCodigo,
                fornecedorCodigo: GetValuesUi.getRowDataId(),
                valorLance: GetValuesUi.getBidValue(),
                orcamentoItemCodigo: GetValuesUi.getCodeItemOrLot()
            };
        } else if (typeJulg == 3) {
            var data = {
                tipoJulg: typeJulg,
                licitacaoCodigo: liclicitaCodigo,
                fornecedorCodigo: GetValuesUi.getRowDataId(),
                valorLance: GetValuesUi.getBidValue(),
                numeroLoteCodigo: GetValuesUi.getCodeItemOrLot()
            };
        }

        disableTheButtonAndAddTheChargingSymbol('btnLance', 'Adicionando...');

        const response = await this.judgmentFetch.addBid(routeRegistrarLance, data);
        const responseData = await response.json();
        const checkStatus = this.checkStatusResponse(response.status, responseData);

        reactivateTheButtonAndRemoveTheChargingSymbol('btnLance');

        if (checkStatus && responseData.julgLance) {
            const valueTd = GetValuesUi.getValueTdHighlight();

            if (valueTd && responseData.julgLance) {
                valueTd.textContent = FormatterUi.formatCurrency(responseData.julgLance.l32_lance);
                createToast(responseData.message, 'success', 4000);

                let btnReverterLance = document.getElementById('btnReverterLance');
                btnReverterLance.classList.remove('disabled');
                btnReverterLance.removeAttribute('disabled');
            
                let btnLimpaLances = document.getElementById('btnLimpaLances');
                btnLimpaLances.classList.remove('disabled');
                btnLimpaLances.removeAttribute('disabled');

                ManipulationDom.cleanBidInput();

                this.auctionServices.highlightRow(responseData.nextBid, diferencaMinimaDeLance);
            }
        }

    }

    /**
     * Função `adicionarLanceSemValor` que adiciona um lance sem valor ao item da licitação.
     * 
     * A função coleta os dados necessários para adicionar o lance sem valor, desabilita o botão de "Sem Lance" 
     * para indicar que o processo está em andamento, realiza uma requisição assíncrona para registrar o lance
     * sem valor e, dependendo da resposta, exibe uma mensagem de sucesso e atualiza a lista de fornecedores.
     */
    async adicionarLanceSemValor() {
        let typeJulg = GetValuesUi.getTypeJulg();

        if (typeJulg == 1) {
            var data = {
                tipoJulg: typeJulg,
                licitacaoCodigo: liclicitaCodigo,
                licitacaoItemCodigo: liclicitemCodigo,
                fornecedorCodigo: GetValuesUi.getRowDataId(),
                orcamentoItemCodigo: GetValuesUi.getCodeItemOrLot()
            };
        } else if (typeJulg == 3) {
            var data = {
                tipoJulg: typeJulg,
                licitacaoCodigo: liclicitaCodigo,
                fornecedorCodigo: GetValuesUi.getRowDataId(),
                numeroLoteCodigo: GetValuesUi.getCodeItemOrLot()
            };
        }

        disableTheButtonAndAddTheChargingSymbol('btnSemLance', 'Adicionando...');

        const response = await this.judgmentFetch.addBidWithoutValue(routeRegistrarLanceSemValor, data);
        const responseData = await response.json();
        const checkStatus = this.checkStatusResponse(response.status, responseData);

        if (checkStatus && responseData.julgLance) {
            createToast(responseData.message, 'success', 4000);
            await this.auctionServices.fetchSuppliers();
        }

        await reactivateTheButtonAndRemoveTheChargingSymbol('btnSemLance');
    }

    /**
     * Função `reverterLance` que reverte o lance de um item da licitação.
     * 
     * A função coleta os dados necessários para reverter o lance, desabilita o botão de "Reverter Lance"
     * para indicar que o processo está em andamento, realiza uma requisição assíncrona para reverter o lance
     * e, dependendo da resposta, exibe uma mensagem de sucesso e atualiza a lista de fornecedores.
     */
    async reverterLance() {
        let typeJulg = GetValuesUi.getTypeJulg();

        if (typeJulg == 1) {
            var data = { 
                tipoJulg: typeJulg,
                orcamentoItemCodigo: GetValuesUi.getCodeItemOrLot()
            };
        } else if (typeJulg == 3) {
            var data = { 
                tipoJulg: typeJulg,
                numeroLoteCodigo: GetValuesUi.getCodeItemOrLot()
            };
        }

        disableTheButtonAndAddTheChargingSymbol('btnReverterLance', 'Revertendo...');

        const response = await this.judgmentFetch.reverseLance(routeReverterLance, data);
        const responseData = await response.json();
        const checkStatus = this.checkStatusResponse(response.status, responseData);

        if (checkStatus) {
            createToast(responseData.message, 'success', 4000);
            await this.auctionServices.fetchSuppliers();
        }

        await reactivateTheButtonAndRemoveTheChargingSymbol('btnReverterLance');
    }

    /**
     * Função `limparLances` que limpa os lances registrados para um item da licitação.
     * 
     * A função coleta os dados necessários para limpar os lances, desabilita o botão de "Limpar Lances"
     * para indicar que o processo está em andamento, realiza uma requisição assíncrona para limpar os lances
     * e, dependendo da resposta, exibe uma mensagem de sucesso e atualiza a lista de fornecedores.
     */
    async limparLances() {
        let typeJulg = GetValuesUi.getTypeJulg();

        if (typeJulg == 1) {
            var data = { 
                tipoJulg: typeJulg,
                orcamentoItemCodigo: GetValuesUi.getCodeItemOrLot()
            };
        } else if (typeJulg == 3) {
            var data = { 
                tipoJulg: typeJulg,
                numeroLoteCodigo: GetValuesUi.getCodeItemOrLot()
            };
        }

        disableTheButtonAndAddTheChargingSymbol('btnLimpaLances', 'Limpando Lances...');

        const response = await this.judgmentFetch.cleanBids(routeLimparLances, data);
        const responseData = await response.json();
        const checkStatus = this.checkStatusResponse(response.status, responseData);

        if (checkStatus) {
            createToast(responseData.message, 'success', 4000);
            await this.auctionServices.fetchSuppliers();
        }

        await reactivateTheButtonAndRemoveTheChargingSymbol('btnLimpaLances');
    }

    /**
     * Função `checkStatusResponse` que verifica o status da resposta da requisição HTTP
     * e exibe mensagens de erro ou sucesso dependendo do status retornado.
     * 
     * @param {number} status - O status da resposta HTTP (ex: 200, 400, 422, 500).
     * @param {object} responseData - O objeto de dados retornado na resposta (em formato JSON).
     * @returns {boolean} Retorna `true` se a resposta for bem-sucedida (status 200), caso contrário, `false`.
     */
    checkStatusResponse(status, responseData) {
        if (status === 422) {
            if (responseData) {
                createToast(`${responseData.message}`, 'danger', 9000);
            } 
            if (responseData.errors) {
                Object.keys(responseData.errors).forEach(key => {
                    responseData.errors[key].forEach(errorMessage => {
                        createToast(`${errorMessage}`, 'warning', 9000);
                    });
                });
            }
            return false;
        } else if (status === 400 || status === 500) {
            if (responseData.error) {
                createToast(`${responseData.error}`, 'danger', 9000);
            }
            return false;
        } else if(status === 200) {
            return true;
        }
    }
}