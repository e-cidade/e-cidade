export class FormatterUi {
    /**
     * Formata um valor numérico para o formato de moeda brasileira (BRL).
     * @param {number} value - O valor numérico a ser formatado.
     * @returns {string} - String formatada como moeda no formato BRL.
     */
    static formatCurrency(value) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(value);
    }

    /**
     * Formata uma string de CNPJ para o padrão brasileiro: XX.XXX.XXX/XXXX-XX.
     * @param {string} cnpj - String contendo o CNPJ numérico.
     * @returns {string} - String formatada no padrão CNPJ.
     */
    static formatCNPJ(cnpj) {
        const justNumbers = String(cnpj).replace(/\D/g, ''); // Converte para string se não for
        return justNumbers.replace(
            /^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/,
            '$1.$2.$3/$4-$5'
        );
    }

    /**
     * Função estática `setLoadingState` que adiciona ou remove a classe `loading`
     * de um conjunto de elementos de entrada (`inputs`) com base no estado de carregamento.
     * 
     * A função percorre cada elemento do array `inputs` e, dependendo do valor de `isLoading`, 
     * adiciona ou remove a classe `loading` usando o método `classList.toggle`.
     * - Se `isLoading` for `true`, a classe `loading` será adicionada a cada input.
     * - Se `isLoading` for `false`, a classe `loading` será removida de cada input.
     * 
     * @param {HTMLElement[]} inputs - Array de elementos de entrada nos quais a classe `loading` será aplicada.
     * @param {boolean} isLoading - Indica se o estado de carregamento está ativo (`true`) ou inativo (`false`).
     */
    static setLoadingState(inputs, isLoading) {
        inputs.forEach(input => {
            input.classList.toggle('loading', isLoading);
        });
    }

    /**
     * Função estática `formatDate` que formata uma data no formato ISO (`YYYY-MM-DD`)
     * para o formato `DD/MM/YYYY`.
     * 
     * A função divide a string da data ISO usando o método `split('-')`, converte os componentes
     * (ano, mês e dia) para números com `map(Number)` e então reconstrói a data no formato desejado.
     * Para garantir que o dia e o mês tenham sempre dois dígitos, é utilizado o método `padStart`.
     * 
     * @param {string} dataISO - A data no formato ISO (`YYYY-MM-DD`) que será formatada.
     * @returns {string} - Retorna a data formatada no formato `DD/MM/YYYY`.
     */
    static formatDate(dataISO) {
        const [ano, mes, dia] = dataISO.split('-').map(Number);
        return `${String(dia).padStart(2, '0')}/${String(mes).padStart(2, '0')}/${ano}`;
    }


    static cleanMaskCleave(valor) {
        if (!valor) return 0;
        return parseFloat(String(valor).replace(/R\$\s?|\./g, '').replace(',', '.')) || 0;
    }
}