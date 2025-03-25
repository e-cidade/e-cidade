import { BiddingPhaseFetch } from '../communication/BiddingPhaseFetch.js';
import { FormatterUi } from '../ui/FormatterUi.js';
import { GetValuesUi } from '../ui/GetValuesUi.js';

export class BiddingPhaseServices {

    constructor() {
        this.biddingPhaseFetch = new BiddingPhaseFetch();
        this.carregarDetalhesLicitacao = this.carregarDetalhesLicitacao.bind(this);
        this.carregarDetalhesLicitacaoRedirect = this.carregarDetalhesLicitacaoRedirect.bind(this);
        this.finalizar = this.finalizar.bind(this);
    }

    /**
     * Função `carregarDetalhesLicitacao` que carrega os detalhes de uma licitação,
     * preenche os campos do modal com as informações e carrega os itens relacionados.
     * 
     * @param {HTMLElement} rowElement - O elemento da linha que contém as informações da licitação a ser carregada.
     */
    async carregarDetalhesLicitacao(rowElement) {
        closeModal('customModal');

        const inputs = document.querySelectorAll('#codigo, #modalidade, #numero, #data, #hora, #objeto');
        FormatterUi.setLoadingState(inputs, true);

        try {
            const codigo = rowElement.querySelector('[data-column-id="codigo"]').textContent.trim();
            const licitacaoData = await this.biddingPhaseFetch.getLicitacaoData(`only=l20_codigo,l20_tipojulg,l20_numero,l20_dataencproposta,l20_horaencerramentoprop,l20_objeto,l20_codtipocom,l20_mododisputa&include=cflicita:l03_descr&filter=l20_codigo:${codigo}`);
            const responseData = await licitacaoData.json();
            const data = responseData.data[0];

            document.getElementById('codigo').value = data.l20_codigo;
            document.getElementById('modalidade').value = data.cflicita.l03_descr;
            document.getElementById('numero').value = data.l20_numero;
            document.getElementById('data').value = FormatterUi.formatDate(data.l20_dataencproposta);
            document.getElementById('hora').value = data.l20_horaencerramentoprop;
            document.getElementById('objeto').value = data.l20_objeto;
            document.getElementById('tipojulg').value = data.l20_tipojulg;

            document.getElementById('proposta').removeAttribute('onclick');
            document.getElementById('proposta').setAttribute('onclick', 'CurrentWindow.corpo.document.location.href=`' + routeBase + '/w/1/lic_propostas.php?l20_codigo=' + data.l20_codigo + '`');

            // Controle dos
            if (data.l20_mododisputa == 2) {

                document.getElementById('classificar').classList.add('disabled');
                document.getElementById('classificar').style.display = "none";
                document.getElementById('classificar').disabled = true;

                document.getElementById('btnFinalizar').classList.remove('disabled');
                document.getElementById('btnFinalizar').style.display = "block";
                document.getElementById('btnFinalizar').disabled = false;

            } else {
                
                document.getElementById('classificar').classList.remove('disabled');
                document.getElementById('classificar').style.display = "block";
                document.getElementById('classificar').disabled = false;

                document.getElementById('btnFinalizar').classList.add('disabled');
                document.getElementById('btnFinalizar').style.display = "none";
                document.getElementById('btnFinalizar').disabled = true;
                
            }

            document.getElementById('situacao').classList.add('disabled');
            document.getElementById('situacao').disabled;
            document.getElementById('limparLance').classList.add('disabled');
            document.getElementById('limparLance').disabled;

            if (data.l20_tipojulg == 3) {
                this.carregarLotesLicitacao(data.l20_codigo);
            } else {
                this.carregarItensLicitacao(data.l20_codigo);
            }

        } catch (error) {
            console.error('Erro ao carregar detalhes da licitação:', error);
        } finally {
            FormatterUi.setLoadingState(inputs, false);
        }
    }

