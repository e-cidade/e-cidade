<?

/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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


include("classes/db_db_depart_classe.php");
include("dbforms/db_classesgenericas.php");
$cldb_depart = new cl_db_depart;

$clliclicita->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc50_descr");
$clrotulo->label("l34_protprocesso");
$clrotulo->label("nome");
$clrotulo->label("l03_usaregistropreco");
$clrotulo->label("p58_numero");
$clrotulo->label("l20_naturezaobjeto");
$clrotulo->label("l20_nroedital");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");


//verifica��o do tipo de usu�rio por login => .conttas
$lusuario = db_getsession("DB_login");
$uLogin = explode(".", $lusuario, 2);

//url identificada para valida��o de campo modalidade da para uso de inclus�o
$url_atual = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$url_particao = explode("_", $url_atual, 2);

echo "<script>
parent.iframe_liclicpublicacoes.location.href='lic1_liclicpublicacao001.php?licitacao=$l20_codigo&db_opcao=$db_opcao';\n;
</script>";

$oParamLicicita = db_stdClass::getParametro('licitaparam', array(db_getsession("DB_instit")));
$l12_pncp = $oParamLicicita[0]->l12_pncp;

if ($db_opcao == 1) {

    /*
     * verifica na tabela licitaparam se deve utilizar processo do sistema
     */
    $oParamLicicita = db_stdClass::getParametro('licitaparam', array(db_getsession("DB_instit")));

    if (isset($oParamLicicita[0]->l12_escolheprotocolo) && $oParamLicicita[0]->l12_escolheprotocolo == 't') {
        $lprocsis = 's';
    } else {
        $lprocsis = 'n';
    }

    /*
     *verifica se existe apenas 1 cl_liclocal
     */
    $oLicLocal = new cl_liclocal();
    $rsLicLocal = $oLicLocal->sql_record($oLicLocal->sql_query_file());
    if ($oLicLocal->numrows == 1) {
        db_fieldsmemory($rsLicLocal, 0);
        $l20_liclocal = $l26_codigo;
    }

    /*
     * verifica se existe apenas 1 cl_liccomissao
     */
    $oLicComissao = new cl_liccomissao();
    $rsLicComissao = db_query($oLicComissao->sql_query_file());
    if (pg_num_rows($rsLicComissao) == 1) {
        db_fieldsmemory($rsLicComissao, 0);
        $l20_liccomissao = $l30_codigo;
    }
}

if ($l20_codepartamento != null) {
    $result_depto = $cldb_depart->sql_record($cldb_depart->sql_query_file($l20_codepartamento, 'descrdepto'));
    if ($cldb_depart->numrows != 0) {
        db_fieldsmemory($result_depto, 0);
        $l20_descricaodep = $descrdepto;
    }
}
if ($l20_codigo != null) {
    $comissao = $clliccomissaocgm->sql_record($clliccomissaocgm->sql_query_file(null, 'l31_codigo,l31_liccomissao,l31_numcgm, (select cgm.z01_nome from cgm where z01_numcgm = l31_numcgm) as z01_nome, l31_tipo', null, "l31_licitacao=$l20_codigo"));
    for ($i = 0; $i < $clliccomissaocgm->numrows; $i++) {
        $comisaoRes = db_utils::fieldsMemory($comissao, $i);
        if ($comisaoRes->l31_tipo == 1) {
            $respAbertcodigo = $comisaoRes->l31_numcgm;
            $respAbertunome = $comisaoRes->z01_nome;
            $respAutocodigo = $comisaoRes->l31_numcgm;
            $respAutonome = $comisaoRes->z01_nome;
        } else if ($comisaoRes->l31_tipo == 2) {
            $respEditalcodigo = $comisaoRes->l31_numcgm;
            $respEditalunome = $comisaoRes->z01_nome;
        } else if ($comisaoRes->l31_tipo == 3) {
            //$respConducodigo = $comisaoRes->l31_numcgm;
            //$respCondunome = $comisaoRes->z01_nome;
        } else if ($comisaoRes->l31_tipo == 4) {
            //$respConducodigo = $comisaoRes->l31_numcgm;
            //$respCondunome = $comisaoRes->z01_nome;
        } else if ($comisaoRes->l31_tipo == 5) {
            $respConducodigo = $comisaoRes->l31_numcgm;
            $respCondunome = $comisaoRes->z01_nome;
        } else if ($comisaoRes->l31_tipo == 6) {
            //$respConducodigo = $comisaoRes->l31_numcgm;
            //$respCondunome = $comisaoRes->z01_nome;
        } else if ($comisaoRes->l31_tipo == 7) {
            //$respConducodigo = $comisaoRes->l31_numcgm;
            //$respCondunome = $comisaoRes->z01_nome;
        } else if ($comisaoRes->l31_tipo == 8) {
            //$respPubliccodigo = $comisaoRes->l31_numcgm;
            //$respPublicnome = $comisaoRes->z01_nome;
        } else if ($comisaoRes->l31_tipo == 9) {
            $respAvaliBenscodigo = $comisaoRes->l31_numcgm;
            $respAvaliBensnome = $comisaoRes->z01_nome;
        } else if ($comisaoRes->l31_tipo == 10) {
            $respObrascodigo = $comisaoRes->l31_numcgm;
            $respObrasunome = $comisaoRes->z01_nome;
        }
    }
}
$lBloqueadoRegistroPreco = (empty($itens_lancados) ? $db_opcao : 3);
?>

<style type="text/css">
    .fieldsetinterno {
        border: 0px;
        border-top: 2px groove white;
        margin-top: 10px;

    }

    fieldset table tr>td {
        width: 180px;
        white-space: nowrap
    }

    select:disabled {
        background-color: #DEB887;
        text-transform: uppercase;
        color: black;
        -webkit-appearance: none;
        -moz-appearance: none;
        text-indent: 1px;
        text-overflow: '';
    }

    #l20_categoriaprocesso,
    #l20_tipliticacao,
    #l20_tipnaturezaproced,
    #l20_regimexecucao {
        width: 307px;
    }

    #l20_mododisputa,
    #l20_receita {
        width: 169px;
    }
