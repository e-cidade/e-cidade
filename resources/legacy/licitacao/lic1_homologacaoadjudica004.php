<?php

require_once("fpdf151/pdf.php");
require_once("libs/db_stdlib.php");

use App\Services\Patrimonial\Licitacao\DadosRelatorioHomologacaoAdjudicacaoService;

const JULGAMENTO_POR_ITEM = "1";
const JULGAMENTO_POR_LOTE = "3";
const ADJUDICACAO_OUTROS = "3";
const ADJUDICACAO_TAXA_TABELA = ["1", "2"];
const ALTURA_CELULA = 6;
const ALTURA_MULTICELL = 4;

$tipoRelatorio = $tipoRelatorio == "Homologacao" ? "Homologação" : "Adjudicação";

$DadosRelatorioHomologacaoAdjudicacaoService = new DadosRelatorioHomologacaoAdjudicacaoService();
$dadosRelatorio = $DadosRelatorioHomologacaoAdjudicacaoService->obterDadosRelatorio($codigoLicitacao, $tipoRelatorio);

/* Funções */

function gerarCabecalhoFornecedor($pdf,$rsItensHomologacao,$indiceItem) {

    $fornecedorAnterior = db_utils::fieldsMemory($rsItensHomologacao, $indiceItem - 1);
    $fornecedorAtual = db_utils::fieldsMemory($rsItensHomologacao, $indiceItem);

    if ($fornecedorAnterior->z01_nome != $fornecedorAtual->z01_nome) {
        $pdf->ln(4);
        $pdf->setfont('arial', 'b', 8);
        $pdf->cell(190, ALTURA_CELULA, "{$fornecedorAtual->z01_nome} - {$fornecedorAtual->z01_cgccpf}", 1, 0, "L", 1);
        $pdf->ln();
    }

}

function gerarCabecalhoLote($pdf,$itemHomologacao,$tipoJulgamento){

    if($tipoJulgamento == JULGAMENTO_POR_ITEM) return false;

    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(190, ALTURA_CELULA, $itemHomologacao->l04_descricao, 1, 0, "L", 1);
    $pdf->ln();

}

function gerarCabecalhoItem($pdf,$rsItensHomologacao,$indiceItem,$criterioAdjudicacao,$tipoJulgamento){

    $fornecedorAnterior = db_utils::fieldsMemory($rsItensHomologacao, $indiceItem - 1);
    $fornecedorAtual = db_utils::fieldsMemory($rsItensHomologacao, $indiceItem);

    if($tipoJulgamento == JULGAMENTO_POR_ITEM && $fornecedorAnterior->z01_nome == $fornecedorAtual->z01_nome) return false;

    if($criterioAdjudicacao == ADJUDICACAO_OUTROS){
        $pdf->cell(10, ALTURA_CELULA, "Seq.", 1, 0, "C", 1);
        $pdf->cell(15, ALTURA_CELULA, "Código", 1, 0, "C", 1);
        $pdf->cell(56, ALTURA_CELULA, "Descrição", 1, 0, "C", 1);
        $pdf->cell(13, ALTURA_CELULA, "Unid.", 1, 0, "C", 1);
        $pdf->cell(36, ALTURA_CELULA, "Marca", 1, 0, "C", 1);
        $pdf->cell(20, ALTURA_CELULA, "Quantidade", 1, 0, "C", 1);
        $pdf->cell(20, ALTURA_CELULA, "Unitário", 1, 0, "C", 1);
        $pdf->cell(20, ALTURA_CELULA, "Total", 1, 0, "C", 1);
        $pdf->ln(6);
    }

    if (in_array($criterioAdjudicacao, ADJUDICACAO_TAXA_TABELA, true)) {
        $pdf->cell(10, ALTURA_CELULA, "Seq.", 1, 0, "C", 1);
        $pdf->cell(13, ALTURA_CELULA, "Código", 1, 0, "C", 1);
        $pdf->cell(52, ALTURA_CELULA, "Descrição", 1, 0, "C", 1);
        $pdf->cell(13, ALTURA_CELULA, "Unid.", 1, 0, "C", 1);
        $pdf->cell(31, ALTURA_CELULA, "Marca", 1, 0, "C", 1);
        $pdf->cell(17, ALTURA_CELULA, "Quantidade", 1, 0, "C", 1);
        $pdf->cell(17, ALTURA_CELULA, "Unitário", 1, 0, "C", 1);
        $pdf->cell(17, ALTURA_CELULA, "Percentual", 1, 0, "C", 1);
        $pdf->cell(20, ALTURA_CELULA, "Total", 1, 0, "C", 1);
        $pdf->ln(6);
    }

}

