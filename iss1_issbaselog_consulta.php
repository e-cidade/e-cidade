<?php

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";

use App\Repositories\Tributario\ISSQN\IssbaseLogRepository;
use ECidade\V3\Extension\Registry;
use ECidade\V3\Extension\Request;

/**
 * @var Request $request
 */

$request = Registry::get('app.request');
$inscricaoMunicipal = $request->get()->get('q102_inscr');
$issBaseLogRepository = new IssbaseLogRepository();
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
        .body {
            background-color: #F5FFFB !important;
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }

        .content {
            display: flex;
            justify-content: center;
            flex-direction: column;
            gap: 1rem;
        }

        .details {
            background-color: #fff;
            border: 1px solid lightgray;
            padding: 0.5rem;
        }

        .textArea {
            width: 100%;
            height: 100%;
            font-size: large;
        }
    </style>
</head>
<body class="body">
<div class="content">
    <div>
        <?php
        db_lovrot($issBaseLogRepository->getLovrotSql($inscricaoMunicipal), 15, "()", "", 'mostraDadosLog|q102_obs');
        ?>
    </div>
    <div class="details">
        <textarea class="readonly textArea" id="log_details" readonly>Clique em uma linha da listagem acima para visualizar os detalhes.</textarea>
    </div>
</div>
</body>
<script>
    const textarea = document.querySelector('#log_details');

    function mostraDadosLog(q102_obs) {
        textarea.value = q102_obs;
    }
</script>
</html>
