<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_varfixval_classe.php");
require_once("classes/db_issnotaavulsa_classe.php");
require_once("classes/db_isstipoalvara_classe.php");
require_once("classes/db_numpref_classe.php");
require_once("libs/db_utils.php");
require_once("dbforms/verticalTab.widget.php");
require_once("classes/db_cgm_classe.php");

$clnumpref = new cl_numpref();
$clissbase = new cl_issbase();
$clisstipoalvara = new cl_isstipoalvara();
$clcgm = new cl_cgm();

$clcgm->rotulo->label("z01_nome");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$iCodigoIncricao = $numeroDaInscricao;
$sDisplayNone = "";

/**
 * Retorna se o Alvara pode ser Impresso
 */
$sSqlTipoAlvara = " select q98_permiteimpressao                                                                  ";
$sSqlTipoAlvara .= "  from isstipoalvara                                                                          ";
$sSqlTipoAlvara .= "       inner join issalvara    on issalvara.q123_isstipoalvara = isstipoalvara.q98_sequencial ";
$sSqlTipoAlvara .= "       inner join issmovalvara on issmovalvara.q120_issalvara  = issalvara.q123_sequencial    ";
$sSqlTipoAlvara .= " where q123_inscr = {$iCodigoIncricao} ";
$rsTipoAlvara = $clisstipoalvara->sql_record($sSqlTipoAlvara);
$iLinhasMovimentacaoAlvara = $clisstipoalvara->numrows;
if ($iLinhasMovimentacaoAlvara > 0) {
    $oTipoAlvara = db_utils::fieldsMemory($rsTipoAlvara, 0);

    if ($oTipoAlvara->q98_permiteimpressao == "f") {
        $sDisplayNone = "display:none;";
    }
} else {
    $sDisplayNone = "display:none;";
}

/**
 * Sql que retorna os dados básicos da inscrição
 */
$sSqlDadosInscricao = " select distinct issbase.*,                                                                                       \n";
$sSqlDadosInscricao .= "        case when (cgm_inscr.z01_nomecomple is null or trim(cgm_inscr.z01_nomecomple) = '')              \n";
$sSqlDadosInscricao .= "          then cgm_inscr.z01_nome                                                                        \n";
$sSqlDadosInscricao .= "          else cgm_inscr.z01_nomecomple                                                                  \n";
$sSqlDadosInscricao .= "        end as z01_nome,                                                                                 \n";
$sSqlDadosInscricao .= "        cgm_inscr.z01_ender,                                                                             \n";
$sSqlDadosInscricao .= "        cgm_inscr.z01_cgccpf,                                                                            \n";
$sSqlDadosInscricao .= "        cgm_inscr.z01_nomefanta,                                                                         \n";
$sSqlDadosInscricao .= "        cgm_inscr.z01_telef,                                                                             \n";
$sSqlDadosInscricao .= "        cgm_inscr.z01_incest,                                                                            \n";
$sSqlDadosInscricao .= "        cgm_inscr.z01_email,                                                                             \n";
$sSqlDadosInscricao .= "        cgm_inscr.z01_numero,                                                                            \n";
$sSqlDadosInscricao .= "        cgm_inscr.z01_compl,                                                                             \n";
$sSqlDadosInscricao .= "        cgm_inscr.z01_bairro,                                                                            \n";
$sSqlDadosInscricao .= "        cgm_inscr.z01_munic,                                                                             \n";
$sSqlDadosInscricao .= "        cgm_inscr.z01_uf,                                                                                \n";
$sSqlDadosInscricao .= "        cgm_escr.z01_nome as escritorio,                                                                 \n";
$sSqlDadosInscricao .= "        j14_nome,                                                                                        \n";
$sSqlDadosInscricao .= "        j13_descr,                                                                                       \n";
$sSqlDadosInscricao .= "        q02_numero,                                                                                      \n";
$sSqlDadosInscricao .= "        q02_compl,                                                                                       \n";
$sSqlDadosInscricao .= "        q05_matric,                                                                                      \n";
$sSqlDadosInscricao .= "        (SELECT q98_descricao
                                   FROM isstipoalvara
                                   INNER JOIN issalvara ON issalvara.q123_isstipoalvara = q98_sequencial
                                   INNER JOIN issmovalvara ON q123_sequencial = q120_issalvara
                                   WHERE q123_inscr = issbase.q02_inscr
                                   ORDER BY q120_sequencial DESC
                                   LIMIT 1),                                                                                       \n";
