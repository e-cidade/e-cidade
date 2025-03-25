<?php
    require("libs/db_stdlib.php");
    require("libs/db_conecta.php");
    include("libs/db_sessoes.php");
    include("libs/db_usuariosonline.php");
    include("dbforms/db_funcoes.php");
    include("dbforms/db_classesgenericas.php");
    include("classes/db_pcparam_classe.php");

    db_postmemory($HTTP_GET_VARS);
    db_postmemory($HTTP_POST_VARS);

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <?php
        db_app::load("scripts.js", date('YmdHis'));
        db_app::load("strings.js");
        db_app::load("prototype.js");
        db_app::load("datagrid.widget.js");
        db_app::load("dbcomboBox.widget.js");
        db_app::load("dbmessageBoard.widget.js");
        db_app::load("dbtextField.widget.js");
        db_app::load("widgets/DBHint.widget.js");
        db_app::load("widgets/dbautocomplete.widget.js");
        db_app::load("widgets/windowAux.widget.js");
        db_app::load("estilos.css, grid.style.css");
        db_app::load("mask.js");
        db_app::load("form.js");
        db_app::load("estilos.bootstrap.css");
        db_app::load("sweetalert.js");
        db_app::load("just-validate.js");
        db_app::load("tabsmanager.js");
        db_app::load("accordion.js");
        db_app::load("dynamicloader.js");
    ?>
    <style>
        body {
            background-color: #CCCCCC;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }
        .container {
            margin-top: 20px; /* Espaï¿½o acima do container */
            background-color: #FFFFFF;
            padding: 20px;
            max-width: 100%; /* Largura mï¿½xima do conteï¿½do */
            width: 1024px; /* Para garantir responsividade */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra leve */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .tdleft {
            text-align: left;
        }
        .tdright {
            text-align: right;
        }
        form {
            margin-top: 10px;
        }
        select{
            width: 100%;
        }
        .DBJanelaIframeTitulo{
            text-align: left;
        }
        label{
            font-weight: bold;
        }
        .contain-buttons{
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
        }

        #tablegridListagemPorItemheader{
            width: calc(100% - 18px) !important;
        }

        #gridListagemPorItembody{
            width: 100% !important;
        }
        #gridListagemPorItembody tbody tr td:first-child{
            text-align: center !important;
        }
        #gridItensPorLotebody tbody tr td:first-child{
            text-align: center !important;
        }

        fieldset{
            padding: 10px;
        }

    </style>
</head>
<body class="container">
    <ul class="nav nav-pills" id="dynamicNav"></ul>
    <div class="mt-3" id="contentArea"></div>
</body>
</html>
<script>
  const url = 'lic_pareceres.RPC.php';
  const l200_sequencial = '<?= $l200_sequencial ?>';
  const showDocumento = '<?= $showDocumento ?>';
  
  let currentUrlForm = new URL('lic_frm_pareceres001.php', window.location.href);
  currentUrlForm.searchParams.set('l200_sequencial', l200_sequencial);

  const menus = [];
  if(l200_sequencial != null && l200_sequencial != ''){
    menus.push({ label: 'Parecer', url: currentUrlForm.toString() });
  } else {
    menus.push({ label: 'Parecer', url: currentUrlForm.toString() });
  }

  const dynamicLoader = new DynamicLoader('dynamicNav', 'contentArea', 'iso-8859-1');
  dynamicLoader.initialize(menus);

  document.getElementById('contentArea').addEventListener('contentLoaded', (event) => {
    const parser = new DOMParser();
    const doc = parser.parseFromString(event.detail.content, 'text/html');
  });

</script>