import { BiddingPhaseServices } from './services/BiddingPhaseServices.js';
import { JudgmentFetch } from './communication/JudgmentFetch.js';
import { GetValuesUi } from './ui/GetValuesUi.js';

const biddingPhaseServices = new BiddingPhaseServices();
const judgmentFetch = new JudgmentFetch();

/**
 * Função `licitacoesItensSituacoes` que gerencia a exibição e manipulação de informações
 * relacionadas às situações dos itens de licitações selecionados.
 * 
 * A função organiza os códigos e descrições dos itens selecionados, atualiza os campos da interface,
 * exibe badges para múltiplas seleções e abre um modal para permitir alterações de situações.
 */
function licitacoesItensSituacoes() {
    const selectedRows = getSelectedCodigo();

    const ids = document.getElementById('idsStatusItem');
    const fieldCode = document.getElementById('situacaoItemCodigoDiv');
    const fieldDesc = document.getElementById('situacaoItemDescDiv');
    const tooltipBadgesDiv = document.getElementById('tooltip-badges-div');
    const tooltipBadgesBody = document.getElementById('tooltip-badges-body');

    let tipojulg = GetValuesUi.getTypeJulg();

    if (tipojulg == 3) {
        var codigoDoItem = Array.from(selectedRows)
            .map(row => {
                let codigoCell = row.querySelector('td[data-column-id="identificador"]');
                return codigoCell ? codigoCell.textContent.trim() : null;
            })
            .filter(codigo => codigo !== null)
            .join(',');
    } else {
        var codigoDoItem = Array.from(selectedRows)
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
    }

    ids.value = codigoDoItem;

    if(selectedRows.length > 1) {
        const badges = [];
        let tipojulg = GetValuesUi.getTypeJulg();

        selectedRows.forEach(row => {
            if (tipojulg == 3) {
                var codigoCell = row.querySelector('td[data-column-id="identificador"]');
            } else {
                var codigoCell = row.querySelector('td[data-column-id="sequencial"]');
            }
            const descricaoCell = row.querySelector('td[data-column-id="descricao"]');
            if (codigoCell && descricaoCell) {
                badges.push(
                    `<div style="background-color: white; padding: 5px; font-size: .7rem; margin-right: 5px; border-radius: 5px; margin-bottom: 10px;">
                        ${codigoCell.textContent.trim()} - 
                        ${descricaoCell.textContent.trim().substring(0, 15)}...
                    </div>`
                );
            }
        });

        if (tooltipBadgesBody) {
            tooltipBadgesBody.innerHTML = badges.join(' ');
        }

        tooltipBadgesDiv.classList.remove('d-none');
        fieldCode.classList.add('d-none');
        fieldDesc.classList.add('d-none');
    } else {
        tooltipBadgesDiv.classList.add('d-none');
        fieldCode.classList.remove('d-none');
        fieldDesc.classList.remove('d-none');
    }

    if (selectedRows.length !== 0) {
        openModal('situacaoLicitacaoItens');
    }
    
    if (selectedRows.length == 1) {
        let tipojulg = GetValuesUi.getTypeJulg();

        selectedRows.forEach(row => {
            if (tipojulg == 3) {
                var codigoCell = row.querySelector('td[data-column-id="identificador"]');
                var descricaoCell = row.querySelector('td[data-column-id="descricao"]');
            } else {
                var codigoCell = row.querySelector('td[data-column-id="sequencial"]');
                var descricaoCell = row.querySelector('td[data-column-id="descricao"]');
            }

            document.getElementById('situacaoItemCodigo').value = codigoCell.textContent.trim();
            document.getElementById('situacaoItemDesc').value = descricaoCell.textContent.trim().substring(0, 30)+'...';
        });
    }
} 

/**
 * Função `alterarSituacoesDosItens` que é responsável por alterar as situações dos itens de licitação.
 * 
 * Esta função coleta os valores de IDs, categorias e motivo a partir de elementos da interface do usuário
 * e os utiliza para chamar um serviço que realiza a alteração das situações dos itens.
 */
function alterarSituacoesDosItens() {
    let tipoJulg = GetValuesUi.getTypeJulg();
    let ids = document.getElementById('idsStatusItem');
    let categorias = document.getElementById('categoriasstatusitem');
    let motivo = document.getElementById('motivoStatusItem');
    biddingPhaseServices.alterarSituacoesDosItens(tipoJulg, ids.value, categorias.value, motivo.value);
}

/**
 * Função `limparLances` que é responsável por limpar os lances dos itens de licitação
 * que foram selecionados na interface do usuário.
 * 
 * Esta função utiliza um serviço (`biddingPhaseServices`) para executar a ação,
 * garantindo que a operação só seja realizada se houver itens selecionados.
 */
