<?
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


include("libs/db_liborcamento.php");


// pesquisa a conta mae da receita

$tipo_mesini = 1;
$tipo_mesfim = 1;

include("fpdf151/pdf.php");
include("libs/db_sql.php");

db_postmemory($HTTP_POST_VARS);

$instit     = str_replace('-',', ',$db_selinstit);

$resultinst = db_query("select codigo,nomeinstabrev from db_config where codigo in ($instit)");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  $descr_inst .= $xvirg.$nomeinstabrev ;
  $xvirg = ', ';
}
$head2 = "TOTAL DO ORCAMENTO - RECEITA";
$head3 = "POR RECURSO";
$head4 = "EXERCICIO: ".db_getsession("DB_anousu");
$head5 = "INSTITUIÇÕES : ".$descr_inst;

$filtroReceita = ' o70_instit in (' . str_replace('-',', ',$instit) . ')';
$sqlReceita = db_receitasaldo(11,1,2,true,$filtroReceita,db_getsession("DB_anousu"),null,null,true);

$filtroDespesa = ' 1=1 and w.o58_instit in (' . str_replace('-',', ',$instit) . ')';
$sqlDespesa = db_dotacaosaldo(8,2,2,true,$filtroDespesa,db_getsession("DB_anousu"),null,null,8,0,true);

$sql = "
WITH sub_receita AS (
    SELECT 
        0::int AS tipo,
        o70_codigo::integer,
        o70_codrec,
        o15_descr,
        SUM(saldo_inicial) AS valor
    FROM 
        ( $sqlReceita ) AS sub
    WHERE o70_codigo > 0
    GROUP BY o70_codigo, o15_descr, o70_codrec
),

sub_despesa AS (
    SELECT 
        1::int AS tipo,
        o58_codigo::integer AS o70_codigo,
        NULL::integer AS o70_codrec,
        o15_descr,
        SUM(dot_ini) AS valor
    FROM 
        ( $sqlDespesa ) AS sub2
    WHERE o58_codigo > 0
    GROUP BY o58_codigo, o15_descr, o70_codrec
)

SELECT 
    o70_codigo,
    (SELECT o15_descr  FROM orctiporec  WHERE o70_codigo = o15_codigo LIMIT 1 ) AS o15_descr,
    SUM(receita) AS receita,
    SUM(despesa) AS despesa,
    SUM(difer) AS difer
FROM (
   SELECT 
    *,
    receita - despesa AS difer
    FROM (
        SELECT 
            RIGHT(o70_codigo::varchar, 8)::integer AS o70_codigo,
            o15_descr,
            SUM(CASE WHEN tipo = 0 THEN valor ELSE 0 END) AS receita,
            SUM(CASE WHEN tipo = 1 THEN valor ELSE 0 END) AS despesa
        FROM (

            SELECT * FROM sub_receita  

                UNION  
                
            SELECT * FROM sub_despesa
        
        ) AS x
        GROUP BY o70_codigo, o15_descr
    ) AS x
    ORDER BY o70_codigo
) y
GROUP BY 1, 2
ORDER BY 1";

$result = db_query($sql);

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$tota2 = 0;
$tota3 = 0;

$pagina = 1;
for($i=0;$i<pg_numrows($result);$i++){

  db_fieldsmemory($result,$i);

  if($pdf->gety()>$pdf->h-30 || $pagina ==1){
    $pagina = 0;
    $pdf->addpage();
    $pdf->setfont('arial','b',7);

    $pdf->cell(20,$alt,"Recurso",0,0,"L",0);
    $pdf->cell(90,$alt,"Descrição",0,0,"L",0);
    $pdf->cell(25,$alt,"Receita",0,0,"R",0);
    $pdf->cell(25,$alt,"Despesa",0,0,"R",0);
    $pdf->cell(25,$alt,"Diferença",0,1,"R",0);
    $pdf->cell(0,$alt,'',"T",1,"C",0);
    $pdf->setfont('arial','',7);
  }

  $pdf->cell(20,$alt,db_formatar($o70_codigo,"recurso"),0,0,"L",0);
  $pdf->cell(90,$alt,$o15_descr,0,0,"L",0);
  $pdf->cell(25,$alt,db_formatar($receita,'f'),0,0,"R",0);
  $pdf->cell(25,$alt,db_formatar($despesa,'f'),0,0,"R",0);
  $pdf->cell(25,$alt,db_formatar($difer,'f'),0,1,"R",0);
  $total += $receita;
  $tota2 += $despesa;
  $tota3 += $difer;
}
$pdf->setfont('arial','b',7);
$pdf->ln(3);
$pdf->cell(110,$alt,'T O T A L',0,0,"L",0);
$pdf->cell(25,$alt,db_formatar($total,'f'),0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($tota2,'f'),0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($tota3,'f'),0,1,"R",0);

$pdf->Output();

db_query("commit");
