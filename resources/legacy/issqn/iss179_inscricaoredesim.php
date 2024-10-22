<?php

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");

?>
<!DOCTYPE html>
<html lang="">
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
</head>
<body class="body-default">
<div class="container">
    <form name="form1" method="post" action="">
        <fieldset>
            <legend>Inscrições geradas pela REDESIM.</legend>
            <table>
                <tr>
                    <td>
                        <strong>Período:</strong>
                    </td>
                    <td>
                        <?php
                        db_inputdata('data1','','','',true,'text',1,"");
                        echo " a ";
                        db_inputdata('data2','','','',true,'text',1,"");
                        ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <input  name="btnGerar" id="btnGerar" type="button" value="Processar" >
    </form>
</div>
<?php
db_menu();
?>
</body>
<script>
    const route = 'api/v1/redesim/companiesReport';
    const btnGerar = document.getElementById('btnGerar');
    const dataInicio = document.getElementById('data1');
    const dataFim = document.getElementById('data2');

    const validarData = () => {
        try {
            if (empty(dataInicio.value) || empty(dataFim.value)) {
                throw new Error('Necessário informar o período para realizar a consulta!');
            }

            if (js_comparadata(dataInicio.value, dataFim.value, '>')) {
                throw new Error ('Data de inicio deve ser menor que a data final.');
            }
        } catch (e) {
            alert(e);
            return false;
        }

        return true;
    }

    btnGerar.addEventListener('click', () => {
        const formData = new FormData();

        if (!validarData()) {
            return;
        }

        formData.append('dataInicio', dataInicio.value);
        formData.append('dataFim', dataFim.value);

        HttpClient.post(route, {body: formData}).then(response => {
            if (response.error) {
                return alert(response.message);
            }

            window.open(response.data.arquivo, "_blank");
        });

    });
</script>
</html>
