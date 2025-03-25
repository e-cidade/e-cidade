<?php
    require("libs/db_stdlib.php");
    require("libs/db_conecta.php");
    include("libs/db_sessoes.php");
    include("libs/db_usuariosonline.php");
    include("dbforms/db_funcoes.php");
    include("dbforms/db_classesgenericas.php");
    include("classes/db_pcparam_classe.php");
?>
<!DOCTYPE html>
<html>
<head>
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
</head>
<body class="container">
  <form id="frmFiltro" method="post" style="margin-top: 10px;">
    <fieldset class="p-4">
      <legend><b>Pesquisar</b></legend>
      <div class="row">
        <div class="col-12 col-sm-3 form-group" style="margin-bottom: 10px;">
          <label for="l200_sequencial"><b>Cod. Sequencial:</b></label>
          <?php
            db_input(
              'l200_sequencial',
              10,
              '',
              true,
              'text',
              44,
              "",
              "",
              "",
              "",
              null,
              'form-control',
              [
                  'validate-no-special-chars'         => 'true',
                  'validate-integer'                  => 'true',
                  'validate-no-special-chars-message' => 'O Cod. Sequencial não deve conter aspas simples, ponto e vírgula ou porcentagem',
                  'validate-integer-message'          => 'O Cod. Sequencial deve conter apenas numeros'
              ]
            );
          ?>
        </div>
        <div class="col-12 col-sm-3 form-group" style="margin-bottom: 10px;">
          <label for="l20_codigo"><b>Licitação:</b></label>
          <?php
            db_input(
              'l20_codigo',
              10,
              '',
              true,
              'text',
              44,
              "",
              "",
              "",
              "",
              null,
              'form-control',
              [
                  'validate-no-special-chars'         => 'true',
                  'validate-integer'                  => 'true',
                  'validate-no-special-chars-message' => 'A Licitação não deve conter aspas simples, ponto e vírgula ou porcentagem',
                  'validate-integer-message'          => 'A Licitação deve conter apenas numeros'
              ]
            );
          ?>
        </div>
        <div class="col-12 col-sm-3 form-group" style="margin-bottom: 10px;">
          <label for="l20_edital"><b>Processo Licitatório:</b></label>
          <?php
              db_input(
              'l20_edital',
              10,
              '',
              true,
              'text',
              44,
              "",
              "",
              "",
              "",
              null,
              'form-control',
              [
                  'validate-maxlength'                => '16',
                  'validate-no-special-chars'         => 'true',
                  'validate-integer'                  => 'true',
                  'validate-maxlength-message'        => 'O Processo Licitatório deve ter no máximo 10 caracteres',
                  'validate-no-special-chars-message' => 'O Processo Licitatório não deve conter aspas simples, ponto e vírgula ou porcentagem',
                  'validate-integer-message'          => 'O campo Processo Licitatório deve conter apenas numeros'
              ]
            );
          ?>
        </div>
        <div class="col-12 col-sm-3 form-group" style="margin-bottom: 10px;">
          <label for="l20_numero"><b>Número Licitação:</b></label>
          <?php
            db_input(
              'l20_numero',
              10,
              '',
              true,
              'text',
              44,
              "",
              "",
              "",
              "",
              null,
              'form-control',
              [
                  'validate-maxlength'                => '10',
                  'validate-no-special-chars'         => 'true',
                  'validate-integer'                  => 'true',
                  'validate-maxlength-message'        => 'O Número Licitação deve ter no máximo 10 caracteres',
                  'validate-no-special-chars-message' => 'O Número Licitação não deve conter aspas simples, ponto e vírgula ou porcentagem',
                  'validate-integer-message'          => 'O campo Número Licitação deve conter apenas numeros'
              ]
            );
          ?>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-sm-3 form-group" style="margin-bottom: 10px;">
          <label for="l200_data"><b>Data Parecer:</b></label>
          <?php
            db_input(
              'l200_data',
              10,
              '',
              true,
              'date',
              44,
              "",
              "",
              "",
              "",
              null,
              'form-control',
              [
                'validate-date' => 'true',
                'validate-date-message' => 'Informe uma data válida'
              ],
            );
          ?>
        </div>
        <div class="col-12 col-sm-3 form-group" style="margin-bottom: 10px;">
          <label for="l200_tipoparecer"><b>Tipo Parecer:</b></label>
          <select name="l200_tipoparecer" id="l200_tipoparecer" class="custom-select">
            <option value="">Selecione</option>
            <option value="1">Técnico</option>
            <option value="2">Juridico - Edital</option>
            <option value="3">Juridico - Julgamento</option>
            <option value="4">Juridico - Outros</option>
          </select>
        </div>
        <div class="col-12 col-sm-3 form-group" style="margin-bottom: 10px;">
          <label for="l200_exercicio"><b>Exercício:</b></label>
          <?php
            db_input(
              'l200_exercicio',
              4,
              '',
              true,
              'text',
              1,
              "onkeyup='validacaoAno(event, this.value);'",
              '',
              '',
              '',
              '',
              'form-control',
              [
                  'validate-maxlength'                => '4',
                  'validate-minlength'                => '4',
                  'validate-no-special-chars'         => 'true',
                  'validate-integer'                  => 'true',
                  'validate-maxlength-message'        => 'O campo Exercício deve ter no máximo 4 caracteres',
                  'validate-minlength-message'        => 'O campo Exercício deve ter no minimo 4 caracteres',
                  'validate-no-special-chars-message' => 'O campo Exercício não deve conter aspas simples, ponto e vírgula ou porcentagem',
                  'validate-integer-message'          => 'O campo Exercício deve conter apenas numeros'
              ]
            );
          ?>
        </div>
        <div class="col-12 col-sm-3 form-group">
          <label for="l20_objeto"><b>Objeto:</b></label>
          <?php
            db_input(
              'l20_objeto',
              4,
              '',
              true,
              'text',
              1,
              "",
              '',
              '',
              '',
              '',
              'form-control',
              [
                'validate-minlength'                => "1",
                'validate-no-special-chars'         => 'true',
                'validate-minlength-message'        => "O Objeto deve ter pelo menos 1 caracteres",
                'validate-no-special-chars-message' => 'O Objeto não deve conter aspas simples, ponto e vírgula ou porcentagem',
              ]
            );
          ?>
        </div>
      </div>
      <div class="row">
          <div class="col-12">
              <button type="button" class="btn btn-success" id="btnNovo" style="margin: 0 2px;">Novo</button>
              <button class="btn btn-primary" type="button" id="btnFiltrar" style="margin: 0 2px;">Pesquisar</button>
              <button class="btn btn-danger" type="button" id="btnLimpar" style="margin: 0 2px;">Limpar</button>
          </div>
      </div>
    </fieldset>
  </form>
  <div class="inner-container">
    <div class="row mt-4">
      <div class="col-12" id="containTabelaPareceres"></div>
    </div>
  </div>
