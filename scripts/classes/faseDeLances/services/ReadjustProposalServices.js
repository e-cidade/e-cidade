import { FormatterUi } from '../ui/FormatterUi.js';
import { ReadjustProposalUi } from '../ui/ReadjustProposalUi.js';
import { ManipulationDom } from '../ui/ManipulationDom.js';
import { ReadjustProposalFetch } from '../communication/ReadjustProposalFetch.js';

export class ReadjustProposalServices {

    constructor() {
        this.readjustProposalFetch = new ReadjustProposalFetch();

        this.obterItensDaReadequecaoDeProposta = this.obterItensDaReadequecaoDeProposta.bind(this);
        this.verificarPropostaExistente = this.verificarPropostaExistente.bind(this);
        this.salvarProposta = this.salvarProposta.bind(this);
        this.deletarProposta = this.deletarProposta.bind(this);
        this.exportarItens = this.exportarItens.bind(this);
        this.importarItens = this.importarItens.bind(this);

        this.inicializar();
    }
  
    /**
     * Inicializa os eventos e formatações para os inputs de valores e marcas na interface da proposta de reajuste.
     * 
     * - Configura listeners para atualizar valores e realizar a contagem e soma dos inputs.
     * - Aplica formatação monetária aos campos de valor usando Cleave.js.
     * - Utiliza debounce para otimizar a contagem e soma, evitando execuções excessivas ao digitar.
     */
    inicializar() {
        let inputsValores = ReadjustProposalUi.getInputsValores();

        inputsValores.forEach(input => {
            const atualizarContagemESomaDebounced = this.debounce(() => {
                this.atualizarContagemESoma();
              }, 300);
              
            input.addEventListener('input', (event) => {
                this.atualizarValorTotal(event.target);
                atualizarContagemESomaDebounced();
            });

            new Cleave(input, {
                prefix: 'R$ ',
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                delimiter: '.',
                numeralDecimalMark: ',',
                numeralDecimalScale: 2
            });
        });

        let inputsMarcas = ReadjustProposalUi.getInputsMarcas();

        inputsMarcas.forEach(input => {
            const atualizarContagemESomaDebounced = this.debounce(() => {
                this.atualizarContagemESoma();
              }, 300);
              
            input.addEventListener('input', atualizarContagemESomaDebounced);
        });
        
        this.atualizarContagemESoma();
    }

