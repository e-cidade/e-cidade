<?php
  require("libs/db_stdlib.php");
  require("libs/db_conecta.php");
  include("libs/db_sessoes.php");
  include("libs/db_usuariosonline.php");
  include("dbforms/db_funcoes.php");
  include("classes/db_tabrecjm_classe.php");
  db_postmemory($HTTP_POST_VARS);
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  $cltabrecjm = new cl_tabrecjm;
  $cltabrecjm->rotulo->label();
  $instit = db_getsession("DB_instit");
  ?>
  <html>
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
      <link href="estilos.css" rel="stylesheet" type="text/css"/>
      <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    </head>

    <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
      <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
        <tr> 
          <td height="63" align="center" valign="top">
              <table width="35%" border="0" align="center" cellspacing="0">
                <form name="form2" method="post" action="" >
                    <tr> 
                      <td width="4%" align="right" nowrap title="<?=$Tk02_codjm?>">
                        <?=$Lk02_codjm?>
                      </td>
                      <td width="96%" align="left" nowrap> 
                        <?db_input("k02_codjm",10,$Ik02_codjm,true,"text",4,"","chave_k02_codjm");?>
                      </td>
                    </tr>
                    <tr> 
                      <td colspan="2" align="center"> 
                        <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
                        <input name="limpar" type="reset" id="limpar" value="Limpar" >
                        <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_tabrecjm.hide();">
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
                  if(file_exists("funcoes/db_func_tabrecjm.php")==true){
                    include("funcoes/db_func_tabrecjm.php");
                  }else{
                    $campos = "tabrecjm.k02_codjm,tabrecjm.k02_corr,tabrecjm.k02_descrjm,tabrecjm.k02_juros";
                  }
                }
                if(isset($chave_k02_codjm) && trim($chave_k02_codjm) != ""){
                  $sql = $cltabrecjm->sql_query("",$campos,"k02_codjm","k02_codjm = $chave_k02_codjm and k02_instit = $instit");
                }else{
                  $sql = $cltabrecjm->sql_query(null,$campos,"k02_codjm","k02_instit = $instit");
                }
                  $repassa = array();
                  db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
              }else{
                if($pesquisa_chave!=null && $pesquisa_chave!=""){
                                
                  $result = $cltabrecjm->sql_record($cltabrecjm->sql_query("","*","","k02_codjm = $pesquisa_chave and k02_instit = $instit"));

                  if($cltabrecjm->numrows > 0){
                  
                    db_fieldsmemory($result,0);
                    echo "<script>".$funcao_js."('$k02_corr',false);</script>";
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