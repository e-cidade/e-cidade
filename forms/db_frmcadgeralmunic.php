<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

include("dbforms/db_classesgenericas.php");

use App\Repositories\Configuracoes\DbConfigRepository;
use App\Repositories\Patrimonial\Protocolo\CgmSituacaoCadastralRepository;
use App\Services\Configuracoes\ApiRFPService;

$oDadosInstituicao = (new DbConfigRepository())->getByCodigo(db_getsession("DB_instit"));
$aSituacaoCadastral = CgmSituacaoCadastralRepository::toArray();
$oApiRFP = new ApiRFPService();

db_postmemory($HTTP_POST_VARS);
$aux = new cl_arquivo_auxiliar;
$cadenderMunicipio = new cl_cadendermunicipio;

?>

<link href="estilos/grid.style.css" rel="stylesheet" />
<link href="estilos.css" rel="stylesheet" />
<style type="text/css">
    .clicavel {
        cursor: pointer;
    }

    #z01_naturezajuridica {
        width: 123px;
    }

    #z01_naturezajuridicadescr {
        width: 549px;
    }

    .fieldsetinterno {
        border: 0px;
        border-top: 2px groove white;
        margin-top: 10px;
    }

    td {
        white-space: nowrap
    }

    fieldset table td:first-child {
        width: 80px;
        white-space: nowrap
    }

    #listMunicipios {
        width: 180px;
    }

    #z01_ultalt,
    #municipio,
    #z01_sexo {
        width: 118px;
    }

    #z01_identdtexp,
    #z01_dtfalecimento {
        width: 88px;
    }

    /* Padroniza cores dos links */
    a,
    a:visited {
        color: #338C63;
        font-weight: bold;
    }
</style>
<?php

$btnDisabled = "";
if ($db_opcao == 33 || $db_opcao == 3) {
    $btnDisabled = "disabled";
}

$clrotulo = new rotulocampo();
$cldb_uf  = new cl_db_uf;
$clrotulo->label('z03_tipoempresa');
$clrotulo->label('rh70_sequencial');
$clrotulo->label('rh70_descr');
$clrotulo->label('db12_uf');
$oPost = db_utils::postMemory($_POST);

if (isset($oPost->cpf) && trim($oPost->cpf) != '') {
    $lPessoaFisica = true;
}

if (isset($oPost->cnpj) && trim($oPost->cnpj) != '') {
    $lPessoaFisica = false;
}

$ov02_sequencial = "";
$ov02_seq = "";
if (isset($oPost->ov02_sequencial) && trim($oPost->ov02_sequencial) != "") {
    $ov02_sequencial = $oPost->ov02_sequencial;
}
if (isset($oPost->ov02_seq) && trim($oPost->ov02_seq) != "") {
    $ov02_seq = $oPost->ov02_seq;
}
$funcaoRetorno = "";
if (isset($oGet->funcaoRetorno) && trim($oGet->funcaoRetorno) != "") {
    $funcaoRetorno = $oGet->funcaoRetorno;
}

if ($db_opcao == 22 || $db_opcao == 2) {
    $z01_numcgm = $oCgm->z01_numcgm;
    $z01_ender = $oCgm->z01_ender;
    $z01_cxpostal = $oCgm->z01_cxpostal;
    $z01_compl = $oCgm->z01_compl;
    $z01_munic = $oCgm->z01_munic;
    $z01_uf = $oCgm->z01_uf;
    $z01_numero = $oCgm->z01_numero;
    $z01_bairro = $oCgm->z01_bairro;
    $z01_cep = $oCgm->z01_cep;
    $z01_loteamento = $oCgm->db76_loteamento;
    $z01_condominio = $oCgm->db76_condominio;
    $z01_pontoreferencia = $oCgm->db76_pontoref;
    $z01_ibge = $oCgm->z01_ibge;
    $z01_anoobito = $oCgm->z01_anoobito;
    $z01_produtorrural = $oCgm->z01_produtorrural;
    $z01_situacaocadastral = $oCgm->z01_situacaocadastral;
    if (!$lPessoaFisica) {
        $z01_situacaoespecial = $oCgm->z01_situacaoespecial;
        $z01_naturezajuridica = $oCgm->z01_naturezajuridica;
        $z01_tipoestabelecimento = $oCgm->z01_tipoestabelecimento;
        $z01_porte = $oCgm->z01_porte;
        $z01_optantesimples = $oCgm->z01_optantesimples;
        $z01_optantemei = $oCgm->z01_optantemei;
        $z01_incmunici = $oCgm->z01_incmunici;
        if ($oCgm->z01_dtabertura) {
            $z01_dtabertura = $oCgm->z01_dtabertura;
            $z01_dtabertura_dia = date("d", strtotime($z01_dtabertura));
            $z01_dtabertura_mes = date("m", strtotime($z01_dtabertura));
            $z01_dtabertura_ano = date("Y", strtotime($z01_dtabertura));
        }

        if ($oCgm->z01_datasituacaoespecial) {
            $z01_datasituacaoespecial = $oCgm->z01_datasituacaoespecial;
            $z01_datasituacaoespecial_dia = date("d", strtotime($z01_datasituacaoespecial));
            $z01_datasituacaoespecial_mes = date("m", strtotime($z01_datasituacaoespecial));
            $z01_datasituacaoespecial_ano = date("Y", strtotime($z01_datasituacaoespecial));
        }
    }
}

/**
 * Record Set utilizado para montar o select
 */
$sCampos         = "db98_sequencial as z03_tipoempresa, db98_descricao";
$sSqlTipoEmpresa = $cltipoempresa->sql_query_file(null, $sCampos, null, "");
$rsTipoEmpresa   = $cltipoempresa->sql_record($sSqlTipoEmpresa);

