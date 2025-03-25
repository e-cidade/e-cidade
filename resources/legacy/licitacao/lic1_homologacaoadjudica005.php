<?php

use App\Services\Patrimonial\Licitacao\DadosRelatorioHomologacaoAdjudicacaoService;

require_once("fpdf151/pdf.php");
require_once("libs/db_stdlib.php");

const JULGAMENTO_POR_ITEM = "1";
const JULGAMENTO_POR_LOTE = "3";
const ADJUDICACAO_OUTROS = "3";
const ADJUDICACAO_TAXA_TABELA = ["1", "2"];
const ALTURA_CELULA = 6;

$tipoRelatorio = $tipoRelatorio == "Homologacao" ? "Homologação" : "Adjudicação";

$DadosRelatorioHomologacaoAdjudicacaoService = new DadosRelatorioHomologacaoAdjudicacaoService();
$dadosRelatorio = $DadosRelatorioHomologacaoAdjudicacaoService->obterDadosRelatorio($codigoLicitacao, $tipoRelatorio);

/* Quantidade de celulas */
define('QUANTIDADE_CELULAS', $dadosRelatorio['criterioAdjudicacao'] == ADJUDICACAO_OUTROS ? 8 : 9);

function gerarParagrafoPreDefinido($paragrafoPreDefinido){

    foreach ($paragrafoPreDefinido as $oTexto) {
        $paragrafo = $oTexto->oParag->db02_texto;
    }

    $paragrafo = explode("\n",$paragrafo);
    for($i=0;$i<count($paragrafo);$i++){
        echo "<strong>$paragrafo[$i]</strong>";
        echo"<br>";
    }
    echo"<br>";
}

function gerarCabecalhoFornecedor($rsItensHomologacao,$indiceItem) {

    $fornecedorAnterior = db_utils::fieldsMemory($rsItensHomologacao, $indiceItem - 1);
    $fornecedorAtual = db_utils::fieldsMemory($rsItensHomologacao, $indiceItem);

    if ($fornecedorAnterior->z01_nome != $fornecedorAtual->z01_nome) {
        $sFornecedor = "{$fornecedorAtual->z01_nome} - {$fornecedorAtual->z01_cgccpf}";
        echo "<tr><td colspan='" . QUANTIDADE_CELULAS . "' style='height: 10px;'></td></tr>";
        echo '<tr> <td style="font-weight:bold; background-color: #D3D3D3;" colspan="' . QUANTIDADE_CELULAS . '">' . $sFornecedor . '</td> </tr>';
    }

}

function gerarCabecalhoItem($rsItensHomologacao,$indiceItem,$criterioAdjudicacao,$tipoJulgamento){

    $fornecedorAnterior = db_utils::fieldsMemory($rsItensHomologacao, $indiceItem - 1);
    $fornecedorAtual = db_utils::fieldsMemory($rsItensHomologacao, $indiceItem);

    if($tipoJulgamento == JULGAMENTO_POR_ITEM && $fornecedorAnterior->z01_nome == $fornecedorAtual->z01_nome) return false;

    $styleCabecalhoItens = 'style="font-weight: bold; text-align: center; background-color: #D3D3D3;"';

    if ($criterioAdjudicacao == ADJUDICACAO_OUTROS) {
        echo '<tr>';
        echo "<td $styleCabecalhoItens>Seq.</td>";
        echo "<td $styleCabecalhoItens>Código</td>";
        echo "<td $styleCabecalhoItens>Descrição</td>";
        echo "<td $styleCabecalhoItens>Unidade</td>";
        echo "<td $styleCabecalhoItens>Marca</td>";
        echo "<td $styleCabecalhoItens>Quantidade</td>";
        echo "<td $styleCabecalhoItens>Unitário</td>";
        echo "<td $styleCabecalhoItens>Total</td>";
        echo '</tr>';
    }
    
    if (in_array($criterioAdjudicacao, ADJUDICACAO_TAXA_TABELA, true)) {
        echo '<tr>';
        echo "<td $styleCabecalhoItens>Seq.</td>";
        echo "<td $styleCabecalhoItens>Código</td>";
        echo "<td $styleCabecalhoItens>Descrição</td>";
        echo "<td $styleCabecalhoItens>Unidade</td>";
        echo "<td $styleCabecalhoItens>Marca</td>";
        echo "<td $styleCabecalhoItens>Quantidade</td>";
        echo "<td $styleCabecalhoItens>Unitário</td>";
        echo "<td $styleCabecalhoItens>Percentual</td>";
        echo "<td $styleCabecalhoItens>Total</td>";
        echo '</tr>';
    }

}

function gerarItens($itemHomologacao,$criterioAdjudicacao){

    $gerarCelula = function ($conteudo, $alinhamento, $fontSize = '') {
        echo '<td style="text-align:' . $alinhamento . '; font-size:' . $fontSize . ';">' . $conteudo . '</td>';
    };
    
    if ($criterioAdjudicacao == ADJUDICACAO_OUTROS) {
        echo '<tr>';
        $gerarCelula($itemHomologacao->l21_ordem, 'center');
        $gerarCelula($itemHomologacao->pc01_codmater, 'center');
        $gerarCelula($itemHomologacao->descricao,'left');
        $gerarCelula($itemHomologacao->m61_abrev, 'center');
        $gerarCelula($itemHomologacao->marca == "" ? "-" : $itemHomologacao->marca,'center');
        $gerarCelula($itemHomologacao->pc11_quant, 'center');
        $gerarCelula($itemHomologacao->pc23_vlrun,'left','12px');
        $gerarCelula(number_format($itemHomologacao->pc23_valor, 2, ",", "."),'left','12px');
        echo '</tr>';
    }
    
    if (in_array($criterioAdjudicacao, ADJUDICACAO_TAXA_TABELA, true)) {

        $valorUnitario = ($itemHomologacao->pc01_tabela || $itemHomologacao->pc01_taxa) ? "-" : $itemHomologacao->pc23_vlrun;
    
        echo '<tr>';
        $gerarCelula($itemHomologacao->l21_ordem, 'center');
        $gerarCelula($itemHomologacao->pc01_codmater, 'center');
        $gerarCelula($itemHomologacao->descricao,'left');
        $gerarCelula($itemHomologacao->m61_abrev, 'center');
        $gerarCelula($itemHomologacao->marca == "" ? "-" : $itemHomologacao->marca,'center');
        $gerarCelula($itemHomologacao->pc11_quant, 'center');
        $gerarCelula($valorUnitario, 'left');
        $gerarCelula($itemHomologacao->pc23_perctaxadesctabela . '%','left','12px');
        $gerarCelula(number_format($itemHomologacao->pc23_valor, 2, ",", "."),'left','12px');
        echo '</tr>';
    }
    

}