</style>
<form name="form1" method="post" action="" onsubmit="js_ativaregistro()">
    <input type="hidden" id="modalidade_tribunal" name="modalidade_tribunal">
    <center>

        <table align=center style="margin-top:25px;">
            <tr>
                <td>

                    <fieldset>
                        <legend><strong>Licita��o</strong></legend>

                        <fieldset style="border:0px;">

                            <table border="0">
                                <tr>
                                    <td nowrap title="<?= @$Tl20_codigo ?>">
                                        <?= @$Ll20_codigo ?>
                                    </td>
                                    <td>
                                        <?
                                        db_input('l20_codigo', 10, $Il20_codigo, true, 'text', 3, "");
                                        if ($db_opcao == 1 || $db_opcao == 11) {
                                            $l20_correto = 'f';
                                        }
                                        db_input("l20_correto", 1, "", true, "hidden", 3);
                                        if ($db_botao == false && @$l20_correto == 't') {
                                        ?>
                                            &nbsp;&nbsp;<font color="#FF0000"><b>Licita��o j� julgada</b></font>
                                        <?
                                        }
                                        ?>



                                    </td>
                                </tr>

                                <tr>
                                    <td nowrap title="<?= @$Tl20_codtipocom ?>">
                                        <b>
                                            <?
                                            db_ancora("Modalidade :", "js_pesquisal20_codtipocom(true);", 3);
                                            ?>
                                        </b>
                                    </td>
                                    <td>
                                        <?
                                        $result_tipo = $clcflicita->sql_record($clcflicita->sql_query_numeracao(null, "l03_codigo,l03_descr", null, "l03_instit = " . db_getsession("DB_instit")));
                                        if ($clcflicita->numrows == 0) {
                                            db_msgbox("Nenhuma Modalidade cadastrada!!");
                                            $result_tipo = "";
                                            $db_opcao = 3;
                                            $db_botao = false;
                                            db_input("l20_codtipocom", 10, "", true, "text");
                                            db_input("l20_codtipocom", 40, "", true, "text");
                                        } else {
                                            db_selectrecord("l20_codtipocom", @$result_tipo, true, $db_opcao, "js_mostraRegistroPreco()");
                                            if (isset($l20_codtipocom) && $l20_codtipocom != "") {
                                                echo "<script>document.form1.l20_codtipocom.selected=$l20_codtipocom;</script>";
                                            }
                                        }
                                        ?>
                                        <input type="hidden" id="descricao" name="descricao" value="" onchange="js_convite()">
                                        <input type="hidden" id="vUsuario" name="vUsuario" value="<? echo $uLogin[1]; ?>">
                                        <input type="hidden" id="vInclu" name="vInclu" value="<? echo $url_particao[1]; ?>">

                                    </td>
                                </tr>

                                <tr>
                                    <td nowrap title="<?= @$Tl20_edital ?>">
                                        <?= @$Ll20_edital ?>
                                    </td>
                                    <td>
                                        <?

                                        $bloqueianumeracoes = 3;

                                        if ($l12_numeracaomanual == 't') {
                                            if ($db_opcao == 1 || $db_opcao == 2) $bloqueianumeracoes = 1;
                                        }


                                        db_input('l20_edital', 10, $Il20_edital, true, 'text', $bloqueianumeracoes, "");
                                        ?>

                                        <strong id="txtNumeracao">Numera��o:</strong>
                                        <?
                                        db_input('l20_numero', 10, $Il20_numero, true, 'text',  $bloqueianumeracoes, "");
                                        if ($db_opcao == 1 && !isset($incluir)) {
                                            echo "<script> document.getElementById('l20_numero').style.display = 'none'; </script>";
                                            echo "<script> document.getElementById('txtNumeracao').style.display = 'none'; </script>";
                                        }

                                        ?>
                                        <span id="linha_nroedital" style="display:none;">
                                            <strong>Edital:</strong>
                                                <?php
                                                    $mostra = $l20_nroedital && $db_opcao == 2 || !$l20_nroedital && $db_opcao == 1
                                                    || db_getsession('DB_anousu') >= 2021 ? 3 : 1;
                                                    db_input('l20_nroedital', 10, 1, true, 'text', $bloqueianumeracoes, "");
                                                ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap title="<?= @$Tl20_codepartamento ?>">
                                        <?
                                        db_ancora("Cod.Departamento", "js_pesquisal20_codepartamento(true);", $db_opcao)

                                        ?>
                                    </td>
                                    <td>
                                        <?
                                        db_input('l20_codepartamento', 4, $Il20_codepartamento, true, 'text', $db_opcao, "onchange='js_pesquisal20_codepartamento(false)';");
                                        db_input('l20_descricaodep', 40, $Il20_descricaodep, true, 'text', 3, "");
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td nowrap title="<?= @$Tl20_leidalicitacao ?>" id="leidalicitacao">
                                        <strong>Lei da Licita��o:</strong>
                                    </td>
                                    <td>
                                        <?
                                        $arr_tipo = array(
                                            "0" => "Selecione",
                                            "1" => "1 - Lei 14.133/2021",
                                            "2" => "2 - Lei 8.666/1993 e outras"
                                        );
                                        db_select("l20_leidalicitacao", $arr_tipo, true, $db_opcao, "onchange='js_verificalei(this.value);'");
                                        ?>
                                    </td>
                                </tr>
                                <tr style="display:none;" id="amparolegal">
                                    <td nowrap title="Amparo Legal">
                                        <?
                                        echo "<b>Amparo Legal:</b>";
                                        ?>
                                    </td>
                                    <td>
                                        <?
                                        $tipo = array();
                                        $tipo[0] = "Selecione";



                                        if (isset($l20_codtipocom) && $l20_codtipocom != "") {
                                            $result_tipo = db_query("select * from amparolegal where l212_codigo in (select l213_amparo from amparocflicita where l213_modalidade = $l20_codtipocom)");
                                        } else {
                                            $result_tipo = db_query("select * from amparolegal");
                                        }


                                        for ($iIndiceTipo = 0; $iIndiceTipo < pg_numrows($result_tipo); $iIndiceTipo++) {

                                            $oTipo = db_utils::fieldsMemory($result_tipo, $iIndiceTipo);

                                            $tipo[$oTipo->l212_codigo] = $oTipo->l212_lei;
                                        }

                                        //db_selectrecord("l20_codtipocom", @$result_tipo, true, $db_opcao, "js_mostraRegistroPreco()");
                                        db_select("l212_codigo", $tipo, true, $db_opcao, "");

                                        if (isset($l20_amparolegal) && $l20_amparolegal != "") {
                                            echo "<script>document.getElementById('l212_codigo').value=$l20_amparolegal;</script>";
                                        }

                                        ?>

                                    </td>
                                </tr>
                                <tr style="display:none" id="respAvaliaBens">
                                    <td nowrap title="respAvaliBenscodigo">
                                        <?
                                        db_ancora("Resp.Avalia��o de Bens:", "js_pesquisal31_numcgm(true,'respAvaliBenscodigo','respAvaliBensnome');", $db_opcao)

                                        ?>
                                    </td>
                                    <td>
                                        <?
                                        db_input('respAvaliBenscodigo', 10, $respAvaliBenscodigo, true, 'text', $db_opcao, "onchange=js_pesquisal31_numcgm(false,'respAvaliBenscodigo','respAvaliBensnome');");
                                        db_input('respAvaliBensnome', 45, $respAvaliBensnome, true, 'text', 3, "");
                                        ?>
                                    </td>
                                </tr>
                                <tr id="respCondProcesso" style="display:none">
                                    <td nowrap title="respConducodigo">
                                        <?
                                        db_ancora("Resp.Condu��o do Processo:", "js_pesquisal31_numcgm(true,'respConducodigo','respCondunome');", $db_opcao)

                                        ?>
                                    </td>
                                    <td>
                                        <?
                                        db_input('respConducodigo', 4, $respConducodigo, true, 'text', $db_opcao, "onchange=js_pesquisal31_numcgm(false,'respConducodigo','respCondunome');");
                                        db_input('respCondunome', 45, $respCondunome, true, 'text', 3, "");
                                        ?>
                                    </td>
                                </tr>

                                <tr style="display: none">
                                    <td nowrap title="<?= @$Tl20_id_usucria ?>">
                                        <?
                                        db_ancora(@$Ll20_id_usucria, "js_pesquisal20_id_usucria(true);", 3);
                                        ?>
                                    </td>
                                    <td>
                                        <?
                                        $usuario = db_getsession("DB_id_usuario");
                                        $result_usuario = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file($usuario));
                                        if ($cldb_usuarios->numrows > 0) {
                                            db_fieldsmemory($result_usuario, 0);
                                        }
                                        $l20_id_usucria = $id_usuario;
                                        db_input('l20_id_usucria', 10, $Il20_id_usucria, true, 'text', 3, " onchange='js_pesquisal20_id_usucria(false);'")
                                        ?>
                                        <?
                                        db_input('nome', 45, $Inome, true, 'text', 3, '')
                                        ?>
                                    </td>
                                </tr>

                                <tr>

                                    <td nowrap title="<?= @$Tl20_tipliticacao ?>" id="tipolicitacao">
                                        <strong>Crit�rio de Julgamento: </strong>
                                    </td>

                                    <td id="licitacao1">
                                        <?
                                        $arr_tipo = array(
                                            "0" => "Selecione",
                                            "1" => "1- Menor pre�o",
                                            "2" => "2- Maior desconto",
                                            "4" => "4- T�cnica e pre�o",
                                            "5" => "5- Maior lance",
                                            "6" => "6- Maior retorno econ�mico",
                                            "7" => "7- N�o se aplica",
                                            "8" => "8- Melhor t�cnica",
                                            "9" => "9- Conte�do art�stico"
                                        );
                                        db_select("l20_tipliticacao", $arr_tipo, true, $db_opcao);
                                        ?>
                                    </td>
                                </tr>
                                <tr style="display: none;">
                                    <td nowrap title="<?= @$Tl20_lances ?>">
                                        <strong><?= "Apura��o de lances pelo sistema: " ?></strong>
                                    </td>
                                    <td>
                                        <?php 
                                        $alances = array("f" => "N�o","t"=> "Sim");
                                        db_select("l20_lances",$alances,true,"");
                                        ?>
                                    </td>
                                </tr> 
                                <tr id="tr_l20_tipnaturezaproced">
                                    <td nowrap title="<?= @$Tl20_tipnaturezaproced ?>" id="tipnaturezaproced">
                                        <?= @$Ll20_tipnaturezaproced ?>
                                    </td>
                                    <td>
                                        <?
                                        $al20_tipnaturezaproced = array("0" => "Selecione", "1" => "1-Licita��o Normal", "2" => "2-Registro de Pre�o", "3" => "3-Credenciamento/Chamada");
                                        db_select("l20_tipnaturezaproced", $al20_tipnaturezaproced, true, $db_opcao, "onchange='js_naturezaprocedimento(this.value);'");
                                        ?>
                                    </td>
                                </tr>
                                <span>
                                    <tr>
                                        <td nowrap title="<?= @$Tl20_naturezaobjeto ?>" id="natureOB1">
                                            <?= @$Ll20_naturezaobjeto ?>
                                        </td>
                                        <td id="natureOB">
                                            <?
                                            $al20_naturezaobjeto = array("0" => "Selecione", "1" => "1- Obras e Servi�os de Engenharia", "2" => "2- Compras e outros servi�os", "3" => "3- Loca��o de Im�veis", "4" => "4- Concess�o", "5" => "5- Permiss�o", "6" => "6- Aliena��o de bens");
                                            if (db_getsession('DB_anousu') >= 2019) {
                                                $al20_naturezaobjeto[7] = "7- Compras Para Obras e/ou Servi�os de Engenharia";
                                            }
                                            db_select("l20_naturezaobjeto", $al20_naturezaobjeto, true, $db_opcao, "onchange='js_regime(this.value)'");
                                            ?>
                                        </td>
                                    </tr>
                                    <tr id="categoriaprocesso" style="display:none">
                                        <td>
                                            <strong>Categoria do Processo:</strong>
                                        </td>
                                        <td>
                                            <?
                                            $al20_categoriaprocesso = array(
                                                "0" => "Selecione",
                                                "1" => "1- Cess�o",
                                                "2" => "2- Compras",
                                                "3" => "3- Inform�tica (TIC)",
                                                "4" => "4- Internacional",
                                                "5" => "5- Loca��o Im�veis",
                                                "6" => "6- M�o de Obra",
                                                "7" => "7- Obras",
                                                "8" => "8- Servi�os",
                                                "9" => "9- Servi�os de Engenharia",
                                                "10" => "10- Servi�os de Sa�de"
                                            );
                                            db_select("l20_categoriaprocesso", $al20_categoriaprocesso, true, $db_opcao, '');
                                            ?>
                                        </td>
                                    </tr>
                                    <tr style="display:none;" id="respObras">
                                        <td nowrap title="respObrascodigo">
                                            <?
                                            db_ancora("Resp.Orc. Obras/Servi�o:", "js_pesquisal31_numcgm(true,'respObrascodigo','respObrasunome');", $db_opcao)

                                            ?>
                                        </td>
                                        <td>
                                            <?
                                            db_input('respObrascodigo', 10, $respObrascodigo, true, 'text', $db_opcao, "onchange=js_pesquisal31_numcgm(false,'respObrascodigo','respObrasunome');");
                                            db_input('respObrasunome', 45, $respObrasunome, true, 'text', 3, "");
                                            ?>
                                        </td>
                                    </tr>
                                </span>
                                <tr id="l20_regimexecucao">
                                    <td nowrap title="<?= @$Tl20_regimexecucao ?>">
                                        <?= @$Ll20_regimexecucao ?>
                                    </td>
                                    <td>
                                        <?
                                        $al20_regimexecucao = array(
                                            "0" => "N�o se Aplica",
                                            "1" => "1- Empreitada por Pre�o Global",
                                            "2" => "2- Empreitada por Pre�o Unit�rio",
                                            "3" => "3- Empreitada Integral",
                                            "4" => "4- Tarefa",
                                            "5" => "5- Execu��o Direta",
                                            "6" => "6- Contrata��o integrada",
                                            "7" => "7- Contrata��o semi-integrada",
                                            "8" => "8- Fornecimento e presta��o de servi�o associado"
                                        );
                                        db_select("l20_regimexecucao", $al20_regimexecucao, true, $db_opcao);
                                        ?>
                                    </td>
                                    </tr>
                                    <tr id="l20_criterioadjudicacao">
                                    <td id="descontotab">
                                        <label class="bold">Crit�rio de Adjudica��o:</label>
                                    </td>
                                    <td>
                                        <? //OC3770
                                        if (!isset($l20_criterioadjudicacao) || $l20_criterioadjudicacao == '') {
                                            $l20_criterioadjudicacao = "";
                                        }
                                        $aCriterios = array("3" => "Outros", "1" => "Desconto sobre tabela", "2" => "Menor taxa ou percentual");
                                        db_select("l20_criterioadjudicacao", $aCriterios, true, '');
                                        ?>
                                    </td>
                                </tr>
                                <tr id="receita" style="display:none">
                                    <td nowrap title="receita">
                                        <b>Receita: </b>
                                    </td>
                                    <td>
                                        <?php
                                        $aReceita = array(
                                            "t" => "Despesa",
                                            "f" => "Receita"
                                        );
                                        db_select("l20_receita", $aReceita, true, '');
                                        ?>
                                    </td>
                                </tr>

                                <tr id="disputa">
                                    <td nowrap title="Modo de disputa">
                                        <b>Modo de disputa: </b>
                                    </td>
                                    <td>
                                        <?php
                                        $aDisputas = array(
                                            "0" => "Selecione",
                                            "1" => "Aberto",
                                            "2" => "Fechado",
                                            "3" => "Conjunto"
                                        );
                                        db_select("l20_mododisputa", $aDisputas, true, '');
                                        ?>
                                    </td>
                                </tr>


                                <tr style="display:none;" id="convite1">
                                    <td nowrap title="<?= @$Tl20_numeroconvidado ?>" id="numeroconvidado">
                                        <span><?= @$Ll20_numeroconvidado ?></span>
                                    </td>
                                    <td>
                                        <span>
                                            <?php
                                            db_input('l20_numeroconvidado', 3, $Il20_numeroconvidado, true, 'text', $db_opcao, "", "", "#E6E4F1")
                                            ?>
                                        </span>
                                    </td>

                                </tr>

                                <tr>
                                    <td nowrap title="<?= @$Tl20_execucaoentrega ?>">
                                        <?= @$Ll20_execucaoentrega ?>
                                    </td>
                                    <td>
                                        <?php $al20_diames = array("1" => "Dias", "2" => "Mes");
                                        db_select("l20_diames", $al20_diames, true, $db_opcao);
                                        db_input('l20_execucaoentrega', 3, $Il20_execucaoentrega, true, 'text', $db_opcao, "");
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>

                        <fieldset class="fieldsetinterno">
                            <legend><strong>Datas</strong></legend>
                            <table>
                                <tr>
                                    <td nowrap title="Data Abertura Proc. Adm.">
                                        <b>Data Abertura Proc. Adm. :</b>
                                    </td>
                                    <td>
                                        <?
                                        if (!isset($l20_datacria)) {
                                            $l20_datacria_dia = date('d', db_getsession("DB_datausu"));
                                            $l20_datacria_mes = date('m', db_getsession("DB_datausu"));
                                            $l20_datacria_ano = date('Y', db_getsession("DB_datausu"));
                                        }
                                        db_inputdata("l20_datacria", @$l20_datacria_dia, @$l20_datacria_mes, @$l20_datacria_ano, true, 'text', $db_opcao);
                                        ?>
                                        <?= @$Ll20_horacria ?>
                                        <?
                                        if ($db_opcao == 1 || $db_opcao == 11) {
                                            $l20_horacria = db_hora();
                                        }
                                        echo "&nbsp;";
                                        db_input('l20_horacria', 5, $Il20_horacria, true, 'text', 3, "");
                                        echo "hh:mm";
                                        ?>
                                    </td>
                                </tr>



                                <tr style="display:none;" id="respAutoProcesso">
                                    <td nowrap title="respAutocodigo">
                                        <?
                                        db_ancora("Resp.Autoriza��o de Abertura:", "js_pesquisal31_numcgm(true,'respAutocodigo','respAutonome');", $db_opcao)

                                        ?>
                                    </td>
                                    <td>
                                        <?
                                        db_input('respAutocodigo', 10, $respAutocodigo, true, 'text', $db_opcao, "onchange=js_pesquisal31_numcgm(false,'respAutocodigo','respAutonome');");
                                        db_input('respAutonome', 45, $respAutonome, true, 'text', 3, "");
                                        ?>
                                    </td>
                                </tr>
                                <tr id="respAberProcesso">
                                    <td nowrap title="respAbertcodigo">
                                        <?
                                        db_ancora("Resp.Abertura do Processo:", "js_pesquisal31_numcgm(true,'respAbertcodigo','respAbertunome');", $db_opcao)

                                        ?>
                                    </td>
                                    <td>
                                        <?
                                        db_input('respAbertcodigo', 10, $respAbertcodigo, true, 'text', $db_opcao, "onchange=js_pesquisal31_numcgm(false,'respAbertcodigo','respAbertunome');");
                                        db_input('respAbertunome', 45, $respAbertunome, true, 'text', 3, "");
                                        ?>
                                    </td>
                                </tr>

                                <tr id="dataaber">
                                    <td nowrap title="Data Emis/Alt Edital/Convite" id="dataaber">
                                        <b> Data Emis/Alt Edital/Convite : </b>
                                    </td>
                                    <td>
                                        <?
                                        db_inputdata("l20_dataaber", @$l20_dataaber_dia, @$l20_dataaber_mes, @$l20_dataaber_ano, true, 'text', $db_opcao);
                                        ?>
                                        <?= @$Ll20_horaaber ?>
                                        <?
                                        if (empty($l20_horaaber)) {
                                            $l20_horaaber = db_hora();
                                        }
                                        db_input('l20_horaaber', 5, $Il20_horaaber, true, 'text', $db_opcao, "onchange='js_verifica_hora(this.value,this.name)';onkeypress='return js_mask(event, \"0-9|:|0-9\"); '");
                                        echo "hh:mm";
                                        ?>
                                    </td>
                                </tr>



                                <!--
                                <tr id="datenpc">
                                    <td nowrap title="Data de Publica��o PNCP">
                                        <b>Data de Publica��o PNCP: </b>
                                    </td>
                                    <td>
                                        <?
                                        db_inputdata("l20_dtpulicacaopncp", @$l20_dtpulicacaopncp_dia, @$l20_dtpulicacaopncp_mes, @$l20_dtpulicacaopncp_ano, true, 'text', $db_opcao);
                                        ?>
                                    </td>
                                </tr>
                                -->
                                <!--
                                <tr id="linkpnpc">
                                    <td nowrap title="Link no PNCP">
                                        <b>Link no PNCP: </b>
                                    </td>
                                    <td>
                                        <?
                                        db_input('l20_linkpncp', 100, $Il20_linkpncp, true, 'text', $db_opcao, "");
                                        ?>
                                    </td>
                                </tr>
                                -->
                                <!--
                                <tr id="diario">
                                    <td nowrap title="Di�rio Oficial da Divulga��o">
                                        <b>Di�rio Oficial da Divulga��o: </b>
                                    </td>
                                    <td>
                                        <?
                                        $aDiarios = array(
                                            "0" => "Selecione",
                                            "1" => "Munic�pio",
                                            "2" => "Estado",
                                            "3" => "Uni�o"
                                        );
                                        db_select("l20_diariooficialdivulgacao", $aDiarios, true, '');
                                        ?>
                                    </td>
                                </tr>
                                -->

                                <tr id="respEmissaoEdi">
                                    <td nowrap title="respEditalcodigo">
                                        <?
                                        db_ancora("Resp.Emiss�o Edital:", "js_pesquisal31_numcgm(true,'respEditalcodigo','respEditalunome');", $db_opcao)

                                        ?>
                                    </td>
                                    <td>
                                        <?
                                        db_input('respEditalcodigo', 10, $respEditalcodigo, true, 'text', $db_opcao, "onchange=js_pesquisal31_numcgm(false,'respEditalcodigo','respEditalunome');");
                                        db_input('respEditalunome', 45, $respEditalunome, true, 'text', 3, "");
                                        ?>
                                    </td>
                                </tr>


                                <!--
                                <tr>
                                    <td nowrap title="Data Publica��o DO" id="dtpublic">
                                        <b>Data Publica��o DO : </b>
                                    </td>
                                    <td>
                                        <?
                                        db_inputdata('l20_dtpublic', @$l20_dtpublic_dia, @$l20_dtpublic_mes, @$l20_dtpublic_ano, true, 'text', $db_opcao, "", "", "#ffffff");
                                        ?>
                                    </td>
                                </tr>
                                -->
                                <!--
                                <tr id="dtpubedital">
                                    <td nowrap title="Data Publica��o Edital" id="dtpublic">
                                        <b>Data Publica��o Edital: </b>
                                    </td>
                                    <td>
                                        <?
                                        db_inputdata('l20_dtpulicacaoedital', @$l20_dtpulicacaoedital_dia, @$l20_dtpulicacaoedital_mes, @$l20_dtpulicacaoedital_ano, true, 'text', $db_opcao);
                                        ?>
                                    </td>
                                </tr>
                                -->
                                <!--
                                <tr id="linkedital">
                                    <td nowrap title="Link de Publica��o Edital">
                                        <b>Link de Publica��o Edital:</b>
                                    </td>
                                    <td>
                                        <?
                                        db_input('l20_linkedital', 100, $Il20_linkedital, true, 'text', $db_opcao, "");
                                        ?>
                                    </td>
                                </tr>
                                -->
                                <!--
                                <tr id="respPublic">
                                    <td nowrap title="respPubliccodigo">
                                        <?
                                        db_ancora("Resp. pela Publica��o:", "js_pesquisal31_numcgm(true,'respPubliccodigo','respPublicnome');", $db_opcao)

                                        ?>
                                    </td>
                                    <td>
                                        <?
                                        db_input('respPubliccodigo', 10, $respPubliccodigo, true, 'text', $db_opcao, "onchange=js_pesquisal31_numcgm(false,'respPubliccodigo','respPublicnome');");
                                        db_input('respPublicnome', 45, $respPublicnome, true, 'text', 3, "");
                                        ?>
                                    </td>
                                </tr>
                                -->

                                <tr id="dataaberturapncp">
                                    <td>
                                        <b>Data Abertura Proposta :</b>
                                    </td>
                                    <td>
                                        <?
                                        db_inputdata("l20_dataaberproposta", @$l20_dataaberproposta_dia, @$l20_dataaberproposta_mes, @$l20_dataaberproposta_ano, true, 'text', $db_opcao, "");
                                        ?>
                                        <strong>Hora Abertura Prop:</strong>
                                        <?php
                                        if (empty($l20_horaaberturaprop)) {
                                            $l20_horaaberturaprop = db_hora();
                                        }
                                        db_input('l20_horaaberturaprop', 5, $Il20_horaaberturaprop, true, 'text', $db_opcao, "onchange='js_verifica_hora(this.value,this.name)';onkeypress='return js_mask(event, \"0-9|:|0-9\"); '");
                                        echo "hh:mm";
                                        ?>
                                    </td>
                                </tr>

                                <tr id="dataencerramentopncp" style="display: none;">
                                    <td>
                                        <b> Data Encerramento Proposta: </b>
                                    </td>
                                    <td>
                                        <?
                                        db_inputdata("l20_dataencproposta", @$l20_dataencproposta_dia, @$l20_dataencproposta_mes, @$l20_dataencproposta_ano, true, 'text', $db_opcao, "");
                                        ?>
                                        <strong>Hora Encerramento:</strong>
                                        <?php
                                        if (empty($l20_horaencerramentoprop)) {
                                            $l20_horaencerramentoprop = db_hora();
                                        }
                                        db_input('l20_horaencerramentoprop', 5, $Il20_horaencerramentoprop, true, 'text', $db_opcao, "onchange='js_verifica_hora(this.value,this.name)';onkeypress='return js_mask(event, \"0-9|:|0-9\"); '");
                                        echo "hh:mm";
                                        ?>
                                    </td>
                                </tr>
                                <!--
                                <tr>
                                    <td nowrap title="<?= @$Tl20_datapublicacao1 ?>" id="datapublicacao1">
                                        <? //=@$Ll20_datapublicacao1
                                        ?>
                                        <strong>Publica��o Ve�culo 1:</strong>
                                    </td>
                                    <td>
                                        <?
                                        db_inputdata('l20_datapublicacao1', @$l20_datapublicacao1_dia, @$l20_datapublicacao1_mes, @$l20_datapublicacao1_ano, true, 'text', $db_opcao, "");
                                        ?>
                                        <strong id="nomeveiculo1">Ve�culo Divulga��o 1:</strong>
                                        <?
                                        db_input('l20_nomeveiculo1', 40, $Il20_nomeveiculo1, true, 'text', $db_opcao, "");
                                        ?>
                                    </td>

                                </tr>
                                -->
                                <!--
                                <tr>
                                    <td nowrap title="<?= @$Tl20_nomeveiculo1 ?>" id="nomeveiculo1">
                                        <?= @$Ll20_nomeveiculo1 ?>
                                    </td>
                                    <td>
                                        <?
                                        db_input('l20_nomeveiculo1', 50, $Il20_nomeveiculo1, true, 'text', $db_opcao, "");
                                        ?>
                                    </td>
                                </tr> -->
                                <!--
                                <tr>
                                    <td nowrap title="<?= @$Tl20_datapublicacao2 ?>" id="datapublicacao2">
                                        <? //=@$Ll20_datapublicacao2
                                        ?>
                                        <strong>Publica��o Ve�culo 2:</strong>
                                    </td>
                                    <td>
                                        <?
                                        db_inputdata('l20_datapublicacao2', @$l20_datapublicacao2_dia, @$l20_datapublicacao2_mes, @$l20_datapublicacao2_ano, true, 'text', $db_opcao, "");
                                        ?>
                                        <strong id="nomeveiculo2">Ve�culo Divulga��o 2:</strong>
                                        <?
                                        db_input('l20_nomeveiculo2', 40, $Il20_nomeveiculo2, true, 'text', $db_opcao, "");
                                        ?>
                                    </td>
                                </tr>
                                -->
                                <!--
                                <tr>
                                    <td nowrap title="<?= @$Tl20_nomeveiculo2 ?>" id="nomeveiculo2">
                                        <?= @$Ll20_nomeveiculo2 ?>
                                    </td>
                                    <td>
                                        <?
                                        db_input('l20_nomeveiculo2', 50, $Il20_nomeveiculo2, true, 'text', $db_opcao, "");
                                        ?>

                                    </td>
                                </tr> -->

                            </table>

                        </fieldset>

                        <fieldset>
                            <legend><strong>Detalhamento para ME e EPP </strong></legend>
                            <table>
                                <tr>
                                    <td nowrap title="<?= @$Tl20_critdesempate ?>">
                                        <?= @$Ll20_critdesempate ?>
                                    </td>
                                    <td>
                                        <?
                                        $al20_critdesempate = array("0" => "Selecione", "2" => "N�o", "1" => "Sim");
                                        db_select("l20_critdesempate", $al20_critdesempate, true, $db_opcao);
                                        ?>
                                    <td>
                                        <?= @$Ll20_subcontratacao ?>
                                    </td>
                                    <td>
                                        <? $al20_subcontratacao = array("0" => "Selecione", "2" => "N�o", "1" => "Sim");
                                        db_select("l20_subcontratacao", $al20_subcontratacao, true, $db_opcao);
                                        ?>
                                    </td>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Tl20_destexclusiva ?>">
                    <?= @$Ll20_destexclusiva ?>
                </td>
                <td>
                    <?
                    $al20_destexclusiva = array("0" => "Selecione", "2" => "N�o", "1" => "Sim");
                    db_select("l20_destexclusiva", $al20_destexclusiva, true, $db_opcao);
                    ?>
                <td>
                    <?= @$Ll20_limitecontratacao ?>
                </td>
                <td>
                    <? $al20_limitcontratacao = array("0" => "Selecione", "2" => "N�o", "1" => "Sim");
                    db_select("l20_limitcontratacao", $al20_limitcontratacao, true, $db_opcao);
                    ?>
                </td>
                </td>
            </tr>
        </table>
        </fieldset>

        <fieldset>
            <legend><strong>Informa��es Adicionais </strong></legend>
            <table>

                <tr id="trTipoJulgamento">
                    <td nowrap title="<?= @$Tl20_tipojulg ?>">
                        <?= @$Ll20_tipojulg ?>
                    </td>
                    <td>
                        <?
                        $arr_tipo = array("0" => "Selecione", "1" => "Por item", "3" => "Por lote");
                        if ($lBloqueadoRegistroPreco == 3) {
                            db_select("l20_tipojulg", $arr_tipo, true, 1, "disabled=");
                        } else {
                            db_select("l20_tipojulg", $arr_tipo, true, 1);
                        }
                        db_input("tipojulg", 1, "", true, "hidden", 3, "");
                        db_input("confirmado", 1, "", true, "hidden", 3, "");
                        ?>
                    </td>
                    <td>
                        <span style="display:none" id="usaregistropreco">
                            <strong>Usa Registro de Pre�o:</strong>
                            <?
                            if (!isset($l20_usaregistropreco)) {
                                $l20_usaregistropreco = "f";
                            }
                            db_select("l20_usaregistropreco", array("t" => "Sim", "f" => "N�o"), true, 3, "");
                            ?>
                        </span>
                    </td>
                </tr>
                <!--
                                <tr>
                                    <td id="usaregistropreco">
                                        <?= @$Ll03_usaregistropreco ?>
                                    </td>
                                    <td>
                                        <?
                                        if (!isset($l20_usaregistropreco)) {
                                            $l20_usaregistropreco = "f";
                                        }
                                        db_select("l20_usaregistropreco", array("t" => "Sim", "f" => "N�o"), true, $db_opcao, "");
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap title="<?= @$Tl20_liclocal ?>">
                                        <?
                                        //db_ancora(@$Ll20_liclocal,"js_pesquisal20_liclocal(true);",$db_opcao);
                                        ?>
                                    </td>
                                    <td> -->
                <?
                db_input('l20_liclocal', 10, $Il20_liclocal, true, 'hidden', $db_opcao, " onchange='js_pesquisal20_liclocal(false);'")
                ?>
                <?
                db_input('l20_liccomissao', 10, $Il20_liccomissao, true, 'hidden', $db_opcao, " onchange='js_pesquisal20_liccomissao(false);'")

                ?>
                <tr style="display:none" id="trequipepregao">
                    <td nowrap title="<?= @$Tl20_equipepregao ?>">
                        <?
                        db_ancora(@$Ll20_equipepregao, "js_pesquisal20_equipepregao(true);", $db_opcao);
                        ?>
                    </td>
                    <td>
                        <?
                        db_input('l20_equipepregao', 10, $Il20_equipepregao, true, 'text', $db_opcao, " onchange='js_pesquisal20_equipepregao(false);'");
                        ?>
                    </td>
                </tr>

                <tr id="formacontraleregistropreco">
                    <td nowrap title="<?= @$Tl20_formacontroleregistropreco ?>">
                        <?= @$Ll20_formacontroleregistropreco ?>
                    </td>
                    <td>
                        <?
                        if (!isset($ll20_formacontroleregistropreco)) {
                            $ll20_formacontroleregistropreco = "1";
                        }
                        db_select("l20_formacontroleregistropreco", array("1" => "Por Quantidade", "2" => "Por Valor"), true, $lBloqueadoRegistroPreco, "onchange='verificaTipoJulgamento()'");
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>
                        <b>Processo do Sistema:</b>
                    </td>
                    <td>
                        <?
                        $aProcSistema = array(
                            "s" => "Sim",
                            "n" => "N�o"
                        );
                        db_select('lprocsis', $aProcSistema, true, $db_opcao, "onChange='js_mudaProc(this.value);'");
                        ?>
                    </td>
                    <td>
                        <strong>Processo Administrativo:</strong>
                        <? db_input('l20_procadmin', 21, $Il20_procadmin, true, 'text', $db_opcao, "") ?>
                    </td>
                </tr>
            </table>
            <table>
                <!--
                <tr>
                    <td>
                        <?= @$Ll20_procadmin ?>
                    </td>
                    <td>
                        <? db_input('l20_procadmin', 21, $Il20_procadmin, true, 'text', $db_opcao, "") ?>
                    </td>
                </tr> -->
                <tr id="procSis">
                    <td nowrap title="<?= @$Tl34_protprocesso ?>">
                        <?
                        db_ancora($Ll34_protprocesso, "js_pesquisal34_protprocesso(true);", $db_opcao);
                        ?>
                    </td>
                    <td>
                        <?
                        db_input('p58_numero', 15, $Ip58_numero, true, 'hidden', $db_opcao, "onChange='js_pesquisal34_protprocesso(false);'");
                        db_input('l34_protprocesso', 10, $Il34_protprocesso, true, 'text', $db_opcao);
                        db_input('l34_protprocessodescr', 45, "",  true, 'text', 3, "");
                        ?>
                    </td>
                </tr>

            </table>

        </fieldset>

        <fieldset class="fieldsetinterno">
            <legend><b>Outras Informa��es</b></legend>

            <table>
                <tr>
                    <td nowrap title="<?= @$Tl20_local ?>" id="local">
                        <?= @$Ll20_local ?>
                    </td>
                    <td>
                        <?
                        db_textarea('l20_local', 0, 57, $Il20_local, true, 'text', $db_opcao, "onkeyup='limitaTextarea(this);'");
                        ?>
                    </td>
                </tr>

                <tr>
                    <td nowrap title="<?= @$Tl20_objeto ?>">
                        <?= @$Ll20_objeto ?>
                    </td>
                    <td>
                        <?
                        db_textarea('l20_objeto', 0, 57, $Il20_objeto, true, 'text', $db_opcao, "onkeyup='limitaTextareaobj(this);' onkeypress='doNothing()';");
                        ?>
                    </td>
                </tr>

                <tr>
                    <td nowrap title="<?= @$Tl20_localentrega ?>">
                        <?= @$Ll20_localentrega ?>
                    </td>
                    <td>
                        <?
                        db_textarea('l20_localentrega', 0, 57, $Il20_localentrega, true, 'text', $db_opcao, "onkeyup='limitaTextarea(this);'");
                        ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="<?= @$Tl20_prazoentrega ?>">
                        <?= @$Ll20_prazoentrega ?>
                    </td>
                    <td>
                        <?
                        db_textarea('l20_prazoentrega', 0, 57, $Il20_prazoentrega, true, 'text', $db_opcao, "onkeyup='limitaTextarea(this);'");
                        ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="<?= @$Tl20_condicoespag ?>">
                        <?= @$Ll20_condicoespag ?>
                    </td>
                    <td>
                        <?
                        db_textarea('l20_condicoespag', 0, 57, $Il20_condicoespag, true, 'text', $db_opcao, "onkeyup='limitaTextarea(this);'");
                        ?>
                    </td>
                </tr>

                <tr>
                    <td nowrap title="<?= @$Tl20_validadeproposta ?>">
                        <?= @$Ll20_validadeproposta ?>
                    </td>
                    <td>
                        <?
                        db_textarea('l20_validadeproposta', 0, 57, $Il20_validadeproposta, true, 'text', $db_opcao, "onkeyup='limitaTextarea(this);'");
                        ?>
                    </td>
                </tr>


                <tr>
                    <td nowrap title="<?= @$Tl20_clausulapro ?>">
                        <?= @$Ll20_clausulapro ?>
                    </td>
                    <td>
                        <?
                        db_textarea('l20_clausulapro', 0, 57, $Il20_clausulapro, true, 'text', $db_opcao, "onkeyup='limitaTextareacpro(this);'", '', '#E6E4F1');
                        ?>
                    </td>
                </tr>


                <tr id="aceitabilidade">
                    <td nowrap title="<?= @$Tl20_aceitabilidade ?>">
                        <b>Crit�rio de Aceitabilidade:</b>
                    </td>
                    <td>
                        <?
                        db_textarea('l20_aceitabilidade', 0, 57, $Il20_aceitabilidade, true, 'text', $db_opcao, "onkeyup='limitaTextarea(this);'");
                        ?>
                    </td>
                </tr>

                <tr id="justificativapncp" style="display:none">
                    <td>
                        <strong>Justificativa pela escolha da modalidade presencial:</strong>
                    </td>
                    <td>
                        <?
                        db_textarea('l20_justificativapncp', 0, 57, $Il20_justificativapncp, true, 'text', $db_opcao, "onkeyup='limitaTextarea(this);'");
                        ?>
                    </td>
                </tr>
            </table>
        </fieldset>

        <fieldset style="display:none;" id="dispensa">
            <legend><b>Dispensa/Inexigibilidade</b></legend>

            <table>


                <tr id='tr_tipoprocesso'>
                    <td nowrap title="<?= @$Tl20_tipoprocesso ?>">
                        <?= @$Ll20_tipoprocesso ?>
                    </td>
                    <td>
                        <?
                        $al20_tipoprocesso = array("0" => "Selecione", "1" => "1-Dispensa", "2" => "2-Inexigibilidade", "3" => "3-Inexigibilidade por credenciamento/chamada p�blica", "4" => "4-Dispensa por chamada publica","5" => "5-Dispensa para Registro de Pre�os","6" => "6-Inexigibilidade para Registro de Pre�os");
                        db_select("l20_tipoprocesso", $al20_tipoprocesso, true, $db_opcao,"onchange='js_verificatipoprocesso()'");
                        ?>
                    </td>
                </tr>
                <tr style="display: none" id="tr_dispensaporvalor">
                    <td nowrap title="<?= @$Tl_dispensaporvalor ?>">
                        <strong><?= "Dispensa por Valor:" ?></strong>
                    </td>
                <td>
                    <?php
                    $aDispensaPorValor = array(""=>"Selecione","t" => "Sim", "f" => "N�o");
                    db_select("l20_dispensaporvalor", $aDispensaPorValor, true,"");
                    ?>
                </td>
            </tr>


            <tr id="l20_criterioadjudicacaodispensa">
                                    <td id="descontotab">
                                        <label class="bold">Crit�rio de Adjudica��o:</label>
                                    </td>
                                    <td>
                                        <? //OC3770
                                        if (!isset($l20_criterioadjudicacao) || $l20_criterioadjudicacao == '') {
                                            $l20_criterioadjudicacao = "";
                                        }
                                        $aCriterios = array("0" => "Selecione","3" => "Outros", "1" => "Desconto sobre tabela", "2" => "Menor taxa ou percentual");
                                        db_select("l20_criterioadjudicacaodispensa", $aCriterios, true, '');
                                        ?>
                                    </td>
                                </tr>

                <tr>
                    <td nowrap title="<?= @$Tl20_justificativa ?>">
                        <?= @$Ll20_justificativa ?>
                    </td>
                    <td>
                        <?
                        db_textarea('l20_justificativa', 0, 53, $Il20_justificativa, true, 'text', $db_opcao, "onkeyup='limitaTextarea(this);'");
                        ?>
                    </td>
                </tr>

                <tr>
                    <td nowrap title="<?= @$Tl20_razao ?>">
                        <?= @$Ll20_razao ?>
                    </td>
                    <td>
                        <?
                        db_textarea('l20_razao', 0, 53, $Il20_razao, true, 'text', $db_opcao, "onkeyup='limitaTextarea(this);'");
                        ?>
                    </td>
                </tr>

            </table>
        </fieldset>

        </fieldset>

        </td>
        </tr>
        </table>

    </center>

    <input name="<?= ($db_opcao == 1 ? 'incluir' : ($db_opcao == 2 || $db_opcao == 22 ? 'alterar' : 'excluir')) ?>" type="submit" id="db_opcao" value="<?= ($db_opcao == 1 ? 'Incluir' : ($db_opcao == 2 || $db_opcao == 22 ? 'Alterar' : 'Excluir')) ?>" <?= ($db_botao == false ? 'disabled' : '') ?> onClick="return js_confirmadatas(codigotribunal)">
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
</form>
<script>
    var codigotribunal = 0;
    var numeracaopreenchida = false;
    document.form1.l20_prazoentrega.style.backgroundColor = '#FFFFFF';
    document.form1.l20_condicoespag.style.backgroundColor = '#FFFFFF';
    document.form1.l20_local.style.backgroundColor = '#E6E4F1';
    buscamodalidade = false;

    let elemento = document.getElementById('l20_tipnaturezaproced');
    elemento.addEventListener('change', (event) => {
        if (elemento.selectedIndex == 2) {
            document.getElementById('formacontraleregistropreco').style.display = '';
        } else {
            document.getElementById('formacontraleregistropreco').style.display = 'none';
        }
    });

    $('l20_codtipocomdescr').observe('change', function() {
        document.getElementById('l20_equipepregao').value = '';
    });

    js_busca();

    js_verificaDatasProposta();

    <?php $postRequest = count($_POST)?>;
    let postRequest = <? print $postRequest; ?>;
    postRequest = postRequest == 0 ? false : true;
    if(postRequest == false){
        document.getElementById('l20_criterioadjudicacao').value = '3';
    }

    // alterando a fun��o padrao para verificar  as op�oes de convite e de INEXIGIBILIDADE
    function js_ProcCod_l20_codtipocom(proc, res) {
        document.getElementById('l20_numero').style.display = '';
        document.getElementById('txtNumeracao').style.display = '';

        js_verificaDatasProposta();


        var sel1 = document.forms[0].elements[proc];
        var sel2 = document.forms[0].elements[res];
        for (var i = 0; i < sel1.options.length; i++) {
            if (sel1.options[sel1.selectedIndex].value == sel2.options[i].value)
                sel2.options[i].selected = true;
        }

        // ajax para fazer consulta sql, retornando o codigo do tribunal
        var codigocompra = document.getElementById("l20_codtipocom").options[document.getElementById("l20_codtipocom").selectedIndex].text;
        var oParam = new Object();
        oParam.codigo = codigocompra;

        var url = 'lic1_liclicita_consulta.php';
        var oAjax = new Ajax.Request(url, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornolicitacao
        });

    }


    function js_retornolicitacao(oAjax) {
        var oRetornoNatu = document.getElementById("l20_naturezaobjeto").value;
        var oRetornoamparo = document.getElementById("l212_codigo").value;
        if (oRetornoNatu == 1) {
            document.getElementById("respObras").style.display = "table-row";
        }

        var oRetorno = eval("(" + oAjax.responseText + ")");
        var l12_numeracaomanual = <? echo '"' . $l12_numeracaomanual . '"';      ?>;


        if (oRetorno.numeracao != "" && l12_numeracaomanual == 't' && numeracaopreenchida == false && buscamodalidade == true) document.getElementById('l20_numero').value = parseInt(oRetorno.numeracao) + 1;
        numeracaopreenchida = false;
        buscamodalidade = true;

        var campo = document.getElementById("l20_codtipocomdescr").options[document.getElementById("l20_codtipocomdescr").selectedIndex].text;
        var vUsua = document.getElementById("vUsuario").value;
        var vInclu = document.getElementById("vInclu").value;
        if (vUsua != "contass" && vInclu != "liclicita001.php") {
            document.getElementById("l20_codtipocom").disabled = true;
            document.getElementById("l20_codtipocomdescr").disabled = true;
        }

        document.getElementById("respCondunome").style.width = "258px";
        document.getElementById("l20_descricaodep").style.width = "258px";
        document.getElementById("lprocsis").style.width = "85px";
        document.getElementById("l20_usaregistropreco").style.width = "85px";
        document.getElementById("l20_codtipocomdescr").style.width = "307px";
        document.getElementById("l20_tipliticacao").style.width = "307px";
        document.getElementById("l20_leidalicitacao").style.width = "307px";
        document.getElementById("tr_l20_tipnaturezaproced").style.width = "307px";
        document.getElementById("l20_regimexecucao").style.width = "307px";
        document.getElementById("l20_naturezaobjeto").style.width = "307px";
        document.getElementById("l20_criterioadjudicacao").style.width = "307px";
        document.getElementById("l212_codigo").style.width = "307px";
        document.getElementById("l20_tipojulg").style.width = "85px";
        document.getElementById("l20_tipoprocesso").style.width = "400px";
        document.getElementById("l20_criterioadjudicacao").style.width = "400px";

        document.form1.modalidade_tribunal.value = oRetorno.tribunal;

        var l12_pncp = <? echo '"' . $l12_pncp . '"';      ?>;

        if (document.form1.l20_leidalicitacao.value == 1 && l12_pncp == 't') {
            document.getElementById("amparolegal").style.display = "";
            if (oRetorno.numrows > 0) {
                document.getElementById("amparolegal").style.display = "table_row";
                let listaamparolegal = document.getElementById('l212_codigo').options;
                for ($x = 59; $x > 0; $x--) {

                    listaamparolegal.remove($x);
                }

                for ($x = 0; $x < 200; $x++) {
                    if (oRetorno.amparo[$x] != "" && oRetorno.amparo[$x] != null) {

                        listaamparolegal.add(new Option(oRetorno.amparo[$x], $x));
                    }
                }
            } else if (oRetorno.numrows == 0 && document.getElementById("l20_codtipocom").value != 99) {
                document.getElementById("amparolegal").style.display = "none";
                let listaamparolegal = document.getElementById('l212_codigo').options;
                for ($x = 59; $x > 0; $x--) {

                    listaamparolegal.remove($x);
                }
            }
        } else {
            document.getElementById("amparolegal").style.display = "none";
            let listaamparolegal = document.getElementById('l212_codigo').options;
            for ($x = 59; $x > 0; $x--) {

                listaamparolegal.remove($x);
            }

        }
        document.querySelector("#l212_codigo").value = oRetornoamparo;
        // verifica se e do tipo convite
        if (oRetorno.tribunal == 48) {
            document.form1.l20_numeroconvidado.style.backgroundColor = '#FFFFFF';
            document.getElementById("l20_numeroconvidado").readOnly = false;
            document.getElementById("convite1").style.display = "table-row";
            document.getElementById("numeroconvidado").style.display = "table-cell";
            document.getElementById("l20_tipliticacao").disabled = false;
            document.getElementById("l20_naturezaobjeto").disabled = false;
        } else {
            var campo = document.getElementById("l20_codtipocomdescr").options[document.getElementById("l20_codtipocomdescr").selectedIndex].text;
            document.getElementById("convite1").style.display = "none";
            if (campo == "CONCURSO") {
                document.getElementById("l20_tipliticacao").style.display = "none";
                //document.getElementById("licitacao1").style.display = "none";
                document.getElementById("natureOB").style.display = "none";
                document.getElementById("natureOB1").style.display = "none";
            } else {
                document.getElementById("licitacao1").style.display = "table-cell";
                document.getElementById("l20_tipliticacao").style.display = "";
                document.getElementById("natureOB").style.display = "table-cell";
                document.getElementById("natureOB1").style.display = "table-cell";
            }
            document.form1.l20_numeroconvidado.style.backgroundColor = '#E6E4F1';
            $("l20_numeroconvidado").value = "";
            document.getElementById("l20_numeroconvidado").readOnly = true;
        }

        //valida��o para respons�vel na modalidade Leil�o
        if (oRetorno.tribunal == 54) {
            document.getElementById("respAvaliaBens").style.display = "table-row";
        } else {
            document.getElementById("respAvaliaBens").style.display = "none";
        }

        if (oRetorno.tribunal == 100 || oRetorno.tribunal == 101 || oRetorno.tribunal == 102 || oRetorno.tribunal == 103) {
            let tipoprocesso = <? echo '"' . $l20_tipoprocesso . '"';?>;
            let aDispensaInexigibilidade = ["102","103"];
            let modalidade_tribunal = document.getElementById('modalidade_tribunal').value;
            if($('l20_leidalicitacao').value == "1" && !aDispensaInexigibilidade.includes(modalidade_tribunal)){
                document.getElementById('l20_tipoprocesso').innerHTML = "";
                tipoProcesso = document.getElementById('l20_tipoprocesso').options;
                tipoProcesso.add(new Option('Selecione', 0));
                tipoProcesso.add(new Option('1- Dispensa', 1));
                tipoProcesso.add(new Option('2- Inexigibilidade', 2));
                tipoProcesso.add(new Option('3- Inexigibilidade por credenciamento/chamada p�blica', 3));
                tipoProcesso.add(new Option('4- Dispensa por chamada publica', 4));
                tipoProcesso.add(new Option('5- Dispensa para Registro de Pre�os', 5));
                tipoProcesso.add(new Option('6- Inexigibilidade para Registro de Pre�os', 6));
            } else{
                document.getElementById('l20_tipoprocesso').innerHTML = "";
                tipoProcesso = document.getElementById('l20_tipoprocesso').options;
                tipoProcesso.add(new Option('Selecione', 0));
                tipoProcesso.add(new Option('1- Dispensa', 1));
                tipoProcesso.add(new Option('2- Inexigibilidade', 2));
                tipoProcesso.add(new Option('3- Inexigibilidade por credenciamento/chamada p�blica', 3));
                tipoProcesso.add(new Option('4- Dispensa por chamada publica', 4));
            }

            if(document.getElementById('db_opcao').name != "incluir"){
                document.getElementById('l20_tipoprocesso').value = tipoprocesso;

                let criterioadjudicacao = <? echo '"' . $l20_criterioadjudicacao . '"';?>;
                if(tipoprocesso === "5" || tipoprocesso === "6"){

                var selectcriterio = document.form1.l20_criterioadjudicacaodispensa;
                selectcriterio.value = criterioadjudicacao;
                    
                document.getElementById('l20_criterioadjudicacaodispensa').style.display = "";
                } else {
                    document.getElementById('l20_criterioadjudicacaodispensa').style.display = "none";
                }
            }

            if(oRetorno.tribunal == 102 || oRetorno.tribunal == 103){
                document.getElementById("linha_nroedital").style.display = '';
            }else{
                document.getElementById("linha_nroedital").style.display = 'none';
            }

            if (l12_pncp == 't') {
                document.getElementById("receita").style.display = '';
                document.getElementById("dataaberturapncp").style.display = '';
            } else {
                document.getElementById("receita").style.display = 'none';
                document.getElementById("dataaberturapncp").style.display = 'none';
            }
            document.getElementById("respCondProcesso").style.display = "none"
            document.getElementById("tr_l20_tipnaturezaproced").style.display = "none";
            document.getElementById("usaregistropreco").style.display = 'none';
            document.getElementById("trequipepregao").style.display = 'none';
            document.getElementById("respAutoProcesso").style.display = '';
            document.getElementById("respAberProcesso").style.display = "none";
            document.getElementById("respEmissaoEdi").style.display = "none";
            document.getElementById("dataaber").style.display = "none";
            document.getElementById("dispensa").style.display = '';
            document.form1.l20_justificativa.style.backgroundColor = '#FFFFFF ';
            document.form1.l20_razao.style.backgroundColor = '#FFFFFF ';
            document.getElementById("l20_tipoprocesso").disabled = false;
            document.getElementById("l20_justificativa").disabled = false;
            document.getElementById("l20_razao").disabled = false;
            document.getElementById("recdocumentacao").style.display = 'none';
            document.getElementById("aceitabilidade").style.display = 'none';

            let listaNatureza = document.getElementById('l20_naturezaobjeto').options;
            listaNatureza.remove(7);
            listaNatureza.remove(6);
            listaNatureza.remove(5);
            listaNatureza.remove(4);
            listaNatureza.remove(3);
            listaNatureza.remove(2);
            listaNatureza.remove(1);
            listaNatureza.add(new Option('1- Obras e Servi�os de Engenharia', 1));
            listaNatureza.add(new Option('2- Compras e Outros Servi�os', 2));
            listaNatureza.add(new Option('3- Loca��o de Im�veis', 3));
            listaNatureza.add(new Option('7- Compras Para Obras e/ou Servi�os de Engenharia', 7));

            document.querySelector("#l20_naturezaobjeto").value = oRetornoNatu;

            /*Demandas sicom 2016*/
            document.form1.l20_numeroconvidado.style.display = 'none';
            document.form1.l20_dataaber.style.display = 'none';
            document.form1.dtjs_l20_dataaber.style.display = 'none';
            document.form1.l20_recdocumentacao.style.display = 'none';
            document.form1.dtjs_l20_recdocumentacao.style.display = 'none';
            document.form1.l20_local.style.display = 'none';
            document.getElementById("tipolicitacao").style.display = 'none';
            document.getElementById("tipnaturezaproced").style.display = 'none';
            document.getElementById("descontotab").style.display = 'none';
            document.getElementById("numeroconvidado").style.display = 'none';
            document.getElementById("recdocumentacao").style.display = 'none';
            document.getElementById("local").style.display = 'none';
            document.getElementById("respAvaliaBens").style.display = "none";
            document.getElementById("respAberProcesso").style.display = "none";
            document.getElementById("respEmissaoEdi").style.display = "none";
        } else {
            var codigo_lei = document.form1.l20_leidalicitacao.value;
            document.getElementById("linha_nroedital").style.display = '';

            if (codigo_lei == 1) {
                document.getElementById("disputa").style.display = '';
            }else{
                document.getElementById("disputa").style.display = 'none';
            }
            document.getElementById("respCondProcesso").style.display = '';
            document.getElementById("l20_tipliticacao").style.display = '';
            document.getElementById("tr_l20_tipnaturezaproced").style.display = '';
            //document.getElementById("l20_regimexecucao").style.display = '';
            document.getElementById("l20_criterioadjudicacao").style.display = '';
            document.getElementById("respAutoProcesso").style.display = "none";
            document.getElementById("respAberProcesso").style.display = '';
            document.getElementById("respEmissaoEdi").style.display = '';
            document.getElementById("dataaber").style.display = '';
            document.getElementById("dataaberturapncp").style.display = '';
            document.getElementById("trequipepregao").style.display = '';
            document.getElementById("usaregistropreco").style.display = '';
            document.getElementById("aceitabilidade").style.display = '';

            if (l12_pncp == 't') {
                document.getElementById("receita").style.display = '';
            } else {
                document.getElementById("receita").style.display = 'none';
            }

            let listaNatureza = document.getElementById('l20_naturezaobjeto').options;

            listaNatureza.remove(7);
            listaNatureza.remove(6);
            listaNatureza.remove(5);
            listaNatureza.remove(4);
            listaNatureza.remove(3);
            listaNatureza.remove(2);
            listaNatureza.remove(1);
            listaNatureza.add(new Option('1- Obras e Servi�os de Engenharia', 1));
            listaNatureza.add(new Option('2- Compras e Outros Servi�os', 2));
            listaNatureza.add(new Option('3- Loca��o de Im�veis', 3));
            listaNatureza.add(new Option('4- Concess�o', 4));
            listaNatureza.add(new Option('5- Permiss�o', 5));
            listaNatureza.add(new Option('6- Aliena��o de Bens', 6));
            listaNatureza.add(new Option('7- Compras Para Obras e/ou Servi�os de Engenharia', 7));

            document.querySelector("#l20_naturezaobjeto").value = oRetornoNatu;
            document.getElementById("l20_justificativa").disabled = true;
            document.getElementById("l20_razao").disabled = true;
            document.getElementById("l20_tipoprocesso").disabled = true;
            document.getElementById("dispensa").style.display = 'none';
            var codigo_lei = document.form1.l20_leidalicitacao.value;

            if (codigo_lei == 1) {
                document.getElementById("disputa").style.display = '';
            } else {
                document.getElementById("disputa").style.display = 'none';
            }
            //OC17312 toda vez que fizer uma altera��o na modalidade a op��o de lei de licita��o volta para selecionar para que o usuario coloque novamente a lei

            /*Demandas sicom 2016*/
            document.form1.l20_criterioadjudicacao.style.display = 'inline';
            document.form1.l20_numeroconvidado.style.display = 'inline';
            document.form1.l20_dataaber.style.display = 'inline';
            document.form1.dtjs_l20_dataaber.style.display = 'inline';
            document.form1.l20_recdocumentacao.style.display = 'inline';
            document.form1.dtjs_l20_recdocumentacao.style.display = 'inline';
            document.form1.l20_usaregistropreco.style.display = 'inline';
            document.form1.l20_local.style.display = 'inline';
            document.getElementById("tipnaturezaproced").style.display = 'inline';
            document.getElementById("descontotab").style.display = 'inline';
            document.getElementById("numeroconvidado").style.display = 'inline';
            document.getElementById("recdocumentacao").style.display = 'inline';
            document.getElementById("local").style.display = 'inline';
        }

        let aModalidades = ['48', '49', '50', '52', '53', '54'];
        let anousu = <?= db_getsession('DB_anousu'); ?>;

        if (anousu >= 2021) {
            aModalidades.push('102', '103');
        }

        let listaExecucoes = document.getElementById('l20_regimexecucao').options;
    }

    if ($F('l20_equipepregao') != '') {
        let modalidade = document.form1.modalidade_tribunal.value; //document.form1.l20_codtipocomdescr.value;
        if (modalidade == 52 || modalidade == 53) {
            verificaMembrosModalidade("pregao");
        } else if (modalidade == 48 || modalidade == 49 || modalidade == 50) {
            verificaMembrosModalidade("outros");
        }
    }

    document.getElementById("l20_tipoprocesso").disabled = true;
    /*para habiliatar o campo caso seja inex*/

    var campo = document.getElementById("l20_codtipocomdescr").options[document.getElementById("l20_codtipocomdescr").selectedIndex].text;
    campo = campo.replace(" ", "");
    if ([100, 101, 102, 103].includes(oRetorno.tribunal)) {
        document.getElementById("l20_justificativa").disabled = false;
        document.getElementById("l20_razao").disabled = false;
        document.getElementById("l20_tipoprocesso").disabled = false;
        document.form1.l20_justificativa.style.backgroundColor = '#FFFFFF ';
        document.form1.l20_razao.style.backgroundColor = '#FFFFFF ';
    }

    function js_busca() {
        var codigocompra = document.getElementById("l20_codtipocom").options[document.getElementById("l20_codtipocom").selectedIndex].text;
        var oParam = new Object();
        oParam.codigo = codigocompra;
        var url = 'lic1_liclicita_consulta.php';
        var oAjax = new Ajax.Request(url, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornolicitacao
        });
    }

    function js_naturezaprocedimento(valor) {
        if (valor == 2) {
            $('l20_usaregistropreco').value = 't';
            $('l20_usaregistropreco').disabled = "disabled";

        } else if (valor == 1) {
            $('l20_usaregistropreco').value = 'f';
            $('l20_usaregistropreco').disabled = "disabled";

        } else if (valor == 3) {
            $('l20_usaregistropreco').value = 'f';
            $('l20_usaregistropreco').disabled = '';
        }
    }

    function js_ativaregistro() {

        document.getElementById("l20_codtipocom").disabled = false;
        document.getElementById("l20_codtipocomdescr").disabled = false;
        var campo = $(l20_tipnaturezaproced).value;
        $('l20_usaregistropreco').disabled = "";
        var campo = document.getElementById("l20_codtipocomdescr").options[document.getElementById("l20_codtipocomdescr").selectedIndex].text;
        var convite = document.getElementById('l20_numeroconvidado').value;

        document.getElementById('descricao').value = campo;
        document.form1.submit();

    }

    function js_verifica_hora(valor, campo) {
        erro = 0;
        ms = "";
        hs = "";

        tam = "";
        pos = "";
        tam = valor.length;
        pos = valor.indexOf(":");
        if (pos != -1) {
            if (pos == 0 || pos > 2) {
                erro++;
            } else {
                if (pos == 1) {
                    hs = "0" + valor.substr(0, 1);
                    ms = valor.substr(pos + 1, 2);
                } else if (pos == 2) {
                    hs = valor.substr(0, 2);
                    ms = valor.substr(pos + 1, 2);
                }
                if (ms == "") {
                    ms = "00";
                }
            }
        } else {
            if (tam >= 4) {
                hs = valor.substr(0, 2);
                ms = valor.substr(2, 2);
            } else if (tam == 3) {
                hs = "0" + valor.substr(0, 1);
                ms = valor.substr(1, 2);
            } else if (tam == 2) {
                hs = valor;
                ms = "00";
            } else if (tam == 1) {
                hs = "0" + valor;
                ms = "00";
            }
        }
        if (ms != "" && hs != "") {
            if (hs > 24 || hs < 0 || ms > 60 || ms < 0) {
                erro++
            } else {
                if (ms == 60) {
                    ms = "59";
                }
                if (hs == 24) {
                    hs = "00";
                }
                hora = hs;
                minu = ms;
            }
        }

        if (erro > 0) {
            alert("Informe uma hora v�lida.");
        }
        if (valor != "") {
            eval("document.form1." + campo + ".focus();");
            eval("document.form1." + campo + ".value='" + hora + ":" + minu + "';");
        }
    }

    var sUrl = "lic4_licitacao.RPC.php";
    var oTipoJulgamento = $('trTipoJulgamento');



    function verificaTipoJulgamento() {
        let oTipoJulgamento = $('trTipoJulgamento');
        oTipoJulgamento.style.display = '';
        if ($F('l20_formacontroleregistropreco') == "2") {
            $('l20_tipojulg').value = "1";
            oTipoJulgamento.style.display = 'none';
        }
    }

    $('l20_codtipocom').observe('change', function() {
        js_verificaModalidade();
    });
    $('l20_codtipocomdescr').observe('change', function() {

        js_verificaModalidade();
    });

    function js_verificaDatasProposta() {

        var sUrl = "lic4_licitacao.RPC.php";

        var oParam = new Object();
        oParam.exec = "getCodigoTribunal";
        oParam.iModalidade = $F('l20_codtipocomdescr');

        var oAjax = new Ajax.Request(sUrl, {
            method: "post",
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoVerificaDatasProposta
        });

    }

    function js_retornoVerificaDatasProposta(oAjax) {

        var oRetorno = eval("(" + oAjax.responseText + ")");
        codigotribunal = oRetorno.l03_pctipocompratribunal;
        presencial = oRetorno.l03_presencial;
        let criterioadjudicacao = document.getElementById('l20_criterioadjudicacao').value;
        if(codigotribunal == 100 || codigotribunal == 101 || codigotribunal == 102 || codigotribunal == 103){
            document.getElementById('l20_criterioadjudicacao').style.display = 'none';
        }else{
            document.getElementById('l20_criterioadjudicacao').style.display = '';
        }



        let lei = document.getElementById('l20_leidalicitacao').value;

        var l12_pncp = <? echo '"' . $l12_pncp . '"';      ?>;

        if (presencial == 't' && l12_pncp == 't' && lei == 1) {
            document.getElementById('justificativapncp').style.display = '';
        } else {
            document.getElementById('justificativapncp').style.display = 'none';
        }

        if (codigotribunal == 100 || codigotribunal == 102 || codigotribunal == 103) {
             var select = document.getElementById("l20_tipliticacao");

            // Definir o valor padr�o para "7"
            select.value = "7";

            // Esconder as outras op��es exceto a op��o "7"
            for (var i = 0; i < select.options.length; i++) {
                var option = select.options[i];
                if (option.value !== "7") {
                option.style.display = "none";
                }
            }
             } else {
                var select = document.getElementById("l20_tipliticacao");

            for (var i = 0; i < select.options.length; i++) {
                var option = select.options[i];
                option.style.display = "";
                }
            }

        if (l12_pncp == 't') {
            document.getElementById('categoriaprocesso').style.display = '';
            document.getElementById("dataaberturapncp").style.display = '';
            document.getElementById("dataencerramentopncp").style.display = '';
            document.getElementById("receita").style.display = '';
        }

        if (lei == "1" && l12_pncp == "t") {
            for (const s of document.getElementsByClassName("dataabertura")) {
                s.style.display = '';
            }

        } else {
            for (const s of document.getElementsByClassName("dataabertura")) {
                s.style.display = 'none';
            }
        }
        js_alteracaoTipoProcesso(codigotribunal);

    }

    function js_verificaModalidade() {


        js_divCarregando("Aguarde, pesquisando dados da modalidade.", "msgBox");
        var oParam = new Object();
        oParam.exec = "verificaModalidade";
        oParam.iModalidade = $F('l20_codtipocom');

        var oAjax = new Ajax.Request(sUrl, {
            method: "post",
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoVerificaModalidade
        });

    }

    function js_retornoVerificaModalidade(oAjax) {

        js_removeObj("msgBox");
        var oRetorno = eval("(" + oAjax.responseText + ")");

        $("l20_usaregistropreco").options.length = 0;
        if (oRetorno.l03_usaregistropreco == 't') {
            //true pode por sim nao no campo l20_usaregistropreco

            $("l20_usaregistropreco").options[0] = new Option("N�o", "f");
            $("l20_usaregistropreco").options[1] = new Option("Sim", "t");
        } else {
            // false somentenao
            $("l20_usaregistropreco").options[0] = new Option("N�o", "f");
        }
    }

    function js_mudaProc(sTipoProc) {

        if (sTipoProc == 's') {
            $('procSis').style.display = '';
            $('procAdm').style.display = 'none';
        } else {
            $('procSis').style.display = 'none';
            $('procAdm').style.display = '';
        }

    }

    function js_pesquisal20_codepartamento(mostra) {
        if (mostra == true) {
            var sUrl = 'func_db_depart.php?funcao_js=parent.js_mostradepartamento|coddepto|descrdepto';
            js_OpenJanelaIframe('', 'db_iframe_departamento', sUrl, 'Pesquisar Departamento', true, '0');
        } else {
            if (document.form1.l20_codepartamento.value != '') {
                js_OpenJanelaIframe('', 'db_iframe_departamento', 'func_db_depart.php?pesquisa_chave=' + document.form1.l20_codepartamento.value + '&funcao_js=parent.js_mostrardepartamento',
                    'Pesquisar licita��o Outro �rg�o',
                    false,
                    '0');
            }
        }

    }

    function js_mostrardepartamento(chave, erro) {
        document.form1.l20_descricaodep.value = chave;

        if (erro == true) {
            document.form1.l20_descricaodep.focus();
        }
    }

    function js_mostradepartamento(chave, chave2) {
        $('l20_codepartamento').value = chave;
        $('l20_descricaodep').value = chave2;
        $('l20_codepartamento').focus();

        db_iframe_departamento.hide();
    }

    function js_pesquisal20_codtipocom(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_pctipocompra', 'func_pctipocompra.php?funcao_js=parent.js_mostrapctipocompra1|pc50_codcom|pc50_descr', 'Pesquisa', true, 0);
        } else {
            if (document.form1.l20_codtipocom.value != '') {
                js_OpenJanelaIframe('top.corpo', 'db_iframe_pctipocompra', 'func_pctipocompra.php?pesquisa_chave=' + document.form1.l20_codtipocom.value + '&funcao_js=parent.js_mostrapctipocompra', 'Pesquisa', false);
            } else {
                document.form1.pc50_descr.value = '';
            }
        }
    }

    function js_mostrapctipocompra(chave, erro) {
        document.form1.pc50_descr.value = chave;
        if (erro == true) {
            document.form1.l20_codtipocom.focus();
            document.form1.l20_codtipocom.value = '';
        }
    }

    function js_mostrapctipocompra1(chave1, chave2) {
        document.form1.l20_codtipocom.value = chave1;
        document.form1.pc50_descr.value = chave2;
        db_iframe_pctipocompra.hide();
    }

    function js_pesquisal20_id_usucria(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_db_usuarios', 'func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome', 'Pesquisa', true, 0);
        } else {
            if (document.form1.l20_id_usucria.value != '') {
                js_OpenJanelaIframe('top.corpo', 'db_iframe_db_usuarios', 'func_db_usuarios.php?pesquisa_chave=' + document.form1.l20_id_usucria.value + '&funcao_js=parent.js_mostradb_usuarios', 'Pesquisa', false);
            } else {
                document.form1.nome.value = '';
            }
        }
    }

    function js_mostradb_usuarios(chave, erro) {
        document.form1.nome.value = chave;
        if (erro == true) {
            document.form1.l20_id_usucria.focus();
            document.form1.l20_id_usucria.value = '';
        }
    }

    function js_mostradb_usuarios1(chave1, chave2) {
        document.form1.l20_id_usucria.value = chave1;
        document.form1.nome.value = chave2;
        db_iframe_db_usuarios.hide();
    }

    function js_pesquisa() {
        js_OpenJanelaIframe('', 'db_iframe_liclicita', 'func_liclicita.php?tipo=1&situacao=0&funcao_js=parent.js_preenchepesquisa|l20_codigo', 'Pesquisa', true, "0");
    }

    function js_preenchepesquisa(chave) {
        db_iframe_liclicita.hide();
        <?
        if ($db_opcao != 1) {
            echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave;";
        ?>
            parent.iframe_liclicitem.location.href = 'lic1_liclicitemalt001.php?licitacao=' + chave;
        <?
        }
        ?>
    }

    function js_pesquisal20_liclocal(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_local', 'func_liclocal.php?funcao_js=parent.js_mostralocal1|l26_codigo', 'Pesquisa', true, "0");
        } else {
            if (document.form1.l20_liclocal.value != '') {
                js_OpenJanelaIframe('', 'db_iframe_local', 'func_liclocal.php?pesquisa_chave=' + document.form1.l20_liclocal.value + '&funcao_js=parent.js_mostralocal&sCampoRetorno=l20_liclocal', 'Pesquisa', false);
            } else {
                document.form1.nome.value = '';
            }
        }
    }

    function js_mostralocal(chave, erro) {
        if (erro == true) {
            document.form1.l20_liclocal.focus();
            document.form1.l20_liclocal.value = '';
        }
    }

    function js_mostralocal1(chave1) {
        document.form1.l20_liclocal.value = chave1;
        db_iframe_local.hide();
    }

    function js_pesquisal20_liccomissao(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_comissao', 'func_liccomissao.php?funcao_js=parent.js_mostracomissao1|l30_codigo', 'Pesquisa', true, "0");
        } else {

            if (document.form1.l20_liccomissao.value != '') {
                js_OpenJanelaIframe('', 'db_iframe_proc', 'func_liccomissao.php?pesquisa_chave=' + document.form1.l20_liccomissao.value + '&funcao_js=parent.js_mostracomissao&sCampoRetorno=l20_liccomissao', 'Pesquisa', false);
            } else {
                document.form1.l20_liccomissao.value = '';
            }



        }
    }

    function js_mostracomissao(chave, erro) {
        if (erro == true) {
            document.form1.l20_liccomissao.focus();
            document.form1.l20_liccomissao.value = '';
        }
    }

    function js_mostracomissao1(chave1) {
        document.form1.l20_liccomissao.value = chave1;
        db_iframe_comissao.hide();
    }

    function js_pesquisal34_protprocesso(mostra) {

        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_proc', 'func_protprocesso_protocolo.php?funcao_js=parent.js_mostraprocesso1|p58_numero|p58_codproc|dl_nome_ou_raz�o_social', 'Pesquisa', true, "0");
        } else {

            if (document.form1.p58_numero.value != '') {
                js_OpenJanelaIframe('', 'db_iframe_proc', 'func_protprocesso_protocolo.php?pesquisa_chave=' + document.form1.p58_numero.value + '&funcao_js=parent.js_mostraprocesso&sCampoRetorno=p58_codproc', 'Pesquisa', false);
            } else {
                document.form1.l34_protprocessodescr.value = '';
            }
        }
    }

    function js_mostraprocesso(iCodigoProcesso, sNome, lErro) {

        document.form1.l34_protprocessodescr.value = sNome;

        if (lErro) {

            document.form1.p58_numero.focus();
            document.form1.p58_numero.value = '';
            document.form1.l34_protprocesso.value = '';
            return false;
        }

        document.form1.l34_protprocesso.value = iCodigoProcesso;

        db_iframe_proc.hide();
    }

    function js_mostraprocesso1(iNumeroProcesso, iCodigoProcesso, sNome) {

        document.form1.p58_numero.value = iNumeroProcesso;
        document.form1.l34_protprocesso.value = iCodigoProcesso;
        document.form1.l34_protprocessodescr.value = sNome;
        db_iframe_proc.hide();
    }

    function js_pesquisal20_equipepregao(mostra) {

        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_proc', 'func_licpregao.php?funcao_js=parent.js_pregao|l45_sequencial', 'Pesquisa', true, "0");
        } else {

            if (document.form1.l20_equipepregao.value != '') {
                js_OpenJanelaIframe('', 'db_iframe_proc', 'func_licpregao.php?pesquisa_chave=' + document.form1.l20_equipepregao.value + '&funcao_js=parent.js_pregao&sCampoRetorno=l20_equipepregao', 'Pesquisa', false);
            } else {
                document.form1.nome.value = '';
            }
        }
    }

    function novoAjax(params, onComplete) {

        var request = new Ajax.Request('lic4_licitacao.RPC.php', {
            method: 'post',
            parameters: 'json=' + Object.toJSON(params),
            onComplete: onComplete
        });

    }

    function verificaMembrosModalidade(modalidade) {
        var params = {
            exec: 'VerificaMembrosModalidade',
            equipepregao: $F('l20_equipepregao'),
            modalidade: modalidade
        };
        
        novoAjax(params, function(e) {
            var oRetorno = JSON.parse(e.responseText);

            if (oRetorno.validaMod == 0) {
                if (modalidade == 'pregao') {
                    alert("Para as modalidades Preg�o presencial e Preg�o eletr�nico � necess�rio\nque a Comiss�o de Licita��o tenham os tipos Pregoeiro e Membro da Equipe de Apoio");
                    document.form1.l20_equipepregao.value = "";
                    document.form1.l20_equipepregao.focus();
                    return false;
                } else if (modalidade == 'outros') {

                    alert("Para as modalidades Tomada de Pre�os, Concorr�ncia e Convite � necess�rio que a Comiss�o de Licita��o tenham os tipos Secret�rio, Presidente e Membro da Equipe de Apoio");
                    document.form1.l20_equipepregao.value = "";
                    document.form1.l20_equipepregao.focus();
                    return false;
                }
            }
        });
    }

    function js_pregao(iCodigoProcesso, sNome, lErro) {

        document.form1.l20_equipepregao.value = iCodigoProcesso;
        db_iframe_proc.hide();

        let codigoModalidade = document.form1.modalidade_tribunal.value;
        let aModalidades = {
            52 : "pregao",
            53 : "pregao",
            50 : "outros",
            49 : "outros",
            51: "outros",
            54: "outros",
            110: "outros"
        };
        let descricaoModalidade = aModalidades[codigoModalidade];
        let lei = document.form1.l20_leidalicitacao.value;

        if(lei == "1"){

            resultadoValidacao = verificaMembrosModalidadeParaLei1(descricaoModalidade);
            if(resultadoValidacao == false && descricaoModalidade == "pregao"){
                document.getElementById("l20_equipepregao").value = "";
                return alert("Usu�rio: Para as modalidades Preg�o Presencial e Preg�o Eletr�nico � necess�rio que a Comiss�o de Licita��o tenha o tipo Pregoeiro.");
            }

            if(resultadoValidacao == false && descricaoModalidade == "outros"){
                document.getElementById("l20_equipepregao").value = "";
                return alert("Usu�rio: Para as modalidades Concorr�ncia, Concurso, Leil�o e Di�logo competitivo � necess�rio que a Comiss�o de Licita��o tenha os tipos Agente de contrata��o ou Comiss�o de Contrata��o.");
            }

            return;

        }


        verificaMembrosModalidade(descricaoModalidade);

    }

    var sUrl = "lic4_licitacao.RPC.php";

    function js_mostraRegistroPreco() {

        alert($F('l20_codtipocom'));

        js_divCarregando("Aguarde, pesquisando parametros", "msgBox");
        var oParam = new Object();
        oParam.exec = "verificaParametros";
        oParam.itipoLicitacao = $F('l20_codtipocom');
        db_iframe_estimativaregistropreco.hide();
        var oAjax = new Ajax.Request(sUrlRC, {
            method: "post",
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoRegistroPreco
        });

    }

    function js_retornoRegistroPreco(oAjax) {

        js_removeObj("msgBox");
        var oRetorno = eval("(" + oAjax.responseText + ")");
        if (oRetorno.status == 1) {}
    }

    function js_verificaCritAdj(valor) {
        var codtipocom = $F('l20_codtipocom');
        return valor == codtipocom;
    }

    function js_confirmadatas(codigotribunal) {
        js_naturezaprocedimento(document.getElementById('l20_tipnaturezaproced').value);

        if(document.getElementById('tr_l20_tipnaturezaproced').style.display == 'none'){
            document.getElementById('l20_tipnaturezaproced').value = "0";
        }

        if (document.getElementById('l20_numero').value == "0") {
            alert("Usu�rio: o campo Numera��o n�o pode ser preenchido com valor 0");
            return false;
        }

        if (document.getElementById('l20_edital').value == "0") {
            alert("Usu�rio: o campo Processo Licitat�rio: n�o pode ser preenchido com valor 0");
            return false;
        }

        var l12_pncp = <? echo '"' . $l12_pncp . '"';      ?>;

        if(l12_pncp == "t"){
            let tipocriterio = document.getElementById('l20_tipliticacao').value;
            if(codigotribunal == 54 && tipocriterio !== "5"){
                alert('Usu�rio: De acordo com o manual do PNCP, para a modalidade selecionada o crit�rio de julgamento tem que ser 5-Maior Lance.');
                return false;
            }
            if ((codigotribunal == 50) && tipocriterio == "7") {
                alert('Usu�rio: De acordo com o manual do PNCP, para a modalidade selecionada o crit�rio de julgamento n�o poder� ser 7-N�o Aplic�vel');
                return false;
            }
            if (codigotribunal == 51 && (tipocriterio !== "8" && tipocriterio !== "9")) {
                alert('Usu�rio: De acordo com o manual do PNCP, para a modalidade selecionada o crit�rio de julgamento tem que ser 8-Melhor T�cnica ou 9-Conte�do art�stico.');
                return false;
            }


            if (codigotribunal == 52 || codigotribunal == 53) {
                if (!["1", "2", "5"].includes(tipocriterio)) {
                    alert('Usu�rio: De acordo com o manual do PNCP, para a modalidade selecionada o crit�rio de julgamento tem que ser 1-Menor Pre�o, 2-Maior Desconto ou 5-Maior Lance.');
                    return false;
                }
            }
            if (codigotribunal == 101) {
                if (!["1", "2", "5", "7"].includes(tipocriterio)) {
                    alert('Usu�rio: De acordo com o manual do PNCP, para a modalidade selecionada o crit�rio de julgamento tem que ser 1-Menor Pre�o, 2-Maior Desconto, 5-Maior Lance ou 7-N�o Aplic�vel.');
                    return false;
                }
            }
            if (codigotribunal == 110 && tipocriterio == "7") {
            alert('Usu�rio: De acordo com o manual do PNCP, para a modalidade selecionada o crit�rio de julgamento n�o poder� ser 7-N�o Aplic�vel');
            return false;
        }
        }

        if (document.getElementById('l20_nroedital').value == "0") {
            alert("Usu�rio: o campo Edital n�o pode ser preenchido com valor 0");
            return false;
        }

        var l12_pncp = <? echo '"' . $l12_pncp . '"';      ?>;
        var db_opcao = <? echo '"' . $db_opcao . '"';      ?>;

        if (l12_pncp == 't' && document.getElementById('l20_dataaberproposta').style.display == "" && document.getElementById('l20_dataaberproposta').value == "" && db_opcao != 3) {
            alert("Campo Data de Abertura da Proposta n�o Informado");
            return false;
        }

        if (l12_pncp == 't' && document.getElementById('l20_dataencproposta').style.display == "" && document.getElementById('l20_dataencproposta').value == "" && db_opcao != 3) {
            alert("Campo Data de Encerramento da Proposta n�o Informado");
            return false;
        }

        var dataCriacao = $F('l20_datacria');
        var dataAbertura = $F('l20_dataaber');
        var critadjudicac = $F('l20_criterioadjudicacao');
        var matriz = [1, 2, 3, 4, 5, 11, 19];
        var verifica = matriz.find(js_verificaCritAdj);

        if (verifica) {
            if (!critadjudicac) {
                alert("Informe Crit�rio de Adjudica��o");
                return false;
            }
        }
        /*
        if (js_CompararDatas(dataCriacao, dataPublicacao, '<=')) {
            if (js_CompararDatas(dataPublicacao, dataAbertura, '<=')) {
                <?
                if ($db_opcao == 2 || $db_opcao == 22) {
                    echo 'return js_confirmar();';
                } else {
                    echo 'return true;';
                }
                ?>
            } else {

                alert("A Data Edital/Convite deve ser maior ou igual a Data de Publica��o.");
                document.form1.l20_dataaber.style.backgroundColor='#99A9AE';
                 document.form1.l20_dataaber.focus();
                return false;
            }
        } else {
            alert("A Data de Publica��o deve ser maior ou igual a Data de Cria��o.");
            return false;
        }
        */
    }

    function js_CompararDatas(data1, data2, comparar) {

        if (data1.indexOf('/') != -1) {
            datepart = data1.split('/');
            pYear = datepart[2];
            pMonth = datepart[1];
            pDay = datepart[0];
        }
        data1 = pYear + pMonth + pDay;

        if (data2.indexOf('/') != -1) {
            datepart = data2.split('/');
            pYear = datepart[2];
            pMonth = datepart[1];
            pDay = datepart[0];
        }
        data2 = pYear + pMonth + pDay;
        if (eval(data1 + " " + comparar + " " + data2)) {

            return true;

        } else {
            return false;
        }
    }

    /*Para desabilitar o combo  Usa Registro de Pre�o */
    var campo = $(l20_tipnaturezaproced).value;
    if (campo == '2') {
        $('l20_usaregistropreco').value = 't';
        $('l20_usaregistropreco').disabled = "disabled";
    }

    document.form1.l20_justificativa.style.backgroundColor = '#E6E4F1';
    document.form1.l20_razao.style.backgroundColor = '#E6E4F1';
    document.getElementById("l20_numeroconvidado").readOnly = true;

    var campo = document.getElementById("l20_codtipocomdescr").options[document.getElementById("l20_codtipocomdescr").selectedIndex].text;
    if (campo == "CONVITE") {
        document.getElementById("l20_numeroconvidado").readOnly = false;
    }


    /*Fun��o para limitar texaarea*/
    //"onkeyup='limitaTextarea(this.value);'");
    function limitaTextarea(valor) {
        var qnt = valor.value;
        quantidade = 80;
        total = qnt.length;

        if (total <= quantidade) {
            resto = quantidade - total;
            document.getElementById('contador').innerHTML = resto;
        } else {
            document.getElementById(valor.name).value = qnt.substr(0, quantidade);
            alert("Ol�. Para atender  as normas do TCE MG / SICOM, este campo �  limitado. * LIMITE ALCAN�ADO * !");
        }
    }

    function limitaTextareaobj(valor) {
        var qnt = valor.value;
        const arra = qnt.split("\n");
        var valAr = arra.length;
        var res = "";

        if (valAr > 1) {
            for (i = 0; i < valAr; i++) {
                res += " " + arra[i];
            }

            qnt = res;
            document.getElementById(valor.name).value = qnt;
        }

        quantidade = 999;
        total = qnt.length;

        if (total <= quantidade) {
            resto = quantidade - total;
            document.getElementById('contador').innerHTML = resto;
        } else {
            document.getElementById(valor.name).value = qnt.substr(0, quantidade);
            alert("Ol�. Para atender  as normas do TCE MG / SICOM, este campo �  limitado. * LIMITE ALCAN�ADO * !");
        }
    }

    function doNothing() {
        var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
        if (keyCode == 13) {

            if (!e) var e = window.event;

            e.cancelBubble = true;
            e.returnValue = false;

            if (e.stopPropagation) {
                e.stopPropagation();
                e.preventDefault();
            }
        }
    }

    function js_verificalei(lei) {

        js_verificaDatasProposta();

        var oRetornoTipo = document.getElementById('l20_tipliticacao').value;

        var opcoesreg = document.getElementById('l20_tipliticacao').options;

        let modalidade = document.form1.modalidade_tribunal.value;

        if (document.getElementById('l20_codtipocomdescr').value != 99) numeracaopreenchida = true;
        js_ProcCod_l20_codtipocom('l20_codtipocomdescr', 'l20_codtipocom');

        let l12_pncp = <? echo '"' . $l12_pncp . '"';      ?>;

        if (lei == 1 && l12_pncp == 't') {
            document.getElementById("amparolegal").style.display = "";
        }else{
            document.getElementById("amparolegal").style.display = "none";
        }

        if(lei == 1){
            document.getElementById("disputa").style.display = "";
        } else {
            document.getElementById("disputa").style.display = "none";
        }
    }

    function limitaTextareacpro(valor) {
        var qnt = valor.value;
        quantidade = 249;
        total = qnt.length;

        if (total <= quantidade) {
            resto = quantidade - total;
            document.getElementById('contador').innerHTML = resto;
        } else {
            document.getElementById(valor.name).value = qnt.substr(0, quantidade);
            alert("Ol�. Para atender  as normas do TCE MG / SICOM, este campo �  limitado. * LIMITE ALCAN�ADO * !");
        }
    }

    <?
    if ($db_opcao == 1) {
        echo "js_mudaProc('{$lprocsis}');";
    } else {
        if ((isset($l34_protprocesso) && trim($l34_protprocesso) != '')) {
            echo "js_mudaProc('s');";
        }
    }
    ?>

    function mostrarFormaControleRegistroPreco() {

        if ($F('l20_usaregistropreco') == 't') {
            $('formacontraleregistropreco').style.display = '';
        } else {
            $('l20_formacontroleregistropreco').value = '1'
            $('formacontraleregistropreco').style.display = 'none';
        }



        verificaTipoJulgamento();
    }

    function js_regime(valor) {
        let anousuario = "<?php echo db_getsession('DB_anousu'); ?>";
        if (valor == 1) {
            document.getElementById("respObras").style.display = "table-row";
        } else {
            document.getElementById("respObras").style.display = "none";
        }


        if (anousuario >= 2019) {
            let opcoes = document.getElementById('l20_regimexecucao').options;

            if (document.getElementById('modalidade_tribunal').value) {
                if (valor != 7) {
                    if (opcoes.item(7)) {
                        opcoes.remove(7);
                    }
                    if (opcoes.item(6)) {
                        opcoes.remove(6);
                    }
                    if (opcoes.item(5)) {
                        opcoes.remove(5);
                    }
                }

                if (valor == 7) {
                    opcoes.add(new Option('5- Execu��o Direta', 5));
                    opcoes.add(new Option('6- Contrata��o Integrada'), 6);
                    opcoes.add(new Option('7- Contrata��o Semi Integrada', 7));
                }
            }

        }
    }
    var varNumCampo;
    var varNomeCampo;

    function js_pesquisal31_numcgm(mostra, numCampo, nomeCampo) {
        varNumCampo = numCampo;
        varNomeCampo = nomeCampo;

        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_cgm', 'func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome&filtro=1', 'Pesquisa', true, '0', '1');
        } else {
            numcgm = document.getElementById(numCampo).value;
            if (numcgm != '') {
                js_OpenJanelaIframe('', 'db_iframe_cgm', 'func_nome.php?pesquisa_chave=' + numcgm + '&funcao_js=parent.js_mostracgm&filtro=1', 'Pesquisa', false);
            } else {
                document.getElementById(numCampo).value = "";
            }
        }
    }

    function js_mostracgm(erro, chave) {
        document.getElementById(varNomeCampo).value = chave;
        if (erro == true) {
            //  document.form1.l31_numcgm.focus();
            document.getElementById(varNumCampo).value = "";
            document.getElementById(varNomeCampo).value = "";
            alert("Respons�vel n�o encontrado!");
        }
    }

    function js_mostracgm1(chave1, chave2) {

        document.getElementById(varNumCampo).value = chave1;
        document.getElementById(varNomeCampo).value = chave2;
        db_iframe_cgm.hide();
    }

    function js_alteracaoTipoProcesso(codigo_tribunal) {
        let tipoProcesso = document.getElementById('l20_tipoprocesso').value;
        let lei = document.getElementById('l20_leidalicitacao').value;

        if (codigo_tribunal === 101) {
            document.getElementById('tr_tipoprocesso').style.display = "";
        }
            if (lei === '1' && tipoProcesso === "5" || tipoProcesso === "6") {
            document.getElementById('l20_criterioadjudicacaodispensa').style.display = "";
            document.getElementById('l20_usaregistropreco').value = "t";
        }   else {
            document.getElementById('l20_usaregistropreco').value = "f";
            document.getElementById('l20_criterioadjudicacaodispensa').style.display = "none";
        }
        js_verificatipoprocesso();
    }

    function js_verificatipoprocesso(){
        let lei = document.getElementById('l20_leidalicitacao').value;
        let tipoProcesso = document.getElementById('l20_tipoprocesso').value;
        if (tipoProcesso === '1' && lei === '1') {
            document.getElementById('tr_dispensaporvalor').style.display = "";
        }else{
            document.getElementById('tr_dispensaporvalor').style.display = "none";
        }
        js_alteracaoTipoProcesso();
    }

    function verificaMembrosModalidadeParaLei1(modalidade){
        var oParametro = new Object();
        oParametro.exec = 'VerificaMembrosModalidadeParaLei1',
        oParametro.comissao = document.getElementById('l20_equipepregao').value;
        oParametro.modalidade = modalidade;
        let validacao;
        var oAjax = new Ajax.Request('lic4_licitacao.RPC.php', {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametro),
            asynchronous: false,
            onComplete: function(oAjax) {
                var oRetorno = eval("(" + oAjax.responseText + ")");
                validacao = oRetorno.validacao;
            }
        });

        return validacao;

    }

</script>
<?
if (empty($l34_liclicita)) {
    echo "<script>
         document.form1.lprocsis.value = 'n';
         $('procSis').style.display = 'none';
         $('procAdm').style.display = '';
        </script>";
}
?>
<script>
    mostrarFormaControleRegistroPreco();

    function bloquearRegistroPreco() {

        $('l20_usaregistropreco').disabled = true;
        $('l20_usaregistropreco').className = 'readonly';
        $('l20_formacontroleregistropreco').disabled = true;
        $('l20_formacontroleregistropreco').className = 'readonly';
        verificaTipoJulgamento();
    }
</script>
