import { ReadjustProposalServices } from './services/ReadjustProposalServices.js';

document.addEventListener('DOMContentLoaded', () => {
    const readjustProposalServices = new ReadjustProposalServices();
    window.obterItensDaReadequecaoDeProposta = readjustProposalServices.obterItensDaReadequecaoDeProposta;
    window.verificarPropostaExistente = readjustProposalServices.verificarPropostaExistente;
    window.salvarProposta = readjustProposalServices.salvarProposta;
    window.deletarProposta = readjustProposalServices.deletarProposta;
    window.exportarItens = readjustProposalServices.exportarItens;
    window.importarItens = readjustProposalServices.importarItens;
});

