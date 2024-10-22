<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_liclicita_classe.php");
require_once("classes/db_liclicitem_classe.php");

db_postmemory($_GET);
db_postmemory($_POST);
$oGet = db_utils::postMemory($_GET);

parse_str($_SERVER["QUERY_STRING"]);

$clliclicitem = new cl_liclicitem;
$clliclicita  = new cl_liclicita;

$clliclicita->rotulo->label("l20_codigo");
$clliclicita->rotulo->label("l20_numero");
$clliclicita->rotulo->label("l20_edital");
$clrotulo = new rotulocampo;
$clrotulo->label("l03_descr");
$iAnoSessao = db_getsession("DB_anousu");
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href='estilos.css' rel='stylesheet' type='text/css'>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body>
<table height="100%" border="0" align="center" cellspacing="0" bgcolor="#CCCCCC">
        <tr>
        <td align="center" valign="top">
            <?php
                $sql = "SELECT DISTINCT l20_codigo,
                l20_edital,
                l03_descr,
                l20_numero,
                l20_nroedital,
                l20_objeto
                FROM liclicita
                INNER JOIN cflicita ON liclicita.l20_codtipocom = cflicita.l03_codigo
                INNER JOIN pcorcamfornelic ON l20_codigo = pc31_liclicita
                AND l20_lances = 't'
                WHERE l20_licsituacao = 0
                AND l20_instit = " . db_getsession("DB_instit") . "
                ORDER BY l20_codigo DESC";

                db_lovrot($sql, 15, "()", "", $funcao_js, null, 'NoMe', $aRepassa, false);
                ?>
            </td>
        </tr>
    </table>
</body>