$sSqlDadosInscricao .= "        q14_proces,                                                                                      \n";
$sSqlDadosInscricao .= "        p58_dtproc,                                                                                      \n";
$sSqlDadosInscricao .= "        extract (year from p58_dtproc)::integer as p58_ano,                                              \n";
$sSqlDadosInscricao .= "        q45_codporte,                                                                                    \n";
$sSqlDadosInscricao .= "        q40_descr,                                                                                       \n";
$sSqlDadosInscricao .= "        j88_sigla,                                                                                       \n";
$sSqlDadosInscricao .= "        db98_descricao as tipo,                                                                          \n";
$sSqlDadosInscricao .= "        q179_inscricaoredesim                                                                            \n";
$sSqlDadosInscricao .= "   from issbase                                                                                          \n";
$sSqlDadosInscricao .= "        inner      join cgm cgm_inscr      on cgm_inscr.z01_numcgm         = q02_numcgm                  \n";
$sSqlDadosInscricao .= "        left       join cgmtipoempresa     on cgm_inscr.z01_numcgm         = z03_numcgm                  \n";
$sSqlDadosInscricao .= "        left       join tipoempresa        on cgmtipoempresa.z03_tipoempresa    = db98_sequencial        \n";
$sSqlDadosInscricao .= "        left outer join issruas            on issbase.q02_inscr            = issruas.q02_inscr           \n";
$sSqlDadosInscricao .= "        left outer join ruas               on ruas.j14_codigo              = issruas.j14_codigo          \n";
$sSqlDadosInscricao .= "        left outer join ruastipo           on ruas.j14_tipo                = ruastipo.j88_codigo         \n";
$sSqlDadosInscricao .= "        left outer join issbairro          on issbase.q02_inscr            = q13_inscr                   \n";
$sSqlDadosInscricao .= "        left outer join bairro             on j13_codi                     = q13_bairro                  \n";
$sSqlDadosInscricao .= "        left outer join escrito            on issbase.q02_inscr            = q10_inscr                   \n";
$sSqlDadosInscricao .= "        left outer join cgm cgm_escr       on cgm_escr.z01_numcgm          = q10_numcgm                  \n";
$sSqlDadosInscricao .= "        left outer join issmatric          on issbase.q02_inscr            = q05_inscr                   \n";
$sSqlDadosInscricao .= "        left outer join issprocesso        on issbase.q02_inscr            = q14_inscr                   \n";
$sSqlDadosInscricao .= "              left join protprocesso       on issprocesso.q14_proces       = protprocesso.p58_codproc    \n";
$sSqlDadosInscricao .= "              left join meicgm             on meicgm.q115_numcgm           = issbase.q02_numcgm          \n";
$sSqlDadosInscricao .= "              left join issalvara          on issalvara.q123_inscr         = issbase.q02_inscr           \n";
$sSqlDadosInscricao .= "              left join isstipoalvara      on isstipoalvara.q98_sequencial = issalvara.q123_isstipoalvara\n";
$sSqlDadosInscricao .= "              left join issqn.issbaseporte on q45_inscr                    = issbase.q02_inscr           \n";
$sSqlDadosInscricao .= "              left join issqn.issporte     on q40_codporte                 = q45_codporte                \n";
$sSqlDadosInscricao .= "              left join issqn.inscricaoredesim     on q179_inscricao       = issbase.q02_inscr                \n";
if (isset($numeroDaInscricao)) {
    $sSqlDadosInscricao .= " where issbase.q02_inscr = $numeroDaInscricao ";
} elseif (isset($referenciaanterior)) {
    $sSqlDadosInscricao .= " where issbase.q02_inscmu ilike '$referenciaanterior' ";
}elseif (isset($inscricaoredesim)) {
    $sSqlDadosInscricao .= " where inscricaoredesim.q179_inscricaoredesim = '$inscricaoredesim' ";
}

$rsSqlDadosInscricao = db_query($sSqlDadosInscricao);
$iNumRowsIncricao = pg_numrows($rsSqlDadosInscricao);

if (isset($referenciaanterior) || isset($inscricaoredesim) ) {

    $numeroDaInscricao = null;

    if ($iNumRowsIncricao == 1) {
        $numeroDaInscricao = pg_result($rsSqlDadosInscricao, 0, "q02_inscr");
    }
    $iCodigoIncricao = $numeroDaInscricao;
}

