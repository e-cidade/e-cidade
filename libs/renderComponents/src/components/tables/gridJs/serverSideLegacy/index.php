<?php

    use App\Helpers\StringHelper;

    // Gera um identificador único (UUID) hexadecimal de 32 caracteres.
    $uuid = bin2hex(random_bytes(16));

    // Define o ID do componente, utilizando um valor padrão caso não esteja definido nas variáveis do componente.
    $id = isset($variaveisComponents['id']) 
        ? $variaveisComponents['id'] 
        : 'server-side-grid';

    // Define $multiSelect com o valor de 'multiSelect' ou false se não existir.
    $multiSelect = isset($variaveisComponents['multiSelect']) 
        ? $variaveisComponents['multiSelect'] 
        : false;
    
    // Define $rowClick com o valor de 'multiSelect' ou false (possível erro lógico).
    $rowClick = isset($variaveisComponents['rowClick']) 
        ? $variaveisComponents['rowClick'] 
        : false;

    // Define $rowClickFunction com o valor de 'rowClickFunction' ou false se não existir.
    $rowClickFunction = isset($variaveisComponents['rowClickFunction']) 
        ? $variaveisComponents['rowClickFunction'] 
        : false;
    
    // Define $rowDoubleClick com o valor de 'multiSelect' ou false (possível erro lógico).
    $rowDoubleClick = isset($variaveisComponents['rowDoubleClick']) 
        ? $variaveisComponents['rowDoubleClick'] 
        : false;

    $loadFunction = isset($variaveisComponents['loadFunction']) 
        ? $variaveisComponents['loadFunction'] 
        : null;

    // Define $rowDoubleClickFunction com o valor de 'rowDoubleClickFunction' ou false se não existir.
    $rowDoubleClickFunction = isset($variaveisComponents['rowDoubleClickFunction']) 
        ? $variaveisComponents['rowDoubleClickFunction'] 
        : false;

    // Define a classe CSS do componente, com um valor padrão caso não esteja definido.
    $class = isset($variaveisComponents['class']) 
        ? $variaveisComponents['class'] 
        : 'grid-container-3b7d7b0517f09';

    // Define as colunas do grid. Caso não haja definição, utiliza um array padrão com três elementos "#".
    $columns = isset($variaveisComponents['columns']) 
        ? $variaveisComponents['columns'] 
        : ['#', '#', '#'];

    // Define a URL da API que será usada pelo grid, com valor padrão vazio.
    $apiUrl = isset($variaveisComponents['apiUrl']) 
        ? $variaveisComponents['apiUrl'] 
        : '';

    // Define o número de registros por página no grid. O padrão é 10.
    $perPage = isset($variaveisComponents['perPage']) 
        ? $variaveisComponents['perPage'] 
        : 10;

    // Indica se a funcionalidade de busca está habilitada. O padrão é true (desabilitado).
    $search = isset($variaveisComponents['search']) 
        ? $variaveisComponents['search'] 
        : false;

    // Indica se a funcionalidade de ordenação está habilitada. O padrão é true (desabilitado).
    $sort = isset($variaveisComponents['sort']) 
        ? $variaveisComponents['sort'] 
        : false;

    // Indica se a funcionalidade de fixedHeader está habilitada. O padrão é true (desabilitado).
    $fixedHeader = isset($variaveisComponents['fixedHeader']) 
        ? $variaveisComponents['fixedHeader'] 
        : false;

    // Sanitiza o valor do ID, transformando-o para minúsculas e removendo caracteres não alfanuméricos.
    $idSanitize = strtolower(preg_replace('/[^a-z0-9]/', '', $id));

    // Extrai os rótulos ("label") das colunas, caso existam. Caso contrário, resultará em um array vazio.
    $labels = array_column($columns, 'label');

    // Extrai os nomes das colunas, caso existam. Caso contrário, resultará em um array vazio.
    $columnsNames = array_column($columns, 'name');

    // Define a mensagem exibida quando a tabela está vazia na primeira carga. O padrão é 'Mensagem de teste'.
    $emptyTableFirstLoadMessage = isset($variaveisComponents['emptyTableFirstLoadMessage']) 
        ? $variaveisComponents['emptyTableFirstLoadMessage'] 
        : 'Mensagem de teste';

    // Inclui o arquivos CSS
    includeOnceAsset('css', '/libs/renderComponents/src/components/tables/gridJs/serverSideLegacy/serverSide.css');
    includeOnceAsset('css', '/libs/renderComponents/src/components/tables/gridJs/mermaid.min.css');
    includeOnceAsset('css', '/libs/renderComponents/src/components/icons/cursor-arrow-rays/cursor-arrow-rays.css');
    includeOnceAsset('css', '/libs/renderComponents/src/components/icons/cursor-arrow-ripple/cursor-arrow-ripple.css');
    
    // Inclui o arquivos JavaScript
    includeOnceAsset('js', '/libs/renderComponents/src/components/tables/gridJs/serverSideLegacy/serverSide.js');
    includeOnceAsset('js', '/libs/renderComponents/src/components/tables/gridJs/gridjs.umd.js');
?>

