<?php
    require("libs/db_stdlib.php");
    require("libs/db_conecta.php");
    include("libs/db_sessoes.php");
    include("libs/db_usuariosonline.php");
    include("dbforms/db_funcoes.php");
    include("dbforms/db_classesgenericas.php");
    include("classes/db_pcparam_classe.php");
    require_once 'libs/renderComponents/index.php';
?>
<script type="text/javascript" defer>
  loadComponents(['simpleModal', 'radiosBordered']);
</script>

<!DOCTYPE html>
<html>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <title>DBSeller Informática Ltda - Página Inicial</title>
  <?php
      db_app::load("scripts.js");
      db_app::load("prototype.js");
      db_app::load("datagrid.widget.js");
      db_app::load("strings.js");
      db_app::load("grid.style.css");
      db_app::load("classes/dbViewAvaliacoes.classe.js");
      db_app::load("widgets/windowAux.widget.js");
      db_app::load("widgets/dbmessageBoard.widget.js");
      db_app::load("dbcomboBox.widget.js");
      db_app::load("estilos.bootstrap.css");
      db_app::load("sweetalert.js");
      db_app::load("just-validate.js");
      db_app::load("estilos.css", date('YmdHis'));
  ?>
  <body class="container">
    <form id="frmFiltro" method="post" style="margin-top: 10px; display: none">
      <input type="hidden" name="remessa_gerada" id="remessa_gerada">
      <fieldset class="p-4">
        <legend><b>Gerar Arquivos:</b></legend>
        <div class="row" style="justify-content: center;">
          <div class="col-12 col-sm-6 form-group">
            <label for=""><b>Período:</b></label>
            <div class="row" style="align-items: center;">
              <div class="col-12 col-sm-5 pl-0 pr-0">
                <?php  
                  db_input(
                    'l228_datainicial',
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
                        'validate-date-message' => 'Informe uma data válida'
                    ],
                    date('Y-m-d', db_getsession('DB_datausu'))
                  ); 
                ?>
              </div>
              <div class="col-12 col-sm-1 text-center"><b>Até</b></div>
              <div class="col-12 col-sm-5 pl-0 pr-0">
                <?php  
                  db_input(
                    'l228_datafim',
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
                        'validate-date-message' => 'Informe uma data válida'
                    ],
                    date('Y-m-d', db_getsession('DB_datausu'))
                  ); 
                ?>
              </div>
            </div>
          </div>
        </div>
      </fieldset>
      <div class="row mt-4" style="align-items:center; justify-content: center;">
        <div class="col-12 col-sm-2">
          <button type="submit" class="btn btn-success" id="btnGerarArquivos">Gerar Arquivos</button>
        </div>
      </div>
    </form>
    <form id="frmGeraArquivo">
      <?php 
      /*$component->render('modais/simpleModal/startModal', [
          'id' => 'modalGeracaoArquivos',
          'size' => 'lg'
      ], true); */ ?>
      <fieldset class="p-4 mt-4 mb-4">
        <legend>Geração dos arquivos:</legend>
        <div class="row">
          <div class="col-12 col-sm-2 text-left">
            <label for=""><b>Remessa:</b></label>
            <?php
              db_input(
                  'l228_seqremessa',
                  4,
                  1,
                  true,
                  'text',
                  1,
                  "",
                  '',
                  '',
                  '',
                  null,
                  'form-control',
                  []
              );
            ?>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-12 col-sm-6 form-group">
            <fieldset class="p-4" style="height: 100%;">
              <legend>Arquivos</legend>
              <div class="row">
                <div class="col-12 col-sm-12 form-group mb-2" style="display: flex; align-items: center; gap: 10px;">
                  <input type="checkbox" name="ide" id="ide" value="IDE" class="checkbox-arquivos" checked>
                  <label for="ide">IDE - Identificação de Remessa</label>
                </div>
                <div class="col-12 col-sm-12 form-group mb-2" style="display: flex; align-items: center; gap: 10px;">
                  <input type="checkbox" name="item" id="item" value="ITEM" class="checkbox-arquivos" checked>
                  <label for="item">ITEM - Cadastro dos itens</label>
                </div>
                <div class="col-12 col-sm-12 form-group" style="display: flex; align-items: center; gap: 10px;">
                  <input type="checkbox" name="pessoa" id="pessoa" value="PESSOA" class="checkbox-arquivos" checked>
                  <label for="pessoa">PESSOA - Pessoa Física e Jurídica</label>
                </div>
              </div>
            </fieldset>
          </div>
          <div class="col-12 col-sm-6 form-group">
            <fieldset class="p-4" style="height: 100%;">
              <legend>Arquivos Gerados</legend>
              <div class="row">
                <div id="divArquivosGerados" class="col-12 form-group"></div>
              </div>
            </fieldset>
          </div>
        </div>
        <div class="row">
          <div class="col-12 text-center mt-4 mb-2">
            <button type="submit" class="btn btn-success" id="btnProcessar">Processar</button>
            <button class="btn btn-primary" type="button" id="btnLimparTodos">Limpar Todos</button>
          </div>
        </div>
      </fieldset>
      <div class="row" style="align-items: center;">
        <div class="col-12 col-sm-3 form-group mb-2">
          <label for="l228_dataenvio"><b>Data de Envio Remessa:</b></label>
          <?php  
            db_input(
              'l228_dataenvio',
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
                  'validate-date-message' => 'Informe uma data válida'
              ]
            ); 
          ?>
        </div>
        <div class="col-12 col-sm-9 form-group mb-2" style="display: flex; gap: 5px;">
          <input type="checkbox" value="declaracao" id="declaracao" />
          <label for="declaracao"><b>Declaro estar ciente de que é de minha inteira responsabilidade prestar as informações corretas ao SICOM dentro dos prazos exigidos, conforme as normas estabelecidas.</b></label>
        </div>
      </div>
      <div class="row">
        <div class="col-12 text-center mt-4 mb-2">
          <button type="submit" class="btn btn-success" id="btnSalvar">Salvar</button>
        </div>
      </div>
      <?php /* $component->render('modais/simpleModal/endModal', [], true); */ ?>
    </form>
  </body>
