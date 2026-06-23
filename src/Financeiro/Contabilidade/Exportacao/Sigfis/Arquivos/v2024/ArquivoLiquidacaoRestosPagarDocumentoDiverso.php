<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Arquivos\v2024;

use ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Arquivos\v2024\ArquivoBase;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Query\JoinClause;
use stdClass;

class ArquivoLiquidacaoRestosPagarDocumentoDiverso extends ArquivoBase
{
    protected $sNomeArquivo = 'LiquidacaoRestosPagarDocumentoDiverso';

    
    public function voltaValor($seqempenho){
        $di = $this->dtDataInicial;
        $df = $this->dtDataFinal;
        $sql = pg_query("SELECT c70_codlan, c70_data, c53_descr, case when c127_conlancam is not null then 'Sim' else 'Não' end as dl_Lançamento_Retenção, c70_valor, c82_reduz, c60_descr, c72_complem, e69_numero as dl_Nota_Fiscal, e50_codord, e50_data, nomeinstabrev as dl_Ente from conlancamemp inner join conlancam on c70_codlan = c75_codlan inner join empempenho on c75_numemp = e60_numemp inner join conlancamordem on conlancamordem.c03_codlan = conlancam.c70_codlan left outer join conlancampag on c82_codlan = c70_codlan inner join conlancamdoc on c71_codlan = c70_codlan inner join conhistdoc on c53_coddoc = c71_coddoc left join conlancamcompl on c72_codlan =c70_codlan left join conlancamnota on c66_codlan =c70_codlan left join conlancamord on c80_codlan =c70_codlan left join empnota on c66_codnota = e69_codnota left join conplanoreduz on c61_reduz = conlancampag.c82_reduz and c61_anousu=c70_anousu left join conplano on c60_codcon = conplanoreduz.c61_codcon and c60_anousu=c61_anousu left join pagordem on e50_codord = c80_codord inner join conlancaminstit on c02_codlan = c70_codlan inner join db_config on c02_instit = db_config.codigo left join conlancamretencao on c127_conlancam = c70_codlan where c75_numemp = {$seqempenho} AND c70_data between '{$di}' AND '{$df}' AND c71_coddoc = 33 order by c75_data, c03_ordem, c75_codlan");
        $resultado = pg_fetch_all($sql);
        return ($resultado[0]["c70_valor"]) ? $resultado[0]["c70_valor"] : 0;
    }

    public function tabelaSigfis($tipo){
        switch ($tipo) {
            case '4':
                return 1;            
            case '5':
                return 2;
        }
    }


