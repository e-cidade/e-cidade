<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_liclicita_classe.php");
require_once("classes/db_liclicitem_classe.php");

db_postmemory($HTTP_POST_VARS);
$oPost = db_utils::postMemory($_POST);

$iAnoSessao = db_getsession("DB_anousu");
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="../../../FrontController.php" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="../../../scripts/scripts.js"></script>
</head>
<body>
<table height="100%" border="0" align="center" cellspacing="0" bgcolor="#CCCCCC">
        <tr>
        <td align="center" valign="top">
            <?php
                $sql = "SELECT DISTINCT
                pc97_sequencial as Sequencial,
                pc97_descricao AS Descricao,
                pc97_ativo AS Ativo
                FROM prazoentrega
                ORDER BY pc97_sequencial DESC";

                db_lovrot($sql, 15, "()", "", $funcao_js, null, 'NoMe', $aRepassa, false);
                ?>
            </td>
        </tr>
    </table>
</body>