    /**
     * Retorna uma função que atrasa a execução da função original até que um determinado tempo tenha se passado
     * desde a última vez que a função foi chamada. Isso ajuda a evitar execuções excessivas de funções que disparam
     * frequentemente, como manipuladores de eventos de input.
     * 
     * @param {Function} func - A função a ser debounced (atrasada).
     * @param {number} delay - O tempo de espera em milissegundos antes de executar a função.
     * @returns {Function} - Uma nova função que aplica o efeito debounce à função original.
     */
    debounce(func, delay) {
        let timeoutId;
        return function (...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func.apply(this, args), delay);
        };
    }

    /**
     * Atualiza o valor total de um lote na tabela com base na quantidade e no valor unitário informado.
     * 
     * - Obtém a linha (`tr`) correspondente ao input alterado.
     * - Extrai a quantidade do lote, garantindo que o valor seja numérico e formatado corretamente.
     * - Remove a máscara do input de valor unitário e converte para número.
     * - Calcula o valor total multiplicando quantidade pelo valor unitário.
     * - Atualiza o campo de valor total da linha com a nova quantia formatada.
     * 
     * @param {HTMLInputElement} input - O input de valor unitário que foi modificado.
     */
    atualizarValorTotal(input) {
        const tr = input.closest('tr');
        const quantidadeText = tr.querySelector('.quantidade')?.textContent.trim() || '0';
        const quantidade = parseFloat(quantidadeText.replace('.', '').replace(',', '.')) || 0;

        const valorUnitario = FormatterUi.cleanMaskCleave(input.value) || 0;
        const valorTotalCalculado = quantidade * valorUnitario;
    
        const tdVlrTotal = tr.querySelector('.valorTotal');

        if (tdVlrTotal) {
            tdVlrTotal.textContent = FormatterUi.formatCurrency(valorTotalCalculado);
        }
    }

    /**
     * Atualiza a contagem de itens, soma os valores unitários e controla a habilitação do botão de salvamento.
     * 
     * - Obtém a lista de itens da tabela e extrai os valores unitários.
     * - Verifica se há itens sem valor informado e exibe mensagens de alerta.
     * - Habilita ou desabilita o botão de salvar com base na existência de valores vazios.
     * - Calcula a soma dos valores unitários dos itens.
     * - Atualiza a interface com a quantidade de itens sem preenchimento e o valor restante disponível.
     */
    atualizarContagemESoma() {
        let tbodyItens = ReadjustProposalUi.getTbodyItens();

        const itens = Array.from(tbodyItens.querySelectorAll('tr')).map(tr => {
            const input = tr.querySelector('.vlrUnitario');
            return input ? this.itemProposta(input) : null;
        }).filter(Boolean);

        let mensagens = [];

        const ordensSemValorUnitario = itens
            .filter(item => FormatterUi.cleanMaskCleave(item.vlrUnitario) === 0)
            .map(item => item.ordem);

        if (ordensSemValorUnitario.length > 0) {
            mensagens.push(`Os valores dos itens de ordem ${ordensSemValorUnitario.join(', ')} est&atilde;o vazios.`);
        }

        let salvarBtnTooltip = ReadjustProposalUi.getSalvarBtnTooltip();
        salvarBtnTooltip.innerHTML = mensagens.join('<br>');

        const trSemPreenchimento = ordensSemValorUnitario.length;

        if (trSemPreenchimento == 0 && ordensSemValorUnitario.length == 0) {
            ManipulationDom.enableElement(ReadjustProposalUi.getSalvarBtn());
        } else {
            ManipulationDom.disableElement(ReadjustProposalUi.getSalvarBtn());
        }

        const somaValores = itens.reduce((soma, item) => soma + (item.vlrUnitario * item.quantidade), 0).toFixed(2);

        let lanceVencedorCleanMask = FormatterUi.cleanMaskCleave(lanceVencedor);
        const valorRestante = lanceVencedorCleanMask - somaValores;

        let contadorItens = ReadjustProposalUi.getContadorItens();
        contadorItens.textContent = trSemPreenchimento;

        let valorRestanteSpan = ReadjustProposalUi.getValorRestante();
        valorRestanteSpan.textContent = FormatterUi.formatCurrency(valorRestante);
    }

    /**
     * Extrai e retorna os dados de um lote da proposta com base no input informado.
     * 
     * - Obtém o identificador único do lote a partir do atributo `data-orcamitem` do input.
     * - Recupera a ordem do lote na tabela usando um `span` correspondente.
     * - Obtém a marca associada ao lote a partir de um campo de input.
     * - Remove a máscara do valor unitário do input para obter um número limpo.
     * - Retorna um objeto contendo os dados do lote da proposta.
     * 
     * @param {HTMLInputElement} input - O input do valor unitário do lote.
     * @returns {Object} - Um objeto contendo as informações do lote da proposta:
     *   - {string|null} ordem - Número da ordem do lote.
     *   - {string} orcamitem - Identificador único do lote no orçamento.
     *   - {number|null} vlrUnitario - Valor unitário do lote, sem máscara.
     *   - {string|null} marca - Marca do lote, se informada.
     */
    itemProposta(input) {
        let orcamitem = input.dataset.orcamitem;
        let ordem = document.querySelector(`span.ordem[data-orcamitem="${orcamitem}"]`)?.textContent?.trim() || null;
        let marca = document.querySelector(`input[name="marca"][data-orcamitem="${orcamitem}"]`)?.value || null;
        let quantidade = document.querySelector(`span.quantidade[data-orcamitem="${orcamitem}"]`)?.textContent?.trim() || null;
    
        let vlrUnitario = FormatterUi.cleanMaskCleave(input.value) || null;
        let quantidadeFloat = FormatterUi.cleanMaskCleave(quantidade) || null;
    
        return {
            ordem: ordem,
            orcamitem: orcamitem,
            vlrUnitario: vlrUnitario,
            quantidade: quantidadeFloat,
            vlrTotal: parseFloat((vlrUnitario * quantidadeFloat).toFixed(2)),
            marca: marca
        };
    }

    /**
     * Exibe um efeito de carregamento (skeleton) na tabela de itens da proposta.
     * 
     * - Obtém o elemento `<tbody>` da tabela de itens.
     * - Determina a quantidade de linhas para exibir o efeito skeleton.
     * - Remove qualquer conteúdo existente no `<tbody>`.
     * - Insere dinamicamente linhas esqueleto simulando o carregamento de dados.
     */
    mostrarSkeleton() {
        const tbody =  ReadjustProposalUi.getTbodyItens();
        const qtdLinhas = tbody.querySelectorAll("tr").length || 5;
        
        tbody.innerHTML = "";
    
        for (let i = 0; i < qtdLinhas; i++) {
            const skeletonRow = `
                <tr class="skeleton-row">
                    <td><div class="skeleton-6734N skeleton-text-6734N" style="width: 40px;"></div></td>
                    <td><div class="skeleton-6734N skeleton-text-6734N" style="width: 50px;"></div></td>
                    <td><div class="skeleton-6734N skeleton-text-6734N" style="width: 120px;"></div></td>
                    <td><div class="skeleton-6734N skeleton-text-6734N" style="width: 120px;"></div></td>
                    <td><div class="skeleton-6734N skeleton-text-6734N" style="width: 80px;"></div></td>
                    <td><div class="skeleton-6734N skeleton-text-6734N" style="width: 100px;"></div></td>
                    <td><div class="skeleton-6734N skeleton-text-6734N" style="width: 100px;"></div></td>
                    <td><div class="skeleton-6734N skeleton-text-6734N" style="width: 120px;"></div></td>
                    <td><div class="skeleton-6734N skeleton-text-6734N" style="width: 120px;"></div></td>
                    <td><div class="skeleton-6734N skeleton-text-6734N" style="width: 120px;"></div></td>
                </tr>
            `;
            tbody.insertAdjacentHTML("beforeend", skeletonRow);
        }
    }

    /**
     * Atualiza a tabela com os dados fornecidos, preenchendo as linhas da tabela com informações dinâmicas.
     * 
     * - Limpa o conteúdo atual do `<tbody>` da tabela.
     * - Para cada lote nos dados fornecidos, cria uma nova linha com as informações de ordem, código do material, descrição,
     *   unidade, quantidade, valor unitário, valor total e marca.
     * - Adiciona as novas linhas ao corpo da tabela.
     * 
     * @param {Array} dados - Um array de objetos contendo os dados que serão usados para preencher a tabela.
     */
    atualizarTabela(dados) {
        const tbody =  ReadjustProposalUi.getTbodyItens();
        tbody.innerHTML = "";
    
        dados.forEach((item, index) => {
            const row = `
                <tr>
                    <td><span class="ordem" data-orcamitem="${item.pc22_orcamitem}">${item.l21_ordem}</span></td>
                    <td>${item.pc01_codmater}</td>
                    <td>${item.pc01_descrmater}</td>
                    <td>${item.l04_descricao}</td>
                    <td>${item.unidade}</td>
                    <td><span class="quantidade" data-orcamitem="${item.pc22_orcamitem}">${item.quantidade}</td>
                    <td>${item.percentual ?? '-'}</td>
                    <td>
                        <input type="text" name="vlrUnitario" id="vlrUnitario${index}" 
                            class="vlrUnitario" data-orcamitem="${item.pc22_orcamitem}" 
                            placeholder="R$ 0,00" value="${item.vlr_unitario ?? ''}" 
                            style="border-radius: 0.5rem; border: 1px solid gray; padding: 5px; text-align: center;">
                    </td>
                    <td class="valorTotal">${item.vlr_total}</td>
                    <td>
                        <input type="text" name="marca" id="marca${index}" 
                            class="marca" data-orcamitem="${item.pc22_orcamitem}" 
                            value="${item.marca ?? ''}" 
                            style="border-radius: 0.5rem; border: 1px solid gray; padding: 5px; text-align: center;">
                    </td>
                </tr>
            `;
    
            tbody.insertAdjacentHTML("beforeend", row);
        });
    }

    /**
     * Obtém os itens da readequação de proposta e atualiza a tabela com esses dados.
     * 
     * - Exibe um efeito de carregamento (skeleton) enquanto os dados estão sendo recuperados.
     * - Realiza uma requisição assíncrona para obter os itens da proposta.
     * - Verifica o status da resposta para garantir que os dados foram recebidos corretamente.
     * - Caso os dados sejam válidos, atualiza a tabela e inicializa os eventos necessários.
     * 
     * @async
     */
    async obterItensDaReadequecaoDeProposta() {
        this.mostrarSkeleton();

        const response = await this.readjustProposalFetch.getItemsFromProposalRequest(routeObterItensDaReadequecaoDeProposta);
        const responseData = await response.json();
        const checkStatus = this.checkStatusResponse(response.status, responseData);

        if (checkStatus) {
            this.atualizarTabela(responseData.itensProposta);
            this.inicializar();
        }
    }

    /**
     * Verifica se uma proposta já existe com base nos itens fornecidos.
     * 
     * - Obtém os valores dos inputs na interface de usuário.
     * - Para cada input, coleta os dados necessários sobre os itens da proposta.
     * - Envia uma requisição assíncrona para verificar se a proposta já existe no sistema.
     * - Verifica o status da resposta para determinar se a proposta existe ou não.
     * 
     * @async
     */
    async verificarPropostaExistente() {
        let inputsValores = ReadjustProposalUi.getInputsValores();

        const itens = Array.from(inputsValores).map(input => this.itemProposta(input));

        const data = {
            itens: itens
        };

        const response = await this.readjustProposalFetch.checkExistingProposal(routeVerificarPropostaExistente, data);
        const responseData = await response.json();
        this.checkStatusResponse(response.status, responseData);
    }

    /**
     * Salva a proposta com os itens fornecidos e atualiza a interface de usuário.
     * 
     * - Coleta os valores dos inputs na interface de usuário para formar os itens da proposta.
     * - Cria um objeto de dados com informações sobre a licitação, lote, fornecedor e itens.
     * - Envia uma requisição assíncrona para salvar a proposta no sistema.
     * - Verifica o status da resposta para confirmar o sucesso da operação.
     * - Se a proposta for salva com sucesso, exibe uma mensagem de sucesso, habilita o botão de limpar e obtém os itens da readequação.
     * 
     * @async
     */
    async salvarProposta() {
        let inputsValores = ReadjustProposalUi.getInputsValores();

        const itens = Array.from(inputsValores).map(input => this.itemProposta(input));

        const data = {
            licitacao: ReadjustProposalUi.getLicitacao(),
            lote: ReadjustProposalUi.getLote(),
            orcamforne: ReadjustProposalUi.getFornecedorCodigo(),
            itens: itens
        };

        const response = await this.readjustProposalFetch.saveProposal(routeSalvarProposta, data);
        const responseData = await response.json();
        const checkStatus = this.checkStatusResponse(response.status, responseData);

        if (checkStatus) {
            createToast(responseData.message, 'success', 4000);
            limparBtn.style.display = "block";
            this.obterItensDaReadequecaoDeProposta();
        }
    }

    /**
     * Deleta a proposta com base nos itens fornecidos e atualiza a interface de usuário.
     * 
     * - Coleta os valores dos inputs na interface de usuário para formar os itens da proposta.
     * - Cria um objeto de dados com os itens a serem deletados.
     * - Envia uma requisição assíncrona para deletar a proposta no sistema.
     * - Verifica o status da resposta para confirmar o sucesso da operação.
     * - Se a proposta for deletada com sucesso, esconde o botão de limpar, exibe uma mensagem de sucesso e atualiza a lista de itens.
     * 
     * @async
     */
    async deletarProposta() {
        let inputsValores = ReadjustProposalUi.getInputsValores();

        const itens = Array.from(inputsValores).map(input => this.itemProposta(input));

        const data = {
            lote: ReadjustProposalUi.getLote(),
            itens: itens
        };

        const response = await this.readjustProposalFetch.deleteProposal(routeDeletarProposta, data);
        const responseData = await response.json();
        const checkStatus = this.checkStatusResponse(response.status, responseData);

        if (checkStatus) {
            limparBtn.style.display = "none";
            createToast(responseData.message, 'success', 4000);
            this.obterItensDaReadequecaoDeProposta();
        }
    }

    /**
     * Exporta os itens da proposta com base nas informações fornecidas e atualiza a interface de usuário.
     * 
     * - Coleta os dados da licitação, fornecedor, lote e informações adicionais para exportar os itens.
     * - Envia uma requisição assíncrona para exportar os itens da proposta.
     * - Verifica o status da resposta para confirmar o sucesso da operação.
     * - Se a exportação for bem-sucedida, exibe uma mensagem de sucesso e atualiza a lista de itens da readequação da proposta.
     * 
     * @async
     */
    async exportarItens() {
        let licitacao = ReadjustProposalUi.getLicitacao();
        let fornecedor = ReadjustProposalUi.getFornecedorCodigo();
        let lote = ReadjustProposalUi.getLote();
        let descrforne = ReadjustProposalUi.getRazaoSocial();
        let cnpj = ReadjustProposalUi.getCnpj();

        let query = `licitacao=${licitacao}&fornecedor=${fornecedor}&lote=${lote}&descrforne=${descrforne}&cnpj=${cnpj}`;
        window.open(`${routeExportarItens}lic_fasedelance006.php?${query}`);
    }

    /**
     * Importa itens de um arquivo XLSX enviado e exibe mensagens de sucesso ou erro.
     * 
     * - Obtém o arquivo selecionado pelo usuário e cria um FormData para envio.
     * - Envia o arquivo via requisição POST assíncrona ao servidor.
     * - Exibe mensagem de sucesso ou erro conforme a resposta do servidor.
     * - Em caso de erro na requisição, exibe alerta genérico.
     * 
     * @async
     */
    async importarItens() {
        const fileInput = document.getElementById('xlsxFile');
        const file = fileInput.files[0];

        if (!file) {
            alert('Selecione um arquivo XLSX');
            return;
        }

        const formData = new FormData();
        formData.append('xlsx_file', file);

        fetch(routeImportarItens, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Arquivo enviado com sucesso!');
            } else {
                alert('Erro no upload: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro no upload');
        });
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