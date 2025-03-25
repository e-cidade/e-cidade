export class ReadjustProposalFetch {

    // Função assíncrona para realizar requisições HTTP para uma API
    // Recebe o endpoint (caminho da API), o método HTTP (padrão é 'GET') e um corpo opcional para a requisição.
    async fetchData(endpoint, method = 'GET', body = null) {

        try {

            // Configuração inicial das opções para a requisição HTTP
            const options = {
                method, // Define o método HTTP da requisição (GET, POST, PUT, DELETE, etc.)
                headers: {
                    'Content-Type': 'application/json', // Define o cabeçalho Content-Type como JSON
                },
            };

            // Caso um corpo (body) tenha sido passado, ele é convertido para uma string JSON
            if (body) options.body = JSON.stringify(body);

            // Faz a requisição HTTP utilizando a API `fetch`
            // Concatena a base URL (armazenada em `this.apiBaseUrl`) com o endpoint passado
            const response = await fetch(`${endpoint}`, options);

            // Retorna os dados da resposta no formato JSON
            return response;

        } catch (error) {

            // Caso ocorra algum erro durante a execução, ele é capturado aqui
            // Exibe o erro no console com informações adicionais sobre o endpoint
            console.error(`Erro em ${endpoint}:`, error);
            // Relança o erro para que possa ser tratado por quem chamou a função
            throw error;

        }
    }

    async getItemsFromProposalRequest(route) {
        return this.fetchData(route, 'GET');
    }

    async checkExistingProposal(route, data) {
        return this.fetchData(route, 'POST', data);
    }

    async saveProposal(route, data) {
        return this.fetchData(route, 'POST', data);
    }

    async deleteProposal(route, data) {
        return this.fetchData(route, 'POST', data);
    }
}