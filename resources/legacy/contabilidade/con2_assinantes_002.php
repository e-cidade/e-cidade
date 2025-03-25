<?

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("fpdf151/pdf.php");

$sOrderBy = '';
$sLayout  = 'default';

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$head2 = "Relatório de Assinantes";

$pdf->AddPage("L");
$pdf->setfillcolor(235);

if (!empty($_GET)) {
   extract($_GET);
}

if($sQuebra == '1') {
    $sOrderBy = ' ORDER BY db243_orgao, db243_unidade;';
    $sLayout  = 'unidade';
} 

if($sQuebra == '2') {
    $sOrderBy = ' ORDER BY u.nome, db243_unidade;';
    $sLayout  = 'usuario';
} 

if($sQuebra == '3') {
    $sOrderBy = ' ORDER BY db243_cargo, db243_orgao, db243_unidade;';
    $sLayout  = 'cargo';
} 

if($sQuebra == '4') {
    $sOrderBy = ' ORDER BY db243_documento, db243_orgao, db243_unidade;';
    $sLayout  = 'documento';
}

if($sQuebra == '0') {
    $pdf->setfont('arial','b',10);
    $pdf->ln();

    $pdf->cell(80,4,"Unidade",1,0,"C",1);
    $pdf->cell(90,4,"Usuário",1,0,"C",1);
    $pdf->cell(40,4,"Cargo",1,0,"C",1);
    $pdf->cell(30,4,"Documento",1,0,"C",1);
    $pdf->cell(20,4,"Data Início",1,0,"C",1);
    $pdf->cell(18.7,4,"Data Fim",1,0,"C",1);
}

$sAnoUsu = db_getsession("DB_anousu");
$iInstit = db_getsession("DB_instit");

$sUsersByAssinaturas = implode(',', getUsersByAssinaturas());

$sql = "SELECT
            CONCAT( LPAD(db243_orgao, 2, '0'), LPAD(db243_unidade, 3, '0'), ' - ', oc.o41_descr) AS unidade,
            CONCAT(u.nome, ' Usuário: ', u.login) AS usuario,
            CASE 
                db243_cargo 
                WHEN 0 THEN 'Outros'
                WHEN 1 THEN 'Ordenador Despesa'
                WHEN 2 THEN 'Ordenador Liquidação'
                WHEN 3 THEN 'Ordenador Pagamento'
                WHEN 4 THEN 'Contador'
                WHEN 5 THEN 'Tesoureiro'
                WHEN 6 THEN 'Gestor'
                WHEN 7 THEN 'Controle Interno'
                ELSE 'Desconhecido'
            END AS cargo,
            CASE 
                db243_documento 
                WHEN 0 THEN 'Empenho'
                WHEN 1 THEN 'Liquidacao'
                WHEN 2 THEN 'Pagamento'
                WHEN 3 THEN 'Movimento Extra / Slip'
                ELSE 'Desconhecido'
            END AS documento,
            db243_data_inicio AS data_inicio, 
            db243_data_final AS data_final
        FROM 
            assinatura_digital_assinante 
        INNER JOIN
            db_usuarios AS u ON u.id_usuario = db243_usuario
        INNER JOIN 
            orcunidade AS oc ON (oc.o41_anousu, oc.o41_instit, oc.o41_unidade, oc.o41_orgao) = (db243_anousu, db243_instit, db243_unidade, db243_orgao) 
        WHERE 
            db243_anousu = $sAnoUsu " . $sOrderBy;

$aResult = db_query($sql);

$sLastTitleUnidade  = '';
$sLastTitleUsuario  = '';
$sLastTitleCargo    = '';
$sLastTitlDocumento = '';

