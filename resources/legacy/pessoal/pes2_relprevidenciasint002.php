<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

include("fpdf151/pdf.php");
include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$sql_prev = "select r33_ppatro,r33_nome from inssirf 
                 where r33_anousu = $ano 
                 and r33_mesusu = $mes 
                 and r33_instit = " . db_getsession("DB_instit") . "
                 and r33_codtab = $prev+2 limit 1;";
$res_prev = db_query($sql_prev);
$dadosPrevidencia  = db_utils::fieldsMemory($res_prev, 0);

$head2 = "RELATÓRIO " . strtoupper($dadosPrevidencia->r33_nome);
$head4 = "PERÍODO  : " . $mes . " / " . $ano;

if ($ordem == 'A') {
    $xordem = ' order by z01_nome';
} else {
    $xordem = ' order by r14_regist';
}

if ($sembase == 'S') {
    $xsembase = '';
} else {
    $xsembase = ' and base > 0 ';
}
//echo '<br> vinculo --> '.$vinculo;
if ($vinculo == 'A') {
    $where_vinc = " and rh30_vinculo = 'A'";
    $head7 = 'VINCULO : ATIVOS';
} elseif ($vinculo == 'I') {
    $where_vinc = " and rh30_vinculo = 'I'";
    $head7 = 'VINCULO : INATIVOS';
} elseif ($vinculo == 'P') {
    $where_vinc = " and rh30_vinculo = 'P'";
    $head7 = 'VINCULO : PENSIONISTAS';
} elseif ($vinculo == 'IP') {
    $where_vinc = " and rh30_vinculo <> 'A'";
    $head7 = 'VINCULO : INATIVOS/PENSIONISTAS';
} else {
    $where_vinc = '';
    $head7 = 'VINCULO : TODOS';
}

$rubric = 'R993';
$aArquivo = array();
if ($folha == 'r14') {
    $head5 = "PATRONAL : " . $dadosPrevidencia->r33_ppatro . "% - SALÁRIO";
    $aArquivo[$folha] = 'gerfsal';
} elseif ($folha == 'r35') {
    $head5 = "PATRONAL : " . $dadosPrevidencia->r33_ppatro . "% - 13o. SALÁRIO";
    $aArquivo[$folha] = 'gerfs13';
} elseif ($folha == 'r48') {
    $head5 = "PATRONAL : " . $dadosPrevidencia->r33_ppatro . "% - COMPLEMENTAR";
    $aArquivo[$folha] = 'gerfcom';
} elseif ($folha == 'r20') {
    $head5 = "PATRONAL : " . $dadosPrevidencia->r33_ppatro . "% - RESCISÃO";
    $aArquivo[$folha] = 'gerfres';
} elseif ($folha == 'todas') {
    $head5 = "PATRONAL : " . $dadosPrevidencia->r33_ppatro . "% - TODOS";
    $aArquivo = array(
        "r14" => "gerfsal",
        "r48" => "gerfcom",
        "r20" => "gerfres",
        "r35" => "gerfs13"
    );
} elseif ($folha == 'todasexc13') {
    $head5 = "PATRONAL : " . $dadosPrevidencia->r33_ppatro . "% - TODOS, EXCETO 13o";
    $aArquivo = array(
        "r14" => "gerfsal",
        "r48" => "gerfcom",
        "r20" => "gerfres"
    );
}

$instit = db_getsession('DB_instit');
$sJoinFolha = "(SELECT r14_regist AS r14_regist,
                       SUM(proventos) AS proventos,
                       SUM(base) AS base,
                       SUM(segurado) AS segurado
                FROM (";

$aJoinFolha = array();
foreach ($aArquivo as $sigla => $arquivo) {
    $aJoinFolha[] = " 
        SELECT {$sigla}_regist AS r14_regist,
               SUM(CASE WHEN {$sigla}_pd = 1 THEN {$sigla}_valor ELSE 0 END) AS proventos,
               SUM(CASE WHEN {$sigla}_rubric = 'R992' THEN {$sigla}_valor ELSE 0 END) AS base,
               SUM(CASE WHEN {$sigla}_rubric = 'R993' THEN {$sigla}_valor ELSE 0 END) AS segurado
        FROM {$arquivo}
        WHERE {$sigla}_anousu = $ano
          AND {$sigla}_mesusu = $mes
          AND {$sigla}_instit = $instit
        GROUP BY {$sigla}_regist";
}

$sJoinFolha .= implode(" UNION ALL ", $aJoinFolha) . ") AS valores
                GROUP BY r14_regist) AS x";

$sAliquota = "";
if (!empty($campoextra)) {
    $sAliquota = "ROUND(base / 100 * $campoextra, 2) AS aliquotacomp, ";
}

