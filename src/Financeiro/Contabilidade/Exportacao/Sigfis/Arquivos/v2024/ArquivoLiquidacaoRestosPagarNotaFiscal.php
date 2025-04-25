<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Arquivos\v2024;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Query\JoinClause;

class ArquivoLiquidacaoRestosPagarNotaFiscal extends ArquivoBase
{
    protected $sNomeArquivo = 'LiquidacaoRestosPagarNotaFiscal';

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
            'e69_outrosdados',
            'e50_data',
            'e70_vlrliq',
            'e70_valor', 
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
            ->where('e178_sigfistipodocliquidacao', 1)
            ->where('e60_instit', $this->instit)
            ->whereBetween('e69_dtinclusao', [$this->dtDataInicial, $this->dtDataFinal])
            ->where('e60_vlrliq', '!=', 0)
            ->orderBy('e60_numemp')
            ->distinct()
            ->get();
            
        if ($empenhos->isEmpty()) {
            throw new \Exception('Não foi encontrado nenhum empenho para o arquivo da Remessa.');
        }

        $obj = new \stdClass();
        $obj->LiquidacoesRestosPagarNotasFiscais = [];
        $xi = 1;        
        
        foreach ($empenhos as $empenho) {            
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
                        'NomeAtestador' => utf8_encode($atestador->z01_nome),
                        'CPF' => $cpf,
                    ];
                })->toArray();

            $dadosNota = \JSON::create()->parse($empenho->e69_outrosdados);
            if($empenho->e60_anousu == $anoexercicio){continue;}

            $data = (object)[
                'Identificador' => $xi,
                'CodigoUnidadeGestora' => $this->sCodigoTribunal,
                'NumeroEmpenho' => $empenho->e60_codemp,
                'AnoEmpenho' => $empenho->e60_anousu,
                'Competencia' => $this->competencia,
                'NumeroLiquidacaoRestosPagar' => $empenho->e69_codnota,
                'AnoLiquidacaoRestosPagar' => substr($empenho->e50_data, 0, 4),
                'DANFE' => (empty($dadosNota->danfe->numero)) ? "false" : "false",
                'ChaveDANFE' => $dadosNota->danfe->chave,
                'NumeroNF' => $empenho->e69_numero,
                'Serie' => $empenho->e69_serienota,
                'Data' => $empenho->e69_dtnota,
                'Valor' => $empenho->e70_valor,
                'DataAtestacao' => $empenho->e69_dtrecebe,
                'CodigoOrgao' => $empenho->o58_orgao,
                'CodigoUnidadeOrcamentaria' => $empenho->o58_unidade,

                'Atestadores' => ($atestadores) ? (object)['Atestador' => $atestadores] : NULL
            ];
            
            $xi++;
            $obj->LiquidacoesRestosPagarNotasFiscais[] = (object)['LiquidacaoRestosPagarNotaFiscal' => $data];
        }

        $this->aDados = $obj;
    }
}