    public function gerarDados()
    {   
        $anoexercicio = db_getsession("DB_anousu");

        $campos = [
            'e60_numemp',
            'e60_codemp',
            'e60_anousu',
            'o58_orgao',
            'o58_unidade',
            'e69_codnota',
            'e69_anousu',
            'e60_vlrliq',
            'e50_obs',
            'e69_anousu',
            'e69_numero',
            'e69_serienota',
            'e69_dtnota',
            'e69_dtrecebe',
            'e178_sigfistipodocliquidacao',
            'z01_nome',
            'z01_cgccpf',
            'e50_data',
            'e178_sequencial',
        ];
        $empenhos = DB::table('empresto')
            ->select($campos)
            ->join('empempenho', 'e60_numemp', 'e91_numemp')
            ->join('empnota', 'e69_numemp', 'e60_numemp')
            ->join('empnotaele', 'e70_codnota', 'e69_codnota')
            ->join('pagordem', 'e50_numemp', 'e60_numemp')
            ->join('pagordemnota', function (JoinClause $join) {
                $join->on('e71_codnota', 'e69_codnota')
                    ->on('e71_codord', 'e50_codord');
            })
            ->join('empnotasigfistipodocliquidacao', 'e178_empnota', 'e69_codnota')
            ->join('orcdotacao', function (JoinClause $join) {
                $join->on('o58_anousu', 'e60_anousu')
                    ->on('o58_coddot', 'e60_coddot');
            })
            ->join('cgm', 'z01_numcgm', 'e60_numcgm')
            ->where('e60_instit', $this->instit)
            ->whereIn('e178_sigfistipodocliquidacao', [4, 5])
            ->whereBetween('e69_dtinclusao', [$this->dtDataInicial, $this->dtDataFinal])
            ->where('e60_vlrliq', '!=', 0)
            ->orderBy('e60_numemp')
            ->get();            

        if ($empenhos->isEmpty()) {
            throw new \Exception('Não foi encontrado nenhum empenho de Liquidação de Empenho - Documento Diverso.');
        }

        $obj = new stdClass();
        $obj->LiquidacoesRestosPagarDocumentosDiversos = [];
        $xi = 1;        

        $guardaempenho = array();
        foreach ($empenhos as $empenho) {
            
            if($empenho->e60_anousu == $anoexercicio){continue;}

            if(in_array($empenho->e60_codemp, $guardaempenho)){
                continue;
            }
            array_push($guardaempenho, $empenho->e60_codemp);
            $atestadores = DB::table('empnotaatestador')
                ->select(['z01_numcgm', 'z01_nome', 'z01_cgccpf'])
                ->join('cgm', 'z01_numcgm', 'e169_numcgm')
                ->where('e169_empnota', $empenho->e69_codnota)
                ->limit(1)
                ->get()
                ->map(function ($atestador) {
                    $cpf = $atestador->z01_cgccpf;

                    if(strlen($cpf) > 11){
                        $cpf = "81885520700";
                    }
                    return (object)[
                        'Identificador' => $atestador->z01_numcgm,
                        'Nome' => utf8_encode(trim($atestador->z01_nome)),
                        'CPF' => $cpf,
                    ];
                })->toArray();

            if(empty($atestadores)){
                $atestadores = new stdClass();
                $atestadores->Identificador = NULL;
                $atestadores->Nome = NULL;
                $atestadores->CPF = NULL;                                
                $tipo = 1;
                $natureza = strlen($empenho->z01_cgccpf) == 11 ? 2 : 1;

                $valorRPP = round($empenho->e91_vlrliq - $empenho->e91_vlrpag, 2);
                $novovalor = $this->voltaValor($empenho->e60_numemp);
                

                if(!$novovalor || $novovalor == null){
                    $novovalor = "0";
                }
                if($novovalor == 0){continue;}
                

                $data = (object)[                    
                    'Identificador' => $xi,
                    'CodigoUnidadeGestora' => $this->sCodigoTribunal,
                    'NumeroEmpenho' => $empenho->e60_codemp,
                    'AnoEmpenho' => $empenho->e60_anousu,
                    'Competencia' => $this->competencia,
                    'AnoLiquidacaoRestosPagar' => substr($empenho->e50_data, 0, 4),
                    'NumeroLiquidacaoRestosPagar' => $empenho->e69_codnota,
                    'Tipo' => $tipo,
                    'NumeroDocumento' => $empenho->e69_numero,
                    'DataEmissao' => $empenho->e69_dtnota,                    
                    'Valor' => $novovalor,
                    'CpfCnpjNifEmitente' => $empenho->z01_cgccpf,
                    'NaturezaEmitente' => $natureza,
                    'Nome' => utf8_encode($empenho->z01_nome),
                    'DataAtestacao' => $empenho->e69_dtrecebe,
                    'CodigoOrgao' => $empenho->o58_orgao,
                    'CodigoUnidadeOrcamentaria' => $empenho->o58_unidade,                    
                    'Atestadores' => NULL,
                ];
            }else{
                
                $tipo = 1;
                $natureza = strlen($empenho->z01_cgccpf) == 11 ? 2 : 1;
                $novovalor = $this->voltaValor($empenho->e60_numemp);
                if(!$novovalor || $novovalor == null){
                    $novovalor = "0";
                }
                if($novovalor == 0){continue;}
                

                $data = (object)[                    
                    'Identificador' => $xi,
                    'CodigoUnidadeGestora' => $this->sCodigoTribunal,
                    'NumeroEmpenho' => $empenho->e60_codemp,
                    'AnoEmpenho' => $empenho->e60_anousu,
                    'Competencia' => $this->competencia,
                    'AnoLiquidacaoRestosPagar' => substr($empenho->e50_data, 0, 4),
                    'NumeroLiquidacaoRestosPagar' => $empenho->e69_codnota,
                    'Tipo' => $tipo,
                    'NumeroDocumento' => $empenho->e69_numero,
                    'DataEmissao' => $empenho->e69_dtnota,                    
                    'Valor' => $novovalor,
                    'CpfCnpjNifEmitente' => $empenho->z01_cgccpf,
                    'NaturezaEmitente' => $natureza,
                    'Nome' => utf8_encode($empenho->z01_nome),
                    'DataAtestacao' => $empenho->e69_dtrecebe,
                    'CodigoOrgao' => $empenho->o58_orgao,
                    'CodigoUnidadeOrcamentaria' => $empenho->o58_unidade,
                    'Atestadores' => (object)['Atestador' => $atestadores],                
                ];
            }
            
            
            
            $xi++;           
            
            
            $obj->LiquidacoesRestosPagarDocumentosDiversos[] = (object) [
                'LiquidacaoRestosPagarDocumentoDiverso' => $data
            ];
        }
        
        $this->aDados = $obj;
    }
}