function gerarTotal($rsItensHomologacao,$indiceItem,$codigoLicitacao,$codigoHomologacao,$tipoRelatorio){

    $fornecedorAtual = db_utils::fieldsMemory($rsItensHomologacao, $indiceItem);
    $fornecedorPosterior = db_utils::fieldsMemory($rsItensHomologacao, $indiceItem + 1);

    if ($fornecedorAtual->z01_nome != $fornecedorPosterior->z01_nome) {

        $clhomologacaoadjudica = new cl_homologacaoadjudica();
        $total = $tipoRelatorio == "Homologação" ? $clhomologacaoadjudica->totalHomologadoPorFornecedor($codigoLicitacao,$codigoHomologacao,$fornecedorAtual->z01_nome) : $clhomologacaoadjudica->totalAdjudicadoPorFornecedor($codigoLicitacao,$fornecedorAtual->z01_nome);
        $total = number_format($total, 2, ',', '.');
        $total = $tipoRelatorio == "Homologação" ? "Total Homologado: R$  $total" : "Total Adjudicado: R$  $total";
    
        echo '<tr>';
        echo "<td colspan='" . QUANTIDADE_CELULAS . "' style='font-weight: bold; text-align: right; width: 100%; padding: 5px; border: 1px solid black;'>";
        echo $total;
        echo '</td>';
        echo '</tr>';

    }
}

function gerarCabecalhoLote($itemHomologacao,$tipoJulgamento){

    if($tipoJulgamento == JULGAMENTO_POR_ITEM) return false;

    echo '<tr style="background-color: #D3D3D3;">  <td style="font-weight:bold;" colspan="' . QUANTIDADE_CELULAS . '">' . $itemHomologacao->l04_descricao . '</td> </tr>';

}

function gerarValorTotal($valorTotal){
    echo '<p style="text-align: right; font-weight: bold; margin-top: 20px;">Total Geral: R$ ' . $valorTotal . '</p>';
}

function gerarData(){
    $oInstituicao = new Instituicao(db_getsession('DB_instit'));
    $nomeInstituicao = $oInstituicao->getMunicipio();
    $dataAtual = new DateTime();
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    $nomeMes = strftime('%B', $dataAtual->getTimestamp());
    $dataEmExtenso = $nomeInstituicao . ", " .  $dataAtual->format('j') . ' de ' . $nomeMes . ' de ' . $dataAtual->format('Y');
    echo '<p style="text-align: right; font-weight: bold; margin-top: 20px;">' . $dataEmExtenso . '</p>';
}

function gerarAssinatura($nomeResponsavel) {
    echo '<div style="margin-top: 30px; text-align: center;">';
    echo '<hr style="border: none; border-top: 1px solid black; width: 60%;">';  // Linha simulando a assinatura
    echo '<p style="font-weight: bold; margin-top: 5px; font-size: 14px;">' . $nomeResponsavel . '</p>';  // Nome abaixo da linha
    echo '</div>';
}


// Definindo o cabeçalho para forçar o download de um arquivo .doc
header("Content-type: application/vnd.ms-word; charset=UTF-8");
header("Content-Disposition: attachment; Filename=relaotorio.doc");

// Conteúdo do relatório em formato HTML
echo '<html xmlns="http://www.w3.org/1999/html">';
echo '<head>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"';
echo '</head>';
echo '<body>';

echo '<h2 style="text-align: center;">' . $tipoRelatorio . ' DE PROCESSO</h2>';

gerarParagrafoPreDefinido($dadosRelatorio['paragrafosPreDefinidos']);

echo '<table border="1" cellpadding="' . QUANTIDADE_CELULAS . '">';

for ($i = 0; $i < pg_num_rows($dadosRelatorio['itens']); $i++) {
    $itemHomologacao = db_utils::fieldsMemory($dadosRelatorio['itens'], $i);
    gerarCabecalhoFornecedor($dadosRelatorio['itens'],$i);
    gerarCabecalhoLote($itemHomologacao,$dadosRelatorio['tipoJulgamento']);
    gerarCabecalhoItem($dadosRelatorio['itens'],$i,$dadosRelatorio['criterioAdjudicacao'],$dadosRelatorio['tipoJulgamento']);
    gerarItens($itemHomologacao,$dadosRelatorio['criterioAdjudicacao']);
    gerarTotal($dadosRelatorio['itens'],$i,$codigoLicitacao,$dadosRelatorio['codigoHomologacao'],$tipoRelatorio);
}


echo '</table>';

gerarValorTotal($dadosRelatorio['valorTotal']);
gerarData();
gerarAssinatura($dadosRelatorio['responsavel']);

echo '</body>';
echo '</html>';