function limparLances() {
    const selectedRows = getSelectedCodigo();
    if (selectedRows.length !== 0) {
        biddingPhaseServices.limparLancesSelecionados(selectedRows);
    }
}

/**
 * Função assíncrona `classificarLicitacoesItens` que realiza a classificação de itens de licitações.
 * Essa função interage com a interface do usuário, desativa o botão durante a execução,
 * realiza chamadas de API e redireciona o usuário para outra página dependendo dos resultados.
 */
async function classificarLicitacoesItens() {
    const selectedRows = getSelectedCodigo();

    disableTheButtonAndAddTheChargingSymbol('classificar', 'Classificando...');

    if (selectedRows.length === 1) {
        const codigoLicitacao = document.getElementById('codigo').value;
        let codigoStatusItem = selectedRows[0].querySelector('td[data-column-id="status"]').textContent.trim();

        if (codigoStatusItem !== 'Julgado') {

            let tipojulg = GetValuesUi.getTypeJulg();

            if (tipojulg == 3) {
                var codigoLicitacaoItem = selectedRows[0].querySelector('td[data-column-id="identificador"]').textContent.trim();
            } else {
                var codigoLicitacaoItem = selectedRows[0].querySelector('td[data-column-id="sequencial"]').textContent.trim();
            }

            const routeObterFornecedoresProposta = routeFornecedoresPropostasTemp
                .replace(':codigoLicitacao', codigoLicitacao)
                .replace(':codigoLicitacaoItem', codigoLicitacaoItem);
    
            const listaDeFornecedoresEPropostaDeLicitacao = await judgmentFetch.fetchSuppliers(routeObterFornecedoresProposta, false, tipojulg);
            const listaDeFornecedoresEPropostaDeLicitacaoData = await listaDeFornecedoresEPropostaDeLicitacao.json();

            if (listaDeFornecedoresEPropostaDeLicitacao.status == 200) {
                const routePregaoUrl = routeJulgamento;
                const url = routePregaoUrl
                    .replace(':codigoLicitacao', codigoLicitacao)
                    .replace(':codigoLicitacaoItem', codigoLicitacaoItem);
                window.location.href = url;
            } else {
                createToast(listaDeFornecedoresEPropostaDeLicitacaoData.error, 'danger', 4000);
            }
        }
    }

    reactivateTheButtonAndRemoveTheChargingSymbol('classificar');
}

async function readequarPropostaLotes() {
    const selectedRows = getSelectedCodigo();

    disableTheButtonAndAddTheChargingSymbol('readequarPropostaBT', 'Redirecionando...');

    if (selectedRows.length === 1) {
        const codigoLicitacao = document.getElementById('codigo').value;
        let codigoStatusItem = sanitizeText(selectedRows[0].querySelector('td[data-column-id="status"]').textContent.trim());

        if (codigoStatusItem === 'aguardando readequacao' || codigoStatusItem === 'julgado') {

            let tipojulg = GetValuesUi.getTypeJulg();

            if (tipojulg == 3) {
                var codigoLicitacaoItem = selectedRows[0].querySelector('td[data-column-id="identificador"]').textContent.trim();
            } else {
                var codigoLicitacaoItem = selectedRows[0].querySelector('td[data-column-id="sequencial"]').textContent.trim();
            }

            const url = routeReadequarProposta
                .replace(':codigoLicitacao', codigoLicitacao)
                .replace(':codigoLicitacaoItem', codigoLicitacaoItem);

            window.location.href = url;
        }
    }

    reactivateTheButtonAndRemoveTheChargingSymbol('readequarPropostaBT');
}

/**
 * Função `clickNaLinhaFunction` que é responsável por gerenciar o estado de botões
 * com base nas condições dos dados de linhas de uma tabela. 
 * Ela permite habilitar ou desabilitar botões dependendo do status
 * das linhas selecionadas na tabela.
 * 
 * @param {NodeList | Array} rows - Uma coleção de elementos de linha (<tr>) da tabela.
 */
