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

//MODULO: educa��o
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clescoladifacesso->rotulo->label();
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_botao1 = true;
 }else{
  $db_opcao = 1;
 }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted126_i_codigo?>">
   <?=@$Led126_i_codigo?>
  </td>
  <td>
   <?db_input('ed126_i_codigo',10,$Ied126_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted126_i_escola?>">
   <?db_ancora(@$Led126_i_escola,"js_pesquisaed126_i_escola(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed126_i_escola',10,$Ied126_i_escola,true,'text',$db_opcao," onchange='js_pesquisaed126_i_escola(false);'")?>
   <?db_input('ed18_c_nome',40,@$Ied18_c_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted126_i_tipoacesso?>">
   <?db_ancora(@$Led126_i_tipoacesso,"js_pesquisaed126_i_tipoacesso(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed126_i_tipoacesso',10,$Ied126_i_tipoacesso,true,'text',$db_opcao," onchange='js_pesquisaed126_i_tipoacesso(false);'")?>
   <?db_input('ed125_c_descr',20,@$Ied125_c_descr,true,'text',3,'')?>
  </td>
</tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width="100%">
 <tr>
  <td valign="top"><br>
  <?
   $chavepri= array("ed126_i_codigo"=>@$ed126_i_codigo,"ed126_i_escola"=>@$ed126_i_escola,"ed18_c_nome"=>@$ed18_c_nome,"ed126_i_tipoacesso"=>@$ed126_i_tipoacesso,"ed125_c_descr"=>@$ed125_c_descr);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clescoladifacesso->sql_query($ed126_i_codigo,"*","ed18_c_nome");
   $cliframe_alterar_excluir->campos  ="ed126_i_escola,ed18_c_nome,ed125_c_descr";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="N�o foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="200";
   $cliframe_alterar_excluir->iframe_width ="100%";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</form>
</center>
<script>
function js_pesquisaed126_i_escola(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_escola','func_escola.php?funcao_js=parent.js_mostraescola1|ed18_i_codigo|ed18_c_nome','Pesquisa',true);
 }else{
  if(document.form1.ed126_i_escola.value != ''){
   js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_escola','func_escola.php?pesquisa_chave='+document.form1.ed126_i_escola.value+'&funcao_js=parent.js_mostraescola','Pesquisa',false);
  }else{
   document.form1.ed18_c_nome.value = '';
  }
 }
}
function js_mostraescola(chave,erro){
 document.form1.ed18_c_nome.value = chave;
 if(erro==true){
  document.form1.ed126_i_escola.focus();
  document.form1.ed126_i_escola.value = '';
 }
}
function js_mostraescola1(chave1,chave2){
 document.form1.ed126_i_escola.value = chave1;
 document.form1.ed18_c_nome.value = chave2;
 db_iframe_escola.hide();
}
function js_pesquisaed126_i_tipoacesso(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tipoacesso','func_tipoacesso.php?funcao_js=parent.js_mostratipoacesso1|ed125_i_codigo|ed125_c_descr','Pesquisa',true);
 }else{
  if(document.form1.ed126_i_tipoacesso.value != ''){
   js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tipoacesso','func_tipoacesso.php?pesquisa_chave='+document.form1.ed126_i_tipoacesso.value+'&funcao_js=parent.js_mostratipoacesso','Pesquisa',false);
  }else{
   document.form1.ed125_c_descr.value = '';
  }
 }
}
function js_mostratipoacesso(chave,erro){
 document.form1.ed125_c_descr.value = chave;
 if(erro==true){
  document.form1.ed126_i_tipoacesso.focus();
  document.form1.ed126_i_tipoacesso.value = '';
 }
}
function js_mostratipoacesso1(chave1,chave2){
 document.form1.ed126_i_tipoacesso.value = chave1;
 document.form1.ed125_c_descr.value = chave2;
 db_iframe_tipoacesso.hide();
}
</script>