</html>
<script>
  const url = 'lic_cadastrobasicosicom.RPC.php';
  const btnGerarArquivos = document.getElementById('btnGerarArquivos');
  const btnLimparTodos = document.getElementById('btnLimparTodos');
  const btnProcessar = document.getElementById('btnProcessar');
  const btnSalvar = document.getElementById('btnSalvar');
  const defaultDate = '<?= date('Y-m-d', db_getsession('DB_datausu')); ?>';
  const regex = /^(\d{4})-(\d{2})-(\d{2})$/;

  function desmarcarTodos() { 
    const inputs = document.querySelectorAll('.checkbox-arquivos')
    inputs.forEach(check => check.checked = false)
  }

  function marcarTodos(){
    const inputs = document.querySelectorAll('.checkbox-arquivos')
    inputs.forEach(check => check.checked = true)
  }

  function validateForm(){
    const validator = initializeValidation('#frmGeraArquivo');
      const isValid = validator.validate();

      if(!document.getElementById('l228_seqremessa').value){
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'Preencha o código da remessa.',
        });
        return false;
      }

      if(!document.getElementById('declaracao').value){
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'Preencha a declaração.',
        });
        return false;
      } 

      if(!document.getElementById('declaracao').checked){
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'Preencha a declaração de ciencia.',
        });
        return false;
      }

      if(!isValid){
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'Preencha todos os campos corretamente.',
        });
        return false;
      }

      return true;
  }

  function getNumeroRemessa(){
    Swal.fire({
      title: 'Aguarde...',
      text: 'Estamos processando sua solicitação.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    const oParam = {};
    oParam.exec = 'getNumeroRemessa';
    new Ajax.Request(
      url,
      {
        method: 'post',
        parameters: 'json=' + Object.toJSON(oParam),
        onComplete: (oAjax) => {
          let oRetorno = JSON.parse(oAjax.responseText);

          document.getElementById('l228_seqremessa').value = oRetorno.data.l228_seqremessa

          Swal.close();

          document.getElementById('divArquivosGerados').innerHTML = "";
          document.getElementById('declaracao').checked = false;
          document.getElementById('l228_dataenvio').value = "";
          marcarTodos();

          // openModal('modalGeracaoArquivos')      
        }
      }
    );
  }

  getNumeroRemessa();

  if(btnGerarArquivos){
    btnGerarArquivos.addEventListener('click', function (event) { 
      event.preventDefault();
      
      const validator = initializeValidation('#frmFiltro');
      const isValid = validator.validate();
      const l228_datainicial = document.getElementById('l228_datainicial').value;
      const l228_datafim = document.getElementById('l228_datafim').value;
      
      if(!l228_datainicial){
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'Informe a data inicial do periodo.',
        });
        return false;
      }

      if(!l228_datafim){
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'Informe a data fim do periodo.',
        });
        return false;
      }

      const matchDataInicio = l228_datainicial.match(regex);
      const yearDataInicio = parseInt(matchDataInicio[1], 10);
      if(yearDataInicio < 1900 || yearDataInicio > 2099){
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'A data inicio do periodo deve ficar entre os anos 1900 e 2099.',
        });
        return false;
      }

      const matchDataRemessa = l228_datafim.match(regex);
      const yearDataRemessa = parseInt(matchDataRemessa[1], 10);
      if(yearDataRemessa < 1900 || yearDataRemessa > 2099){
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'A data da remessa deve ficar entre os anos 1900 e 2099.',
        });
        return false;
      }

      if(!isValid){
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'Preencha todos os campos corretamente.',
        });
        return false;
      }

      getNumeroRemessa();
    });
  }

  if(btnLimparTodos){
    btnLimparTodos.addEventListener('click', function(e){
      e.preventDefault();
      desmarcarTodos();
    })
  }

  if(btnProcessar){
    btnProcessar.addEventListener('click', function(e){
      e.preventDefault();

      const inputs = [...document.querySelectorAll('.checkbox-arquivos')];

      const inputsCheckeds = inputs.filter(checkbox => checkbox.checked).map(checkbox => checkbox.value);

      if(inputsCheckeds.length == 0){
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'Nenhum arquivo está selecionado!',
        });
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

      const oParam = {};
      oParam.exec = 'gerarArquivos';
      oParam.itens = inputsCheckeds;
      oParam.l228_seqremessa = document.getElementById('l228_seqremessa').value;
      oParam.l228_dataenvio = document.getElementById('l228_dataenvio').value;
      oParam.l228_datainicial = document.getElementById('l228_datainicial').value;
      oParam.l228_datafim = document.getElementById('l228_datafim').value;
      new Ajax.Request(
        url,
        {
          method: 'post',
          parameters: 'json=' + Object.toJSON(oParam),
          onComplete: (oAjax) => {
            let oRetorno = JSON.parse(oAjax.responseText);

            if(oRetorno.status != 200 && oRetorno.status != 400){
              Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: oRetorno.message,
              });
              return false;
            }

            if(oRetorno.status == 400){
              Swal.fire({
                icon: 'warning',
                title: 'Atenção!',
                text: oRetorno.message,
              });
            }

            if(oRetorno.status == 200){
              Swal.fire({
                icon: 'success',
                title: 'Atenção!',
                text: oRetorno.message,
              });
            }

            const divArquivosGerados = document.getElementById('divArquivosGerados');
            let links = '';

            oRetorno.data.csv.forEach(function(url){
              const urlencode = 'db_download.php?arquivo=' + encodeURIComponent(`tmp/${oRetorno.data.remessa}/${url}.csv`);
              links += `<a target="_blank" href="${urlencode}">${url}.csv</a><br>`
            });

            const urlencode = 'db_download.php?arquivo='+encodeURIComponent(`tmp/${oRetorno.data.remessa}/${oRetorno.data.nomeZip}`);
            links += `<a target="_blank" href="${urlencode}">${oRetorno.data.nomeZip}</a><br>`;

            divArquivosGerados.innerHTML = links;

            document.getElementById('remessa_gerada').value = oRetorno.data.remessa;
          }
        }
      );
    })
  }

  if(btnSalvar){
    btnSalvar.addEventListener('click', function (e) { 
      e.preventDefault();

      if(!validateForm()){
        return false;
      }

      const inputs = [...document.querySelectorAll('.checkbox-arquivos')];

      const inputsCheckeds = inputs.filter(checkbox => checkbox.checked).map(checkbox => checkbox.value);

      if(inputsCheckeds.length == 0){
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'Nenhum arquivo está selecionado!',
        });
        return false;
      }

      if(document.getElementById('divArquivosGerados').querySelectorAll('a').length <= 0){
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'Nenhum arquivo foi gerado!',
        });
        return false;
      }

      const l228_dataenvio = document.getElementById('l228_dataenvio').value;
      if(!l228_dataenvio){
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'Informe a data de envio da remessa!',
        });
        return false;
      }

      const matchDataFim = l228_dataenvio.match(regex);
      const yearDataFim = parseInt(matchDataFim[1], 10);
      if(yearDataFim < 1900 || yearDataFim > 2099){
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'A data fim do periodo deve ficar entre os anos 1900 e 2099.',
        });
        return false;
      }

      if(document.getElementById('remessa_gerada').value != document.getElementById('l228_seqremessa').value){
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'Código sequencial da remessa informado diferente do código gerado no arquivo IDE! Reprocesse a geração do arquivo.',
        });
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

      const oParam = {};
      oParam.exec = 'salvarGeracaoArquivos';
      oParam.itens = inputsCheckeds;
      oParam.l228_seqremessa = document.getElementById('l228_seqremessa').value;
      oParam.l228_dataenvio = l228_dataenvio;
      oParam.l228_datainicial = document.getElementById('l228_datainicial').value;
      oParam.l228_datafim = document.getElementById('l228_datafim').value;
      new Ajax.Request(
        url,
        {
          method: 'post',
          parameters: 'json=' + Object.toJSON(oParam),
          onComplete: (oAjax) => {
            let oRetorno = JSON.parse(oAjax.responseText);

            if(oRetorno.status != 200){
              Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: oRetorno.message,
              });
              return false;
            }

            Swal.fire({
              icon: 'success',
              title: 'Sucesso!',
              text: 'Dados salvos com sucesso!',
            });

            document.getElementById('divArquivosGerados').innerHTML = "";            
            getNumeroRemessa();
            // closeModal('modalGeracaoArquivos')
          }
        }
      );
    })
  }

</script>