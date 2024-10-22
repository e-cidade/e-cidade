<?php

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
require_once('libs/db_utils.php');
require_once("libs/db_libpostgres.php");

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<div class="container">
  <div id="documentos_evento">
    <div class="subcontainer">
      <form name="form2" method="post" action="iss1_relnotaavulsa002.php" target="_blank">
        <fieldset>
          <legend>Relatório de Retenções</legend>
          <table>
            <tr>
              <td>
                <label for="insc_municipal" class="bold">Inscrição Municipal:</label>
              </td>
              <td>
                  <?php
                  db_input("inscricao", 14, "0", true, "text", $db_opcao, "onkeyup=\"js_ValidaCampos(this, 10, 'valor', false, null, event)\"", "", "", "", 10);
                  ?>
              </td>
            </tr>
            <tr>
              <td>
                <label for="ano" class="bold">Ano:</label>
              </td>
              <td>
                <?php
                db_input("ano", 14, "0", true, "text", $db_opcao, "onkeyup=\"js_ValidaCampos(this, 4, 'valor', false, null, event)\"", "", "", "", 4);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                  <label for="competencia" class="bold">Competência:</label>
              </td>
              <td>
                  <?php
                  $meses = array(
                      0 => "Selecione",
                      1 => "Janeiro",
                      2 => "Fevereiro",
                      3 => "Março",
                      4 => "Abril",
                      5 => "Maio",
                      6 => "Junho",
                      7 => "Julho",
                      8 => "Agosto",
                      9 => "Setembro",
                      10 => "Outubro",
                      11 => "Novembro",
                      12 => "Dezembro"
                  );
                  db_select('competencia', $meses, true, 1, "");
                  ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input type="submit" id="pesquisar" value="Gerar Relatório" />
      </form>
    </div>
  </div>
</div>
</body>
</html>
<?php
db_menu(db_getsession('DB_id_usuario'), db_getsession('DB_modulo'), db_getsession('DB_anousu'), db_getsession('DB_instit'));
?>
