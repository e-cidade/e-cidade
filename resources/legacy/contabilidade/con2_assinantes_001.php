<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
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

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <br />
    <br />
    <form action="" method="post" name="form1">
        <table align="center">
            <td>
                <fieldset>
                    <legend>
                        <b>&nbsp;Relatório de Assinantes&nbsp;</b>
                    </legend>
                    <table width="100%">
                        <tr>
                            <td><strong>Quebra:</strong></td>
                            <td>
                                <?
                                $aQuebra = array('0' => 'Sem Quebra', '1' => 'Unidade', '2' => 'Usuário', '3' => 'Cargo', '4' => 'Documento');
                                db_select('iQuebra', $aQuebra, true, 1, "style='width:125px'");
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="selectDescription" colspan="1">
                                <b>Formato:</b>
                            </td>
                            <td class="selectCell">
                                <?php
                                $aFormatos = array(1 => "PDF", 2 => "CSV");
                                db_select("iFormato", $aFormatos, true, 1, "style='width:125px'");
                                ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>

            <tr>
                <td align="center"> <br>
                    <input type="button" name='btnEmitir' value='Emitir Relatório' onclick="js_abre()" />
                </td>
            </tr>
        </table>
    </form>
</body>

</html>
<script>
    
function js_abre(formato){

    var formato = document.getElementById('iFormato').value;
    var quebra  = document.getElementById('iQuebra').value;

    filters  = "sQuebra="+quebra;

    if(formato == '1') {
        return window.open( 'con2_assinantes_002.php?'+filters,
                            '',
                            'width='+(screen.availWidth-5)+',height='+ (screen.availHeight-40)+',scrollbars=1,location=0 ');
    }

    if(formato == '2') {
        return window.open( 'con2_assinantes_003.php?'+filters,
                            '',
                            'width='+(screen.availWidth-5)+',height='+ (screen.availHeight-40)+',scrollbars=1,location=0 ');
    }
}
</script>