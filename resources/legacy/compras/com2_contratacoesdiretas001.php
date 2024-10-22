<?php
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
require_once 'libs/renderComponents/index.php';

$clcontratacoesdiretas = new cl_contratacoesdiretas();
$aDepartamentos = $clcontratacoesdiretas->getDepartmentsSelect($_SESSION['DB_instit']);

db_postmemory($HTTP_POST_VARS);
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

  function fn_loadReport() {
    validatePreReport(function() {
      // Pegando os valores dos campos
      var departamento = document.getElementById("departamentos");
      var categoria = document.getElementById("categorias");
      var dataInicial = document.getElementById("data_inicial");
      var dataFinal = document.getElementById("data_final");

      if (dataInicial.value == "") {
        alert("Campo Data Inicial é Obrigatório!");
        return;
      }

      // Pegando o valor do radio button selecionado
      var tipo = document.querySelector('input[name="tipo"]:checked');

      // Construindo a URL com os parâmetros
      if (tipo.value == "pdf") {
        var url = 'com2_contratacoesdiretas002.php';
      } else {
        var url = 'com2_contratacoesdiretas003.php';
      }
      url += '?departamento=' + encodeURIComponent(departamento.value);
      url += '&categoria=' + encodeURIComponent(categoria.value);
      url += '&data_inicial=' + encodeURIComponent(dataInicial.value);
      url += '&data_final=' + encodeURIComponent(dataFinal.value);
      url += '&tipo=' + encodeURIComponent(tipo.value);

      // Abrindo a nova janela com a URL construída
      var jan = window.open(url);
      jan.moveTo(0, 0);
    })
  }
</script>

<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="../../../scripts/scripts.js"></script>
  <link href="../../../FrontController.php" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

  <div style="width: 100%; display: flex; justify-content: center; align-items: center; height: 100vh;">

    <!-- Start Card Component -->
    <?php $component->render('cards/simple/start', ['title' => 'Contratações Diretas'], true) ?>

    <!-- Select Component Departamentos -->
    <?php $component->render('inputs/selects/choices/simple', [
      'id' => 'departamentos',
      'name' => 'departamentos',
      'label' => 'Departamentos:',
      'options' => $aDepartamentos,
      'size' => 'sm'
    ]) ?>

    <!-- Select Component Categorias -->
    <?php $component->render('inputs/selects/choices/simple', [
      'id' => 'categorias',
      'name' => 'categorias',
      'label' => 'Categorias:',
      'startIndex' => 1,
      'size' => 'sm',
      'options' => [
        '1 - Cessão',
        '2 - Compras',
        '3 - Informática (TIC)',
        '4 - Internacional',
        '5 - Locação Imóveis',
        '6 - Mão de Obra',
        '7 - Obras',
        '8 - Serviços',
        '9 - Serviços de Engenharia',
        '10 - Serviços de Saúde'
      ],
    ]) ?>

    <!-- Dates -->
    <div style="display: flex; justify-content: space-around; align-items: center; align-content: center; gap: 5px;">

      <!-- Date Component -->
      <?php $component->render('inputs/date/simple', [
        'id' => 'data_inicial',
        'placeholder' => 'Escolha uma data inicial',
        'name' => 'data_inicial',
        'required' => true,
        'label' => 'Data Inicial:'
      ]) ?>

      <!-- Date Component -->
      <?php $component->render('inputs/date/simple', [
        'id' => 'data_final',
        'placeholder' => 'Escolha uma data final',
        'name' => 'data_final',
        'label' => 'Data Final:'
      ]) ?>

    </div>

    <hr style="margin: 0px 0px 10px 0px;">

    <!-- Radios -->
    <div style="display: flex; justify-content: center; align-items: center; align-content: center; gap: 5px;">
      <!-- Radio Component -->
      <?php $component->render('inputs/radios/bordered', ['label' => 'Pdf', 'id' => 'pdf', 'value' => 'pdf', 'name' => 'tipo', 'checked' => true]) ?>

      <!-- Radio Component -->
      <?php $component->render('inputs/radios/bordered', ['label' => 'Word', 'id' => 'word', 'value' => 'word', 'name' => 'tipo']) ?>
    </div>

    <div style="display: flex; justify-content: center; align-items: center; align-content: center; gap: 5px;">
      <!-- Button Solid Component -->
      <?php $component->render('buttons/solid', [
        'designButton' => 'success',
        'size' => 'md',
        'onclick' => 'fn_loadReport();',
        'message' => 'Gerar Relatório'
      ]); ?>
    </div>

    <!-- End Card Component -->
    <?php $component->render('cards/simple/end', [], true) ?>

  </div>

  <? db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>

  <script>
    function validatePreReport(callback) {
      // Pegando os valores dos campos
      var departamento = document.getElementById("departamentos").value;
      var categoria = document.getElementById("categorias").value;
      var dataInicial = document.getElementById("data_inicial").value;
      var dataFinal = document.getElementById("data_final").value;

      // Parâmetros para enviar via AJAX
      var oParam = {
        exec: 'validateReportData',
        departamento: departamento,
        categoria: categoria,
        data_inicial: dataInicial,
        data_final: dataFinal
      };

      // URL para o script PHP
      var sUrlRpc = 'con2_contratacoesdiretas.RPC.php';

      // Requisição AJAX utilizando Prototype.js
      var oAjax = new Ajax.Request(sUrlRpc, {
        method: 'post',
        parameters: 'json=' + JSON.stringify(oParam),
        onComplete: function(response) {
          // Convertendo a resposta JSON
          var data = response.responseText.evalJSON();
          console.log(data);
          if (data.status == 2) {
            alert('Nenhum registro encontrado para os critérios informados.');
          } else {
            callback(); // Chama a função de callback para continuar o processo
          }
        },
        onFailure: function() {
          alert('Ocorreu um erro na comunicação com o servidor.');
        }
      });
    }
  </script>
</body>

</html>
