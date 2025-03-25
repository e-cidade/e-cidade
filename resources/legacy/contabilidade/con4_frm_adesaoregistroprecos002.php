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
<style>
  .table_header.checkbox {
    width: 28px !important;
  }
</style>
<form id="frmEdicaoBloco" method="post" style="margin-top: 10px;">
  <fieldset class="p-4">
    <legend>Edição em Bloco</legend>
    <div class="row">
      <div class="col-12 col-sm-7 form-group mb-2">
        <label for="si07_fornecedor">
          <?php db_ancora('Fornecedor Ganhador:', "pesquisaOrgaoGerenciador(true);", 1) ?>
        </label>
        <div class="row">
          <div class="col-12 col-sm-3 mb-2 pl-0">
            <?php
                db_input(
                    'si07_fornecedor',
                    4,
                    '',
                    true,
                    'text',
                    1,
                    " onchange='pesquisaOrgaoGerenciador(false);' ",
                    '',
                    '',
                    '',
                    '',
                    'form-control',
                    [
                        'validate-minlength'                => "1",
                        'validate-maxlength'                => '10',
                        'validate-no-special-chars'         => 'true',
                        'validate-integer'                  => 'true',
                        'validate-minlength-message'        => "O campo Fornecedor Ganhador deve ter pelo menos 1 caracteres",
                        'validate-maxlength-message'        => 'O campo Fornecedor Ganhador deve ter no máximo 10 caracteres',
                        'validate-no-special-chars-message' => 'O campo Fornecedor Ganhador não deve conter aspas simples, ponto e vírgula ou porcentagem',
                        'validate-integer-message'          => 'O campo Fornecedor Ganhador deve conter apenas numeros'
                    ]
                );
            ?>
          </div>
          <div class="col-12 col-sm-9 mb-2 pr-0">
              <?php
                  db_input(
                      'z01_nomef',
                      40,
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
                          'validate-required-message'  => "O campo Fornecedor Ganhador não foi informado",
                          'validate-minlength-message' => "O campo Fornecedor Ganhador deve ter pelo menos 1 caracteres",
                      ]
                  );
              ?>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-2 form-group mb-2">
        <button type="submit" class="btn btn-success" id="btnAplicar" style="margin-top: 0.9em;">Aplicar</button>
      </div>
    </div>
  </fieldset>
</form>
<div class="inner-container">
  <div class="row">
    <div class="col-12 mt-4">
      <div id="gridListagemItens"></div>
    </div>
    <div class="col-12 col-sm-12 mt-3 text-center">
      <button type="button" class="btn btn-success" id="btnSalvar">Salvar</button>
    </div>
  </div>
