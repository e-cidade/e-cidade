/**
 * Define as opções de idioma para o grid, customizando textos de interface.
 * 
 * Esta função retorna um objeto contendo as mensagens traduzidas ou adaptadas
 * para o idioma desejado. As traduções abrangem:
 * - Placeholders e mensagens da barra de busca.
 * - Mensagens de ordenação, incluindo ordem crescente e decrescente.
 * - Mensagens de paginação, como navegação entre páginas e resultados exibidos.
 * - Mensagens para estados de carregamento, erro ou ausência de registros.
 * 
 * @returns {object} Objeto com as configurações de idioma para o grid.
 */
function getLanguageOptions() 
{
    return {
        search: { placeholder: 'Digite uma palavra-chave...' },
        sort: { sortAsc: 'Ordenar coluna em ordem crescente', sortDesc: 'Ordenar coluna em ordem decrescente' },
        pagination: {
            previous: 'Anterior', next: 'PrÃ³ximo',
            navigate: (page, pages) => `PÃ¡gina ${page} de ${pages}`,
            page: (page) => `PÃ¡gina ${page}`, showing: 'Exibindo',
            of: 'de', to: 'a', results: 'resultados',
        },
        loading: 'Carregando...', noRecordsFound: 'Nenhum registro correspondente encontrado',
        error: 'Ocorreu um erro ao buscar os dados',
    };
}