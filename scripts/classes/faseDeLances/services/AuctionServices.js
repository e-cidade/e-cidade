import { FormatterUi } from '../ui/FormatterUi.js';
import { GetValuesUi } from '../ui/GetValuesUi.js';
import { ManipulationDom } from '../ui/ManipulationDom.js';
import { JudgmentFetch } from '../communication/JudgmentFetch.js';
import { SupplierStatusServices } from './SupplierStatusServices.js';

export class AuctionServices {

    constructor() {
        this.tableBody = GetValuesUi.getTableBody();
        this.inputFornecedor = GetValuesUi.getInputFornecedor();
        this.inputDifMinimaLance = GetValuesUi.getInputDifMinimaLance();
        this.subjectValue = GetValuesUi.getSubjectValue();
        this.subjectValueDiv = GetValuesUi.getSubjectValueDiv();
        this.buttons = GetValuesUi.getButtons();

        this.judgmentFetch = new JudgmentFetch();
        this.supplierStatus = new SupplierStatusServices();
    }

    /**
     * Busca dados de fornecedores e atualiza a tabela e os controles na interface.
     * 
     * Essa função realiza uma requisição HTTP para obter os fornecedores e manipula os dados recebidos.
     * Ela também lida com possíveis erros e alterna o estado de carregamento.
     *
     * @param {boolean} ignoreCache - Indica se o cache deve ser ignorado na requisição. 
     *                                O padrão é `false`, ou seja, utiliza o cache.
     */
    async fetchSuppliers(ignoreCache = false) {
        try {
            let tipojulg = GetValuesUi.getTypeJulg();
            let response = await this.judgmentFetch.fetchSuppliers(routeObterFornecedoresProposta, ignoreCache, tipojulg);
            const responseData = await response.json();
            this.populateTable(responseData.data);
            this.highlightRow(responseData.nextBid, diferencaMinimaDeLance);
            ManipulationDom.toggleLoadingState(false);

        } catch (error) {
            console.error(`${error}`);
        }
    }

    /**
     * Preenche a tabela com os dados fornecidos e atualiza o estado dos botões.
     * 
     * Essa função cria linhas na tabela com base nos dados recebidos e aplica estilos, eventos e classes 
     * apropriados de acordo com os valores de cada linha. Em seguida, atualiza o estado dos botões com base
     * em condições calculadas a partir dos dados.
     *
     * @param {Array} data - Um array de objetos representando os dados para preencher a tabela. Cada objeto contém:
     *   - {number} pc21_orcamforne: Identificador único da linha.
     *   - {number} l34_julgfornestatus: Status usado para definir classes e comportamentos.
     *   - {number|null} l32_lance: Valor do lance (pode ser nulo).
     *   - {number} l224_vlrun: Valor alternativo ao lance.
     *   - {string} z01_cgccpf: CNPJ ou CPF do fornecedor.
     *   - {string} z01_nome: Nome do fornecedor.
     */
    populateTable(data) {
        this.tableBody.innerHTML = "";

        data.forEach(row => {
            const tr = document.createElement('tr');
            tr.id = `row-${row.pc21_orcamforne}`;

            if (row.l34_julgfornestatus !== 2) {
                tr.ondblclick = () => fornecedorSituacoes(`row-${row.pc21_orcamforne}`);
                tr.classList.add('pointer');
            }

            const statusClass = GetValuesUi.getStatusClass(row.l34_julgfornestatus) || 'default-class'; // fallback to a default class if empty or undefined
            tr.classList.add(statusClass);

            const valueTable = row.l32_lance !== null ? row.l32_lance : row.l224_vlrun;

            tr.innerHTML = `
                <td id="cnpj" data-id="${row.pc21_orcamforne}">${FormatterUi.formatCNPJ(row.z01_cgccpf)}</td>
                <td id="name">${row.z01_nome}</td>
                <td id="value">${FormatterUi.formatCurrency(valueTable)}</td>
            `;

            this.tableBody.appendChild(tr);
        });

        let allStatus3 = data.every(item => item.l34_julgfornestatus === 3);
        let allStatus2 = data.every(item => item.l34_julgfornestatus === 2);
        let anyStatus2 = data.some(item => item.l34_julgfornestatus === 3);
        let anyLanceNotNull = data.some(item => item.l32_lance !== null);

        ManipulationDom.updateButtonStates({ allStatus2, allStatus3, anyStatus2, anyLanceNotNull });
    }

    /**
     * Destaque uma linha na tabela e atualize os controles relacionados ao próximo lance.
     * 
     * Essa função adiciona destaque visual a uma linha da tabela baseada no identificador `nextBid` 
     * e ajusta os controles de entrada e valores relacionados ao próximo lance. 
     * Ela também valida condições para habilitar ou desabilitar funções interativas.
     *
     * @param {number|string} nextBid - O identificador da próxima linha a ser destacada.
     * @param {number} diferencaMinimaDeLance - A diferença mínima necessária para o próximo lance.
     */
    highlightRow(nextBid, diferencaMinimaDeLance) {
        Array.from(this.tableBody.rows).forEach(row => {
            row.classList.remove('highlight-AEF1E83');
        });

        let targetRow = document.querySelector(`#row-${nextBid}`);

        if (targetRow) {
            targetRow.classList.add('highlight-AEF1E83');

            let razaoSocialCell = targetRow.cells[1];
            let proximoLanceMaximo = this.obterLanceMaximo(diferencaMinimaDeLance);
            let controleDeLances = proximoLanceMaximo - parseFloat(diferencaMinimaDeLance);

            if (controleDeLances <= 0) {
                ManipulationDom.controlBidsLessThanZero();
                this.inputDifMinimaLance.value = "R$ 0,00";
                this.subjectValue.innerHTML = "R$ 0,00";
                this.subjectValueDiv.style.visibility = 'hidden';
            } else {
                ManipulationDom.enableBids();
                this.inputDifMinimaLance.value = `R$ ${proximoLanceMaximo.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
                this.subjectValue.innerHTML = `R$ ${proximoLanceMaximo.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
                this.subjectValueDiv.style.visibility = 'visible';
            }

            this.inputFornecedor.value = razaoSocialCell.textContent.trim();
            
        } else {
            if (nextBid === null) {
                ManipulationDom.controlBidsLessThanZero();
                this.inputDifMinimaLance.value = "R$ 0,00";
                this.subjectValue.innerHTML = "R$ 0,00";
                this.subjectValueDiv.style.visibility = 'hidden';
            } else {
                console.error(`Row with id row-${nextBid} not found.`);
            }
        }
    }

    /**
     * Calcula o próximo lance máximo com base no menor valor atual da tabela.
     *
     * A função encontra o menor valor nas células da tabela de fornecedores, 
     * subtrai a diferença mínima configurada (`l13_difminlance`) e retorna o próximo lance.
     * 
     * @return float O próximo valor de lance.
     */
    obterLanceMaximo() {
        const valueCells = document.querySelectorAll('#dynamic-table-supplier td#value');
        const values = Array.from(valueCells).map(cell => {
            return parseFloat(cell.textContent.replace('R$', '').replace('.', '').replace(',', '.').trim());
        });
        const minValue = Math.min(...values);
        const difminlance = parseFloat(diferencaMinimaDeLance);
        let proxLance = minValue - difminlance;
        return proxLance;
    }

    subjectValueComply() {
        let bidInput = GetValuesUi.getBidInput();
        let inputDifMinimaLance = GetValuesUi.getInputDifMinimaLance();
        bidInput.value = inputDifMinimaLance.value.replace("R$ ", "");
        bidInput.focus();
        adjustBidInputWidth();
    }
}