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
<form id="frmDocumento" method="post" style="margin-top: 10px;">
  <input type="hidden" name="json[exec]" value="uploadAdesaoRegPrecoDocumento">
  <input type="hidden" name="json[si06_sequencial]" value="<?= $si06_sequencial ?>">
  <fieldset class="p-4">
    <legend>Adicionar Documento</legend>
    <div class="row">
      <div class="col-12 col-sm-12 form-group mb-2">
        <label for="">Documento:</label>
        <div class="custom-file mt-2">
          <label for="documento">
            <button type="button" class="btn btn-success">Procurar</button>
          </label>
          <input type="file" name="documento" id="documento" class="custom-file-input" accept="application/pdf">
          <span class="custom-file-name">Escolher arquivo</span>
        </div>
      </div>
      <div class="col-12 col-sm-12 text-center form-group mb-2 mt-4" style="text-align: center;">
        <button type="submit" class="btn btn-success" id="btnAplicar">Salvar</button>
      </div>
    </div>
  </fieldset>
</form>
<div class="inner-container">
  <div class="row">
    <div class="col-12 mt-4">
      <div id="gridListagemDocumentos"></div>
    </div>
  </div>
</div>
<script>
  (function(){
    const btnAplicar = document.getElementById('btnAplicar');
    const oGridDocumentos = new DBGrid('gridListagemDocumentos');
    window.oGridDocumentos = oGridDocumentos;

    document.getElementById('documento').addEventListener('change', function (event) {
      let fileName = event.target.files[0] ? event.target.files[0].name : 'Escolher arquivo';
      document.querySelector('.custom-file-name').textContent = fileName;
    });

    function initGridDocumentos(){
      oGridDocumentos.nameInstance = 'oGridDocumentos';
      const aAligns = [
        'center',
        'center',
        'center',
      ];
      const aWidth = [
        '80px',
        '100px',
        '90px',
      ];
      const aHeaders = [
        'Código',
        'Arquivo',
        'Ações',
      ];

     
      oGridDocumentos.setCellAlign(aAligns);
      oGridDocumentos.setCellWidth(aWidth);
      oGridDocumentos.setHeader(aHeaders);

      oGridDocumentos.allowSelectColumns(true);
      oGridDocumentos.setHeight(300);
      oGridDocumentos.showV2(document.getElementById('gridListagemDocumentos'));
      oGridDocumentos.clearAll(true);
      oGridDocumentos.inicializaTooltip();
    }

    function loadingDocumentos(offset = 1){
      let oParam = {};
      oParam.exec = 'getDocumentosAdesaoRegistroPrecos';
      oParam.si06_sequencial = si06_sequencial;
      oGridDocumentos.showLoading();
      new Ajax.Request(
        url,
        {
          method: 'post',
          parameters: 'json=' + Object.toJSON(oParam),
          onComplete: function(oAjax) {
            let oRetorno = JSON.parse(oAjax.responseText);
            oGridDocumentos.clearAll(true);

            if(oRetorno.data.documentos.length <= 0){
                oGridDocumentos.setTotalItens(0);
                oGridDocumentos.hideLoading();
                return false;
            }

            oRetorno.data.documentos.forEach(function(oValue, iSeq){
              let aLinha = new Array();

              aLinha.push(oValue.sd1_sequencial);
              aLinha.push(oValue.sd1_nomearquivo);
              aLinha.push(`
                <a
                  href="#"
                    style="margin: 0 5px; color: #007bff; font-weigth: bold;"
                    onclick="openDownload(
                        event,
                        ${oValue.sd1_sequencial}
                    )"
                ><i class="fa fa-download"></i></a>
                <a
                    href="#"
                    style="margin: 0 5px; color: #dc3545; font-weigth: bold;"
                    onclick="openExclusao(
                        event,
                        ${oValue.sd1_sequencial}
                    )"
                ><i class="fa fa-trash"></i></a>
              `);
              oGridDocumentos.addRow(aLinha, false, false, false);
            });

            oGridDocumentos.setTotalItens(oRetorno.data.total || 0);
            oGridDocumentos.renderRows();
            oGridDocumentos.hideLoading();
            oGridDocumentos.fixedColumns(0, 0);
          }
        }
      );
    }

    function openDownload(event, sd1_sequencial){
      event.preventDefault();
      Swal.fire({
        title: 'Deseja realizar o Download do Documento?',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Sim',
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
        oParam.exec = 'downloadDocumentoAdesao';
        oParam.sd1_sequencial = sd1_sequencial;

        new Ajax.Request(
          url, 
          {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: function (oAjax) { 
              let oRetorno = JSON.parse(oAjax.responseText);
              if(oRetorno.status == 200){
                Swal.close();
                window.open("db_download.php?arquivo=" + oRetorno.data.nome);
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

    function openExclusao(event, sd1_sequencial){
      event.preventDefault();
      Swal.fire({
        title: 'Deseja realmente remover o documento?',
        text: 'Está ação não poderá ser defeita',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim',
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
        oParam.exec = 'removeDocumentoAdesaoPreco';
        oParam.sd1_sequencial = sd1_sequencial;
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
                      text: 'Documento removido com sucesso!',
                  });
                  loadingDocumentos();
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

    if(btnAplicar != null){
      btnAplicar.addEventListener('click', async function (e) { 
        e.preventDefault();
        
        if(!document.getElementById('documento').files.length){
          Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'Informe o arquivo.',
          });
          return false;
        }

        if(oGridDocumentos.getTotalItens() >= 1){
          Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'Já existe um arquivo anexado à adesão, verifique.',
          });
          return false;
        }

        const file = document.getElementById('documento').files[0];
        if (file) {
          const isPDF = file.name.toLowerCase().endsWith(".pdf");
          const isMimePDF = file.type === "application/pdf";

          if (!isPDF || !isMimePDF) {
            Swal.fire({
              icon: 'warning',
              title: 'Arquivo inválido!',
              text: 'Somente arquivos PDF são permitidos.',
            });
            document.getElementById('documento').value = "";
            return false;
          }
        }

        Swal.fire({
          title: 'Aguarde...',
          text: 'Estamos processando sua solicitação.',
          allowOutsideClick: false,
          didOpen: () => {
              Swal.showLoading();
          }
        });
        
        const formData = new FormData(document.getElementById('frmDocumento')); // Coleta os dados do formulário

        const resposta = await fetch(url, {
            method: "POST",
            body: formData, // Envia diretamente
        });

        const oRetorno = await resposta.json();

        if(oRetorno.status == 200){
          Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: oRetorno.message,
          });

          document.getElementById('frmDocumento').reset();
          document.querySelector('.custom-file-name').textContent = 'Escolher arquivo';

          loadingDocumentos();
          return false;
        }

        Swal.fire({
          icon: 'error',
          title: 'Erro!',
          text: oRetorno.message,
        });
      });
    }
    
    function init(){
      initGridDocumentos()
      loadingDocumentos()
    }

    init()

    window.openDownload = openDownload;
    window.openExclusao = openExclusao;
  })()
</script>