<?php
///MODULO: sicom
$clprecoreferencia->rotulo->label();
$clrotulo = new rotulocampo;

if ($si01_processocompra != "") {

      $respCotacaocodigo = $si01_numcgmcotacao;
      $respOrcacodigo = $si01_numcgmorcamento;
        if ($respCotacaocodigo != "") {
            $sql = "Select z01_nome from cgm where z01_numcgm = " . $respCotacaocodigo;
            $nome = db_query($sql);
            $nome = db_utils::fieldsMemory($nome, 0);
            $respCotacaonome = $nome->z01_nome;
        }

        if ($respOrcacodigo != "") {
            $sql1 = "Select z01_nome from cgm where z01_numcgm = " . $respOrcacodigo;
            $nome1 = db_query($sql1);
            $nome1 = db_utils::fieldsMemory($nome1, 0);
            $respOrcanome = $nome1->z01_nome;
        }
}
if($db_opcao == 33 || $db_opcao == 3){
    $itemmeepp = "2";
}
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

    .form-group textarea {
        flex: 1;
    }

    .form-group strong {
        margin-right: 10px;
    }

    #si01_sequencial,#si01_casasdecimais,#si01_processocompra,#si01_datacotacao,#respCotacaocodigo,#respOrcacodigo,#si01_impjustificativa,#si01_itemmeepp {
        width: 200px;
        font-size: 12px;
    }

    #si01_tipoprecoreferencia,#si01_cotacaoitem{
        width: 258px;
        font-size: 12px;
    }

    #respCotacaonome,#respOrcanome{
        width: 370px;
        font-size: 12px;
    }

    #si01_justificativa{
        font-size: 12px;
        width: 933px;
        height: 29px;
    }
</style>

