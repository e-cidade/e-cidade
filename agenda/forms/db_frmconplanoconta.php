<?
//MODULO: contabilidade
$clconplanoconta->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c60_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc63_codcon?>">
       <?
       db_ancora(@$Lc63_codcon,"js_pesquisac63_codcon(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('c63_codcon',6,$Ic63_codcon,true,'text',$db_opcao," onchange='js_pesquisac63_codcon(false);'")
?>
       <?
db_input('c60_descr',50,$Ic60_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc63_banco?>">
       <?=@$Lc63_banco?>
    </td>
    <td>
<?
db_input('c63_banco',5,$Ic63_banco,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc63_agencia?>">
       <?=@$Lc63_agencia?>
    </td>
    <td>
<?
db_input('c63_agencia',5,$Ic63_agencia,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc63_conta?>">
       <?=@$Lc63_conta?>
    </td>
    <td>
<?
db_input('c63_conta',50,$Ic63_conta,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisac63_codcon(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_conplano','func_conplano.php?funcao_js=parent.js_mostraconplano1|c60_codcon|c60_descr','Pesquisa',true);
  }else{
     if(document.form1.c63_codcon.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_conplano','func_conplano.php?pesquisa_chave='+document.form1.c63_codcon.value+'&funcao_js=parent.js_mostraconplano','Pesquisa',false);
     }else{
       document.form1.c60_descr.value = '';
     }
  }
}
function js_mostraconplano(chave,erro){
  document.form1.c60_descr.value = chave;
  if(erro==true){
    document.form1.c63_codcon.focus();
    document.form1.c63_codcon.value = '';
  }
}
function js_mostraconplano1(chave1,chave2){
  document.form1.c63_codcon.value = chave1;
  document.form1.c60_descr.value = chave2;
  db_iframe_conplano.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_conplanoconta','func_conplanoconta.php?funcao_js=parent.js_preenchepesquisa|c63_codcon','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_conplanoconta.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
