// choices-translations.js
const choicesTranslations = {
    loadingText: 'Carregando...',
    noResultsText: 'Nenhum resultado encontrado',
    noChoicesText: 'Nenhuma opção disponível',
    itemSelectText: 'Pressione para selecionar',
    uniqueItemText: 'Apenas valores únicos podem ser adicionados',
    customAddItemText: 'Apenas valores que atendem a condições específicas podem ser adicionados',
    addItemText: (value) => `Pressione Enter para adicionar <b>"${value}"</b>`,
    maxItemText: (maxItemCount) => `Somente ${maxItemCount} valores podem ser adicionados`,
    searchEnabled: true,
    itemSelectText: '',   // Texto mostrado ao selecionar um item (vazio neste caso)
    shouldSort: false     // Mantém a ordem original dos elementos
};
