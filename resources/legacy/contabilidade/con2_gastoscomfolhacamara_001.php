<?
/*
 *  E-cidade Software Publico para Gestao Municipal
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


?>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

    <fieldset style="margin: 40px auto 10px; width: 400px;">

        <table align="center" border=0>

            <legend><b>Gastos com folha Câmara</b></legend>

            <form name="form1" method="post" action="">
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan=2 nowrap>
                        <b>Mês :&nbsp;&nbsp;</b>
                        <select name="mes" class="field-size2" id="mes">
                            <option value="">Selecione</option>
                            <option value="1">Janeiro</option>
                            <option value="2">Fevereiro</option>
                            <option value="3">Março</option>
                            <option value="4">Abril</option>
                            <option value="5">Maio</option>
                            <option value="6">Junho</option>
                            <option value="7">Julho</option>
                            <option value="8">Agosto</option>
                            <option value="9">Setembro</option>
                            <option value="10">Outubro</option>
                            <option value="11">Novembro</option>
                            <option value="12">Dezembro</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </form>
        </table>

    </fieldset>

    <center>
        <td>
            &nbsp; &nbsp; &nbsp;<input name="imprimir" type="button" id="imprimir" value="Imprimir" onclick="js_abre()"> 
        </td>
    </center>
    
</body>

<script>
    
function js_abre(){

    var periodo  = document.getElementById('mes').value;

    if(periodo == '') {
        alert('Selecione um Período');
        return false;
    }

    filters  = "sMes="+periodo;

    return window.open( 'con2_gastoscomfolhacamara_002.php?'+filters,
                            '',
                            'width='+(screen.availWidth-5)+',height='+ (screen.availHeight-40)+',scrollbars=1,location=0 ');
}
</script>