<?php
  require("libs/db_stdlib.php");
  require("libs/db_conecta.php");
  include("libs/db_sessoes.php");
  include("libs/db_usuariosonline.php");
  include("dbforms/db_funcoes.php");
  include("dbforms/db_classesgenericas.php");
  include("classes/db_pcparam_classe.php");

  db_postmemory($HTTP_GET_VARS);
  db_postmemory($HTTP_POST_VARS);
?>
<form action="" method="post" name="form1" id="frmPareceres">
  <input type="hidden" name="exec" id="exec" value="<?= !empty($l200_sequencial)? 'atualizaParecer' : 'inserirParecer' ?>">
  <fieldset>
    <legend>Parecer da Licitação</legend>
    <div class="row">
      <div class="col-12 col-sm-2 form-group mb-2">
        <label for="si06_sequencial">Cód. Sequencial:</label>
        <?php
          db_input(
            'l200_sequencial',
            10,
            $l200_sequencial,
            true,
            'text',
            3,
            '',
            '',
            '',
            '',
            '',
            'form-control'
          );
        ?>
      </div>
      <div class="col-12 col-sm-10 form-group mb-2">
        <label for="l200_licitacao">
          <?php if(empty($l200_sequencial)): ?>
            <?php db_ancora("Licitação:", "pesquisaLicitacao(true);", 1) ?>
          <?php else:?>
            <label for="l200_licitacao">Licitação:</label>
          <?php endif; ?>
        </label>
        <div class="row">
          <div class="col-12 col-sm-4 mb-2 pl-0">
            <?php
              db_input(
                'l200_licitacao',
                4,
                '',
                true,
                'text',
                (empty($l200_sequencial) ? 1 : 3),
                (empty($l200_sequencial) ? " onchange='pesquisaLicitacao(false);' " : ''),
                '',
                '',
                '',
                '',
                'form-control',
                [
                  'validate-required'                 => "true",
                  'validate-minlength'                => "1",
                  'validate-maxlength'                => '10',
                  'validate-no-special-chars'         => 'true',
                  'validate-integer'                  => 'true',
                  'validate-required-message'         => "O campo Licitação não foi informado",
                  'validate-minlength-message'        => "O código deve ter pelo menos 1 caracteres",
                  'validate-maxlength-message'        => 'O campo Licitação deve ter no máximo 10 caracteres',
                  'validate-no-special-chars-message' => 'O campo Licitação não deve conter aspas simples, ponto e vírgula ou porcentagem',
                  'validate-integer-message'          => 'O campo Licitação deve conter apenas numeros'
                ]
            );
            ?>
          </div>
          <div class="col-12 col-sm-8 mb-2 pl-0">
            <?php
              db_input(
                'pc50_descr',
                10,
                '',
                true,
                'text',
                3,
                '',
                '',
                '',
                '',
                '',
                'form-control',
                [
                  'validate-required'          => "true",
                  'validate-minlength'         => "1",
                  'validate-required-message'  => "O campo Licitação não foi informado",
                  'validate-minlength-message' => "O campo Licitação deve ter pelo menos 1 caracteres",
                ]
              );
            ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-sm-2 form-group mb-2">
        <label for="l20_edital">Número Processo:</label>
        <?php
          db_input(
            'l20_edital',
            4,
            '',
            true,
            'text',
            3,
            "",
            '',
            '',
            '',
            '',
            'form-control',
            [
              'validate-required'                 => "true",
              'validate-minlength'                => "1",
              'validate-maxlength'                => '10',
              'validate-no-special-chars'         => 'true',
              'validate-integer'                  => 'true',
              'validate-required-message'         => "O campo Número Processo não foi informado",
              'validate-minlength-message'        => "O código deve ter pelo menos 1 caracteres",
              'validate-maxlength-message'        => 'O campo Número Processo deve ter no máximo 10 caracteres',
              'validate-no-special-chars-message' => 'O campo Número Processo não deve conter aspas simples, ponto e vírgula ou porcentagem',
              'validate-integer-message'          => 'O campo Número Processo deve conter apenas numeros'
            ]
          );
        ?>
      </div>
      <div class="col-12 col-sm-7 form-group mb-2">
        <label for="dl_modalidade">Modalidade:</label>
        <?php
          db_input(
            'dl_modalidade',
            4,
            '',
            true,
            'text',
            3,
            "",
            '',
            '',
            '',
            '',
            'form-control',
            [
              'validate-required'                 => "true",
              'validate-minlength'                => "1",
              'validate-no-special-chars'         => 'true',
              'validate-integer'                  => 'true',
              'validate-required-message'         => "O campo Modalidade não foi informado",
              'validate-minlength-message'        => "O código deve ter pelo menos 1 caracteres",
              'validate-no-special-chars-message' => 'O campo Modalidade não deve conter aspas simples, ponto e vírgula ou porcentagem',
              'validate-integer-message'          => 'O campo Modalidade deve conter apenas numeros'
            ]
          );
        ?>
      </div>
      <div class="col-12 col-sm-3 form-group mb-2">
      <label for="l20_numero">Númeração Modalidade:</label>
        <?php
          db_input(
            'l20_numero',
            4,
            '',
            true,
            'text',
            3,
            "",
            '',
            '',
            '',
            '',
            'form-control',
            [
              'validate-required'                 => "true",
              'validate-minlength'                => "1",
              'validate-maxlength'                => '10',
              'validate-no-special-chars'         => 'true',
              'validate-integer'                  => 'true',
              'validate-required-message'         => "O campo Númeração Modalidade não foi informado",
              'validate-minlength-message'        => "O código deve ter pelo menos 1 caracteres",
              'validate-maxlength-message'        => 'O campo Númeração Modalidade deve ter no máximo 10 caracteres',
              'validate-no-special-chars-message' => 'O campo Númeração Modalidade não deve conter aspas simples, ponto e vírgula ou porcentagem',
              'validate-integer-message'          => 'O campo Númeração Modalidade deve conter apenas numeros'
            ]
          );
        ?>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-sm-4 form-group mb-2">
        <label for="l200_tipoparecer">Tipo de Parecer:</label>
        <select 
          name="l200_tipoparecer" 
          id="l200_tipoparecer" 
          class="custom-select"
          data-validate-required="true"
          data-validate-required-message="O campo Tipo de Parecer não foi informado"
        >
          <option value="">Selecione</option>
          <option value="1">Técnico</option>
          <option value="2">Juridico - Edital</option>
          <option value="3">Juridico - Julgamento</option>
          <option value="4">Juridico - Outros</option>
        </select>
      </div>
      <div class="col-12 col-sm-3 form-group mb-2">
        <label for="l200_data">Data do Parecer:</label>
        <?php
          db_input(
              'l200_data',
              4,
              '',
              true,
              'date',
              1,
              "",
              '',
              '',
              '',
              '',
              'form-control',
              [
                'validate-required' => 'true',
                'validate-date' => 'true',
                'validate-date-message' => 'O campo Data do Parecer não foi informado'
              ]
          );
        ?>
      </div>
    </div>
    <div class="row">
      <div class="col-12 form-group mb-2">
      <label for="l200_numcgm">
          <?php db_ancora('Resp. pela Parecer:', "pesquisaCgm(true, 'l200_numcgm', 'z01_nome');", 1) ?>
        </label>
        <div class="row">
          <div class="col-12 col-sm-3 mb-2 pl-0">
            <?php
              db_input(
                'l200_numcgm',
                10,
                '',
                true,
                'text',
                1,
                " onchange=\"pesquisaCgm(false, 'l200_numcgm', 'z01_nome');\" ",
                '',
                '',
                '',
                '',
                'form-control',
                [
                  'validate-maxlength'                => '10',
                  'validate-no-special-chars'         => 'true',
                  'validate-integer'                  => 'true',
                  'validate-required'                 => "true",
                  'validate-minlength'                => "1",
                  'validate-required-message'         => "O campo Resp. pela Parecer não foi informado",
                  'validate-minlength-message'        => "O código deve ter pelo menos 1 caracteres",
                  'validate-maxlength-message'        => 'O Resp. pela Parecer deve ter no máximo 10 caracteres',
                  'validate-no-special-chars-message' => 'O Resp. pela Parecer não deve conter aspas simples, ponto e vírgula ou porcentagem',
                  'validate-integer-message'          => 'O campo Resp. pela Parecer deve conter apenas numeros',
                ]
              );
            ?>
          </div>
          <div class="col-12 col-sm-9 mb-2 pr-0">
            <?php
              db_input(
                'z01_nome',
                45,
                '',
                true,
                'text',
                3,
                '',
                '',
                '',
                '',
                '',
                'form-control',
                [
                  'validate-required'          => "true",
                  'validate-minlength'         => "1",
                  'validate-required-message'  => "O campo Resp. pela Parecer não foi informado",
                  'validate-minlength-message' => "O nome do Resp. pela Parecer deve ter pelo menos 1 caracteres",
                ]
              );
            ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row" id="containDescricaoTipoParecer" style="display: none;">
      <div class="col-12 col-sm-12 form-group mb-2">
        <label for="l200_descrparecer">Descrição do Tipo de Parecer:</label>
        <?php
          db_textarea(
            'l200_descrparecer',
            0,
            1,
            '',
            true,
            'text',
            '1',
            'onkeydown="disabledQuebraLinha(event)"',
            '',
            '',
            150,
            'form-control-plaintext form-control-lg',
            [
              'validate-required' => 'false',
              // 'validate-minlength' => '10',
              'validate-no-special-chars' => 'false',
              'validate-required-message' => 'A Descrição do Tipo de Parecer não foi informado',
              'validate-minlength-message' => 'A Descrição do Tipo de Parecer deve ter pelo menos 10 caracteres',
              'validate-no-special-chars-message' => 'A Descrição do Tipo de Parecer não deve conter aspas simples, ponto e vírgula ou porcentagem'
            ]
          );
        ?>
      </div>
    </div>
  </fieldset>
  <div class="row">
    <div class="col-12 text-center mt-4 mb-2">
      <?php if(!empty($l200_sequencial)): ?>
        <button type="submit" class="btn btn-success" id="btnAtualizar">Alterar</button>
      <?php else: ?>
        <button type="submit" class="btn btn-success" id="btnSalvar">Incluir</button>
      <?php endif; ?>
      <button class="btn btn-danger" type="button" id="btnCancelar">Cancelar</button>
    </div>
  </div>