    /**
     * Função `carregarDetalhesLicitacaoRedirect` que carrega os detalhes de uma licitação,
     * preenche os campos do modal com as informações e carrega os itens relacionados.
     * 
     * @param {string} codigoLicitacao - O elemento da linha que contém as informações da licitação a ser carregada.
     */
    async carregarDetalhesLicitacaoRedirect(codigoLicitacao) {
        closeModal('customModal');

        const inputs = document.querySelectorAll('#codigo, #modalidade, #numero, #data, #hora, #objeto');
        FormatterUi.setLoadingState(inputs, true);

        try {
            const codigo = codigoLicitacao;
            const licitacaoData = await this.biddingPhaseFetch.getLicitacaoData(`only=l20_codigo,l20_tipojulg,l20_numero,l20_dataencproposta,l20_horaencerramentoprop,l20_objeto,l20_codtipocom,l20_mododisputa&include=cflicita:l03_descr&filter=l20_codigo:${codigo}`);
            const responseData = await licitacaoData.json();
            const data = responseData.data[0];

            document.getElementById('codigo').value = data.l20_codigo;
            document.getElementById('modalidade').value = data.cflicita.l03_descr;
            document.getElementById('numero').value = data.l20_numero;
            document.getElementById('data').value = FormatterUi.formatDate(data.l20_dataencproposta);
            document.getElementById('hora').value = data.l20_horaencerramentoprop;
            document.getElementById('objeto').value = data.l20_objeto;
            document.getElementById('tipojulg').value = data.l20_tipojulg;

            // Controle dos
            if (data.l20_mododisputa == 2) {

                document.getElementById('classificar').classList.add('disabled');
                document.getElementById('classificar').style.display = "none";
                document.getElementById('classificar').disabled = true;

                document.getElementById('btnFinalizar').classList.remove('disabled');
                document.getElementById('btnFinalizar').style.display = "block";
                document.getElementById('btnFinalizar').disabled = false;

            } else {
                
                document.getElementById('classificar').classList.remove('disabled');
                document.getElementById('classificar').style.display = "block";
                document.getElementById('classificar').disabled = false;

                document.getElementById('btnFinalizar').classList.add('disabled');
                document.getElementById('btnFinalizar').style.display = "none";
                document.getElementById('btnFinalizar').disabled = true;
                
            }

            document.getElementById('situacao').classList.add('disabled');
            document.getElementById('situacao').disabled;
            document.getElementById('limparLance').classList.add('disabled');
            document.getElementById('limparLance').disabled;

            if (data.l20_tipojulg == 3) {
                this.carregarLotesLicitacao(data.l20_codigo);
            } else {
                this.carregarItensLicitacao(data.l20_codigo);
            }
        } catch (error) {
            console.error('Erro ao carregar detalhes da licitação:', error);
        } finally {
            FormatterUi.setLoadingState(inputs, false);
        }
    }

    /**
     * Função `carregarItensLicitacao` que carrega os itens relacionados a uma licitação,
     * desabilita algumas funções da interface e inicializa a exibição dos itens em uma tabela.
     * 
     * @param {string} codigoLicitacao - O código da licitação cujos itens devem ser carregados.
     */
    async carregarItensLicitacao(codigoLicitacao) {
        try {
            const itensData = await this.biddingPhaseFetch.getLicitacaoItens(`filter=l21_codliclicita:${codigoLicitacao}`);
            const responseData = await itensData.json();
            let url = routeDatagridGetLiclicitaItens + '?filter=l21_codliclicita:' + codigoLicitacao;

            initializeGridjslicitacoes0789676811132(url, responseData);
        } catch (error) {
            console.error('Erro ao carregar itens da licitação:', error);
        }
    }

    /**
     * Função `carregarItensLicitacao` que carrega os lotes relacionados a uma licitação,
     * desabilita algumas funções da interface e inicializa a exibição dos lotes em uma tabela.
     * 
     * @param {string} codigoLicitacao - O código da licitação cujos lotes devem ser carregados.
     */
    async carregarLotesLicitacao(codigoLicitacao) {
        try {
            const lotesData = await this.biddingPhaseFetch.getLicitacaoLotes(`filter=l21_codliclicita:${codigoLicitacao}`);
            const responseData = await lotesData.json();
            let url = routeDatagridGetLiclicitaLotes + '?filter=l21_codliclicita:' + codigoLicitacao;

            initializeGridjslicitacoes0789676811132(url, responseData);
        } catch (error) {
            console.error('Erro ao carregar itens da licitação:', error);
        }
    }

    /**
     * Função `alterarSituacoesDosItens` que atualiza o status dos itens de uma licitação,
     * mostrando um indicador de carregamento enquanto a requisição está em andamento e
     * atualiza a lista de itens exibidos após a conclusão.
     * 
     * @param {int} tipoJulg
     * @param {Array} ids - Os IDs dos itens cujas situações precisam ser alteradas.
     * @param {Array} categorias - As categorias para as quais os itens devem ser alterados.
     * @param {string} motivo - O motivo para a alteração do status dos itens.
     */
    async alterarSituacoesDosItens(tipoJulg, ids, categorias, motivo) {
        try {
            disableTheButtonAndAddTheChargingSymbol('alterarSituacoesDosItens', 'Atualizando...');
            const response = await this.biddingPhaseFetch.alterarStatusItens({ tipoJulg, ids, categorias, motivo });
            const responseData = await response.json();
            const checkStatus = this.checkStatusResponse(response.status, responseData);

            if (checkStatus) {
                createToast(responseData.message, 'success', 4000);
            }

            if (document.getElementById('tipojulg').value == 3) {
                this.carregarLotesLicitacao(document.getElementById('codigo').value);
            } else {
                this.carregarItensLicitacao(document.getElementById('codigo').value);
            }

            reactivateTheButtonAndRemoveTheChargingSymbol('alterarSituacoesDosItens');
            closeModal('situacaoLicitacaoItens')
        } catch (error) {
            createToast(error.message, 'danger', 4000);
        }
    }

