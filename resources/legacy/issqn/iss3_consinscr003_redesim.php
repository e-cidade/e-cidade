<?php
require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";

use App\Models\ISSQN\InscricaoRedesim;
use App\Models\ISSQN\RedesimLog;
use ECidade\V3\Extension\Registry;
use ECidade\V3\Extension\Request;

/**
 * @var Request $request
 */

$request = Registry::get('app.request');
$inscricaoMunicipal = $request->get()->get('inscricao');
$q190_sequencial = $request->get()->get('q190_sequencial');

$inscricaoRedesim = InscricaoRedesim::query()->where('q179_inscricao', $inscricaoMunicipal)->first();

if ($q190_sequencial) {
    $redesimLog = RedesimLog::query()->where('q190_sequencial', $q190_sequencial)->first();
}
?>

<html lang="">
<head>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="https://rawgit.com/summerstyle/jsonTreeViewer/master/libs/jsonTree/jsonTree.css" rel="stylesheet"/>
    <script src="https://rawgit.com/summerstyle/jsonTreeViewer/master/libs/jsonTree/jsonTree.js"></script>
    <style>
        .body {
            background-color: #F5FFFB !important;
            display: flex;
            justify-content: center;
            margin-top: 1rem;
            flex-direction: column;
        }

        .content {
            display: flex;
            justify-content: center;
        }

        .jsontree_tree {
            list-style: none;
        }
    </style>
</head>
<body class="body">
<div class="content">
    <?php
    if (empty($inscricaoRedesim)) {
        ?>
        <p>Nenhum registro encontrado.</p>
        <?php
    } else {
    $sql = "select q190_sequencial as dl_sequencial, q190_data as dl_data, q190_cpfcnpj as dl_cpfcnpj from redesim_log where q190_cpfcnpj = '{$inscricaoRedesim->issBase->cgm->z01_cgccpf}'";
    db_lovrot($sql, 15, "()", "", 'mostraDadosRedesim|dl_sequencial');
    ?>
</div>
<?php } ?>
<?php
if($redesimLog->q190_json) {
?>
    <fieldset>
        <legend>Registro Redesim</legend>
        <div id="wrapper"></div>
    </fieldset>
<?php } ?>
<script>
    const wrapper = document.getElementById("wrapper");
    const jsonData = <?=DBString::utf8_decode_all($redesimLog->q190_json) ?? 'null'?>;

    function mostraDadosRedesim(q190_sequencial) {
        window.location.href = `iss3_consinscr003_redesim.php?inscricao=<?=$inscricaoMunicipal?>&q190_sequencial=${q190_sequencial}`;
    }

    if (jsonData) {
        jsonTree.create(jsonData, wrapper);
    }
</script>
</body>
</html>