<form name="form1" method="post" action="">
    <fieldset>
        <legend><b>Preço de Referência</b></legend>
        <div style="display: flex; gap: 0px;">
            <div class="form-group" style="flex: 0;">
                <label><?= @$Lsi01_sequencial ?></label>
                <input title="si01_sequencial" value="<?=$si01_sequencial?>" name="si01_sequencial" type="text" id="si01_sequencial" style="background-color:#DEB887;" class="form-control">
            </div>

            <div class="form-group" style="flex: 0; margin-left: 5px;">
                <strong>
                    <a href="#" id="ancoraprocessocompra" onclick="js_pesquisasi01_processocompra(true);">Processo de Compra:</a>
                </strong>
                <input tabindex="1" title="si01_processocompra" value="<?=$si01_processocompra?>" onchange="js_pesquisasi01_processocompra(false);" name="si01_processocompra" type="text" id="si01_processocompra" class="form-control" autocomplete="">
            </div>

            <div class="form-group" style="flex: 0;">
                <label><?= @$Lsi01_datacotacao ?></label>
                <input type="date" tabindex="2" onchange="validadata(event);" name="si01_datacotacao" title="si01_datacotacao" value="<?=$si01_datacotacao?>" id="si01_datacotacao" class="form-control" data-validate-date="true" data-validate-date-message="Data invalida">
            </div>

            <div class="form-group" style="flex: 0;">
                <label><?= @$Lsi01_tipoprecoreferencia ?></label>
                <select name="si01_tipoprecoreferencia" id="si01_tipoprecoreferencia" class="custom-select">
                    <option value="1" <?php echo ($si01_tipoprecoreferencia == "1") ? 'selected' : ''; ?>>Preço Médio</option>
                    <option value="2" <?php echo ($si01_tipoprecoreferencia == "2") ? 'selected' : ''; ?>>Maior Preço</option>
                    <option value="3" <?php echo ($si01_tipoprecoreferencia == "3") ? 'selected' : ''; ?>>Menor Preço</option>
                </select>
            </div>

            <div class="form-group" style="flex: 0;">
                <label><strong>Cotação por item:</strong></label>
                <select name="si01_cotacaoitem" id="si01_cotacaoitem" class="custom-select">
                    <option value="0" <?php echo ($si01_cotacaoitem == "0") ? 'selected' : ''; ?>>Selecione</option>
                    <option value="1" <?php echo ($si01_cotacaoitem == "1") ? 'selected' : ''; ?>>No mínimo uma cotação</option>
                    <option value="2" <?php echo ($si01_cotacaoitem == "2") ? 'selected' : ''; ?>>No mínimo duas cotação</option>
                    <option value="3" <?php echo ($si01_cotacaoitem == "3") ? 'selected' : ''; ?>>No mínimo três cotação</option>
                </select>
            </div>
        </div>

        <div class="form-group" style=" margin: 10px; display: flex; gap: 4px;">

            <div style="flex: 0;">
                <strong>
                    <a href="#" onclick="js_pesquisal31_numcgm(true,'respCotacaocodigo','respCotacaonome');">Responsável pela Cotação:</a>
                </strong>
                <div style="display: flex; gap: 5px; margin-top: 5px;">
                    <input title="respCotacaocodigo" value="<?=$respCotacaocodigo?>" onchange="js_pesquisal31_numcgm(false,'respCotacaocodigo','respCotacaonome');" name="respCotacaocodigo" type="text" id="respCotacaocodigo" class="form-control">
                    <input disabled title="respCotacaonome" value="<?=$respCotacaonome?>" name="respCotacaonome" type="text" id="respCotacaonome" style="background-color:#DEB887;" class="form-control">
                </div>
            </div>

            <div style="flex: 1;">
                <strong>
                    <a href="#" onclick="js_pesquisal31_numcgm(true,'respOrcacodigo','respOrcanome');">Resp. Recursos Orçamentários:</a>
                </strong>
                <div style="display: flex; gap: 5px; margin-top: 5px;">
                    <input title="respOrcacodigo" value="<?=$respOrcacodigo?>" name="respOrcacodigo" onchange="js_pesquisal31_numcgm(false,'respOrcacodigo','respOrcanome');" type="text" id="respOrcacodigo" class="form-control">
                    <input disabled title="respOrcanome" value="<?=$respOrcanome?>" name="respOrcanome" type="text" id="respOrcanome" style="background-color:#DEB887;" class="form-control">
                </div>
            </div>

        </div>


        <div style="display: flex; gap: 10px; margin: 10px">
            <div class="form-group" style="flex: 0;">
                <label><strong>Imprimir Justificativa: </strong></label>
                <select name="si01_impjustificativa" id="si01_impjustificativa" class="custom-select">
                    <option value="f">Não</option>
                    <option value="t">Sim</option>
                </select>
            </div>

            <div class="form-group" style="flex: 0;">
                <label><strong>Justificativa: </strong></label>
                <?php db_textarea('si01_justificativa', 7, 60, $Isi01_justificativa, true, 'text', $db_opcao, ""); ?>
            </div>
        </div>

    </fieldset>

    <div style="margin: 10px; display:flex">
        <?php  if ($db_opcao == 1) : ?>
            <input class="btn btn-success Secondary" name="incluir" type="submit" id="incluir" value="Incluir">
        <?php endif;?>

        <?php  if ($db_opcao == 2 || $db_opcao == 22) : ?>
            <input class="btn btn-success Secondary" name="alterar" type="submit" id="alterar" value="Alterar">
        <?php endif;?>

        <?php  if ($db_opcao == 3 || $db_opcao == 33) : ?>
            <input class="btn btn-danger" name="excluir" type="button" id="excluir" onclick="js_excluirPrecoReferencia();" value="Excluir">
        <?php endif;?>

        <input class="btn btn-primary" name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
        <?php
            if($db_opcao == 2 && $pc80_dispvalor == "f"):?>
            <input name="itemcota"  class="btn btn-warning" type="button" id="itemcota" value="Item Exclusivo / Cota" onclick="js_abriritemcotabutton();">
        <?php endif; ?>
        <?php
        if ($db_opcao == 2) : ?>
            <input name="imprimir" class="btn btn-primary" type="button" id="imprimir" value="Imprimir PDF" onclick="js_imprimirRelatorioPDF();">
            <input name="imprimirword" class="btn btn-primary" type="button" id="imprimirword" value="Imprimir Word" onclick="js_imprimirRelatorioWord()">
            <input name="imprimircsv" class="btn btn-primary" type="button" id="imprimircsv" value="Imprimir CSV" onclick="js_imprimirRelatorioCSV()">
        <?php endif; ?>
        <b>Qtd. de casas decimais:</b>
        <select name="si01_casasdecimais" id="si01_casasdecimais" class="custom-select">
            <option value="2" <?php if ($si01_casasdecimais == 2) echo 'selected'; ?>>2</option>
            <option value="3" <?php if ($si01_casasdecimais == 3) echo 'selected'; ?>>3</option>
            <option value="4" <?php if ($si01_casasdecimais == 4) echo 'selected'; ?>>4</option>
        </select>
    </div>
