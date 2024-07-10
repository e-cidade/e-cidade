<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhpessoal_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhpessoal = new cl_rhpessoal;
$clrotulo = new rotulocampo;
$clrhpessoal->rotulo->label("rh01_regist");
$clrhpessoal->rotulo->label("rh01_numcgm");
$clrotulo->label("z01_nome");
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
            <td width="4%" align="right" nowrap title="<?=$Trh01_regist?>">
              <?=$Lrh01_regist?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh01_regist",10,$Irh01_regist,true,"text",4,"","chave_rh01_regist");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh01_numcgm?>">
              <?=$Lrh01_numcgm?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh01_numcgm",10,$Irh01_numcgm,true,"text",4,"","chave_rh01_numcgm");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
            <?=$Lz01_nome?>
            </td>
            <td width="96%" align="left" nowrap colspan='3'> 
            <?
            db_input("z01_nome",80,$Iz01_nome,true,"text",4,"","chave_z01_nome");
	        ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhpessoal.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $dbwhere = "";
      if(isset($instit)){
      	$dbwhere = " and rh01_instit = $instit ";
      }
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_rhpessoal.php")==true){
             include("funcoes/db_func_rhpessoal.php");
           }else{
           $campos = "rhpessoal.*";
           }
        }

        $repassa = array("chave_z01_nome"=>@$chave_z01_nome,"chave_rh01_regist"=>@$chave_rh01_regist,"chave_rh01_numcgm"=>@$chave_rh01_numcgm,"rh01_instit"=>@$instit);
        if(isset($chave_rh01_regist) && (trim($chave_rh01_regist)!="") ){
	         $sql = $clrhpessoal->sql_query(null,$campos,"rh01_regist"," rh01_regist = $chave_rh01_regist $dbwhere");
	         //echo $sql;
             db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        }else if(isset($chave_rh01_numcgm) && (trim($chave_rh01_numcgm)!="") ){
	         $sql = $clrhpessoal->sql_query("",$campos,"rh01_numcgm"," rh01_numcgm = $chave_rh01_numcgm $dbwhere ");
	         //echo $sql;
             db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
	         $sql = $clrhpessoal->sql_query("",$campos,"z01_nome"," z01_nome like '$chave_z01_nome%' $dbwhere ");
	         //echo $sql;
             db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        }
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clrhpessoal->sql_record($clrhpessoal->sql_query(null,"*","rh01_regist"," rh01_regist = $pesquisa_chave $dbwhere"));
          if($clrhpessoal->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$z01_numcgm','$z01_nome',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('','Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('','',false);</script>";
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
