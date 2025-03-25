<?

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("fpdf151/pdf.php");

$aLinhas = array();

$sOrderBy = '';
$sLayout  = 'default';

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

    $aLinhaTitleDefault = array();
    array_push(
        $aLinhaTitleDefault, 
        'Unidade', 
        'Usuário', 
        'Cargo', 
        'Documento', 
        'Data Início', 
        'Data Fim'
    );

    $aLinhas[] = $aLinhaTitleDefault;
}

$sAnoUsu = db_getsession("DB_anousu");
$iInstit = db_getsession("DB_instit");

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

$sLastTitleUnidade   = '';
$sLastTitleUsuario   = '';
$sLastTitleCargo     = '';
$sLastTitleDocumento = '';

foreach (pg_fetch_all($aResult) as $key => $row) {

    $row['data_inicio'] = formatDate($row['data_inicio']);
    $row['data_final']  = formatDate($row['data_final']);

    if($sLayout == 'unidade') {

        if($row['unidade'] != $sLastTitleUnidade) {

            $aLinhaTitleUnidade = array();
            $aLinhaTitleUnidade2 = array();

            array_push(
                $aLinhaTitleUnidade, 
                'Unidade'
            );

            array_push(
                $aLinhaTitleUnidade2, 
                'Usuário', 
                'Cargo', 
                'Documento', 
                'Data Início', 
                'Data Final'
            );

            $aLinhas[] = $aLinhaTitleUnidade;
            $aLinhas[] = $aLinhaTitleUnidade2;

            $sLastTitleUnidade = $row['unidade'];
        }
        

        $aLinhaRegistersUnidade = array();
        array_push(
            $aLinhaRegistersUnidade, 
            $row['usuario'], 
            $row['cargo'], 
            $row['documento'], 
            $row['data_inicio'], 
            $row['data_final']
        );

        $aLinhas[] = $aLinhaRegistersUnidade;
    }


    if($sLayout == 'usuario') {

        if($row['usuario'] != $sLastTitleUsuario) {

            $aLinhaTitleUsuario = array();
            $aLinhaTitleUsuario2 = array();

            array_push(
                $aLinhaTitleUsuario, 
                $row['usuario']
            );

            array_push(
                $aLinhaTitleUsuario2, 
                'Unidade', 
                'Cargo', 
                'Documento', 
                'Data Início', 
                'Data Final'
            );
   
            $aLinhas[] = $aLinhaTitleUsuario;
            $aLinhas[] = $aLinhaTitleUsuario2;

            $sLastTitleUsuario = $row['usuario'];
        }

        $aLinhaRegistersUsuario = array();
        array_push(
            $aLinhaRegistersUsuario, 
            $row['unidade'], 
            $row['cargo'], 
            $row['documento'], 
            $row['data_inicio'], 
            $row['data_final']
        );

        $aLinhas[] = $aLinhaRegistersUsuario;
    }


    if($sLayout == 'cargo') {
        
        if($row['cargo'] != $sLastTitleCargo) {

            $aLinhaTitleCargo = array();
            $aLinhaTitleCargo2 = array();

            array_push(
                $aLinhaTitleCargo, 
                $row['cargo']
            );

            array_push(
                $aLinhaTitleCargo2, 
                'Unidade',
                'Usuário',
                'Documento',
                'Data Início',
                'Data Final'
            );

            $aLinhas[] = $aLinhaTitleCargo;
            $aLinhas[] = $aLinhaTitleCargo2;

            $sLastTitleCargo = $row['cargo'];
        }

        $aLinhaRegistersCargo = array();
        array_push(
            $aLinhaRegistersCargo, 
            $row['unidade'], 
            $row['usuario'], 
            $row['documento'], 
            $row['data_inicio'], 
            $row['data_final']
        );

        $aLinhas[] = $aLinhaRegistersCargo;
    }


    if($sLayout == 'documento') {
        
        if($row['documento'] != $sLastTitleDocumento) {

            $aLinhaTitleDocumento = array();
            $aLinhaTitleDocumento2 = array();

            array_push(
                $aLinhaTitleDocumento, 
                $row['documento']
            );

            array_push(
                $aLinhaTitleDocumento2, 
                'Unidade',
                'Usuário',
                'Cargo',
                'Data Início',
                'Data Final',
            );

            $aLinhas[] = $aLinhaTitleDocumento;
            $aLinhas[] = $aLinhaTitleDocumento2;

            $sLastTitleDocumento = $row['documento'];
        }

        $aLinhaRegistersDocumento = array();
        array_push(
            $aLinhaRegistersDocumento, 
            $row['unidade'],
            $row['usuario'],
            $row['cargo'],
            $row['data_inicio'],
            $row['data_final']
        );

        $aLinhas[] = $aLinhaRegistersDocumento;
    }

    if($sLayout == 'default') {

        $aLinhaRegistersDefault = array();
        array_push(
            $aLinhaRegistersDefault, 
            $row['unidade'],
            $row['usuario'],
            $row['cargo'],
            $row['documento'],
            $row['data_inicio'],
            $row['data_final']
        );

        $aLinhas[] = $aLinhaRegistersDefault;
    }

    unset(
        $aLinhaTitleUnidade,   $aLinhaTitleUnidade2,   $aLinhaRegistersUnidade,
        $aLinhaTitleUsuario,   $aLinhaTitleUsuario2,   $aLinhaRegistersUsuario,
        $aLinhaTitleCargo,     $aLinhaTitleCargo2,     $aLinhaRegistersCargo,
        $aLinhaTitleDocumento, $aLinhaTitleDocumento2, $aLinhaRegistersDocumento,
        $aLinhaRegistersDefault
    );
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

function formatDate($date) {

    if (!$date instanceof DateTime) {
        $date = new DateTime($date);
    }

    return $date->format('d/m/Y');
}

if (empty($aLinhas)) {
    $msg = 'No existem registros cadastrados.';
    db_redireciona('db_erros.php?fechar=true&db_erro='.$msg);
}

$filename = 'tmp/assinantes.csv';

$csv = fopen($filename, "w");

foreach ($aLinhas as $aLinha) {
    fputcsv($csv, $aLinha, ';');
}

fclose($csv);

$aLinhas[] = [];

?>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        function limparTelaEMostrarDownload() {

            document.body.innerHTML = '';
            document.body.style.backgroundColor = '#cccccc';

            var paragrafo = document.createElement('p');

            paragrafo.innerHTML = "<center><a id='downloadLink' href='tmp/assinantes.csv'>Clique no link para baixar o arquivo <b>assinantes.csv</b></a></center>";
            document.body.appendChild(paragrafo);

            var downloadLink = document.getElementById('downloadLink');
            downloadLink.style.display = 'inline-block';

            downloadLink.addEventListener('click', function() {
                setTimeout(function() {
                    alert('Download realizado com sucesso!');
                }, 1000);
            });
        }

        limparTelaEMostrarDownload();
    });
</script>
