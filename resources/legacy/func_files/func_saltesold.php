<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_saltes_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clsaltes = new cl_saltes;
$clsaltes->rotulo->label("k13_conta");
$clsaltes->rotulo->label("k13_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Tk13_conta?>">
              <?=$Lk13_conta?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("k13_conta",5,$Ik13_conta,true,"text",4,"","chave_k13_conta");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tk13_descr?>">
              <?=$Lk13_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("k13_descr",40,$Ik13_descr,true,"text",4,"","chave_k13_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_saltes.hide();">
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
           if(file_exists("funcoes/db_func_saltes.php")==true){
             include("funcoes/db_func_saltes.php");
           }else{
           $campos = "saltes.*";
           }
        }
        if(isset($chave_k13_conta) && (trim($chave_k13_conta)!="") ){
	         $sql = $clsaltes->sql_query($chave_k13_conta,$campos,"k13_conta");
        }else if(isset($chave_k13_descr) && (trim($chave_k13_descr)!="") ){
	         $sql = $clsaltes->sql_query("",$campos,"k13_descr"," k13_descr like '$chave_k13_descr%' ");
        }else{
           $sql = $clsaltes->sql_query("",$campos,"k13_conta","");
        }
	echo $sql;
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clsaltes->sql_record($clsaltes->sql_query($pesquisa_chave));
          if($clsaltes->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$k13_descr',false);</script>";
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
