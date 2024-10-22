<?php

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
include("libs/db_liborcamento.php");
require_once("libs/JSON.php");

$aMeses = array(
    "01" => "Janeiro", "02" => "Fevereiro", "03" => "Março", "04" => "Abril", "05" => "Maio", "06" => "Junho", "07" => "Julho", "08" => "Agosto", "09" => "Setembro", "10" => "Outubro", "11" => "Novembro", "12" => "Dezembro"
);

?>

<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/micoxUpload.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
        div .formatdiv{
            margin-top: 5px;
            margin-bottom: 10px;
            padding-left: 5px;
        }
        .container {
            width: auto;
        }
        .formatselect {
            width: 200px;
            height: 18px;
        }
        .fieldS1 {
            position:relative;
            float: left;
        }
        .fieldS2 {
            position: relative;
            float: left;
            height: 115px;
        }
        #file {
            width: 200px !important;
        }
    </style>
</head>
<body bgcolor="#cccccc" style="margin-top: 25px;">
<form id='form1' name="form1" method="post" action="" enctype="multipart/form-data">
    <div class="center container">
        <fieldset class="fieldS1"> <legend>Balancete Verificação Conta Corrente</legend>
            <div class="formatdiv" align="center">
                <table style="margin-top: 10px; width: 100%;">
                    <tr>
                        <td>
                            <label class="bold">Data Inicial: </label>
                        </td>
                        <td>
                            <?
                                db_inputdata('dataInicial','01','01',db_getsession('DB_anousu'),true,'text',1);
                            ?>
                            </td>
                        <td>
                            <label class="bold" style="margin-left: -182px">&nbsp;&nbsp;Data Final: </label>
                            <?
                                $dataAtual = db_getsession('DB_datausu');
                                db_inputdata('dataFinal',date('d', $dataAtual),date('m', $dataAtual),date('Y', $dataAtual),true,'text',1);
                            ?>
                        </td>
                    </tr>
                    <tr>
                      <td>
                        <label class="bold">Indicador de Superávit: </label>
                      </td>
                      <td colspan="3">
                          <?php
                             $aIndicadores = array(
                                ''  => 'Todos',
                                'N' => 'N - Não se aplica',
                                'F' => 'F - Financeiro',
                                'P' => 'P - Patrimonial',
                            );
                            db_select('indicadorSuperavit', $aIndicadores, true, 1, 'style="width: 518px;"');
                          ?>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <label class="bold">Natureza de Saldo:</label>
                      </td>
                      <td colspan="3">
                          <?php
                            $aOpcoes = array(0  => 'Todos', 1 => 'Invertidas');
                            db_select('naturezaSaldo', $aOpcoes, true, 1, 'style="width: 518px;"');
                          ?>
                      </td>
                    </tr>
                    <tr>
                        <td>
                        <b>Tipo:</b>
                        <td>
                            <?$aTipos = array(2 => "Analítico - Fonte", 1 => "Sintético");
                            db_select('tipoRelatorio',$aTipos,false,1, 'style="width: 518px;"');
                            ?>
                        </td>
                        </td>
                    </tr>
                    <tr>
                        <?
                        db_selinstit('',300,100);
                        ?>
                    </tr>
                    <tr>
                        <td>
                            <label class="bold" id="lbl_estruturais" for="estrut_inicial">Estrutural:</label>
                        </td>
                        <td colspan="3">
                            <?php
                            $Testrut_inicial = 'Informe os estruturais separados por vírgula';
                            db_input('estrut_inicial', '', '', false, '', '', 'style="width: 518px;"')
                            ?>
                        </td>
                    </tr>                    
                    <tr>
                      <td>
                        <label class="bold" id="lbl_recurso" for="recurso">Recurso:</label>
                      </td>
                      <td colspan="3">
                        <?php
                            $sData         = date('Y-m-d', db_getsession('DB_datausu'));
                            $sWhere        = " o15_datalimite is null or o15_datalimite > '{$sData}' ";
                            $oClorctiporec = new cl_orctiporec;
                            $oResource     = $oClorctiporec->sql_record($oClorctiporec->sql_query(null, "distinct on (o15_codtri) o15_codtri, o15_descr", "o15_codtri", $sWhere));
                            db_selectrecord("recurso", $oResource, true, 2, "", "", "", "0");
                        ?>
                      </td>
                    </tr>
                </table>
            </div>

            <div id="MSC" class="recebe">&nbsp;</div>
        </fieldset>
        <div class="formatdiv" align="center">
            <input type="button" value="Imprimir" onclick="js_emite()">
        </div>
    </div>
</form>
<script>

    function js_emite(){
        var dataInicial     = document.form1.dataInicial.value;
        var dataFinal     = document.form1.dataFinal.value;
        var selinstit= document.form1.db_selinstit.value;
        var estrut_inicial = document.form1.estrut_inicial.value;
        var tipoRel = document.form1.tipoRelatorio.value;
        var recurso = document.form1.recurso.value;
        var naturezaSaldo = document.form1.naturezaSaldo.value;
        var indicSuperavit = document.form1.indicadorSuperavit.value;
        var arquivo = "";

        if (!dataInicial || !dataFinal) {
            alert("Todos os campos são obrigatórios");
            return false;
        }

        arquivo = 'con2_balanceteverificacaocontacorrente002.php';

        var query = "";
        query += ("dataInicial="+dataInicial+"&dataFinal="+dataFinal+"&selinstit="+selinstit+"&estrut_inicial="+estrut_inicial)+"&tipoRel="+tipoRel+"&naturezaSaldo="+naturezaSaldo+"&recurso="+recurso+"&indicSuperavit="+indicSuperavit;

        jan = window.open(
            arquivo+"?" + query,
            "",
            'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0'
        );
        jan.moveTo(0,0);
    }
</script>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
</body>
</html>