</form>

<script>
  const validator = initializeValidation('form[name="form1"]');

  function validadata(e){
      e.preventDefault()
      const isValid = validator.validate();
      if(!isValid){
       return false;
      }
  }

  var db_opcao = <?php echo $db_opcao;?>;

  js_bloqueiainputprocessodecomrpas();

  function js_bloqueiainputprocessodecomrpas(){
      if(db_opcao != 1){
          document.getElementById('si01_processocompra').readOnly = true;
      }else{
          document.getElementById('si01_processocompra').readOnly = false;
      }
  }
  function js_pesquisasi01_processocompra(mostra) {
      console.log(db_opcao);
    if(db_opcao === 1){
        if (mostra == true) {
          js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_pcproc', 'func_pcprocnovo.php?funcao_js=parent.js_mostrapcproc1|pc80_codproc', 'Pesquisa', true);
        } else {
          if (document.form1.si01_processocompra.value != '') {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_pcproc', 'func_pcprocnovo.php?pesquisa_chave=' + document.form1.si01_processocompra.value + '&funcao_js=parent.js_mostrapcproc', 'Pesquisa', false);
          }
        }
    }else{
        alert('Para alterar o processo de compras, é necessário inserir um novo preço médio.');
    }
  }

  function js_mostrapcproc(chave, erro) {
    if (erro == true) {
      document.form1.si01_processocompra.focus();
      document.form1.si01_processocompra.value = '';
    }
  }

  function js_mostrapcproc1(chave1) {
    document.form1.si01_processocompra.value = chave1;
    db_iframe_pcproc.hide();
  }

  function js_pesquisa() {
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_precoreferencia', 'func_precoreferencia.php?funcao_js=parent.js_preenchepesquisa|si01_sequencial', 'Pesquisa', true);
  }

  function js_preenchepesquisa(chave) {
    db_iframe_precoreferencia.hide();
    <?
    if ($db_opcao != 1) {
      echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) .
        "?chavepesquisa='+chave";
    } ?>
  }

  function js_redirecionaItemCota(){
      let oParams = {
          action: 'com01_itensmeepp.php',
          iInstitId: top.jQuery('#instituicoes span.active').data('id'),
          iAreaId: 4,
          iModuloId: 28
      }

      let title = 'Procedimentos > Item ME/EPP';

      Desktop.Window.create(title, oParams);
  }

  var varNumCampo;
  var varNomeCampo;

  function js_pesquisal31_numcgm(mostra, numCampo, nomeCampo) {
    varNumCampo = numCampo;
    varNomeCampo = nomeCampo;

    if (mostra == true) {
      js_OpenJanelaIframe('', 'db_iframe_cgm', 'func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome&filtro=1', 'Pesquisa', true, '0', '1');
    } else {
      numcgm = document.getElementById(numCampo).value;
      if (numcgm != '') {
        js_OpenJanelaIframe('', 'db_iframe_cgm', 'func_nome.php?pesquisa_chave=' + numcgm + '&funcao_js=parent.js_mostracgm&filtro=1', 'Pesquisa', false);
      } else {
        document.getElementById(numCampo).value = "";
      }
    }
  }

  function js_mostracgm(erro, chave) {
    document.getElementById(varNomeCampo).value = chave;
    if (erro == true) {
      document.getElementById(varNumCampo).value = "";
      document.getElementById(varNomeCampo).value = "";
      alert("Responsável não encontrado!");
    }
  }

  function js_mostracgm1(chave1, chave2) {

    document.getElementById(varNumCampo).value = chave1;
    document.getElementById(varNomeCampo).value = chave2;
    db_iframe_cgm.hide();
  }
  js_abriritemcota();
  function js_abriritemcota(){
      let itemmeepp = <?=$itemmeepp?>;

      if(itemmeepp == 1){
          let resposta = confirm('O Processo possui itens que se enquadram na lei 123/2006, deseja criar ITEM EXCLUSIVO / COTA ?');
          if (resposta) {
              let tipoprecoreferencia = document.getElementById('si01_tipoprecoreferencia').value;
              let si01_impjustificativa = document.getElementById('si01_impjustificativa').value;
              let si01_casasdecimais = document.getElementById('si01_casasdecimais').value;
              const pc80_codproc =  document.getElementById('si01_processocompra').value;
              js_OpenJanelaIframe('CurrentWindow.corpo',
                                  'db_iframe_itemcota',
                                  `com01_itensmeepp.php?pc80_codproc=${encodeURIComponent(pc80_codproc)}&tipoprecoreferencia=${encodeURIComponent(tipoprecoreferencia)}&si01_impjustificativa=${encodeURIComponent(si01_impjustificativa)}&si01_casasdecimais=${encodeURIComponent(si01_casasdecimais)}`,
                                  'Item ME EPP',
                                  true);
          }else{
              if(db_opcao === 1){
                  js_imprimirRelatorioPDF();
              }
          }
      }
  }

  function js_abriritemcotabutton(){
      const pc80_codproc =  document.getElementById('si01_processocompra').value;
      let tipoprecoreferencia = document.getElementById('si01_tipoprecoreferencia').value;
      let si01_impjustificativa = document.getElementById('si01_impjustificativa').value;
      let si01_casasdecimais = document.getElementById('si01_casasdecimais').value;
      js_OpenJanelaIframe('CurrentWindow.corpo',
          'db_iframe_itemcota',
          `com01_itensmeepp.php?pc80_codproc=${encodeURIComponent(pc80_codproc)}&tipoprecoreferencia=${encodeURIComponent(tipoprecoreferencia)}&si01_impjustificativa=${encodeURIComponent(si01_impjustificativa)}&si01_casasdecimais=${encodeURIComponent(si01_casasdecimais)}`,
          'Item exclusivo / cota',
          true);
  }

  function js_imprimirRelatorioPDF(){
      let tipoprecoreferencia = document.getElementById('si01_tipoprecoreferencia').value;
      let si01_impjustificativa = document.getElementById('si01_impjustificativa').value;
      let si01_processocompra = document.getElementById('si01_processocompra').value;
      let si01_casasdecimais = document.getElementById('si01_casasdecimais').value;
      jan = window.open('sic1_precoreferencia007.php?impjust='+si01_impjustificativa+'&codigo_preco='+si01_processocompra+'&quant_casas='+si01_casasdecimais+
          '&tipoprecoreferencia='+tipoprecoreferencia,
      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
  }

  function js_imprimirRelatorioWord(){
      let tipoprecoreferencia = document.getElementById('si01_tipoprecoreferencia').value;
      let si01_impjustificativa = document.getElementById('si01_impjustificativa').value;
      let si01_processocompra = document.getElementById('si01_processocompra').value;
      let si01_casasdecimais = document.getElementById('si01_casasdecimais').value;
      jan = window.open('sic1_precoreferencia006.php?impjust='+si01_impjustificativa+'&codigo_preco='+si01_processocompra+'&quant_casas='+si01_casasdecimais+
          '&tipoprecoreferencia='+tipoprecoreferencia,
      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
  }

  function js_imprimirRelatorioCSV(){
      let tipoprecoreferencia = document.getElementById('si01_tipoprecoreferencia').value;
      let si01_impjustificativa = document.getElementById('si01_impjustificativa').value;
      let si01_processocompra = document.getElementById('si01_processocompra').value;
      let si01_casasdecimais = document.getElementById('si01_casasdecimais').value;

      jan = window.open('sic1_precoreferencia005.php?impjust='+si01_impjustificativa+'&codigo_preco='+si01_processocompra+'&quant_casas='+si01_casasdecimais+
          '&tipoprecoreferencia='+tipoprecoreferencia,
      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
  }

  function js_excluirPrecoReferencia(){
      const oParam = {}
      let si01_sequencial = document.getElementById('si01_sequencial').value;
      let si01_processocompra = document.getElementById('si01_processocompra').value;
      oParam.exec = "excluirPrecoReferencia";
      oParam.si01_sequencial = si01_sequencial;
      oParam.si01_processocompra = si01_processocompra;
      let oAjax = new Ajax.Request('sic1_precoreferencia.RPC.php', {
          method: 'post',
          parameters: 'json=' + Object.toJSON(oParam),
          onComplete: js_retornoexclusaoprecomedio
      });
  }

  function js_retornoexclusaoprecomedio(oAjax){
      let oRetorno = eval("(" + oAjax.responseText + ")");
      if (oRetorno.status === 2){
          alert(oRetorno.message.urlDecode());
      }else{
          alert('Excluido com sucesso!!');
          js_pesquisa();
      }
  }
</script>
