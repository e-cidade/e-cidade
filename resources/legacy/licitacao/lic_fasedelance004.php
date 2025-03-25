<?php
    use App\Helpers\StringHelper;
    require_once("libs/renderComponents/index.php");
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
</head>

<body bgcolor=#f5fffb leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">

    <?php $component->render('toast/default') ?>

    <div style="width: 100%; display: flex; justify-content: center; align-items: center; height: 100vh;">

        <?php $component->render('cards/simple/start', ['title' => StringHelper::toUtf8('Parâmetros Registro de Preço')], true) ?>

            <?php $component->render('inputs/selects/choices/simple', [
                'id' => 'precoref',
                'name' => 'l13_precoref',
                'label' => StringHelper::toUtf8('Exibir preço de referência:'),
                'startIndex' => 0,
                'size' => 'md',
                'selected' => (empty($param->l13_precoref)) ? '' : $param->l13_precoref,
                'options' => [
                    StringHelper::toUtf8('Não'),
                    'Sim'
                ]
            ]) ?>

            <?php $component->render('inputs/text/simple', [
                'id' => 'difminlance',
                'name' => 'l13_difminlance',
                'placeholder' => StringHelper::toUtf8('Diferença mínimo divergente.'),
                'label' => StringHelper::toUtf8('Diferença mínima entre lances:'),
                'value' => (empty($param->l13_difminlance))?'':$param->l13_difminlance,
                'size' => 'md',
            ]) ?>

            <?php $component->render('inputs/text/simple', [
                'id' => 'clapercent',
                'name' => 'l13_clapercent',
                'placeholder' => StringHelper::toUtf8('Percentual de classificação.'),
                'label' => StringHelper::toUtf8('Classificação de Fornecedor de até:'),
                'value' => (empty($param->l13_clapercent))?'':$param->l13_clapercent,
                'size' => 'md',
            ]) ?>
            
            <?php $component->render('inputs/selects/choices/simple', [
                'id' => 'avisodeacoestabela',
                'name' => 'l13_avisodeacoestabela',
                'label' => StringHelper::toUtf8('Exibir aviso de ações das tabelas:'),
                'startIndex' => 0,
                'size' => 'md',
                'selected' => (empty($param->l13_avisodeacoestabela)) ? '' : $param->l13_avisodeacoestabela,
                'options' => [
                    StringHelper::toUtf8('Não'),
                    'Sim'
                ]
            ]) ?>
            
            <div style="display: flex; justify-content: center; align-items: center; align-content: center; gap: 5px;">
                <?php $component->render('buttons/solid', [
                    'designButton' => 'success',
                    'size' => 'md',
                    'onclick' => 'updateParam()',
                    'message' => 'Alterar'
                ]); ?>
            </div>

        <?php $component->render('cards/simple/end', [], true) ?>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>

    <script>

        new Cleave('#difminlance', {
            prefix: 'R$ ',
            numeral: true,
            numeralThousandsGroupStyle: 'thousand',
            delimiter: '.',
            numeralDecimalMark: ',',
            numeralDecimalScale: 2
        });

        new Cleave('#clapercent', {
            prefix: '% ',
            numeral: true,
            numeralThousandsGroupStyle: 'thousand',
            delimiter: '.',
            numeralDecimalMark: ',',
            numeralDecimalScale: 2
        });

        /**
         * Envia os dados do formulário para atualizar os parâmetros de julgamento.
         *
         * Coleta os valores de `precoref`, `difminlance` e `clapercent`, e envia para o servidor via `POST`.
         * Em caso de erro (status 422, 400, 500), exibe mensagens de erro. Se a atualização for bem-sucedida, exibe uma mensagem de sucesso.
         *
         * @return void
         */
        function updateParam() {
            let precoref = document.getElementById("precoref").value;
            let difminlance = document.getElementById("difminlance").value;
            let clapercent = document.getElementById("clapercent").value;
            let avisodeacoestabela = document.getElementById("avisodeacoestabela").value;
            
            // Remover '%' e espaços em branco, substituir '.' por ',' e remover pontos de 'clapercent'
            clapercent = clapercent
            .replace(/\s+/g, '') // Remove espaços em branco
            .replace('%', '')    // Remove o símbolo '%'
            .replace(/\./g, '')  // Remove os pontos
            .replace(',', '.');  // Substitui ',' por '.'

            clapercent = parseFloat(clapercent);

            // Remover 'R$', espaços em branco, substituir '.' por ',' e remover pontos de 'difminlance'
            difminlance = difminlance
            .replace(/\s+/g, '')  // Remove espaços em branco
            .replace('R$', '')    // Remove 'R$'
            .replace(/\./g, '')   // Remove os pontos
            .replace(',', '.');   // Substitui ',' por '.'

            difminlance = parseFloat(difminlance);

            const data = {
                l13_precoref: precoref,
                l13_difminlance: difminlance,
                l13_clapercent: clapercent,
                l13_avisodeacoestabela: avisodeacoestabela,
            };

            fetch('<?=route('parametros.update')?>', {
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

                            if (errorData.errors.codigoFornecedor) {
                                errorData.errors.codigoFornecedor.forEach(errorMessage => {
                                    createToast(`${errorMessage}`, 'warning', 9000);
                                });
                            } 
                            
                            if (errorData.errors.codigoItemOrcamento) {
                                errorData.errors.codigoItemOrcamento.forEach(errorMessage => {
                                    createToast(`${errorMessage}`, 'warning', 9000);
                                });
                            } 
                            
                            if (errorData.errors.valorLance) {
                                errorData.errors.valorLance.forEach(errorMessage => {
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
                createToast(`${data.message}`, 'success', 4000);
            })
            .catch(error => {
                console.error('Erro ao salvar os dados:', error);
            });
        }
    </script>
</body>

</html>