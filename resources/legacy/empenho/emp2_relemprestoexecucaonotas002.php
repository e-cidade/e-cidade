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

require_once("fpdf151/pdf.php");
require_once("libs/db_liborcamento.php");
require_once("classes/db_empresto_classe.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_orcelemento_classe.php");
require_once("classes/db_orcprojativ_classe.php");
require_once("classes/db_orcdotacao_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("fpdf151/assinatura.php");
require_once("model/orcamento/DeParaRecurso.model.php");

db_postmemory($HTTP_POST_VARS);
//exit();
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$arquivo = fopen("/tmp/sigfis_restos_a_pagar.txt", 'w');

$clempresto = new cl_empresto;
$clrotulo = new rotulocampo;
$clorcelemento = new cl_orcelemento;
$clorcprojativ = new cl_orcprojativ;
$clselorcdotacao = new cl_selorcdotacao();
$oClassinatura = new cl_assinatura();

$clselorcdotacao->setDados($filtra_despesa); // passa os parametros vindos da func_selorcdotacao_abas.php

$sql_filtro = $clselorcdotacao->getDados(false);

//fun��o para retornar desdobramento
function retorna_desdob($elemento, $e64_codele, $clorcelemento)
{
    return pg_query($clorcelemento->sql_query_file(null, null, "o56_elemento as estrutural,o56_descr as descr", null, "o56_codele = $e64_codele and o56_elemento like '$elemento%'"));
}

$troca = 1;

function cabecalho(&$pdf, &$troca)
{

    if ($pdf->gety() > $pdf->h - 35 || $troca != 0) {

        $tam = "10";
        $tam2 = "5";
        $pdf->addpage("L");
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(80, $tam, "Dados dos Empenhos", 1, 0, "C", 1);
        $pdf->Cell(40, $tam, "Inscri��o", 1, 0, "C", 1);
        $alturacabecalho = $pdf->gety();
        $distanciacabecalho = $pdf->getx();
        $pdf->Cell(100, $tam2, "Movimenta��o dos Restos a Pagar no Per�odo", 1, 1, "C", 1);
        $pdf->setxy($distanciacabecalho, $alturacabecalho + 5);
        $pdf->Cell(40, $tam2, "Anula��o", 1, 0, "C", 1);
        $alturacabecalho2 = $pdf->gety();
        $distanciacabecalho2 = $pdf->getx();
        $pdf->Cell(20, $tam, "Liquida��o", 1, "TLR", "C", 1);
        $pdf->setxy($distanciacabecalho2 + 20, $alturacabecalho2);
        $pdf->Cell(40, $tam2, "Pagamento", 1, "TLR", "C", 1);
        $pdf->setxy($distanciacabecalho + 100, $alturacabecalho);
        $pdf->Cell(60, $tam, "Saldo Final de Restos a Pagar", 1, 1, "C", 1);

        $pdf->Cell(15, $tam2, "N�mero", 1, 0, "C", 1); 
        $pdf->Cell(15, $tam2, "Data", 1, 0, "C", 1);
        $pdf->Cell(50, $tam2, "Credor", 1, 0, "C", 1);

        $pdf->Cell(20, $tam2, "RP N�o Proc", 1, 0, "C", 1);
        $pdf->Cell(20, $tam2, "RP Proc", 1, 0, "C", 1);

        $pdf->Cell(20, $tam2, "RP N�o Proc", 1, 0, "C", 1);
        $pdf->Cell(20, $tam2, "RP Proc", 1, 0, "C", 1);
        $pdf->setx($pdf->getx() + 20);
        $pdf->Cell(20, $tam2, "RP N�o Proc", 1, 0, "C", 1);
        $pdf->Cell(20, $tam2, "RP Proc", 1, 0, "C", 1);

        $pdf->Cell(20, $tam2, "A Liquidar ", 1, 0, "C", 1);
        $pdf->Cell(20, $tam2, "Liquidados ", 1, 0, "C", 1);
        $pdf->Cell(20, $tam2, "Geral ", 1, 1, "C", 1);

        $pdf->SetFont('Arial', '', 7);
        $troca = 0;
        $iYlinha = $pdf->getY();

    }


}

$xinstit = explode("-", $db_selinstit);
$resultinst = pg_exec("select codigo,nomeinstabrev from db_config where codigo in (" . str_replace('-', ', ', $db_selinstit) . ") ");
$descr_inst = '';
$xvirg = '';
for ($xins = 0; $xins < pg_numrows($resultinst); $xins++) {
    db_fieldsmemory($resultinst, $xins);
    $descr_inst .= $xvirg . $nomeinstabrev;
    $xvirg = ', ';
}

$sele_work = ' e60_instit in (' . str_replace('-', ', ', $db_selinstit) . ') ';
$sele_work1 = '';//tipo de recurso
$anoatual = db_getsession("DB_anousu");
if ($tipo == "or") {
    $tipofiltro = "�rg�o";
}

if ($tipo == "un") {
    $tipofiltro = "Unidade";
}

if ($tipo == "fu") {
    $tipofiltro = "Fun��o";
}

if ($tipo == "su") {
    $tipofiltro = "Subfun��o";
}
if ($tipo == "pr") {
    $tipofiltro = "Programa";
}

if ($tipo == "pa") {
    $tipofiltro = "Projeto Atividade";
}

if ($tipo == "el") {
    $tipofiltro = "Elemento";
}

if ($tipo == "de") {
    $tipofiltro = "Desdobramento";
}
if ($tipo == "re") {
    $tipofiltro = "Recurso";
}

if ($tipo == "tr") {
    $tipofiltro = "Tipo de Resto";
}

if ($tipo == "cr") {
    $tipofiltro = "Credor";
}
/*OC5710*/
if ($tipo == "ex") {
  $tipofiltro = "Exerc�cio";
}

if ($commov == "0") {
    $commovfiltro = "Todos";
}

if ($commov == "1") {
    $commovfiltro = "Com movimento at� a data";
}

if ($commov == "2") {
    $commovfiltro = "Com saldo a pagar";
}

if ($commov == "3") {
    $commovfiltro = "Liquidados";
}
if ($commov == "4") {
    $commovfiltro = "Anulados";
}
if ($commov == "5") {
    $commovfiltro = "Pagos";
}

if ($commov == "6") {
    $commovfiltro = "N�o liquidados";
}

if ($commov=="7"){
    $commovfiltro= "Com Saldo Liquidado a Pagar";
}


//$head1 = "INSTITUI��E(S): ".$descr_inst. "\nPosi��o at�: ".$dtfim. "\nAgrupado por: ".$tipofiltro. "\nRestos a pagar:".$commovfiltro;

/*
 * Acrescentado restantes das op�oes de impressao
 */

if ($impressao == 0) {
    $sOpImpressao = 'Anal�tico';
} else {
    $sOpImpressao = 'Sint�tico';
}
if ($exercicio == 0) {
    $sExercicio = 'Todos';
} else {
    $sExercicio = $exercicio;
}
$head1 = "INSTITUI��E(S): " . $descr_inst . "\nPosi��o: $dtini at� $dtfim - Agrupado por: " . $tipofiltro . "\nRestos a pagar:" .
    $commovfiltro . "\nExercicio: " . $sExercicio . " - Op��o de Impress�o: " . $sOpImpressao;

$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(235);
$pdf->setAutoPageBreak(false);
$tam = "10";
$tam2 = "5";

//filtro por posi��o
// $sqlempresto = $clempresto->sql_rp_novo(db_getsession("DB_anousu"), $sele_work, $dtini, $dtfim, $sele_work1, $sql_where_externo, "$sql_order ");
              
$pdtini = $dtini_ano . "-" . "01". "-" . "01";
$dtini = implode("-", array_reverse(explode("/", $dtini)));
$dtfim = implode("-", array_reverse(explode("/", $dtfim)));

//filtro por agrupamento
$arr_tipos = explode(",",$vertipos);
$ultimo = count($arr_tipos)-1;
$sql_order = "";
$arr_vcampo;
$arr_campo;


for ($i = 0; $i < count($arr_tipos); $i++) {
  $nivel = substr($arr_tipos[$i],0,2);

  if ($nivel == "ex") {
    $sql_order .= " order by e60_anousu ";
    $arr_vcampo[]    = "vexercicio";
    $arr_campo[]     = "e60_anousu";
  }

  if ($nivel == "or") {// �rg�o - tabela orcdotacao
    if ($sql_order == "") {
      $sql_order .= " order by o58_orgao ";
    } else {
        $sql_order .= " , o58_orgao";
    }
    $arr_vcampo[]    = "vorgao";
    $arr_campo[]     = "o58_orgao";
  }

  if ($nivel == "un") {// unidade - tabela orcdotacao
    if ($sql_order == "") {
      $sql_order .= " order by o58_orgao, o58_unidade ";
    } else {
        $sql_order .= " , o58_unidade ";
    }
    $arr_vcampo[]    = "vunidade";
    $arr_campo[]     = "o58_unidade";
  }

  if ($nivel == "fu") {//fun��o  - tabela orcdotacao
    if ($sql_order == "") {
      $sql_order .= " order by o58_funcao ";
    } else {
        $sql_order .= " , o58_funcao";
    }
    $arr_vcampo[]    = "vfuncao";
    $arr_campo[]     = "o58_funcao";
  }

  if ($nivel == "su") {//subfun��o - tabela orcdotacao
    if ($sql_order == "") {
      $sql_order .= " order by o58_subfuncao ";
    } else {
        $sql_order .= " , o58_subfuncao ";
    }
    $arr_vcampo[]    = "vsubfuncao";
    $arr_campo[]     = "o58_subfuncao";
  }

  if ($nivel == "pr") {//programa - tabela orcdotacao
    if ($sql_order == "") {
      $sql_order .= " order by o58_programa ";
    } else {
        $sql_order .= " , o58_programa ";
    }
    $arr_vcampo[]    = "vprograma";
    $arr_campo[]     = "o58_programa";
  }

  if ($nivel == "pa") {//projeto atividade - tabela orcdotacao
    if ($sql_order == "") {
      $sql_order .= " order by o58_projativ ";
    } else {
        $sql_order .= " , o58_projativ ";
    }
    $arr_vcampo[]    = "vprojativ";
    $arr_campo[]     = "o58_projativ";
  }

  if ($nivel == "el") {//elemento - tabela orcdotacao
    if ($sql_order == "") {
      $sql_order .= " order by o56_elemento ";
    } else {
        $sql_order .= " , o56_elemento ";
    }
    $arr_vcampo[]    = "velemento";
    $arr_campo[]     = "o56_elemento";
  }


  if ($nivel == "de") {//desdobramento-tabela empelemento
    if ($sql_order == "") {
      $sql_order .= " order by e64_codele ";
    } else {
        $sql_order .= " , e64_codele ";
    }
    $arr_vcampo[]    = "vcodele";
    $arr_campo[]     = "e64_codele";
  }

  if ($nivel == "re") {//recurso - tabela empresto
    if ($sql_order == "") {
      $sql_order .= " order by e91_recurso ";
    } else {
        $sql_order .= " , e91_recurso ";
    }
    $arr_vcampo[] = "vrecurso";
    $arr_campo[] = "e91_recurso";
  }


  if ($nivel == "tr") {//resto - tabela empresto
    if ($sql_order == "") {
      $sql_order .= " order by e91_codtipo ";
    } else {
        $sql_order .= " , e91_codtipo ";
    }
    $arr_vcampo[] = "vtiporesto";
    $arr_campo[] = "e91_codtipo";
  }


  if ($nivel == "cr") {//credor - tabela cgm
    if ($sql_order == "") {
      $sql_order .= " order by z01_nome ";
    } else {
        $sql_order .= " , z01_nome ";
    }
    $arr_vcampo[] = "vnumcgm";
    $arr_campo[] = "z01_numcgm";
  }
}

if(stristr($sql_order, 'e60_anousu') === FALSE) {
    $sql_order .= " , e60_anousu, e60_codemp::bigint ";
} else {
  $sql_order .= " , e60_codemp::bigint";
}
 

//filtro por restos a pagar
$sql_where_externo = " ";
if ($commov == "0") {//geral
    $sql_where_externo .= "  ";
    
}

if ($commov == "1") {//com movimento at� a data
    $sql_where_externo = "and (round(vlranu,2) + round(vlrliq,2) + round(vlrpag,2)) > 0 and $sele_work";
}

if ($commov == "2") {//com saldo a pagar ok
    $sql_where_externo .= "and (((round(round(e91_vlremp, 2) - (round(e91_vlranu, 2) + round(vlranu, 2)), 2)) - ( round(e91_vlrpag, 2) + round(vlrpag, 2) + round(vlrpagnproc,2) )) > 0)";
}

if ($commov == "3") {//liquidados
    $sql_where_externo .= "and (round(vlrliq,2)) > 0 ";
}

if ($commov == "4") {//anulados
    $sql_where_externo .= " and (round(vlranu,2)) > 0";
}


if ($commov == "5") {//pagos
    $sql_where_externo .= "and (round(vlrpag,2) > 0 or round(vlrpagnproc,2)  > 0)";

}

if ($commov == "6") {//n�o liquidados
    $sql_where_externo .= "and (((round(round(e91_vlremp, 2) - (round(e91_vlranu, 2) + round(vlranu, 2)), 2)) - (round(e91_vlrliq, 2) + round(vlrliq, 2))) > 0) ";

}

if ($commov=="7"){//Com Saldo Liquidado a Pagar
 $sql_where_externo .= "and (((round(round(e91_vlremp, 2) - (round(e91_vlranu, 2) + round(vlranu, 2)), 2)) - ( round(e91_vlrpag, 2) + round(vlrpag, 2) + round(vlrpagnproc,2) )) - ((round(round(e91_vlremp, 2) - (round(e91_vlranu, 2) + round(vlranu, 2)), 2)) - (round(e91_vlrliq, 2) + round(vlrliq, 2))) > 0) ";

}

//filtro por exercicio
if ($exercicio != 0) {
    $sql_where_externo .= ' and e60_anousu = ' . $exercicio;
}

if ($listacredor != "") {
    if (isset ($vercredor) and $vercredor == "com") {
        $sql_where_externo .= " and e60_numcgm in  ($listacredor)";
    } else {
        $sql_where_externo .= " and e60_numcgm not in  ($listacredor)";
    }
}

$sql_where_externo .= " and " . $sql_filtro;

// Primeira consulta
$sqlempresto = $clempresto->sql_rp_novo(db_getsession("DB_anousu"), $sele_work, $pdtini, $dtfim, $sele_work1, $sql_where_externo, "$sql_order ");
$resconsulta = $clempresto->sql_record($sqlempresto); 
// die($sqlempresto);
if ($clempresto->numrows == 0) {
    db_redireciona("db_erros.php?fechar=true&db_erro=Sem movimenta��o de restos a pagar.");
    exit;
}
$rowsconsulta  = $clempresto->numrows;
// fim primeira
$sqlempresto = $clempresto->sql_rp_novo(db_getsession("DB_anousu"), $sele_work, $dtini, $dtfim, $sele_work1, $sql_where_externo, "$sql_order ");

$res = $clempresto->sql_record($sqlempresto);//die($sqlempresto);
if ($clempresto->numrows == 0) {
    db_redireciona("db_erros.php?fechar=true&db_erro=Sem movimenta��o de restos a pagar.");
    exit;
}

//echo $sqlempresto; exit;
$rows = $clempresto->numrows;

//variaveis agrupamentos
$vnumcgm = null;
$vorgao = null;
$vunidade = null;
$vfuncao = null;
$vsubfuncao = null;
$vprojativ = null;
$velemento = null;
$vdesdobramento = null;
$vrecurso = null;
$vprograma = null;
$vtiporesto = null;
$vexercicio = null;
$vcodele = null;

/*$cnumcgm = null;
$corgao = null;
$cunidade = null;
$cfuncao = null;
$csubfuncao = null;
$cprojativ = null;
$celemento = null;
$cdesdobramento = null;
$crecurso = null;
$cprograma = null;
$ctiporesto = null;
$cexercicio = null;*/

//subtotal
$vorgaosub = 0;
$vunidadesub = 0;
$vfuncaosub = 0;
$vsubfuncaosub = 0;
$vprogramasub = 0;
$vprojativsub = 0;
$velementosub = 0;
$vrecursosub = 0;
$vtiporestosub = 0;
$vnumcgmsub = 0;
$vdesdobramentosub = 0;
$vexerciciosub = 0;//OC5710

$cnumcgmanterior = 0;
$corgaoanterior = 0;
$cunidadeanterior = 0;
$cfuncaoanterior = 0;
$csubfuncaoanterior = 0;
$cprojativanterior = 0;
$celementoanterior = 0;
$cdesdobramentoanterior = 0;
$crecursoanterior = 0;
$cprogramaanterior = 0;
$ctiporestoanterior = 0;
$cexercicioanterior = 0;

$trocaexercicio = false;
$trocaorgao  = false;
$trocaorgao2 = false;
$trocaunidade = false;
$trocaunidade2 = false;
$trocafuncao = false;
$trocafuncao2 = false;
$trocasubfuncao = false;
$trocasubfuncao2 = false;
$trocaprojativ = false;
$trocaprojativ2 = false;
$trocaelemento = false;
$trocaelemento2 = false;
$trocadesdobramento = false;
$trocadesdobramento2 = false;
$trocarecurso = false;
$trocarecurso2 = false;
$trocaprograma = false;
$trocaprograma2 = false;
$trocatiporesto = false;
$trocatiporesto2 = false;

$subtotal_rp_n_proc = 0;
$subtotal_rp_proc = 0;
$subtotal_anula_rp_n_proc = 0;
$subtotal_anula_rp_proc = 0;
$subtotal_mov_liquida = 0;
$subtotal_mov_pagmento = 0;
$subtotal_mov_pagnproc = 0;
$subtotal_aliquidar_finais = 0;
$subtotal_liquidados_finais = 0;
$subtotal_geral_finais = 0;


//total
$total_rp_n_proc = 0;
$total_rp_proc = 0;

$total_anula_rp_n_proc = 0;
$total_anula_rp_proc = 0;


$total_mov_liquida = 0;
$total_mov_pagmento = 0;
$total_mov_pagnproc = 0;

$total_aliquidar_finais = 0;
$total_liquidados_finais = 0;
$total_geral_finais = 0;
//

$verifica = true;
$estrutura = "";
$projativ = "";
$o55anousu = "";
$vprojativ = "";
$uIndice = count($arr_tipos)-1;

$clDeParaFonte = new DeParaRecurso;

if ($formato != "csv") { 
    // db_criatabela($res);
    // echo "nro de linhas: ".$rows;
    // echo "nro de linhas: ".$rows;

    /** 
     * Reordenando em caso de fonte de recursos
     * Como todo o dePara est� organizado em uma classe, o controle de fonte de recursos do relat�rio n�o funciona pois seu agrupamento est� na SQL
     * O relat�rio funciona com uma base de agrupamento que acumula at� que a fonte mude, sendo assim mesmo um dePara de 100 para 1500000 n�o funciona
     * Pois quando outras fontes est�o juntas as informa��es do 100 n�o ficam com 1500000 subsequente, assim sendo, o sistema agrupa duas vezes
     * Alternativa inicial para n�o reescrever o relat�rio foi separar casos de relat�rio por fonte, agrupar em um array, aplicar o dePara, ordenar o array por fonte
     * A fun��o array_msort � responsavel por essa ordena��o do array
     * Author: widouglas
    */
    if (substr($arr_tipos[$uIndice], 0, 2) == "re") {
        $resultadoConsulta = [];
        for ($x = 0; $x < $rows; $x++) {
            $resultadoConsulta[$x] = pg_fetch_array($resconsulta, $x);
            if (db_getsession("DB_anousu") > 2022) {
                $resultadoConsulta[$x][5] = $clDeParaFonte->getDePara($resultadoConsulta[$x][5]);
                $resultadoConsulta[$x]['e91_recurso'] = $clDeParaFonte->getDePara($resultadoConsulta[$x]['e91_recurso']);
            } 
        }
        $resultadoConsulta = array_msort($resultadoConsulta, array('e91_recurso' => SORT_ASC, 'e60_emiss' => SORT_ASC));

        $i = 0;
        foreach ($resultadoConsulta as $key => $resultado) {
            $resultadoConsultaOrdenado[$i] = $resultado;
            $i++;
        }
    }
    // Final do bloco alternativo para o controle de fontes de recursos - widouglas
 

    for ($x = 0; $x < $rows; $x++) {
        db_fieldsmemory($resconsulta, $x);

        /**
         * Conversor de array para variaveis, substituindo o db_fieldsmemory para tratativas de controle de fonte de recursos - widouglas
         */
        if (substr($arr_tipos[$uIndice], 0, 2) == "re") {
            $e91_vlremp = $resultadoConsultaOrdenado[$x]['e91_vlremp']; 
            $e91_vlranu = $resultadoConsultaOrdenado[$x]['e91_vlranu'];
            $e91_vlrliq = $resultadoConsultaOrdenado[$x]['e91_vlrliq'];
            $e91_vlrpag = $resultadoConsultaOrdenado[$x] ['e91_vlrpag'];
            $e91_recurso = $resultadoConsultaOrdenado[$x]['e91_recurso'];              
            $o15_descr = $resultadoConsultaOrdenado[$x]['o15_descr'];
            $o15_codtri = $resultadoConsultaOrdenado[$x]['o15_codtri'];
            $vlranu = $resultadoConsultaOrdenado[$x]['vlranu'];
            $vlrliq = $resultadoConsultaOrdenado[$x]['vlrliq'];
            $vlrpag = $resultadoConsultaOrdenado[$x]['vlrpag']; 
            $vlrpagnproc = $resultadoConsultaOrdenado[$x]['vlrpagnproc'];
            $e91_codtipo = $resultadoConsultaOrdenado[$x]['e91_codtipo'];
            $e90_descr = $resultadoConsultaOrdenado[$x]['e90_descr'];
            $z01_numcgm = $resultadoConsultaOrdenado[$x]['z01_numcgm'];
            $z01_nome = $resultadoConsultaOrdenado[$x]['z01_nome'];
            $z01_cgccpf = $resultadoConsultaOrdenado[$x]['z01_cgccpf'];
            $e60_codemp = $resultadoConsultaOrdenado[$x]['e60_codemp'];
            $e60_emiss = $resultadoConsultaOrdenado[$x]['e60_emiss'];
            $e60_anousu = $resultadoConsultaOrdenado[$x]['e60_anousu'];
            $e64_codele = $resultadoConsultaOrdenado[$x]['e64_codele'];
            $vlranuliq = $resultadoConsultaOrdenado[$x]['vlranuliq']; 
            $vlranuliqnaoproc = $resultadoConsultaOrdenado[$x]['vlranuliqnaoproc'];
        }
        
        // inicio criando variaveis auxiliares para receber o valor da primentira consulta
        $auxliquidado_anterior = ($e91_vlremp - $e91_vlranu - $e91_vlrliq) + ($e91_vlrliq - $e91_vlrpag);
        $auxapagargeral = ($auxliquidado_anterior - $vlranu - $vlrpag - $vlrpagnproc);
        $auxaliquidargeral = $e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq - $vlranuliq));
       
        // fim
        if (substr($arr_tipos[$uIndice], 0, 2) != "re") {
            db_fieldsmemory($res, $x);
        }

        cabecalho($pdf, $troca);
        $troca = 0;

        if (substr($arr_tipos[$uIndice],0,2) == "ex") {

          if ($vexerciciosub != $e60_anousu) {

              if ($vexerciciosub != 0) {

                  $pdf->Cell(80, $tam, "Subtotal", "TBR", 0, "C", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar($subtotal_mov_liquida, 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagnproc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais), 'f'), "TBL", 1, "R", 1);


                  $subtotal_rp_n_proc = 0;
                  $subtotal_rp_proc = 0;
                  $subtotal_anula_rp_n_proc = 0;
                  $subtotal_anula_rp_proc = 0;
                  $subtotal_mov_liquida = 0;
                  $subtotal_mov_pagmento = 0;
                  $subtotal_mov_pagnproc = 0;
                  $subtotal_aliquidar_finais = 0;
                  $subtotal_liquidados_finais = 0;
                  $subtotal_geral_finais = 0;

              }

              $vexerciciosub = $e60_anousu;
          }
        }
        
        //subtotal
        if(substr($arr_tipos[$uIndice],0,2) == "or") {
          $indice   = array_search('or', $arr_tipos);
          $controle = 0;
          for ($i=0; $i <= $indice-2; $i++) {
            $controle1 = $arr_vcampo[$i];
            $controle2 = $arr_campo[$i];

            if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
              if ($$controle1 != $$controle2) {
                $controle++;
              }
            }
          }
          $controle1 = $arr_vcampo[$indice-1];
          $controle2 = $arr_campo[$indice-1];
          if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
            if ($$controle1 != $$controle2 && $vorgaosub == $o58_orgao || $controle > 0) {
              $vorgaosub = 0.1;
            }
          }

          if ($vorgaosub != $o58_orgao) {

              if ($vorgaosub != 0) {

                  $pdf->Cell(80, $tam, "Subtotal", "TBR", 0, "C", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar($subtotal_mov_liquida, 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagnproc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais), 'f'), "TBL", 1, "R", 1);


                  $subtotal_rp_n_proc = 0;
                  $subtotal_rp_proc = 0;
                  $subtotal_anula_rp_n_proc = 0;
                  $subtotal_anula_rp_proc = 0;
                  $subtotal_mov_liquida = 0;
                  $subtotal_mov_pagmento = 0;
                  $subtotal_mov_pagnproc = 0;
                  $subtotal_aliquidar_finais = 0;
                  $subtotal_liquidados_finais = 0;
                  $subtotal_geral_finais = 0;
              }

              $vorgaosub = $o58_orgao;
          }
        }
        //Unidades
        if (substr($arr_tipos[$uIndice],0,2) == "un") {

          $indice   = array_search('un', $arr_tipos);
          $controle = 0;
          for ($i=0; $i <= $indice-2; $i++) {
            $controle1 = $arr_vcampo[$i];
            $controle2 = $arr_campo[$i];

            if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
              if ($$controle1 != $$controle2) {
                $controle++;
              }
            }
          }
          $controle1 = $arr_vcampo[$indice-1];
          $controle2 = $arr_campo[$indice-1];
          if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
            if ($$controle1 != $$controle2 && $vunidadesub == $o58_unidade || $controle > 0) {
              $vunidadesub = 0.1;
            }
          }
          if ($vunidadesub != $o58_unidade) {

            if ($vunidadesub != 0) {
                $pdf->Cell(80, $tam, "Subtotal", "TBR", 0, "C", 1);
                $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc), 'f'), 1, 0, "R", 1);
                $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc), 'f'), 1, 0, "R", 1);
                $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc), 'f'), 1, 0, "R", 1);
                $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc), 'f'), 1, 0, "R", 1);
                $pdf->Cell(20, $tam, db_formatar($subtotal_mov_liquida, 'f'), 1, 0, "R", 1);
                $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagnproc), 'f'), 1, 0, "R", 1);
                $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento), 'f'), 1, 0, "R", 1);
                $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais), 'f'), 1, 0, "R", 1);
                $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais), 'f'), 1, 0, "R", 1);
                $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais), 'f'), "TBL", 1, "R", 1);


                $subtotal_rp_n_proc = 0;
                $subtotal_rp_proc = 0;
                $subtotal_anula_rp_n_proc = 0;
                $subtotal_anula_rp_proc = 0;
                $subtotal_mov_liquida = 0;
                $subtotal_mov_pagmento = 0;
                $subtotal_mov_pagnproc = 0;
                $subtotal_aliquidar_finais = 0;
                $subtotal_liquidados_finais = 0;
                $subtotal_geral_finais = 0;

            }

            $vunidadesub = $o58_unidade;

          }
        }

        //Fun��es
        if (substr($arr_tipos[$uIndice],0,2) == "fu") {

          $indice   = array_search('fu', $arr_tipos);
          $controle = 0;
          for ($i=0; $i <= $indice-2; $i++) {
            $controle1 = $arr_vcampo[$i];
            $controle2 = $arr_campo[$i];

            if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
              if ($$controle1 != $$controle2) {
                $controle++;
              }
            }
          }
          $controle1 = $arr_vcampo[$indice-1];
          $controle2 = $arr_campo[$indice-1];
          if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
            if ($$controle1 != $$controle2 && $vfuncaosub == $o58_funcao || $controle > 0) {
              $vfuncaosub = 0.1;
            }
          }
          if ($vfuncaosub != $o58_funcao) {
              if ($vfuncaosub != 0) {
                  $pdf->Cell(80, $tam, "Subtotal", "TBR", 0, "C", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar($subtotal_mov_liquida, 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagnproc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais), 'f'), "TBL", 1, "R", 1);


                  $subtotal_rp_n_proc = 0;
                  $subtotal_rp_proc = 0;
                  $subtotal_anula_rp_n_proc = 0;
                  $subtotal_anula_rp_proc = 0;
                  $subtotal_mov_liquida = 0;
                  $subtotal_mov_pagmento = 0;
                  $subtotal_mov_pagnproc = 0;
                  $subtotal_aliquidar_finais = 0;
                  $subtotal_liquidados_finais = 0;
                  $subtotal_geral_finais = 0;

              }
              $vfuncaosub = $o58_funcao;
          }
        }

        // Subfun��es
        if (substr($arr_tipos[$uIndice],0,2) == "su") {
          $indice   = array_search('su', $arr_tipos);
          $controle = 0;
          for ($i=0; $i <= $indice-2; $i++) {
            $controle1 = $arr_vcampo[$i];
            $controle2 = $arr_campo[$i];

            if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
              if ($$controle1 != $$controle2) {
                $controle++;
              }
            }
          }
          $controle1 = $arr_vcampo[$indice-1];
          $controle2 = $arr_campo[$indice-1];
          if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
            if ($$controle1 != $$controle2 && $vsubfuncaosub == $o58_subfuncao || $controle > 0) {
              $vsubfuncaosub = 0.1;
            }
          }
          if ($vsubfuncaosub != $o58_subfuncao) {
              if ($vsubfuncaosub != 0) {

                  $pdf->Cell(80, $tam, "Subtotal", "TBR", 0, "C", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar($subtotal_mov_liquida, 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagnproc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais), 'f'), "TBL", 1, "R", 1);


                  $subtotal_rp_n_proc = 0;
                  $subtotal_rp_proc = 0;
                  $subtotal_anula_rp_n_proc = 0;
                  $subtotal_anula_rp_proc = 0;
                  $subtotal_mov_liquida = 0;
                  $subtotal_mov_pagmento = 0;
                  $subtotal_mov_pagnproc = 0;
                  $subtotal_aliquidar_finais = 0;
                  $subtotal_liquidados_finais = 0;
                  $subtotal_geral_finais = 0;

              }
              $vsubfuncaosub = $o58_subfuncao;
          }
        }

        // Programa
        if (substr($arr_tipos[$uIndice],0,2) == "pr") {
          $indice   = array_search('pr', $arr_tipos);
          $controle = 0;
          for ($i=0; $i < $indice-1; $i++) {
            $controle1 = $arr_vcampo[$i];
            $controle2 = $arr_campo[$i];

            if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
              if ($$controle1 != $$controle2) {
                $controle++;
              }
            }
          }
          $controle1 = $arr_vcampo[$indice-1];
          $controle2 = $arr_campo[$indice-1];
          if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
            if ($$controle1 != $$controle2 && $vprogramasub == $o58_programa || $controle > 0) {
              $vprogramasub = 0.1;
            }
          }
          if ($vprogramasub != $o58_programa) {
              if ($vprogramasub != 0) {

                  $pdf->Cell(80, $tam, "Subtotal", "TBR", 0, "C", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar($subtotal_mov_liquida, 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagnproc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais), 'f'), "TBL", 1, "R", 1);


                  $subtotal_rp_n_proc = 0;
                  $subtotal_rp_proc = 0;
                  $subtotal_anula_rp_n_proc = 0;
                  $subtotal_anula_rp_proc = 0;
                  $subtotal_mov_liquida = 0;
                  $subtotal_mov_pagmento = 0;
                  $subtotal_mov_pagnproc = 0;
                  $subtotal_aliquidar_finais = 0;
                  $subtotal_liquidados_finais = 0;
                  $subtotal_geral_finais = 0;

              }
              $vprogramasub = $o58_programa;
          }
        }

        // Projeto Ativo
        if (substr($arr_tipos[$uIndice],0,2) == "pa") {
          $indice   = array_search('pa', $arr_tipos);
          $controle = 0;
          for ($i=0; $i < $indice-1; $i++) {
            $controle1 = $arr_vcampo[$i];
            $controle2 = $arr_campo[$i];

            if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
              if ($$controle1 != $$controle2) {
                $controle++;
              }
            }
          }
          $controle1 = $arr_vcampo[$indice-1];
          $controle2 = $arr_campo[$indice-1];
          if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
            if ($$controle1 != $$controle2 && $vprojativsub == $o58_projativ || $controle > 0) {
              $vprojativsub = 0.1;
            }
          }
          if ($vprojativsub != $o58_projativ) {
              if ($vprojativsub != 0) {

                  $pdf->Cell(80, $tam, "Subtotal", "TBR", 0, "C", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar($subtotal_mov_liquida, 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagnproc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais), 'f'), "TBL", 1, "R", 1);


                  $subtotal_rp_n_proc = 0;
                  $subtotal_rp_proc = 0;
                  $subtotal_anula_rp_n_proc = 0;
                  $subtotal_anula_rp_proc = 0;
                  $subtotal_mov_liquida = 0;
                  $subtotal_mov_pagmento = 0;
                  $subtotal_mov_pagnproc = 0;
                  $subtotal_aliquidar_finais = 0;
                  $subtotal_liquidados_finais = 0;
                  $subtotal_geral_finais = 0;

              }
              $vprojativsub = $o58_projativ;
          }
        }

        // Elemento
        if(substr($arr_tipos[$uIndice],0,2) == "el") {
          $indice   = array_search('el', $arr_tipos);
          $controle = 0;
          for ($i=0; $i < $indice-1; $i++) {
            $controle1 = $arr_vcampo[$i];
            $controle2 = $arr_campo[$i];

            if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
              if ($$controle1 != $$controle2) {
                $controle++;
              }
            }
          }
          $controle1 = $arr_vcampo[$indice-1];
          $controle2 = $arr_campo[$indice-1];
          if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
            if ($$controle1 != $$controle2 && $velementosub == $o56_elemento || $controle > 0) {
              $velementosub = 0.1;
            }
          }
          if ($velementosub != $o56_elemento) {
              if ($velementosub != 0) {

                  $pdf->Cell(80, $tam, "Subtotal", "TBR", 0, "C", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar($subtotal_mov_liquida, 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagnproc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais), 'f'), "TBL", 1, "R", 1);


                  $subtotal_rp_n_proc = 0;
                  $subtotal_rp_proc = 0;
                  $subtotal_anula_rp_n_proc = 0;
                  $subtotal_anula_rp_proc = 0;
                  $subtotal_mov_liquida = 0;
                  $subtotal_mov_pagmento = 0;
                  $subtotal_mov_pagnproc = 0;
                  $subtotal_aliquidar_finais = 0;
                  $subtotal_liquidados_finais = 0;
                  $subtotal_geral_finais = 0;
              }
              $velementosub = $o56_elemento;
          }
        }

        // Desdobramento
        if (substr($arr_tipos[$uIndice],0,2) == "de") {
          $indice   = array_search('de', $arr_tipos);
          $controle = 0;
          for ($i=0; $i < $indice-1; $i++) {
            $controle1 = $arr_vcampo[$i];
            $controle2 = $arr_campo[$i];

            if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
              if ($$controle1 != $$controle2) {
                $controle++;
              }
            }
          }
          $controle1 = $arr_vcampo[$indice-1];
          $controle2 = $arr_campo[$indice-1];
          if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
            if ($$controle1 != $$controle2 && $vdesdobramentosub == $e64_codele || $controle > 0) {
              $vdesdobramentosub = 0.1;
            }
          }
          if ($vdesdobramentosub != $e64_codele) {
              if ($vdesdobramentosub != 0) {

                  $pdf->Cell(80, $tam, "Subtotal", "TBR", 0, "C", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar($subtotal_mov_liquida, 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagnproc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais), 'f'), "TBL", 1, "R", 1);


                  $subtotal_rp_n_proc = 0;
                  $subtotal_rp_proc = 0;
                  $subtotal_anula_rp_n_proc = 0;
                  $subtotal_anula_rp_proc = 0;
                  $subtotal_mov_liquida = 0;
                  $subtotal_mov_pagmento = 0;
                  $subtotal_mov_pagnproc = 0;
                  $subtotal_aliquidar_finais = 0;
                  $subtotal_liquidados_finais = 0;
                  $subtotal_geral_finais = 0;
              }
              $vdesdobramentosub = $e64_codele;
          }
        }

        // Recurso
        if (substr($arr_tipos[$uIndice], 0, 2) == "re") {
            $indice = array_search('re', $arr_tipos);
            $controle = 0;

            for ($i = 0; $i < $indice - 1; $i++) {
                $controle1 = $arr_vcampo[$i];
                $controle2 = $arr_campo[$i];

                if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
                    if ($$controle1 != $$controle2) {
                        $controle++;
                    }
                }
            }
            $controle1 = $arr_vcampo[$indice - 1];
            $controle2 = $arr_campo[$indice - 1];

            if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
                if ($$controle1 != $$controle2 && $vrecursosub == $e91_recurso || $controle > 0) {
                    $vrecursosub = 0.1;
                }
            }

            if ($vrecursosub != $e91_recurso) {
                if ($vrecursosub != 0) {
                    $pdf->Cell(80, $tam, "Subtotal", "TBR", 0, "C", 1);
                    $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc), 'f'), 1, 0, "R", 1);
                    $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc), 'f'), 1, 0, "R", 1);
                    $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc), 'f'), 1, 0, "R", 1);
                    $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc), 'f'), 1, 0, "R", 1);
                    $pdf->Cell(20, $tam, db_formatar($subtotal_mov_liquida, 'f'), 1, 0, "R", 1);
                    $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagnproc), 'f'), 1, 0, "R", 1);
                    $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento), 'f'), 1, 0, "R", 1);
                    $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais), 'f'), 1, 0, "R", 1);
                    $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais), 'f'), 1, 0, "R", 1);
                    $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais), 'f'), "TBL", 1, "R", 1);


                    $subtotal_rp_n_proc = 0;
                    $subtotal_rp_proc = 0;
                    $subtotal_anula_rp_n_proc = 0;
                    $subtotal_anula_rp_proc = 0;
                    $subtotal_mov_liquida = 0;
                    $subtotal_mov_pagmento = 0;
                    $subtotal_mov_pagnproc = 0;
                    $subtotal_aliquidar_finais = 0;
                    $subtotal_liquidados_finais = 0;
                    $subtotal_geral_finais = 0;
                }
                $vrecursosub = $e91_recurso;
            }
        }

        // Tipo de resto
        if (substr($arr_tipos[$uIndice],0,2) == "tr") {
          $indice   = array_search('tr', $arr_tipos);
          $controle = 0;
          for ($i=0; $i < $indice-1; $i++) {
            $controle1 = $arr_vcampo[$i];
            $controle2 = $arr_campo[$i];

            if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
              if ($$controle1 != $$controle2) {
                $controle++;
              }
            }
          }
          $controle1 = $arr_vcampo[$indice-1];
          $controle2 = $arr_campo[$indice-1];
          if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
            if ($$controle1 != $$controle2 && $vtiporestosub == $e91_codtipo || $controle > 0) {
              $vtiporestosub = 0.1;
            }
          }
          if ($vtiporestosub != $e91_codtipo) {
              if ($vtiporestosub != 0) {

                  $pdf->Cell(80, $tam, "Subtotal", "TBR", 0, "C", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar($subtotal_mov_liquida, 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagnproc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais), 'f'), "TBL", 1, "R", 1);


                  $subtotal_rp_n_proc = 0;
                  $subtotal_rp_proc = 0;
                  $subtotal_anula_rp_n_proc = 0;
                  $subtotal_anula_rp_proc = 0;
                  $subtotal_mov_liquida = 0;
                  $subtotal_mov_pagmento = 0;
                  $subtotal_mov_pagnproc = 0;
                  $subtotal_aliquidar_finais = 0;
                  $subtotal_liquidados_finais = 0;
                  $subtotal_geral_finais = 0;

              }
              $vtiporestosub = $e91_codtipo;
          }
        }

        // Credor
        if (substr($arr_tipos[$uIndice],0,2) == "cr") {
          $indice   = array_search('cr', $arr_tipos);
          $controle = 0;
          for ($i=0; $i < $indice-1; $i++) {
            $controle1 = $arr_vcampo[$i];
            $controle2 = $arr_campo[$i];

            if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
              if ($$controle1 != $$controle2) {
                $controle++;
              }
            }
          }
          $controle1 = $arr_vcampo[$indice-1];
          $controle2 = $arr_campo[$indice-1];
          if ($controle1 != "" && $controle2 != "" && $$controle1 != "") {
            if ($$controle1 != $$controle2 && $vnumcgmsub == $z01_numcgm || $controle > 0) {
              $vnumcgmsub = 0.1;
            }
          }
          if ($vnumcgmsub != $z01_numcgm) {
              if ($vnumcgmsub != 0) {

                  $pdf->Cell(80, $tam, "Subtotal", "TBR", 0, "C", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar($subtotal_mov_liquida, 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagnproc), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais), 'f'), 1, 0, "R", 1);
                  $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais), 'f'), "TBL", 1, "R", 1);


                  $subtotal_rp_n_proc = 0;
                  $subtotal_rp_proc = 0;
                  $subtotal_anula_rp_n_proc = 0;
                  $subtotal_anula_rp_proc = 0;
                  $subtotal_mov_liquida = 0;
                  $subtotal_mov_pagmento = 0;
                  $subtotal_mov_pagnproc = 0;
                  $subtotal_aliquidar_finais = 0;
                  $subtotal_liquidados_finais = 0;
                  $subtotal_geral_finais = 0;

              }
              $vnumcgmsub = $z01_numcgm;
          }
        }


        //filtro por:
        //Exerc�cio
        if (in_array("ex", $arr_tipos)) {

          if ($vexercicio != $e60_anousu) {
            if (isset($quebradepagina) and $verifica == false || ($pdf->getY() >= $pdf->h - 30)) {
                $troca = 1;
                cabecalho($pdf, $troca);
            }

            $pdf->SetFont('Arial', 'B', 7);
            $pdf->cell(0, 2, "", 0, 1, "", 0);
            $pdf->cell(0, 5, "Exerc�cio: $e60_anousu ", 0, 1, "L", 0);
            $vexercicio = $e60_anousu;
            $verifica = false;
            $indice  = array_search('ex', $arr_tipos);
            $vcampo = $arr_vcampo[$indice+1];
            $$vcampo = null;
          }
        }

        //Org�o
        if (in_array("or", $arr_tipos)) {

          $indice  = array_search('or', $arr_tipos);

          if ($vorgao != $o58_orgao) {

            if ((isset($quebradepagina) and $verifica == false) || ($pdf->getY() >= $pdf->h - 30)) {
                $troca = 1;
                cabecalho($pdf, $troca);
            }

            $pdf->SetFont('Arial', 'B', 7);

            $pdf->cell(0, 5, "Org�o: $o58_orgao $o40_descr ", 0, 1, "L", 0);
            $vorgao = $o58_orgao;
            $verifica = false;
            $vcampo = $arr_vcampo[$indice+1];
            $$vcampo = null;

          }
        }
        //Unidade
        if (in_array("un", $arr_tipos)) {

          if ($vunidade != $o58_unidade) {

            if ((isset($quebradepagina) and $verifica == false) || ($pdf->getY() >= $pdf->h - 30)) {
                $troca = 1;
                cabecalho($pdf, $troca);
            }

            $pdf->SetFont('Arial', 'B', 7);
            if(!in_array("or", $arr_tipos)) {
              $pdf->cell(0, 5, "�rg�o:$o58_orgao $o40_descr  ", 0, 1, "L", 0);
            }
            $pdf->cell(0, 5, "Unidade:$o58_unidade $o41_descr  ", 0, 1, "L", 0);
            $vunidade = $o58_unidade;
            $verifica = false;
            $indice  = array_search('un', $arr_tipos);
            $vcampo = $arr_vcampo[$indice+1];
            $$vcampo = null;
          }
        }
        //Fun��o
        if (in_array("fu", $arr_tipos)) {
          if ($vfuncao != $o58_funcao) {

            if ((isset($quebradepagina) and $verifica == false) || ($pdf->getY() >= $pdf->h - 30)) {
                $troca = 1;
                cabecalho($pdf, $troca);
            }

            $pdf->SetFont('Arial', 'B', 7);
            //$pdf->cell(0, 2, "", 0, 1, "", 0);
            $pdf->cell(0, 5, "Fun��o:$o58_funcao $o52_descr", 0, 1, "L", 0);
            $vfuncao = $o58_funcao;
            $verifica = false;
            $indice  = array_search('fu', $arr_tipos);
            $vcampo = $arr_vcampo[$indice+1];
            $$vcampo = null;
          }
        }
        //SubFun��o
        if (in_array("su", $arr_tipos)) {
         if ($vsubfuncao != $o58_subfuncao) {
            if ((isset($quebradepagina) and $verifica == false) || ($pdf->getY() >= $pdf->h - 30)) {
                $troca = 1;
                cabecalho($pdf, $troca);
            }
            $pdf->SetFont('Arial', 'B', 7);
            //$pdf->cell(0, 2, "", 0, 1, "", 0);
            $pdf->cell(0, 5, "Subfun��o:$o58_subfuncao $o53_descr  ", 0, 1, "L", 0);
            $vsubfuncao = $o58_subfuncao;
            $verifica = false;
            $indice  = array_search('su', $arr_tipos);
            $vcampo = $arr_vcampo[$indice+1];
            $$vcampo = null;
          }
        }

        //Programa
        if (in_array("pr", $arr_tipos)) {

          if ($vprograma != $o58_programa) {
              if ((isset($quebradepagina) and $verifica == false) || ($pdf->getY() >= $pdf->h - 30)) {
                  $troca = 1;
                  cabecalho($pdf, $troca);
              }
              $pdf->SetFont('Arial', 'B', 7);
              //$pdf->cell(0, 2, "", 0, 1, "", 0);
              $pdf->cell(0, 5, "Programa:$o58_programa $o54_descr ", 0, 1, "L", 0);
              $vprograma = $o58_programa;
              $verifica = false;
              $indice  = array_search('pr', $arr_tipos);
              $vcampo = $arr_vcampo[$indice+1];
              $$vcampo = null;
          }
        }

        //Projeto atividade
        if (in_array("pa", $arr_tipos)) {

          $indice  = array_search('pa', $arr_tipos);
          if ($vprojativ != $o58_projativ) {
            if ((isset($quebradepagina) and $verifica == false) || ($pdf->getY() >= $pdf->h - 30)) {
                $troca = 1;
                cabecalho($pdf, $troca);
            }
            if ($vprojativ != $o58_projativ or $o55anousu != $e60_anousu) {

                $pdf->SetFont('Arial', 'B', 7);
                //$pdf->cell(0, 2, "", 0, 1, "", 0);
                $pdf->cell(0, 5, "Projeto/atividade:$o58_projativ $o55_descr", 0, 1, "L", 0);
                $projativ = $o58_projativ;
                $vprojativ = $o58_projativ;
                $o55anousu = $e60_anousu;
                $vcampo = $arr_vcampo[$indice+1];
                $$vcampo = null;
            }
            $verifica = false;

          }
        }
        //Elemento
        if (in_array("el", $arr_tipos)) {

          if ($velemento != $o56_elemento) {
            if ((isset($quebradepagina) and $verifica == false) || ($pdf->getY() >= $pdf->h - 30)) {
              $troca = 1;
              cabecalho($pdf, $troca);
            }
              $pdf->SetFont('Arial', 'B', 7);
              //$pdf->cell(0, 2, "", 0, 1, "", 0);
              $pdf->cell(0, 5, "Elemento:$o56_elemento  $o56_descr  ", 0, 1, "L", 0);
              $velemento = $o56_elemento;
              $verifica = false;
              $indice  = array_search('el', $arr_tipos);
              $vcampo = $arr_vcampo[$indice+1];
              $$vcampo = null;
          }
        }

        //Desdobramento
        if (in_array("de", $arr_tipos)) {//desdobramento

         $resdesdob = retorna_desdob(substr($o56_elemento, 0, 7), $e64_codele, $clorcelemento);
         $numrows = pg_numrows($resdesdob);

          for ($i = 0; $i < $numrows; $i++) {
            db_fieldsmemory($resdesdob, $i);
            if ($estrutura != $estrutural) {
                if ((isset($quebradepagina) and $verifica == false) || ($pdf->getY() >= $pdf->h - 30)) {
                    $troca = 1;
                    cabecalho($pdf, $troca);
                }

                /*if(substr($arr_tipos[0],0,2) == "de") {
                  $pdf->cell(0, 2, "", 0, 1, "", 0);
                }*/
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->cell(0, 5, "Desdobramento:" . $estrutural . " " . $descr. " - " .$e64_codele, 0, 1, "L", 0);
                $estrutura = $estrutural;
                $verifica = false;
            }
          }
          if ($vcodele != $e64_codele) {
            $vcodele = $e64_codele;
            $indice  = array_search('de', $arr_tipos);
            $vcampo  = $arr_vcampo[$indice+1];
            $$vcampo = null;
          }
        }

        // Recurso
        if (in_array("re", $arr_tipos)) {
          if ($vrecurso != $e91_recurso) {
              if ((isset($quebradepagina) and $verifica == false) || ($pdf->getY() >= $pdf->h - 30)) {
                  $troca = 1;
                  cabecalho($pdf, $troca);
              }
              /*if(substr($arr_tipos[0],0,2) == "re") {
                $pdf->cell(0, 2, "", 0, 1, "", 0);
              }*/
              $pdf->SetFont('Arial', 'B', 7);
              $pdf->cell(0, 5, "Recurso:" . $e91_recurso . " $o15_descr  ", 0, 1, "L", 0);
            
              $vrecurso = $e91_recurso;
              $verifica = false;
              $indice  = array_search('re', $arr_tipos);
              $vcampo = $arr_vcampo[$indice+1];
              $$vcampo = null;
          }
        }

        //Resto
        if (in_array("tr", $arr_tipos)) {

         if ($vtiporesto != $e91_codtipo) {
            if ((isset($quebradepagina) and $verifica == false) || ($pdf->getY() >= $pdf->h - 30)) {
                $troca = 1;
                cabecalho($pdf, $troca);
            }
            if(substr($arr_tipos[0],0,2) == "tr") {
              $pdf->cell(0, 2, "", 0, 1, "", 0);
            }
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->cell(0, 5, "Tipo de resto: $e91_codtipo $e90_descr   ", 0, 1, "L", 0);
            $vtiporesto = $e91_codtipo;
            $verifica = false;
            $indice  = array_search('tr', $arr_tipos);
            $vcampo = $arr_vcampo[$indice+1];
            $$vcampo = null;
          }
        }

        //Credor
        if (in_array("cr", $arr_tipos)) {
         if ($vnumcgm != $z01_numcgm) {
            if ((isset($quebradepagina) and $verifica == false) || ($pdf->getY() >= $pdf->h - 30)) {
                $troca = 1;
                cabecalho($pdf, $troca);
            }
            if(substr($arr_tipos[0],0,2) == "cr") {
              $pdf->cell(0, 2, "", 0, 1, "", 0);
            }
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->cell(0, 5, "Credor:" . $z01_numcgm . " CNPJ:" . db_formatar($z01_cgccpf, 'cnpj') . " " . substr($z01_nome, 0, 100), 0, 1, "L", 0);
            $vnumcgm = $z01_numcgm;
            $verifica = false;
          }
        }
        /*--------------------------------FIM Credor--------------------------------*/

        //dados do relat�rio
        $pdf->SetFont('Arial', '', 7);
        $tam = "5";

        $total_rp_n_proc += ($e91_vlremp - $e91_vlranu - $e91_vlrliq);
        $total_rp_proc += ($e91_vlrliq - $e91_vlrpag);
        $total_anula_rp_n_proc += $vlranuliqnaoproc;
        $total_anula_rp_proc += $vlranuliq;
        $total_mov_liquida += ($vlrliq);
        $total_mov_pagmento += $vlrpag;
        $total_mov_pagnproc += $vlrpagnproc;

        // Dados recebidos da varievesi aux;
        $liquidado_anterior = $auxliquidado_anterior; 
        $apagargeral = $auxapagargeral;
        $aliquidargeral=$auxaliquidargeral;      
        $liquidados = ($apagargeral - $aliquidargeral);
        $total_aliquidar_finais = $total_aliquidar_finais + $aliquidargeral;
        $total_liquidados_finais = $total_liquidados_finais + abs($liquidados);
        $total_geral_finais = ($total_geral_finais + $apagargeral);
       
        if ($impressao == '0') { 
            //dados cadastrais dos empenhos
            $pdf->Cell(15, $tam, ($e60_codemp . "/" . $e60_anousu), "TBR", 0, "R", 0);//empenho
            $pdf->Cell(15, $tam, db_formatar($e60_emiss, 'd'), 1, 0, "C", 0);//emissao
            $pdf->Cell(50, $tam, substr($z01_nome, 0, 25), 1, 0, "L", 0);//credor

            //saldos a pagar anteriores
            $pdf->Cell(20, $tam, db_formatar(abs($e91_vlremp - $e91_vlranu - $e91_vlrliq), 'f'), 1, 0, "R", 0);// rp nao proc

            $pdf->Cell(20, $tam, db_formatar(abs($e91_vlrliq - $e91_vlrpag), 'f'), 1, 0, "R", 0);//rp proc

            //movimenta��o dos restos a pagar no per�odo
            $pdf->Cell(20, $tam, db_formatar(abs($vlranuliqnaoproc), 'f'), 1, 0, "R", 0);//anulacao -> rp nao proc

            $pdf->Cell(20, $tam, db_formatar(abs($vlranuliq), 'f'), 1, 0, "R", 0);//anulacao -> rp proc
           
            if ($c70_anousu == $anoatual) {
                $pdf->Cell(20, $tam, db_formatar($vlrliq, 'f'), 1, 0, "R", 0);//liquidado=rpproc
               
            } else {
                           
                $pdf->Cell(20, $tam, db_formatar("0", 'f'), 1, 0, "R", 0);//liquidado=rpproc
            }

            $pdf->Cell(20, $tam, db_formatar(abs($vlrpagnproc), 'f'), 1, 0, "R", 0);//pagamento
            $pdf->Cell(20, $tam, db_formatar(abs($vlrpag), 'f'), 1, 0, "R", 0);//pagamento

            // a liquidar
            $pdf->Cell(20, $tam, db_formatar(abs($aliquidargeral), 'f'), 1, 0, "R", 0);

            // liquidados
            $pdf->Cell(20, $tam, db_formatar(abs($liquidados), 'f'), 1, 0, "R", 0);

            // a pagar
            $pdf->Cell(20, $tam, db_formatar(abs($apagargeral), 'f'), "TBL", 1, "R", 0);

        }
        //subtotal
        $subtotal_rp_n_proc += $e91_vlremp - $e91_vlranu - $e91_vlrliq;
        $subtotal_rp_proc += $e91_vlrliq - $e91_vlrpag;
        $subtotal_anula_rp_n_proc += $vlranuliqnaoproc;
        $subtotal_anula_rp_proc += $vlranuliq;
        $subtotal_mov_liquida += $vlrliq;
        $subtotal_mov_pagmento += $vlrpag;
        $subtotal_mov_pagnproc += $vlrpagnproc;
        $subtotal_aliquidar_finais += $aliquidargeral;
        $subtotal_liquidados_finais += abs($liquidados);
        $subtotal_geral_finais += $apagargeral;

        $linha = " 5252014";
        $linha .= str_pad($e60_codemp, 10, ' ', STR_PAD_LEFT);
        $linha .= str_pad($o58_unidade, 10, ' ', STR_PAD_RIGHT);
        $linha .= str_pad($o58_orgao, 10, ' ', STR_PAD_RIGHT);
        fputs($arquivo, "$linha\r\n");

    } // Final do ($x = 0; $x < $rows; $x++)


    if ($subtotal_rp_n_proc != 0 ||
        $subtotal_rp_proc != 0 ||
        $subtotal_anula_rp_n_proc != 0 ||
        $subtotal_anula_rp_proc != 0 ||
        $subtotal_mov_liquida != 0 ||
        $subtotal_mov_pagmento != 0 ||
        $subtotal_mov_pagnproc != 0 ||
        $subtotal_aliquidar_finais != 0 ||
        $subtotal_liquidados_finais != 0 ||
        $subtotal_geral_finais != 0
    ) {
        $pdf->Cell(80, $tam, "Subtotal", "TBR", 0, "C", 1);
        $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc), 'f'), 1, 0, "R", 1);
        $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc), 'f'), 1, 0, "R", 1);
        $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc), 'f'), 1, 0, "R", 1);
        $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc), 'f'), 1, 0, "R", 1);
        $pdf->Cell(20, $tam, db_formatar($subtotal_mov_liquida, 'f'), 1, 0, "R", 1);
        $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagnproc), 'f'), 1, 0, "R", 1);
        $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento), 'f'), 1, 0, "R", 1);
        $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais), 'f'), 1, 0, "R", 1);
        $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais), 'f'), 1, 0, "R", 1);
        $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais), 'f'), "TBL", 1, "R", 1);

    }
    // die("<br>vlr da liquidacao: ".$total_mov_liquida);

    $pdf->ln(2);
    $pdf->Cell(80, $tam, "Total", "TBR", 0, "C", 1);
    $pdf->Cell(20, $tam, db_formatar(abs($total_rp_n_proc), 'f'), 1, 0, "R", 1);
    $pdf->Cell(20, $tam, db_formatar(abs($total_rp_proc), 'f'), 1, 0, "R", 1);
    $pdf->Cell(20, $tam, db_formatar(abs($total_anula_rp_n_proc), 'f'), 1, 0, "R", 1);
    $pdf->Cell(20, $tam, db_formatar(abs($total_anula_rp_proc), 'f'), 1, 0, "R", 1);
    $pdf->Cell(20, $tam, db_formatar($total_mov_liquida, 'f'), 1, 0, "R", 1);
    $pdf->Cell(20, $tam, db_formatar(abs($total_mov_pagnproc), 'f'), 1, 0, "R", 1);
    $pdf->Cell(20, $tam, db_formatar(abs($total_mov_pagmento), 'f'), 1, 0, "R", 1);
    $pdf->Cell(20, $tam, db_formatar(abs($total_aliquidar_finais), 'f'), 1, 0, "R", 1);
    $pdf->Cell(20, $tam, db_formatar(abs($total_liquidados_finais), 'f'), 1, 0, "R", 1);
    $pdf->Cell(20, $tam, db_formatar(abs($total_geral_finais), 'f'), "TBL", 1, "R", 1);

    /*
     *Melhoria para impressao dos filtros selecionados
     */
    $pdf->SetAutoPageBreak(true, 20);
    $pdf->widths = array(200);
