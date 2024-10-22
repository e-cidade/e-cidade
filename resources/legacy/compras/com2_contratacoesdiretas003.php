<?php
require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("libs/db_libdocumento.php");

parse_str($_SERVER['QUERY_STRING'], $aVariaveis);
db_postmemory($_SERVER);

$clcontratacoesdiretas = new cl_contratacoesdiretas();
$aReport = $clcontratacoesdiretas->getReportData($aVariaveis);

$exercicio = $clcontratacoesdiretas->getExercise($aVariaveis);
$departamento = $clcontratacoesdiretas->getDepartment($aVariaveis);
$categoria = $clcontratacoesdiretas->getCategory($aVariaveis);

// Início do conteúdo HTML
$htmlContent = "
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        h1 { font-size: 20px; }
        h2 { font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; text-align: center; }
        th { background-color: #f2f2f2; font-weight: bold; }
    </style>
</head>
<body>
    <h1>CONTRATAÇÕES DIRETAS</h1>";

if ($exercicio !== null) {
    $htmlContent .= "<h5>PERÍODO: " . $exercicio . "</h5>";
}
if ($departamento !== null) {
    $htmlContent .= "<h5>DEPARTAMENTO(S): " . $departamento . "</h5>";
}
if ($categoria !== null) {
    $htmlContent .= "<h5>CATEGORIA(S): " . $categoria . "</h5>";
}

foreach ($aReport as $data) {
    $htmlContent .= "
    <table>
        <tr>
            <th>Processo</th>
            <th>Data</th>
            <th>Número</th>
            <th>Modalidade</th>
            <th>Categoria</th>
            <th>Departamento</th>
        </tr>
        <tr>
            <td>{$data->pc80_codproc}</td>
            <td>" . date('d/m/Y', strtotime($data->pc80_data)) . "</td>
            <td>{$data->pc80_numdispensa}</td>
            <td>" . $clcontratacoesdiretas->getTypeContracting($data->pc80_modalidadecontratacao) . "</td>
            <td>" . $clcontratacoesdiretas->getDescriptionCategoryProcess($data->pc80_categoriaprocesso) . "</td>
            <td>{$data->descrdepto}</td>
        </tr>

        <tr>
            <th colspan='6'>Objeto</th>
        </tr>
        <tr>
            <td colspan='6'>{$data->pc80_resumo}</td>
        </tr>

        <tr>
            <th colspan='3'>ID de Envio ao PNCP</th>
            <th colspan='3'>Data do Lançamento</th>
        </tr>
        <tr>
            <td colspan='3'>{$data->l213_numerocontrolepncp}</td>
            <td colspan='3'>" . date('d/m/Y', strtotime($data->l213_dtlancamento)) . "</td>
        </tr>
    </table>
    <br>";
}

$htmlContent .= "
</body>
</html>";

$exercicioSlug = str_replace([' - ',], '-ate-', $exercicio);

// Construa o nome do arquivo com espaços e underscores
$filename = "relatorio-contratacoes-diretas-de-" . $exercicioSlug ."-departamento-". $departamento ."-categoria-". $categoria . ".doc";

// Substitua espaços e underscores por hífens
$filename = str_replace([' ', '_', '/'], '-', $filename);

// Define o cabeçalho para download do arquivo Word
header("Content-Type: application/vnd.ms-word; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$filename\"");

// Certifica-se de que o conteúdo HTML está codificado em UTF-8
$htmlContent = mb_convert_encoding($htmlContent, 'UTF-8', 'ISO-8859-1');

// Envia o conteúdo HTML
echo $htmlContent;
exit;
?>