</div>
<script>
  (function(){
    const oGridItens = new DBGrid('gridListagemItens');
    const btnAplicar = document.getElementById('btnAplicar');
    const btnSalvar = document.getElementById('btnSalvar');
    let adesao = null;

    const inputsValidateInteger = [
      'si07_fornecedor'
    ];

    inputsValidateInteger.forEach(function(value, index) {
      document.getElementById(value).addEventListener('input', function(e){
        validateChangeInteger(value)
      });
    });

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

    window.oGridItens = oGridItens;

    function initGridPorItem(){
      oGridItens.nameInstance = 'oGridItens';
      oGridItens.setCheckbox(0);
      const aAligns = [
        'center',
        'center',
        'center',
        'center',
      ];
      const aWidth = [
        '75px',
        '70px',
        '200px',
        '90px',
      ];
      const aHeaders = [
        'Ordem',
        'Item',
        'Descrição Item',
        'Unidade',
      ];

      if(adesao.si06_criterioadjudicacao == 3){
        aAligns.push('center');
        aWidth.push('100px');
        aHeaders.push('Qtd. Aderida');

        aAligns.push('center');
        aWidth.push('120px');
        aHeaders.push('Qtd. Licitada');

        aAligns.push('center');
        aWidth.push('120px');
        aHeaders.push('Preço Unitário');
      }

      aAligns.push('center');
      aWidth.push('300px');
      aHeaders.push('Fornecedor Ganhador');

      if(adesao.si06_processoporlote == 1){
        aAligns.push('center');
        aWidth.push('120px');
        aHeaders.push('Número do Lote');

        aAligns.push('center');
        aWidth.push('150px');
        aHeaders.push('Descrição do Lote');
      }

      if(adesao.si06_criterioadjudicacao != 3){
        aAligns.push('center');
        aWidth.push('150px');
        aHeaders.push('Percentual %');
      }

      aAligns.push('center');
      aWidth.push('100px');
      aHeaders.push('Ações');
      
      aAligns.push('center');
      aWidth.push('10px');
      aHeaders.push('json');

      oGridItens.setCellAlign(aAligns);
      oGridItens.setCellWidth(aWidth);
      oGridItens.setHeader(aHeaders);

      oGridItens.allowSelectColumns(true);
      oGridItens.setHeight(300);
      oGridItens.aHeaders[aHeaders.length].lDisplayed = false;
      oGridItens.showV2(document.getElementById('gridListagemItens'));
      oGridItens.clearAll(true);
      oGridItens.inicializaTooltip();
      oGridItens.inicializaTooltip();

      // gridListagemItens.selectSingle = function(oCheckbox, sRow, oRow){
      //     // getItensChange(oRow);
      // }
    }

    function loadingItens(offset = 1){
      let oParam = {};
      oParam.exec = 'getItensAdesaoRegistroPrecos';
      oParam.si06_sequencial = si06_sequencial;
      oGridItens.showLoading();
      new Ajax.Request(
        url,
        {
          method: 'post',
          parameters: 'json=' + Object.toJSON(oParam),
          onComplete: function(oAjax) {
            let oRetorno = JSON.parse(oAjax.responseText);
            oGridItens.clearAll(true);

            if(oRetorno.data.length <= 0){
                oGridItens.hideLoading();
                return false;
            }

            oRetorno.data.forEach(function(oValue, iSeq){
              let aLinha = new Array();
              aLinha.push(oValue.pc11_seq);
              aLinha.push(oValue.pc01_codmater);
              aLinha.push(`<div class="tooltip-target" data-tooltip="${oValue.pc01_descrmater.trim() || ''}">${oValue.pc01_descrmater.trim() || ''}</div>`);
              aLinha.push(oValue.m61_descr);
              if(adesao.si06_criterioadjudicacao == 3){
                aLinha.push(oValue.pc11_quant);
                aLinha.push(`
                  <div class="from-group">
                    <input 
                      type="text" 
                      name="si07_quantidadelicitada" 
                      class="form-control" 
                      value="${oValue.si07_quantidadelicitada || 0}" 
                      oninput="mascaraQtdLicitada(this)"
                      maxlength="999999"
                    >
                  </div>
                `);
                aLinha.push(`
                  <div class="from-group">
                    <input 
                      type="text" 
                      name="si07_precounitario" 
                      class="form-control" 
                      value="${oValue.si07_precounitario || 0}"
                      oninput="mascaraPrecoUnitario(this)"
                      maxlength="999999"
                    >
                  </div>
                `);
              }

              aLinha.push(`
                <div class="form-group" style="display: flex; align-items: center; gap: 5px;">
                  <input type="text" name="si07_fornecedor" class="form-control" value="${oValue.z01_nome || ''}" readonly>
                  <a style="margin: 0 5px; color: #dc3545; font-weigth: bold;" onclick="exclusaoFornecedor(event)"><i class="fa fa-trash"></i></a>
                </div>
              `);

              if(adesao.si06_processoporlote == 1){
                aLinha.push(`
                  <div class="from-group">
                    <input type="text" name="si07_numerolote" class="form-control" value="${oValue.si07_numerolote || ''}" oninput="validaInputNumero(this)" maxlength="9999">
                  </div>
                `);
                aLinha.push(`
                  <div class="from-group">
                    <input type="text" name="si07_descricaolote" class="form-control" value="${oValue.si07_descricaolote || ''}" oninput="validaInputText(this)" maxlength="250">
                  </div>
                `);
              }
              if(adesao.si06_criterioadjudicacao != 3){
                aLinha.push(`
                  <div class="from-group">
                    <input 
                      type="text" 
                      name="percentual" 
                      class="form-control" 
                      value="${oValue.percentual || ''}"
                      oninput="mascaraPrecoUnitario(this)"
                      maxlength="999999"
                    >
                  </div>
                `);
              }

              if(oValue.si07_sequencial){
                aLinha.push(`
                  <a
                      href="#"
                      style="margin: 0 5px; color: #dc3545; font-weigth: bold;"
                      onclick="openExclusaoItemRegPreco(
                          event,
                          ${oValue.si07_sequencial}
                      )"
                  ><i class="fa fa-trash"></i></a>
                `);
              } else {
                aLinha.push('');
              }

              aLinha.push(JSON.stringify(oValue));
              oGridItens.addRow(aLinha, false, false, false);
            });

            oGridItens.setTotalItens(oRetorno.total || 0);
            oGridItens.renderRows();
            oGridItens.hideLoading();
            oGridItens.fixedColumns(1, 2);
          }
        }
      );
    }

    function pesquisaOrgaoGerenciador(mostra) { 
      if(mostra){
        var sUrl = 'func_cgm.php?funcao_js=parent.carregaCgmOrgaoAncora|z01_numcgm|z01_nome';
        if(typeof db_iframe_cgm != 'undefined'){
          db_iframe_cgm.jan.location.href = sUrl;
          db_iframe_cgm.setAltura("100%");
          db_iframe_cgm.setLargura("100%");
          db_iframe_cgm.setPosX("0");
          db_iframe_cgm.setPosY("0");
          db_iframe_cgm.setCorFundoTitulo('#316648');
          db_iframe_cgm.show();
          return false;
        }

        let frame = js_OpenJanelaIframe(
          '',
          'db_iframe_cgm',
          sUrl,
          'Pesquisar Fornecedor',
          false,
          '0'
        );

        if(frame){
          frame.setAltura("100%");
          frame.setLargura("100%");
          frame.setPosX("0");
          frame.setPosY("0");
          frame.hide();
          frame.setCorFundoTitulo('#316648');
          db_iframe_cgm.show();
        }
      } else if(document.getElementById('si07_fornecedor').value != '') {
        js_OpenJanelaIframe(
          '', 
          'db_iframe_cgm', 
          'func_cgm.php?pesquisa_chave=' + document.getElementById('si07_fornecedor').value + '&funcao_js=parent.carregaCgmOrgao', 
          'Pesquisa', 
          false
        );
      } else {
        document.getElementById('si07_fornecedor').value = '';
        document.getElementById('z01_nomef').value = '';
      }
    }

    function carregaCgmOrgaoAncora(chave1, chave2){
      document.getElementById('si07_fornecedor').value = chave1;
      document.getElementById('z01_nomef').value = chave2;
      db_iframe_cgm.hide();
    }

    function carregaCgmOrgao(erro, chave){
      if(erro == true){
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'Nenhum Orgão CGM foi encontrado.',
        });
        document.getElementById('si07_fornecedor').focus();
        document.getElementById('si07_fornecedor').value = '';
        return false;
      }
      
      document.getElementById('z01_nomef').value = chave
    }

    function mascaraPrecoUnitario(input) {
      let valorAtual = input.value;

      valorAtual = valorAtual.replace(/[^0-9.\-]/g, '');
      valorAtual = valorAtual.replace(',', '.');

      let partes = valorAtual.split('.');
      if (partes.length > 2) {
        valorAtual = partes[0] + '.' + partes.slice(1).join('');
      }

      if (partes.length === 2 && partes[1].length > 4) {
        valorAtual = partes[0] + '.' + partes[1].substring(0, 4);
      }

      input.value = valorAtual.substring(0, 6);
    }

    function mascaraQtdLicitada(input) {
      let valorAtual = input.value;

      valorAtual = valorAtual.replace(/[^0-9.\-]/g, '');
      valorAtual = valorAtual.replace(',', '.');
      valorAtual = valorAtual;

      let partes = valorAtual.split('.');
      if (partes.length > 2) {
        valorAtual = partes[0] + '.' + partes.slice(1).join('');
      }

      if (partes.length === 2 && partes[1].length > 6) {
        valorAtual = partes[0] + '.' + partes[1].substring(0, 6);
      }

      input.value = valorAtual.substring(0, 6);
    }

    function validaInputNumero(input) {
      input.value = input.value.replace(/\D/g, '').substring(0, 4);
    }

    function validaInputText(input) {
      input.value = input.value.substring(0, 250);
    }

    function exclusaoFornecedor(event){
      const tr = event.target.closest('tr');
      const tbody = tr.parentNode;
      const index = Array.from(tbody.children).indexOf(tr);
      
      const aRow = oGridItens.aRows[index];
      let id = aRow.sId;
      const json = JSON.parse(aRow.aCells[aRow.aCells.length - 1].getValue());
      aRow.aCells.each(function (cell, index){
        const content = cell.getContent()
        if(typeof content == 'string'){
          const parser = new DOMParser();
          const html = parser.parseFromString(content, 'text/html')
          const input = html.querySelector('input');
          if(input){
            const value = document.querySelector(`#${cell.getId()} input`).value;              
            if(input.name == 'si07_fornecedor'){
              document.querySelector(`#${cell.getId()} input[name="si07_fornecedor"]`).value = ''
              json.si07_fornecedor = null;
            }
          }
        }
      });

      aRow.aCells[aRow.aCells.length - 1].setContent(JSON.stringify(json));
    }

    function openExclusaoItemRegPreco(event, si07_sequencial){
      event.preventDefault();
      Swal.fire({
        title: 'Deseja remover o item?',
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
          oParam.exec = 'removeItemAdesaoPrecos';
          oParam.si07_sequencial = si07_sequencial;
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
                        text: 'Item removido com sucesso!',
                    });
                    loadingItens();
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

    function getItensChange(isAplicar = false){
      const itensChange = [];
      oGridItens.aRows.each(function(aRow, iIndice){
        let checked = document.getElementById(aRow.aCells[0].getId()).querySelector('input[type="checkbox"]').checked;
        
        if(!checked){
          return false;
        }

        let id = aRow.sId;
        const json = JSON.parse(aRow.aCells[aRow.aCells.length - 1].getValue());

        // 149712
        aRow.aCells.each(function (cell, index){
          const content = cell.getContent()
          if(typeof content == 'string'){
            const parser = new DOMParser();
            const html = parser.parseFromString(content, 'text/html')
            const input = html.querySelector('input');
            if(input){
              const value = document.querySelector(`#${cell.getId()} input`).value;              
              if(input.name == 'si07_fornecedor' && isAplicar){
                document.querySelector(`#${cell.getId()} input[name="si07_fornecedor"]`).value = document.getElementById('z01_nomef').value
                json.si07_fornecedor = document.getElementById('si07_fornecedor').value;
              } else if(input.name == 'percentual'){
                json.percentual = value;
              } else if(input.name == 'si07_descricaolote'){
                json.si07_descricaolote = value;
              } else if(input.name == 'si07_numerolote'){
                json.si07_numerolote = value;
              } else if(input.name == 'si07_precounitario'){
                json.si07_precounitario = value;
              } else if(input.name == 'si07_quantidadelicitada'){
                json.si07_quantidadelicitada = value;
              }
            }
          }
        });

        aRow.aCells[aRow.aCells.length - 1].setContent(JSON.stringify(json));

        itensChange.push(json);
      });

      return itensChange;
    }

    function validateItens(itensChange) {
      if (!itensChange || itensChange.length === 0) {
        Swal.fire({
          icon: 'warning',
          title: 'Atenção!',
          text: 'Selecione ao menos um item da lista.',
        });
        return false;
      }

      const erro = itensChange.some(value => {
        if (
          adesao.si06_criterioadjudicacao === 3 &&
          (
            (!value.si07_quantidadelicitada || value.si07_quantidadelicitada == 0) 
            || (!value.si07_precounitario || value.si07_precounitario == 0)  
            || (!value.si07_fornecedor || value.si07_fornecedor == 0)
          )
        ) {
          Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: `Os dados do item ${value.pc01_codmater} - ${value.pc01_descrmater} não foram preenchidos corretamente.`,
          });
          return true;
        }

        if (
          adesao.si06_processoporlote === 1 &&
          (
            (!value.si07_numerolote || value.si07_numerolote == 0) 
            || (!value.si07_descricaolote || value.si07_descricaolote == 0)
          )
        ) {
          Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: `Os dados do item ${value.pc01_codmater} - ${value.pc01_descrmater} não foram preenchidos corretamente.`,
          });
          return true;
        }

        if (
          adesao.si06_criterioadjudicacao !== 3 &&
          (!value.percentual || value.percentual == 0)
        ) {
          Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: `Os dados do item ${value.pc01_codmater} - ${value.pc01_descrmater} não foram preenchidos corretamente.`,
          });
          return true;
        }

        return false;
      });

      return !erro;
    }

    function init(){
      let oParam = {};
      oParam.exec = 'getAdesaoByCodigo';
      oParam.si06_sequencial = si06_sequencial;
      new Ajax.Request(
        url,
        {
          method: 'post',
          parameters: 'json=' + Object.toJSON(oParam),
          onComplete: async function(oAjax){
            let oRetorno = JSON.parse(oAjax.responseText);
            adesao = oRetorno.data.adesao;

            initGridPorItem();
            loadingItens();
          }
        }
      );
    }

    init();

    if(btnAplicar != null){
      btnAplicar.addEventListener('click', function(e){
        e.preventDefault();

        if(!document.getElementById('si07_fornecedor').value){
          Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'Preencha o fornecedor ganhador.',
          });
          return false;
        }

        const itens = getItensChange(true);

        if(itens.length < 1){
          Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'Selecione ao menos um item da lista.',
          });
        }

        // document.querySelector('#frmEdicaoBloco #si07_fornecedor').value = '';
        // document.querySelector('#frmEdicaoBloco #z01_nomef').value = '';
      });
    }

    if(btnSalvar != null){
      btnSalvar.addEventListener('click', function(e){
        e.preventDefault();
        const itens = getItensChange();
        
        if(itens.length <= 0){
          Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'Selecione ao menos um item da lista.',
          });
          return false;
        }


        if(!validateItens(itens)){
          return false;
        }

        let oParam  = {};
        oParam.exec = 'salvarItensAdesaoRegistroPrecos';
        oParam.itens = itens;

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
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: (oAjax) => {
              let oRetorno = JSON.parse(oAjax.responseText);
              if(oRetorno.status == 200){
                Swal.fire({
                  icon: 'success',
                  title: 'Sucesso!',
                  text: oRetorno.message,
                });

                loadingItens();
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

      });
    }

    window.pesquisaOrgaoGerenciador = pesquisaOrgaoGerenciador;
    window.carregaCgmOrgaoAncora = carregaCgmOrgaoAncora;
    window.carregaCgmOrgao = carregaCgmOrgao;

    window.mascaraPrecoUnitario = mascaraPrecoUnitario;
    window.mascaraQtdLicitada = mascaraQtdLicitada;
    window.validaInputNumero = validaInputNumero;
    window.validaInputText = validaInputText;

    window.exclusaoFornecedor = exclusaoFornecedor;
    window.openExclusaoItemRegPreco = openExclusaoItemRegPreco;
  })();
</script>