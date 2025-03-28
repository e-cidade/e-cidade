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
include("classes/db_empautoriza_classe.php");
include("classes/db_orcreservaaut_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clempautoriza = new cl_empautoriza;
$clorcreservaaut = new cl_orcreservaaut;
$clempautoriza->rotulo->label("e54_autori");
$clempautoriza->rotulo->label("e54_anousu");
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload='document.form1.chave_e54_autori.focus();'>
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Te54_autori?>">
              <?=$Le54_autori?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("e54_autori",6,$Ie54_autori,true,"text",4,"","chave_e54_autori");
		       ?>
            </td>
            <td width="4%" align="right" nowrap title="<?=$Te54_anousu?>">
              <?=$Le54_anousu?>
              <?
		       db_input("e54_anousu",4,$Ie54_anousu,true,"text",4,"","chave_e54_anousu");
		       ?>
            </td>
            <td width="4%" align="right" nowrap title="<?=$Tz01_numcgm?>">
              <?=$Lz01_numcgm?>
            </td>
            <td width="96%" align="left" nowrap> 
            <? db_input("z01_numcgm",8,$Iz01_numcgm,true,"text",4,"","chave_z01_numcgm");?>
            </td>
         </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
              <?=$Lz01_nome?>
            </td>
            <td width="96%" align="left" nowrap colspan='4'> 
            <? db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","chave_z01_nome");?>
            </td>
         </tr>
          <tr> 
            <td colspan="5" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_selempautoriza.hide();">
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
           if(file_exists("funcoes/db_func_empautoriza.php")==true){
             include("funcoes/db_func_empautoriza.php");
           }else{
           $campos = "empautoriza.*";
           }

        }
	$campos = "e54_autori,e54_emiss,e54_numcgm,z01_nome,db_usuarios.login, case when  e61_numemp is not null then e60_codemp else '' end as e60_codemp ";
	$dbwhere=" e54_anulad is not null and e54_instit = ".db_getsession("DB_instit");
        if(isset($chave_e54_autori) && (trim($chave_e54_autori)!="") ){
	   $sql = $clempautoriza->sql_query(null,$campos,"e54_autori","$dbwhere and e54_autori = $chave_e54_autori");
        }else if(isset($chave_e54_anousu) && (trim($chave_e54_anousu)!="") ){
	   $sql = $clempautoriza->sql_query("",$campos,"e54_anousu","$dbwhere and  e54_anousu like '$chave_e54_anousu%' ");
        }else if(isset($chave_z01_numcgm) && (trim($chave_z01_numcgm)!="") ){
	   $sql = $clempautoriza->sql_query("",$campos,"z01_nome"," $dbwhere and z01_numcgm = $chave_z01_numcgm");
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
	   $sql = $clempautoriza->sql_query("",$campos,"z01_nome"," $dbwhere and z01_nome like '$chave_z01_nome%' ");
        }else{
           $sql = $clempautoriza->sql_query("",$campos,"e54_autori desc",$dbwhere);
        }
        //echo $sql;
        db_lovrot($sql,13,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clempautoriza->sql_record($clempautoriza->sql_query($pesquisa_chave,$campos,"","$dbwhere"));
          if($clempautoriza->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$e54_anousu',false);</script>";
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