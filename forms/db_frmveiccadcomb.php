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

//MODULO: veiculos
$clveiccadcomb->rotulo->label();
?>

<center>
<form id="form1" name="form1" method="post" action="">
<fieldset style="width: 700px; margin-top: 30px">
  <legend>Combustiveis</legend>           
  <div class="row">
    <div class="col-4 form-group">
    <b>Código do Combustível:</b> 
    <?
      db_input('ve26_codigo',10,$Ive26_codigo,true,'text',3,"",'','','',null,'form-control')
    ?>
    </div>    
    
    <div class="col-8 form-group">
    <b> Descrição do Combustível: </b> 
    <?
      db_input('ve26_descr',40,$Ive26_descr,true,'text',$db_opcao,"",'','','',null,'form-control')
    ?>
    </div>  

  </div>     

  <div style="margin-top: 10px;" class="row">
  <div class="col-4 form-group">

  <b> Combustível Padrão SICOM: </b> 
   <?php 
    $opcoesCombustivel = array("0" => "Selecione", "01" => "ALCOOL", "02" => "GASOLINA", "03" => "GAS","04" => "DIESEL");
   db_select("ve26_combustivelsicom", $opcoesCombustivel, true, $db_opcao,"class='custom-select'");
   ?>
     </div>  

  </div>  

  </fieldset>
  <div>
  <?php

          $messageButton = ($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"));
          $nameButton = ($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"));

                $component->render('buttons/solid', [
                'type' => 'submit',
                'designButton' => 'success',
                'size' => 'sm',
                'message' => $messageButton,
                'name' => $nameButton,
                'id' => 'acao',
                ]);

              $component->render('buttons/solid', [
                'type' => 'button',
                'designButton' => 'primary',
                'size' => 'sm',
                'onclick' => 'js_pesquisa();',
                'message' => "Pesquisar",
                'value' => 'Pesquisar',
                'name' => $nameButton,
                ]);
                
                ?>


  </div>

</center>

</form>

<script>

function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_veiccadcomb','func_veiccadcomb.php?funcao_js=parent.js_preenchepesquisa|ve26_codigo','Pesquisa',true);
}

function js_preenchepesquisa(chave){
  db_iframe_veiccadcomb.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

if(document.getElementById('acao').name != "incluir" && document.getElementById('ve26_codigo').value == ""){
  js_pesquisa();
}

</script>