<div id="gridjsContent<?=$uuid?>" class="<?=$class?> <?=(empty($apiUrl))?'hidden-gridjs-component':''?>">
    <div id="contentBox-0a0a" class="content-box-gridjs-component">

        <!-- Header da tabela -->
        <div class="row-gridjs-component">

            <!-- Barra de pesquisa -->
            <div class="search-input-container-gridjs-component">
                <?php if($search): ?>
                    <svg class="search-icon-gridjs-component" width="20" height="20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                    </svg>

                    <input id="searchInput<?=$uuid?>" type="text" class="search-input-gridjs-component" placeholder="Pesquisar em todas as colunas...">
                <?php endif; ?>


            </div>

            <!-- Botão de configurações da tabela -->
            <div style="display: flex;">

                <div style="display: flex; align-content: center; align-items: center; justify-content: center; margin-right: 20px;">
                    <?php if($rowClick): ?>
                        <span class="tooltip-8653X">
                            <icon id="iconRowClick" class="icon cursor-arrow-rays"></icon>
                            <span class="tooltip-text-8653X">
                                <?= StringHelper::toUtf8('Esta tabela permite a execução de ações ao clicar em suas linhas.') ?>
                            </span>
                        </span>
                    <?php endif; ?>

                    <?php if($rowDoubleClick): ?>
                        <span class="tooltip-8653X">
                            <icon id="iconRowDoubleClick" class="icon cursor-arrow-ripple"></icon>
                            <span class="tooltip-text-8653X">
                                <?= StringHelper::toUtf8('Esta tabela permite a execução de ações ao dar um duplo clique em suas linhas.') ?>
                            </span>
                        </span>
                    <?php endif; ?>
                </div>
                
                <?php if(!empty($columnsNames)): ?>
                    <!-- Botão de configurações -->
                    <button id="dropdownToggleButton<?=$uuid?>" class="dropdown-settings-button-gridjs-component" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                        </svg>

                        <?= StringHelper::toUtf8('Configurações') ?>
                    </button>
                    
                    <!-- Menu dropdown -->
                    <div id="dropdownToggle<?=$uuid?>" class="dropdown-settings-menu-gridjs-component hidden-gridjs-component">

                        <div class="clear-filter-gridjs-component" onclick="cleanFilters<?=$uuid?>()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 1.664 1.664M21 21l-1.5-1.5m-5.485-1.242L12 17.25 4.5 21V8.742m.164-4.078a2.15 2.15 0 0 1 1.743-1.342 48.507 48.507 0 0 1 11.186 0c1.1.128 1.907 1.077 1.907 2.185V19.5M4.664 4.664 19.5 19.5" />
                            </svg>
                            <span class="tooltip-gridjs-component">Limpar filtros</span>
                        </div>

                        <!-- Pesquisa por colunas -->
                        <div class="content-search-column-gridjs-component">
                            <div class="label-search-column-gridjs-component">
                                <span class="span-label-search-column-gridjs-component">
                                    Pesquisa por colunas:
                                </span>
                                
                                <div id="warningLabelColumn<?=$uuid?>" class="warning-label-search-column-gridjs-component hidden-gridjs-component">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                    </svg>
                                    Selecione alguma coluna para a pesquisa
                                </div>

                                <div id="warningLabelSearch<?=$uuid?>" class="warning-label-search-input-gridjs-component hidden-gridjs-component">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                    </svg>
                                    <?= StringHelper::toUtf8('Busque um termo relacionado à coluna selecionada.') ?>
                                </div>
                            </div>

                            <div class="sub-content-search-column-gridjs-component">
                                <select class="select-column-search-gridjs-component" id="selectColumnSearch<?=$uuid?>">
                                    <option value="">Selecione</option>

                                    <?php foreach ($columns as $i => $column): ?>
                                        <option value="<?=$column['name']?>">
                                            <?=$column['label']?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <div class="search-column-input-container-gridjs-component">
                                    <svg class="search-column-icon-gridjs-component" width="20" height="20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                                    </svg>

                                    <input type="text" id="inputColumnSearch<?=$uuid?>" placeholder="Pesquisar na coluna selecionada..." class="input-column-search-gridjs-component">
                                </div>
                            </div>
                        </div>

                        <!-- Acordion de checkbox de vizibilidade -->
                        <div class="accordion-settings-menu">
                            <div class="accordion-item">
                                <div class="accordion-header-gridjs-component" onclick="toggleAccordionGridjsComponent(event)">
                                    <span>Visibilidade das Colunas</span>
                                    
                                    <div class="icon-toggle">
                                        <svg class="icon-expand" xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>

                                        <svg class="icon-collapse hidden-gridjs-component" xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="accordion-content-gridjs-component hidden-gridjs-component">
                                    <ul class="dropdown-list-gridjs-component">
                                        <?php foreach ($columns as $i => $column): ?>
                                            <li>
                                                <div class="dropdown-item-gridjs-component">
                                                    <input class="checkbox-settings-menu-gridjs-component" id="checkbox-item-<?=$i?>" type="checkbox" data-label="<?=$column['label']?>" name="gridColumns<?=$uuid?>" value="<?=$column['name']?>" checked>
                                                    
                                                    <label for="checkbox-item-<?=$i?>" class="label-settings-menu-gridjs-component">
                                                        <?=$column['label']?>
                                                    </label>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                    
                            <!-- Acordion de coluna com codição e pesquisa -->
                            <div class="accordion-item">
                                <div class="accordion-header-gridjs-component" onclick="toggleAccordionGridjsComponent(event)">
                                    <span><?= StringHelper::toUtf8('Filtragem por Condições') ?></span>
        
                                    <div class="icon-toggle">
                                        <svg class="icon-expand" xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>

                                        <svg class="icon-collapse hidden-gridjs-component" xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="accordion-content-gridjs-component hidden-gridjs-component">
                                    <div style="display: flex;">

                                        <select id="select-column-search-condition-<?=$uuid?>" class="select-column-condition-search-gridjs-component">
                                            <option value="">Selecione uma coluna</option>

                                            <?php foreach ($columns as $i => $column): ?>
                                                <option value="<?=$column['name']?>"><?=$column['label']?></option>
                                            <?php endforeach; ?>
                                        </select>

                                        <select id="select-search-condition-<?=$uuid?>" class="select-condition-search-gridjs-component">
                                            <option value=""><?= StringHelper::toUtf8('Selecione uma condição') ?></option>
                                            <option value=">">maior que</option>
                                            <option value="<">menor que</option>
                                            <option value=">=">maior ou igual a</option>
                                            <option value="<=">menor ou igual a</option>
                                        </select>

                                        <div class="search-condition-column-input-container-gridjs-component">
                                            <svg class="search-condition-column-icon-gridjs-component" width="20" height="20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                                            </svg>

                                            <input type="text" id="input-search-condition-<?=$uuid?>" placeholder="Informe um valor" class="input-condition-search-gridjs-component">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>
            
    <div id="<?=$id?>"></div>
</div>

<div id="gridEmptyContent<?=$uuid?>" class="<?=(empty($apiUrl))?'':'hidden-gridjs-component'?>">
    <div class="empty-table-gridjs-component">
        <div class="icon-container-empty-table-gridjs-component">
            <svg class="search-icon-empty-table-gridjs-component" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
            </svg>
        </div>

        <div class="title-empty-table-gridjs-component">
            Nenhum registro encontrado
        </div>

        <div class="message-empty-table-gridjs-component">
            <?= StringHelper::toUtf8($emptyTableFirstLoadMessage) ?>
        </div>
    </div>
</div>


<div id="auxiliarySpansServerSideLegacy<?=$uuid?>">

</div>

