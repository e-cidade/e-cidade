<?php

    include("fpdf151/pdf.php");
    include("classes/db_convconvenios_classe.php");
    require_once("libs/db_utils.php");
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
        db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.');
    }

    $head2 = "Convênios";

    $pdf = new PDF('Landscape', 'mm', 'A4');
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->Ln(3);
    $pdf->Ln(3);
    $linhas = 1;
    $y = 0;
    $pagina = 1;

    $limiteY = 145; 
    
    foreach ($rowsConv as $key => &$value) {

        $value['info_concedentes'] = Convenio::getConcedentesByConvenio($value['sequencial'], $iEsferaConcedenteId);
        $value['info_aditivos']    = Convenio::getAditivosByConvenio($value['sequencial']);

        $pdf->setfillcolor(235);
        $pdf->SetFont("Arial", "B", 9);
    
        $pdf->Cell(28, 5, "Número", 1, 0, "C", 1);
        $pdf->Cell(60, 5, "Objeto", 'TLB', 0, "C", 1);
        $pdf->Cell(65, 5, "Recurso", 'TLB', 0, "C", 1);
        $pdf->Cell(20, 5, "Assinatura", 'TLB', 0, "C", 1);
        $pdf->Cell(24, 5, "Início vigência", 'TLB', 0, "C", 1);
        $pdf->Cell(24, 5, "Final vigência", 'TLB', 0, "C", 1);
        $pdf->Cell(26, 5, "Valor convênio", 'TLB', 0, "C", 1);
        $pdf->Cell(32, 5, "Valor contrapartida", 'TLBR', 0, "C", 1);

        Convenio::verifyAdicionaPagina($pdf, $limiteY);

        $pdf->Ln();

        Convenio::verifyAdicionaPagina($pdf, $limiteY);
    
        $pdf->setfillcolor(255);
        $pdf->SetFont("Arial", "", 8);
    
        if(strlen($value['recurso']) == 3) {
            $value['recurso'] = null;
        } else {
           $value['recurso'] = strtoupper(trim($value['recurso']));
        } 

        $value['objeto_do_convenio'] = strtoupper(trim($value['objeto_do_convenio']));

        unset($numLinesNumConv,$numLinesObjConv,$numLinesRecurso,$qtdLinesDados);
        $numLinesNumConv = Convenio::getNumLinesCell($pdf, 28, $value['numero_do_convenio']);
        $numLinesObjConv = Convenio::getNumLinesCell($pdf, 62, $value['objeto_do_convenio']);
        $numLinesRecurso = Convenio::getNumLinesCell($pdf, 65, $value['recurso']);

        $qtdLinesDados = max($numLinesNumConv, $numLinesObjConv, $numLinesRecurso);

        if($numLinesNumConv == $qtdLinesDados) {
            $pdf->MultiAlignCell(28, 5, $value['numero_do_convenio'], 'TLBR', 0, 'C'); //TLBR
        } else {
            if($numLinesNumConv > 1) {
                $pdf->MultiAlignCell(28, ($qtdLinesDados / $numLinesNumConv) * 5, $value['numero_do_convenio'], 'TLBR', 0, 'C');
            } else {
                $pdf->MultiAlignCell(28, $qtdLinesDados * 5, $value['numero_do_convenio'], 'TLBR', 0, 'C');
            }
        }

        if($numLinesObjConv == $qtdLinesDados) {
            $pdf->MultiAlignCell(60, 5, $value['objeto_do_convenio'], 'TLB', 0, 'C');
        } else {
            if($numLinesObjConv > 1) {
                $pdf->MultiAlignCell(60, ($qtdLinesDados / $numLinesObjConv) * 5, $value['objeto_do_convenio'], 'TLB', 0, 'C');
            } else {
                $pdf->MultiAlignCell(60, $qtdLinesDados * 5, $value['objeto_do_convenio'], 'TLB', 0, 'C');
            } 
        }

        if($numLinesRecurso == $qtdLinesDados) {
            $pdf->MultiAlignCell(65, 5, $value['recurso'], 'TLB', 0, 'C');
        } else {
            if($numLinesRecurso > 1) {
                $pdf->MultiAlignCell(65, ($qtdLinesDados / $numLinesRecurso) * 5, $value['recurso'], 'TLB', 0, 'C');
            } else {
                $pdf->MultiAlignCell(65, $qtdLinesDados * 5, $value['recurso'], 'TLB', 0, 'C');
            }
        }

        $pdf->MultiAlignCell(20, $qtdLinesDados * 5, Convenio::formatDate($value['data_da_assinatura']), 'TLB', 0, 'C');
        $pdf->MultiAlignCell(24, $qtdLinesDados * 5, Convenio::formatDate($value['data_inicial_da_vigencia']), 'TLB', 0, 'C');
        $pdf->MultiAlignCell(24, $qtdLinesDados * 5, Convenio::formatDate($value['data_final_da_vigencia']), 'TLB', 0, 'C');
        $pdf->MultiAlignCell(26, $qtdLinesDados * 5, Convenio::formatToReal($value['valor_do_convenio']), 'TLB', 0, 'C');
        $pdf->MultiAlignCell(32, $qtdLinesDados * 5, Convenio::formatToReal($value['valor_da_contrapartida']), 'TLBR', 0, 'C');

        Convenio::verifyAdicionaPagina($pdf, $limiteY);

        // ------------------------------------concedentes-------------------------------------------
        $pdf->Ln();
        $pdf->setfillcolor(235);
        $pdf->SetFont("Arial", "B", 9);

        $pdf->Cell(197, 5, "Concedente", 'TLB', 0, "C", 1);
        $pdf->Cell(50, 5, "Esfera Concedente", 'TLB', 0, "C", 1);
        $pdf->Cell(32, 5, "Valor", 'TLBR', 0, "C", 1);

        Convenio::verifyAdicionaPagina($pdf, $limiteY);

        $pdf->Ln();
        $pdf->setfillcolor(255);
        $pdf->SetFont("Arial", "", 8);

        $concedentesNotNull = 0;

        if(!empty($value['info_concedentes'])) {

            foreach ($value['info_concedentes'] as $key => $concedente) {

                $concedentesNotNull++;

                $cpfCnpjArray = explode(' - ', $concedente['concedente']);

                if(count($cpfCnpjArray) == 2) {
                    $cpfCnpj = $cpfCnpjArray[0];
                    $descConcedente = $cpfCnpjArray[1];
                }
    
                $concedente['concedente'] = !empty($cpfCnpj) ? Convenio::formatCnpjCpf($cpfCnpj) .' - '.  $descConcedente : $concedente['concedente'];
                $concedente['concedente'] = strtoupper(trim($concedente['concedente']));

                unset($numLinesConcedente);
                $numLinesConcedente       = Convenio::getNumLinesCell($pdf, 197, $concedente['concedente']);

                $pdf->MultiAlignCell(197, 5, $concedente['concedente'], 'TLB', 0, 'C' );
                $pdf->MultiAlignCell(50,  $numLinesConcedente * 5, Convenio::getEsferaDescricao($concedente['esfera_concedente']), 'TLB', 0, 'C' );
                $pdf->MultiAlignCell(32,  $numLinesConcedente * 5, Convenio::formatToReal($concedente['valor']), 'TLBR', 0, 'C' );
                $pdf->Ln();

                Convenio::verifyAdicionaPagina($pdf, $limiteY);
            }
        }

        if($concedentesNotNull == 0) {

            $pdf->MultiAlignCell(197, 5, '-', 'TLB', 0, 'C' );
            $pdf->MultiAlignCell(50,  5, '-', 'TLB', 0, 'C' );
            $pdf->MultiAlignCell(32,  5, '-', 'TLBR', 0, 'C' );
            $pdf->Ln();
        }

        Convenio::verifyAdicionaPagina($pdf, $limiteY);

        // ------------------------------------aditivos-------------------------------------------

        $pdf->setfillcolor(235);
        $pdf->SetFont("Arial", "B", 9);

        $pdf->Cell(28, 5, "Número aditivo", 'TLB', 0, "C", 1);
        $pdf->Cell(28, 5, "Tipo do termo", 'TLB', 0, "C", 1);
        $pdf->Cell(97, 5, "Descricao da alteração", 'TLB', 0, "C", 1);
        $pdf->Cell(20, 5, "Assinatura", 'TLB', 0, "C", 1);
        $pdf->Cell(24, 5, "Final vigência", 'TLB', 0, "C", 1);
        $pdf->Cell(39, 5, "Valor atualizado conv.", 'TLB', 0, "C", 1);
        $pdf->Cell(43, 5, "Valor atualizado contrap.", 'TLBR', 0, "C", 1);

        Convenio::verifyAdicionaPagina($pdf, $limiteY);

        $pdf->Ln();
        $pdf->setfillcolor(255);
        $pdf->SetFont("Arial", "", 8);

        $aditivosNotNull = 0;
        $arrayQtdLinesAditivos = [];

        if(!empty($value['info_aditivos'])) {

            foreach ($value['info_aditivos'] as $key => $aditivo) {

                if(!empty($aditivo['numero_aditivo'])) {
                    
                    $aditivosNotNull++;

                    $aditivo['descricao_da_alteracao'] = strtoupper($aditivo['descricao_da_alteracao']);
                    $aditivo['tipo_termo_aditivo']     = Convenio::getTermoDescricaoById($aditivo['tipo_termo_aditivo']);

                    unset($numLinesTipoTermo, $numLinesDescAlteracao, $maxLinesAditivos);

                    $numLinesTipoTermo     = Convenio::getNumLinesCell($pdf, 28, $aditivo['tipo_termo_aditivo']);
                    $numLinesDescAlteracao = Convenio::getNumLinesCell($pdf, 97, $aditivo['descricao_da_alteracao']);

                    $maxLinesAditivos = max($numLinesDescAlteracao, $numLinesTipoTermo);

                    $pdf->MultiAlignCell(28, $maxLinesAditivos * 5, $aditivo['numero_aditivo'], 'TLB', 0, 'C' );

                    if($numLinesTipoTermo == $maxLinesAditivos) {
                        $pdf->MultiAlignCell(28, 5, $aditivo['tipo_termo_aditivo'], 'TLB', 0, 'C');
                    } else {
                        if($numLinesTipoTermo > 1) {
                            $pdf->MultiAlignCell(28, ($maxLinesAditivos / $numLinesTipoTermo) * 5, $aditivo['tipo_termo_aditivo'], 'TLB', 0, 'C');
                        } else {
                            $pdf->MultiAlignCell(28, $maxLinesAditivos * 5, $aditivo['tipo_termo_aditivo'], 'TLB', 0, 'C');
                        }
                    }

                    if($numLinesDescAlteracao == $maxLinesAditivos) {
                        $pdf->MultiAlignCell(97, 5, $aditivo['descricao_da_alteracao'], 'TLB', 0, 'C');
                    } else {
                        if($numLinesDescAlteracao > 1) {
                            $pdf->MultiAlignCell(97, ($maxLinesAditivos / $numLinesDescAlteracao) * 5, $aditivo['descricao_da_alteracao'], 'TLB', 0, 'C');
                        } else {
                            $pdf->MultiAlignCell(97, $maxLinesAditivos * 5, $aditivo['descricao_da_alteracao'], 'TLB', 0, 'C');
                        }
                    }

                    $pdf->MultiAlignCell(20, $maxLinesAditivos * 5, Convenio::formatDate($aditivo['data_da_assinatura_aditivo']), 'TLB', 0, 'C' );
                    $pdf->MultiAlignCell(24, $maxLinesAditivos * 5, Convenio::formatDate($aditivo['data_final_vigencia_aditivo']), 'TLB', 0, 'C' );
                    $pdf->MultiAlignCell(39, $maxLinesAditivos * 5, Convenio::formatToReal($aditivo['valor_atualizado_do_convenio']), 'TLB', 0, 'C' );
                    $pdf->MultiAlignCell(43, $maxLinesAditivos * 5, Convenio::formatToReal($aditivo['valor_atualizado_contrapartida']), 'TLBR', 0, 'C' );
                    $pdf->Ln();

                    Convenio::verifyAdicionaPagina($pdf, $limiteY);
                }
            }

            if($aditivosNotNull == 0) {

                $pdf->MultiAlignCell(28, 5, '-', 'TLB', 0, 'C' );
                $pdf->MultiAlignCell(28, 5, '-', 'TLB', 0, 'C' );
                $pdf->MultiAlignCell(97, 5, '-', 'TLB', 0, 'C' );
                $pdf->MultiAlignCell(20, 5, '-', 'TLB', 0, 'C' );
                $pdf->MultiAlignCell(24, 5, '-', 'TLB', 0, 'C' );
                $pdf->MultiAlignCell(39, 5, '-', 'TLB', 0, 'C' );
                $pdf->MultiAlignCell(43, 5, '-', 'TLBR', 0, 'C' );
                $pdf->Ln();
            }

            Convenio::verifyAdicionaPagina($pdf, $limiteY);
        }

        $pdf->Ln();
        $pdf->Ln();
        Convenio::verifyAdicionaPagina($pdf, $limiteY);
    }

    $pdf->Output();
?>