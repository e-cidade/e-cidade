<?php
    require_once "libs/db_stdlib.php";
    require_once "libs/db_conecta.php";
    include("libs/db_utils.php");
    require_once("model/contabilidade/Convenio.model.php");

    $oGet = db_utils::postMemory($_GET);

    $date1 = DateTime::createFromFormat('d/m/Y', $oGet->sDataInicial);
    $date2 = DateTime::createFromFormat('d/m/Y', $oGet->sDataFinal);

    if(!empty($oGet->sDataInicial)) {
        $dataInicial = $date1->format('Y-m-d');
    }

    if(!empty($oGet->sDataFinal)) {
        $dataFinal   = $date2->format('Y-m-d');
    }

    $iEsferaConcedenteId = $oGet->esferaconcedente;

    $sWhere = " ORDER BY c.c206_dataassinatura ASC;";

    if(!empty($esferaconcedente) && !empty($dataFinal)) {
        $sWhere = " INNER JOIN 
                        convdetalhaconcedentes cdt_c on cdt_c.c207_codconvenio = c.c206_sequencial 
                    WHERE 
                        c.c206_dataassinatura BETWEEN '{$dataInicial}' AND '{$dataFinal}' AND cdt_c.c207_esferaconcedente = {$iEsferaConcedenteId}
                    ORDER BY c.c206_dataassinatura ASC;";
    } else if(!empty($esferaconcedente)) {
        $sWhere = " INNER JOIN 
                        convdetalhaconcedentes cdt_c on cdt_c.c207_codconvenio = c.c206_sequencial 
                    WHERE 
                        cdt_c.c207_esferaconcedente = {$iEsferaConcedenteId}
                    ORDER BY c.c206_dataassinatura ASC;";
    } else if(!empty($dataFinal)) {
        $sWhere = " WHERE 
                        c.c206_dataassinatura BETWEEN '{$dataInicial}' AND '{$dataFinal}'
                    ORDER BY c.c206_dataassinatura ASC;";
    }

    $sql = "SELECT
                distinct(c.c206_sequencial) as sequencial,
                c.c206_nroconvenio as numero_do_convenio,
                c.c206_objetoconvenio as objeto_do_convenio,
                concat(otr.o15_codigo , ' - ' , otr.o15_descr) as recurso,
                c.c206_dataassinatura as data_da_assinatura, 
                c.c206_datainiciovigencia as data_inicial_da_vigencia, 
                c.c206_datafinalvigencia as data_final_da_vigencia, 
                c.c206_vlconvenio as valor_do_convenio, 
                c.c206_vlcontrapartida as valor_da_contrapartida 
            FROM 
                convconvenios c
            INNER JOIN  
                orctiporec otr on otr.o15_codigo = c.c206_tipocadastro {$sWhere};";

    $oResult  = db_query($sql);
    $rowsConv = pg_fetch_all($oResult);

    if (empty($rowsConv)) {
        $msg = 'Não existem registros cadastrados.';
        db_redireciona('db_erros.php?fechar=true&db_erro='.$msg);
    }

    $aLinhas = array();

    foreach ($rowsConv as $key => $value) {

        $aLinhaTitle = array();
        array_push(
            $aLinhaTitle,
            'numero_do_convenio',
            'objeto_do_convenio',
            'recurso',
            'data_da_assinatura',
            'data_inicial_da_vigencia',
            'data_final_da_vigencia',
            'valor_do_convenio',
            'valor_da_contrapartida'
        );

        $aLinhas[] = $aLinhaTitle;

        $aLinhaConv = array();
        array_push(
            $aLinhaConv, 
            $value['numero_do_convenio'],
            strtoupper(trim($value['objeto_do_convenio'])),
            strtoupper(trim($value['recurso'])),
            Convenio::formatDate($value['data_da_assinatura']),
            Convenio::formatDate($value['data_inicial_da_vigencia']),
            Convenio::formatDate($value['data_final_da_vigencia']),
            Convenio::formatToReal($value['valor_do_convenio']),
            Convenio::formatToReal($value['valor_da_contrapartida'])
        );

        $aLinhas[] = $aLinhaConv; 

        $dataConcedentes =  Convenio::getConcedentesByConvenio($value['sequencial'], $iEsferaConcedenteId);
        $dataAditivos    =  Convenio::getAditivosByConvenio($value['sequencial']);

        //------------------------concedentes---------------------------

        $aLinhaTitleConced = array();
        array_push(
            $aLinhaTitleConced,
            'concedente',
            'esfera_concedente',
            'valor'
        );

        $aLinhas[] = $aLinhaTitleConced;

        $aLinhaConced = array();
        foreach ($dataConcedentes as $key => $concedente) {

            $cpfCnpjArray = explode(' - ', $concedente['concedente']);

            if(count($cpfCnpjArray) == 2) {
                $cpfCnpj = $cpfCnpjArray[0];
                $descConcedente = $cpfCnpjArray[1];
            }

            $concedente['concedente'] = !empty($cpfCnpj) ? Convenio::formatCnpjCpf($cpfCnpj) .' - '.  $descConcedente : $concedente['concedente'];

            array_push(
                $aLinhaConced,
                strtoupper(trim($concedente['concedente'])),
                Convenio::getEsferaDescricao($concedente['esfera_concedente']),
                Convenio::formatToReal($concedente['valor'])
            );
        }

        count($aLinhaConced) > 0 ? $aLinhaConced : array_push($aLinhaConced, '-', '-', '-');

        $aLinhas[] = $aLinhaConced;  

        //-------------------------aditivos-----------------------------

        $aLinhaTitleAdit = array();
        array_push(
            $aLinhaTitleAdit,
            'numero_aditivo',
            'tipo_termo_aditivo',
            'descricao_da_alteracao',
            'data_da_assinatura_aditivo',
            'data_final_vigencia_aditivo',
            'valor_atualizado_do_convenio',
            'valor_atualizado_contrapartida'
        );

        $aLinhas[] = $aLinhaTitleAdit; 
        
        $aLinhaAdit = array();
        foreach ($dataAditivos as $key => $aditivo) {
            if(!empty($aditivo['numero_aditivo'])) {

                array_push(
                    $aLinhaAdit,
                    $aditivo['numero_aditivo'],
                    Convenio::getTermoDescricaoById($aditivo['tipo_termo_aditivo']),
                    strtoupper(trim($value['objeto_do_convenio'])),
                    Convenio::formatDate($aditivo['data_da_assinatura_aditivo']),
                    Convenio::formatDate($aditivo['data_final_vigencia_aditivo']),
                    Convenio::formatToReal($aditivo['valor_atualizado_do_convenio']),
                    Convenio::formatToReal($aditivo['valor_atualizado_contrapartida'])
                );
            }
        }

        count($aLinhaAdit) > 0 ? $aLinhaAdit : array_push($aLinhaAdit, '-', '-', '-', '-', '-', '-', '-');

        $aLinhas[] = $aLinhaAdit;
        $aLinhas[] = [];

        unset($aLinhaTitle,$aLinhaConv,$aLinhaTitleConced,$aLinhaConced,$aLinhaTitleAdit,$aLinhaAdit);
    }

    $filename = 'tmp/convenios.csv';

    $csvContent = ob_get_clean();
    file_put_contents($filename, $csvContent);

    $csv = fopen($filename, "w");

    foreach ($aLinhas as $aLinha) {
        fputcsv($csv, $aLinha, ';');
    }

    fclose($csv);
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        function limparTelaEMostrarDownload() {

            document.body.innerHTML = '';
            document.body.style.backgroundColor = '#cccccc';

            var paragrafo = document.createElement('p');

            paragrafo.innerHTML = "<center><a id='downloadLink' href='tmp/convenios.csv'>Clique no link para baixar o arquivo <b>convenios.csv</b></a></center>";
            document.body.appendChild(paragrafo);

            // Exibe o link de download
            var downloadLink = document.getElementById('downloadLink');
            downloadLink.style.display = 'inline-block';
        }

        limparTelaEMostrarDownload();
    });
</script>