    /**
     * Função `limparLancesSelecionados` que limpa os lances selecionados pelos usuários,
     * enviando uma requisição para o servidor e atualizando a lista de itens da licitação.
     * 
     * @param {NodeList} selectedRows - As linhas da tabela que foram selecionadas pelo usuário para limpar os lances.
     */
    async limparLancesSelecionados(selectedRows) {
        const tipojulg = document.getElementById('tipojulg').value;

        if (tipojulg == 3) {

            var numeroLoteCodigo = Array.from(selectedRows)
                .map(row => row.querySelector('td[data-column-id="identificador"]').textContent.trim())
                .join(',');

            var data = { 
                tipoJulg: tipojulg, 
                numeroLoteCodigo: numeroLoteCodigo 
            };

        } else {

            var pcorcamitemCodigos = Array.from(selectedRows)
                .map(row => {
                    let uuid = row.querySelector('i[data-uuid]').getAttribute('data-uuid');
                    let codigoCell = row.querySelector('td[data-column-id="sequencial"]');
                    if (!codigoCell) return null;  // Se não encontrar a célula com o código, retorna null
                    const ordem = codigoCell.textContent.trim(); 
                    const spanComOrdem1 = document.querySelector(`div#auxiliarySpansServerSideLegacy${uuid} span[data-l21_ordem="${ordem}"]`);
                    return spanComOrdem1 ? spanComOrdem1.getAttribute('data-pc22_orcamitem').trim() : null;
                })
                .filter(codigo => codigo !== null)
                .join(',');

            var data = { 
                tipoJulg: tipojulg, 
                orcamentoItemCodigo: pcorcamitemCodigos 
            };

        }

        try {
            disableTheButtonAndAddTheChargingSymbol('limparLance', 'Limpando Lances...');
            const response = await this.biddingPhaseFetch.limparLances(data);
            const responseData = await response.json();
            const checkStatus = this.checkStatusResponse(response.status, responseData);

            if (checkStatus) {
                createToast(responseData.message, 'success', 4000);
            }

            if (tipojulg == 3) {
                this.carregarLotesLicitacao(document.getElementById('codigo').value);
            } else {
                this.carregarItensLicitacao(document.getElementById('codigo').value);
            }
            reactivateTheButtonAndRemoveTheChargingSymbol('limparLance');
        } catch (error) {
            createToast(error.message, 'danger', 4000);
        }
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
        const selectedRows = getSelectedCodigo();
        const typeJulg = GetValuesUi.getTypeJulg();
        const liclicitaCodigo = document.getElementById('codigo').value;

        let codigoStatusItem = selectedRows[0].querySelector('td[data-column-id="status"]').textContent.trim();

        if (codigoStatusItem !== 'Julgado') {

            if (typeJulg == 1) {

                const liclicitemCodigo = selectedRows[0].querySelector('td[data-column-id="sequencial"]').textContent.trim();

                var data = {
                    tipoJulg: typeJulg,
                    licitacaoCodigo: liclicitaCodigo,
                    licitacaoItemCodigo: liclicitemCodigo,
                };
    
            } else if (typeJulg == 3) {
    
                const codigoLicitacaoItem = selectedRows[0].querySelector('td[data-column-id="identificador"]').textContent.trim();

                var data = {
                    tipoJulg: typeJulg,
                    licitacaoCodigo: liclicitaCodigo,
                    numeroLoteCodigo: codigoLicitacaoItem
                };
    
            }
        }

        disableTheButtonAndAddTheChargingSymbol('btnFinalizar', 'Finalizando...');

        const response = await this.biddingPhaseFetch.finish(routeFinalizar, data);
        const responseData = await response.json();
        const checkStatus = this.checkStatusResponse(response.status, responseData);

        if (checkStatus && responseData.finalizar) {
            createToast(responseData.message, 'success', 4000);

            if (tipojulg == 3) {
                this.carregarLotesLicitacao(document.getElementById('codigo').value);
            } else {
                this.carregarItensLicitacao(document.getElementById('codigo').value);
            }
        }

        reactivateTheButtonAndRemoveTheChargingSymbol('btnFinalizar');
        
        document.getElementById('btnFinalizar').classList.add('disabled');
        document.getElementById('btnFinalizar').style.display = "block";
        document.getElementById('btnFinalizar').disabled = true;
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