function clickNaLinhaFunction(rows) {
    const rowsArray = Array.isArray(rows) ? rows : Array.from(rows);

    const toggleButton = (buttonId, condition) => {
        const button = document.getElementById(buttonId);
        if (button) {
            button.classList.toggle('disabled', !condition);
            button.toggleAttribute('disabled', !condition);
        }
    };

    const hasOpenStatus = rowsArray.some(row => {
        const statusCell = row.querySelector('td[data-column-id=\"status\"]');
        return statusCell && sanitizeText(statusCell.textContent) === "em aberto";
    });

    const allJudgedOrReadequationStatus = rowsArray.every(row => {
        const statusCell = row.querySelector('td[data-column-id="status"]');
        const status = statusCell ? sanitizeText(statusCell.textContent) : "";
        return status === "julgado" || status === "aguardando readequacao";
    });

    let tipojulg = GetValuesUi.getTypeJulg();

    const hasReadjustProposalStatus = rowsArray.some(row => {
        const statusCell = row.querySelector('td[data-column-id="status"]');
        return statusCell && ["aguardando readequacao", "julgado"].includes(sanitizeText(statusCell.textContent).toLowerCase());
    });

    toggleButton('classificar', rowsArray.length === 1 && hasOpenStatus);
    toggleButton('btnFinalizar', rowsArray.length === 1 && hasOpenStatus);
    toggleButton('limparLance', rowsArray.length >= 1 && allJudgedOrReadequationStatus);
    if(tipojulg == 3) {
        toggleButton('readequarPropostaBT', rowsArray.length === 1 && hasReadjustProposalStatus);
    }
    toggleButton('situacao', rowsArray.length >= 1);
}

function sanitizeText(text) {
    return text.normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .replace(/\s+/g, " ")
        .trim()
        .toLowerCase();
}

function debounce(func, delay) {
    let debounceTimeout;
    return function (...args) {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => func.apply(this, args), delay);
    };
}

function handleMouseEvents(cellsSelector, descricaoDivId, uuid, dataColumn, spanGetAttribute, spanAttribute) {
    const cells = document.querySelectorAll(`#gridjsContent${uuid} ${cellsSelector}`);
    const descricaoDiv = document.getElementById(descricaoDivId);

    let descricaoVisivel = false;

    function mostrarDescricao(texto) {
        if (texto && texto.trim() !== "") {
            descricaoDiv.innerText = texto;
    
            if (descricaoVisivel) return;
            descricaoVisivel = true;

            descricaoDiv.classList.remove("esconder");
            descricaoDiv.classList.add("mostrar");
        } else {
            if (descricaoVisivel) esconderDescricao();
        }
    }
    
    function esconderDescricao() {

        if (!descricaoVisivel) return;
        descricaoVisivel = false;
    
        descricaoDiv.classList.remove("mostrar");
        descricaoDiv.classList.add("esconder");
        descricaoDiv.innerText = '';
    }

    const debouncedMostrarDescricao = debounce(mostrarDescricao, 400);
    const debouncedEsconderDescricao = debounce(esconderDescricao, 400);

    cells.forEach(cell => {
        cell.addEventListener('mouseenter', function () {
            const currentRow = this.closest('tr');
            if (!currentRow) return;

            const ordemElement = currentRow.querySelector(`[data-column-id="${dataColumn}"]`);
            if (!ordemElement) return;

            const ordem = ordemElement.innerText.trim();
            const auxiliarySpan = document.querySelector(`#auxiliarySpansServerSideLegacy${uuid} span[${spanGetAttribute}="${ordem}"]`);
            if (!auxiliarySpan) return;

            const texto = auxiliarySpan.getAttribute(spanAttribute);
            debouncedMostrarDescricao(texto);
        });

        cell.addEventListener('mouseleave', debouncedEsconderDescricao);
    });
}

function descricaoDescFunction(uuid) {
    handleMouseEvents(
        '[data-column-id="descricao"]',
        'complemento-descricao-67HGS',
        uuid,
        'sequencial',
        'data-l21_ordem',
        'data-pc01_complmater'
    );
}

function objetoDescFunction(uuid) {
    handleMouseEvents(
        '[data-column-id="objeto"]',
        'complemento-descricao-67HGS',
        uuid,
        'codigo',
        'data-l20_codigo',
        'data-l20_objeto_full'
    );
}

window.descricaoDescFunction = descricaoDescFunction;
window.objetoDescFunction = objetoDescFunction;
window.licitacoesDoubleClick = biddingPhaseServices.carregarDetalhesLicitacao;
window.finalizar = biddingPhaseServices.finalizar;
window.licitacoesItensRedirect = biddingPhaseServices.carregarDetalhesLicitacaoRedirect;
window.licitacoesItensLoad = biddingPhaseServices.carregarItensLicitacao;
window.licitacoesItensSituacoes = licitacoesItensSituacoes;
window.alterarSituacoesDosItens = alterarSituacoesDosItens;
window.limparLances = limparLances;
window.classificarLicitacoesItens = classificarLicitacoesItens;
window.readequarPropostaLotes = readequarPropostaLotes;
window.clickNaLinhaFunction = clickNaLinhaFunction;