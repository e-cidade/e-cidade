<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhlotavincele_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhlotavincele = new cl_rhlotavincele;
$clrhlotavincele->rotulo->label("rh28_codlotavinc");
$clrhlotavincele->rotulo->label("rh28_codeledef");
$clrhlotavincele->rotulo->label("rh28_codelenov");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh28_codlotavinc?>">
              <?=$Lrh28_codlotavinc?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh28_codlotavinc",6,$Irh28_codlotavinc,true,"text",4,"","chave_rh28_codlotavinc");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh28_codeledef?>">
              <?=$Lrh28_codeledef?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh28_codeledef",6,$Irh28_codeledef,true,"text",4,"","chave_rh28_codeledef");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh28_codelenov?>">
              <?=$Lrh28_codelenov?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh28_codelenov",6,$Irh28_codelenov,true,"text",4,"","chave_rh28_codelenov");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhlotavincele.hide();">
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
           if(file_exists("funcoes/db_func_rhlotavincele.php")==true){
             include("funcoes/db_func_rhlotavincele.php");
           }else{
           $campos = "rhlotavincele.*";
           }
        }
        if(isset($chave_rh28_codlotavinc) && (trim($chave_rh28_codlotavinc)!="") ){
	         $sql = $clrhlotavincele->sql_query($chave_rh28_codlotavinc,$chave_rh28_codeledef,$campos,"rh28_codlotavinc");
        }else if(isset($chave_rh28_codelenov) && (trim($chave_rh28_codelenov)!="") ){
	         $sql = $clrhlotavincele->sql_query("","",$campos,"rh28_codelenov"," rh28_codelenov like '$chave_rh28_codelenov%' ");
        }else{
           $sql = $clrhlotavincele->sql_query("","",$campos,"rh28_codlotavinc#rh28_codeledef","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clrhlotavincele->sql_record($clrhlotavincele->sql_query($pesquisa_chave));
          if($clrhlotavincele->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh28_codelenov',false);</script>";
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
