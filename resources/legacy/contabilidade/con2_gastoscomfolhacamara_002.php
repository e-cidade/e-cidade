<?

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("fpdf151/pdf.php");
include("libs/db_libcontabilidade.php");
require_once("libs/db_sessoes.php");

ini_set('display_errors', 'on');

class oData {

    private static $aData = [
        'sAnoUsu' => '',
        'sMes' => '',
        'iInstitCamara' => 0,
        'maxDias' => ''
    ];

    public static function get($chave) {
        return self::$aData[$chave] ?? null;
    }

    public static function set($chave, $valor) {
        self::$aData[$chave] = $valor;
    }
}

$aInstit           = getInstitCamara();
$iInstitCamara     = !empty($aInstit['codigo'])   ? $aInstit['codigo']   : null;
$sDescInstitCamara = !empty($aInstit['nomeinst']) ? $aInstit['nomeinst'] : null;

oData::set('sAnoUsu', db_getsession("DB_anousu"));
oData::set('sMes', str_pad($sMes, 2, '0', STR_PAD_LEFT));
oData::set('iInstitCamara', $iInstitCamara);

$sMes    = oData::get('sMes');
$sAnoUsu = oData::get('sAnoUsu');

$maxDias = diasNoMes($sAnoUsu, $sMes);
oData::set('maxDias', $maxDias);

$head2 = "GASTOS COM FOLHA - CÂMARA";
$head3 = "EXERCÍCIO " . $sAnoUsu;
$head4 = "PERÍODO : " . $sMes . " / " . $sAnoUsu;
$head5 = "INSTITUIÇÃO : " . '(' . $iInstitCamara . ') ' . $sDescInstitCamara;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head = "Relatrio de Gastos com folha - CÂMARA";

$pdf->AddPage("L");
$pdf->setfillcolor(235);

if (!empty($_GET)) {
    extract($_GET);
}

$pdf->setfont('arial', 'b', 10);
$pdf->ln();

$pdf->cell(199, 6, "DESCRIÇÃO", 1, 0, "C", 1);
$pdf->cell(40, 6, "VALOR NO MÊS", 1, 0, "C", 1);
$pdf->cell(40, 6, "VALOR ATÉ O MÊS", 1, 0, "C", 1);

$pdf->ln();
$pdf->setfont('arial', 'b', 11);
$pdf->SetFillColor(255, 255, 255);
$pdf->cell(279, 6, "Folha de Pagamento", 'LR', 0, "L", 1);
$pdf->ln();

$sqlMes         = getSqlMes();
$sqlAteOMes     = getSqlAteOMes();

$valorMes       = db_utils::getCollectionByRecord(db_query($sqlMes));  
$valorAteOmes   = db_utils::getCollectionByRecord(db_query($sqlAteOMes)); 

$aResultMes     = formatElementos($valorMes, 'mes');
$aResultAteOMes = formatElementos($valorAteOmes, 'ate_o_mes');

$aElementos = mergeArrays($aResultMes, $aResultAteOMes);

$repasseMes     = (float) getRepasseMes();
$repasseAteOMes = (float) getRepasseAteOmes();

$fTotalMes      = 0;
$fTotalAteOMes  = 0;

foreach ($aElementos as $key => $elem) {

    $elem['mes']['total']       = !empty($elem['mes']['total'])       ? $elem['mes']['total']       : 0.00;
    $elem['ate_o_mes']['total'] = !empty($elem['ate_o_mes']['total']) ? $elem['ate_o_mes']['total'] : 0.00;

    $fTotalMes     += $elem['mes']['total'];
    $fTotalAteOMes += $elem['ate_o_mes']['total'];

    if(!empty($elem['mes']['total']) || !empty($elem['ate_o_mes']['total'])) {
    
        $pdf->setfont('arial', '', 10); 
        $pdf->cell(199, 6, isset($elem['mes']['descricao']) ? $elem['mes']['descricao'] : $elem['ate_o_mes']['descricao'] , 'L', 0, "L", 1); 
        $pdf->cell(40, 6, db_formatar($elem['mes']['total'], 'f'), '', 0, "R", 1); 
        $pdf->cell(40, 6, db_formatar($elem['ate_o_mes']['total'], 'f'), 'R', 0, "R", 1);
    
        $pdf->ln();
    }
}