?>
<form action="" method="post" name="form1" id="form1">
    <input type="hidden" name="ov02_sequencial" id="ov02_sequencial" value="<?= $ov02_sequencial ?>" />
    <input type="hidden" name="ov02_seq" id="ov02_seq" value="<?= $ov02_seq ?>" />
    <table>
        <tr>
            <td rowspan="5" valign="top">
                <img src="imagens/none1.jpeg" width="95" height="120" id='fotocgm' style="border: 1px inset white" />
            </td>
            <td>
                <fieldset>
                    <legend> <strong>Dados Gerais</strong> </legend>
                    <table>
                        <!-- Pessoa Fisica z01_cgccpf = 11 -->
                        <?php
                        if ($lPessoaFisica) {
                        ?>
                            <tr>
                                <td title='<?= $Tz01_numcgm ?>' nowrap>
                                    <?= $Lz01_numcgm ?>
                                </td>
                                <td colspan="3">
                                    <?
                                    db_input('z01_numcgm', 17, $Iz01_numcgm, true, 'text', 3);
                                    ?>
                                </td>
                                <td align="left">
                                    <b>Data de Cadastro:</b>
                                </td>
                                <td align="right">
                                    <?php
                                    if ($db_opcao == 1) {
                                        $z01_cadast = date('d/m/Y', db_getsession("DB_datausu"));
                                    } else if ($db_opcao == 2 || $db_opcao == 22) {
                                        $z01_cadast = implode("/", array_reverse(explode("-", $oCgm->z01_cadast)));
                                    }

                                    $z01_ultalt = date('d/m/Y', db_getsession("DB_datausu"));
                                    db_input('z01_cadast', 13, @$Iz01_cadast, true, 'text', 3, "", '', '', '', 11);
                                    db_input('z01_ultalt', 13, @$z01_ultalt, true, 'hidden', 3, "", '', '', '', 11);
                                    ?>
                                </td>
                                <input type="hidden" value="<?= date('d/m/Y', db_getsession("DB_datausu")) ?>" />
                            </tr>
                            <tr>
                                <td title="CPF"><strong>CPF:</strong></td>
                                <td align="left" colspan="3">
                                    <?php
                                    if (isset($oPost->cpf) && strlen($oPost->cpf) == 11) {
                                        $z01_cpf = $oPost->cpf;
                                    }

                                    db_input('z01_cpf', 17, @$Iz01_cpf, true, 'text', $db_opcao, "onBlur='js_verificaCGCCPF(this);'", '', '', 'text-align:left;', 11);
                                    ?>
                                </td>
                                <td nowrap title="<?= $Tz01_nasc ?>">
                                    <?= $Lz01_nasc ?>
                                </td>
                                <td nowrap title="<?= $Tz01_nasc ?>" align="right">
                                    <?
                                    db_inputdata('z01_nasc', @$z01_nasc_dia, @$z01_nasc_mes, @$z01_nasc_ano, true, 'text', 2, '', '', ' ');
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td nowrap title=<?= @$Tz01_nome ?>>
                                    <?= @$Lz01_nome ?>
                                </td>
                                <td nowrap title="<?= @$Tz01_nome ?>" colspan="5">
                                    <?
                                    db_input('z01_nome', 115, $Iz01_nome, true, 'text', $db_opcao, 'onblur=js_copiaNome();');
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td nowrap title="">
                                    <strong>Situacao Cadastral:</strong>
                                </td>
                                <td nowrap title="<?= $Tz01_notificaemail ?>" colspan="3">
                                    <?php
                                    db_select('z01_situacaocadastral', $aSituacaoCadastral, true, $db_opcao, 'style="width:125px"');
                                    ?>
                                </td>

                                <td nowrap title="<?= $Tz01_produtorrural ?>">
                                    <b>Produtor Rural</b>
                                </td>
                                <td nowrap title="<?= $Tz01_produtorrural ?>" align="right">
                                    <?
                                    $x = array("f" => "Não", "t" => "Sim");
                                    db_select('z01_produtorrural', $x, true, $db_opcao, 'style="width:125px;"');
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td nowrap title="<?= @$Tz01_telef ?>">
                                    <?= @$Lz01_telef ?>
                                </td>
                                <td nowrap colspan="3">
                                    <?
                                    db_input('z01_telef', 17, $Iz01_telef, true, 'text', $db_opcao);
                                    ?>
                                </td>
                                <td nowrap title="<?= @$Tz01_telcel ?>">
                                    <?= @$Lz01_telcel ?>
                                </td>
                                <td nowrap align="right">
                                    <?
                                    db_input('z01_telcel', 17, $Iz01_telcel, true, 'text', $db_opcao);
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td nowrap title="<?= @$Tz01_email ?>">
                                    <b>E-mail:</b>
                                </td>
                                <td colspan="3">
                                    <?
                                    db_input('z01_email', 40, $Iz01_email, true, 'text', $db_opcao);
                                    ?>
                                </td>
                                <td nowrap title="">
                                    <?= @$Lz01_notificaemail ?>
                                </td>
                                <td nowrap title="<?= $Tz01_notificaemail ?>" align="right">
                                    <?
                                    $x = array("t" => "Sim", "f" => "Não");
                                    db_select('z01_notificaemail', $x, true, $db_opcao, 'style="width:125px"');
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="6">
                                    <fieldset class="rfieldsetinterno">
                                        <legend><strong>Endereço Primário</strong></legend>

                                        <?php
                                        db_input('idEnderPrimario', 10, '', true, 'hidden', $db_opcao);
                                        db_input('endPrimario', 52, '', true, 'hidden', 3);
                                        ?>

                                        <table>
                                            <tr>
                                                <td><a href='#' onclick='lookupCepPrimario();'><b>Pesquisa Cep:</b></a></td>
                                                <td colspan="3"><? db_input("z01_cep", 10, null, true, 'text', $db_opcao, "style='background-color:#FFF'"); ?></td>
                                            </tr>

                                            <tr>
                                                <td><b>País:</b></td>
                                                <?php
                                                $paises = db_query("SELECT db70_descricao FROM cadenderpais ORDER BY db70_descricao");
                                                $arrayPais = [0 => "SELECIONE"];
                                                for ($iCont = 0; $iCont < pg_num_rows($paises); $iCont++) {
                                                    $oPais = db_utils::fieldsMemory($paises, $iCont);
                                                    $arrayPais[$oPais->db70_descricao] = $oPais->db70_descricao;
                                                }

                                                if (!$z01_pais) {
                                                    $z01_pais = 'BRASIL';
                                                }
                                                ?>
                                                <td><? db_select("z01_pais", $arrayPais, true, $db_opcao, "style='width:193px;'"); ?></td>
                                                <td><b>Estado:</b></td>
                                                <?php
                                                $estados = db_query("SELECT db71_sigla, db71_descricao FROM cadenderestado ORDER BY db71_sigla");
                                                for ($iCont = 0; $iCont < pg_num_rows($estados); $iCont++) {
                                                    $oEstado = db_utils::fieldsMemory($estados, $iCont);
                                                    $arrayEstado[$oEstado->db71_sigla] = $oEstado->db71_descricao;
                                                }

                                                if (!$z01_uf) {
                                                    $z01_uf = 'MG';
                                                }
                                                ?>
                                                <td><? db_select("z01_uf", $arrayEstado, true, $db_opcao, "style='width:134px;'"); ?></td>
                                                <td><b>Município:</b></td>
                                                <?php
                                                $municipios = $cadenderMunicipio->getMunicipiosPorEstado($z01_uf);

                                                for ($iCont = 0; $iCont < pg_num_rows($municipios); $iCont++) {
                                                    $oMunicipio = db_utils::fieldsMemory($municipios, $iCont);
                                                    $arrayMunicipio[$oMunicipio->db72_sequencial] = $oMunicipio->db72_descricao;
                                                }

                                                if (!$z01_munic) {
                                                    $oInstituicao = new Instituicao(db_getsession("DB_instit"));
                                                    $z01_munic = $oInstituicao->getMunicipio();
                                                }

                                                $municipios = $cadenderMunicipio->getMunicipiosPorEstadoECidade($z01_uf, $z01_munic);

                                                $oMunicipio = db_utils::fieldsMemory($municipios, 0);
                                                $z01_munic = $oMunicipio->db72_sequencial;
                                                ?>
                                                <td align="right"><? db_select("z01_munic", $arrayMunicipio, true, $db_opcao, "style='width:175px;'"); ?></td>
                                            </tr>

                                            <tr>
                                                <input type="hidden" name="imunicipio" id="imunicipio" value="<?= $z01_munic ?>" />
                                                <input type="hidden" name="irua" id="irua" value="" />
                                                <input type="hidden" name="ibairro" id="ibairro" value="" />
                                                <input type="hidden" name="iestado" id="iestado" value="" />
                                                <td><b>Logradouro:</b></td>
                                                <td colspan="3"><?php db_input("z01_ender", 63, null, true, 'text', $db_opcao); ?></td>
                                                <td><b>Bairro/Distrito:</b></td>
                                                <td align="right"><?php db_input("z01_bairro", 25, null, true, 'text', $db_opcao, "", "", "white"); ?></td>
                                            </tr>

                                            <tr>
                                                <td><b>Número:</b></td>
                                                <td><? db_input("z01_numero", 28, 1, true, 'text', $db_opcao, "", "", "white"); ?></td>
                                                <td><b>Complemento:</b></td>
                                                <td><? db_input("z01_compl", 19, null, true, 'text', $db_opcao); ?></td>
                                                <td><?= @$Lz01_cxpostal ?></td>
                                                <td align="right"><? db_input("z01_cxpostal", 25, null, true, 'text', $db_opcao); ?></td>
                                            </tr>

                                            <tr>
                                                <td><b>Condominio:</b></td>
                                                <td><? db_input("z01_condominio", 28, null, true, 'text', $db_opcao, "style='background-color:#E6E4F1'"); ?></td>
                                                <td><b>Loteamento:</b></td>
                                                <td><? db_input("z01_loteamento", 19, null, true, 'text', $db_opcao, "style='background-color:#E6E4F1'"); ?></td>
                                                <td><b>Ponto de Referencia:</b></td>
                                                <td align="right"><? db_input("z01_pontoreferencia", 25, null, true, 'text', $db_opcao, "style='background-color:#E6E4F1'"); ?></td>
                                            </tr>
                                        </table>
                                        <!-- Novo Modelo de Endereço - Oc21367 -->

                                    </fieldset>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="6">
                                    <fieldset class="fieldsetinterno">
                                        <legend class="clicavel" onclick="js_mostraTabela('dados_complementares');">
                                            <strong>Dados Complementares: ></strong>
                                        </legend>
                                        <table style="display: none" id="dados_complementares">
                                            <tr>
                                                <td nowrap title=<?= @$Tz01_pai ?>>
                                                    <?= @$Lz01_pai ?>
                                                </td>
                                                <td nowrap title="<?= @$Tz01_pai ?>" colspan="5">
                                                    <?
                                                    db_input('z01_pai', 102, $Iz01_pai, true, 'text', $db_opcao);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap title=<?= @$Tz01_mae ?>>
                                                    <?= @$Lz01_mae ?>
                                                </td>
                                                <td nowrap title="<?= @$Tz01_mae ?>" colspan="5">
                                                    <?
                                                    db_input('z01_mae', 102, $Iz01_mae, true, 'text', $db_opcao, '', '', '', '');
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td title="<?= @$Tz01_ident ?>">
                                                    <?= @$Lz01_ident ?>
                                                </td>
                                                <td>
                                                    <?
                                                    db_input('z01_ident', 21, $Iz01_ident, true, 'text', $db_opcao);
                                                    ?>
                                                </td>
                                                <td>
                                                    <?= @$Lz01_identorgao ?>
                                                </td>
                                                <td align="left">
                                                    <?php
                                                    if (isset($oPost->cpf) && strlen($oPost->cpf) == 11) {
                                                        $z01_cpf = $oPost->cpf;
                                                    }

                                                    db_input('z01_identorgao', 25, @$Iz01_identorgao, true, 'text', $db_opcao);
                                                    ?>
                                                </td>
                                                <td>
                                                    <?= @$Lz01_identdtexp ?>
                                                </td>
                                                <td align="right">
                                                    <?
                                                    db_inputdata('z01_identdtexp', @$z01_identdtexp_dia, @$z01_identdtexp_mes, @$z01_identdtexp_ano, true, 'text', $db_opcao);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr style="display: none">
                                                <td nowrap title=<?= @$Tz01_naturalidade ?>>
                                                    <?= @$Lz01_naturalidade ?>
                                                </td>
                                                <td nowrap title="<?= @$Tz01_naturalidade ?>" colspan="3">
                                                    <?
                                                    db_input('z01_naturalidade', 50, $Iz01_naturalidade, true, 'text', $db_opcao);
                                                    ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td title="naturalidade">
                                                    <b>Naturalidade</b>
                                                    <b>UF:</b>
                                                </td>
                                                <td>
                                                    <?php
                                                    $ufs = db_query($cldb_uf->sql_query_file(null, "db12_uf as uf, db12_nome as ufdescr"));
                                                    $listaUf['0'] = 'SELECIONE';
                                                    while ($row = pg_fetch_object($ufs)) {
                                                        $listaUf[$row->uf] = $row->ufdescr;
                                                    }

                                                    if ($z01_ibge) {
                                                        $oDaoCadEnderMunicipio = new cl_cadendermunicipio();
                                                        $oNaturalidade = db_utils::fieldsMemory($oDaoCadEnderMunicipio->getEstadoCidadeByIBGE($z01_ibge), 0);
                                                        $ufdescr = $oNaturalidade->estado;
                                                        echo "<input type='hidden' id='codigo_cidade' value='{$oNaturalidade->codigo_cidade}' />";
                                                    }
                                                    db_select('ufdescr', $listaUf, true, $db_opcao, "onchange='js_carregarMunicipios()' style='width:150px;'");
                                                    ?>
                                                </td>
                                                <td>
                                                    <b>Cidade:</b>
                                                </td>
                                                <td>
                                                    <?php
                                                    db_select('listMunicipios', '', true, $db_opcao, "onchange='js_retornaCodigoIbge()' style='width:176px;'");
                                                    ?>
                                                </td>
                                                <td>
                                                    <b>Código IBGE:</b>
                                                </td>
                                                <td align="right">
                                                    <?
                                                    db_input('z01_ibge', 15, '', true, 'text', 3);
                                                    ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td nowrap title="<?= $Tz01_estciv ?>">
                                                    <?= $Lz01_estciv ?>
                                                </td>
                                                <td nowrap title="<?= $Tz01_estciv ?>">
                                                    <?
                                                    $x = array(
                                                        "0" => "Selecione",
                                                        "1" => "Solteiro",
                                                        "2" => "Casado",
                                                        "3" => "Viúvo",
                                                        "4" => "Divorciado",
                                                        "5" => "Separado Consensual",
                                                        "6" => "Separado Judicial",
                                                        "7" => "União Estavel"
                                                    );

                                                    db_select('z01_estciv', $x, true, $db_opcao, 'style="width:150;"');
                                                    ?>
                                                </td>

                                                <td nowrap title="<?= $Tz01_nacion ?>">
                                                    <?= $Lz01_nacion ?>
                                                </td>
                                                <td nowrap title="<?= $Tz01_nacion ?>">
                                                    <?
                                                    $x = array("1" => "Brasileira", "2" => "Estrangeira");
                                                    db_select('z01_nacion', $x, true, $db_opcao, 'style="width:176px;"');
                                                    ?>
                                                </td>

                                                <td nowrap title="<?= $Tz01_sexo ?>">
                                                    <?= $Lz01_sexo ?>
                                                </td>
                                                <td nowrap title="<?= $Tz01_sexo ?>" align="right">
                                                    <?
                                                    $sex = array("0" => "Selecione", "M" => "Masculino", "F" => "Feminino");
                                                    db_select('z01_sexo', $sex, true, $db_opcao, 'style="width:110px;"');
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>

                                                <td nowrap title=<?= @$Tz01_escolaridade ?>>
                                                    <?= @$Lz01_escolaridade ?>
                                                </td>
                                                <td nowrap title="<?= @$Tz01_escolaridade ?>">
                                                    <?
                                                    $aEscolaridade = array(
                                                        '0' => 'SELECIONE',
                                                        '1' => 'ANALFABETO',
                                                        '2' => 'FUNDAMENTAL INCOMPLETO',
                                                        '3' => 'FUNDAMENTAL COMPLETO',
                                                        '4' => 'ENSINO MÉDIO INCOMPLETO',
                                                        '5' => 'ENSINO MÉDIO COMPLETO',
                                                        '6' => 'ENSINO SUPERIOR INCOMPLETO',
                                                        '7' => 'ENSINO SUPERIOR COMPLETO',
                                                        '8' => 'MESTRADO',
                                                        '9' => 'DOUTORADO'
                                                    );
                                                    db_select('z01_escolaridade', $aEscolaridade, true, 1, 'style="width:150;"');
                                                    ?>
                                                </td>

                                                <td nowrap title="<?= $Tz01_anoobito ?>">
                                                    <b>Ano do Óbito:</b>
                                                </td>
                                                <td nowrap title="<?= $Tz01_anoobito ?>">
                                                    <?
                                                    db_input('z01_anoobito', 25, @$Iz01_anoobito, true, 'text', $db_opcao, 'style="background-color:#E6E4F1"', '', '', 'text-align:left;', 4);
                                                    ?>
                                                </td>
                                                <td nowrap title="<?= $Tz01_dtfalecimento ?>">
                                                    <?= $Lz01_dtfalecimento ?>
                                                </td>
                                                <td nowrap title="<?= $Tz01_dtfalecimento ?>" align="right">
                                                    <?
                                                    db_inputdata('z01_dtfalecimento', @$z01_dtfalecimento_dia, @$z01_dtfalecimento_mes, @$z01_dtfalecimento_ano, true, 'text', $db_opcao);
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="6">
                                    <fieldset class="fieldsetinterno">
                                        <legend class="clicavel" onclick="js_mostraTabela('tabela_dados_emprego')">
                                            <strong>Dados do Emprego: ></strong>
                                        </legend>
                                        <table style="display:none" id="tabela_dados_emprego">
                                            <tr>
                                                <td nowrap title=<?= @$Tz01_profis ?>>
                                                    <?= @$Lz01_profis ?>
                                                </td>
                                                <td nowrap title="<?= @$Tz01_profis ?>" colspan="5">
                                                    <?
                                                    db_input('z01_profis', 63, $Iz01_profis, true, 'text', $db_opcao);
                                                    ?>
                                                </td>

                                                <td nowrap title=<?= @$Tz01_pis ?>>
                                                    <?= @$Lz01_pis ?>
                                                </td>
                                                <td nowrap title="<?= @$Tz01_pis ?>" align="right">
                                                    <?
                                                    db_input('z01_pis', 18, $Iz01_pis, true, 'text', $db_opcao, "onblur = js_validaPis(this.value);");
                                                    ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td nowrap title="<?= $Tz01_trabalha ?>">
                                                    <?= $Lz01_trabalha ?>
                                                </td>
                                                <td nowrap title="<?= $Tz01_trabalha ?>" colspan="3">
                                                    <?
                                                    $x = array("t" => "Sim", "f" => "Não");
                                                    db_select('z01_trabalha', $x, true, $db_opcao, 'style="width:123px;text-align:left;"');
                                                    ?>
                                                </td>
                                                <td nowrap title=<?= @$Tz01_localtrabalho ?>>
                                                    <?= @$Lz01_localtrabalho ?>
                                                </td>
                                                <td nowrap align="right" title="<?= @$Tz01_localtrabalho ?>">
                                                    <?
                                                    db_input('z01_localtrabalho', 21, $Iz01_localtrabalho, true, 'text', $db_opcao);
                                                    ?>
                                                </td>
                                                <td nowrap title="<?= $Lz01_renda ?>">
                                                    <?= $Lz01_renda ?>
                                                </td>
                                                <td nowrap title="Renda" align="right">
                                                    <?
                                                    db_input('z01_renda', 18, $Iz01_renda, true, 'text', $db_opcao);
                                                    ?>
                                                </td>
                                            </tr>



                                            <tr>
                                                <td nowrap title="<?= @$Tz01_telcon ?>">
                                                    <?= @$Lz01_telcon ?>
                                                </td>
                                                <td nowrap colspan="3">
                                                    <?
                                                    db_input('z01_telcon', 17, $Iz01_telcon, true, 'text', $db_opcao);
                                                    ?>
                                                </td>
                                                <td nowrap title="<?= @$Tz01_celcon ?>">
                                                    <?= @$Lz01_celcon ?>
                                                </td>
                                                <td nowrap align="right">
                                                    <?
                                                    db_input('z01_celcon', 21, $Iz01_celcon, true, 'text', $db_opcao);
                                                    ?>
                                                </td>

                                                <td nowrap title="<?= @$Tz01_emailc ?>">
                                                    <?= @$Lz01_emailc ?>
                                                </td>
                                                <td nowrap>
                                                    <?
                                                    db_input('z01_emailc', 18, $Iz01_emailc, true, 'text', $db_opcao);
                                                    ?>
                                                </td>
                                            </tr>

                                            <tr title="<?= @$Trh70_descr ?>">
                                                <td align="left" nowrap>
                                                    <strong>
                                                        <?
                                                        db_ancora("CBO", "js_pesquisaCbo(true);", $db_opcao);
                                                        ?>
                                                    </strong>
                                                </td>
                                                <td colspan="7" align="left">
                                                    <?
                                                    db_input("rh70_sequencial",  17, "", true, "text", $db_opcao, "onchange='js_pesquisaCbo(false);'");
                                                    db_input("rh70_descr",  80, "",  true, "text", 3, "");
                                                    ?>
                                                </td>
                                            </tr>

                                        </table>
                                    </fieldset>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="6">
                                    <fieldset class="fieldsetinterno">
                                        <legend class="clicavel" onclick="js_mostraTabela('endereco_secundario_pessoa_fisica')">
                                            <strong>Endereço Secundário: ></strong>
                                        </legend>
                                        <table style="display: none" id="endereco_secundario_pessoa_fisica">
                                            <tr>
                                                <td><b>Caixa Postal:</b></td>
                                                <td><? db_input("z01_cxposcon", 15, null, true, 'text', $db_opcao); ?></td>
                                                <td>
                                                    <?php
                                                    db_input('idEnderSecundario', 11, '', true, 'hidden', $db_opcao);
                                                    db_input('endSecundario', 70, '', true, 'text', 3);
                                                    ?>
                                                </td>
                                                <td>
                                                    <input type="button" value="Lançar" id="btnLancarEndSecundario" onclick="js_lancaEnderSecundario();" <?= $btnDisabled ?> />
                                                    <input type="button" value="Excluir" id="btnExcluirEndSecundario" onclick="js_ExcluiEnderSecundario();" <?= $btnDisabled ?> />
                                                </td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    <fieldset>
                                        <legend><strong>Observações</strong></legend>
                                        <?
                                        if ($db_opcao == 1) {
                                            db_textarea('z01_obs', 1, 115, null, true, '', $db_opcao, '', '', "#E6E4F1", '100');
                                        } else {
                                            db_textarea('z01_obs', 1, 115, null, true, '', $db_opcao, '', '', '', '100');
                                        }
                                        ?>
                                    </fieldset>
                                </td>
                            </tr>

                        <?php

                        } else {

                        ?>

                            <!-- ******************************** Fim de pessoa Fisica ***************************************************** -->
                            <!-- Inicio pessoa Jurídica -->
                            <tr>
                                <td title='<?= $Tz01_numcgm ?>' nowrap>
                                    <?= $Lz01_numcgm ?>
                                </td>
                                <td colspan="4">
                                    <?
                                    db_input('z01_numcgm', 17, $Iz01_numcgm, true, 'text', 3);
                                    ?>
                                </td>
                                <td align="right">
                                    <b>Data de Cadastro:</b>
                                    <?php
                                    if ($db_opcao == 1) {
                                        $z01_cadast = date('d/m/Y', db_getsession("DB_datausu"));
                                    } else if ($db_opcao == 2 || $db_opcao == 22) {
                                        $z01_cadast = implode("/", array_reverse(explode("-", $oCgm->z01_cadast)));
                                    }

                                    $z01_ultalt = date('d/m/Y', db_getsession("DB_datausu"));
                                    db_input('z01_cadast', 13, @$Iz01_cadast, true, 'text', 3, "", '', '', '', 11);
                                    db_input('z01_ultalt', 13, @$z01_ultalt, true, 'hidden', 3, "", '', '', '', 11);

                                    ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </td>
                                <input type="hidden" value="<?= date('d/m/Y', db_getsession("DB_datausu")) ?>" />
                            </tr>

                            <tr>
                                <td nowrap title="<?= @$Tz01_cgc ?>"><strong>CNPJ:</strong></td>
                                <td>
                                    <?

                                    if (isset($oPost->cnpj) && strlen($oPost->cnpj) == 14) {
                                        $z01_cgc = $oPost->cnpj;
                                    }

                                    db_input('z01_cgc', 17, @$Iz01_cgc, true, 'text', $db_opcao, "onBlur='js_verificaCGCCPF(this);js_testanome(\"\",\"\",this.value)'", '', '', 'text-align:left;');
                                    ?>

                                    <input type="button" id="consultar-rfb-cpf" value="Consultar na RFB" onclick="js_consultarrfb(<?= $oDadosInstituicao->db21_apirfb ?>, '<?= $oApiRFP->getURL() ?>', <?= $oApiRFP->getCliente() ?>)" />
                                </td>

                            </tr>

                            <tr>
                                <td nowrap title=<?= @$Tz01_nome ?>>
                                    <?= @$Lz01_nome ?>
                                </td>
                                <td nowrap title="<?= @$Tz01_nome ?>" colspan="5">
                                    <?
                                    db_input('z01_nome', 103, $Iz01_nome, true, 'text', $db_opcao, 'onBlur="js_ToUperCampos(\'z01_nome\');js_copiaNome();" onkeyup="";');
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap title=<?= @$Tz01_nomecomple ?>>
                                    <?= @$Lz01_nomecomple ?>
                                </td>
                                <td nowrap title="<?= @$Tz01_nomecomple ?>" colspan=5>
                                    <?
                                    db_input('z01_nomecomple', 103, $Iz01_nomecomple, true, 'text', $db_opcao, "onkeyup=''; onblur='js_ToUperCampos(\"z01_nomecomple\");'");
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap title=<?= @$Tz01_nomefanta ?>>
                                    <?= @$Lz01_nomefanta ?>
                                </td>
                                <td nowrap title="<?= @$Tz01_nomefanta ?>" colspan="5">
                                    <?
                                    db_input('z01_nomefanta', 103, $Iz01_nomefanta, true, 'text', $db_opcao, "onkeyup=''; onblur='js_ToUperCampos(\"z01_nomefanta\");'");
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <strong>Natureza Juridica:</strong>
                                </td>
                                <td colspan="5">
                                    <?php
                                    $rsNaturezajuridica = db_query("select n1_codigo,n1_descricao from naturezajurifica where n1_codigo not in ('0000')");
                                    db_selectrecord('z01_naturezajuridica', $rsNaturezajuridica, true, $db_opcao, '', '', '', true, '', 2);
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td nowrap title="">
                                    <?= @$Lz01_notificaemail ?>
                                </td>
                                <td nowrap title="<?= $Tz01_notificaemail ?>">
                                    <?
                                    $x = array("t" => "Sim", "f" => "Não");
                                    db_select('z01_notificaemail', $x, true, $db_opcao, 'style="width:125px"');
                                    ?>
                                </td>
                                <td nowrap title="<?= @$Tz01_email ?>">
                                    <?= @$Lz01_email ?>
                                </td>
                                <td colspan="3">
                                    <?
                                    db_input('z01_email', 58, $Iz01_email, true, 'text', $db_opcao);
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td nowrap title="<?= @$Tz01_telef ?>">
                                    <?= @$Lz01_telef ?>
                                </td>
                                <td nowrap>
                                    <?
                                    db_input('z01_telef', 17, $Iz01_telef, true, 'text', $db_opcao);
                                    ?>
                                </td>
                                <td nowrap title="<?= @$Tz01_telcel ?>">
                                    <?= @$Lz01_telcel ?>
                                </td>
                                <td nowrap>
                                    <?
                                    db_input('z01_telcel', 15, $Iz01_telcel, true, 'text', $db_opcao);
                                    ?>
                                </td>

                                <td nowrap align="right" title=<?= @$Tz01_contato ?>>
                                    <?= @$Lz01_contato ?>
                                </td>
                                <td nowrap title="<?= @$Tz01_contato ?>">
                                    <?
                                    db_input('z01_contato', 31, $Iz01_contato, true, 'text', $db_opcao, "");
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="6">
                                    <fieldset class="rfieldsetinterno">
                                        <legend><strong>Endereço Primário</strong></legend>

                                        <?php
                                        db_input('idEnderPrimario', 10, '', true, 'hidden', $db_opcao);
                                        db_input('endPrimario', 52, '', true, 'hidden', 3);
                                        ?>

                                        <table>
                                            <tr>
                                                <td><a href='#' onclick='lookupCepPrimario();'><b>Pesquisa Cep:</b></a></td>
                                                <td colspan="3"><? db_input("z01_cep", 10, null, true, 'text', $db_opcao, "style='background-color:#FFF'"); ?></td>
                                            </tr>

                                            <tr>
                                                <td><b>País:</b></td>
                                                <?php
                                                $paises = db_query("SELECT db70_descricao FROM cadenderpais ORDER BY db70_descricao");
                                                $arrayPais = [0 => "SELECIONE"];
                                                for ($iCont = 0; $iCont < pg_num_rows($paises); $iCont++) {
                                                    $oPais = db_utils::fieldsMemory($paises, $iCont);
                                                    $arrayPais[$oPais->db70_descricao] = $oPais->db70_descricao;
                                                }

                                                if (!$z01_pais) {
                                                    $z01_pais = 'BRASIL';
                                                }
                                                ?>
                                                <td><? db_select("z01_pais", $arrayPais, true, $db_opcao, "style='width:150px;'"); ?></td>
                                                <td><b>Estado:</b></td>
                                                <?php
                                                $estados = db_query("SELECT db71_sigla, db71_descricao FROM cadenderestado ORDER BY db71_sigla");
                                                for ($iCont = 0; $iCont < pg_num_rows($estados); $iCont++) {
                                                    $oEstado = db_utils::fieldsMemory($estados, $iCont);
                                                    $arrayEstado[$oEstado->db71_sigla] = $oEstado->db71_descricao;
                                                }

                                                if (!$z01_uf) {
                                                    $z01_uf = 'MG';
                                                }
                                                ?>
                                                <td><? db_select("z01_uf", $arrayEstado, true, $db_opcao, "style='width:150px;'"); ?></td>
                                                <td><b>Município:</b></td>
                                                <?php
                                                $municipios = $cadenderMunicipio->getMunicipiosPorEstado($z01_uf);

                                                for ($iCont = 0; $iCont < pg_num_rows($municipios); $iCont++) {
                                                    $oMunicipio = db_utils::fieldsMemory($municipios, $iCont);
                                                    $arrayMunicipio[$oMunicipio->db72_sequencial] = $oMunicipio->db72_descricao;
                                                }

                                                if (!$z01_munic) {
                                                    $oInstituicao = new Instituicao(db_getsession("DB_instit"));
                                                    $z01_munic = $oInstituicao->getMunicipio();
                                                }

                                                $municipios = $cadenderMunicipio->getMunicipiosPorEstadoECidade($z01_uf, $z01_munic);

                                                $oMunicipio = db_utils::fieldsMemory($municipios, 0);
                                                $z01_munic = $oMunicipio->db72_sequencial;
                                                ?>
                                                <td align="right"><? db_select("z01_munic", $arrayMunicipio, true, $db_opcao, "style='width:175px;'"); ?></td>
                                            </tr>

                                            <tr>
                                                <input type="hidden" name="imunicipio" id="imunicipio" value="<?= $z01_munic ?>" />
                                                <input type="hidden" name="irua" id="irua" value="" />
                                                <input type="hidden" name="ibairro" id="ibairro" value="" />
                                                <input type="hidden" name="iestado" id="iestado" value="" />
                                                <td><b>Logradouro:</b></td>
                                                <td colspan="3"><?php db_input("z01_ender", 59, null, true, 'text', $db_opcao); ?></td>
                                                <td><b>Bairro/Distrito:</b></td>
                                                <td align="right"><?php db_input("z01_bairro", 25, null, true, 'text', $db_opcao, "", "", "white"); ?></td>
                                            </tr>

                                            <tr>
                                                <td><b>Número:</b></td>
                                                <td><? db_input("z01_numero", 21, 1, true, 'text', $db_opcao, "", "", "white"); ?>
                                                </td>
                                                <td><b>Complemento:</b></td>
                                                <td><? db_input("z01_compl", 21, null, true, 'text', $db_opcao); ?></td>
                                                <td><?= @$Lz01_cxpostal ?></td>
                                                <td align="right"><? db_input("z01_cxpostal", 25, null, true, 'text', $db_opcao); ?></td>
                                            </tr>


                                            <tr>
                                                <td><b>Condominio:</b></td>
                                                <td><? db_input("z01_condominio", 21, null, true, 'text', $db_opcao, "style='background-color:#E6E4F1'"); ?></td>
                                                <td><b>Loteamento:</b></td>
                                                <td><? db_input("z01_loteamento", 21, null, true, 'text', $db_opcao, "style='background-color:#E6E4F1'"); ?></td>
                                                <td><b>Ponto de Referencia:</b></td>
                                                <td align="right">
                                                    <? db_input("z01_pontoreferencia", 25, null, true, 'text', $db_opcao, "style='background-color:#E6E4F1'"); ?>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- Novo Modelo de Endereço - Oc21367 -->
                                    </fieldset>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="6">
                                    <fieldset class="fieldsetinterno">
                                        <legend class="clicavel" onclick="js_mostraTabela('dados_complementares')">
                                            <strong>Dados Complementares: ></strong>
                                        </legend>
                                        <table style="display: none" id="dados_complementares">

                                            <tr>
                                                <td nowrap title="">
                                                    <strong>Tipo Estabelecimento:</strong>
                                                </td>
                                                <td nowrap title="<?= $Tz01_notificaemail ?>">
                                                    <?
                                                    $x = array("0" => "Selecione", "1" => "Matriz", 2 => "Filial");
                                                    db_select('z01_tipoestabelecimento', $x, true, $db_opcao, 'style="width:160px"');
                                                    ?>
                                                </td>
                                                <td nowrap title="">
                                                    <strong>Porte:</strong>
                                                </td>
                                                <td nowrap title="<?= $Tz01_notificaemail ?>" colspan="3">
                                                    <?
                                                    $x = array(
                                                        "0" => "Selecione",
                                                        "1" => "Microempresa - ME",
                                                        "3" => "Empresa de pequeno porte - EPP",
                                                        "5" => "Demais empresas"
                                                    );
                                                    db_select('z01_porte', $x, true, $db_opcao, 'style="width:160px"');
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap title="">
                                                    <strong>Optante pelo Simples:</strong>
                                                </td>
                                                <td nowrap title="<?= $Tz01_notificaemail ?>">
                                                    <?
                                                    $x = array("0" => "Selecione", "1" => "Sim", "2" => "Não");
                                                    db_select('z01_optantesimples', $x, true, $db_opcao, 'style="width:160px"');
                                                    ?>
                                                </td>
                                                <td nowrap title="">
                                                    <strong>Optante pelo MEI:</strong>
                                                </td>
                                                <td nowrap title="<?= $Tz01_notificaemail ?>" colspan="3">
                                                    <?
                                                    $x = array("0" => "Selecione", "1" => "Sim", "2" => "Não");
                                                    db_select('z01_optantemei', $x, true, $db_opcao, 'style="width:160px"');
                                                    ?>
                                                </td>
                                            </tr>


                                            <tr>
                                                <td><strong>Capital Social:</strong></td>
                                                <td>
                                                    <?php
                                                    db_input('z08_capitalsocial', 23, '', true, 'text', $db_opcao, '', '', "#E6E4F1");
                                                    ?>
                                                </td>

                                                <td><strong>Nire:</strong></td>
                                                <td colspan="3">
                                                    <?php
                                                    db_input('z08_nire', 23, $z01_incmunici, true, 'text', $db_opcao, '', '', "#E6E4F1");
                                                    ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td nowrap title="<?= @$Tz01_incest ?>">
                                                    <?= @$Lz01_incest ?>
                                                </td>
                                                <td nowrap>
                                                    <?
                                                    db_input('z01_incest', 23, $Iz01_incest, true, 'text', $db_opcao);
                                                    ?>
                                                </td>

                                                <td><b>Inscrição Municipal:</b></td>
                                                <td>
                                                    <?
                                                    db_input('z01_incmunici', 23, $z01_incmunici, true, 'text', $db_opcao);
                                                    ?>
                                                </td>
                                            </tr>



                                            <tr>
                                                <td nowrap title="<?= $Tz01_situacaocadastral ?>">
                                                    <b>Situação Cadastral:</b>
                                                </td>
                                                <td nowrap title="<?= $Tz01_situacaocadastral ?>">
                                                    <?php
                                                    db_select('z01_situacaocadastral', $aSituacaoCadastral, true, $db_opcao, 'style="width:160px;"');
                                                    ?>
                                                </td>

                                                <td nowrap title="<?= $Tz01_nasc ?>">
                                                    <strong>Data da Abertura:</strong>
                                                </td>
                                                <td nowrap title="<?= $Tz01_nasc ?>" colspan="3">
                                                    <?
                                                    db_inputdata('z01_dtabertura', @$z01_dtabertura_dia, @$z01_dtabertura_mes, @$z01_dtabertura_ano, true, 'text', 2, '', '', ' ');
                                                    ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td nowrap title="">
                                                    <strong>Situação Especial:</strong>
                                                </td>
                                                <td nowrap title="<?= $Tz01_notificaemail ?>">
                                                    <?php
                                                    $x = array(
                                                        "0" => "Não",
                                                        "1" => "Início de Concordata",
                                                        "2" => "Término de Concordata",
                                                        "3" => "Em Liquidação",
                                                        "4" => "Em Liquidação Extra-Judicial",
                                                        "5" => "Falido",
                                                        "6" => "Intervenção",
                                                        "7" => "Financeiro e de Capitais",
                                                        "8" => "Liquidação Judicial",
                                                        "9" => "Liquidação Extra-Judicial",
                                                        "10" => "Recuperação Judicial",
                                                    );
                                                    db_select('z01_situacaoespecial', $x, true, $db_opcao, 'style="width:160px"');
                                                    ?>
                                                </td>

                                                <td nowrap title="<?= $Tz01_nasc ?>">
                                                    <strong>Data da Situação Especial:</strong>
                                                </td>
                                                <td nowrap title="<?= $Tz01_nasc ?>">
                                                    <?
                                                    db_inputdata('z01_datasituacaoespecial', @$z01_datasituacaoespecial_dia, @$z01_datasituacaoespecial_mes, @$z01_datasituacaoespecial_ano, true, 'text', 2, '', '', ' ');
                                                    ?>
                                                </td>
                                            </tr>

                                        </table>
                                    </fieldset>
                                </td>
                            </tr>

                            <td colspan="6">
                                <fieldset class="fieldsetinterno">
                                    <legend class="clicavel" onclick="js_mostraTabela('atividades')">
                                        <strong>Atividades: ></strong>
                                    </legend>
                                    <table style="display: none" id="atividades">
                                        <tr>
                                            <td nowrap title="<?= @$Tz01_email ?>">
                                                <a href='#' onclick='lookupCnaePrimario();'><b>CNAE Primário:</b></a>
                                            </td>
                                            <td colspan="3">
                                                <?

                                                $cnae = db_query("SELECT z16_cnae, q71_descr FROM cgmcnae INNER JOIN cnae ON q71_sequencial = z16_cnae WHERE z16_numcgm = '{$z01_numcgm}' AND z16_tipo = 'p'");
                                                while ($row = pg_fetch_object($cnae)) {
                                                    $z01_cnae = $row->z16_cnae;
                                                    $z01_cnaedescr = $row->q71_descr;
                                                }

                                                db_input('z01_cnae', 10, $z01_cnae, true, 'text', $db_opcao);
                                                db_input('z01_cnaedescr', 87, $z01_cnaedescr, true, 'text', $db_opcao);
                                                ?>
                                            </td>
                                        </tr>

                                        <tr id="ancoramedidasadotadas">
                                            <td nowrap>
                                                <?

                                                // $aux = new cl_arquivo_auxiliar;

                                                $aux->cabecalho      = "<strong>CNAE Secundário:</strong>";

                                                $aux->codigo         = "q71_sequencial";  //chave de retorno da func
                                                $aux->descr          = "q71_descr";   //chave de retorno
                                                $aux->nomeobjeto     = 'cnaes';
                                                $aux->funcao_js      = 'lookupCnaeSecundario'; //função javascript que será utilizada quando clicar na âncora
                                                $aux->funcao_js_hide = 'lookupCnaeSecundario1'; //função javascript que será utilizada quando colocar um código e sair do campo
                                                $aux->sql_exec       = "";
                                                $aux->func_arquivo   = "func_cnae.php";  //func a executar
                                                $aux->nomeiframe     = "db_iframe_medidasadotadas";
                                                $aux->localjan       = "";
                                                $aux->onclick        = "";
                                                $aux->db_opcao       = 4;
                                                $aux->tipo           = 2;
                                                $aux->top            = 0;
                                                $aux->linhas         = 10;
                                                $aux->vwhidth        = 400;
                                                $aux->funcao_gera_formulario();

                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>

                            <tr>
                                <td colspan="6">
                                    <fieldset class="fieldsetinterno">
                                        <legend class="clicavel" onclick="js_mostraTabela('endereco_secundario_pessoa_fisica')">
                                            <strong>Endereço Secundário: ></strong>
                                        </legend>
                                        <table style="display: none" id="endereco_secundario_pessoa_fisica">
                                            <tr>
                                                <td><b>Caixa Postal:</b></td>
                                                <td><? db_input("z01_cxposcon", 15, null, true, 'text', $db_opcao); ?></td>
                                                <td>
                                                    <?php
                                                    db_input('idEnderSecundario', 11, '', true, 'hidden', $db_opcao);
                                                    db_input('endSecundario', 70, '', true, 'text', 3);
                                                    ?>
                                                </td>
                                                <td>
                                                    <input type="button" value="Lançar" id="btnLancarEndSecundario" onclick="js_lancaEnderSecundario();" <?= $btnDisabled ?> />
                                                    <input type="button" value="Excluir" id="btnExcluirEndSecundario" onclick="js_ExcluiEnderSecundario();" <?= $btnDisabled ?> />
                                                </td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="6">
                                    <fieldset>
                                        <legend><strong>Observações</strong></legend>
                                        <?
                                        if ($db_opcao == 1) {
                                            db_textarea('z01_obs', 1, 115, null, true, '', $db_opcao, '', '', "#E6E4F1", '100');
                                        } else {
                                            db_textarea('z01_obs', 1, 115, null, true, '', $db_opcao, '', '', '', '100');
                                        }
                                        ?>
                                    </fieldset>
                                </td>
                            </tr>

                            <!-- Fim pessoa Jurídica -->
                        <?php
                        }
                        ?>
                    </table>
                </fieldset>
            </td>
        </tr>
        <tr align="center">
            <td>
                <input name="btnSubmit" type="button" id="btnSubmit" value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?> onclick="js_validarCGCCPF(this.value)">
                <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
                <input name="novo" type="button" id="novo" value="Novo" onclick="window.location='prot1_cadgeralmunic004.php';">
                <?
                $lPermissaoMenu = db_permissaomenu(db_getsession("DB_anousu"), 604, 7901);
                if ($db_opcao == 2 && $lPermissaoMenu == true) {
                ?>
                    <input name="btnVincular" type="button" id="btnVincular" value="Vincular Cidadao ao CGM" onclick="js_vinculaCadastroCidadaoCGM();" style="display: none;" />
                    <input name="btnImportar" type="button" id="btnImportar" value="Importar dados do Cidadão" style="display: none;">
                    <input type="button" id="btnCadastrarFornecedor" value="Cadastrar como Fornecedor" onclick="js_cadastrarFornecedor();" />
                <?
                }
                ?>
            </td>
        </tr>
    </table>
