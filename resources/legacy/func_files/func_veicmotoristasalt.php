<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_veicmotoristas_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clveicmotoristas = new cl_veicmotoristas;
$clveicmotoristas->rotulo->label("ve05_codigo");
$clveicmotoristas->rotulo->label("ve05_codigo");
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
        <table width="35%" border="0" align="center" cellspacing="0">
          <form name="form2" method="post" action="">
            <tr>
              <td width="4%" align="right" nowrap title="<?= $Tve05_codigo ?>">
                <?= $Lve05_codigo ?>
              </td>
              <td width="96%" align="left" nowrap>
                <?
                db_input("ve05_codigo", 10, $Ive05_codigo, true, "text", 4, "", "chave_ve05_codigo");
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                <input name="limpar" type="reset" id="limpar" value="Limpar">
                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_veicmotoristas.hide();">
              </td>
            </tr>
          </form>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center" valign="top">
        <?
        $where   = "";
        $dbinner = "";
        if (isset($pessoal) && trim($pessoal) != "") {
          $dDataSistema = date('Y-m-d', db_getsession('DB_datausu'));

          if ($pessoal == "true") {
            $dbinner = " inner join rhpessoalmov on rhpessoalmov.rh02_regist = rhpessoal.rh01_regist and 
                                                        rhpessoalmov.rh02_anousu = " . db_anofolha() . " and 
                                                        rhpessoalmov.rh02_mesusu = " . db_mesfolha() . " and
                                                        rhpessoalmov.rh02_instit = " . db_getsession("DB_instit") . "
                             inner join rhregime     on rhregime.rh30_codreg     = rhpessoalmov.rh02_codreg and 
                                                        rhregime.rh30_instit     = rhpessoalmov.rh02_instit 
                             left join rhpesrescisao on rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes";
            $where   = " (rh05_recis > '{$dDataSistema}' or rh05_recis is null) and rh30_vinculo = 'A'";  // Funcionario nao pode esta demitido e deve estar ativo
          }
        }

        if (trim($where) != "") {
          $where .= "and ";
        }

        $where .= "ve41_veicmotoristas is not null and '" . date("Y-m-d", db_getsession("DB_datausu")) . "' between ve41_dtini and coalesce(ve41_dtfim,cast('9999-12-31' as date))";
        $where .= " and (ve36_coddepto = " . db_getsession("DB_coddepto") . " or 
                       ve37_coddepto = " . db_getsession("DB_coddepto") . ")";

        if (!isset($pesquisa_chave)) {
          if (isset($campos) == false) {
            if (file_exists("funcoes/db_func_veicmotoristas.php") == true) {
              include("funcoes/db_func_veicmotoristas.php");
            } else {
              $campos = "veicmotoristas.*,z01_nome";
            }
          }

          $campos = "distinct " . $campos;
          if (isset($chave_ve05_codigo) && (trim($chave_ve05_codigo) != "")) {
            if (trim(@$where) != "") {
              $where = " and " . $where;
            }

            $sql = $clveicmotoristas->sql_query_veic(null, $campos, "ve05_codigo", "ve05_codigo = $chave_ve05_codigo $where", $dbinner);
          } else {
            $sql = $clveicmotoristas->sql_query_veic("", $campos, "ve05_codigo", "$where", $dbinner);
          }
          $repassa = array();
          if (isset($chave_ve05_codigo)) {
            $repassa = array("chave_ve05_codigo" => $chave_ve05_codigo, "chave_ve05_codigo" => $chave_ve05_codigo);
          }
          db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
        } else {
          if ($pesquisa_chave != null && $pesquisa_chave != "") {
            if (trim(@$where) != "") {
              $where = " and " . $where;
            }

            $result = $clveicmotoristas->sql_record($clveicmotoristas->sql_query_veic(null, "*", null, "ve05_codigo = $pesquisa_chave $where", $dbinner));
            if ($clveicmotoristas->numrows != 0) {
              db_fieldsmemory($result, 0);
              echo "<script>" . $funcao_js . "('$z01_nome',false);</script>";
            } else {
              echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") n�o Encontrado',true);</script>";
            }
          } else {
            echo "<script>" . $funcao_js . "('',false);</script>";
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
  js_tabulacaoforms("form2", "chave_ve05_codigo", true, 1, "chave_ve05_codigo", true);
</script>