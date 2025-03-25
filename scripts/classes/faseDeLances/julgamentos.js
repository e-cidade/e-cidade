import { AuctionServices } from './services/AuctionServices.js';
import { BidServices } from './services/BidServices.js';
import { SupplierStatusServices } from './services/SupplierStatusServices.js';
import { MicroEnterpriseServices } from './services/MicroEnterpriseServices.js';
import { ManipulationDom } from './ui/ManipulationDom.js';

const auctionServices = new AuctionServices();
const bidServices = new BidServices();
const supplierStatusServices = new SupplierStatusServices();
const microEnterpriseServices = new MicroEnterpriseServices();

document.addEventListener('DOMContentLoaded', () => {
    auctionServices.fetchSuppliers();

    if (julgitemstatus === 2) {
        ManipulationDom.disableAllFunctions();
    }
});

window.leiMicroEmpresa = microEnterpriseServices.openLawMicroEnterprise;
window.liberarMicroEmpresas = microEnterpriseServices.releaseMicroEnterprises;
window.semLanceMicroEmpresa = microEnterpriseServices.finalizeNoBid;

window.finalizar = bidServices.finalizar;
window.adicionarLance = bidServices.adicionarLance;
window.adicionarLanceSemValor = bidServices.adicionarLanceSemValor;
window.reverterLance = bidServices.reverterLance;
window.limparLances = bidServices.limparLances;

window.fornecedorSituacoes = supplierStatusServices.supplierStatus;
window.alterarSituacaoDoFornecedor = supplierStatusServices.changeSupplierStatus;

window.obterListaDeFornecedoresEPropostaDeLicitacao = auctionServices.fetchSuppliers;
window.highlightRowDescending = auctionServices.highlightRow;
window.desativarFuncoesDoJulgamentos = auctionServices.disableAllFunctions;
window.populateTable = auctionServices.populateTable;
window.subjectValueComply = auctionServices.subjectValueComply;