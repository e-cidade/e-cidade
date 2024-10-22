<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
db_app::load("estilos.bootstrap.css");
db_app::load("sweetalert.js");
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBFileUpload.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <style>
        body {
            background-color: #f5fffb;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            font-size: 12px !important;

        }
        .container {
            margin-top: 50px;
            background-color: #FFFFFF;
            padding: 20px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: Arial;
            font-size: 12px !important;
        }
        .inputUploadFile{
            width: 254px;
            height: 32px;
        }
    </style>
</head>
<body>

<div class="container">
    <form action="../../../FrontController.php" method="post" enctype="multipart/form-data">
        <fieldset class="form-container">
            <legend><strong>Importação de Processsos Sem RP</strong></legend>
            <div id="ctnImportacao"></div>
        </fieldset>
    </form>
    <div style="margin-top: 20px">
        <button class="btn btn-success" type="button" onclick="migrarProcesso();">Migrar Processos</button>
        <button class="btn btn-secondary" type="button" onclick="js_gerarPlanilha();">Gerar Planilha</button>
    </div>
</div>

</body>
<script>
    let oWindowAuxVinculos = '';
    let oGridVinculos      = '';
    let sRpc               = 'com1_migracaocompras.RPC.php';
    let lTemInconsistencia = false;

    let oFileUpload = new DBFileUpload( {callBack: retornoEnvioArquivo} );
    oFileUpload.show($('ctnImportacao'));

    document.querySelector(".btnUploadFile").addClassName('btn btn-secondary');
    /**
     * Função de retorno ao selecionar um arquivo para upload
     * Valida se foi registrado algum erro ou se o arquivo possui uma extensão inválida
     * @param oRetorno
     * @returns {boolean}
     */
    function retornoEnvioArquivo( oRetorno ) {

        if (oRetorno.error) {

            alert(oRetorno.error);
            $('btnProcessar').disabled = true;
            return false;
        }

        if( oRetorno.extension.toLowerCase() != 'xlsx' ) {

            alert( _M( MENSAGENS_SAU4_IMPORTACNES001 + 'arquivo_invalido' ) );
            $('btnProcessar').disabled = true;
            return false;
        }

        $('btnProcessar').disabled = false;
    }

    function migrarProcesso() {

        let oParametros                 = {};
        oParametros.sExecuta        = 'migrarsubgrupos';
        oParametros.sNomeArquivo    = oFileUpload.file;
        oParametros.sCaminhoArquivo = oFileUpload.filePath;

        let oAjaxRequest = new AjaxRequest( sRpc, oParametros, retornoMigrarSubgrupos );
        // oAjaxRequest.setMessage( "Aguarde! Migrando Subgrupos..." );
        oAjaxRequest.execute();
    }

    function retornoMigrarSubgrupos( oRetorno, lErro ) {
        if (oRetorno.iStatus == 1){
            alert("Importação Ralizada com sucesso !");
        }else{
            alert(oRetorno.sMensagem);
        }
    }


    function js_gerarPlanilha(){
        const jan = window.open('com1_planilhaprocessossemrp.php');
    }
</script>
</html>
