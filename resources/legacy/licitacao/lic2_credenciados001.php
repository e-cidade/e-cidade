<?php
require 'libs/db_stdlib.php';
require 'libs/db_conecta.php';
include 'libs/db_sessoes.php';
include 'libs/db_usuariosonline.php';
include 'dbforms/db_funcoes.php';
require_once 'libs/renderComponents/index.php';

db_postmemory($HTTP_POST_VARS);
?>

<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <form name="form1" method="post" action="">
    <div style="width: 100%; display: flex; justify-content: center; align-items: center; height: 100vh;">

      <!-- Start Card Component -->
      <?php $component->render('cards/simple/start', ['title' => 'Credenciados'], true); ?>

      <div style="display: flex; justify-content: center; align-items: center; align-content: center; gap: 5px;">
          <div style="font-size: 12px;margin-top: -25px;"> <?php db_ancora('Licitação:', 'js_pesquisa_liclicita(true);', 1); ?></div>
          <?php
              db_input('l20_codigo', 30, 1, true, 'text', 1, " onchange='js_pesquisa_liclicita();'", '', '', '', null, 'form-control');
          ?>
      </div>
      <hr style="margin: 0px 0px 10px 0px;">

      <!-- Radios -->
      <div style="display: flex; justify-content: center; align-items: center; align-content: center; gap: 5px;">

        <!-- Radio Component -->
        <?php $component->render('inputs/radios/bordered', ['label' => 'Pdf', 'id' => 'pdf', 'value' => 'pdf', 'name' => 'tipo', 'checked' => true]); ?>

        <!-- Radio Component -->
        <?php $component->render('inputs/radios/bordered', ['label' => 'Word', 'id' => 'word', 'value' => 'word', 'name' => 'tipo']); ?>

        <!-- Radio Component -->
        <?php $component->render('inputs/radios/bordered', ['label' => 'Excel', 'id' => 'excel', 'value' => 'excel', 'name' => 'tipo']); ?>
      </div>

      <div style="display: flex; justify-content: center; align-items: center; align-content: center; gap: 5px;">
        <!-- Button Solid Component -->
        <?php $component->render('buttons/solid', [
          'type' => 'button',
          'designButton' => 'success',
          'size' => 'md',
          'onclick' => 'js_gerarRelatorio();',
          'message' => 'Gerar Relatório',
        ]); ?>
      </div>

      <!-- End Card Component -->
      <?php $component->render('cards/simple/end', [], true); ?>
    </div>
  </form>

<?php db_menu(db_getsession('DB_id_usuario'), db_getsession('DB_modulo'), db_getsession('DB_anousu'), db_getsession('DB_instit')); ?>

<script>
  function js_pesquisa_liclicita(mostra) {
      if (mostra == true) {
          js_OpenJanelaIframe(
              'top.corpo',
              'db_iframe_liclicita',
              'func_liclicita.php?relatoriocredenciamento=true&funcao_js=parent.js_mostraliclicita1|l20_codigo',
              'Pesquisa',
              true
          );
          return true;
      }

      if (document.form1.l20_codigo.value != '') {
          js_OpenJanelaIframe(
              'top.corpo',
              'db_iframe_liclicita',
              'func_liclicita.php?relatoriocredenciamento=true&pesquisa_chave=' + document.form1.l20_codigo.value + '&funcao_js=parent.js_mostraliclicita',
              'Pesquisa',
              false
          );
          return true;
      }

      document.form1.l20_codigo.value = '';
  }

  function js_mostraliclicita(chave, erro) {
      if (erro == true) {
          alert('Usuário: Licitação não encontrada.');
          document.form1.l20_codigo.value = '';
          document.form1.l20_codigo.focus();
      }
  }

  function js_mostraliclicita1(chave1) {
      document.form1.l20_codigo.value = chave1;
      db_iframe_liclicita.hide();
  }

  function js_gerarRelatorio() {
      if (document.form1.l20_codigo.value == '') {
          return alert('Usuário: Selecione a licitação.');
      }

      if (document.getElementById('pdf').checked == true) {
          var jan = window.open(
              'lic2_credenciadospdf002.php?l20_codigo=' + document.form1.l20_codigo.value,
              '',
              'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 '
          );
          jan.moveTo(0, 0);
      }

      if (document.getElementById('word').checked == true) {
          var jan = window.open(
              'lic2_credenciadosword002.php?l20_codigo=' + document.form1.l20_codigo.value,
              '',
              'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 '
          );
          jan.moveTo(0, 0);
      }

      if (document.getElementById('excel').checked == true) {
          var jan = window.open(
              'lic2_credenciadosexcel002.php?l20_codigo=' + document.form1.l20_codigo.value,
              '',
              'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 '
          );
          jan.moveTo(0, 0);
      }
  }
</script>
</body>
</html>