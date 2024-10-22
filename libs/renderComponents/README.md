# Component Renderer Library

A biblioteca de renderização de componentes fornece uma maneira modular e reutilizável de incluir e gerenciar componentes em suas aplicações PHP. Ela permite carregar e renderizar componentes, incluindo seus arquivos CSS e JavaScript associados, de forma eficiente.

## Estrutura de Diretórios
```
├── README.md
├── config
│ └── components-config.json
├── index.php
├── public
│ └── loadComponents.js
└── src
├── components
│ ├── buttons
│ │ ├── buttons.css
│ │ ├── buttons.js
│ │ ├── fab
│ │ ├── ghost
│ │ ├── icon
│ │ ├── link
│ │ ├── outline
│ │ ├── solid
│ │ │ ├── index.php
│ │ │ ├── solids.css
│ │ │ └── solids.js
│ │ ├── text
│ │ ├── toggle
│ │ └── underlined
│ └── modais
│ └── simpleModal
│ ├── endModal.php
│ ├── simpleModal.css
│ ├── simpleModal.js
│ └── startModal.php
├── helpers
│ └── ComponentRenderer.php
└── interfaces
└── RenderableInterface.php
```


## Instalação

Para usar a biblioteca de renderização de componentes, siga estes passos:

1. **Inclua o arquivo `index.php`** em seu projeto PHP.

    ```php
    <?php require_once 'libs/renderComponents/index.php'; ?>
    ```

2. **Certifique-se de que o arquivo JavaScript `loadComponents.js`** está sendo carregado corretamente no seu HTML.

    ```html
    <script type="text/javascript">
        loadComponents([
            'buttonsSolid',
            'simpleModal',
        ]);
    </script>
    ```

## Uso

### Renderizar Componentes

Para renderizar um componente, use o método `render` da classe `ComponentRenderer`. Exemplo:

```php
<?php $component->render('buttons/solid', [
    'designButton' => 'primary',
    'size' => 'lg'
]); ?>
```

### Configuração de Componentes
Os componentes e seus arquivos associados são definidos no arquivo components-config.json. Este arquivo deve estar localizado em libs/renderComponents/config/components-config.json.

#### Exemplo de configuração:

```json
{
    "buttonsSolid": {
        "css": [
            "/libs/renderComponents/src/components/buttons/buttons.css",
            "/libs/renderComponents/src/components/buttons/solid/solids.css"
        ],
        "js": [
            "/libs/renderComponents/src/components/buttons/buttons.js",
            "/libs/renderComponents/src/components/buttons/solid/solids.js"
        ]
    },
    "simpleModal": {
        "css": [
            "/libs/renderComponents/src/components/modais/simpleModal/simpleModal.css"
        ],
        "js": [
            "/libs/renderComponents/src/components/modais/simpleModal/simpleModal.js"
        ]
    }
}
```

### Arquivos de Componentes
Os arquivos CSS e JavaScript de cada componente devem estar localizados nos diretórios apropriados dentro de libs/renderComponents/src/components/.

### Exemplo de Uso
Aqui está um exemplo de como renderizar botões e modais:

```php
<?php $component->render('buttons/solid', [
    'designButton' => 'primary',
    'size' => 'md'
]); ?>

<?php $component->render('modais/simpleModal/startModal', [
    'title' => 'Exemplo de Modal',
    'id' => 'exampleModal',
    'size' => 'lg'
], true); ?>
    <div>Conteúdo do modal.</div>
<?php $component->render('modais/simpleModal/endModal', [], true); ?>

<?php $component->render('buttons/solid', [
    'designButton' => 'primary',
    'id' => 'openModalBtn',
    'message' => 'Abrir Modal',
    'onclick' => "openModal('exampleModal')"
]); ?>
```

### Contribuição
Sinta-se à vontade para ajustar o conteúdo conforme necessário para refletir os detalhes específicos da sua biblioteca e requisitos de uso.