function gerarItens($pdf,$itemHomologacao,$criterioAdjudicacao){


    if($criterioAdjudicacao == ADJUDICACAO_OUTROS){

        $pdf->SetFont('Arial', '', 8); 

        if(strlen($itemHomologacao->descricao) >  1000){
                $pdf->SetFont('Arial', '', 6); 
        }

        // Calcula o número de linhas de cada texto
        $linhasDescricao = $pdf->NbLines(56, substr($itemHomologacao->descricao,0,1250));
        $linhasMarca = $pdf->NbLines(36, $itemHomologacao->marca);

        // Calcula as quebras necessárias
        $quebraDeLinhaMarca = ($linhasDescricao > $linhasMarca) ? str_repeat("\n", ($linhasDescricao - $linhasMarca) + 1) : "";
        $quebraDeLinhaDescricao = ($linhasMarca > $linhasDescricao) ? str_repeat("\n", ($linhasMarca - $linhasDescricao) + 1) : "";

        // Usa o maior número de linhas para determinar a altura da célula
        $quantidadeDeLinhas = max($linhasDescricao, $linhasMarca);
        $alturaCell = $quantidadeDeLinhas * ALTURA_MULTICELL;

        // Preenche as células com os dados
        $pdf->Cell(10, $alturaCell, $itemHomologacao->l21_ordem, 1, 0, "C", 0);
        $pdf->Cell(15, $alturaCell, $itemHomologacao->pc01_codmater, 1, 0, "C", 0);

        // Define o ponto inicial para o texto da descrição e preenche a celula da descrição
        $y = $pdf->GetY();
        $pdf->MultiCell(56, ALTURA_MULTICELL, substr($itemHomologacao->descricao,0,1250) , 1, "L", 2);

        // Restaura o ponto Y e ajusta o X para a próxima célula
        $pdf->SetY($y);
        $pdf->SetX(91);

        // Preenche a célula da abreviação
        $pdf->Cell(13, $alturaCell, $itemHomologacao->m61_abrev, 1, 0, "C", 0);

        // Prepara o texto da marca com possível quebra de linha
        $textoMarca = ($itemHomologacao->marca == "") ? "-" . $quebraDeLinhaMarca : $itemHomologacao->marca . $quebraDeLinhaMarca;
        $alinhamentoMarca = ($itemHomologacao->marca == "") ? "C" : "L";

        // Define o ponto inicial para o texto da marca
        $y = $pdf->GetY();
        $pdf->MultiCell(36, ALTURA_MULTICELL, $textoMarca, 1, $alinhamentoMarca, 2);

        // Restaura o ponto Y e ajusta o X para as próximas células
        $pdf->SetY($y);
        $pdf->SetX(140);

        // Preenche as células com os valores monetários
        $pdf->Cell(20, $alturaCell, $itemHomologacao->pc11_quant, 1, 0, "C");
        $pdf->Cell(20, $alturaCell, $itemHomologacao->pc23_vlrun, 1, 0, "C");
        $pdf->Cell(20, $alturaCell, $itemHomologacao->pc23_valor, 1, 0, "C");

        // Realiza a quebra de linha após o preenchimento de todas as células
        $pdf->ln();

    }

    if (in_array($criterioAdjudicacao, ADJUDICACAO_TAXA_TABELA, true)) {

        // Calcula o número de linhas de cada texto
        $linhasDescricao = $pdf->NbLines(52, $itemHomologacao->descricao);
        $linhasMarca = $pdf->NbLines(31, $itemHomologacao->marca);

        // Calcula as quebras necessárias
        $quebraDeLinhaMarca = ($linhasDescricao > $linhasMarca) ? str_repeat("\n", ($linhasDescricao - $linhasMarca) + 1) : "";
        $quebraDeLinhaDescricao = ($linhasMarca > $linhasDescricao) ? str_repeat("\n", ($linhasMarca - $linhasDescricao) + 1) : "";

        // Usa o maior número de linhas para determinar a altura da célula
        $quantidadeDeLinhas = max($linhasDescricao, $linhasMarca);
        $alturaCell = $quantidadeDeLinhas * ALTURA_MULTICELL;

        // Preenche as células com os dados
        $pdf->Cell(10, $alturaCell, $itemHomologacao->l21_ordem, 1, 0, "C", 0);
        $pdf->Cell(13, $alturaCell, $itemHomologacao->pc01_codmater, 1, 0, "C",0);

        // Define o ponto inicial para o texto da descrição e preenche a celula da descrição
        $y =  $pdf->GetY();
        $pdf->MultiCell(52, ALTURA_MULTICELL , $itemHomologacao->descricao . $quebraDeLinhaDescricao, 1, "L", 2);

        // Restaura o ponto Y e ajusta o X para a próxima célula
        $pdf->SetY($y);
        $pdf->SetX(85);

        // Preenche a célula da abreviação
        $pdf->Cell(13, $alturaCell , $itemHomologacao->m61_abrev, 1, 0, "C",0);

        // Prepara o texto da marca com possível quebra de linha
        $textoMarca = ($itemHomologacao->marca == "") ? "-" . $quebraDeLinhaMarca : $itemHomologacao->marca . $quebraDeLinhaMarca;
        $alinhamentoMarca = ($itemHomologacao->marca == "") ? "C" : "L";

        // Define o ponto inicial para o texto da marca
        $y = $pdf->GetY();
        $pdf->MultiCell(31, ALTURA_MULTICELL, $textoMarca, 1, $alinhamentoMarca, $alinhamentoMarca);

        // Restaura o ponto Y e ajusta o X para as próximas células
        $pdf->SetY($y);
        $pdf->SetX(129);

        // Preenche as células com os valores monetários
        $pdf->Cell(17, $alturaCell , $itemHomologacao->pc11_quant, 1, 0, "C");
        $valorUnitario = ($itemHomologacao->pc01_tabela == "t" || $itemHomologacao->pc01_taxa == "t") ? "-" : "R$" . $itemHomologacao->pc23_vlrun;
        $pdf->Cell(17, $alturaCell , $valorUnitario, 1, 0, "C");

        $percentual = ($itemHomologacao->pc01_tabela == "t" || $itemHomologacao->pc01_taxa == "t") ? "$itemHomologacao->pc23_perctaxadesctabela%" : "-";
        $pdf->Cell(17, $alturaCell ,$percentual, 1, 0, "C");
        $pdf->Cell(20, $alturaCell ,number_format($itemHomologacao->pc23_valor, 2, ",", "."), 1, 0, "C");

        // Realiza a quebra de linha após o preenchimento de todas as células
        $pdf->ln();
    }
}

