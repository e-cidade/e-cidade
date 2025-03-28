<?

require_once("fpdf151/pdf.php");

$head3 = "RELAT�RIO DE ADITAMENTO";

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(235);
$pdf->addpage('P');
$alt = 3;
$pdf->SetFont('arial', 'B', 12);
$pdf->ln(6);


/* Consulta para informa��es do contrato e aditamento. */
$rs_acordo = db_query("select * from acordo
inner join acordoposicao on ac16_sequencial = ac26_acordo
inner join acordoposicaotipo on ac27_sequencial = ac26_acordoposicaotipo
inner join cgm on z01_numcgm = ac16_contratado
inner join acordoposicaoaditamento on ac35_acordoposicao = ac26_sequencial
left join liclicita on ac16_licitacao = l20_codigo
where ac26_sequencial in ($aditamentos);");

/* Consulta do nome da institui��o. */
$rs_inst = db_query("select nomeinst from db_config where codigo = " . db_getsession("DB_instit"));
$nomeinst = db_utils::fieldsMemory($rs_inst, 0)->nomeinst;


for ($i = 0; $i < pg_numrows($rs_acordo); $i++) {

    $acordo = db_utils::fieldsMemory($rs_acordo, $i);

    /* Consulta dos itens do aditamento. */
    $rs_acordoitem = db_query("select * from acordoitem
                    inner join pcmater on ac20_pcmater = pc01_codmater
                    inner join acordoposicao on ac26_sequencial = ac20_acordoposicao  
                    where ac20_acordoposicao = $acordo->ac26_sequencial order by ac20_ordem;");



    /* Consulta do periodo de vigencia do contrato. */
    $rs_vigencia = db_query("select * from acordovigencia where ac18_acordoposicao  = $acordo->ac26_sequencial;");
    $vigencia = db_utils::fieldsMemory($rs_vigencia, 0);



    /* Consulta do valor total aditado. */
    $rs_totaladitado = db_query("select sum(ac20_valoraditado) as valoraditado from acordoitem
    inner join pcmater on ac20_pcmater = pc01_codmater
    inner join acordoposicao on ac26_sequencial = ac20_acordoposicao  
    where ac20_acordoposicao = $acordo->ac26_sequencial;");
    $totaladitado =  db_utils::fieldsMemory($rs_totaladitado, 0)->valoraditado;

    $pdf->SetFont('arial', 'B', 12);
    $pdf->Cell(160, 6, "ADITIVO CONTRATUAL N� $acordo->ac26_numeroaditamento AO CONTRATO N� $acordo->ac16_numero/$acordo->ac16_anousu", 0, 1, "C", 0);
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());

    $pdf->ln(6);

    $pdf->SetFont('arial', 'B', 10);
    $pdf->Cell(160, 6, "Aditivo de " . $acordo->ac27_descricao, 0, 1, "C", 0);
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());

    $pdf->ln(10);

    $pdf->Cell(15, 6, "Objeto: ", 0, 0, "L", 0);
    $pdf->SetFont('arial', '', 10);
    $pdf->MultiCell(150, 6, $acordo->ac16_objeto, 0, 1, "L", 0);


    $pdf->SetFont('arial', 'B', 10);
    $pdf->Cell(22, 6, "Fornecedor: ", 0, 0, "L", 0);
    $pdf->SetFont('arial', '', 10);
    $pdf->Cell(150, 6,  ucwords(strtolower($acordo->z01_nome)), 0, 1, "L", 0);


    $pdf->SetFont('arial', 'B', 10);
    $tituloVigencia = $acordo->ac16_vigenciaindeterminada == "t" ? "Vig�ncia Inicial: " : "Per�odo de Vig�ncia: ";
    $pdf->Cell(35, 6, $tituloVigencia, 0, 0, "L", 0);
    $pdf->SetFont('arial', '', 10);
    $dataVigencia = $acordo->ac16_vigenciaindeterminada == "t" ? date("d/m/Y", strtotime($vigencia->ac18_datainicio)) : date("d/m/Y", strtotime($vigencia->ac18_datainicio)) . ' at� ' . date("d/m/Y", strtotime($vigencia->ac18_datafim));
    $pdf->Cell(140, 6,  $dataVigencia, 0, 1, "L", 0);

    $pdf->setfont('arial', 'B', 10);
    $pdf->Cell(35, 6, "Data de Publica��o: ", 0, 0, "L", 0);
    $datapublicacao =  implode('/', array_reverse(explode('-', $acordo->ac35_datapublicacao)));
    $pdf->SetFont('arial', '', 10);
    $pdf->Cell(150, 6,  $datapublicacao, 0, 1, "L", 0);

    $pdf->setfont('arial', 'B', 10);
    $pdf->Cell(40, 6, "Ve�culo de Divulga��o: ", 0, 0, "L", 0);
    $pdf->SetFont('arial', '', 10);
    $pdf->Cell(150, 6,  $acordo->ac35_veiculodivulgacao, 0, 1, "L", 0);


    /* 
    * inser��o de c�lula com a descri��o da altera��o
    * Se o aditivo for do tipo "Outros" ou "Acr�scimo/Descr�scimo de item(ns) conjugado com outros tipos de termo aditivos".
    */

    if ($acordo->ac26_acordoposicaotipo == 14 || $acordo->ac26_acordoposicaotipo == 7) {
        $pdf->setfont('arial', 'B', 10);
        $pdf->Cell(41, 6, "Descri��o da Altera��o: ", 0, 0, "L", 0);
        $pdf->SetFont('arial', '', 10);
        $pdf->Cell(150, 6,  $acordo->ac35_descricaoalteracao, 0, 1, "L", 0);
    }

    /* 
     * inser��o de c�lula com a justificativa do aditvo
    * Se o contrato for decorrente de licita��o, cuja lei da licita��o (l20_leidalicitacao) for 1 ? Lei 14.133/2021. .
    */

    if ($acordo->l20_leidalicitacao != null && $acordo->l20_leidalicitacao == 1) {
        $pdf->SetFont('arial', 'B', 10);
        $pdf->Cell(40, 6, "Justificativa do Aditivo: ", 0, 0, "L", 0);
        $pdf->SetFont('arial', '', 10);
        $pdf->Cell(150, 6, "$acordo->ac35_justificativa", 0, 1, "L", 0);
    }

    $pdf->ln(10);
    $pdf->setfont('arial', 'B', 7);

    $pdf->cell(20, 6, "Ordem", 1, 0, "C", 1);
    $pdf->cell(70, 6, "Descri��o do Item", 1, 0, "C", 1);
    $pdf->cell(20, 6, "Qtd. Atual", 1, 0, "C", 1);
    $pdf->cell(20, 6, "Vlr. Unit�rio", 1, 0, "C", 1);
    $pdf->cell(20, 6, "Valor Total", 1, 0, "C", 1);
    $pdf->cell(20, 6, "Qtd. Aditada", 1, 0, "C", 1);
    $pdf->cell(20, 6, "Vlr. Aditado", 1, 1, "C", 1);

    $pdf->setfont('arial', '', 7);

    for ($j = 0; $j < pg_num_rows($rs_acordoitem); $j++) {
        $acordoitem = db_utils::fieldsMemory($rs_acordoitem, $j);
        $pdf->cell(20, 6, "$acordoitem->ac20_ordem", 1, 0, "C", 0);
        $pdf->cell(70, 6,  substr($acordoitem->pc01_descrmater, 0, 44), 1, 0, "L", 0);
        $pdf->cell(20, 6, "$acordoitem->ac20_quantidade", 1, 0, "C", 0);
        $pdf->cell(20, 6, 'R$ ' . number_format($acordoitem->ac20_valorunitario, 2, ',', '.'), 1, 0, 'C', 0);
        $pdf->cell(20, 6, 'R$ ' . number_format($acordoitem->ac20_valortotal, 2, ',', '.'), 1, 0, "C", 0);
        $pdf->cell(20, 6,  $acordoitem->ac20_quantidadeaditada == 0 ? "-" : "$acordoitem->ac20_quantidadeaditada", 1, 0, "C", 0);
        $pdf->cell(20, 6,  $acordoitem->ac20_valoraditado == 0 ? "-" : 'R$ ' . number_format($acordoitem->ac20_valoraditado, 2, ',', '.'), 1, 1, "C", 0);
    }

    $pdf->setfont('arial', 'B', 7);
    $pdf->cell(170, 6, "Valor Total: ", 0, 0, "R", 0);
    $pdf->cell(20, 6, 'R$ ' . number_format($totaladitado, 2, ',', '.'), 0, 1, "C", 0);

    /* Verifica��o para acr�scimo de nova p�gina a cada novo aditamento incluso no relat�rio */
    if ($i + 1 != pg_numrows($rs_acordo)) {
        $pdf->AddPage();
    }
}
$pdf->Output();
