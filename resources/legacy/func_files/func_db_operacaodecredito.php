<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");  
include("dbforms/db_funcoes.php");
include("classes/db_db_operacaodecredito_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_operacaodecredito = new cl_db_operacaodecredito;
$cldb_operacaodecredito->rotulo->label("op01_numerocontratoopc");
$cldb_operacaodecredito->rotulo->label("op01_sequencial");

$cldb_operacaodecredito->rotulo->label(); 
$clrotulo = new rotulocampo;
$clrotulo->label("op01_numerocontratoopc");
$clrotulo->label("op01_sequencial");
$clrotulo->label("op01_dataassinaturacop"); 
?>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table height="100%" border="0" align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
      <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="left" cellspacing="0">
          <form name="form2" method="post" action="">

            <tr align="">
              <td width="4%" align="right" title="<?php echo $Top01_sequencial ?>"> <?php echo @$Lop01_sequencial ?> </td>
              <td width="96%" >
                <?
                db_input("op01_sequencial", 10, $Iop01_sequencial, true, "text", 4, "", "chave_op01_sequencial");
                ?>
              </td>
            </tr>

            <tr align="center">
              <td width="4%" align="right" title="<?php echo $Top01_numerocontratoopc ?>"> <?php echo @$Lop01_numerocontratoopc ?> </td>
              <td width="96%" >
                <?
                db_input("op01_numerocontratoopc", 30, $Iop01_numerocontratoopc, true, "text", 4, "", "chave_op01_numerocontratoopc");
                ?>
              </td>
            </tr>

            <tr align="" >
              <td width="94%"></td>
              <td width="6%" align="left">
                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                <input name="limpar" type="reset" id="limpar" value="Limpar">
                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_db_operacaodecredito.hide();">
              </td>
            </tr>
          </form>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center" valign="top">
        <?
        if (!isset($pesquisa_chave)) {

          if (isset($campos) == false) {
            if ($movimentacoes == 1){
              $campos = "
              op01_sequencial, 
              op01_numerocontratoopc, 
              op01_dataassinaturacop, 
              (select
                sum(
                case 
                  when op02_movimentacao in (1, 5) then op02_valor
                  when op02_movimentacao in (2, 3, 4) then -op02_valor
                end) as saldo
                from
                  movimentacaodedivida
                where
                  op02_operacaodecredito = op01_sequencial) as dl_saldo,
              op01_credor as db_op01_credor, 
              z01_nome as db_z01_nome, 
              z01_numcgm||' - '||z01_nome as op01_credor, 
              SUBSTR(op01_descricaodividaconsolidada, 0, 60) as op01_descricaodividaconsolidada, 
              op01_dataquitacao";

            } else if (file_exists("funcoes/db_func_db_operacaodecredito.php") == true) {
              include("funcoes/db_func_db_operacaodecredito.php");
            } else {
              $campos = "db_operacaodecredito.*";
            }
          }

          if ($movimentacoes == 1){
            $sql = $cldb_operacaodecredito->buscaDados("", $campos, "op01_sequencial", "");
          } else if(!empty($tipos_lancamento)) {
            $sql = $cldb_operacaodecredito->sql_query("", $campos, "op01_tipolancamento", " op01_tipolancamento IN(" . $tipos_lancamento . ")");
          } else if(!empty($credor) && !empty($regras)) {
            $sql = $cldb_operacaodecredito->sql_query("", $campos, "op01_credor", "op01_credor = $credor" . $regras);
          } else if (isset($chave_op01_sequencial) && (trim($chave_op01_sequencial) != "")) {
            $sql = $cldb_operacaodecredito->sql_query($chave_op01_sequencial, $campos, "op01_sequencial");
          } else if (isset($chave_op01_numerocontratoopc) && (trim($chave_op01_numerocontratoopc) != "")) {
            $sql = $cldb_operacaodecredito->sql_query("", $campos, "op01_numerocontratoopc", " op01_numerocontratoopc like '$chave_op01_numerocontratoopc%' ");
          } else {
            $sql = $cldb_operacaodecredito->sql_query("", $campos, "op01_sequencial", "");
          }

          $repassa = array();
          if (isset($chave_op01_numerocontratoopc)) {
            $repassa = array("chave_op01_sequencial" => $chave_op01_sequencial, "chave_op01_numerocontratoopc" => $chave_op01_numerocontratoopc);
          }
          db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
        } else {

          if (isset($digito) && trim($digito) != '') {

            if ($pesquisa_chave != null && $pesquisa_chave != "") {

              $result = $cldb_operacaodecredito->sql_record($cldb_operacaodecredito->sql_query($pesquisa_chave));

              if ($cldb_operacaodecredito->numrows != 0) {
                db_fieldsmemory($result, 0);
                echo "<script>" . $funcao_js . "('$op01_numerocontratoopc','$db89_digito',false);</script>";
              } else {
                echo "<script>" . $funcao_js . "('','',true);</script>";
              }

            } else {
              echo "<script>" . $funcao_js . "('','',false);</script>";
            }

          } else {
            
            if ($pesquisa_chave != null && $pesquisa_chave != "") {

              if(!empty($tipos_lancamento)) {
                $result = $cldb_operacaodecredito->sql_record($cldb_operacaodecredito->sql_query($pesquisa_chave, "*", null, " op01_sequencial = $pesquisa_chave and op01_tipolancamento IN(" . $tipos_lancamento . ")"));
              } else if(!empty($credor) && !empty($regras)) {
                $result = $cldb_operacaodecredito->sql_record($cldb_operacaodecredito->sql_query($pesquisa_chave, "*", null, "op01_credor = $credor" . $regras));
              }
   
              if ($cldb_operacaodecredito->numrows != 0) {
                db_fieldsmemory($result, 0);
                echo "<script>" . $funcao_js . "('$op01_numerocontratoopc','$op01_dataassinaturacop',false);</script>";
              } else {
                echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado',true);</script>";
              }
            } else {
              echo "<script>" . $funcao_js . "('',false);</script>";
            }
          }
        }
        ?>
      </td>
    </tr>
  </table>
</body>

</html>
<?
if (!isset($pesquisa_chave)) {
?>
  <script>
  </script>
<?
}
?>
<script>
  js_tabulacaoforms("form2", "chave_op01_numerocontratoopc", true, 1, "chave_op01_numerocontratoopc", true);
</script>