function gerarTotal($pdf,$rsItensHomologacao,$indiceItem,$codigoLicitacao,$codigoHomologacao,$tipoRelatorio){

    $fornecedorAtual = db_utils::fieldsMemory($rsItensHomologacao, $indiceItem);
    $fornecedorPosterior = db_utils::fieldsMemory($rsItensHomologacao, $indiceItem + 1);

    if ($fornecedorAtual->z01_nome != $fornecedorPosterior->z01_nome) {
        $pdf->SetFont('Arial', 'b', 8);

        $clhomologacaoadjudica = new cl_homologacaoadjudica();
        $total = $tipoRelatorio == "Homologação" ? $clhomologacaoadjudica->totalHomologadoPorFornecedor($codigoLicitacao,$codigoHomologacao,$fornecedorAtual->z01_nome) : $clhomologacaoadjudica->totalAdjudicadoPorFornecedor($codigoLicitacao,$fornecedorAtual->z01_nome);
        $total = number_format($total, 2, ',', '.');
        $total = $tipoRelatorio == "Homologação" ? "Total Homologado: R$  $total" : "Total Adjudicado: R$  $total";

        $pdf->cell(190, 6, $total, 1, 0, "R", 0);
        $pdf->ln(6);
    }
}

function gerarTotalGeral($pdf,$valorTotal){
    $pdf->ln();
    $pdf->SetFont('Arial', 'b', 10);
    $pdf->cell(190, 6, "Total Geral: R$ $valorTotal", 0, 0, "R", 0);
}

