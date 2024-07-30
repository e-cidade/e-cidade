<?php

class Convenio {

    public static function getConcedentesByConvenio($sSequencial, $iEsferaConcedenteId) {

        $sWhere = " WHERE c.c206_sequencial = '{$sSequencial}';";

        if(!empty($iEsferaConcedenteId)) {
            $sWhere = " WHERE 
                            c.c206_sequencial = '{$sSequencial}' AND
                            cdt_c.c207_esferaconcedente = '{$iEsferaConcedenteId}'
                        ORDER BY 
                            c.c206_dataassinatura ASC;";
        }

        $sqlConv =  "SELECT 
                        distinct(concat(cg.z01_cgccpf, ' - ', cg.z01_nome)) as concedente,
                        cdt_c.c207_esferaconcedente as esfera_concedente,
                        cdt_c.c207_valorconcedido as valor,
                        c.c206_dataassinatura
                    FROM 
                        convconvenios c
                    JOIN 
                        convdetalhaconcedentes cdt_c on cdt_c.c207_codconvenio = c.c206_sequencial	
                    JOIN  
                        orctiporec otr on otr.o15_codigo = c.c206_tipocadastro
                    INNER JOIN
                        cgm cg on cg.z01_cgccpf = cdt_c.c207_nrodocumento
                    {$sWhere}";

        $oResultConv = db_query($sqlConv);
        $rowsConv    = pg_fetch_all($oResultConv);

        if(is_array($rowsConv) && !empty($rowsConv)) {
            $rowsConv = self::removeDuplicates($rowsConv);
            return $rowsConv;
        }
        return false;
    }

    public static function getAditivosByConvenio($sSequencial) {

        $sqlAd =   "SELECT 
                        cdt_t.c208_nroseqtermo as numero_aditivo,
                        cdt_t.c208_tipotermoaditivo as tipo_termo_aditivo,
                        cdt_t.c208_dscalteracao as descricao_da_alteracao, 
                        cdt_t.c208_dataassinaturatermoaditivo as data_da_assinatura_aditivo,
                        cdt_t.c208_datafinalvigencia as data_final_vigencia_aditivo,
                        cdt_t.c208_valoratualizadoconvenio as valor_atualizado_do_convenio,
                        cdt_t.c208_valoratualizadocontrapartida as valor_atualizado_contrapartida,
                        c.c206_dataassinatura
                    FROM 
                        convconvenios c
                    LEFT JOIN
                        convdetalhatermos cdt_t on cdt_t.c208_codconvenio = c.c206_sequencial
                    WHERE 
                        c.c206_sequencial = '{$sSequencial}'
                    ORDER BY 
                        c.c206_dataassinatura ASC;";

        $oResultAd = db_query($sqlAd);
        $rowsAd = pg_fetch_all($oResultAd);

        if(is_array($rowsAd) && !empty($rowsAd)) {
            $rowsAd = self::removeDuplicates($rowsAd);
            return $rowsAd;
        }
        return false;
    }

    public static function removeDuplicates($array) {
        $uniqueArray = [];
        $serializedArray = [];
    
        foreach ($array as $item) {
            $serializedItem = serialize($item);
            if (!in_array($serializedItem, $serializedArray)) {
                $serializedArray[] = $serializedItem;
                $uniqueArray[] = $item;
            }
        }
    
        return $uniqueArray;
    } 
 
    public static function NbLines($pdf, $width, $text) {
        $cw = $pdf->CurrentFont['cw'];
        
        if ($width == 0) {
            $width = $pdf->w - $pdf->rMargin - $pdf->x;
        }
        
        $wmax = ($width - 2 * $pdf->cMargin) * 1000 / $pdf->FontSize;
        $s = str_replace("\r", '', $text);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n") {
            $nb--;
        }
    
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
    
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
            }
            if (isset($cw[$c])) {
                $l += $cw[$c];
            } else {
                $l += 500; // Valor padrÃƒÂ£o para caracteres desconhecidos
            }
            
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) {
                        $i++;
                    }
                } else {
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }
        }
    
        return $nl;
    }

    public static function getNumLinesCell($pdf, $width, $text) {
        $text = utf8_decode($text);
        $numLines = self::NbLines($pdf, $width, $text);
        return $numLines;
    }

    public static function getEsferaDescricao($esferaId) {

        $esferaDescricao = null;

        switch ($esferaId) {
            case '1':
                $esferaDescricao = 'Federal';
                break;
            case '2':
                $esferaDescricao = 'Estadual';
                break;
            case '3':
                $esferaDescricao = 'Municipal';
                break;
            case '4':
                $esferaDescricao = 'Exterior';
                break;
            case '5':
                $esferaDescricao = 'Instituição Privada';
                break;
        }

        return strtoupper($esferaDescricao);
    }

    public static function formatToReal($value) {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }

    public static function formatDate($date) {

        if (!$date instanceof DateTime) {
            $date = new DateTime($date);
        }

        return $date->format('d/m/Y');
    }

    public static function formatCnpjCpf($value) {
        $CPF_LENGTH = 11;
        $cnpj_cpf = preg_replace("/\D/", '', $value);
        
        if (strlen($cnpj_cpf) === $CPF_LENGTH) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
        } 
        
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
    }

    public static function verifyAdicionaPagina($pdf, $limiteY) {
        if ($pdf->getY() > $limiteY) { 
            $pdf->AddPage();
            $pdf->Ln(3);
        }
    }

    public static function getTermoDescricaoById($tipoTermoId) {

        $tipoTermoDescricao = null;

        switch ($tipoTermoId) {
            case '1':
                $tipoTermoDescricao = '01 - Acréscimo';
                break;
            case '2':
                $tipoTermoDescricao = '02 - Supressão';
                break;
            case '3':
                $tipoTermoDescricao = '03 - Alteração da Vigência';
                break;
            case '4':
                $tipoTermoDescricao = '04 - Ampliação do Objeto';
                break;
            case '5':
                $tipoTermoDescricao = '05 - Indicação de Crédito';
                break;
            case '6':
                $tipoTermoDescricao = '06 - Alteração de Responsável do Concedente';
                break;
            case '7':
                $tipoTermoDescricao = '07 - Exclusão de Dados Orçamentários';
                break;
            case '8':
                $tipoTermoDescricao = '08 - Inclusão de Dados Orçamentários';
                break;
            case '9':
                $tipoTermoDescricao = '09 - Alteração de Executor';
                break;
            case '99':
                $tipoTermoDescricao = '99 - Outros';
                break;
        }

        return $tipoTermoDescricao;
    }
}

?>