</form>

<script type="text/javascript" charset="UTF-8">
    // Busca variaveis necessárias para o funcionamento da página
    var lPessoaFisica = "<?= $lPessoaFisica ?>";
    var iCep = "<?= $z01_cep ?>";
    var sBairro = "<?= $z01_bairro ?>";

    if (!lPessoaFisica) {
        function ajustarSelecao() {
            document.getElementById("cnaes").style.width = "100%";
            document.getElementById("q71_descr").style.width = "480px";
        }
        ajustarSelecao();

    }


    if (lPessoaFisica) {
        document.getElementById('z01_nasc').focus();
    } else {
        document.getElementById('z01_nome').focus();
    }

    // Inicia o endereço, buscando dados não salvos no banco
    js_pesquisaCepInicial(iCep, sBairro);

    // ======================================== ANCORA DA CBO =========================
    function js_pesquisaCbo(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_Cbo', 'func_rhcbo.php?funcao_js=parent.js_mostraCbo|rh70_sequencial|rh70_descr|rh70_estrutural', 'Pesquisa', true);
            return;
        }
        js_OpenJanelaIframe('', 'db_iframe_Cbo', 'func_rhcbo.php?lCadastroCgm=true&pesquisa_chave=' + document.form1.rh70_sequencial.value + '&funcao_js=parent.js_mostraCboHide', 'Pesquisa', false);
        return;
    }

    function js_mostraCboHide(chave, chave2, chave3, erro) {
        if (chave2 != false) {
            if (erro == true) {
                document.form1.rh70_sequencial.value = '';
                document.form1.rh70_sequencial.focus();
            }
            document.form1.rh70_descr.value = chave3 + ' - ' + chave2;
            return;
        }
        document.form1.rh70_sequencial.value = '';
        document.form1.rh70_descr.value = '';
    }

    function js_mostraCbo(chave1, chave2, chave3) {
        document.form1.rh70_sequencial.value = chave1;
        document.form1.rh70_descr.value = chave3 + ' - ' + chave2;
        db_iframe_Cbo.hide();
    }

    parent.document.getElementById('cgm').style.display = '';
    parent.document.getElementById('documentos').style.display = '';
    parent.document.getElementById('fotos').style.display = '';
    parent.document.getElementById('contasbanco').style.display = '';

    var j14_codigo = "";
    var j13_codi = "";
    var funcaoRetorno = "<?= $funcaoRetorno ?>";

    function js_ToUperCampos(campos) {
        var sCampo = $F(campos).toUpperCase();
        $(campos).value = sCampo;
    }

    /** CONDICIONAR EM JUNCAO */
    if (lPessoaFisica) {
        $('z01_naturalidade').observe('keyup', function() {
            $('z01_naturalidade').style.textTransform = 'uppercase';
        });
    } else {
        $('z01_nome').observe('keyup', function() {
            $('z01_nome').style.textTransform = 'uppercase';
        });

        $('z01_nomecomple').observe('keyup', function() {
            $('z01_nomecomple').style.textTransform = 'uppercase';
        });

        $('z01_nomefanta').observe('keyup', function() {
            $('z01_nomefanta').style.textTransform = 'uppercase';
        });

        $('z08_nire').setAttribute("maxlength", "11");
    }

    /*-----------------------------------Trata do endereço secundário-------------------------------- */
    function js_lancaEnderSecundario() {
        j13_codi = '';
        j14_codigo = '';
        // Tem que validar se o cgm é do municipio ai tem que selecionar a rua e o bairro
        // Chamar a cgm ruas e depois o bairro do cadastro imobiliário
        js_abreEnderSecundario();
    }

    function js_abreEnderSecundario() {
        var idEnderSecundario = '';
        if ($F('idEnderSecundario') != "") {
            idEnderSecundario = $F('idEnderSecundario');
        }

        oEnderSecundario = new DBViewCadastroEndereco('sec', 'oEnderSecundario', idEnderSecundario);
        oEnderSecundario.setObjetoRetorno($('idEnderSecundario'));

        oEnderSecundario.setTipoValidacao(2);
        oEnderSecundario.setCallBackFunction(function() {
            js_lancaEnderSecundarioCallBack()
        });
        oEnderSecundario.setCodigoBairroMunicipio(j13_codi);
        oEnderSecundario.setCodigoRuaMunicipio(j14_codigo);

        oEnderSecundario.show();
    }

    /* Função disparada no retorno do fechamneto da janela de endereço */
    function js_lancaEnderSecundarioCallBack() {
        var oEndereco = new Object();
        oEndereco.exec = 'findEnderecoByCodigo';
        oEndereco.iCodigoEndereco = $F('idEnderSecundario');

        js_AjaxCgm(oEndereco, js_retornoEnderSecundario);
    }

    function js_retornoEnderSecundario(oAjax) {
        js_removeObj('msgBox');

        var oRetorno = eval('(' + oAjax.responseText + ')');
        var sExpReg = new RegExp('\\\\n', 'g');

        if (oRetorno.endereco == false) {
            var strMessageUsuario = "Falha ao ler o endereço cadastrado! ";
            js_messageBox(strMessageUsuario, '');
            return false;
        }

        oRetorno.endereco[0].stipo = 'S';
        js_PreencheEndereco(oRetorno.endereco);
    }
    /*-----------------------------------Fim do endereço secundário ----------------------------------- */

    function js_pesquisaTipoEmpresa(mostra) {
        if (mostra == true) {
            var funcao = 'parent.js_mostraTipoEmpresa1|db98_sequencial|db98_descricao';
            js_OpenJanelaIframe('', 'db_iframe_tipoempresa',
                'func_tipoempresa.php?funcao_js=' + funcao,
                'Pesquisa', true, 0, 0);
        } else {
            if ($F('z03_tipoempresa') != '') {

                var pesquisaChave = $F('z03_tipoempresa');
                var funcao = 'parent.js_mostraTipoEmpresa';
                js_OpenJanelaIframe('',
                    'db_iframe_tipoempresa',
                    'func_tipoempresa.php?pesquisa_chave=' + pesquisaChave + '&funcao_js=' + funcao,
                    'Pesquisa', false, 0, 0);
            } else {
                $('db98_descricao').value = '';
            }
        }
    }

    function js_mostraTipoEmpresa(chave, erro) {
        $('db98_descricao').value = chave;
        if (erro == true) {
            $('z03_tipoempresa').focus();
            $('z03_tipoempresa').value = '';
        }
    }

    function js_mostraTipoEmpresa1(chave1, chave2) {
        $('z03_tipoempresa').value = chave1;
        $('db98_descricao').value = chave2;
        db_iframe_tipoempresa.hide();
    }

    function js_pesquisa() {
        js_OpenJanelaIframe('',
            'func_nome',
            'func_nome.php?funcao_js=parent.js_preenchepesquisa|0&ifrname=func_nome',
            'Pesquisa', true, '0', '1');
    }

    function js_preenchepesquisa(chave) {
        func_nome.hide();
        <?
        if ($db_opcao != 1) {
            echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
        }
        ?>
    }

    function js_findCidadao(chavePesquisa) {
        if (chavePesquisa == "" || chavePesquisa == null) {
            return false;
        }

        sUrlRpc = "prot1_cadgeralmunic.RPC.php";

        var oCidadao = new Object();
        oCidadao.exec = 'findCidadao';
        oCidadao.ov02_sequencial = chavePesquisa;

        var msgDiv = "Aguarde ... Carregando dados do Cidadão.";
        js_divCarregando(msgDiv, 'msgBox');

        var oAjax = new Ajax.Request(
            sUrlRpc, {
                parameters: 'json=' + Object.toJSON(oCidadao),
                method: 'post',
                onComplete: js_retornoFindCidadao
            }

        );
    }

    function js_retornoFindCidadao(oAjax) {
        js_removeObj('msgBox');
        var oRetorno = eval('(' + oAjax.responseText + ')');

        var sExpReg = new RegExp('\\\\n', 'g');

        if (oRetorno.status == 2) {

            alert(oRetorno.message.urlDecode().replace(sExpReg, '\n'));
            parent.location.href = "prot1_cadgeralmunic001.php";
            return false;
        } else {
            if (oRetorno.cidadao.ov02_cnpjcpf.length == 11) {
                $('z01_cpf').value = oRetorno.cidadao.ov02_cnpjcpf;
                $('z01_ident').value = oRetorno.cidadao.z01_ident;
            } else if (oRetorno.cidadao.ov02_cnpjcpf.length == 14) {
                $('z01_cgc').value = oRetorno.cidadao.ov02_cnpjcpf;
                $('z01_nomecomple').value = oRetorno.cidadao.z01_nome.urlDecode();
            }

            $('z01_nome').value = oRetorno.cidadao.z01_nome.urlDecode();
            $('z01_telef').value = oRetorno.cidadao.z01_telef;
            $('z01_email').value = oRetorno.cidadao.z01_email.urlDecode();
            $('ov02_sequencial').value = oRetorno.cidadao.ov02_sequencial;
            $('ov02_seq').value = oRetorno.cidadao.ov02_seq;

            if (oRetorno.endereco != false) {
                js_PreencheEndereco(oRetorno.endereco);
            }
        }

    }

    function js_findCgm(chavePesquisa) {
        sUrlRpc = "prot1_cadgeralmunic.RPC.php";

        var oCgm = new Object();
        oCgm.exec = 'findCgm';
        oCgm.numcgm = chavePesquisa;

        var msgDiv = "Aguarde ...";
        js_divCarregando(msgDiv, 'msgBox');

        var oAjax = new Ajax.Request(
            sUrlRpc, {
                parameters: 'json=' + Object.toJSON(oCgm),
                method: 'post',
                async: false,
                onComplete: js_retornoFindCgm
            }
        );
    }

    function js_retornoFindCgm(oAjax) {
        js_removeObj('msgBox');
        var oRetorno = eval('(' + oAjax.responseText + ')');

        var sExpReg = new RegExp('\\\\n', 'g');
        if (oRetorno.status == 2) {
            alert(oRetorno.message.urlDecode().replace(sExpReg, '\n'));
            parent.location.href = "prot1_cadgeralmunic002.php";
            return false;
        } else {
            if (oRetorno.endereco != false) {
                js_PreencheEndereco(oRetorno.endereco);
            }

            js_PreencheFormulario(oRetorno.cgm);
            if (oRetorno.tipoempresa != false) {
                js_PreencheTipoEmpresa(oRetorno.tipoempresa);
            }

            js_cgmMunicipio(oRetorno.cgmmunicipio);

            if (oRetorno.lPermissaoCidadao) {

                if (oRetorno.cidadaocgm != false) {
                    var ov02_sequencial = oRetorno.cidadaocgm[0].ov03_cidadao;
                    var ov02_seq = oRetorno.cidadaocgm[0].ov03_seq;
                    var ov03_numcgm = oRetorno.cidadaocgm[0].ov03_numcgm;
                    $('btnVincular').style.display = 'none';
                    $('btnImportar').style.display = '';
                    $('btnImportar').observe('click', function() {
                        js_MICidadao(ov02_sequencial, ov02_seq, ov03_numcgm);
                    });
                } else {
                    $('btnVincular').style.display = '';
                    $('btnImportar').style.display = 'none';
                }
            }

        }
        js_getNaturezaJuridica();
        js_preencheIbge(oRetorno.cgm.z01_ibge.urlDecode());
    }

    function js_preencheIbge(sIbge) {
        if (sIbge != null) {
            $('z01_ibge').value = sIbge;
        }
    }

    function js_PreencheTipoEmpresa(aTipoEmpresa) {
        $('z03_tipoempresa').value = aTipoEmpresa[0].z03_tipoempresa;
        js_ProcCod_z03_tipoempresa('z03_tipoempresa', 'z03_tipoempresadescr');
    }

    /*-----------------Função para peencher os endereços primário e secundário do form ----------------------*/
    function js_PreencheEndereco(aEndereco) {
        var iNumEndereco = aEndereco.length;
        for (var iInd = 0; iInd < iNumEndereco; iInd++) {

            var sEndereco = "";
            sEndereco += aEndereco[iInd].srua.urlDecode();
            sEndereco += ",  nº " + aEndereco[iInd].snumero.urlDecode();
            sEndereco += " " + aEndereco[iInd].scomplemento.urlDecode();
            sEndereco += " - " + aEndereco[iInd].sbairro.urlDecode();
            sEndereco += " - " + aEndereco[iInd].smunicipio.urlDecode();
            sEndereco += " - " + aEndereco[iInd].ssigla.urlDecode();

            if (aEndereco[iInd].stipo == 'P') {
                $('idEnderPrimario').value = aEndereco[iInd].iendereco;
                $('endPrimario').value = sEndereco;
            } else {
                $('idEnderSecundario').value = aEndereco[iInd].iendereco;
                $('endSecundario').value = sEndereco;
            }
        }
    }

    /*-----------------------Fim da função que preenche os endereços e secundário do form ----------------------*/
    function js_PreencheFormulario(oCgm) {
        if (oCgm.lfisico == true) {
            js_preenchePessoaFisica(oCgm);
            return;
        }

        js_preenchePessoaJuridica(oCgm);
    }

    function js_preenchePessoaJuridica(oCgm) {
        $('z01_numcgm').value = oCgm.z01_numcgm;
        $('z01_telef').value = oCgm.z01_telef;
        $('z01_telcel').value = oCgm.z01_telcel;
        $('z01_email').value = oCgm.z01_email.urlDecode();
        $('z01_nome').value = oCgm.z01_nome.urlDecode();

        $('z01_cgc').value = oCgm.z01_cgc;
        $('z01_incest').value = oCgm.z01_incest;
        $('z01_contato').value = oCgm.z01_contato.urlDecode();
        $('z01_nomefanta').value = oCgm.z01_nomefanta.urlDecode();
        $('z01_nomecomple').value = oCgm.z01_nomecomple.urlDecode();
        $('z08_nire').value = oCgm.nire;
        $('z08_capitalsocial').value = oCgm.z08_capitalsocial;
        $('z01_cxpostal').value = oCgm.z01_cxpostal;
        $('z01_cxposcon').value = oCgm.z01_cxposcon;
        $('z01_obs').value = oCgm.z01_obs.urlDecode();
        $('z01_notificaemail').value = oCgm.z01_notificaemail;
        js_buscarCnaes(oCgm.z01_numcgm);
        js_preencheFoto(oCgm.z01_foto);
    }

    function js_preencheFoto(sOidFoto) {
        if (sOidFoto != null) {
            $('fotocgm').src = 'func_mostrarimagem.php?oid=' + sOidFoto;
            return;
        }

        $('fotocgm').src = 'imagens/none1.jpeg';
    }

    function js_preenchePessoaFisica(oCgm) {
        $('z01_numcgm').value = oCgm.z01_numcgm;
        $('z01_cpf').value = oCgm.z01_cpf;
        $('z01_ident').value = oCgm.z01_ident;
        $('z01_nome').value = oCgm.z01_nome.urlDecode();
        $('z01_pai').value = oCgm.z01_pai.urlDecode();
        $('z01_mae').value = oCgm.z01_mae.urlDecode();
        $('z01_nasc').value = js_formatar(oCgm.z01_nasc, 'd', '');
        $('z01_estciv').value = oCgm.z01_estciv;
        $('z01_sexo').value = oCgm.z01_sexo;
        $('z01_nacion').value = oCgm.z01_nacion;
        $('z01_profis').value = oCgm.z01_profis.urlDecode();
        $('z01_telef').value = oCgm.z01_telef;
        $('z01_telcel').value = oCgm.z01_telcel;
        $('z01_email').value = oCgm.z01_email.urlDecode();
        $('z01_telcon').value = oCgm.z01_telcon;
        $('z01_celcon').value = oCgm.z01_celcon;
        $('z01_emailc').value = oCgm.z01_emailc.urlDecode();
        $('z01_dtfalecimento').value = js_formatar(oCgm.z01_dtfalecimento, 'd', '');
        $('z01_identdtexp').value = js_formatar(oCgm.z01_identdtexp, 'd', '');
        $('z01_naturalidade').value = oCgm.z01_naturalidade.urlDecode();
        $('z01_escolaridade').value = oCgm.z01_escolaridade.urlDecode();
        $('z01_identorgao').value = oCgm.z01_identorgao.urlDecode();
        $('z01_trabalha').value = oCgm.z01_trabalha == true ? 't' : 'f';
        $('z01_localtrabalho').value = oCgm.z01_localtrabalha.urlDecode();
        $('z01_renda').value = oCgm.z01_renda;
        $('z01_pis').value = oCgm.z01_pis;
        $('rh70_sequencial').value = oCgm.z04_rhcbo;
        $('z01_cxpostal').value = oCgm.z01_cxpostal;
        $('z01_cxposcon').value = oCgm.z01_cxposcon;
        $('z01_obs').value = oCgm.z01_obs.urlDecode();
        $('z01_notificaemail').value = oCgm.z01_notificaemail;
        js_preencheIbge(oCgm.z01_ibge.urlDecode());
        js_preencheFoto(oCgm.z01_foto);
        js_pesquisaCbo(false);
    }

    /**
     * Valida se CPF/CNPJ ja esta cadastrado para outro cgm
     * @param {String} btnValue
     */
    function js_validarCGCCPF(btnValue) {
        var cgcCpf = '';

        if (lPessoaFisica) {
            cgcCpf = $F('z01_cpf');
        } else {
            cgcCpf = $F('z01_cgc');
        }

        if (empty(cgcCpf) || btnValue == 'Excluir') {
            return js_sendForm(btnValue)
        }

        js_divCarregando("Aguarde verificando CPF/CNPJ.", "msgBox");

        var oPesquisa = {
            exec: "findCpfCnpj",
            iCpfCnpj: cgcCpf
        };
        var sUrlRpc = "prot1_cadgeralmunic.RPC.php";
        var oParametros = {
            parameters: 'json=' + Object.toJSON(oPesquisa),
            method: 'post',
            onComplete: function(oAjax) {

                js_removeObj("msgBox");
                var oRetorno = eval('(' + oAjax.responseText + ')');

                if (oRetorno.z01_numcgm == false || oRetorno.z01_numcgm == $F('z01_numcgm')) {
                    return js_sendForm(btnValue)
                }

                alert("usuário:\n\n Cnpj/Cpf já cadastrado para o CGM " + oRetorno.z01_numcgm);
            }
        };

        var oAjax = new Ajax.Request(sUrlRpc, oParametros);
    }

    function js_consultarrfb(iRFB, sUrl, sCliente) {
        if (iRFB == 0) {
            alert("A instituição não possui contrato ativo para consulta de dados diretamente na Receita Federal. Para mais informações, contate o nosso suporte!");
            return;
        }

        var msgDiv = "Consultando dados, aguarde...";
        js_divCarregando(msgDiv, 'msgBox');
        if (!lPessoaFisica) {
            consultarRFB(iRFB, sUrl + 'validar/cnpj', sCliente, 'CNPJ');
            return;
        }
        consultarRFB(iRFB, sUrl + 'validar/cpf', sCliente, 'CPF');
    }

    function consultarRFB(iRFB, sUrlRpc, sCliente, sTipo) {
        var oSend = new Object();
        oSend.exec = 'consultarRFB';
        oSend.lPessoaFisica = true;
        var sDocumento = $F('z01_cgc');

        if (sTipo == 'CNPJ') {
            var sParametros = 'cnpj=' + sDocumento + '&cliente=' + sCliente + '&tipo=' + iRFB;
        } else {
            var sParametros = 'cpf=' + sDocumento + '&cliente=' + sCliente + '&tipo=' + iRFB;
        }

        var oAjax = new Ajax.Request(
            sUrlRpc, {
                parameters: sParametros,
                method: 'post',
                onComplete: js_retornoAtualizar
            }
        );
    }

    function js_retornoAtualizar(oAjax) {
        if (oAjax.status != 200) {
            alert("CPF/CNPJ não localizado");
        }

        var oRetorno = eval("(" + oAjax.responseText + ")");
        var sExpReg = new RegExp('\\\\n', 'g');
        js_removeObj("msgBox");
        if (!lPessoaFisica) {
            preenchePessoaJuridica(oRetorno.data.pessoa_juridica_info);
            js_removeObj("msgBox");
        } else {
            preenchePessoaFisica(oRetorno.data.pessoa_fisica_info);
            js_removeObj("msgBox");
        }
        js_removeObj("msgBox");
    }

    function preenchePessoaJuridica(oPessoa) {
        $('z01_nome').value = oPessoa.nome_empresarial.urlDecode();
        $('z01_nomecomple').value = oPessoa.nome_empresarial.urlDecode();
        $('z01_nomefanta').value = oPessoa.nome_fantasia.urlDecode();
        $('z01_tipoestabelecimento').value = oPessoa.tipo_estabelecimento;
        $('z01_porte').value = parseInt(oPessoa.porte);
        $('z08_capitalsocial').value = oPessoa.capital_social;
        $('z01_dtabertura').value = oPessoa.data_abertura;
        if (oPessoa.natureza_juridica.length > 0) {
            $('z01_naturezajuridica').value = oPessoa.natureza_juridica[0].codigo;
            $('z01_naturezajuridicadescr').value = oPessoa.natureza_juridica[0].codigo;
        }
        if (oPessoa.cnae_principal.length > 0) {
            js_buscarCnae(oPessoa.cnae_principal[0].codigo);
        }
        if (oPessoa.cnae_secundarios.length > 0) {
            js_buscarCnaesSecundarios(oPessoa.cnae_secundarios);
        }
        if (oPessoa.endereco.length > 0) {
            $('z01_ender').value = oPessoa.endereco[0].logradouro;
            $('z01_numero').value = oPessoa.endereco[0].numero;
            $('z01_bairro').value = oPessoa.endereco[0].bairro;
            $('z01_compl').value = oPessoa.endereco[0].complemento;
            $('z01_cep').value = oPessoa.endereco[0].cep;
            $('z01_uf').value = oPessoa.endereco[0].uf;
            js_pesquisaMunicipioEndereco();
            var select = document.querySelector('#z01_munic');
            for (var i = 0; i < select.options.length; i++) {
                if (select.options[i].text.normalize('NFD').replace(/[\u0300-\u036f]/g, "") == oPessoa.endereco[0].municipio.toUpperCase()) {
                    select.selectedIndex = i;
                    break;
                }
                // $('z01_munic').value = oPessoa.endereco[0].municipio;
            }
        }
        $('z01_email').value = oPessoa.correio_eletronico;
        if (oPessoa.telefone.length > 0) {
            $('z01_telef').value = oPessoa.telefone[0].ddd + oPessoa.telefone[0].telefone;
        }

        if (oPessoa.situacao_cadastral.length > 0) {
            $('z01_situacaocadastral').value = oPessoa.situacao_cadastral[0].codigo;
            if (oPessoa.situacao_cadastral[0].codigo == 1) {
                $('z01_situacaocadastral').value = 8;
            }
            if (oPessoa.situacao_cadastral[0].codigo == 2) {
                $('z01_situacaocadastral').value = 0;
            }
            if (oPessoa.situacao_cadastral[0].codigo == 3) {
                $('z01_situacaocadastral').value = 2;
            }
            if (oPessoa.situacao_cadastral[0].codigo == 8) {
                $('z01_situacaocadastral').value = 10;
            }
            if (oPessoa.situacao_cadastral[0].codigo == 4) {
                $('z01_situacaocadastral').value = 6;
            }
            if (oPessoa.situacao_cadastral[0].codigo == 5) {
                $('z01_situacaocadastral').value = 7;
            }
        } else {
            $('z01_situacaocadastral').value = 0;
        }

        if (oPessoa.informacoes_adicionais.optante_simples == 1) {
            $('z01_optantesimples').value = 1;
        } else {
            $('z01_optantesimples').value = 2;
        }

        if (oPessoa.informacoes_adicionais.optante_mei == 1) {
            $('z01_optantemei').value = 1;
        } else {
            $('z01_optantemei').value = 2;
        }
        js_removeObj("msgBox");
    }

    function preenchePessoaFisica(oPessoa) {
        $('z01_nome').value = oPessoa.nome.urlDecode();
        $('z01_nasc').value = js_formatar(oPessoa.data_nascimento, 'd', '');
        $('z01_anoobito').value = oPessoa.ano_obito;
        $('z01_situacaocadastral').value = oPessoa.situacao.codigo;
        js_removeObj("msgBox");
    }

    function js_sendForm(btnValue) {
        if (btnValue == 'Incluir' || btnValue == 'Alterar') {
            //Se validação e pessoa físca true
            //senão verifica se pessoa fisica  false trata como juridica
            var retornoValidacao = js_validaIncluir();

            if (retornoValidacao == true && lPessoaFisica == true) {
                var oIncluir = new Object();
                oIncluir.exec = 'incluirAlterar';
                oIncluir.lPessoaFisica = true;
                oIncluir.action = "incluir";
                if (btnValue == "Alterar") {
                    oIncluir.action = "alterar";
                }
                var oPessoa = new Object();
                oPessoa.z01_numcgm = $F('z01_numcgm').trim();
                oPessoa.z01_cgccpf = $F('z01_cpf');
                oPessoa.z01_ident = $F('z01_ident');
                oPessoa.z01_nome = tagString($F('z01_nome'));
                oPessoa.z01_nomecomple = tagString($F('z01_nome'));
                oPessoa.z01_pai = tagString($F('z01_pai'));
                oPessoa.z01_mae = tagString($F('z01_mae'));
                oPessoa.z01_nasc = js_formatar($F('z01_nasc'), 'd');
                oPessoa.z01_estciv = $F('z01_estciv');
                oPessoa.z01_sexo = $F('z01_sexo');
                oPessoa.z01_nacion = $F('z01_nacion');
                oPessoa.z01_profis = tagString($F('z01_profis'));
                oPessoa.z01_telef = $F('z01_telef');
                oPessoa.z01_telcel = $F('z01_telcel');
                oPessoa.z01_email = tagString($F('z01_email'));
                oPessoa.z01_notificaemail = $F('z01_notificaemail');
                oPessoa.z01_telcon = $F('z01_telcon');
                oPessoa.z01_celcon = $F('z01_celcon');
                oPessoa.z01_emailc = tagString($F('z01_emailc'));
                oPessoa.z01_cadast = js_formatar($F('z01_cadast'), 'd');
                oPessoa.z01_ultalt = js_formatar($F('z01_ultalt'), 'd');
                oPessoa.z01_dtfalecimento = js_formatar($F('z01_dtfalecimento'), 'd');
                oPessoa.z01_identdtexp = js_formatar($F('z01_identdtexp'), 'd');
                oPessoa.z01_identorgao = tagString($F('z01_identorgao'));
                oPessoa.z01_naturalidade = tagString($F('z01_naturalidade').trim());
                oPessoa.z01_ibge = $F('z01_ibge').trim();
                oPessoa.z01_localtrabalho = tagString($F('z01_localtrabalho'));
                oPessoa.z01_renda = tagString($F('z01_renda'));
                oPessoa.z01_pis = tagString($F('z01_pis'));
                oPessoa.z01_trabalha = $F('z01_trabalha');
                oPessoa.z01_cep = $F('z01_cep');
                oPessoa.z01_cxpostal = $F('z01_cxpostal');
                oPessoa.imunicipio = $F('z01_munic');
                oPessoa.irua = $F('irua');
                oPessoa.ibairro = $F('ibairro');
                oPessoa.iestado = $F('iestado');
                oPessoa.z01_ender = $F('z01_ender');
                oPessoa.z01_compl = $F('z01_compl');
                oPessoa.z01_numero = $F('z01_numero');
                oPessoa.z01_bairro = $F('z01_bairro');
                oPessoa.z01_condominio = $F('z01_condominio');
                oPessoa.z01_loteamento = $F('z01_loteamento');
                oPessoa.z01_pontoreferencia = $F('z01_pontoreferencia');
                oPessoa.z01_cxposcon = $F('z01_cxposcon');
                oPessoa.z01_obs = $F('z01_obs').urlDecode();
                oPessoa.z04_rhcbo = $F('rh70_sequencial');
                oPessoa.z01_escolaridade = $F('z01_escolaridade');
                oPessoa.z01_anoobito = $F('z01_anoobito');
                oPessoa.z01_produtorrural = $F('z01_produtorrural');
                oPessoa.z01_situacaocadastral = $F('z01_situacaocadastral');
                var oEndereco = new Object();
                // oEndereco.idEndPrimario = $F('idEnderPrimario');
                oEndereco.idEndSecundario = $F('idEnderSecundario');
                var oCidadao = new Object();
                oCidadao.ov02_sequencial = $F('ov02_sequencial');
                oCidadao.ov02_seq = $F('ov02_seq');
                oIncluir.pessoa = new Object();
                oIncluir.pessoa = oPessoa;
                oIncluir.tipoEmpresa = new Object();
                oIncluir.tipoEmpresa = oTipoEmpresa;
                oIncluir.cidadao = new Object();
                oIncluir.cidadao = oCidadao;
                oIncluir.endereco = new Object();
                oIncluir.endereco = oEndereco;
                js_AjaxCgm(oIncluir, js_retornoIncluirFisica);
            } else if (retornoValidacao && lPessoaFisica == false) {
                var oIncluir = new Object();
                oIncluir.exec = 'incluirAlterar';
                oIncluir.lPessoaFisica = false;
                oIncluir.action = "incluir";
                if (btnValue == "Alterar") {
                    oIncluir.action = "alterar";
                }

                var oPessoa = new Object();
                oPessoa.z01_numcgm = $F('z01_numcgm').trim();
                oPessoa.z01_cgccpf = $F('z01_cgc');
                oPessoa.z01_naturezajuridica = $F('z01_naturezajuridica');
                oPessoa.z01_tipoestabelecimento = $F('z01_tipoestabelecimento');
                oPessoa.z01_porte = $F('z01_porte');
                oPessoa.z01_situacaocadastral = $F('z01_situacaocadastral');
                oPessoa.z01_situacaoespecial = $F('z01_situacaoespecial');
                oPessoa.z01_optantesimples = $F('z01_optantesimples');
                oPessoa.z01_optantemei = $F('z01_optantemei');
                oPessoa.z01_nome = tagString($F('z01_nome'));
                oPessoa.z01_contato = tagString($F('z01_contato'));
                oPessoa.z01_incest = $F('z01_incest');
                oPessoa.z01_telef = $F('z01_telef');
                oPessoa.z01_telcel = $F('z01_telcel');
                oPessoa.z01_email = tagString($F('z01_email'));
                oPessoa.z01_notificaemail = $F('z01_notificaemail');
                // oPessoa.z01_telcon = $F('z01_telcon');
                // oPessoa.z01_celcon = $F('z01_celcon');
                oPessoa.z01_cep = $F('z01_cep');
                oPessoa.z01_compl = $F('z01_compl');
                oPessoa.z01_cxpostal = $F('z01_cxpostal');
                oPessoa.imunicipio = $F('z01_munic');
                oPessoa.irua = $F('irua');
                oPessoa.ibairro = $F('ibairro');
                oPessoa.iestado = $F('iestado');
                oPessoa.z01_ender = $F('z01_ender');
                oPessoa.z01_numero = $F('z01_numero');
                oPessoa.z01_bairro = $F('z01_bairro');
                oPessoa.z01_cxposcon = $F('z01_cxposcon');
                oPessoa.z01_condominio = $F('z01_condominio');
                oPessoa.z01_loteamento = $F('z01_loteamento');
                oPessoa.z01_pontoreferencia = $F('z01_pontoreferencia');
                // oPessoa.z01_emailc = tagString($F('z01_emailc'));
                oPessoa.z01_cadast = js_formatar($F('z01_cadast'), 'd');
                oPessoa.z01_ultalt = js_formatar($F('z01_ultalt'), 'd');
                oPessoa.z01_datasituacaoespecial = js_formatar($F('z01_datasituacaoespecial'), 'd');
                oPessoa.z01_dtabertura = js_formatar($F('z01_dtabertura'), 'd');
                oPessoa.z01_nomecomple = tagString($F('z01_nomecomple'));
                oPessoa.z01_nomefanta = tagString($F('z01_nomefanta'));
                oPessoa.z01_obs = $F('z01_obs').urlDecode();
                oPessoa.z01_incmunici = $F('z01_incmunici');
                oPessoa.z01_cnae = $F('z01_cnae');
                let cnaes = [];
                let todoscnaes = document.form1.cnaes;
                Array.from(todoscnaes.options).forEach(element => {
                    cnaes.push(element.value);
                });
                oPessoa.z01_cnaes = cnaes;

                var oEndereco = new Object();
                // oEndereco.idEndPrimario = $F('idEnderPrimario');
                oEndereco.idEndSecundario = $F('idEnderSecundario');
                var oTipoEmpresa = new Object();
                // oTipoEmpresa.iTipoEmpresa = $F('z03_tipoempresa');
                var oJuridico = new Object();
                oJuridico.z08_nire = $F('z08_nire');
                oJuridico.z08_capitalsocial = $F('z08_capitalsocial');
                var oCidadao = new Object();
                oCidadao.ov02_sequencial = $F('ov02_sequencial');
                oCidadao.ov02_seq = $F('ov02_seq');

                oIncluir.pessoa = new Object();
                oIncluir.pessoa = oPessoa;

                oIncluir.endereco = new Object();
                oIncluir.endereco = oEndereco;

                oIncluir.tipoEmpresa = new Object();
                oIncluir.tipoEmpresa = oTipoEmpresa;

                oIncluir.juridico = new Object();
                oIncluir.juridico = oJuridico;

                oIncluir.cidadao = new Object();
                oIncluir.cidadao = oCidadao;
                js_AjaxCgm(oIncluir, js_retornoIncluirJuridica);
            }
        } else if (btnValue == 'Excluir') {

            var oExcluir = new Object();
            oExcluir.exec = 'excluir';
            oExcluir.z01_numcgm = $F('z01_numcgm');

            js_AjaxCgm(oExcluir, js_retornoExcluirCgm);

        }
    }

    // Busca a descrição e a UF com base no código do IBGE
    function js_preencheUf(ibge) {
        var oParam = new Object();
        oParam.exec = 'getDescrUf';
        oParam.ibge = ibge;
        var oAjax = new Ajax.Request(
            sUrlRpc, {
                asynchronous: false,
                parameters: 'json=' + Object.toJSON(oParam),
                method: 'post',
                onComplete: js_retornoDescrUf
            }
        );
    }

    function js_retornoDescrUf(oAjax) {
        let oRetorno = eval("(" + oAjax.responseText + ")");
        let ufs = document.getElementById('uf');
        let descricao = oRetorno.descricao;

        for (let cont = 0; cont < ufs.length; cont++) {
            if (ufs[cont].value == oRetorno.sigla) {
                document.getElementById('ufdescr').selectedIndex = cont;
            }
        }

        js_pesquisaMunicipios(descricao);
    }

    function js_retornoExcluirCgm(oAjax) {
        js_removeObj("msgBox");

        var oRetorno = eval("(" + oAjax.responseText + ")");
        var sExpReg = new RegExp('\\\\n', 'g');

        if (oRetorno.status == 2) {
            alert(oRetorno.message.urlDecode().replace(sExpReg, '\n'));
            return false;
        } else if (oRetorno.status == 1) {
            alert(oRetorno.message.urlDecode().replace(sExpReg, '\n'));
            location.href = 'prot1_cadgeralmunic006.php';
            return false;
        }
    }

    /**
     * Método que exibe a lookup de pesquisa por CEP
     * No retorno da mesma disparamos a pesquisa que busca as informações do CEP selecionado
     */
    function lookupCepPrimario() {
        js_OpenJanelaIframe('', 'db_iframe_cep',
            'func_cep.php?funcao_js=parent.retornoLookupCepPrimario|cep|cp01_bairro',
            'Pesquisa CEP', true);
        $('Jandb_iframe_cep').style.zIndex = 100000;
    }

    function lookupCnaePrimario() {
        js_OpenJanelaIframe('', 'db_iframe_cnae',
            'func_cnae.php?funcao_js=parent.retornoLookupCnaePrimario|q71_sequencial|q71_descr',
            'Pesquisa CNAE', true);
        $('Jandb_iframe_cnae').style.zIndex = 100000;
    }

    function lookupCnaeSecundario() {
        js_OpenJanelaIframe('', 'db_iframe_cnae',
            'func_cnae.php?funcao_js=parent.retornoLookupCnaeSecundario|q71_sequencial|q71_descr',
            'Pesquisa CNAE', true);
        $('Jandb_iframe_cnae').style.zIndex = 100000;
    }

    function retornoLookupCnaePrimario() {
        db_iframe_cnae.hide();
        $('z01_cnae').value = arguments[0];
        $('z01_cnaedescr').value = arguments[1];
    }

    function retornoLookupCepPrimario() {
        db_iframe_cep.hide();
        js_pesquisaCep(arguments[0], arguments[1]);
    }

    // Metodo responsavel pela busca do CEP
    function js_pesquisaCep(cep, bairro) {
        // Valida se o CEP foi informado
        if (cep == '') {
            return false;
        }

        // Valida se o CEP é diferente de 8
        if (cep.length != 8) {
            alert('usuário:\n\nO Cep informado possui menos de 8 digitos.\n\nVerifique para continuar a pesquisa.\n\n');
            return false;
        }

        // Valida se o endereço já foi preenchido
        if ($F('z01_bairro').trim() != "" ||
            $F('z01_ender').trim() != "" ||
            $F('z01_numero').trim() != "") {

            if (!confirm('usuário:\n\nExistem dados abaixo preenchidos serão perdidos Deseja Continuar?\n\n')) {
                $('z01_cep').value = ''
                return false;
            }
        }

        $('z01_cep').value = cep;
        $('z01_cxpostal').value = cep;

        var oPesquisa = new Object();
        oPesquisa.exec = 'findCep';
        oPesquisa.codigoCep = cep
        oPesquisa.sNomeBairro = bairro

        var msgDiv = "Aguarde pesquisando cep.";
        js_divCarregando(msgDiv, 'msgBox');

        var oAjax = new Ajax.Request(
            'con4_endereco.RPC.php', {
                parameters: 'json=' + Object.toJSON(oPesquisa),
                method: 'post',
                onComplete: js_retornoPesquisarCep
            }
        );
    }

    // Metodo responsavel pela busca do CEP na Alteracao
    function js_pesquisaCepInicial(cep, bairro) {
        // Valida se o CEP foi informado
        if (cep == '') {
            return false;
        }

        var oPesquisa = new Object();
        oPesquisa.exec = 'findCep';
        oPesquisa.codigoCep = cep
        oPesquisa.sNomeBairro = bairro

        var msgDiv = "Aguarde pesquisando cep.";
        js_divCarregando(msgDiv, 'msgBox');

        var oAjax = new Ajax.Request(
            'con4_endereco.RPC.php', {
                parameters: 'json=' + Object.toJSON(oPesquisa),
                method: 'post',
                onComplete: js_retornoPesquisarCepInicial
            }
        );
    }

    // Tratativas do retorno do metodo pesquisar cep
    function js_retornoPesquisarCep(oAjax) {
        js_removeObj('msgBox');
        var oRetorno = eval('(' + oAjax.responseText + ')');

        if (oRetorno.endereco != false) {
            $('z01_pais').value = oRetorno.endereco[0].spais.urlDecode();
            js_pegarDescricaoEstado(oRetorno.endereco[0].iestado, oRetorno.estados);
            $('z01_bairro').value = oRetorno.endereco[0].sbairro.urlDecode();
            $('z01_munic').value = oRetorno.endereco[0].imunicipio;
            $('z01_ender').value = oRetorno.endereco[0].srua.urlDecode();
            $('imunicipio').value = oRetorno.endereco[0].imunicipio;
            $('irua').value = oRetorno.endereco[0].irua;
            $('iestado').value = oRetorno.endereco[0].iestado;
            $('ibairro').value = oRetorno.endereco[0].ibairro;
            return;
        }

        // alert('usuário:\n\n\Nenhum endereço retornado para o cep informado !\n\n');
    }

    // Tratativas do retorno do metodo pesquisar cep
    function js_retornoPesquisarCepInicial(oAjax) {
        js_removeObj('msgBox');
        var oRetorno = eval('(' + oAjax.responseText + ')');

        if (oRetorno.endereco != false) {
            $('z01_pais').value = oRetorno.endereco[0].spais.urlDecode();
            js_pegarDescricaoEstado(oRetorno.endereco[0].iestado, oRetorno.estados);
            // $('z01_bairro').value = oRetorno.endereco[0].sbairro.urlDecode();
            $('z01_munic').value = oRetorno.endereco[0].imunicipio;
            // $('z01_ender').value = oRetorno.endereco[0].srua.urlDecode();
            $('imunicipio').value = oRetorno.endereco[0].imunicipio;
            $('irua').value = oRetorno.endereco[0].irua;
            $('iestado').value = oRetorno.endereco[0].iestado;
            $('ibairro').value = oRetorno.endereco[0].ibairro;
            return;
        }

        // alert('usuário:\n\n\Nenhum endereço retornado para o cep informado !\n\n');
    }

    // Verifica se o código do estado está no array de estados
    function js_pegarDescricaoEstado(codigo, estados) {
        for (const oEstado of estados) {
            if (oEstado.codigo == estados) {
                $('z01_uf').value = oEstado.descricao.replaceAll('+', ' ');
            }
        }
        return;
    }

    function js_AjaxCgm(oSend, jsRetorno) {
        var msgDiv = "Aguarde ...";
        js_divCarregando(msgDiv, 'msgBox');
        var sUrlRpc = "prot1_cadgeralmunic.RPC.php";
        var oAjax = new Ajax.Request(
            sUrlRpc, {
                parameters: 'json=' + Object.toJSON(oSend),
                method: 'post',
                onComplete: jsRetorno
            }
        );
    }

    function js_retornoIncluirFisica(oAjax) {

        js_removeObj("msgBox");

        var oRetorno = eval("(" + oAjax.responseText + ")");
        var sExpReg = new RegExp('\\\\n', 'g');

        if (oRetorno.status == 2) {
            alert(oRetorno.message.urlDecode().replace(sExpReg, '\n'));
        } else if (oRetorno.status == 1) {

            alert(oRetorno.message.urlDecode().replace(sExpReg, '\n'));
            if (oRetorno.action == 'incluir') {
                if (funcaoRetorno != '') {

                    eval(funcaoRetorno + '(' + oRetorno.z01_numcgm + ');');

                } else {
                    location.href = 'prot1_cadgeralmunic005.php?chavepesquisa=' + oRetorno.z01_numcgm;
                }
            } else {

                if (funcaoRetorno != '') {
                    eval(funcaoRetorno + '(' + oRetorno.z01_numcgm + ');');

                } else {

                    location.href = 'prot1_cadgeralmunic005.php';
                }
            }

            return false;
        }

    }

    function js_retornoIncluirJuridica(oAjax) {

        js_removeObj("msgBox");
        var oRetorno = eval("(" + oAjax.responseText + ")");
        var sExpReg = new RegExp('\\\\n', 'g');
        if (oRetorno.status == 2) {
            alert(oRetorno.message.urlDecode().replace(sExpReg, '\n'));
            return false;
        } else if (oRetorno.status == 1) {
            alert(oRetorno.message.urlDecode().replace(sExpReg, '\n'));
            if (oRetorno.action == 'incluir') {
                if (funcaoRetorno != '') {

                    eval(funcaoRetorno + '(' + oRetorno.z01_numcgm + ');');

                } else {

                    location.href = 'prot1_cadgeralmunic005.php?chavepesquisa=' + oRetorno.z01_numcgm;
                }
            } else {
                if (funcaoRetorno != '') {

                    eval(funcaoRetorno + '(' + oRetorno.z01_numcgm + ');');

                } else {

                    location.href = 'prot1_cadgeralmunic005.php';
                }
            }
            return false;
        }

    }

    function js_validaEmail(email) {

        var email = email;
        var expReg0 = new RegExp("[A-Za-z0-9_.-]+@([A-Za-z0-9_]+\.)+[A-Za-z]{2,4}");
        var expReg1 = new RegExp("[!#$%*<>,:;?°ºª~/|]");

        if (email.match(expReg1) != null || email.indexOf('\\') != -1 || email.indexOf(' ') != -1) {
            alert('Usuário:\n\nEmail informado não é válido ou esta vazio!\n\n exemplo de email: xxx@xx.xx\n\n Email pode conter: \n  letras, números, hifen(-), sublinhado _\n\n Email não pode conter:\n  caracteres especiais, virgula(,), ponto e virgula (;), dois pontos (:) \n\nAdministrador:\n\n');
            return false;
        }

        if (email.match(expReg0) == null) {
            alert('Usuário:\n\nEmail informado não é válido ou esta vazio!\n\n exemplo de email: xxx@xx.xx\n\n Email pode conter: \n  letras, números, hifen(-), sublinhado _\n\n Email não pode conter:\n  caracteres especiais, virgula(,), ponto e virgula (;), dois pontos (:) \n\nAdministrador:\n\n');
            return false;
        }
        return true;

    }

    function js_validaIncluir() {
        if ($F('z01_email') != '') {
            if (!js_validaEmail($F('z01_email'))) {
                return false;
            }
        }

        if (lPessoaFisica == true) {
            if (js_validaPessoaFisica())
                return true;
            else
                return false;
        }

        if (lPessoaFisica == false) {
            if (js_validaPessoaJuridica())
                return true;
            else
                return false;
        }

        var strMessageUsuario = "Tipo de Pessoa indefinido para o cadastro!";
        js_messageBox(strMessageUsuario, '');
        return false;
    }

    function js_validaPessoaFisica() {
        if ($F('z01_emailc') != '') {
            if (!js_validaEmail($F('z01_emailc'))) {
                return false;
            }
        }

        if (<?= $db_opcao ?> == 1) {
            if ($F('z01_nasc').trim() == "") {
                var strMessageUsuario = "Campo DATA DE NASCIMENTO não informada!";
                js_messageBox(strMessageUsuario, '');
                $('z01_nasc').focus();
                return false;
            }
        }

        if ($F('z01_cpf').trim() == "") {
            var strMessageUsuario = "Campo CPF não informado !";
            js_messageBox(strMessageUsuario, '');
            $('z01_cpf').focus();
            return false;
        }

        if ($F('z01_nome').trim() == "") {
            var strMessageUsuario = "Campo Nome não informado!";
            js_messageBox(strMessageUsuario, '');
            $('z01_nome').focus();
            return false;
        }

        if ($F('z01_cep').trim() == "") {
            var strMessageUsuario = "Campo CEP não informado!";
            js_messageBox(strMessageUsuario, '');
            $('z01_cep').focus();
            return false;
        }

        if ($F('z01_ender').trim() == "") {
            var strMessageUsuario = "Campo Logradouro não informado!";
            js_messageBox(strMessageUsuario, '');
            $('z01_ender').focus();
            return false;
        }

        if ($F('z01_bairro').trim() == "") {
            var strMessageUsuario = "Campo Bairro/Distrito não informado!";
            js_messageBox(strMessageUsuario, '');
            $('z01_bairro').focus();
            return false;
        }

        if ($F('z01_numero').trim() == "") {
            var strMessageUsuario = "Campo Número não informado!";
            js_messageBox(strMessageUsuario, '');
            $('z01_numero').focus();
            return false;
        }

        /**
         * Data de nascimento nao pode ser maior que a de falecimento
         */
        if ($F('z01_dtfalecimento') != '' && $F('z01_nasc') != '') {
            var mValidaDatas = js_diferenca_datas(js_formatar($F('z01_nasc'), 'd'), js_formatar($F('z01_dtfalecimento'), 'd'), 3);

            /**
             * Data de falecimento menor
             * - mValidaDatas = 'i' quando datas forem iguais
             */
            if (mValidaDatas != 'i' && mValidaDatas) {
                js_messageBox('Data de falecimento não pode ser menor que data de nascimento.', '');
                $('z01_dtfalecimento').focus();
                return false;
            }
        }

        /**
         * Caso valor da renda possua hifen
         */
        if ($F('z01_renda') != '' && $F('z01_renda').indexOf('-') != -1) {
            alert('Valor para renda inválido.');
            return false;
        }

        if ($('btnSubmit').value == "Alterar") {
            if ($F('z01_obs').trim() == "") {
                var strMessageUsuario = "Observações não informado!";
                js_messageBox(strMessageUsuario, '');
                $('z01_obs').focus();
                return false;
            }
        }

        if ($('ufdescr').selectedIndex != 0) {
            if ($('listMunicipios').selectedIndex != 0) {
                if ($F('z01_ibge').trim() == '') {
                    var strMessageUsuario = 'Código IBGE não informado!';
                    js_messageBox(strMessageUsuario, '');
                    $('listMunicipios').focus();
                    return false;
                }
            } else {
                var strMessageUsuario = 'Cidade não informada!';
                js_messageBox(strMessageUsuario, '');
                $('listMunicipios').focus();
                return false;
            }
        }
        return true;
    }

    function js_validaPessoaJuridica() {
        if ($F('z01_cgc').trim() == "") {
            var strMessageUsuario = "Campo CNPJ não informado !";
            js_messageBox(strMessageUsuario, '');
            $('z01_cgc').focus();
            return false;
        }

        if ($F('z01_nome').trim() == "") {
            var strMessageUsuario = "Campo Nome não informado!";
            js_messageBox(strMessageUsuario, '');
            $('z01_nome').focus();
            return false;
        }

        if ($F('z01_nomecomple').trim() == "") {
            var strMessageUsuario = "Campo Nome Completo não informado!";
            js_messageBox(strMessageUsuario, '');
            $('z01_nomecomple').focus();
            return false;
        }

        if ($('z01_naturezajuridica').selectedIndex == 0) {
            var strMessageUsuario = "Campo Natureza Jurifica não informado";
            js_messageBox(strMessageUsuario, '');
            $('z01_naturezajuridica').focus();
            return false;
        }

        if ($F('z01_cep').trim() == "") {
            var strMessageUsuario = "Campo CEP não informado!";
            js_messageBox(strMessageUsuario, '');
            $('z01_cep').focus();
            return false;
        }

        if ($F('z01_ender').trim() == "") {
            var strMessageUsuario = "Campo Logradouro não informado!";
            js_messageBox(strMessageUsuario, '');
            $('z01_ender').focus();
            return false;
        }

        if ($F('z01_bairro').trim() == "") {
            var strMessageUsuario = "Campo Bairro/Distrito não informado!";
            js_messageBox(strMessageUsuario, '');
            $('z01_bairro').focus();
            return false;
        }

        if ($F('z01_numero').trim() == "") {
            var strMessageUsuario = "Campo Número não informado!";
            js_messageBox(strMessageUsuario, '');
            $('z01_numero').focus();
            return false;
        }

        if ($('btnSubmit').value == "Alterar") {
            if ($F('z01_obs').trim() == "") {
                var strMessageUsuario = "Observações não informado!";
                js_messageBox(strMessageUsuario, '');
                $('z01_obs').focus();
                return false;
            }
        }

        return true;
    }

    function js_messageBox(strMessageUsuario, strMessageAdministrador) {
        var strMessage = "usuário:";
        strMessage += "\n\n\t" + strMessageUsuario + "\n\n";
        strMessage += "administrador:";
        strMessage += "\n\n" + strMessageAdministrador + "\n\n";

        alert(strMessage);
    }

    function js_copiaNome() {

        if ($('z01_nomecomple')) {

            $('z01_nomecomple').value = $F('z01_nome');
        }
    }
    /*----------------------------Funções para manipular o cidadao----------------------*/

    function js_importaCadastroCidadao() {

        js_OpenJanelaIframe('', 'db_iframe_cidadao',
            'func_cidadaovinculos.php?funcao_js=parent.js_mostracidadao1|0|4&liberado=true&ativo=true&vinculocgm=false',
            'Pesquisa', true);

    }

    function js_MICidadao(ov02_sequencial, ov02_seq, ov03_numcgm) {

        var sQuery = "";
        sQuery += "importa=true";
        sQuery += "&ov02_sequencial=" + ov02_sequencial;
        sQuery += "&ov02_seq=" + ov02_seq;
        sQuery += "&ov03_numcgm=" + ov03_numcgm;
        js_OpenJanelaIframe('', 'db_iframe',
            'prot1_cidadaocgmdetalhe.php?' + sQuery,
            'Pesquisa', true);
    }

    function js_retornoAlteraCgmCidadao() {
        db_iframe.hide();
        js_findCgm($F('z01_numcgm'));
    }

    function js_vinculaCadastroCidadaoCGM() {

        js_OpenJanelaIframe('', 'db_iframe_cidadao',
            'func_cidadaovinculos.php?funcao_js=parent.js_vinculaCidadaoCGM|0|1&liberado=true&ativo=true&vinculocgm=false',
            'Pesquisa', true
        );

    }

    function js_vinculaCidadaoCGM(ov02_sequencial, ov02_seq) {

        db_iframe_cidadao.hide();

        var oVincular = new Object();

        oVincular.acao = 'vincular';
        oVincular.ov03_cidadao = ov02_sequencial;
        oVincular.ov03_seq = ov02_seq;
        oVincular.ov03_numcgm = $F('z01_numcgm');

        var sDados = Object.toJSON(oVincular);
        var msgDiv = 'Aguarde vinculando Cidadão ao CGM.....';
        js_divCarregando(msgDiv, 'msgBox');

        sUrl = 'ouv1_cidadao.RPC.php';
        var sQuery = 'dados=' + sDados;
        var oAjax = new Ajax.Request(sUrl, {
            method: 'post',
            parameters: sQuery,
            onComplete: js_retornoVincularDados
        });

    }

    function js_retornoVincularDados(oAjax) {
        js_removeObj("msgBox");

        var aRetorno = eval("(" + oAjax.responseText + ")");
        var sExpReg = new RegExp('\\\\n', 'g');

        alert(aRetorno.message.urlDecode().replace(sExpReg, '\n'));

        if (aRetorno.status == 0) {
            return false;
        }

        if (aRetorno.status == 1) {
            var z01_numcgm = aRetorno.ov03_numcgm;
            location.href = 'prot1_cadgeralmunic005.php?chavepesquisa=' + z01_numcgm;
        }
    }
    /*----------------------------Fim Funções para manipular o cidadao----------------------*/

    function js_ExcluiEnderSecundario() {
        if ($F('endSecundario') != '') {
            if (confirm('usuário:\n\n Deseja excluir o endereço ?')) {
                $('idEnderSecundario').value = '';
                $('endSecundario').value = '';
                return false;
            }
            return false;
        }
    }

    function js_validaPis(pis) {
        if (pis != '') {
            if (!js_ChecaPIS(pis)) {
                alert("Pis inválido.Verifique.");
                document.form1.z01_pis.focus();
                document.form1.z01_pis.value = '';
                return (false);
            }
            return (true);
        }
    }

    function js_carregarMunicipios() {
        js_divCarregando("Aguarde ... Carregando Cidades", 'msgBox');
        var descrMunicipios = document.getElementById('ufdescr');
        js_pesquisaMunicipios(null);
        document.getElementById('z01_ibge').value = '';

        if (descrMunicipios.text == 'DISTRITO FEDERAL')
            document.getElementById('listMunicipios').selectedIndex = 1;

        if (descrMunicipios.selectedIndex == 0) {
            document.getElementById('listMunicipios').selectedIndex = 0;
        }
        js_removeObj('msgBox');
    }


    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('ufdescr')) {
            js_pesquisaMunicipios(null);
            if (document.getElementById('codigo_cidade')) {
                document.getElementById('listMunicipios').value = document.getElementById('codigo_cidade').value;
            }
        }

        document.getElementById('z01_uf').onchange = () => {
            js_pesquisaMunicipioEndereco();
        }

        if (document.getElementById('ufdescr')) {
            document.getElementById('ufdescr').onchange = () => {
                var descrMunicipios = document.getElementById('ufdescr');
                js_pesquisaMunicipios(null);
                document.getElementById('z01_ibge').value = '';

                if (descrMunicipios.text == 'DISTRITO FEDERAL')
                    document.getElementById('listMunicipios').selectedIndex = 1;

                if (descrMunicipios.selectedIndex == 0) {
                    document.getElementById('listMunicipios').selectedIndex = 0;
                }
            };
        }

        if (document.getElementById('listMunicipios')) {
            document.getElementById('listMunicipios').onchange = () => {
                js_retornaCodigoIbge();
            }
        }
    }, false);

    function js_pesquisaMunicipioEndereco() {
        var sUrlRpc = 'prot1_cadgeralmunic.RPC.php';
        var oParam = new Object();
        var element = document.getElementById('z01_uf');
        oParam.estado = element.options[element.selectedIndex].text;
        oParam.exec = 'buscaMunicipios';

        var oAjax = new Ajax.Request(sUrlRpc, {
            method: 'post',
            asynchronous: false,
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_preencheEnderecoCidade
        });
    }

    function js_pesquisaMunicipios(municipio = null) {
        let element = document.getElementById('ufdescr');
        let option = '';
        if (element.options) {
            option = element.options[element.selectedIndex].text;
        }
        var sUrlRpc = 'prot1_cadgeralmunic.RPC.php';
        var oParam = new Object();
        oParam.estado = option;
        oParam.exec = 'buscaMunicipios';

        if (municipio != '' && municipio != undefined) {
            oParam.cidade = municipio;
        }

        var oAjax = new Ajax.Request(sUrlRpc, {
            method: 'post',
            asynchronous: false,
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_preencheCidade
        });
    }

    function js_preencheEnderecoCidade(obj) {
        js_removeObj('msgMunicipio');

        var params = JSON.parse(obj.request.parameters.json);
        var resposta = eval('(' + obj.responseText + ')');

        var municipios = document.getElementById('z01_munic');
        var ufs = document.getElementById('z01_uf');

        municipios.innerHTML = '';
        listaMunicipios = resposta.municipios;
        municipios.add(new Option('Selecione'));

        if (listaMunicipios.length == 1 && ufs.selectedIndex == 7) {
            var ob = document.createElement('option');
            ob.value = listaMunicipios.sequencial;
            ob.text = 'BRASÍLIA';
            municipios.appendChild(ob);
            municipios.selectedIndex = 1;
        } else {
            listamunictratado = listaMunicipios.filter(item => item.descricao !== undefined && item.descricao !== null);

            listamunictratado.forEach((item, index) => {
                var obj = document.createElement('option');
                obj.value = item.sequencial;
                obj.text = item.descricao.urlDecode();
                municipios.appendChild(obj);
            });
        }

        for (let cont = 0; cont < municipios.length; cont++) {
            if (municipios.options[cont].text == params.cidade) {
                municipios.selectedIndex = cont;
            }
        }
    }

    function js_preencheCidade(obj) {
        js_removeObj('msgMunicipio');

        var params = JSON.parse(obj.request.parameters.json);
        var resposta = eval('(' + obj.responseText + ')');

        var municipios = document.getElementById('listMunicipios');
        var ufs = document.getElementById('ufdescr');

        municipios.innerHTML = '';
        listaMunicipios = resposta.municipios;
        municipios.add(new Option('Selecione'));

        if (listaMunicipios.length == 1 && ufs.selectedIndex == 7) {
            var ob = document.createElement('option');
            ob.value = listaMunicipios.sequencial;
            ob.text = 'BRASÍLIA';
            municipios.appendChild(ob);
            municipios.selectedIndex = 1;
            js_retornaCodigoIbge();
        } else {
            listamunictratado = listaMunicipios.filter(item => item.descricao !== undefined && item.descricao !== null);

            listamunictratado.forEach((item, index) => {
                var obj = document.createElement('option');
                obj.value = item.sequencial;
                obj.text = item.descricao.urlDecode();
                municipios.appendChild(obj);

            });
        }

        for (let cont = 0; cont < municipios.length; cont++) {
            if (municipios.options[cont].text == params.cidade) {
                municipios.selectedIndex = cont;
                js_retornaCodigoIbge();
            }
        }
        js_preencheIbge(retorno.ibge);
    }

    function js_retornaCodigoIbge() {
        var element = document.getElementById('ufdescr');
        var option = element.options[element.selectedIndex].text;
        var oParam = new Object();

        let cidade = document.getElementById('listMunicipios');

        oParam.cidade = cidade.options[cidade.selectedIndex].text;
        oParam.estado = option;
        oParam.exec = 'getCodigoIbge';
        var sUrlRpc = 'prot1_cadgeralmunic.RPC.php';

        var oAjax = new Ajax.Request(sUrlRpc, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_preencheCodigoIbge
        });
    }

    function js_preencheCodigoIbge(obj) {
        let oRetorno = JSON.parse(obj.responseText);

        if (oRetorno.codigo != null)
            document.getElementById('z01_ibge').value = oRetorno.codigo;
    }

    function js_getNaturezaJuridica() {
        let oParam = {};

        oParam.numcgm = document.getElementById('z01_numcgm').value;
        oParam.exec = 'getNaturezaJuridica';
        let sUrlRpc = 'prot1_cadgeralmunic.RPC.php';

        let oAjax = new Ajax.Request(sUrlRpc, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_preencheNaturezaJuridica
        });
    }

    function js_preencheNaturezaJuridica(obj) {
        let oRetorno = JSON.parse(obj.responseText);
        document.getElementById('z01_naturezajuridica').value = oRetorno.naturezajuridica;
        document.getElementById('z01_naturezajuridicadescr').value = oRetorno.naturezajuridica;
    }

    function js_mostraTabela(nome_tabela) {
        var tabela = document.getElementById(nome_tabela);

        if (tabela.style.display === "none") {
            tabela.style.display = "block";
            return;
        }
        tabela.style.display = "none";
    }

    function retornoLookupCnaeSecundario(medida, descricao, chave) {
        document.form1.q71_sequencial.value = medida;
        document.form1.q71_descr.value = descricao.slice(0, 85).urlDecode();
        document.form1.db_lanca.onclick = js_insSelectcnaes;
        db_iframe_cnae.hide();
    }

    function js_BuscaDadosArquivocnaes(chave) {
        document.form1.db_lanca.onclick = '';
        if (chave) {
            js_OpenJanelaIframe('', 'db_iframe_cnae',
                'func_cnae.php?funcao_js=parent.retornoLookupCnaeSecundario|q71_sequencial|q71_descr',
                'Pesquisa CNAE', true);
            return;
        }
        js_OpenJanelaIframe('', 'db_iframe_cnae', 'func_cnae.php?pesquisa_chave=' + document.form1.q71_sequencial.value + '&funcao_js=parent.retornoLookupCnaeSecundario', 'Pesquisa', false);
    }

    function js_buscarCnaes(cgm) {
        var oParametros = new Object();
        oParametros.exec = 'getCnaes';
        oParametros.cgm = cgm;
        var oAjaxLista = new Ajax.Request("prot1_cadgeralmunic.RPC.php", {
            method: "post",
            parameters: 'json=' + Object.toJSON(oParametros),
            onComplete: js_preencheCnaes
        });
    }

    function js_buscarCnaesSecundarios(cnaes) {
        document.getElementById("cnaes").innerHTML = "";
        cnaes.forEach(function(cnae, key) {
            js_buscarCnaeSecundario(cnae.codigo);
        });
    }

    function js_buscarCnaeSecundario(cnae) {
        var oParametros = new Object();
        oParametros.exec = 'getCnae';
        oParametros.cnae = cnae;
        var oAjaxLista = new Ajax.Request("prot1_cadgeralmunic.RPC.php", {
            method: "post",
            parameters: 'json=' + Object.toJSON(oParametros),
            onComplete: js_preencheCnaeSecundario
        });
    }

    function js_buscarCnae(cnae) {
        var oParametros = new Object();
        oParametros.exec = 'getCnae';
        oParametros.cnae = cnae;
        var oAjaxLista = new Ajax.Request("prot1_cadgeralmunic.RPC.php", {
            method: "post",
            parameters: 'json=' + Object.toJSON(oParametros),
            onComplete: js_preencheCnae
        });
    }

    function js_preencheCnae(oAjax) {
        oRetorno = eval("(" + oAjax.responseText + ")");
        $('z01_cnae').value = oRetorno.cnae;
        $('z01_cnaedescr').value = oRetorno.descricao.urlDecode();
    }

    function js_preencheCnaeSecundario(oAjax) {
        oRetorno = eval("(" + oAjax.responseText + ")");
        var select = document.getElementById("cnaes");
        var key = select.length;
        var sequencial = oRetorno.cnae;
        var descri = oRetorno.descricao.urlDecode();

        var option = new Option(descri.slice(0, 85).urlDecode(), sequencial);
        option.style = js_backgroundCnae(key);
        select.add(option);
    }

    function js_preencheCnaes(oAjax) {
        oRetorno = eval("(" + oAjax.responseText + ")");
        var select = document.getElementById("cnaes");

        oRetorno.cnaes.forEach(function(cnae, key) {
            var sequencial = cnae.q71_sequencial;
            var descri = cnae.q71_descr;
            var option = new Option(descri.slice(0, 85).urlDecode(), sequencial);
            option.style = js_backgroundCnae(key);
            select.add(option);
        });
    }

    function js_backgroundCnae(key) {
        if (key % 2 == 0) {
            return "background-color: rgb(248, 236, 7);";
        }
        return "background-color: rgb(215, 204, 6);";
    }

    function js_cadastrarFornecedor(){

        let oParam = {};

        oParam.numcgm = document.getElementById('z01_numcgm').value;
        oParam.exec = 'cadastrarFornecedor';
        let sUrlRpc = 'prot1_cadgeralmunic.RPC.php';

        let oAjax = new Ajax.Request(sUrlRpc, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: function(oAjax) {

                var oRetorno = eval('(' + oAjax.responseText + ')');

                if(oRetorno.abrirAbaCadastroFornecedor){
                    js_redirecionamentoCadastroFornecedor();
                    return;
                }

                if (oRetorno.status == 1) {
                    return alert(`Fornecedor ${document.getElementById('z01_numcgm').value} - ${document.getElementById('z01_nome').value} cadastrado com sucesso.`);
                }

                return alert(oRetorno.message.urlDecode());
            }
        });
    }

    function js_redirecionamentoCadastroFornecedor(){

        let title = 'DB:PATRIMONIAL > Licitações > Cadastros > Fornecedor > Inclusão';
        let action = `com1_pcforne001.php?pc60_numcgm=${document.getElementById('z01_numcgm').value}`;
        let areaId = 4;
        let moduloId = 381;
        let redirectionFileName = `com1_pcforne001.php`;

        js_redirectPage(title,action,areaId,moduloId,redirectionFileName);

    }

</script>
