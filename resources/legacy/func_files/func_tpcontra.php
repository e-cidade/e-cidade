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
include("classes/db_tpcontra_classe.php");
include("classes/db_rhregime_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cltpcontra = new cl_tpcontra;
$clrhregime = new cl_rhregime;
$cltpcontra->rotulo->label("h13_codigo");
$cltpcontra->rotulo->label("h13_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Th13_codigo?>">
              <?=$Lh13_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h13_codigo",2,$Ih13_codigo,true,"text",4,"","chave_h13_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Th13_descr?>">
              <?=$Lh13_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h13_descr",40,$Ih13_descr,true,"text",4,"","chave_h13_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_tpcontra.hide();">
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
      if(isset($regime) && trim($regime)!=""){
      	$dbwhere = " and h13_regime = $regime ";
      }
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_tpcontra.php")==true){
             include("funcoes/db_func_tpcontra.php");
           }else{
           $campos = "tpcontra.*";
           }
        }
        if(isset($chave_h13_codigo) && (trim($chave_h13_codigo)!="") ){
	         $sql = $cltpcontra->sql_query(null,$campos,"h13_codigo","h13_codigo = $chave_h13_codigo $dbwhere");
        }else if(isset($chave_h13_descr) && (trim($chave_h13_descr)!="") ){
	         $sql = $cltpcontra->sql_query(null,$campos,"h13_descr"," h13_descr like '$chave_h13_descr%' $dbwhere ");
        }else{
           $sql = $cltpcontra->sql_query(null,$campos,"h13_codigo"," 1=1 $dbwhere");
        }
        // die($sql);
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          // die($cltpcontra->sql_query(null,"*","h13_codigo","h13_codigo = $pesquisa_chave $dbwhere"));
          $result = $cltpcontra->sql_record($cltpcontra->sql_query(null,"*","h13_codigo","h13_codigo = $pesquisa_chave $dbwhere"));
          if($cltpcontra->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$h13_descr','$h13_tpcont','$h13_tipocargo',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true,true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false,false);</script>";
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