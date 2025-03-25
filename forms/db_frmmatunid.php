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

//MODULO: material
$clmatunid->rotulo->label();
db_app::load("estilos.bootstrap.css");
?>

<style>
    .btn {
        font-size: 12px;
        margin: 2px;
    }

    .form-group{
        padding: 5px;
    }
    .form-group label {
        margin-right: 10px;
        width: 246px;
        text-align: right;
    }

    #m61_codmatunid,#m61_abrev,#m61_usaquant,#m61_usadec,#m61_codsicom,#m61_ativo{
        width: 200px;
        font-size: 12px;
        font-family: Arial;
    }

    #m61_descr{
        width: 400px;
        font-size: 12px;
        font-family: Arial;
    }

</style>

<form name="form1" method="post" action="">
    <fieldset style="margin-bottom: 10px">
        <legend><b>Cadastro de Unidades:</b></legend>
        <div style="display: flex; flex-wrap: wrap;">
            <div class="form-group" style="flex: 0;">
                <label><strong>Código da unidade:</strong></label>
                <input title="m61_codmatunid" value="<?=$m61_codmatunid?>" name="m61_codmatunid" type="text" id="m61_codmatunid" style="background-color:#DEB887;" class="form-control">
            </div>
            <div class="form-group" style="flex: 0;">
                <label><strong>Unidade:</strong></label>
                <input title="m61_descr" value="<?=$m61_descr?>" name="m61_descr" type="text" id="m61_descr" style="background-color:#DEB887;" class="form-control">
            </div>
            <div class="form-group" style="flex: 0;">
                <label><strong>Abreviatura da descrição:</strong></label>
                <input title="m61_abrev" value="<?=$m61_abrev?>" name="m61_abrev" type="text" id="m61_abrev" style="background-color:#DEB887;" class="form-control">
            </div>
            <div class="form-group" style="flex: 0;">
                <label><strong>Se usa quantidade da unidade:</strong></label>
                <select name="m61_usaquant" id="m61_usaquant" class="custom-select">
                    <option value="t" <?php echo ($m61_usaquant == "t") ? 'selected' : ''; ?>>SIM</option>
                    <option value="f" <?php echo ($m61_usaquant == "f") ? 'selected' : ''; ?>>NÃO</option>
                </select>
            </div>
            <div class="form-group" style="flex: 0;">
                <label><strong>Aceita casas decimais:</strong></label>
                <select name="m61_usadec" id="m61_usadec" class="custom-select">
                    <option value="t" <?php echo ($m61_usadec == "t") ? 'selected' : ''; ?>>SIM</option>
                    <option value="f" <?php echo ($m61_usadec == "f") ? 'selected' : ''; ?>>NÃO</option>
                </select>
            </div>
            <div class="form-group" style="flex: 0;">
                <label><strong>Codigo Unidade no TCE:</strong></label>
                <input title="m61_codsicom" value="<?=$m61_codsicom?>" name="m61_codsicom" type="text" id="m61_codsicom" class="form-control">
            </div>
            <div class="form-group" style="flex: 0;">
                <label><strong>Ativo:</strong></label>
                <select name="m61_ativo" id="m61_ativo" class="custom-select">
                    <option value="t" <?php echo ($m61_ativo == "t") ? 'selected' : ''; ?>>SIM</option>
                    <option value="f" <?php echo ($m61_ativo == "f") ? 'selected' : ''; ?>>NÃO</option>
                </select>
            </div>
        </div>
    </fieldset>
    <div style="margin-left: 500px;">
        <?php  if ($db_opcao == 1) : ?>
            <input class="btn btn-success Secondary" name="incluir" type="submit" id="incluir" value="Incluir">
        <?php endif;?>

        <?php  if ($db_opcao == 2 || $db_opcao == 22) : ?>
            <input class="btn btn-success Secondary" name="alterar" type="submit" id="alterar" value="Alterar">
        <?php endif;?>

        <?php  if ($db_opcao == 3 || $db_opcao == 33) : ?>
            <input class="btn btn-danger" name="excluir" type="button" id="excluir" value="Excluir">
        <?php endif;?>
            <input style="width: 90px" class="btn btn-primary" name="pesquisar" onclick="js_pesquisa();" id="pesquisar" value="Pesquisar">
    </div>
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matunid','func_matunid.php?funcao_js=parent.js_preenchepesquisa|m61_codmatunid','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_matunid.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_abrev(){
  x = document.form1;
  valor = x.m61_descr.value.substr(0,6);
  if(x.m61_abrev.value==""){
    x.m61_abrev.value = valor;
  }
}
</script>
