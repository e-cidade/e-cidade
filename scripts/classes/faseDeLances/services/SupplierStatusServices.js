import { AuctionServices } from '../services/AuctionServices.js';
import { GetValuesUi } from '../ui/GetValuesUi.js';

export class SupplierStatusServices {

    constructor() {}

    /**
     * Função `supplierStatus` que define os valores para campos relacionados ao fornecedor
     * com base em uma linha de tabela (`tr`).
     * 
     * A função recebe o ID da linha de tabela (`tr`), obtém informações relacionadas ao fornecedor
     * como `data-id`, `cnpj` e `name`. Esses dados são então usados para preencher campos
     * de entrada correspondentes no formulário/modal.
     * Após definir os valores, o modal de status do fornecedor é aberto usando a função `openModal`.
     * 
     * @param {string} tr - O ID da linha da tabela (`tr`) contendo as informações do fornecedor.
     */
    supplierStatus(tr) {
        let trElement = document.getElementById(tr);
        let dataId = trElement.querySelector('#cnpj').getAttribute('data-id');
        let cnpj = trElement.querySelector('#cnpj').textContent.trim();
        let name = trElement.querySelector('#name').textContent.trim();

        document.getElementById('situacaoItemCodigo').value = document.getElementById('codigoItem').value;
        document.getElementById('situacaoItemDesc').value = document.getElementById('descItem').value;

        document.getElementById('situacaoFornecedorCodigo').value = dataId;
        document.getElementById('situacaoFornecedorRazaoSocial').value = name;
        document.getElementById('situacaoFornecedorCnpj').value = cnpj;

        openModal('situacaoFornecedor');
    }

    /**
     * Função `changeSupplierStatus` que envia uma requisição `POST` para alterar o status de um fornecedor.
     * 
     * A função coleta os dados necessários para alterar o status do fornecedor a partir dos campos do formulário.
     * Em seguida, ela envia esses dados via `fetch` para o servidor. Caso a resposta da requisição não seja bem-sucedida,
     * a função processa os erros retornados e exibe mensagens de erro apropriadas. Se a requisição for bem-sucedida,
     * a função exibe uma mensagem de sucesso e atualiza a lista de fornecedores.
     */
    changeSupplierStatus() {
        let typeJulg = GetValuesUi.getTypeJulg();
        let itemCodigo = document.getElementById('situacaoItemCodigo').value;
        let fornecedorCodigo = document.getElementById('situacaoFornecedorCodigo').value;
        let fornecedorCategoria = document.getElementById('categoriasstatusfornecedor').value;
        let fornecedorMotivo = document.getElementById('motivoStatusFornecedor').value;

        const data = {
            tipoJulg: typeJulg,
            itemCodigo: itemCodigo,
            fornecedorCodigo: fornecedorCodigo,
            fornecedorCategoria: fornecedorCategoria,
            fornecedorMotivo: fornecedorMotivo
        };

        fetch(routeAlterarStatusFornecedor, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 422) {
                    return response.json().then(errorData => {
                        if (errorData) {
                            createToast(`${errorData.message}`, 'danger', 9000);
                        } 

                        if (errorData.errors.ids) {
                            errorData.errors.ids.forEach(errorMessage => {
                                createToast(`${errorMessage}`, 'warning', 9000);
                            });
                        } 
                        
                        if (errorData.errors.categorias) {
                            errorData.errors.categorias.forEach(errorMessage => {
                                createToast(`${errorMessage}`, 'warning', 9000);
                            });
                        } 
                        
                        if (errorData.errors.motivo) {
                            errorData.errors.motivo.forEach(errorMessage => {
                                createToast(`${errorMessage}`, 'warning', 9000);
                            });
                        }
                    });
                } else if (response.status === 400 || response.status === 500) {
                    return response.json().then(errorData => {
                        if (errorData.error) {
                            createToast(`${errorData.error}`, 'danger', 9000);
                        }
                    });
                }
            }
            return response.json();
        })
        .then(data => {
            closeModal('situacaoFornecedor');
            createToast(data.message, 'success', 4000);
            let auctionServices = new AuctionServices();
            auctionServices.fetchSuppliers(true);
        })
        .catch(error => {
            console.error('Erro ao salvar os dados:', error);
        });
    }
}