$sql = "
    SELECT z01_nome,
           z01_cgccpf,
           x.r14_regist,
           ROUND(x.proventos, 2) AS proventos,
           ROUND(x.base, 2) AS base,
           ROUND(x.segurado, 2) AS segurado,
           ROUND(ROUND(x.base, 2) / 100 * $dadosPrevidencia->r33_ppatro, 2) AS patronal,
           {$sAliquota}
           ROUND((ROUND(x.base, 2) / 100 * $dadosPrevidencia->r33_ppatro) + ROUND(x.segurado, 2), 2) AS total,
           ROUND(SUM(ROUND(x.base, 2)) OVER (), 2) AS total_base  -- Soma total de 'base'
    FROM {$sJoinFolha}
    INNER JOIN rhpessoal ON x.r14_regist = rh01_regist
    INNER JOIN rhpessoalmov ON rh02_anousu = $ano
                             AND rh02_mesusu = $mes
                             AND rh02_regist = rh01_regist
                             AND rh02_instit = $instit
                             AND rh02_tbprev = $prev
    INNER JOIN rhregime ON rh02_codreg = rh30_codreg
                         AND rh30_instit = $instit
    INNER JOIN cgm ON z01_numcgm = rh01_numcgm
    WHERE 1 = 1 $xsembase
    $where_vinc
    $xordem
    ";

$result = db_query($sql);
$xxnum = pg_num_rows($result);
if ($xxnum == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=No existem clculos para o perodo de ' . $mes . ' / ' . $ano);
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 8);
$troca      = 1;
$alt        = 4;
$total_fun  = 0;
$total_prov = 0;
$total_seg  = 0;
$total_base = 0;
$total_patro = 0;
$total_aliquotacomp = 0;
$total_total = 0;

for ($x = 0; $x < pg_num_rows($result); $x++) {
    $dadosServidor = db_utils::fieldsMemory($result, $x);
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0) {
        $pdf->addpage("L");
        $pdf->setfont('arial', 'b', 8);
        $pdf->cell(16, $alt, 'MATRIC', 1, 0, "C", 1);
        $pdf->cell(26, $alt, 'CPF', 1, 0, "C", 1);
        $pdf->cell(78, $alt, 'NOME DO FUNCIONÁRIO', 1, 0, "C", 1);
        $pdf->cell(26, $alt, 'BRUTO', 1, 0, "C", 1);
        $pdf->cell(26, $alt, 'BASE', 1, 0, "C", 1);
        $pdf->cell(26, $alt, 'SEGURADO', 1, 0, "C", 1);
        $pdf->cell(26, $alt, 'PATRON', 1, 0, "C", 1);
        if (!empty($campoextra)) {
            $pdf->cell(26, $alt, 'ALÍQUOTA', 1, 0, "C", 1);
        }
        $pdf->cell(26, $alt, 'TOTAL', 1, 1, "C", 1);
        $troca = 0;
        $pre = 1;
    }
    if ($pre == 1) {
        $pre = 0;
    } else {
        $pre = 1;
    }
    $pdf->setfont('arial', '', 7);
    $pdf->cell(16, $alt, $dadosServidor->r14_regist, 0, 0, "R", $pre);
    $pdf->cell(26, $alt, db_formatar($dadosServidor->z01_cgccpf, "cpf"), 0, 0, "C", $pre);
    $pdf->cell(78, $alt, $dadosServidor->z01_nome, 0, 0, "L", $pre);
    $pdf->cell(26, $alt, db_formatar($dadosServidor->proventos, 'f'), 0, 0, "R", $pre);
    $pdf->cell(26, $alt, db_formatar($dadosServidor->base, 'f'), 0, 0, "R", $pre);
    $pdf->cell(26, $alt, db_formatar($dadosServidor->segurado, 'f'), 0, 0, "R", $pre);
    $pdf->cell(26, $alt, db_formatar($dadosServidor->patronal, 'f'), 0, 0, "R", $pre);
    if (!empty($campoextra)) {
        $pdf->cell(26, $alt, db_formatar($dadosServidor->aliquotacomp, 'f'), 0, 0, "R", $pre);
    }
    $pdf->cell(26, $alt, db_formatar($dadosServidor->total, 'f'), 0, 1, "R", $pre);
    $total_fun   += 1;
    $total_prov  += $dadosServidor->proventos;
    $total_base  += $dadosServidor->base;
    $total_seg   += $dadosServidor->segurado;
    $total_patro += $dadosServidor->patronal;
    if (!empty($campoextra)) {
        $total_aliquotacomp += $dadosServidor->aliquotacomp;
    }
    $total_total += $dadosServidor->total;
}
$pdf->setfont('arial', 'b', 8);
$pdf->cell(135, $alt, 'TOTAL :  ' . $total_fun . ' FUNCIONÁRIOS ', "T", 0, "C", 0);
$pdf->cell(28, $alt, db_formatar($total_prov, 'f'), "T", 0, "C", 0);
$pdf->cell(28, $alt, db_formatar($total_base, 'f'), "T", 0, "C", 0);
$pdf->cell(28, $alt, db_formatar($total_seg, 'f'), "T", 0, "C", 0);
$pdf->cell(28, $alt, db_formatar($total_patro, 'f'), "T", 0, "C", 0);
if (!empty($campoextra)) {
    $pdf->cell(28, $alt, db_formatar($total_aliquotacomp, 'f'), "T", 0, "C", 0);
}
$pdf->cell(28, $alt, db_formatar($total_total, 'f'), "T", 1, "C", 0);
$pdf->Output();
