<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBselller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/db_liborcamento.php");
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
db_postmemory($HTTP_POST_VARS);
$anousu = db_getsession("DB_anousu");
?>

<html lang="pt">
<head>
    <title>Contass Consultoria Ltda - Pagina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<form name="form1" method="post" action="">
    <center>
        <fieldset style="width: 400px; margin-top: 50px;">
            <legend><h3><b>Renumerar Dotações</b></h3></legend>
            <table border=0 align=center>
                <tr>
                    <?php
                    $o50_anousu = db_getsession('DB_anousu');
                    ?>
                    <td nowrap><b>Exercício: <?php db_input('o50_anousu', 5, $o50_anousu, true) ?></b></td>
                </tr>
                <?php
                db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
                ?>
                <tr>
                    <td colspan="3" align="center">
                        <input type="button" style="margin-top: 15px;" name="renumerar" id="renumerar" value="Renumerar" onclick="js_renumeraDotacoes(<?= db_getsession("DB_anousu"); ?>)">
                    </td>
                </tr>
            </table>
        </fieldset>
    </center>
</form>
</body>
</html>

<script>
    function js_renumeraDotacoes(anoSessao) {

        let sMsgConfirma = "Esse procedimento irá alterar a numeração de todas as dotações do orçamento em todas as instituições.";
        sMsgConfirma += "\nDeseja prosseguir?";
        if (!confirm(sMsgConfirma)) {
            return false;
        }
        js_divCarregando("Renumerando dotações. Aguarde...", "msgBox");
        $('renumerar').disabled = true;
        let anoOrigem = anoSessao

        let oParametros = {};
        oParametros.anoOrigem = anoOrigem;
        let oAjax = new Ajax.Request('orc2_renumeraDotacoes_002.php', {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametros),
            onComplete: js_retornoRenumeraDotacoes

        });
    }

    function js_retornoRenumeraDotacoes(oAjax) {

        js_removeObj('msgBox');
        $('renumerar').disabled = false;
        const oRetorno = eval("(" + oAjax.responseText + ")");
        if (oRetorno.status == 2) {
            alert(oRetorno.message.urlDecode());
        } else {

            alert('Dotações renumeradas!');
        }

    }
</script>