$repasse70Mes = (70 / 100) * $repasseMes;
$repasse70Ano = (70 / 100) * $repasseAteOMes;

$percentExecutadoMes     = $repasseMes > 0     ? ( $fTotalMes     / $repasseMes)     * 100 : 0;
$percentExecutadoAteOMes = $repasseAteOMes > 0 ? ( $fTotalAteOMes / $repasseAteOMes) * 100 : 0;

$pdf->setfont('arial', '', 10);
$pdf->cell(199, 6, 'Total', 'L', 0, "L", 1);
$pdf->cell(40, 6, db_formatar($fTotalMes, 'f'), '', 0, "R", 1);
$pdf->cell(40, 6, db_formatar($fTotalAteOMes, 'f'), 'R', 0, "R", 1);

$pdf->ln();
$pdf->cell(279, 6, '', 'LR', 0, "L", 1);

$pdf->ln();
$pdf->setfont('arial', 'b', 11);
$pdf->SetFillColor(255, 255, 255);
$pdf->cell(279, 6, "Repasse Recebido", 'LR', 0, "L", 1);
$pdf->ln();

$pdf->setfont('arial', '', 10);
$pdf->cell(199, 6, 'Repasse', 'L', 0, "L", 1);
$pdf->cell(40, 6, db_formatar($repasseMes, 'f'), '', 0, "R", 1);
$pdf->cell(40, 6, db_formatar($repasseAteOMes, 'f'), 'R', 0, "R", 1);

$pdf->ln();
$pdf->cell(279, 6, '', 'LR', 0, "L", 1);

$pdf->ln();
$pdf->setfont('arial', 'b', 11);
$pdf->SetFillColor(255, 255, 255);
$pdf->cell(279, 6, "Índices", 'LR', 0, "L", 1);
$pdf->ln();

$pdf->setfont('arial', '', 10);
$pdf->cell(199, 6, 'Limite de Gasto (70%)', 'L', 0, "L", 1);
$pdf->cell(40, 6, db_formatar($repasse70Mes, 'f'), '', 0, "R", 1);
$pdf->cell(40, 6, db_formatar($repasse70Ano, 'f'), 'R', 0, "R", 1);
$pdf->ln();

$pdf->setfont('arial', '', 10);
$pdf->cell(199, 6, 'Gasto Executado', 'L', 0, "L", 1);
$pdf->cell(40, 6, db_formatar($fTotalMes, 'f'), '', 0, "R", 1);
$pdf->cell(40, 6, db_formatar($fTotalAteOMes, 'f'), 'R', 0, "R", 1);
$pdf->ln();

$pdf->setfont('arial', '', 10);
$pdf->cell(199, 6, 'Percentual Executado', 'LB', 0, "L", 1);
$pdf->cell(40, 6, number_format($percentExecutadoMes, 2, ',', '.') . ' %', 'B', 0, "R", 1);
$pdf->cell(40, 6, number_format($percentExecutadoAteOMes, 2, ',', '.') . ' %', 'RB', 0, "R", 1);
$pdf->ln();

$pdf->Output();

function formatElementos($aElementos, $tipo) {

    $fullFormat = [];

    if (!empty($aElementos)) {

        foreach ($aElementos as $key => $elemento) {

            if(!empty($elemento->elemento)) {

                if(isset($fullFormat[$elemento->elemento])) {
                    $fullFormat[$elemento->elemento][$tipo]['total'] += (float) $elemento->total;
                }

                if (!isset($fullFormat[$elemento->elemento])) {
                    $fullFormat[$elemento->elemento][$tipo]['descricao'] = (string) $elemento->descricao;
                    $fullFormat[$elemento->elemento][$tipo]['total']     = (float)  $elemento->total;
                }
            }
        }

        return $fullFormat;
    }

    return null;
}

