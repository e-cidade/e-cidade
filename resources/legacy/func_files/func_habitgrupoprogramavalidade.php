<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_habitgrupoprogramavalidade_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clhabitgrupoprogramavalidade = new cl_habitgrupoprogramavalidade;
$clhabitgrupoprogramavalidade->rotulo->label("ht04_sequencial");
$clhabitgrupoprogramavalidade->rotulo->label("ht04_habitgrupoprograma");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tht04_sequencial?>">
              <?=$Lht04_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ht04_sequencial",10,$Iht04_sequencial,true,"text",4,"","chave_ht04_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tht04_habitgrupoprograma?>">
              <?=$Lht04_habitgrupoprograma?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ht04_habitgrupoprograma",10,$Iht04_habitgrupoprograma,true,"text",4,"","chave_ht04_habitgrupoprograma");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_habitgrupoprogramavalidade.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_habitgrupoprogramavalidade.php")==true){
             include("funcoes/db_func_habitgrupoprogramavalidade.php");
           }else{
           $campos = "habitgrupoprogramavalidade.*";
           }
        }
        if(isset($chave_ht04_sequencial) && (trim($chave_ht04_sequencial)!="") ){
	         $sql = $clhabitgrupoprogramavalidade->sql_query($chave_ht04_sequencial,$campos,"ht04_sequencial");
        }else if(isset($chave_ht04_habitgrupoprograma) && (trim($chave_ht04_habitgrupoprograma)!="") ){
	         $sql = $clhabitgrupoprogramavalidade->sql_query("",$campos,"ht04_habitgrupoprograma"," ht04_habitgrupoprograma like '$chave_ht04_habitgrupoprograma%' ");
        }else{
           $sql = $clhabitgrupoprogramavalidade->sql_query("",$campos,"ht04_sequencial","");
        }
        $repassa = array();
        if(isset($chave_ht04_habitgrupoprograma)){
          $repassa = array("chave_ht04_sequencial"=>$chave_ht04_sequencial,"chave_ht04_habitgrupoprograma"=>$chave_ht04_habitgrupoprograma);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clhabitgrupoprogramavalidade->sql_record($clhabitgrupoprogramavalidade->sql_query($pesquisa_chave));
          if($clhabitgrupoprogramavalidade->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ht04_habitgrupoprograma',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_ht04_habitgrupoprograma",true,1,"chave_ht04_habitgrupoprograma",true);
</script>