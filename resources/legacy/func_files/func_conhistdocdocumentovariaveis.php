<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_conhistdocdocumentovariaveis_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clconhistdocdocumentovariaveis = new cl_conhistdocdocumentovariaveis;
$clconhistdocdocumentovariaveis->rotulo->label("c93_sequencial");
$clconhistdocdocumentovariaveis->rotulo->label("c93_descricao");
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
            <td width="4%" align="right" nowrap title="<?=$Tc93_sequencial?>">
              <?=$Lc93_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("c93_sequencial",10,$Ic93_sequencial,true,"text",4,"","chave_c93_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tc93_descricao?>">
              <?=$Lc93_descricao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("c93_descricao",100,$Ic93_descricao,true,"text",4,"","chave_c93_descricao");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_conhistdocdocumentovariaveis.hide();">
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
           if(file_exists("funcoes/db_func_conhistdocdocumentovariaveis.php")==true){
             include("funcoes/db_func_conhistdocdocumentovariaveis.php");
           }else{
           $campos = "conhistdocdocumentovariaveis.*";
           }
        }
        if(isset($chave_c93_sequencial) && (trim($chave_c93_sequencial)!="") ){
	         $sql = $clconhistdocdocumentovariaveis->sql_query($chave_c93_sequencial,$campos,"c93_sequencial");
        }else if(isset($chave_c93_descricao) && (trim($chave_c93_descricao)!="") ){
	         $sql = $clconhistdocdocumentovariaveis->sql_query("",$campos,"c93_descricao"," c93_descricao like '$chave_c93_descricao%' ");
        }else{
           $sql = $clconhistdocdocumentovariaveis->sql_query("",$campos,"c93_sequencial","");
        }
        $repassa = array();
        if(isset($chave_c93_descricao)){
          $repassa = array("chave_c93_sequencial"=>$chave_c93_sequencial,"chave_c93_descricao"=>$chave_c93_descricao);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clconhistdocdocumentovariaveis->sql_record($clconhistdocdocumentovariaveis->sql_query($pesquisa_chave));
          if($clconhistdocdocumentovariaveis->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$c93_descricao',false);</script>";
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
js_tabulacaoforms("form2","chave_c93_descricao",true,1,"chave_c93_descricao",true);
</script>