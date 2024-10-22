<?php
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/db_utils.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<?php
    db_app::load("scripts.js");
    db_app::load("prototype.js");
    db_app::load("datagrid.widget.js");
    db_app::load("strings.js");
    db_app::load("grid.style.css");
    db_app::load("estilos.css");
    db_app::load("classes/dbViewAvaliacoes.classe.js");
    db_app::load("widgets/windowAux.widget.js");
    db_app::load("widgets/dbmessageBoard.widget.js");
    db_app::load("dbcomboBox.widget.js");
    db_app::load("estilos.bootstrap.css");
    db_app::load("sweetalert.js");
    db_app::load("just-validate.js");
?>
</head>

<body class="container">
    <?php
        db_menu(
            db_getsession("DB_id_usuario"),
            db_getsession("DB_modulo"),
            db_getsession("DB_anousu"),
            db_getsession("DB_instit")
        );

    include("forms/db_frmabastimportacao002.php");
    ?>
</body>

</html>
