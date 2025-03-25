import { AuctionServices } from './AuctionServices.js';
import { BidServices } from './BidServices.js';
import { GetValuesUi } from '../ui/GetValuesUi.js';

export class MicroEnterpriseServices {

    constructor() {
        this.auctionServices = new AuctionServices();
        this.bidServices = new BidServices();

        this.releaseMicroEnterprises = this.releaseMicroEnterprises.bind(this);
        this.finalizeNoBid = this.finalizeNoBid.bind(this);
    }

    /**
     * Função `openLawMicroEnterprise` que abre o modal de "leiMicroEmpresa" e carrega
     * os dados das microempresas a partir de uma requisição `GET`.
     * 
     * A função abre o modal e, em seguida, realiza uma requisição ao servidor para obter
     * informações sobre as microempresas. Se os dados forem encontrados, exibe os nomes das
     * microempresas no modal. Caso contrário, finaliza a ação sem lance.
     */
    openLawMicroEnterprise(protocolNumber) {

        let sessionData = sessionStorage.getItem(protocolNumber);
        if (sessionData) {
            let { value, expirationTime } = JSON.parse(sessionData);
            if (Date.now() >= expirationTime) {
                sessionStorage.removeItem(protocolNumber);
            } else {
                semLanceMicroEmpresa();
                return;
            }
        }

        let typeJulg = GetValuesUi.getTypeJulg();

        fetch(routeLeiMicroEmpresa + '?tipojulg=' + typeJulg, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(erro => {
                    if (erro.error) createToast(erro.error, 'danger', 9000);
                });
            }
            return response.json();
        })
        .then((dados) => {
            if (!dados || !dados.data || dados.data.length === 0) {
                semLanceMicroEmpresa();
                return;
            }

            openModal('leiMicroEmpresa');
            const names = dados.data.map(item => item.z01_nome).join(', ');
            document.getElementById('microEmpresasPopupText').innerHTML = names;
        })
        .catch(erro => console.error('Erro ao carregar os dados:', erro));
    }

    /**
     * Função `releaseMicroEnterprises` que libera as microempresas para uma licitação específica.
     * 
     * A função fecha o modal de "leiMicroEmpresa", coleta os dados necessários (código da licitação
     * e código do item da licitação) e os envia via uma requisição `POST` para o servidor.
     * Se a requisição for bem-sucedida, uma mensagem de sucesso é exibida e a lista de fornecedores
     * é atualizada. Caso contrário, erros são tratados e exibidos ao usuário.
     */
    releaseMicroEnterprises(protocolNumber) {
        closeModal('leiMicroEmpresa');

        let typeJulg = GetValuesUi.getTypeJulg();


        if (typeJulg == 1) {

            var dados = {
                tipeJulg: typeJulg,
                codigoLicitacao: liclicitaCodigo,
                codigoLicitacaoItem: liclicitemCodigo
            };

        } else if (typeJulg == 3) {

            var dados = {
                tipeJulg: typeJulg,
                codigoLicitacao: liclicitaCodigo,
                numeroLote: GetValuesUi.getCodeItemOrLot()
            };
            
        }

        fetch(routeLiberarMicroEmpresas, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dados)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(erro => {
                    if (erro.error) createToast(erro.error, 'danger', 9000);
                });
            }
            return response.json();
        })
        .then(dados => {
            if (dados.liberarMicroEmpresas) {
                createToast(dados.message, 'success', 4000);
                this.auctionServices.fetchSuppliers(true);

                let expirationTime = Date.now() + 3 * 60 * 1000;
                let sessionData = { value, expirationTime };
                sessionStorage.setItem(protocolNumber, JSON.stringify(sessionData));
            }
        })
        .catch(erro => console.error('Erro ao liberar as microempresas:', erro));
    }

    /**
     * Função `finalizeNoBid` que finaliza a ação de lance, quando não há lance.
     * 
     * A função fecha o modal de "leiMicroEmpresa" e chama o serviço responsável
     * por finalizar a ação (no caso, `this.bidServices.finalizar()`).
     */
    finalizeNoBid() {
        closeModal('leiMicroEmpresa');
        this.bidServices.finalizar();
    }
}