<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Arquivos\v2024;

use ECidade\RecursosHumanos\ESocial\Configuracao\JSON;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Query\JoinClause;

class ArquivoLiquidacaoEmpenhoNotaFiscal extends ArquivoBase
{
    protected $sNomeArquivo = 'LiquidacaoEmpenhoNotaFiscal';

    

    public function validaTipo($codnota){
        $sql = pg_query("SELECT e177_codigo, e177_descr, e178_sigfistipodocliquidacao FROM sigfistipodocliquidacao INNER JOIN empnotasigfistipodocliquidacao ON e178_sigfistipodocliquidacao = e177_sequencial WHERE e178_empnota = {$codnota}");
        $resultado = pg_fetch_all($sql);

        if($resultado[0]["e178_sigfistipodocliquidacao"] == 1){
            return true;
        }
        return false;
    }

    public function buscaValor($seqempenho, $seqnota){
        $sql = pg_query("SELECT e70_vlrliq, e70_valor from empnota inner join empnotaele on e70_codnota = e69_codnota left join pagordemnota on e70_codnota = e71_codnota and e71_anulado is false left join pagordemele on e71_codord = e53_codord where e69_numemp = {$seqempenho} AND e69_codnota = {$seqnota} order by e69_dtnota");
        $resultado = pg_fetch_all($sql);
        return $resultado[0]["e70_valor"];
    }

    public function gerarDados()
    {
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
            'e69_outrosdados',
        ];
        $empenhos = DB::table('empempenho')
            ->select($campos)
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
            ->where('e60_anousu', $this->iAnoUsu)
            ->where('e178_sigfistipodocliquidacao', 1)
            ->where('e60_instit', $this->instit)
            ->whereBetween('e69_dtinclusao', [$this->dtDataInicial, $this->dtDataFinal])
            ->where('e60_vlrliq', '!=', 0)            
            ->orderBy('e60_numemp')
            ->get();

        if ($empenhos->isEmpty()) {
            throw new \Exception('Não foi encontrado nenhum empenho para o arquivo da Remessa.');
        }
        
        $obj = new \stdClass();
        $obj->LiquidacoesEmpenhoNotasFiscais = [];
        foreach ($empenhos as $empenho) {
            $vt = $this->validaTipo($empenho->e69_codnota);
            if(!$vt){continue;}
            $novovalor = $this->buscaValor($empenho->e60_numemp, $empenho->e69_codnota);
            
            $atestadores = DB::table('empnotaatestador')
                ->select(['z01_numcgm', 'z01_nome', 'z01_cgccpf'])
                ->join('cgm', 'z01_numcgm', 'e169_numcgm')
                ->where('e169_empnota', $empenho->e69_codnota)
                ->limit(1)
                ->get()
                ->map(function ($atestador) {
                    return (object)[
                        'Identificador' => $atestador->z01_numcgm,
                        'NomeAtestador' => utf8_encode($atestador->z01_nome),
                        //'Nome' => $atestador->z01_nome,
                        'CPF' => $atestador->z01_cgccpf,
                    ];
                })->toArray();

            $dadosNota = \JSON::create()->parse($empenho->e69_outrosdados);            
            
            if($novovalor == 0){continue;}
            
            $data = (object)[
                'Identificador' => $empenho->e69_codnota,
                'CodigoUnidadeGestora' => $this->sCodigoTribunal,
                'NumeroEmpenho' => $empenho->e60_codemp,
                'AnoEmpenho' => $empenho->e60_anousu,
                'Competencia' => $this->competencia,
                'NumeroLiquidacaoEmpenho' => $empenho->e69_codnota,
                'AnoLiquidacaoEmpenho' => $empenho->e69_anousu,
                'CodigoOrgao' => $empenho->o58_orgao,
                'CodigoUnidadeOrcamentaria' => $empenho->o58_unidade,                
                'DANFE' => (empty($dadosNota->danfe->numero)) ? "false" : "false",
                'ChaveDANFE' => $dadosNota->danfe->chave,
                'NumeroNF' => $empenho->e69_numero,
                'Serie' => $empenho->e69_serienota,
                'Data' => $empenho->e69_dtnota,
                'DataAtestacao' => $empenho->e69_dtrecebe,
                'Valor' => $novovalor,
                'Atestadores' => ($atestadores) ? (object)['Atestador' => $atestadores] : NULL
            ];

            $obj->LiquidacoesEmpenhoNotasFiscais[] = (object)['LiquidacaoEmpenhoNotaFiscal' => $data];
        }
        
        $this->aDados = $obj;
    }
}