function gerarData($pdf,$dadosRelatorio,$tipoRelatorio){
    $oInstituicao = new Instituicao(db_getsession('DB_instit'));
    $nomeInstituicao = $oInstituicao->getMunicipio();
    if($tipoRelatorio == "Homologação"){
        $dataAtual = DateTime::createFromFormat('d/m/Y',$dadosRelatorio['datahomologacao']);
    }else{
        $dataAtual = DateTime::createFromFormat('d/m/Y',$dadosRelatorio['dataadjudicacao']);
    }

    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    $nomeMes = strftime('%B', $dataAtual->getTimestamp());
    $dataEmExtenso = $nomeInstituicao . ", " .  $dataAtual->format('j') . ' de ' . $nomeMes . ' de ' . $dataAtual->format('Y');
    $pdf->ln(10);
    $pdf->SetFont('Arial','', 10);
    $pdf->cell(190, 6, $dataEmExtenso, 0, 0, "R", 0);

}

function gerarAssinatura($pdf,$nomeResponsavel){
    $pdf->ln(30);
    $pdf->SetFont('Arial','b', 12);
    $pdf->cell(190, 4, "__________________________________", 0, 0, "C", 0);
    $pdf->ln();
    $pdf->SetFont('Arial','b', 10);
    $pdf->cell(190, 6, $nomeResponsavel, 0, 0, "C", 0);
}

/* Geração do Relatório*/

$head4 = $tipoRelatorio;
$head5 = "Sequencial: $codigoLicitacao";

if($tipoRelatorio == "Homologação"){
    $head6 = "Data: " . $dadosRelatorio['datahomologacao'];
}else{
    $head6 = "Data: " . $dadosRelatorio['dataadjudicacao'];
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(235);
$pdf->addpage('P');
$pdf->SetFont('arial', 'B', 14);

$pdf->ln(6);

$pdf->cell(190, 10, "$tipoRelatorio de Processo", 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('arial', 'b', 8);
$pdf->MultiCell(190, 8, $dadosRelatorio['paragrafosPreDefinidos'], 0, "L", 0);
$pdf->Ln(3);

for ($i = 0; $i < pg_num_rows($dadosRelatorio['itens']); $i++) {
    $itemHomologacao = db_utils::fieldsMemory($dadosRelatorio['itens'], $i);
    gerarCabecalhoFornecedor($pdf, $dadosRelatorio['itens'],$i);
    gerarCabecalhoLote($pdf,$itemHomologacao,$dadosRelatorio['tipoJulgamento']);
    gerarCabecalhoItem($pdf,$dadosRelatorio['itens'],$i,$dadosRelatorio['criterioAdjudicacao'],$dadosRelatorio['tipoJulgamento']);
    gerarItens($pdf,$itemHomologacao,$dadosRelatorio['criterioAdjudicacao']);
    gerarTotal($pdf,$dadosRelatorio['itens'],$i,$codigoLicitacao,$dadosRelatorio['codigoHomologacao'],$tipoRelatorio);
}

gerarTotalGeral($pdf,$dadosRelatorio['valorTotal']);
gerarData($pdf,$dadosRelatorio,$tipoRelatorio);
gerarAssinatura($pdf,$dadosRelatorio['responsavel']);

$pdf->Output();
