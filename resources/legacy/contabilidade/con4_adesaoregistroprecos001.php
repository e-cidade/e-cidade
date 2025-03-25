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
    <style>
        body {
            background-color: #CCCCCC;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }
        .container {
            margin-top: 20px; /* Espaï¿½o acima do container */
            background-color: #FFFFFF;
            padding: 20px;
            max-width: 100%; /* Largura mï¿½xima do conteï¿½do */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra leve */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .tdleft {
            text-align: left;
        }
        .tdright {
            text-align: right;
        }
        form {
            margin-top: 10px;
        }
        select{
            width: 100%;
        }
        .DBJanelaIframeTitulo{
            text-align: left;
        }
        label{
          font-weight: bold;
        }
    </style>
</head>
<body class="container">
    <form id="frmFiltro" method="post" style="margin-top: 10px;">
      <fieldset class="p-4">
        <legend><b>Pesquisar</b></legend>
        <div class="row">
          <div class="col-12 col-sm-3 form-group" style="margin-bottom: 10px;">
            <label for="si06_sequencial">Sequencial:</label>
            <?php
              db_input(
                'si06_sequencial',
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
                    'validate-maxlength-message'        => 'O Sequencial deve ter no máximo 10 caracteres',
                    'validate-no-special-chars-message' => 'O Sequencial não deve conter aspas simples, ponto e vírgula ou porcentagem',
                    'validate-integer-message'          => 'O campo Sequencial deve conter apenas numeros'
                ]
              );
            ?>
          </div>
          <div class="col-12 col-sm-3 form-group" style="margin-bottom: 10px;">
            <label for="si06_numeroprc">Processo Licitatório:</label>
            <?php
               db_input(
                'si06_numeroprc',
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
            <label for="si06_numlicitacao">Número Licitação:</label>
            <?php
              db_input(
                'si06_numlicitacao',
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
          <div class="col-12 col-sm-3 form-group" style="margin-bottom: 10px;">
            <label for="si06_numeroadm">Número do Processo de Adesão:</label>
            <?php
              db_input(
                'si06_numeroadm',
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
                    'validate-maxlength-message'        => 'O Sequencial deve ter no máximo 10 caracteres',
                    'validate-no-special-chars-message' => 'O Sequencial não deve conter aspas simples, ponto e vírgula ou porcentagem',
                    'validate-integer-message'          => 'O campo Sequencial deve conter apenas numeros'
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
        <div class="col-12" id="containTabelaAdesaoDePrecos"></div>
      </div>
    </div>
</body>
</html>
<script>
  const url = 'con4_adesaoregistroprecos.RPC.php';
  const form = document.getElementById('frmFiltro');
  const btnFiltrar = document.getElementById('btnFiltrar');
  const btnLimpar = document.getElementById('btnLimpar');
  const btnNovo = document.getElementById('btnNovo');
  const si06_sequencial = document.getElementById('si06_sequencial');
  const si06_numeroprc = document.getElementById('si06_numeroprc');
  const si06_numlicitacao = document.getElementById('si06_numlicitacao');
  const si06_numeroadm = document.getElementById('si06_numeroadm');
  const oGridAdesaoRegistroPreco = new DBGrid('containTabelaAdesaoDePrecos');
  const formatData = new Intl.DateTimeFormat('pt-BR', { dateStyle: 'short', timeZone: 'America/Sao_Paulo' });
  const validator = initializeValidation('#frmFiltro');
  const limit = 30;

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

  if (si06_sequencial) {
    si06_sequencial.addEventListener('input', function (e) {
      validateChangeInteger('si06_sequencial');
    });
  }

  if (si06_numeroprc) {
    si06_numeroprc.addEventListener('input', function (e) {
      validateChangeInteger('si06_numeroprc');
    });
  }

  if (si06_numlicitacao) {
    si06_numlicitacao.addEventListener('input', function (e) {
      validateChangeInteger('si06_numlicitacao');
    });
  }

  if (si06_numeroadm) {
    si06_numeroadm.addEventListener('input', function (e) {
      validateChangeInteger('si06_numeroadm');
    });
  }

  initGrid();
  function initGrid(){
    oGridAdesaoRegistroPreco.nameInstance = 'oGridAdesaoRegistroPreco';
    oGridAdesaoRegistroPreco.setCellAlign([
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

    oGridAdesaoRegistroPreco.setCellWidth([
      '100px', //si06_sequencial
      '350px', //dl_orgao_gerenciador
      '150px', //si06_numeroprc
      '150px', //si06_anoproc
      '150px', //si06_numlicitacao
      '150px', //si06_dataadesao
      '100px', //si06_dataata
      '400px', //si06_objetoadesao
      '350px', //dl_resp_aprovacao
      '250px', //si06_numeroadm
      '150px', //si06_anocadastro
      '200px', //si06_nummodadm
      '150px', //si06_anomodadm
      '150px', //si06_codunidadesubant
      '100px'
    ]);

    oGridAdesaoRegistroPreco.setHeaderOrderable([
      {orderable: true, slug: 'si06_sequencial', default: true, order: 'desc'},
      {orderable: true, slug: 'dl_orgao_gerenciador'},
      {orderable: true, slug: 'si06_numeroprc'},
      {orderable: true, slug: 'si06_anoproc'},
      {orderable: true, slug: 'si06_numlicitacao'},
      {orderable: true, slug: 'si06_dataadesao'},
      {orderable: true, slug: 'si06_dataata'},
      {orderable: true, slug: 'si06_objetoadesao'},
      {orderable: true, slug: 'dl_resp_aprovacao'},
      {orderable: true, slug: 'si06_numeroadm'},
      {orderable: true, slug: 'si06_anocadastro'},
      {orderable: true, slug: 'si06_nummodadm'},
      {orderable: true, slug: 'si06_anomodadm'},
      {orderable: true, slug: 'si06_codunidadesubant'},
      {orderable: false, slug: ''},
    ]);

    oGridAdesaoRegistroPreco.setHeader([
      'Sequencial', //si06_sequencial
      'Orgão Gerenciador', //dl_orgao_gerenciador
      'Processo Licitatório', // si06_numeroprc
      'Exercício Licitação', // si06_anoproc
      'Número Modalidade', //si06_numlicitacao
      'Data da Adesão', // si06_dataadesao
      'Data da ATA', // si06_dataata
      'Objeto da Adesão', //si06_objetoadesao
      'Resp. Aprovação', //dl_resp_aprovacao
      'Número do Processo de Adesão', //si06_numeroadm
      'Exercício Cadastro', //si06_anocadastro
      'Número da Modalidade', //si06_nummodadm
      'Ano do Processo', // si06_anomodadm
      'Cód. Unidade', // si06_codunidadesubant
      'Ações'
    ]);

    oGridAdesaoRegistroPreco.allowSelectColumns(true);
    oGridAdesaoRegistroPreco.setHeight(450);
    oGridAdesaoRegistroPreco.showV2(document.getElementById('containTabelaAdesaoDePrecos'));
    oGridAdesaoRegistroPreco.clearAll(true);
    oGridAdesaoRegistroPreco.inicializaTooltip();
    oGridAdesaoRegistroPreco.paginatorInitialize((pageNumber) => {
      loading(pageNumber);
    });
    oGridAdesaoRegistroPreco.orderableInitialize((activeSortColumns) => {
      loading(1, activeSortColumns);
    });
    oGridAdesaoRegistroPreco.initializeInpuSearch((search) => {
      loading(1);
    })
  }

  function loading(offset = 1, activeSortColumns = [], search = ''){
    let oParam = new Object();
    oParam.exec = 'listagemAdesaoRegistroPrecos';
    oParam.si06_sequencial = document.getElementById('si06_sequencial').value;
    oParam.si06_numeroprc = document.getElementById('si06_numeroprc').value;
    oParam.si06_numlicitacao = document.getElementById('si06_numlicitacao').value;
    oParam.si06_numeroadm = document.getElementById('si06_numeroadm').value;
    oParam.offset = offset;
    oParam.limit = limit;
    oParam.orderable = activeSortColumns.length > 0 ? activeSortColumns : oGridAdesaoRegistroPreco.getActiveSortColumns();
    oParam.search = oGridAdesaoRegistroPreco.getInputSearch() || '';
    oGridAdesaoRegistroPreco.showLoading();
    new Ajax.Request(
      url,
      {
        method: 'post',
        parameters: 'json=' + Object.toJSON(oParam),
        onComplete: (oAjax) => {
          let oRetorno = JSON.parse(oAjax.responseText);
          oGridAdesaoRegistroPreco.clearAll(true);
          if(oRetorno.data.adesoes.length <= 0){
            Swal.fire({
                icon: 'warning',
                title: 'Atenção!',
                text: 'Ops, nenhuma Adesão foi encontrada.',
            });
          }
          
          oRetorno.data.adesoes.forEach(function(oValue, iSeq){
            let aLinha = new Array();
            aLinha[0] = oValue.si06_sequencial || ' - ';
            aLinha[1] = `<div class="tooltip-target" data-tooltip="${oValue.dl_orgao_gerenciador.trim() || ''}">${oValue.dl_orgao_gerenciador.trim() || ' - '}</div>`;
            aLinha[2] = oValue.si06_numeroprc || ' - ';
            aLinha[3] = oValue.si06_anoproc || ' - ';
            aLinha[4] = oValue.si06_numlicitacao || ' - ';
            aLinha[5] = (oValue.si06_dataadesao && oValue.si06_dataadesao.length > 0)? formatData.format(new Date(oValue.si06_dataadesao + 'T00:00:00')) : ' - ';
            aLinha[6] = (oValue.si06_dataata && oValue.si06_dataata.length > 0)? formatData.format(new Date(oValue.si06_dataata + 'T00:00:00')) : ' - ';
            aLinha[7] = `<div class="tooltip-target" data-tooltip="${oValue.si06_objetoadesao.trim() || ''}">${oValue.si06_objetoadesao.trim() || ' - '}</div>`;
            aLinha[8] = `<div class="tooltip-target" data-tooltip="${oValue.dl_resp_aprovacao.trim() || ''}">${oValue.dl_resp_aprovacao.trim() || ' - '}</div>`;
            aLinha[9] = oValue.si06_numeroadm || ' - ';
            aLinha[10] = oValue.si06_anocadastro || ' - ';
            aLinha[11] = oValue.si06_nummodadm || ' - ';
            aLinha[12] = oValue.si06_anomodadm || ' - ';
            aLinha[13] = oValue.si06_codunidadesubant || ' - ';
            // const anoAtual = new Date().getFullYear();
            const anoAtual = <?= db_getsession('DB_anousu') ?>;
            const anoComparar = new Date(oValue.si06_dataadesao).getFullYear();

            aLinha[14] = `
              <a
                  href="#"
                  style="margin: 0 5px; color: #007bff; font-weigth: bold;"
                  onclick="openEdicao(
                      event,
                      ${oValue.si06_sequencial},
                      ${anoAtual <= oValue.si06_anomodadm || anoAtual <= anoComparar}
                  )"
              ><i class="fa fa-pen"></i></a>
              <a
                  href="#"
                  style="margin: 0 5px; color: #dc3545; font-weigth: bold;"
                  onclick="openExclusao(
                      event,
                      ${oValue.si06_sequencial}
                  )"
              ><i class="fa fa-trash"></i></a>
            `;

            oGridAdesaoRegistroPreco.addRow(aLinha)
          });

          let totalPages = (oRetorno.data.total) ? (oRetorno.data.total / limit) : 0;
          oGridAdesaoRegistroPreco.setTotalItens(oRetorno.data.total || 0);
          oGridAdesaoRegistroPreco.renderRows();
          oGridAdesaoRegistroPreco.renderPagination(totalPages, offset);
          oGridAdesaoRegistroPreco.hideLoading();
          oGridAdesaoRegistroPreco.fixedColumns(0, 1);
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
    oGridAdesaoRegistroPreco.resetOrderableColumns((activeSortColumns) => {
        loading(1, activeSortColumns);
    });
  })

  btnLimpar.addEventListener('click', (event) => {
      document.getElementById('frmFiltro').reset();
  });

  btnNovo.addEventListener('click', (event) => {
    event.preventDefault();

    let sUrl = 'con4_adesaoregistroprecos002.php';

    if(typeof db_iframe_adesao_registro_de_precos != 'undefined'){
      db_iframe_adesao_registro_de_precos.jan.location.href = sUrl;
      db_iframe_adesao_registro_de_precos.setAltura("100%");
      db_iframe_adesao_registro_de_precos.setLargura("100%");
      db_iframe_adesao_registro_de_precos.setPosX("0");
      db_iframe_adesao_registro_de_precos.setPosY("0");
      db_iframe_adesao_registro_de_precos.setCorFundoTitulo('#316648');
      db_iframe_adesao_registro_de_precos.show();
      document.querySelector(`div.DBJanelaIframe`).style.position = 'fixed';
      return false;
    }

    let frame = js_OpenJanelaIframe(
        'CurrentWindow.corpo',
        'db_iframe_adesao_registro_de_precos',
        sUrl,
        'Adesão de Registro de Preços > Inclusão',
        false
    );

    if(frame){
        frame.hide();
        db_iframe_adesao_registro_de_precos.setAltura("100%");
        db_iframe_adesao_registro_de_precos.setLargura("100%");
        db_iframe_adesao_registro_de_precos.setPosX("0");
        db_iframe_adesao_registro_de_precos.setPosY("0");
        db_iframe_adesao_registro_de_precos.setCorFundoTitulo('#316648');
        db_iframe_adesao_registro_de_precos.show();
        document.querySelector(`div.DBJanelaIframe`).style.position = 'fixed';
    }
  });

  function close(refresh = true){
    if(typeof db_iframe_adesao_registro_de_precos != 'undefined'){
      db_iframe_adesao_registro_de_precos.hide();
    }

    if(typeof db_iframe_adesao_registro_de_precos_edit != 'undefined'){
      db_iframe_adesao_registro_de_precos_edit.hide();
    }

    if(refresh){
      loading();
    }
  }

  function openEdicao(event, si06_sequencial, showDocumento = false){
    event.preventDefault();
    let currentUrl = new URL('con4_adesaoregistroprecos002.php', window.location.href);
    currentUrl.searchParams.set('si06_sequencial', si06_sequencial);
    currentUrl.searchParams.set('showDocumento', showDocumento);
    if(typeof db_iframe_adesao_registro_de_precos_edit != 'undefined'){
      window.scroll({
        top:0,
        left:0,
        behavior: 'smooth'
      });
      db_iframe_adesao_registro_de_precos_edit.setCorFundoTitulo('#316648');
      db_iframe_adesao_registro_de_precos_edit.jan.location.href = currentUrl.toString();
      db_iframe_adesao_registro_de_precos_edit.setAltura("100%");
      db_iframe_adesao_registro_de_precos_edit.setLargura("100%");
      db_iframe_adesao_registro_de_precos_edit.setPosX("0");
      db_iframe_adesao_registro_de_precos_edit.setPosY("0");
      db_iframe_adesao_registro_de_precos_edit.show();
      document.querySelector(`div.DBJanelaIframe`).style.position = 'fixed';
      return false;
    }

    let frame = js_OpenJanelaIframe(
      '',
      'db_iframe_adesao_registro_de_precos_edit',
      currentUrl.toString(),
      'Adesão de Registro de Preços > Alteração',
      true
    );

    if(frame){
      frame.hide();
      db_iframe_adesao_registro_de_precos_edit.show();
      db_iframe_adesao_registro_de_precos_edit.setCorFundoTitulo('#316648');
      db_iframe_adesao_registro_de_precos_edit.setAltura("100%");
      db_iframe_adesao_registro_de_precos_edit.setLargura("100%");
      db_iframe_adesao_registro_de_precos_edit.setPosX("0");
      db_iframe_adesao_registro_de_precos_edit.setPosY("0");
      document.querySelector(`div.DBJanelaIframe`).style.position = 'fixed';
    }
  }

  function openExclusao(event, si06_sequencial){
    event.preventDefault();
    Swal.fire({
      title: 'Deseja remover a adesão de registro de preço?',
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
        oParam.exec = 'removeAdesaoPreco';
        oParam.si06_sequencial = si06_sequencial;
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
                      text: 'Adesão de Registro de Preço removida com sucesso!',
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

</script>