$sSqlNumpref = $clnumpref->sql_query_file(db_getsession("DB_anousu"),
    db_getsession("DB_instit"),
    "k03_certissvar,
                                                 k03_regracnd,
                                                 k03_tipocertidao"
);
$rsSqlNumpref = $clnumpref->sql_record($sSqlNumpref);
$iNumRowsNumpref = $clnumpref->numrows;

if ($iNumRowsNumpref !== 0) {
    $oParmNumpref = db_utils::fieldsMemory($rsSqlNumpref, 0);
}

/**
 * Define o tipo de certidao
 *   regular
 *   positiva
 *   negativa
 */
$sWhereIssvar = ($oParmNumpref->k03_certissvar == 't' ? " k00_valor <> 0 " : "");
$dtBase = date('Y-m-d', db_getsession('DB_datausu'));
$sSqlCertid = "select *                                              ";
$sSqlCertid .= "  from fc_tipocertidao($iCodigoIncricao,              ";
$sSqlCertid .= "                       'i',                           ";
$sSqlCertid .= "                       '{$dtBase}',                   ";
$sSqlCertid .= "                       '{$sWhereIssvar}',             ";
$sSqlCertid .= "                        {$oParmNumpref->k03_regracnd} ";
$sSqlCertid .= "                      )                               ";
$rsSqlCertid = $clnumpref->sql_record($sSqlCertid);

if ($rsSqlCertid !== false) {
    $sCertidao = db_utils::fieldsMemory($rsSqlCertid, 0)->fc_tipocertidao;
}

/**
 * Retorna parametro da tabela parissqn
 */
$sSqlParIssqn = "select q60_bloqemiscertbaixa from parissqn ";
$rsSqlParIssqn = $clnumpref->sql_record($sSqlParIssqn);
if ($rsSqlParIssqn !== false) {
    $iBloqueioEmissaoCertidaoBaixa = db_utils::fieldsMemory($rsSqlParIssqn, 0)->q60_bloqemiscertbaixa;
}

/**
 * Valida se é regular, negativa ou positiva
 */
$iAvisoCertidao = 1;
if ($sCertidao != "negativa" && $iBloqueioEmissaoCertidaoBaixa == 2) {
    $iAvisoCertidao = 2;
} else if ($sCertidao != "negativa" && $iBloqueioEmissaoCertidaoBaixa == 3) {
    $iAvisoCertidao = 3;
}

/**
 * Retorna percentual dos socios
 */
$sSqlSocios = $clissbase->sqlinscricoes_socios($iCodigoIncricao, 0, "q95_perc");
$rsSqlSocios = $clissbase->sql_record($sSqlSocios);
$iNumRowsSocios = $clissbase->numrows;
$nPercentual = 0;
if ($iNumRowsSocios > 0) {
    $aPercentual = db_utils::getCollectionByRecord($rsSqlSocios);
    foreach ($aPercentual as $oSocios) {
        $nPercentual += $oSocios->q95_perc;
    }
}
?>
<html>
<head>
    <title>Dados da Inscri&ccedil;&atilde;o - BCI</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <style>
        .tableLabel {
            font-weight: bold;
        }

        .tableValue {
            background-color: #fff;
            border: 1px solid lightgray;
        }

        .details {
            height: 63%;
        }

        .tableDetails {
            height: 100%;
        }
    </style>
</head>
<body>

<?php
/**
 * valida se a inscrição passada é valida
 */