//-- imprime parametros
    $imprime_filtro = $_POST['imprimefiltros'];
    if (isset($imprime_filtro) && ($imprime_filtro == 'sim')) {
        $pdf->AddPage('L');
        $pdf->SetFont("Arial", "", 6);
        $pdf->Ln(10);
        $sParametros = $clselorcdotacao->getParametros();
        $aParametros = array($sParametros);

        $resto = $pdf->multicell(270, $tam, $sParametros, 1);
    }

    $pdf->Ln(17);
    assinaturas($pdf, $oClassinatura, 'LRF', true, false);

    fclose($arquivo);

    $pdf->output();
} else {
    $fp = fopen("tmp/execucaorsp.csv", "w");
    fputs($fp, "Empenho;Emissao;Credor;RP nao proc anterior;RP proc anterior;RP nao proc anulacao;RP proc anulacao;Liquidacao;RP nao proc pagamento;RP proc pagamento;A liquidar;Liquidados;Geral\n");
    for ($x = 0; $x < $rows; $x++) {
        db_fieldsmemory($res, $x);
        $total_rp_n_proc += ($e91_vlremp - $e91_vlranu - $e91_vlrliq);
        $total_rp_proc += ($e91_vlrliq - $e91_vlrpag);
        $total_anula_rp_n_proc += $vlranuliqnaoproc;
        $total_anula_rp_proc += $vlranuliq;
        $total_mov_liquida += ($vlrliq);
        $total_mov_pagmento += $vlrpag;
        $total_mov_pagnproc += $vlrpagnproc;
        $liquidado_anterior = ($e91_vlremp - $e91_vlranu - $e91_vlrliq) + ($e91_vlrliq - $e91_vlrpag);
        $apagargeral = ($liquidado_anterior - $vlranu - $vlrpag - $vlrpagnproc);
        $aliquidargeral = $e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq - $vlranuliq));
        $liquidados = ($apagargeral - $aliquidargeral);
        $total_aliquidar_finais = $total_aliquidar_finais + $aliquidargeral;
        $total_liquidados_finais = $total_liquidados_finais + abs($liquidados);
        $total_geral_finais = ($total_geral_finais + $apagargeral);
       
        fputs($fp, ($e60_codemp . "/" . $e60_anousu) . ";" . db_formatar($e60_emiss, 'd') . ";" . $z01_nome . ";" . db_formatar(abs($e91_vlremp - $e91_vlranu - $e91_vlrliq), 'f') . ";");
        fputs($fp, db_formatar(abs($e91_vlrliq - $e91_vlrpag), 'f') . ";" . db_formatar(abs($vlranuliqnaoproc), 'f') . ";" . db_formatar(abs($vlranuliq), 'f') . ";" . ($c70_anousu == $anoatual ? db_formatar(abs($vlrliq), 'f') : 0) . ";");
        fputs($fp, db_formatar(abs($vlrpagnproc), 'f') . ";" . db_formatar(abs($vlrpag), 'f') . ";" . db_formatar(abs($aliquidargeral), 'f') . ";" . db_formatar(abs($liquidados), 'f') . ";" . db_formatar(abs($apagargeral), 'f') . "\n");
    }
    echo "<html><body bgcolor='#cccccc'><center><a href='tmp/execucaorsp.csv'>Clique com bot�o direito para Salvar o arquivo <b>execucaorsp.csv</b></a></body></html>";
    fclose($fp);
    exit;
}

function array_msort($array, $cols)
{
    $colarr = array();
    foreach ($cols as $col => $order) {
        $colarr[$col] = array();
        foreach ($array as $k => $row) {
            $colarr[$col]['_' . $k] = strtolower($row[$col]);
        }
    }
    $eval = 'array_multisort(';
    foreach ($cols as $col => $order) {
        $eval .= '$colarr[\'' . $col . '\'],' . $order . ',';
    }
    $eval = substr($eval, 0, -1) . ');';
    eval($eval);
    $ret = array();
    foreach ($colarr as $col => $arr) {
        foreach ($arr as $k => $v) {
            $k = substr($k, 1);
            if (!isset($ret[$k])) $ret[$k] = $array[$k];
            $ret[$k][$col] = $array[$k][$col];
        }
    }
    return $ret;
}