foreach (pg_fetch_all($aResult) as $key => $row) {

    $row['data_inicio'] = formatDate($row['data_inicio']);
    $row['data_final']  = formatDate($row['data_final']);

    if($sLayout == 'unidade') {

        if($row['unidade'] != $sLastTitleUnidade) {
            $pdf->ln();
            $pdf->setfont('arial','b',10);
            $pdf->cell(278.7,4, $row['unidade'] ,1,0,"C",1);

            $pdf->ln();

            $pdf->cell(100,4,'Usuário',1,0,"C",1);
            $pdf->cell(90,4,'Cargo',1,0,"C",1);
            $pdf->cell(50,4,'Documento',1,0,"C",1);
            $pdf->cell(20,4,'Data Início',1,0,"C",1);
            $pdf->cell(18.7,4,'Data Final',1,0,"C",1);

            $pdf->ln();
            $sLastTitleUnidade = $row['unidade'];
        }
        
        $pdf->setfont('arial','',8);
        $pdf->cell(100,4,$row['usuario'],1,0,"L",0);
        $pdf->cell(90,4,$row['cargo'],1,0,"C",0);
        $pdf->cell(50,4,$row['documento'],1,0,"C",0);
        $pdf->cell(20,4,$row['data_inicio'],1,0,"C",0);
        $pdf->cell(18.7,4,$row['data_final'],1,0,"C",0);
        $pdf->ln();
    }

    if($sLayout == 'usuario') {

        if($row['usuario'] != $sLastTitleUsuario) {
            $pdf->ln();
            $pdf->setfont('arial','b',10);
            $pdf->cell(278.7,4, $row['usuario'] ,1,0,"C",1);
   
            $pdf->ln();

            $pdf->cell(140,4,'Unidade',1,0,"C",1);
            $pdf->cell(50,4,'Cargo',1,0,"C",1);
            $pdf->cell(50,4,'Documento',1,0,"C",1);
            $pdf->cell(20,4,'Data Início',1,0,"C",1);
            $pdf->cell(18.7,4,'Data Final',1,0,"C",1);

            $pdf->ln();
            $sLastTitleUsuario = $row['usuario'];
        }

        $pdf->setfont('arial','',8);
        $pdf->cell(140,4,$row['unidade'],1,0,"L",0);
        $pdf->cell(50,4,$row['cargo'],1,0,"C",0);
        $pdf->cell(50,4,$row['documento'],1,0,"C",0);
        $pdf->cell(20,4,$row['data_inicio'],1,0,"C",0);
        $pdf->cell(18.7,4,$row['data_final'],1,0,"C",0);
        $pdf->ln();
    }

    if($sLayout == 'cargo') {
        
        if($row['cargo'] != $sLastTitleCargo) {
            $pdf->ln();
            $pdf->setfont('arial','b',10);
            $pdf->cell(278.7,4, $row['cargo'] ,1,0,"C",1);
  
            $pdf->ln();

            $pdf->cell(100,4,'Unidade',1,0,"C",1);
            $pdf->cell(90,4,'Usuário',1,0,"C",1);
            $pdf->cell(50,4,'Documento',1,0,"C",1);
            $pdf->cell(20,4,'Data Início',1,0,"C",1);
            $pdf->cell(18.7,4,'Data Final',1,0,"C",1);

            $pdf->ln();
            $sLastTitleCargo = $row['cargo'];
        }

        $pdf->setfont('arial','',8);
        $pdf->cell(100,4,$row['unidade'],1,0,"L",0);
        $pdf->cell(90,4,$row['usuario'] ,1,0,"C",0);
        $pdf->cell(50,4,$row['documento'],1,0,"C",0);
        $pdf->cell(20,4,$row['data_inicio'],1,0,"C",0);
        $pdf->cell(18.7,4,$row['data_final'],1,0,"C",0);
        $pdf->ln();
    }

    if($sLayout == 'documento') {
        
        if($row['documento'] != $sLastTitleDocumento) {
            $pdf->ln();
            $pdf->setfont('arial','b',10);
            $pdf->cell(278.7,4, $row['documento'] ,1,0,"C",1);

            $pdf->ln();

            $pdf->cell(100,4,'Unidade',1,0,"C",1);
            $pdf->cell(90,4,'Usuário' ,1,0,"C",1);
            $pdf->cell(50,4,'Cargo',1,0,"C",1);
            $pdf->cell(20,4,'Data Início',1,0,"C",1);
            $pdf->cell(18.7,4,'Data Final',1,0,"C",1);

            $pdf->ln();
            $sLastTitleDocumento = $row['documento'];
        }

        $pdf->setfont('arial','',8);
        $pdf->cell(100,4,$row['unidade'],1,0,"L",0);
        $pdf->cell(90,4,$row['usuario'] ,1,0,"C",0);
        $pdf->cell(50,4,$row['cargo'],1,0,"C",0);
        $pdf->cell(20,4,$row['data_inicio'],1,0,"C",0);
        $pdf->cell(18.7,4,$row['data_final'],1,0,"C",0);
        $pdf->ln();
    }

    if($sLayout == 'default') {

        $pdf->ln();
        $pdf->setfont('arial','',8);
        $pdf->cell(80,4,$row['unidade'],1,0,"L",0);
        $pdf->cell(90,4,$row['usuario'],1,0,"C",0);
        $pdf->cell(40,4,$row['cargo'],1,0,"C",0);
        $pdf->cell(30,4,$row['documento'],1,0,"C",0);
        $pdf->cell(20,4,$row['data_inicio'],1,0,"C",0);
        $pdf->cell(18.7,4,$row['data_final'],1,0,"C",0);
    }

}

function formatDate($date) {

    if (!$date instanceof DateTime) {
        $date = new DateTime($date);
    }

    return $date->format('d/m/Y');
}

function getUsersByAssinaturas() {

    $sql = "SELECT DISTINCT(db243_usuario) FROM assinatura_digital_assinante;";

    $result = db_query($sql);

    $aResult = pg_fetch_all($result);

    if(!empty($aResult)) {
        return array_column($aResult, 'db243_usuario');
    }

    return [];
}

$pdf->Output();


