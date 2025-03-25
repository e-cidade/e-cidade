export class BiddingPhaseFetch {
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

    async getLicitacaoData(filter) {
        return this.fetchData(routeDatagridGetLiclicita + `?${filter}`);
    }

    async getLicitacaoItens(filter) {
        return this.fetchData(routeDatagridGetLiclicitaItens + `?${filter}`);
    }

    async getLicitacaoLotes(filter) {
        return this.fetchData(routeDatagridGetLiclicitaLotes + `?${filter}`);
    }

    async alterarStatusItens(data) {
        return this.fetchData(routeAlterarStatusItem, 'POST', data);
    }

    async limparLances(data) {
        return this.fetchData(routeLimparLances, 'POST', data);
    }

    async obterFornecedoresEPropostas(codigoLicitacao, codigoLicitacaoItem, ignorarCache) {

        const routeObterFornecedoresProposta = routeFornecedoresPropostasTemp
            .replace(':codigoLicitacao', codigoLicitacao)
            .replace(':codigoLicitacaoItem', codigoLicitacaoItem);

        return this.fetchData(`${routeObterFornecedoresProposta}/${codigoLicitacao}/${codigoLicitacaoItem}?ignorarCache=${ignorarCache}`);
    }

    async finish(route, data) {
        return this.fetchData(route, 'POST', data);
    }
}