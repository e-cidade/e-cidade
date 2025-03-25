<?php

require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_usuariosonline.php';
require_once 'dbforms/db_funcoes.php';
require_once 'libs/renderComponents/index.php';

?>

<script type="text/javascript" defer>
    loadComponents([
        'todosIcones',
        'selectsChoicesSimple',
        'cardsSimple',
        'radiosBordered',
        'dateSimple',
        'buttonsSolid'
    ]);
</script>

<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="FrontController.php" rel="stylesheet" type="text/css">
    <style>
    </style>
</head>

<body  bgcolor=#f5fffb leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<div  style="width: 100%; display: flex; justify-content: center; align-items: center; height: 100vh;">
    <?php $component->render('cards/simple/start', ['title' => 'Relatório de Usuários'], true) ?>

    <?php $component->render('inputs/selects/choices/simple', [
        'id' => 'filtrar',
        'name' => 'filtrar',
        'label' => 'Filtrar:',
        'startIndex' => 1,
        'size' => 'sm',
        'options' => [
            '1 - Ativos',
            '2 - Inativos'
        ],
    ]) ?>

    <hr style="margin: 0px 0px 10px 0px;">

    <!-- Radio Component -->
    <?php $component->render('inputs/radios/bordered', ['label' => 'PDF', 'id' => 'pdf', 'value' => 'pdf', 'name' => 'tipo', 'checked' => true]) ?>
    <div style="display: flex; justify-content: space-around; align-items: center; align-content: center; gap: 5px;">
    <?php
    $component->render('buttons/solid', [
        'type' => 'button',
        'designButton' => 'success',
        'size' => 'sm',
        'onclick' => 'js_gerarRelatorio();',
        'message' => 'Gerar Relatório',
        'value' => 'Gerar',
        'name' => 'btnAplicar',
        'id' => 'btnAplicar',
    ]);
    ?>
    </div>
</div>
</body>
<script>
    function js_gerarRelatorio(){
        let filtrar = document.getElementById('filtrar').value;
        jan = window.open('sys1_relatoriodeusuarios001.php?filtrar='+filtrar,
            'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
        jan.moveTo(0,0);
    }
</script>
