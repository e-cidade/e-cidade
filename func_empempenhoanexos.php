<?php
require 'libs/db_stdlib.php';
require 'libs/db_conecta.php';
include 'libs/db_sessoes.php';
include 'libs/db_usuariosonline.php';
include 'dbforms/db_funcoes.php';
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clempempenho = new cl_empempenho();
?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
  <link href='estilos.css' rel='stylesheet' type='text/css'>
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body>
  <form name="form2" method="post" action="" class="container">
    <fieldset>
      <legend>Dados para Pesquisa</legend>
      <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
                <tr>
                    <td>
                       <b>Seq. Empenho:</b>
                    </td>
                    <td>
                        <?php db_input('e60_numemp', 14, $Ie60_numemp, true, 'text', 4, '', 'chave_e60_numemp'); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                      <b>Nome/Razão Social:</b>
                    </td>
                    <td>
                        <?php db_input('z01_nome', 45, '', true, 'text', 4, '', 'chave_z01_nome'); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                      <b>CNPJ/CPF:</b>
                    </td>
                    <td>
                        <?php db_input('z01_cgccpf', 14, '', true, 'text', 4, '', 'chave_z01_cgccpf'); ?>
                    </td>
                </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_empenho.hide();">
  </form>
      <?php
      if (!isset($pesquisa_chave)) {
          if (isset($chave_e60_numemp) && !empty($chave_e60_numemp)) {
              $sWhere .= " and e60_numemp = $chave_e60_numemp";
          }

          if (isset($chave_z01_nome) && !empty($chave_z01_nome)) {
              $sWhere .= " and z01_nome like '$chave_z01_nome%'";
          }

          if (isset($chave_z01_cgccpf) && !empty($chave_z01_cgccpf)) {
              $sWhere .= " and z01_cgccpf like '$chave_z01_cgccpf%'";
          }

          $sql = $clempempenho->sqlEmpenhosParaAnexar($sWhere);
          echo '<div class="container">';
          echo '  <fieldset>';
          echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql, 15, '()', '', $funcao_js, '', 'NoMe', []);
          echo '  </fieldset>';
          echo '</div>';
      } else {
          $sWhere = " and e60_numemp = $pesquisa_chave";
          $rsEmpenho = $clempempenho->sql_record($clempempenho->sqlEmpenhosParaAnexar($sWhere));
          if ($clempempenho->numrows != 0) {
              db_fieldsmemory($rsEmpenho, 0);
              echo '<script>'.$funcao_js."('$e60_numemp','$e60_resumo',false);</script>";
          } else {
              echo '<script>'.$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true,true);</script>";
          }
      }
      ?>
</body>
</html>