</body>
</html>
<script>
  const url = 'lic_pareceres.RPC.php';
  const form = document.getElementById('frmFiltro');
  const btnFiltrar = document.getElementById('btnFiltrar');
  const btnLimpar = document.getElementById('btnLimpar');
  const btnNovo = document.getElementById('btnNovo');
  const si06_sequencial = document.getElementById('si06_sequencial');
  const si06_numeroprc = document.getElementById('si06_numeroprc');
  const si06_numlicitacao = document.getElementById('si06_numlicitacao');
  const si06_numeroadm = document.getElementById('si06_numeroadm');
  const oGridPareceres = new DBGrid('containTabelaPareceres');
  const formatData = new Intl.DateTimeFormat('pt-BR', { dateStyle: 'short', timeZone: 'America/Sao_Paulo' });
  const validator = initializeValidation('#frmFiltro');
  const limit = 30;

  const inputsValidateInteger = [
    'l200_sequencial',
    'l20_codigo',
    'l20_edital',
    'l20_numero'
  ];

  const inputsValidate = [
    'l200_data',
    'l200_exercicio',
    'l20_objeto'
  ];

  inputsValidateInteger.forEach(function(value, index) {
    document.getElementById(value).addEventListener('input', function(e){
      validateChangeInteger(value)
    });
  });

  inputsValidate.forEach(function(value, index) {
    document.getElementById(value).addEventListener('input', function(e){
      validateChangeInteger(value, false)
    });
  });

  function validateChangeInteger(id, integer = true) {
    const inputElement = document.getElementById(id);

    if (!inputElement) return false;

    const validator = initializeValidationInput(inputElement);
    const isValid = validator.validate();
    if (!isValid) {
      if(integer){
        inputElement.value = inputElement.value.replace(/\D/g, '');
      }
      return false;
    }

    return true;
  }

  initGrid();
  function initGrid(){
    oGridPareceres.nameInstance = 'oGridPareceres';
    oGridPareceres.setCellAlign([
      'center',
      'center',
      'center',
      'center',
      'center',
      'center',
      'center',
      'center',
      'center',
      'center',
      'center',
      'center',
      'center',
      'center',
      'center'
    ]);

    oGridPareceres.setCellWidth([
      '120px', //l200_sequencial
      '100px', //l20_codigo
      '150px', //l20_edital
      '100px', //l20_numero
      '250px', //dl_modalidade
      '100px', //l20_nroedital
      '100px', //l200_exercicio
      '150px', //l200_data
      '150px', //l200_tipoparecer
      '300px', //z01_nome
      '400px', //l20_objeto
      '150px', //dl_situacao
      '100px'
    ]);

    oGridPareceres.setHeaderOrderable([
      {orderable: true, slug: 'l200_sequencial', default: true, order: 'desc'},
      {orderable: true, slug: 'l20_codigo'},
      {orderable: true, slug: 'l20_edital'},
      {orderable: true, slug: 'l20_numero'},
      {orderable: true, slug: 'dl_modalidade'},
      {orderable: true, slug: 'l20_nroedital'},
      {orderable: true, slug: 'l200_exercicio'},
      {orderable: true, slug: 'l200_data'},
      {orderable: true, slug: 'l200_tipoparecer'},
      {orderable: true, slug: 'z01_nome'},
      {orderable: true, slug: 'l20_objeto'},
      {orderable: true, slug: 'dl_situacao'},
      {orderable: false, slug: ''},
    ]);

    oGridPareceres.setHeader([
      'Cod. Sequecial', //l200_sequencial
      'Licitação', //l20_codigo
      'Processo Licitatório', //l20_edital
      'Numeração', // l20_numero
      'Modalidade', // dl_modalidade
      'Edital', //l20_nroedital
      'Exercício', // l200_exercicio
      'Data do Parecer', // l200_data
      'Tipo de Parecer', //l200_tipoparecer
      'Nome/Razão Social', //z01_nome
      'Objeto', //l20_objeto
      'Situação', //dl_situacao
      'Ações'
    ]);

    oGridPareceres.allowSelectColumns(true);
    oGridPareceres.setHeight(450);
    oGridPareceres.showV2(document.getElementById('containTabelaPareceres'));
    oGridPareceres.clearAll(true);
    oGridPareceres.inicializaTooltip();
    oGridPareceres.paginatorInitialize((pageNumber) => {
      loading(pageNumber);
    });
    oGridPareceres.orderableInitialize((activeSortColumns) => {
      loading(1, activeSortColumns);
    });
    oGridPareceres.initializeInpuSearch((search) => {
      loading(1);
    })
  }

  function loading(offset = 1, activeSortColumns = [], search = ''){
    let oParam = new Object();
    oParam.exec = 'listagemParecer';

    oParam.l200_sequencial = document.getElementById('l200_sequencial').value;
    oParam.l20_codigo = document.getElementById('l20_codigo').value;
    oParam.l20_edital = document.getElementById('l20_edital').value;
    oParam.l20_numero = document.getElementById('l20_numero').value;
    oParam.l200_tipoparecer = document.getElementById('l200_tipoparecer').value;
    oParam.l200_data = document.getElementById('l200_data').value;
    oParam.l200_exercicio = document.getElementById('l200_exercicio').value;
    oParam.l20_objeto = document.getElementById('l20_objeto').value;

    oParam.offset = offset;
    oParam.limit = limit;
    oParam.orderable = activeSortColumns.length > 0 ? activeSortColumns : oGridPareceres.getActiveSortColumns();
    oParam.search = oGridPareceres.getInputSearch() || '';
    oGridPareceres.showLoading();
    new Ajax.Request(
      url,
      {
        method: 'post',
        parameters: 'json=' + Object.toJSON(oParam),
        onComplete: (oAjax) => {
          let oRetorno = JSON.parse(oAjax.responseText);
          oGridPareceres.clearAll(true);
          if(oRetorno.data.pareceres.length <= 0){
            Swal.fire({
                icon: 'warning',
                title: 'Atenção!',
                text: 'Ops, nenhum parecer foi encontrada.',
            });
          }
          
          oRetorno.data.pareceres.forEach(function(oValue, iSeq){
            let aLinha = new Array();
            aLinha[0] = oValue.l200_sequencial || ' - ';
            aLinha[1] = oValue.l20_codigo || ' - ';
            aLinha[2] = oValue.l20_edital || ' - ';
            aLinha[3] = oValue.l20_numero || ' - ';
            aLinha[4] = oValue.dl_modalidade || ' - ';
            aLinha[5] = oValue.l20_nroedital || ' - ';
            aLinha[6] = oValue.l200_exercicio || ' - ';
            aLinha[7] = (oValue.l200_data && oValue.l200_data.length > 0)? formatData.format(new Date(oValue.l200_data + 'T00:00:00')) : ' - ';
            aLinha[8] = oValue.l200_tipoparecer || ' - ';
            // aLinha[7] = `<div class="tooltip-target" data-tooltip="${oValue.si06_objetoadesao.trim() || ''}">${oValue.si06_objetoadesao.trim() || ' - '}</div>`;
            aLinha[9] = `<div class="tooltip-target" data-tooltip="${oValue.z01_nome.trim() || ''}">${oValue.z01_nome.trim() || ' - '}</div>`;
            aLinha[10] = `<div class="tooltip-target" data-tooltip="${oValue.l20_objeto.trim() || ''}">${oValue.l20_objeto.trim() || ' - '}</div>`;
            aLinha[11] = oValue.dl_situacao || ' - ';

            aLinha[12] = `
              <a
                  href="#"
                  style="margin: 0 5px; color: #007bff; font-weigth: bold;"
                  onclick="openEdicao(
                      event,
                      ${oValue.l200_sequencial}
                  )"
              ><i class="fa fa-pen"></i></a>
              <a
                  href="#"
                  style="margin: 0 5px; color: #dc3545; font-weigth: bold;"
                  onclick="openExclusao(
                      event,
                      ${oValue.l200_sequencial}
                  )"
              ><i class="fa fa-trash"></i></a>
            `;

            oGridPareceres.addRow(aLinha)
          });

          let totalPages = (oRetorno.data.total) ? (oRetorno.data.total / limit) : 0;
          oGridPareceres.setTotalItens(oRetorno.data.total || 0);
          oGridPareceres.renderRows();
          oGridPareceres.renderPagination(totalPages, offset);
          oGridPareceres.hideLoading();
          oGridPareceres.fixedColumns(0, 1);
        }
      }
    ); 
  }

  loading();

  btnFiltrar.addEventListener('click', function(event){
    const inputs = document.getElementById('frmFiltro').querySelectorAll('input')
    inputs.forEach(input => {
      const event = new Event('input', { bubbles: true });
      input.dispatchEvent(event);
    });
    
    if(document.querySelectorAll('input.error').length > 0){
        return false;
    }
    oGridPareceres.resetOrderableColumns((activeSortColumns) => {
        loading(1, activeSortColumns);
    });
  })

  btnLimpar.addEventListener('click', (event) => {
      document.getElementById('frmFiltro').reset();
  });

  btnNovo.addEventListener('click', (event) => {
    event.preventDefault();

    let sUrl = 'lic_pareceres002.php';

    if(typeof db_iframe_parecer != 'undefined'){
      db_iframe_parecer.jan.location.href = sUrl;
      db_iframe_parecer.setAltura("100%");
      db_iframe_parecer.setLargura("100%");
      db_iframe_parecer.setPosX("0");
      db_iframe_parecer.setPosY("0");
      db_iframe_parecer.setCorFundoTitulo('#316648');
      db_iframe_parecer.show();
      document.querySelector(`div.DBJanelaIframe`).style.position = 'fixed';
      return false;
    }

    let frame = js_OpenJanelaIframe(
        'CurrentWindow.corpo',
        'db_iframe_parecer',
        sUrl,
        'Parecer da Licitação > Inclusão',
        false
    );

    if(frame){
        frame.hide();
        db_iframe_parecer.setAltura("100%");
        db_iframe_parecer.setLargura("100%");
        db_iframe_parecer.setPosX("0");
        db_iframe_parecer.setPosY("0");
        db_iframe_parecer.setCorFundoTitulo('#316648');
        db_iframe_parecer.show();
        document.querySelector(`div.DBJanelaIframe`).style.position = 'fixed';
    }
  });

  function close(refresh = true){
    if(typeof db_iframe_parecer != 'undefined'){
      db_iframe_parecer.hide();
    }

    if(typeof db_iframe_adesao_registro_de_precos_edit != 'undefined'){
      db_iframe_adesao_registro_de_precos_edit.hide();
    }

    if(refresh){
      loading();
    }
  }

  function openEdicao(event, l200_sequencial){
    event.preventDefault();
    let currentUrl = new URL('lic_pareceres002.php', window.location.href);
    currentUrl.searchParams.set('l200_sequencial', l200_sequencial);
    if(typeof db_iframe_parecer_edit != 'undefined'){
      window.scroll({
        top:0,
        left:0,
        behavior: 'smooth'
      });
      db_iframe_parecer_edit.setCorFundoTitulo('#316648');
      db_iframe_parecer_edit.jan.location.href = currentUrl.toString();
      db_iframe_parecer_edit.setAltura("100%");
      db_iframe_parecer_edit.setLargura("100%");
      db_iframe_parecer_edit.setPosX("0");
      db_iframe_parecer_edit.setPosY("0");
      db_iframe_parecer_edit.show();
      document.querySelector(`div.DBJanelaIframe`).style.position = 'fixed';
      return false;
    }

    let frame = js_OpenJanelaIframe(
      '',
      'db_iframe_parecer_edit',
      currentUrl.toString(),
      'Parecer da Licitação > Alteração',
      true
    );

    if(frame){
      frame.hide();
      db_iframe_parecer_edit.show();
      db_iframe_parecer_edit.setCorFundoTitulo('#316648');
      db_iframe_parecer_edit.setAltura("100%");
      db_iframe_parecer_edit.setLargura("100%");
      db_iframe_parecer_edit.setPosX("0");
      db_iframe_parecer_edit.setPosY("0");
      document.querySelector(`div.DBJanelaIframe`).style.position = 'fixed';
    }
  }

  function openExclusao(event, l200_sequencial){
    event.preventDefault();
    Swal.fire({
      title: 'Deseja remover parecer?',
      text: 'Essa ação não pode ser desfeita!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sim, excluir!',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if(result.isConfirmed){
        Swal.fire({
            title: 'Aguarde...',
            text: 'Estamos processando sua solicitação.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        let oParam = {};
        oParam.exec = 'removeParecer';
        oParam.l200_sequencial = l200_sequencial;
        new Ajax.Request(
          url, 
          {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: function (oAjax) { 
              let oRetorno = JSON.parse(oAjax.responseText);
              if(oRetorno.status != null && oRetorno.status == 200){
                  Swal.fire({
                      icon: 'success',
                      title: 'Sucesso!',
                      text: 'Parecer removido com sucesso!',
                  });
                  loading();
                  return false;
              }

              Swal.fire({
                  icon: 'error',
                  title: 'Erro!',
                  text: oRetorno.message,
              });
            }
          }
        );
      }
    });
  }

  function validacaoAno(event, ano){
    event.preventDefault();
    if(event.target.value.length > 4){
      event.target.value = event.target.value.substr(0, 4);
    }
  } 

</script>