<script>
    let grid<?=$uuid?>;

    /**
     * Inicializa um componente de tabela Grid.js com configurações personalizadas.
     * 
     * Esta função configura e renderiza uma tabela Grid.js usando dados de configuração
     * fornecidos, além de aplicar suporte a ordenação, paginação e seleção de linhas.
     * 
     * Funcionalidade:
     * - Ordenação de coluna: Permite ordenação de coluna via servidor, com URLs dinâmicas
     *   construídas com base na coluna e direção de ordenação.
     * - Paginação: Habilita paginação com suporte ao servidor, controlando o número
     *   de resultados exibidos por página e o total de registros.
     * - Seleção de linha: Adiciona funcionalidade de clique duplo e clique único para 
     *   capturar dados da linha e destacar a linha selecionada.
     * - Verificação de carregamento: Reitera o carregamento do Grid.js até o máximo
     *   de 10 segundos, caso ele ainda não esteja disponível, evitando erros de renderização.
     * 
     * Eventos:
     * - 'rowClick': Gerencia a seleção de linha e captura dados para o banco de dados 
     *   no evento de clique duplo.
     */
    function initializeGrid<?=$uuid?>() 
    {
        let elapsedTime = 0;

        if (isGridJsAvailable()) {
            const gridElement<?=$uuid?> = document.getElementById('<?=$id?>');
            const gridOptions = createGridOptions<?=$uuid?>();

            // Renderize o grid
            grid<?=$uuid?> = new gridjs.Grid(gridOptions).render(gridElement<?=$uuid?>);

            <?php if($rowClick): ?>
                setupRowClickEvent<?=$uuid?>();
            <?php endif; ?>

            <?php if($rowDoubleClick): ?>
                setupDoubleClickEvent<?=$uuid?>();
            <?php endif; ?>

            <?php if(!empty($loadFunction)): ?>;
                setTimeout(() => {
                    <?= $loadFunction ?>('<?=$uuid?>');
                }, 2000);
            <?php endif; ?>
            
        } else if (elapsedTime < maxDuration) {
            elapsedTime += checkInterval;
            setTimeout(initializeGrid<?=$uuid?>, checkInterval);
        } else {
            console.error('Grid.js não foi carregado após 10 segundos.');
        }
    }

    /**
     * Verifica se a biblioteca Grid.js está disponível no ambiente atual.
     * 
     * A função verifica se os objetos `gridjs` e `gridjs.Grid` estão definidos.
     * Se ambos existirem, isso indica que a biblioteca Grid.js foi carregada 
     * corretamente e está pronta para uso.
     * 
     * @returns {boolean} Retorna `true` se a Grid.js estiver disponível, caso contrário, `false`.
     */
    function isGridJsAvailable() 
    {
        return typeof gridjs !== 'undefined' && typeof gridjs.Grid !== 'undefined';
    }

    /**
     * Cria as opções de configuração para um grid utilizando a biblioteca Grid.js.
     * 
     * Esta função gera dinamicamente as opções de configuração do grid com base em 
     * parâmetros fornecidos pelo backend e lógica interna. 
     * 
     * - As colunas são definidas a partir da variável PHP `$labels`.
     * - Configurações de estilo, pesquisa, redimensionamento, cabeçalho fixo e idioma
     *   também são incluídas.
     * - Caso uma URL da API seja fornecida, são ativadas opções de servidor, 
     *   como ordenação, paginação e busca. 
     * - Se nenhuma URL for especificada, um grid vazio é retornado.
     * 
     * @returns {object} Objeto contendo as opções configuradas para o grid.
     */
    function createGridOptions<?=$uuid?>() 
    {
        let labels = <?=json_encode($labels)?>;
        let labelsFilter = labels.filter(item => item !== undefined && item !== null && item !== "");

        <?php if($rowClick): ?>
            labelsFilter.unshift({name: gridjs.html('<i onclick="toggleSelection()" class="icon m-8653X"></i>'), sort: false});
        <?php endif; ?>

        const options = {
            columns: labelsFilter,
            style: { table: { 'white-space': 'nowrap' } },
            search: false,
            <?= ($fixedHeader) ? 'fixedHeader: true' : 'fixedHeader: false'; ?>,
            <?= ($sort) ? 'sort: true' : 'sort: false'; ?>,
            language: getLanguageOptions<?=$uuid?>(),
        };

        const url = '<?=$apiUrl?>';

        if (url.trim()) {
            options.server = getServerOptions<?=$uuid?>(url);
            <?php if($sort): ?>
                options.sort = getSortOptions<?=$uuid?>(url);
            <?php endif; ?>
            options.pagination = getPaginationOptions<?=$uuid?>(url);
        } else {
            options.data = [];
        }

        return options;
    }

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
    function getLanguageOptions<?=$uuid?>() 
    {
        return {
            search: { placeholder: 'Digite uma palavra-chave...' },
            sort: { sortAsc: 'Ordenar coluna em ordem crescente', sortDesc: 'Ordenar coluna em ordem decrescente' },
            pagination: {
                previous: 'Anterior', next: '<?=StringHelper::toUtf8('Próximo')?>',
                navigate: (page, pages) => `Página ${page} de ${pages}`,
                page: (page) => `Página ${page}`, showing: 'Exibindo',
                of: 'de', to: 'a', results: 'resultados',
            },
            loading: 'Carregando...', noRecordsFound: 'Nenhum registro correspondente encontrado',
            error: 'Ocorreu um erro ao buscar os dados',
        };
    }

    /**
     * Define as opções do servidor para o grid, configurando como os dados são 
     * obtidos e processados a partir de uma API.
     * 
     * Esta função retorna um objeto que contém:
     * - A URL base da API, configurada com um parâmetro adicional `?` para facilitar a adição de query strings.
     * - O método HTTP usado para buscar os dados (neste caso, `GET`).
     * - Uma função `then` que transforma os dados recebidos, convertendo cada item
     *   em um array com os valores das suas propriedades.
     * - Uma função `total` que extrai o número total de registros a partir da 
     *   propriedade `meta.total` da resposta da API.
     * - Uma função `handle` que processa a resposta HTTP, convertendo-a para JSON.
     * 
     * @param {string} url - A URL base da API para buscar os dados.
     * @returns {object} Objeto configurado com as opções do servidor para o grid.
     */
    function getServerOptions<?=$uuid?>(url) 
    {
        return {
            url: url.includes('?') ? url : url + '?',
            method: 'GET',
            then: data => {
                let dataColumns = JSON.parse('<?=json_encode($columns)?>').map(col => {
                    if (col.name.includes(' as ')) {
                        col.name = col.name.split(' as ')[1].trim();
                    }
                    return col;
                })

                const columnsToRemove = dataColumns
                    .map((col, index) => (col.label === "" || col.label === null ? index : -1))
                    .filter(index => index !== -1);
                
                const columnNamesToRemove = columnsToRemove.map(index => {
                    const fullName = dataColumns[index].name;
                    if (fullName.includes(' as ')) {
                        return fullName.split(' as ')[1].trim();
                    }
                    return fullName;
                });

                const filteredData = data.data.map(item => {
                    const filteredItem = {};
                    Object.keys(item).forEach(key => {
                        if (!columnNamesToRemove.includes(key)) {
                            filteredItem[key] = item[key];
                        }
                    });
                    
                    return Object.values(filteredItem);
                });

                document.getElementById('auxiliarySpansServerSideLegacy<?=$uuid?>').innerHTML = "";

                data.data.forEach((rowData, rowIndex) => {
                    const values = Object.values(rowData);
                    const hiddenSpan = document.createElement('span');
                    hiddenSpan.classList.add('hidden');

                    values.forEach((cellValue, colIndex) => {
                        if (colIndex < dataColumns.length) {
                            const column = dataColumns[colIndex];
                            hiddenSpan.setAttribute(`data-${column.name}`, cellValue);
                        }
                    });
                    
                    document.getElementById('auxiliarySpansServerSideLegacy<?=$uuid?>').appendChild(hiddenSpan);
                });

                // Adiciona o ícone para cada linha
                return filteredData.map(item => {
                    <?php if($rowClick): ?>
                        item.unshift(gridjs.html('<i data-uuid="<?=$uuid?>" class="icon circle-8653X"></i>'));
                    <?php endif; ?>
                    return item;
                });
            },
            total: (data) => data.meta.total,
            handle: (res) => res.json(),
        };
    }

    /**
     * Define as opções de ordenação para o grid, incluindo suporte para ordenação 
     * no lado do servidor.
     * 
     * Esta função retorna um objeto que configura:
     * - A desativação da ordenação por múltiplas colunas (`multiColumn: false`).
     * - A lógica para a construção da URL de ordenação no servidor:
     *   - Utiliza a primeira coluna selecionada como referência para ordenar.
     *   - Determina a direção (`asc` ou `desc`) com base no valor da propriedade `direction` da coluna.
     *   - Mapeia o índice da coluna para o nome real da coluna no servidor, usando 
     *     o objeto PHP `$columnsNames`.
     *   - Adiciona parâmetros `order` (nome da coluna) e `dir` (direção) à URL base.
     * 
     * @param {string} url - A URL base usada como ponto de partida para a ordenação.
     * @returns {object} Objeto configurado com as opções de ordenação para o grid.
     */
    function getSortOptions<?=$uuid?>(url) 
    {
        return {
            multiColumn: false,
            server: {
                url: (prev, columns) => {
                    if (!columns.length) return prev;
                    const col = columns[0];
                    const dir = col.direction === 1 ? 'asc' : 'desc';
                    const colName = <?=json_encode($columnsNames)?>[col.index];
                    return `${prev}&order=${colName}&dir=${dir}`;
                },
            },
        };
    }

    /**
     * Define as opções de paginação para o grid, incluindo suporte para paginação
     * no lado do servidor.
     * 
     * Esta função retorna um objeto que configura:
     * - Habilitação da paginação (`enabled: true`).
     * - O número de registros exibidos por página, definido pela variável PHP `$perPage`.
     * - Lógica de paginação no servidor, que constrói a URL com os parâmetros de:
     *   - Quantidade de registros por página (`show`).
     *   - Página atual (`page`), ajustada para ser baseada em 1 (padrão do servidor).
     * 
     * @param {string} url - A URL base usada como ponto de partida para a paginação.
     * @returns {object} Objeto configurado com as opções de paginação para o grid.
     */
    function getPaginationOptions<?=$uuid?>(url) {
        return {
            enabled: true,
            limit: <?=$perPage?>,
            server: {
                url: (prev, page, perPage) => `${prev}&show=<?=$perPage?>&page=${page+1}`,
            },
        };
    }

    /**
     * Gerencia a seleção visual da linha clicada na tabela.
     * 
     * Mantém o estilo da linha selecionada, adicionando uma classe CSS 
     * para destacar visualmente a linha. Remove a seleção anterior ao 
     * clicar em uma nova linha.
     */
    function setupRowClickEvent<?=$uuid?>() {
        let selectedRow = null;

        grid<?=$uuid?>.on('rowClick', (event, row) => {
            const rowElement = event.target.closest('tr');
            <?php if(!$multiSelect): ?>
                if (selectedRow) selectedRow.classList.remove('gridjs-tr-selected');
                if (selectedRow) selectedRow.classList.remove('rowSelected<?=$idSanitize?>');
            <?php endif; ?>
            rowElement.classList.toggle('gridjs-tr-selected');
            rowElement.classList.toggle('rowSelected<?=$idSanitize?>');

            const iconElement = rowElement.querySelector('td:first-child i');
            if (iconElement) {
                if (iconElement.classList.contains('circle-8653X')) {
                    iconElement.classList.replace('circle-8653X', 'check-8653X');
                } else {
                    iconElement.classList.replace('check-8653X', 'circle-8653X');
                }
            }

            <?php if($rowClickFunction): ?>
                const selectedRows = document.querySelectorAll('.rowSelected<?=$idSanitize?>');
                <?=$rowClickFunction?>(selectedRows);
            <?php endif; ?>
        });
    }

    /**
     * Gerencia cliques únicos e duplos em uma linha da tabela Grid.js.
     * 
     * Configura um timeout para detectar cliques duplos em uma linha.
     * Se um clique duplo for detectado, a função `captureDB` é chamada 
     * com o elemento da linha clicada. Para cliques únicos, o timeout 
     * é zerado após 300ms, não realizando nenhuma ação.
     */
    function setupDoubleClickEvent<?=$uuid?>() {
        <?php if($rowDoubleClick): ?>
            let clickTimeout;

            grid<?=$uuid?>.on('rowClick', (event, row) => {
                if (clickTimeout) {
                    clearTimeout(clickTimeout);
                    clickTimeout = null;
                    const rowElement = event.target.closest('tr');
                    <?php if($rowDoubleClickFunction): ?>
                        <?=$rowDoubleClickFunction?>(rowElement);
                    <?php endif; ?>
                } else {
                    clickTimeout = setTimeout(() => { clickTimeout = null; }, 300);
                }
            });
        <?php endif; ?>
    }

    /**
     * Atualiza o grid existente, recriando sua instância.
     * 
     * Esta função é utilizada para reiniciar o grid quando há necessidade de 
     * atualizar suas configurações ou recarregar seus dados. O processo envolve:
     * - Verificar se a instância do grid (`grid$uuid`) existe.
    * - Destruir a instância atual usando o método `destroy`.
    * - Recriar a instância do grid chamando a função `initializeGrid$uuid`.
    * 
    * @returns {void}
    */
    function refreshGrid<?=$idSanitize?>() 
    {
        if (grid<?=$uuid?>) {
            grid<?=$uuid?>.destroy();
            initializeGrid<?=$uuid?>();
        }
    }

    /**
     * Obtém o valor do código da linha atualmente selecionada.
     *
     * @returns {string|null} Retorna o valor da célula "codigo" da linha selecionada, 
     *                        ou null se nenhuma linha estiver selecionada.
     */
    function getSelectedCodigo() {
        const selectedRows = document.querySelectorAll('.rowSelected<?=$idSanitize?>');
        return selectedRows;
    }

    /**
     * Limpa todos os filtros aplicados no Grid.js e recarrega os dados da tabela
     * a partir da URL original, sem parâmetros de pesquisa ou filtros adicionais.
     * 
     * A função redefine a URL do servidor para a URL base, fazendo uma nova requisição
     * ao servidor para buscar os dados sem filtros aplicados. Isso restaura a visualização
     * padrão da tabela.
     */
    function cleanFilters<?=$uuid?>()
    {
        const checkboxes = document.querySelectorAll("input[name='gridColumns<?=$uuid?>']");
        const selectedDataColumns = Array.from(checkboxes).map(checkbox => checkbox.getAttribute('data-label'));
        <?php if($rowClick): ?>
            selectedDataColumns.unshift({name: gridjs.html('<i onclick="toggleSelection()" class="icon m-8653X"></i>'), sort: false});
        <?php endif; ?>

        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });

        grid<?=$uuid?>.updateConfig({
            columns: selectedDataColumns,
            server: {
                url: `<?=$apiUrl?>?`,
                then: data => {
                    // Identifica os índices das colunas com label vazio ou null
                    const columnsToRemove = data.columns
                        .map((col, index) => (col.label === "" || col.label === null ? index : -1))
                        .filter(index => index !== -1);

                    // Filtra os dados
                    const filteredData = data.data.map(item => {
                        const values = Object.values(item);

                        // Remove os valores correspondentes aos índices das colunas removidas
                        return values.filter((_, index) => !columnsToRemove.includes(index));
                    });

                    document.getElementById('auxiliarySpansServerSideLegacy<?=$uuid?>').innerHTML = "";

                    data.data.forEach((cellValue, colIndex) => {
                        const values = Object.values(cellValue);
                        const hiddenSpan = document.createElement('span');
                        hiddenSpan.classList.add('hidden');

                        values.forEach((cellValue, colIndex) => {
                            const column = data.columns[colIndex];
                            hiddenSpan.setAttribute(`data-${column.name}`, cellValue);
                            document.getElementById('auxiliarySpansServerSideLegacy<?=$uuid?>').appendChild(hiddenSpan);
                        });
                    });

                    // Adiciona o ícone para cada linha
                    return filteredData.map(item => {
                        <?php if($rowClick): ?>
                            item.unshift(gridjs.html('<i data-uuid="<?=$uuid?>" class="icon circle-8653X"></i>'));
                        <?php endif; ?>
                        return item;
                    });
                },
                total: data => data.meta.total,
            }
        }).forceRender();
    }

    function toggleSelection() {
        let gridContainer = document.getElementById(`gridjsContent<?=$uuid?>`);
        if (!gridContainer) {
            console.error("Grid container not found!");
            return;
        }

        let allRows = gridContainer.querySelectorAll("tbody .gridjs-tr");
        let allIcons = gridContainer.querySelectorAll("tbody .gridjs-tr .icon");

        if (allIcons.length === 0) return;

        let areAllSelected = Array.from(allIcons).every(icon => icon.classList.contains("check-8653X"));

        allIcons.forEach(icon => {
            let row = icon.closest(".gridjs-tr");

            if (areAllSelected) {
                icon.classList.replace("check-8653X", "circle-8653X");
                row.classList.remove("gridjs-tr-selected", "rowSelected<?=$idSanitize?>");
            } else {
                icon.classList.replace("circle-8653X", "check-8653X");
                row.classList.add("gridjs-tr-selected", "rowSelected<?=$idSanitize?>");
            }
        });

        <?php if($rowClickFunction): ?>
            const selectedRows = document.querySelectorAll('.rowSelected<?=$idSanitize?>');
            <?=$rowClickFunction?>(selectedRows);
        <?php endif; ?>
    }

    /**
     * Inicializa um Grid.js dinâmico com configuração de colunas, paginação e ordenação baseada em servidor.
     * 
     * @param {string} url - URL da API para carregar os dados do grid.
     * @param {Object} request - Objeto contendo informações de configuração para o grid.
     * @param {Array} request.columns - Array de colunas com propriedades `label` e `name`.
     */
    function initializeGridjs<?=$idSanitize?>(url, request) 
    {
        const labels = request.columns
            .map(column => column.label)
            .filter(label => label && label.trim() !== "");

        <?php if($rowClick): ?>
            labels.unshift({name: gridjs.html('<i onclick="toggleSelection()" class="icon m-8653X"></i>'), sort: false});
        <?php endif; ?>

        const names = request.columns.map(column => column.name);
        const gridjsContent = document.getElementById('gridjsContent<?=$uuid?>');
        const gridEmptyContent = document.getElementById('gridEmptyContent<?=$uuid?>');

        gridjsContent.classList.remove("hidden-gridjs-component");
        gridEmptyContent.classList.add("hidden-gridjs-component");

        refreshGrid<?=$idSanitize?>()

        grid<?=$uuid?>.updateConfig({
            columns: labels,
            style: { table: { 'white-space': 'nowrap' } },
            sort: {
                multiColumn: false,
                server: {
                    url: (prev, columns) => {
                        if (!columns.length) return prev;

                        const col = columns[0];
                        const dir = col.direction === 1 ? 'asc' : 'desc';
                        const colName = names[col.index];

                        return `${prev}&order=${colName}&dir=${dir}`;
                    }
                }
            },
            pagination: {
                enabled: true,
                limit: <?=$perPage?>,
                server: {
                    url: (prev, page, perPage) => `${prev}&show=<?=$perPage?>&page=${page+1}`
                }
            },
            server: {
                url: url,
                method: 'GET',
                then: data => {
                    // Identifica os índices das colunas com label vazio ou null
                    const columnsToRemove = data.columns
                        .map((col, index) => (col.label === "" || col.label === null ? index : -1))
                        .filter(index => index !== -1);

                    // Filtra os dados
                    const filteredData = data.data.map(item => {
                        const values = Object.values(item);

                        // Remove os valores correspondentes aos índices das colunas removidas
                        return values.filter((_, index) => !columnsToRemove.includes(index));
                    });

                    document.getElementById('auxiliarySpansServerSideLegacy<?=$uuid?>').innerHTML = "";

                    data.data.forEach((cellValue, colIndex) => {
                        const values = Object.values(cellValue);
                        const hiddenSpan = document.createElement('span');
                        hiddenSpan.classList.add('hidden');

                        values.forEach((cellValue, colIndex) => {
                            const column = data.columns[colIndex];
                            hiddenSpan.setAttribute(`data-${column.name}`, cellValue);
                            document.getElementById('auxiliarySpansServerSideLegacy<?=$uuid?>').appendChild(hiddenSpan);
                        });
                    });

                    // Adiciona o ícone para cada linha
                    return filteredData.map(item => {
                        <?php if($rowClick): ?>
                            item.unshift(gridjs.html('<i data-uuid="<?=$uuid?>" class="icon circle-8653X"></i>'));
                        <?php endif; ?>
                        return item;
                    });
                },
                total: data => data.meta.total,
                handle: (res) => res.json(),
            }
        }).forceRender();

        <?php if($search): ?>
            document.getElementById("searchInput<?=$uuid?>").addEventListener("input", debounce<?=$uuid?>(function (event) {
                const searchTerm = event.target.value.toLowerCase();

                grid<?=$uuid?>.updateConfig({
                    server: {
                        url: url+`&search=${searchTerm}`,
                        then: data => {
                            // Identifica os índices das colunas com label vazio ou null
                            const columnsToRemove = data.columns
                                .map((col, index) => (col.label === "" || col.label === null ? index : -1))
                                .filter(index => index !== -1);

                            // Filtra os dados
                            const filteredData = data.data.map(item => {
                                const values = Object.values(item);

                                // Remove os valores correspondentes aos índices das colunas removidas
                                return values.filter((_, index) => !columnsToRemove.includes(index));
                            });

                            document.getElementById('auxiliarySpansServerSideLegacy<?=$uuid?>').innerHTML = "";

                            data.data.forEach((cellValue, colIndex) => {
                                const values = Object.values(cellValue);
                                const hiddenSpan = document.createElement('span');
                                hiddenSpan.classList.add('hidden');

                                values.forEach((cellValue, colIndex) => {
                                    const column = data.columns[colIndex];
                                    hiddenSpan.setAttribute(`data-${column.name}`, cellValue);
                                    document.getElementById('auxiliarySpansServerSideLegacy<?=$uuid?>').appendChild(hiddenSpan);
                                });
                            });

                            // Adiciona o ícone para cada linha
                            return filteredData.map(item => {
                                <?php if($rowClick): ?>
                                    item.unshift(gridjs.html('<i data-uuid="<?=$uuid?>" class="icon circle-8653X"></i>'));
                                <?php endif; ?>
                                return item;
                            });
                        },
                        total: data => data.meta.total,
                    }
                }).forceRender();
            }, 300));
        <?php endif; ?>

        <?php if(!empty($loadFunction)): ?>;
            setTimeout(() => {
                <?= $loadFunction ?>('<?=$uuid?>');
            }, 2000);
        <?php endif; ?>
    }

    /**
     * A função `debounce` implementa uma técnica que limita a quantidade de vezes que uma função pode ser executada em um intervalo de tempo. 
     * Ela é comumente usada para otimizar eventos de alta frequência, como o `input` de um campo de busca, evitando chamadas excessivas 
     * a uma função, especialmente em interações rápidas do usuário.
     * 
     * @param {Function} func - A função que será "debounced". A função original que será chamada após o atraso.
     * @param {number} delay - O tempo de atraso (em milissegundos) após a última execução do evento, antes de chamar a função.
     * 
     * @returns {Function} - Retorna uma nova função que, quando chamada, cancela qualquer execução pendente e reinicia o timer.
     * Isso garante que `func` só seja chamada após o tempo `delay` de inatividade após a última chamada.
     * 
     * Exemplo de uso:
     *     - Usado frequentemente em campos de busca ou ao rolar a página, para reduzir a quantidade de chamadas de API.
     */
    function debounce<?=$uuid?>(func, delay) 
    {
        let timeoutId;
        return function (...args) {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                func(...args);
            }, delay);
        };
    }

    /**
     * Configura a página após o carregamento do DOM para inicializar o Grid.js, 
     * configurar o menu dropdown e adicionar funcionalidade de busca.
     */
    document.addEventListener('DOMContentLoaded', function() {
        initializeGrid<?=$uuid?>();

        <?php if(!empty($columnsNames)): ?>
            /**
             * O código abaixo seleciona dois elementos do DOM usando seus respectivos IDs, que incluem um identificador único gerado dinamicamente
             * através da variável `$uuid`. Esses elementos são usados para controlar a interação de um botão de toggle (abre e fecha) de um dropdown.
             */
            const dropdownToggleButton<?=$uuid?> = document.getElementById("dropdownToggleButton<?=$uuid?>");
            const dropdownToggle<?=$uuid?> = document.getElementById("dropdownToggle<?=$uuid?>");
        
            /**
             * Gerencia a pesquisa filtrada por coluna no Grid.js, permitindo ao usuário 
             * escolher a coluna para pesquisa e fornecer o termo de busca.
             * 
             * - Quando o campo de pesquisa ou a seleção de coluna são alterados, 
             *   a URL da requisição é atualizada com os parâmetros adequados para 
             *   filtrar os dados no servidor.
             */
            const selectColumnSearch = document.getElementById("selectColumnSearch<?=$uuid?>");
            const inputColumnSearch = document.getElementById("inputColumnSearch<?=$uuid?>");

            <?php if($search): ?>
                /*
                * Obtém a referência ao elemento HTML de entrada (input) com o ID "searchInput$uuid".
                * 
                * - O "document.getElementById" é usado para selecionar o elemento HTML pelo seu ID.
                * - "$uuid" é provavelmente uma variável PHP que está sendo interpolada para 
                *   gerar um identificador único no lado do servidor. 
                */
                const inputSearch = document.getElementById("searchInput<?=$uuid?>");
            <?php endif; ?>

            /**
             * Alterna a visibilidade do menu dropdown ao clicar no botão.
             *
             * @param {Event} event - O evento de clique no botão do dropdown.
             * - Interrompe a propagação para que o clique fora do menu feche o dropdown.
             */
            dropdownToggleButton<?=$uuid?>.addEventListener("click", function(event) {
                dropdownToggle<?=$uuid?>.classList.toggle("hidden-gridjs-component");
                event.stopPropagation();
            });
        
            /**
             * Fecha o menu dropdown ao clicar fora dele.
             *
             * @param {Event} event - Evento de clique na janela.
             * - Verifica se o clique ocorreu fora dos elementos dropdown e, se sim, oculta o menu.
             */
            window.addEventListener("click", function(event) {
                if (!dropdownToggleButton<?=$uuid?>.contains(event.target) && !dropdownToggle<?=$uuid?>.contains(event.target)) {
                    dropdownToggle<?=$uuid?>.classList.add("hidden-gridjs-component");
                }
            });

            <?php if($search): ?>
                /**
                 * Configura uma funcionalidade de busca dinâmica no Grid.js.
                 *
                 * @param {Event} event - O evento de entrada no campo de busca.
                 * - Captura o valor digitado, atualiza a configuração de URL do Grid.js
                 *   para incluir o termo de busca e força a renderização com novos resultados.
                 */
                function updateGridWithSearch(event) 
                {
                    const searchTerm = inputSearch.value.toLowerCase();

                    grid<?=$uuid?>.updateConfig({
                        server: {
                            url: `<?=$apiUrl?>?search=${searchTerm}`,
                            then: data => {
                                // Identifica os índices das colunas com label vazio ou null
                                const columnsToRemove = data.columns
                                    .map((col, index) => (col.label === "" || col.label === null ? index : -1))
                                    .filter(index => index !== -1);

                                // Filtra os dados
                                const filteredData = data.data.map(item => {
                                    const values = Object.values(item);

                                    // Remove os valores correspondentes aos índices das colunas removidas
                                    return values.filter((_, index) => !columnsToRemove.includes(index));
                                });

                                document.getElementById('auxiliarySpansServerSideLegacy<?=$uuid?>').innerHTML = "";

                                data.data.forEach((cellValue, colIndex) => {
                                    const values = Object.values(cellValue);
                                    const hiddenSpan = document.createElement('span');
                                    hiddenSpan.classList.add('hidden');

                                    values.forEach((cellValue, colIndex) => {
                                        const column = data.columns[colIndex];
                                        hiddenSpan.setAttribute(`data-${column.name}`, cellValue);
                                        document.getElementById('auxiliarySpansServerSideLegacy<?=$uuid?>').appendChild(hiddenSpan);
                                    });
                                });

                                // Adiciona o ícone para cada linha
                                return filteredData.map(item => {
                                    <?php if($rowClick): ?>
                                        item.unshift(gridjs.html('<i data-uuid="<?=$uuid?>" class="icon circle-8653X"></i>'));
                                    <?php endif; ?>
                                    return item;
                                });
                            },
                            total: data => data.meta.total,
                        }
                    }).forceRender();
                }
            <?php endif; ?>

            /**
             * Atualiza a tabela Grid.js com a URL de busca modificada conforme 
             * o termo de pesquisa e a coluna selecionada.
             * 
             * A URL da requisição será composta pelo termo de pesquisa e pela coluna
             * escolhida, aplicando os filtros no servidor para obter os dados.
             */
            function updateGridWithColumnSearch() 
            {
                const column = selectColumnSearch.value;
                const searchTerm = inputColumnSearch.value.toLowerCase();

                document.getElementById("warningLabelColumn<?=$uuid?>").classList.add("hidden-gridjs-component");
                document.getElementById("warningLabelSearch<?=$uuid?>").classList.add("hidden-gridjs-component");
                inputColumnSearch.classList.remove("border-color-red-gridjs-component");
                selectColumnSearch.classList.remove("border-color-red-gridjs-component");

                if (column != "" && searchTerm != "") {
                    grid<?=$uuid?>.updateConfig({
                        server: {
                            url: `<?=$apiUrl?>?search=${column}:${searchTerm}`,
                            then: data => {
                                // Identifica os índices das colunas com label vazio ou null
                                const columnsToRemove = data.columns
                                    .map((col, index) => (col.label === "" || col.label === null ? index : -1))
                                    .filter(index => index !== -1);

                                // Filtra os dados
                                const filteredData = data.data.map(item => {
                                    const values = Object.values(item);

                                    // Remove os valores correspondentes aos índices das colunas removidas
                                    return values.filter((_, index) => !columnsToRemove.includes(index));
                                });

                                document.getElementById('auxiliarySpansServerSideLegacy<?=$uuid?>').innerHTML = "";

                                data.data.forEach((cellValue, colIndex) => {
                                    const values = Object.values(cellValue);
                                    const hiddenSpan = document.createElement('span');
                                    hiddenSpan.classList.add('hidden');

                                    values.forEach((cellValue, colIndex) => {
                                        const column = data.columns[colIndex];
                                        hiddenSpan.setAttribute(`data-${column.name}`, cellValue);
                                        document.getElementById('auxiliarySpansServerSideLegacy<?=$uuid?>').appendChild(hiddenSpan);
                                    });
                                });

                                // Adiciona o ícone para cada linha
                                return filteredData.map(item => {
                                    <?php if($rowClick): ?>
                                        item.unshift(gridjs.html('<i data-uuid="<?=$uuid?>" class="icon circle-8653X"></i>'));
                                    <?php endif; ?>
                                    return item;
                                });
                            },
                            total: data => data.meta.total,
                        }
                    }).forceRender();
                } else if(column == "" && searchTerm != "") {
                    document.getElementById("warningLabelColumn<?=$uuid?>").classList.remove("hidden-gridjs-component");
                    selectColumnSearch.classList.add("border-color-red-gridjs-component");
                } else if(column != "" && searchTerm == "") {
                    document.getElementById("warningLabelSearch<?=$uuid?>").classList.remove("hidden-gridjs-component");
                    inputColumnSearch.classList.add("border-color-red-gridjs-component");
                } else {
                    console.log("Excecao de rotina");
                }
            }

            /**
             * A função `updateGridColumnsWithFilters` é responsável por atualizar a configuração das colunas no Grid.js com base nas colunas selecionadas.
             * Ela verifica se o usuário selecionou ao menos uma coluna e atualiza a URL da API para refletir as colunas escolhidas.
             *
             * - A função captura todos os checkboxes selecionados e obtém seus valores.
             * - Se nenhum checkbox for selecionado, exibe um alerta.
             * - Caso contrário, a configuração do grid é atualizada com as colunas selecionadas e os dados são recuperados da API.
             */
            function updateGridColumnsWithFilters()
            {
                const checkboxes = document.querySelectorAll("input[name='gridColumns<?=$uuid?>']:checked");
                const selectedUriColumns = Array.from(checkboxes).map(checkbox => checkbox.value).join(','); 
                const selectedDataColumns = Array.from(checkboxes).map(checkbox => checkbox.getAttribute('data-label'));

                // const labels = request.columns
                //     .map(column => column.label)
                //     .filter(label => label && label.trim() !== "");

                <?php if($rowClick): ?>
                    selectedDataColumns.unshift({name: gridjs.html('<i onclick="toggleSelection()" class="icon m-8653X"></i>'), sort: false});
                <?php endif; ?>

                if (checkboxes.length === 0) {
                    alert("Por favor, selecione ao menos uma coluna.");
                    return;
                } else {
                    grid<?=$uuid?>.updateConfig({
                        columns: selectedDataColumns,
                        server: {
                            url: `<?=$apiUrl?>?only=${selectedUriColumns}`,
                            then: data => {
                                // Identifica os índices das colunas com label vazio ou null
                                const columnsToRemove = data.columns
                                    .map((col, index) => (col.label === "" || col.label === null ? index : -1))
                                    .filter(index => index !== -1);

                                // Filtra os dados
                                const filteredData = data.data.map(item => {
                                    const values = Object.values(item);

                                    // Remove os valores correspondentes aos índices das colunas removidas
                                    return values.filter((_, index) => !columnsToRemove.includes(index));
                                });

                                document.getElementById('auxiliarySpansServerSideLegacy<?=$uuid?>').innerHTML = "";

                                data.data.forEach((cellValue, colIndex) => {
                                    const values = Object.values(cellValue);
                                    const hiddenSpan = document.createElement('span');
                                    hiddenSpan.classList.add('hidden');

                                    values.forEach((cellValue, colIndex) => {
                                        const column = data.columns[colIndex];
                                        hiddenSpan.setAttribute(`data-${column.name}`, cellValue);
                                        document.getElementById('auxiliarySpansServerSideLegacy<?=$uuid?>').appendChild(hiddenSpan);
                                    });
                                });

                                // Adiciona o ícone para cada linha
                                return filteredData.map(item => {
                                    <?php if($rowClick): ?>
                                        item.unshift(gridjs.html('<i data-uuid="<?=$uuid?>" class="icon circle-8653X"></i>'));
                                    <?php endif; ?>
                                    return item;
                                });
                            },
                            total: data => data.meta.total,
                        }
                    }).forceRender();
        
                    setTimeout(() => {
                        grid<?=$uuid?>.forceRender();
                    }, 50);
                }
            }

            /*
             * Adiciona um ouvinte de evento ("input") ao elemento `inputSearch`.
             * 
             * - Similar ao exemplo anterior, o evento "input" aciona a execução da função 
             *   `updateGridWithSearch`, mas com a limitação do debounce para evitar chamadas 
             *   excessivas (também configurado para 300ms).
             */
            <?php if($search): ?>
                inputSearch.addEventListener("input", debounce<?=$uuid?>(updateGridWithSearch, 300));
            <?php endif; ?>
            
            /*
             * Adiciona um ouvinte de evento ("input") ao elemento `inputColumnSearch`.
             * 
             * - Quando o usuário digita no campo, o evento "input" é acionado.
             * - A função `debounce$uuid` é chamada para limitar a frequência com que
             *   a função `updateGridWithColumnSearch` é executada (neste caso, a cada 300ms).
             * - O `$uuid` é uma variável PHP interpolada, garantindo um identificador único 
             *   para funções relacionadas.
             */
            inputColumnSearch.addEventListener("input", debounce<?=$uuid?>(updateGridWithColumnSearch, 300));

            /*
             * Adiciona um ouvinte de evento ("change") ao elemento `selectColumnSearch`.
             * 
             * - Diferentemente dos eventos anteriores, este evento é acionado sempre que o usuário
             *   muda a seleção em um elemento `<select>`.
             * - A função `updateGridWithColumnSearch` é chamada diretamente, sem debounce.
             */
            selectColumnSearch.addEventListener("change", updateGridWithColumnSearch);

            /*
             * Seleciona todos os elementos `input` do tipo checkbox com o atributo `name` igual a 'gridColumns$uuid'.
             * 
             * - `document.querySelectorAll` retorna todos os elementos que correspondem ao seletor fornecido.
             * - O seletor `"input[name='gridColumns$uuid']"` é usado para encontrar os checkboxes que têm o nome 'gridColumns' seguido de um identificador único `$uuid`.
             * - O `$uuid` é uma variável PHP interpolada que garante que o nome do elemento seja único, evitando conflitos em páginas dinâmicas.
             */
            document.querySelectorAll("input[name='gridColumns<?=$uuid?>']").forEach(checkbox => {
                checkbox.addEventListener("change", updateGridColumnsWithFilters);
            });
        <?php endif; ?>
    });
</script>

<?php
    // Limpar variáveis após uso
    unset($id, $class, $label, $columns, $apiUrl, $perPage, $search, $sort, $fixedHeader);
?>