function getRepasseAteOmes() {

    $sAnoUsu       = oData::get('sAnoUsu');
    $iInstitCamara = oData::get('iInstitCamara');
    $sMes          = oData::get('sMes');
    $maxDias       = oData::get('maxDias');

    $where = " c61_instit in ({$iInstitCamara})";

    $sEstrutural = '4511202';

    $aWhereEstrutural[] = " p.c60_estrut like '{$sEstrutural}%' ";

    $where .= " and (" . implode(" OR ", $aWhereEstrutural) . ") ";

    $repasse = db_planocontassaldo_matriz($sAnoUsu, $sAnoUsu . '-01-01', $sAnoUsu . '-' . $sMes . '-' . $maxDias, false, $where, '', true, 'false');

    $result  = pg_fetch_all($repasse);

    if (!empty($result)) {
        foreach ($result as $key => $repasseRecebido) {
            if (trim($repasseRecebido['c60_descr']) == 'REPASSE RECEBIDO') {
                return $repasseRecebido['saldo_final'];
            }
        }
    }

    return null;
}

function getRepasseMes() {

    $sAnoUsu       = oData::get('sAnoUsu');
    $iInstitCamara = oData::get('iInstitCamara');
    $sMes          = oData::get('sMes');
    $maxDias       = oData::get('maxDias');

    $perini = $sAnoUsu . '-' . $sMes . '-01';
    $perfin = $sAnoUsu . '-' . $sMes . '-' . $maxDias;

    $where = " c61_instit in ({$iInstitCamara})";

    $sEstrutural = '4511202';

    $aWhereEstrutural[] = " p.c60_estrut like '{$sEstrutural}%' ";

    $where .= " and (" . implode(" OR ", $aWhereEstrutural) . ") ";

    $repasse = db_planocontassaldo_matriz($sAnoUsu, $perini, $perfin, false, $where, '', true, 'false');
    $result  = pg_fetch_all($repasse);

    if (!empty($result)) {
        foreach ($result as $key => $repasseRec) {
            if (trim($repasseRec['c60_descr']) == 'REPASSE RECEBIDO') {
                return ($repasseRec['saldo_anterior_credito'] - $repasseRec['saldo_anterior_debito']);
            }
        }
    }

    return null;
}

function getInstitCamara() {
    
    $sql = "SELECT 
                codigo, 
                nomeinst
            FROM 
                configuracoes.db_config
            WHERE 
                translate(lower(nomeinst), 'áàâãäéèêëíìîïóòôõöúùûüç', 'aaaaaeeeeiiiiooooouuuuc') ILIKE '%camara%'";

    $result = db_query($sql);
    $aResult = pg_fetch_assoc($result);

    if (!empty($aResult['codigo'])) {
        return $aResult;
    }

    return null;
}

function mergeArrays($array1, $array2) {

    $resultado = $array1;

    foreach ($array2 as $chave => $valor) {
        if (isset($resultado[$chave])) {
            $resultado[$chave] = array_merge_recursive($resultado[$chave], $valor);
        } else {
            $resultado[$chave] = $valor;
        }
    }

    uksort($resultado, function($a, $b) {
        return $a <=> $b;
    });

    return $resultado;
}

function diasNoMes($ano, $mes) {
    return cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
}