if ($iCodigoIncricao > 0) {
    db_fieldsmemory($rsSqlDadosInscricao, 0, 1);
    $oDadosInscricao = db_utils::fieldsMemory($rsSqlDadosInscricao, 0, true);
    ?>
    <fieldset>
        <legend>
            <b>Cadastro Municipal</b>
            <?php
            if (!empty($oDadosInscricao->q02_dtbaix)) {
                echo "<span class='aviso'>
                    <span style='color: red'><b>Inscrição Baixada</b></span>
                  </span>";
            }
            ?>
        </legend>
        <fieldset>
            <legend>Dados Cadastrais da Inscrição Municipal</legend>
            <table>
                <tr>
                    <th style="width: 100px"></th>
                    <th style="width: 300px"></th>
                    <th style="width: 100px"></th>
                    <th style="width: 300px"></th>
                </tr>
                <tr>
                    <td nowrap class="tableLabel">Número Inscrição:</td>
                    <td nowrap class="tableValue">

                        <?= $iCodigoIncricao . " - CGM: " . $oDadosInscricao->q02_numcgm ?>

                    </td>
                    <td nowrap class="tableLabel">Inscrição REDESIM:</td>
                    <td nowrap class="tableValue">
                        <?= $oDadosInscricao->q179_inscricaoredesim ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap class="tableLabel">
                        <?= db_ancora($Lz01_nome, "js_JanelaAutomatica('cgm','$oDadosInscricao->q02_numcgm')", 2) . ":" ?>
                    </td>
                    <td nowrap class="tableValue">
                        <?= $oDadosInscricao->z01_nome ?>
                    </td>
                    <td nowrap class="tableLabel">CNPJ/CPF:</td>
                    <td nowrap class="tableValue">
                        <?= $oDadosInscricao->z01_cgccpf ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap class="tableLabel">Email:</td>
                    <td nowrap class="tableValue">
                        <?= @$z01_email ?>
                    </td>
                    <td nowrap class="tableLabel">Referência anterior:</td>
                    <td nowrap class="tableValue">
                        <?= $oDadosInscricao->q02_inscmu ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap class="tableLabel">Nome fantasia:</td>
                    <td nowrap class="tableValue">
                        <?= $oDadosInscricao->z01_nomefanta ?>
                    </td>
                    <td nowrap class="tableLabel">Data inicial:</td>
                    <td nowrap class="tableValue">
                        <?= $oDadosInscricao->q02_dtinic ?>
                    </td>
                </tr>

                <tr>
                    <td nowrap class="tableLabel">Registro na junta:</td>
                    <td nowrap class="tableValue">
                        <?= $oDadosInscricao->q02_regjuc ?>
                    </td>
                    <td nowrap class="tableLabel">Data do cadastro:</td>
                    <td nowrap class="tableValue">
                        <?= $oDadosInscricao->q02_dtcada ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap class="tableLabel">Capital social:</td>
                    <td nowrap class="tableValue">

                        <?= (trim($oDadosInscricao->q02_capit) == 0 ? $nPercentual : $oDadosInscricao->q02_capit); ?>

                    </td>
                    <td nowrap class="tableLabel">Data da junta:</td>
                    <td nowrap class="tableValue">
                        <?= $oDadosInscricao->q02_dtjunta ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap class="tableLabel">Inscrição Estadual:</td>
                    <td nowrap class="tableValue">

                        <?= $oDadosInscricao->z01_incest ?>

                    </td>
                    <td nowrap class="tableLabel">Telefone:</td>
                    <td nowrap class="tableValue">

                        <?= $oDadosInscricao->z01_telef ?>

                    </td>
                </tr>
                <tr>
                    <td nowrap class="tableLabel">n&deg; (Inscrição):</td>
                    <td nowrap class="tableValue">
                        <?= $oDadosInscricao->q02_numero ?>
                    </td>
                    <td nowrap class="tableLabel">Processo:</td>
                    <td nowrap class="tableValue">

                        <?= "$oDadosInscricao->q14_proces/$oDadosInscricao->p58_ano - $oDadosInscricao->p58_dtproc" ?>

                    </td>
                </tr>
                <tr>
                    <td nowrap class="tableLabel">Tipo de Alvará:</td>
                    <td nowrap class="tableValue">

                        <?= !empty($iLinhasMovimentacaoAlvara) ? $oDadosInscricao->q98_descricao : "" ?>

                    </td>
                    <td nowrap class="tableLabel">Tipo:</td>
                    <td nowrap class="tableValue">
                        <?= $oDadosInscricao->tipo ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap class="tableLabel">Ultima alteração:</td>
                    <td nowrap class="tableValue">
                        <?= $oDadosInscricao->q02_dtalt ?>
                    </td>
                    <td nowrap class="tableLabel">Porte:</td>
                    <td nowrap class="tableValue">
                        <?= "$oDadosInscricao->q45_codporte - $oDadosInscricao->q40_descr" ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap class="tableLabel">Escritório:</td>
                    <td nowrap class="tableValue">
                        <?= $oDadosInscricao->escritorio ?>
                    </td>
                    <td nowrap class="tableLabel">Data da baixa:</td>
                    <td nowrap class="tableValue">
                        <?= $oDadosInscricao->q02_dtbaix ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend>Endereço CGM</legend>
            <table>
                <tr>
                    <th style="width: 100px"></th>
                    <th style="width: 300px"></th>
                    <th style="width: 100px"></th>
                    <th style="width: 300px"></th>
                </tr>
                <tr>
                    <td nowrap class="tableLabel">Logradouro:</td>
                    <td nowrap class="tableValue">

                        <?= $oDadosInscricao->z01_ender ?>

                    </td>
                    <td nowrap class="tableLabel">Numero:</td>
                    <td nowrap class="tableValue">
                        <?= $oDadosInscricao->z01_numero ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap class="tableLabel">Bairro:</td>
                    <td nowrap class="tableValue">
                        <?= $z01_bairro ?>
                    </td>
                    <td nowrap class="tableLabel">Complemento:</td>
                    <td nowrap class="tableValue">

                        <?= $oDadosInscricao->z01_compl ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap class="tableLabel">Municipio/UF:</td>
                    <td nowrap class="tableValue">
                        <?= "$z01_munic/$z01_uf" ?>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend>Endereço Inscrição</legend>
            <table>
                <tr>
                    <th style="width: 100px"></th>
                    <th style="width: 300px"></th>
                    <th style="width: 100px"></th>
                    <th style="width: 300px"></th>
                </tr>
                <tr>
                    <td nowrap class="tableLabel">Logradouro:</td>
                    <td nowrap class="tableValue">
                        <?= $oDadosInscricao->j88_sigla . " - " . $oDadosInscricao->j14_nome ?>
                    </td>
                    <td nowrap class="tableLabel">Bairro:</td>
                    <td nowrap class="tableValue">
                        <?= $oDadosInscricao->j13_descr ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap class="tableLabel">Complemento:</td>
                    <td nowrap class="tableValue">
                        <?= $oDadosInscricao->q02_compl ?>
                    </td>
                    <td nowrap class="tableLabel">Matrícula:</td>
                    <td nowrap class="tableValue">
                        <?= $oDadosInscricao->q05_matric ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    </fieldset>
    <fieldset class="details">
        <legend>
            <b>Detalhamento</b>
        </legend>
        <?php
        $oTabDetalhes = new verticalTab("detalhesInscricao", '100%', 20, 'tableDetails');

        if (!empty($q02_dtbaix)) {
            $oTabDetalhes->add("DadosBaixa", "Dados da Baixa",
                "iss3_consinscr003_detalhes.php?solicitacao=Baixa&inscricao=" . $iCodigoIncricao, empty($q02_dtbaix));
        }

        $oTabDetalhes->add("Atividades", "Atividades",
            "iss3_consinscr003_detalhes.php?solicitacao=Atividades&inscricao=" . $iCodigoIncricao, empty($q02_dtbaix));

        $oTabDetalhes->add("Socios", "Sócios",
            "iss3_consinscr003_detalhes.php?solicitacao=Socios&inscricao=" . $iCodigoIncricao);

        $oTabDetalhes->add("Redesim", "Dados Redesim",
            "iss3_consinscr003_redesim.php?inscricao=" . $iCodigoIncricao);

        $oTabDetalhes->add("AlteracoesCadastrais", "Alterações cadastrais",
            "iss1_issbaselog_consulta.php?q102_inscr=" . $iCodigoIncricao);

        $oTabDetalhes->add("TiposDeCalculo", "Tipos de Calculo",
            "iss3_consinscr003_detalhes.php?solicitacao=TiposDeCalculo&inscricao=" . $iCodigoIncricao);

        $oTabDetalhes->add("Quantidades", "Quantidades",
            "iss3_consinscr003_detalhes.php?solicitacao=Quantidades&inscricao=" . $iCodigoIncricao);

        $oTabDetalhes->add("Fixado", "Fixado",
            "iss3_consinscr003_detalhes.php?solicitacao=Fixado&inscricao=" . $iCodigoIncricao);

        $oTabDetalhes->add("Observacoes", "Observacoes",
            "iss3_consinscr003_detalhes.php?solicitacao=Observacoes&inscricao=" . $iCodigoIncricao);

        $oTabDetalhes->add("TextoAlvara", "Texto Alvara",
            "iss3_consinscr003_detalhes.php?solicitacao=TextoAlvara&inscricao=" . $iCodigoIncricao);

        $oTabDetalhes->add("Ocorrencias", "Ocorrencias",
            "iss3_consinscr003_detalhes.php?solicitacao=Ocorrencias&inscricao=" . $iCodigoIncricao);

        $oTabDetalhes->add("DemonstrativoCalculo", "Demonstrativo do Calculo",
            "iss3_consinscr003_detalhes.php?solicitacao=Manual&inscricao=" . $iCodigoIncricao);

        $oTabDetalhes->add("SIMPLES", "Optante Simples",
            "iss3_consinscr003_detalhes.php?solicitacao=OptanteSimples&inscricao=" . $iCodigoIncricao);

        $oTabDetalhes->add("MovimentacaoAlvara", "Movimentações",
            "iss3_consinscr003_movimentacao.php?inscricao=" . $iCodigoIncricao);

        $clissnotaavulsa = new cl_issnotaavulsa();
        $clissnotaavulsa->sql_record($clissnotaavulsa->sql_query(null, "*", "q51_numnota", "q51_inscr = $iCodigoIncricao"));
        if ($clissnotaavulsa->numrows > 0) {
            $oTabDetalhes->add("NotasAvulsas", "Notas Avulsas",
                "iss3_issnotaavulsa002.php?inscr=" . $iCodigoIncricao);
        }

        $oTabDetalhes->add("ImprimeBIC", "Imprime BIC",
            "javascript:");

        if (db_permissaomenu(db_getsession("DB_anousu"), 40, 9740) == "true") {
            $oTabDetalhes->add("ImprimeAlvara", "Imprime Alvará",
                "javascript:");
        }

        if ($q02_dtbaix != "") {
            $oTabDetalhes->add("ImprimeCertidaoBaixa", "Imprimir Certidão de Baixa",
                "javascript:");
        }

        $sql = "select * from varfix inner join varfixval on q33_codigo=q34_codigo and q33_inscr=$iCodigoIncricao";
        $varfix = db_query($sql);
        $numrows = pg_numrows($varfix);
        if ($numrows > 0) {
            $oTabDetalhes->add("ImprimeTermo", "Imprime Termo",
                "javascript:");
        }

        $oTabDetalhes->show();
        ?>
    </fieldset>
    <script>

        const iBloqueioCertidao = <?=$iAvisoCertidao; ?>

        function js_ImpressaoAlvara() {
            console.log('aqui');
            window.open('iss3_consinscr003_imprimealvara.php?inscricao=<?=$iCodigoIncricao?>', '', 'location=0,HEIGHT=600,WIDTH=600');
        }

        function js_ImpressaoBIC() {
            window.open('iss2_imprimebiciss.php?inscr=<?=$iCodigoIncricao?>', '', 'location=0,HEIGHT=600,WIDTH=600');
        }

        function js_Imprimebaix() {

            if (iBloqueioCertidao === 2) {
                alert(_M("tributario.issqn.iss3_consinscr003.certidaoavisodebito"));
            } else if (iBloqueioCertidao === 3) {
                alert(_M("tributario.issqn.iss3_consinscr003.bloqueiocertidao"));
                return false;
            }
            window.open('iss2_certibaixa002.php?inscr=<?=$iCodigoIncricao?>', '', 'location=0,HEIGHT=600,WIDTH=600');
        }

        function js_Imprimetermo() {
            window.open('iss2_imprimetermo001.php?inscr=<?=$iCodigoIncricao?>', '', 'location=0,HEIGHT=600,WIDTH=600');
        }

        $('ImprimeBIC').observe("click", function () {
            js_ImpressaoBIC();
        });

        if ($('ImprimeCertidaoBaixa') !== null) {
            $('ImprimeCertidaoBaixa').on("click", function () {
                js_Imprimebaix();
            });
        }

        if ($('ImprimeAlvara') !== null) {
            $('ImprimeAlvara').observe("click", function () {
                js_ImpressaoAlvara();
            });
        }

        if ($('ImprimeTermo') !== null) {
            $('ImprimeTermo').observe("click", function () {
                js_Imprimetermo();
            });
        }

    </script>
<?php
} else {  // caso nao tenha retornado nenhum registro é mostrado uma tabela informando que a matricula nao foi localizada
?>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center"></td>
        </tr>
        <tr>
            <td align="center">Pesquisa da inscricao n&deg;
                <?= $iCodigoIncricao ?>
                n&atilde;o retornou nenhum registro.
            </td>
        </tr>
    </table>
    <?php
} // fim da verificacao
?>
</body>
</html>
