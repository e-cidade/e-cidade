<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
?>

<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC style="margin-top: 25px;">

<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
</table>
<center>
    <div style="display: table">
        <fieldset>
            <legend>Migra��es</legend>
            <form id="frmImportarContas" method="post">
                <table>
                    <tr>
                        <td>
                            <input type="button" name="Importar Contas SICOM" value="Importar CTB SICOM" onclick="js_processar();"/>
                        </td> 
                    </tr>
                    <tr>
                        <td>
                            <input type="button" name="Importar Contas Fornecedores" value="Importar Contas Fornecedores" onclick="js_processarForne();"/>
                        </td>
                    </tr>
                </table>
            </form>
        </fieldset>
    </div>
</center>
</td>
</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<script>
    function js_processar() {        
        js_divCarregando('Aguarde, importando contas do arquivo CTB', 'msgBox');
        var oAjax = new Ajax.Request("func_importarcontassicom.php",
            {
                method: 'post',
                onComplete: js_retornoProcessamento
            }
        );
    }
    function js_processarForne() {        
        js_divCarregando('Aguarde, importando contas do fornecedores', 'msgBox');
        var oAjax = new Ajax.Request("func_importarcontafornec.php",
            {
                method: 'post',
                onComplete: js_retornoProcessamento
            }
        );
    }
    function js_retornoProcessamento(oAjax) {
        js_removeObj('msgBox');
        var oRetorno = eval("("+oAjax.responseText+")");
        
        if(oRetorno.status == 2){
            alert(oRetorno.message);
        }else{           
            alert("Processo conclu�do com sucesso!");
        }

    }
</script>