function getSqlMes() {

    $sMes          = oData::get('sMes');
    $iInstitCamara = oData::get('iInstitCamara');
    $sAnoUsu       = oData::get('sAnoUsu');
    
    $sql =	"SELECT
                elemento, 
                descricao, 
                sum(liquidado) as liquidado, 
                sum(estornado) as estornado, 
                mes,
                instit,
                sum(liquidado) - sum(estornado) as total
            FROM
                (select
                    e60_instit as instit,
                    orcelemento.o56_elemento as elemento,
                    CONCAT(substr(o56_elemento, 2, 8), ' - ', o56_descr) as descricao,
                    EXTRACT(MONTH FROM c70_data) AS mes,
                    sum(case when c53_tipo = 20 then c70_valor else 0 end ) as liquidado,
                    sum(case when c53_tipo = 21 then c70_valor else 0 end ) as estornado
                FROM 
                        conlancamemp
                    JOIN conlancamele ON c67_codlan = c75_codlan
                    JOIN conlancamdoc ON c71_codlan = c75_codlan
                    JOIN conlancam on c67_codlan = c70_codlan        
                    JOIN empempenho on e60_numemp = c75_numemp and e60_anousu = c70_anousu
                    JOIN empelemento ON e60_numemp = e64_numemp
                    JOIN orcelemento ON o56_codele = c67_codele and o56_anousu = c70_anousu
                    JOIN conplanoconplanoorcamento ON c72_conplanoorcamento = o56_codele and c72_anousu = c70_anousu
                    JOIN conplano ON c60_codcon = c72_conplano and c72_anousu = c60_anousu
                    join conplanoreduz on c61_codcon = c60_codcon and c61_anousu = c70_anousu and c61_instit = e60_instit
                    JOIN conhistdoc ON c53_coddoc = c71_coddoc
                WHERE
                    c53_tipo IN (20, 21) and 
                    substr(o56_elemento, 1, 3)::int4 = 331
                    AND substr(o56_elemento, 2, 6)::int4 NOT IN (319013, 319113, 319091, 319191)
                    AND c70_anousu = {$sAnoUsu}
                    AND e60_instit = {$iInstitCamara}
                    AND EXTRACT(MONTH FROM c70_data) = {$sMes}  
            GROUP by elemento, c70_valor, mes, instit, descricao
                ) as x 
                GROUP by instit,mes,elemento , descricao;";

    return $sql;
}

function getSqlAteOMes() {

    $sMes          = oData::get('sMes');
    $iInstitCamara = oData::get('iInstitCamara');
    $sAnoUsu       = oData::get('sAnoUsu');
        
    $sql =	"SELECT
                elemento, 
                descricao, 
                sum(liquidado) as liquidado, 
                sum(estornado) as estornado, 
                mes,
                instit,
                sum(liquidado) - sum(estornado) as total
            FROM
                (select
                    e60_instit as instit,
                    orcelemento.o56_elemento as elemento,
                    CONCAT(substr(o56_elemento, 2, 8), ' - ', o56_descr) as descricao,
                    EXTRACT(MONTH FROM c70_data) AS mes,
                    sum(case when c53_tipo = 20 then c70_valor else 0 end ) as liquidado,
                    sum(case when c53_tipo = 21 then c70_valor else 0 end ) as estornado
                FROM 
                        conlancamemp
                    JOIN conlancamele ON c67_codlan = c75_codlan
                    JOIN conlancamdoc ON c71_codlan = c75_codlan
                    JOIN conlancam on c67_codlan = c70_codlan        
                    JOIN empempenho on e60_numemp = c75_numemp and e60_anousu = c70_anousu
                    JOIN empelemento ON e60_numemp = e64_numemp
                    JOIN orcelemento ON o56_codele = c67_codele and o56_anousu = c70_anousu
                    JOIN conplanoconplanoorcamento ON c72_conplanoorcamento = o56_codele and c72_anousu = c70_anousu
                    JOIN conplano ON c60_codcon = c72_conplano and c72_anousu = c60_anousu
                    join conplanoreduz on c61_codcon = c60_codcon and c61_anousu = c70_anousu and c61_instit = e60_instit
                    JOIN conhistdoc ON c53_coddoc = c71_coddoc
                WHERE
                    c53_tipo IN (20, 21) and 
                    substr(o56_elemento, 1, 3)::int4 = 331
                    AND substr(o56_elemento, 2, 6)::int4 NOT IN (319013, 319113, 319091, 319191)
                    AND c70_anousu = {$sAnoUsu}
                    AND e60_instit = {$iInstitCamara}
                    AND EXTRACT(MONTH FROM c70_data) <= {$sMes}  
            GROUP by elemento, c70_valor, mes, instit, descricao
                ) as x 
                GROUP by instit,mes,elemento , descricao;";

    return $sql;
}