<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php 
        require_once 'libs/renderComponents/doc.php'; 
        $columns = ["coluna1","coluna2","coluna3","coluna4","coluna5","coluna6","coluna7"];

        $columns = [
            ['label' => 'Coluna1', 'name' => 'coluna1'],
            ['label' => 'Coluna2', 'name' => 'coluna2'],
            ['label' => 'Coluna3', 'name' => 'coluna3'],
            ['label' => 'Coluna4', 'name' => 'coluna4'],
            ['label' => 'Coluna5', 'name' => 'coluna5'],
            ['label' => 'Coluna6', 'name' => 'coluna6'],
            ['label' => 'Coluna7', 'name' => 'coluna7']
        ];
    ?>

    <?php $component->render('tables/gridJs/serverSideLegacy', [
        'id' => 'lista',
        'columns' => $columns,
        'apiUrl' => 'http://10.250.30.8/e-cidade-lucas-patrimonial/libs/renderComponents/aapi.php?component=tables/gridJs/serverSideLegacy/sideRender',
        'perPage' => 10,
        'searchEnabled' => true,
        'sortEnabled' => true,
        'rowDoubleClick' => true,
        'rowDoubleClickFunction' => 'rowDoubleClickFunctionExemple()'
    ]); ?>

    <script>
        function rowDoubleClickFunctionExemple() {
            alert('rowDoubleClickFunction');
        }
    </script>
</body>

</html>