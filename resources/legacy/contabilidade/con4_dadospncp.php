<?php
require_once("dbforms/db_funcoes.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");

$oDaoRotulo    = new rotulocampo;
$oGet          = db_utils::postMemory($_GET);

$sql = "SELECT
            ac213_contrato,
            ac213_usuario,
            db_usuarios.nome AS usuario_nome,
            ac16_tipopagamento,
            ac16_numparcela,
            ac16_vlrparcela,
            ac16_urlcipi,
            ac16_identificadorcipi,
            ac16_infcomplementares,
            ac16_datapublicacao,
            ac213_numerocontrolepncp,
            CASE
                WHEN ac16_tipopagamento = 1 THEN 'Conforme Demanda'
                WHEN ac16_tipopagamento = 2 THEN 'Conforme Mensal'
                ELSE 'Outro Tipo'
            END AS tipo_pagamento
        FROM
            acordo
        INNER JOIN
            acocontratopncp ON ac213_contrato = ac16_sequencial
        LEFT JOIN
            db_usuarios ON db_usuarios.id_usuario = ac213_usuario
        WHERE
            ac16_sequencial = $ac16_sequencial;";


$rsResultDados =  db_query($sql);
$oDadosPNCP = db_utils::fieldsMemory($rsResultDados,0);

$dataPublicacao = implode('/',array_reverse(explode('-',$oDadosPNCP->ac16_datapublicacao)));



?>
<html>
<head>
    <title>Contass Consultoria Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
</head>
<style>
    .background {
        background-color: #FFFFFF;
    }
    .wrap-text {
        word-wrap: break-word;
        word-break: break-word;
        max-width: 100%;
        white-space: normal;
        padding: 5px;
        background-color: #FFFFFF;
    }
</style>
<body bgcolor="#cccccc" onload="">
<div style="display: table; float: left; margin-left: 10%;">
    <fieldset>
        <legend><b>Dados PNCP</b></legend>
        <table>
            <tr>
                <td><strong>Id Contratação:</strong></td>
                <td class="background"><?=$oDadosPNCP->ac213_numerocontrolepncp?></td>
                <td><strong>Tipo de Pagamento:</strong></td>
                <td class="background"><?=$oDadosPNCP->tipo_pagamento?></td>
            </tr>
            <tr>
                <td><strong>Nº Parcela:</strong></td>
                <td class="background"><?=$oDadosPNCP->ac16_numparcela?></td>
                <td><strong>Valor Parcela:</strong></td>
                <td class="background"><?=$oDadosPNCP->ac16_vlrparcela?></td>
            </tr>
            <tr>
                <td><strong>Usuário:</strong></td>
                <td class="background"><?=$oDadosPNCP->ac213_usuario . ' - ' . $oDadosPNCP->usuario_nome?></td>
                <td><strong>Data Publicação:</strong></td>
                <td class="background"><?=$dataPublicacao?></td>
            </tr>
            <tr>
                <td><strong>URL CIPI:</strong></td>
                <td style="width: 740px" class="background"><?=$oDadosPNCP->ac16_urlcipi?></td>
                <td><strong>Identificador CIPI:</strong></td>
                <td style="width: 300px" class="background"><?=$oDadosPNCP->ac16_identificadorcipi?></td>
            </tr>
            <tr>
                <td><strong>Informações Complementares:</strong></td>
                <td class="wrap-text" colspan="3">
                    <?= htmlspecialchars($oDadosPNCP->ac16_infcomplementares, ENT_QUOTES, 'UTF-8') ?>
                </td>
            </tr>
        </table>
    </fieldset>
</div>
</body>
</html>

