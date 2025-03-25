<?php
require_once(modification("classes/db_empempenho_classe.php"));
require_once(modification("classes/db_cgm_classe.php"));
require_once(modification("classes/db_orctiporec_classe.php"));
require_once(modification("classes/db_orcdotacao_classe.php"));
require_once(modification("classes/db_orcorgao_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("classes/db_conlancamcgm_classe.php"));
require_once(modification("classes/db_conlancamval_classe.php"));
require_once(modification("classes/db_conlancam_classe.php"));
require_once(modification("classes/db_orcsuplem_classe.php"));
require_once(modification("classes/db_conlancamrec_classe.php"));
require_once(modification("classes/db_conlancamemp_classe.php"));
require_once(modification("classes/db_conlancamdot_classe.php"));
require_once(modification("classes/db_conlancamdig_classe.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("classes/db_conplano_classe.php"));

db_postmemory($_GET);

header('Content-Type: text/csv; charset=ISO-8859-1');
header('Content-Disposition: attachment; filename=razao_contas.csv');

$output = fopen('php://output', 'w');

$cabecalho = array(
    'Reduzido',
    'Estrutural',
    'Descrição',
    'Lançamento',
    'Sequêncial',
    'Data',
    'Receita',
    'Dotação',
    'Empenho',
    'Suplementação',
    'Documento',
    'Débito',
    'Crédito'
);

$clconlancam     = new cl_conlancam;
$clconlancamval  = new cl_conlancamval;
$clconlancamcgm  = new cl_conlancamcgm;
$clconlancamemp  = new cl_conlancamemp;
$clconlancamdot  = new cl_conlancamdot;
$clconlancamrec  = new cl_conlancamrec;
$clconlancamdig  = new cl_conlancamdig;
$clempempenho    = new cl_empempenho;
$clcgm           = new cl_cgm;
$clorcsuplem     = new cl_orcsuplem;
$clconplano      = new cl_conplano;
$clconplanoreduz = new cl_conplanoreduz;

$clconlancamcgm->rotulo->label();
$clconlancamval->rotulo->label();
$clconlancam->rotulo->label();
$clorcsuplem->rotulo->label();

$instit   = db_getsession("DB_instit");
$contaold = null;
$anousu   = db_getsession("DB_anousu");

if (empty($data1)) {
    $data1 = $anousu . "-01-01";
}
if (empty($data2)) {
    $data2 = $anousu . "-12-31";
}

$txt_where  = " 1 = 1 ";
$txt_where .= " and conplanoreduz.c61_instit =" . db_getsession("DB_instit");
if (!empty($lista)) {
    $txt_where = $txt_where . " and conplanoreduz.c61_reduz in ($lista)";
}

if (isset($estrut_inicial) && $estrut_inicial != '') {

    $txt_where .= " and conplano.c60_estrut like '$estrut_inicial%' ";
}

$sql_analitico  = $clconplanoreduz->sql_razaocontas($anousu,$txt_where);

$res = db_query($sql_analitico);

if (!$res) {
    die("Erro ao executar consulta: " . pg_last_error());
}

if (!empty($sDocumentos)) {
    $txt_where .= " and c53_coddoc in ($sDocumentos)     ";
}

for ($contas = 0; $contas < pg_numrows($res); $contas++) {
    db_fieldsmemory($res, $contas);

    $conta_atual = $c61_reduz;
    $txt_where2  = $txt_where . " and conplanoreduz.c61_reduz = $c61_reduz  and conplanoreduz.c61_instit = " . db_getsession("DB_instit");
    $txt_where2 .= " and c69_data between '$data1' and '$data2'  and conplanoreduz.c61_instit = " . db_getsession("DB_instit");

    $sql_analitico = $clconplanoreduz->sql_razaocontasregistros($anousu,$txt_where2);

    $reslista = db_query($sql_analitico);

    $iTotalRegistros = pg_num_rows($reslista);

    if (!$reslista) {

        echo "ERRO<br><br><br><br><br>";
        die($sql_analitico);
    }

    if (pg_numrows($reslista) > 0) {
        $tot_mov_debito = 0;
        $tot_mov_credito = 0;
        $total_saldo_final = 0;
        fputcsv($output, $cabecalho, ';');
        for ($x = 0; $x < pg_numrows($reslista); $x++) {
            db_fieldsmemory($reslista, $x);

            if ($tipo == "D") {
                $tot_mov_debito += $c69_valor;
            } else {
                $tot_mov_credito += $c69_valor;
            }

            $contrapartida_texto = "";
            if ($c61_reduz == $c69_debito) {
                $contrapartida_texto = "($c69_credito) $credito_descr";
            } else {
                $contrapartida_texto = "($c69_debito) $debito_descr";
            }

            $linha = array(
                $c61_reduz,
                $c60_estrut,
                $conta_descr,
                $c69_codlan,
                $c69_sequen,
                db_formatar($c69_data, 'd'),
                $c74_codrec,
                $c73_coddot,
                (!empty($e60_codemp) ? "{$e60_codemp}/{$e60_anousu}" : ""),
                $c79_codsup,
                "$c53_coddoc-$c53_descr",
                ($tipo == "D" ? db_formatar($c69_valor, 'f') : ""),
                ($tipo == "C" ? db_formatar($c69_valor, 'f') : ""),

            );

            fputcsv($output, $linha, ';');

            if ($contrapartida == "on") {
                $linha_contrapartida = array_fill(0, count($cabecalho), "");
                $linha_contrapartida[2] = "CONTRAPARTIDA: " .
                    ($c61_reduz == $c69_debito ?
                        "($c69_credito) $credito_descr" :
                        "($c69_debito) $debito_descr");
                fputcsv($output, $linha_contrapartida, ';');
            }

            if (!empty($planilha)) {
                $linhainfo[2] = "PLANILHA: {$planilha}";
                fputcsv($output, $linhainfo, ';');
            }

            if (!empty($slip)) {
                $linhaslip = array_fill(0, count($cabecalho), "");
                $linhaslip[2] = "SLIP: " . $slip;
                fputcsv($output, $linhaslip, ';');
            }

            if (isset($z01_numcgm) && $z01_numcgm != '') {
                $textocgm = " CGM: $z01_numcgm : $z01_nome, " . $txt;
            }
            $sHistorico = "HISTORICO: {$c50_descr} {$c72_complem} {$textocgm}";

            $linhainfo = array_fill(0, count($cabecalho), "");
            $linhainfo[2] = $sHistorico;
            fputcsv($output, $linhainfo, ';');
        }

        $linha_total = array_fill(0, count($cabecalho), "");
        $linha_total[2] = "TOTAIS DA MOVIMENTAÇÃO";
        $linha_total[11] = db_formatar($tot_mov_debito, 'f');
        $linha_total[12] = db_formatar($tot_mov_credito, 'f');
        fputcsv($output, $linha_total, ';');

        fputcsv($output, array_fill(0, count($cabecalho), ""), ';');
    }
    $contaold = null;
    if ($iTotalRegistros > 0 || $contasemmov == 's') {
        $contaold = $conta_atual;
    }
}

fclose($output);
