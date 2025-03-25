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
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <?php
        db_app::load("scripts.js", date('YmdHis'));
        db_app::load("strings.js");
        db_app::load("prototype.js");
        db_app::load("datagrid.widget.js");
        db_app::load("dbcomboBox.widget.js");
        db_app::load("dbmessageBoard.widget.js");
        db_app::load("dbtextField.widget.js");
        db_app::load("widgets/DBHint.widget.js");
        db_app::load("widgets/dbautocomplete.widget.js");
        db_app::load("widgets/windowAux.widget.js");
        db_app::load("estilos.css");
        db_app::load("mask.js");
        db_app::load("form.js");
        db_app::load("estilos.bootstrap.css");
        db_app::load("sweetalert.js");
        db_app::load("just-validate.js");
        db_app::load("tabsmanager.js");
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
            width: 1024px; /* Para garantir responsividade */
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
        .contain-buttons{
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
        }

        #tablegridListagemPorItemheader{
            width: calc(100% - 18px) !important;
        }

        #gridListagemPorItembody{
            width: 100% !important;
        }

        fieldset{
            padding: 10px;
        }
    </style>
</head>
<body class="container">
    <form action="" method="post" name="form1" id="frmCadastroLote">
        <input type="hidden" name="exec" value="inserirLote">
        <input type="hidden" name="l24_codliclicita" value="<?= $l20_codigo ?>">
        <fieldset>
            <legend>Lote</legend>
            <div class="row">
                <div class="col-12 col-sm-12 form-group mb-4">
                    <label for="l24_pcdesc">Descrição:</label>
                    <?php
                        db_input(
                            'l24_pcdesc',
                            10,
                            $l24_pcdesc,
                            true,
                            'text',
                            1,
                            '',
                            '',
                            '',
                            '',
                            '',
                            'form-control',
                            [
                                'validate-required'                 => "true",
                                'validate-minlength'                => "1",
                                'validate-maxlength'                => '250',
                                'validate-no-special-chars'         => 'true',
                                'validate-required-message'         => "A descrição do lote é obrigatório",
                                'validate-minlength-message'        => "A descrição do lote deve ter pelo menos 1 caracteres",
                                'validate-no-special-chars-message' => 'A descrição do lote não deve conter aspas simples, ponto e vírgula ou porcentagem',
                                'validate-maxlength-message'        => 'O descrição do lote deve ter no máximo 250 caracteres'
                            ]
                        );
                    ?>
                </div>
            </div>
        </fieldset>
        <div class="row" style="margin-top: 15px;">
            <div class="col-12 text-center">
                <button class="btn btn-success" type="button" id="btnInserirLote" style="margin: 0 8px;">Incluir</button>
                <button class="btn btn-danger" type="button" id="btnCancelarLote" style="margin: 0 8px;">Cancelar</button>
            </div>
        </div>
    </form>
</body>
</html>
<script>
    const url = 'lic_dispensasinexigibilidades.RPC.php';
    const validator = initializeValidation('#frmCadastroLote');
    const btnCancelarLote = document.getElementById('btnCancelarLote');
    const btnInserirLote = document.getElementById('btnInserirLote');

    if(btnCancelarLote != null){
        btnCancelarLote.addEventListener('click', function(e){
            e.preventDefault();
            parent.closeLote(false);
            return false;
        })
    }

    if(btnInserirLote != null){
        btnInserirLote.addEventListener('click', function(e){
            e.preventDefault();
            const formData = serializarFormulario(document.getElementById('frmCadastroLote'));
            const isValid = validator.validate();
            if(!isValid){
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
                    parameters: 'json=' + formData,
                    onComplete: (oAjax) => {
                        let oResponse = JSON.parse(oAjax.responseText);
                        if(oResponse.status == 200){
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso!',
                                text: 'Lote salvo com sucesso!',
                            });
                            document.getElementById('frmCadastroLote').reset();
                            parent.closeLote(true, true, false);
                            return false;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: oResponse.message,
                        });
                    }
                }
            );

        })
    }

</script>