</form>
<script>
  (function() {
    const btnSalvar = document.getElementById('btnSalvar');
    const btnAtualizar = document.getElementById('btnAtualizar');
    const btnCancelar = document.getElementById('btnCancelar');

    const validator = initializeValidation('#frmPareceres');
    const eventChange = new Event('change');
    const eventInput = new Event('input');
    const selectTipoParecer = document.getElementById('l200_tipoparecer');

    const inputsValidateInteger = [
      'l200_numcgm',
      'l200_licitacao'
    ];

    inputsValidateInteger.forEach(function(value, index) {
      document.getElementById(value).addEventListener('input', function(e){
        validateChangeInteger(value)
      });
    });

    function pesquisaLicitacao(mostra){
      if(mostra==true){
        let sUrl = 'func_liclicita.php?situacao=1&parecer=true&funcao_js=parent.carregaLicitacaoAncora|l20_codigo|l20_numero|l20_edital|pc50_descr|dl_modalidade|l20_objeto';
        if(typeof db_iframe_liclicita != 'undefined'){
          db_iframe_liclicita.jan.location.href = sUrl;
          db_iframe_liclicita.setAltura("100%");
          db_iframe_liclicita.setLargura("100%");
          db_iframe_liclicita.setPosX("0");
          db_iframe_liclicita.setPosY("0");
          db_iframe_liclicita.setCorFundoTitulo('#316648');
          db_iframe_liclicita.show();
          return false;
        }
        
        let frame = js_OpenJanelaIframe(
          '',
          'db_iframe_liclicita',
          sUrl,
          'Pesquisa',
          true,
          0
        );

        if(frame){
          frame.setAltura("100%");
          frame.setLargura("100%");
          frame.setPosX("0");
          frame.setPosY("0");
          frame.hide();
          frame.setCorFundoTitulo('#316648');
          db_iframe_liclicita.show();
        }
      }else if(document.getElementById('l200_licitacao').value){
        js_OpenJanelaIframe(
          '',
          'db_iframe_db_depart',
          'func_liclicita.php?pesquisa_chave=' + document.getElementById('l200_licitacao').value + '&funcao_js=parent.carregaLicitacao&parecer=true',
          'Pesquisar Departamento',
          false,
          '0'
        );
      }else{
        document.getElementById('l200_licitacao').value = '';
        document.getElementById('l20_numero').value = '';
        document.getElementById('l20_edital').value = '';
        document.getElementById('pc50_descr').value = '';
        document.getElementById('dl_modalidade').value = '';
      }
    }

    function carregaLicitacao(chave1,chave2,chave3,chave4,chave5, chave6, erro){
      if(erro === true){
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'Nenhuma licitação foi encontrado.',
        });

        document.getElementById('l200_licitacao').value = '';
        document.getElementById('l20_numero').value = '';
        document.getElementById('l20_edital').value = '';
        document.getElementById('pc50_descr').value = '';
        document.getElementById('dl_modalidade').value = '';
        return false;
      }

      document.getElementById('l200_licitacao').value = chave1;
      document.getElementById('l20_numero').value = chave2;
      document.getElementById('l20_edital').value = chave3;
      document.getElementById('pc50_descr').value = chave6;
      document.getElementById('dl_modalidade').value = chave5;
    }

    function carregaLicitacaoAncora(chave1,chave2,chave3,chave4,chave5,chave6){
      document.getElementById('l200_licitacao').value = chave1;
      document.getElementById('l20_numero').value = chave2;
      document.getElementById('l20_edital').value = chave3;
      document.getElementById('pc50_descr').value = chave6;
      document.getElementById('dl_modalidade').value = chave5;

      db_iframe_liclicita.hide();
    }

    function pesquisaCgm(mostra, num, nome){
      numCampo = num;
      nomeCampo = nome;
      if(mostra == true){
        let sUrl = 'func_nome.php?funcao_js=parent.carregaCgmAncora|z01_numcgm|z01_nome&filtro=1';
        if(typeof db_iframe_licitacoes != 'undefined'){
          db_iframe_licitacoes.jan.location.href = sUrl;
          db_iframe_licitacoes.setAltura("100%");
          db_iframe_licitacoes.setLargura("100%");
          db_iframe_licitacoes.setPosX("0");
          db_iframe_licitacoes.setPosY("0");
          db_iframe_licitacoes.setCorFundoTitulo('#316648');
          db_iframe_licitacoes.show();
          return false;
        }

        let frame = js_OpenJanelaIframe(
          '',
          'db_iframe_licitacoes',
          sUrl,
          'Pesquisa',
          false,
          '0',
          '1'
        );

        if(frame){
          frame.setAltura("100%");
          frame.setLargura("100%");
          frame.setPosX("0");
          frame.setPosY("0");
          frame.hide();
          frame.setCorFundoTitulo('#316648');
          db_iframe_licitacoes.show();
        }
      } else if(validateChangeInteger(numCampo)){
        numcgm = document.getElementById(numCampo).value;
        if (numcgm != '') {
          js_OpenJanelaIframe(
            '',
            'db_iframe_licitacoes',
            'func_nome.php?pesquisa_chave=' + numcgm + '&funcao_js=parent.carregaCgm&filtro=1',
            'Pesquisa',
            false
          );
        } else {
          document.getElementById(numCampo).value = "";
        }
      } else {
        document.getElementById(numCampo).value = "";
        document.getElementById(nomeCampo).value = "";
      }
    }

    function carregaCgmAncora(cod, desc){
      document.getElementById(numCampo).value = cod;
      document.getElementById(nomeCampo).value = desc;
      validateChangeInteger(numCampo)
      db_iframe_licitacoes.hide();
    }

    function carregaCgm(erro, chave){
      document.getElementById(nomeCampo).value = chave;
      if (erro == true) {
        document.getElementById(numCampo).value = "";
        document.getElementById(nomeCampo).value = "";
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'Responsável não encontrado!',
        });
      }
    }

    function validateChangeInteger(id, integer = true) {
      const inputElement = document.getElementById(id);

      if (!inputElement) return false;

      const validator = initializeValidationInput(inputElement);

      const isValid = validator.validate();
      if (!isValid) {
        if(integer){
          inputElement.value = inputElement.value.replace(/\D/g, '').substr(0, 10);
        }
        return false;
      }

      return true;
    }

    function validateChange(id, value = true) {
      const inputElement = document.getElementById(id);

      if (!inputElement) return false;

      const validator = initializeValidationInput(inputElement);

      const isValid = validator.validate();
      if (!isValid) {
        return false;
      }

      return true;
    }

    function validateToggleTipoParecer(l200_tipoparecer, enabledEdit = true){
      const textTipoParecer = document.getElementById('containDescricaoTipoParecer').querySelectorAll('textarea');
      if(['4'].includes(l200_tipoparecer)){
        document.getElementById('containDescricaoTipoParecer').style.display = 'block';
        textTipoParecer.forEach(input => {
          if(enabledEdit){
            input.value = '';
          }
          input.setAttribute('data-validate-required', true);
          input.setAttribute('data-validate-no-special-chars', true);
          input.setAttribute('data-validate-minlength', 10);
        });

        return false;
      }

      document.getElementById('containDescricaoTipoParecer').style.display = 'none';
      textTipoParecer.forEach(input => {
        if(enabledEdit){
          input.value = '';
        }
        input.setAttribute('data-validate-required', false);
        input.setAttribute('data-validate-no-special-chars', false);
        input.removeAttribute('data-validate-minlength');
      });
    }

    function makeJson(){
      return {
        exec: document.getElementById('exec').value,
        l200_sequencial: document.getElementById('l200_sequencial').value,
        l200_licitacao: document.getElementById('l200_licitacao').value,
        l20_edital: document.getElementById('l20_edital').value,
        l20_numero: document.getElementById('l20_numero').value,
        l200_tipoparecer: document.getElementById('l200_tipoparecer').value,
        l200_data: document.getElementById('l200_data').value,
        l200_numcgm: document.getElementById('l200_numcgm').value,
        l200_descrparecer: document.getElementById('l200_descrparecer').value,
      }
    }

    if(btnCancelar != null){
      btnCancelar.addEventListener('click', function(e){
        e.preventDefault();
        parent.close(false);
        return false;
      });
    }

    if(btnSalvar != null){
      btnSalvar.addEventListener('click', function(e){
        e.preventDefault();
        const isValid = validator.validate();
        if(!isValid){
          Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'Preencha todos os campos corretamente.',
          });
          return false;
        }

        if(!validateData()){
          return false;
        }

        Swal.fire({
          title: 'Aguarde...',
          text: 'Estamos processando sua solicitação.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        new Ajax.Request(
          url,
          {
            method: 'post',
            parameters: 'json=' + Object.toJSON(makeJson()),
            onComplete: (oAjax) => {
              let oRetorno = JSON.parse(oAjax.responseText);
              if(oRetorno.status == 200){
                Swal.fire({
                  icon: 'success',
                  title: 'Sucesso!',
                  text: 'Parecer salvo com sucesso!',
                });

                let currentUrl = new URL(window.location.href);
                parent.loading();
                currentUrl.searchParams.set('l200_sequencial', oRetorno.data.parecer.l200_sequencial);
                window.location.href = currentUrl.toString();
                return false;
              }

              if(oRetorno.status == 400){
                Swal.fire({
                  icon: 'warning',
                  title: 'Atenção!',
                  html: oRetorno.message,
                });
                return false;
              }

              Swal.fire({
                icon: 'error',
                title: 'Erro!',
                html: oRetorno.message,
              });
            }
          }
        );

      });
    }

    if(selectTipoParecer != null){
      selectTipoParecer.addEventListener('change', function (e) { 
        e.preventDefault();

        validateToggleTipoParecer(selectTipoParecer.value);
      });
    }

    if(btnAtualizar != null){
      btnAtualizar.addEventListener('click', function(e){
        e.preventDefault();

        const isValid = validator.validate();
        if(!isValid){
          Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'Preencha todos os campos corretamente.',
          });
          return false;
        }

        if(!validateData()){
          return false;
        }

        Swal.fire({
          title: 'Aguarde...',
          text: 'Estamos processando sua solicitação.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        new Ajax.Request(
          url,
          {
            method: 'post',
            parameters: 'json=' + Object.toJSON(makeJson()),
            onComplete: (oAjax) => {
              let oRetorno = JSON.parse(oAjax.responseText);
              if(oRetorno.status == 200){
                Swal.fire({
                  icon: 'success',
                  title: 'Sucesso!',
                  text: 'Parecer salvo com sucesso!',
                });

                let currentUrl = new URL(window.location.href);
                parent.loading();
                currentUrl.searchParams.set('l200_sequencial', oRetorno.data.parecer.l200_sequencial);
                window.location.href = currentUrl.toString();
                return false;
              }

              if(oRetorno.status == 400){
                Swal.fire({
                  icon: 'warning',
                  title: 'Atenção!',
                  html: oRetorno.message,
                });
                return false;
              }

              Swal.fire({
                icon: 'error',
                title: 'Erro!',
                html: oRetorno.message,
              });
            }
          }
        );
      });
    }

    function validateData(){
      const inputData = new Date(document.getElementById('l200_data').value);
      const hoje = new Date();

      hoje.setHours(0, 0, 0, 0);
      inputData.setHours(0, 0, 0, 0);

      if (inputData > hoje) {
           Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'A data do parecer deve ser menor ou igual a data atual.',
          });
          return false;
      }

      return true;
    }

    function loadEdit(){
      let oParam = {};
      oParam.exec = 'getParecerByCodigo';
      oParam.l200_sequencial = l200_sequencial;
      new Ajax.Request(
        url,
        {
          method: 'post',
          parameters: 'json=' + Object.toJSON(oParam),
          onComplete: async function(oAjax){
            let oRetorno = JSON.parse(oAjax.responseText);

            let parecer = oRetorno.data.parecer;

            document.getElementById('l200_sequencial').value = parecer.l200_sequencial;
            document.getElementById('l200_licitacao').value = parecer.l20_codigo;
            document.getElementById('pc50_descr').value = parecer.l20_objeto;
            document.getElementById('l20_edital').value = parecer.l20_edital;
            document.getElementById('dl_modalidade').value = parecer.dl_modalidade;
            document.getElementById('l20_numero').value = parecer.l20_numero;
            document.getElementById('l200_tipoparecer').value = parecer.parecer;
            document.getElementById('l200_data').value = parecer.l200_data;
            document.getElementById('l200_numcgm').value = parecer.l200_numcgm;
            document.getElementById('z01_nome').value = parecer.z01_nome;
            document.getElementById('l200_descrparecer').value = parecer.l200_descrparecer;

            validateToggleTipoParecer(
              (parecer.parecer ? parecer.parecer.toString() : null), 
              false
            )
          }
        }
      )
    }

    if(l200_sequencial != null && l200_sequencial != ''){
      loadEdit();
    }

    function disabledQuebraLinha(event){
      if(event.key === 'Enter'){
        event.preventDefault();
      }
    }

    window.carregaLicitacao = carregaLicitacao;
    window.carregaLicitacaoAncora = carregaLicitacaoAncora;
    window.pesquisaLicitacao = pesquisaLicitacao;
    window.pesquisaCgm = pesquisaCgm;
    window.carregaCgmAncora = carregaCgmAncora;
    window.carregaCgm = carregaCgm;
    window.validateChange = validateChange;
    window.validateChangeInteger = validateChangeInteger;
    window.disabledQuebraLinha = disabledQuebraLinha;
  })();
</script>