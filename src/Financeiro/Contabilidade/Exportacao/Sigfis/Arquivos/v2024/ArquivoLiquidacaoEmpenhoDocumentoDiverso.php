<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Arquivos\v2024;

use ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Arquivos\v2024\ArquivoBase;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Query\JoinClause;
use stdClass;

class ArquivoLiquidacaoEmpenhoDocumentoDiverso extends ArquivoBase
{
    protected $sNomeArquivo = 'LiquidacaoEmpenhoDocumentoDiverso';

    public function validaTipo($codnota){
        $sql = pg_query("SELECT e177_codigo, e177_descr, e178_sigfistipodocliquidacao FROM sigfistipodocliquidacao INNER JOIN empnotasigfistipodocliquidacao ON e178_sigfistipodocliquidacao = e177_sequencial WHERE e178_empnota = {$codnota}");
        $resultado = pg_fetch_all($sql);

        if($resultado[0]["e178_sigfistipodocliquidacao"] == 4 || $resultado[0]["e178_sigfistipodocliquidacao"] == 5){
            return true;
        }
        return false;
        
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
            'e178_sigfistipodocliquidacao',
            'z01_nome',
            'z01_cgccpf',
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
            ->join('cgm', 'z01_numcgm', 'e60_numcgm')
            ->where('e60_anousu', $this->iAnoUsu)
            ->whereIn('e178_sigfistipodocliquidacao', [4, 5])
            ->where('e60_instit', $this->instit)
            ->whereBetween('e69_dtinclusao', [$this->dtDataInicial, $this->dtDataFinal])
            ->where('e60_vlrliq', '!=', 0)
            ->orderBy('e60_numemp')
            ->get();
            

        if ($empenhos->isEmpty()) {
            throw new \Exception('Não foi encontrado nenhum empenho de Liquidação de Empenho - Documento Diverso.');
        }

        $obj = new stdClass();
        $obj->LiquidacoesEmpenhoDocumentosDiversos = [];

        
        $guardao = array();
        foreach ($empenhos as $empenho) {
            
            if(in_array($empenho->e69_codnota, $guardao)){
                continue;
            }
            array_push($guardao, $empenho->e69_codnota);

            
            $vt = $this->validaTipo($empenho->e69_codnota);            

            
            
            if(!$vt){continue;}
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
                        'Nome' => trim(utf8_encode($atestador->z01_nome)),
                        'CPF' => $cpf,
                    ];
                })->toArray();


            if(empty($atestadores)){

                $atestadores = new stdClass();
                $atestadores->Identificador = NULL;
                $atestadores->Nome = NULL;
                $atestadores->CPF = NULL;

                $tipo = $empenho->e178_sigfistipodocliquidacao == 4 ? 1 : 2;
                $natureza = strlen($empenho->z01_cgccpf) == 11 ? 2 : 1;

            $data = (object)[
                'Identificador' => $empenho->e69_codnota,
                'CodigoUnidadeGestora' => $this->sCodigoTribunal,
                'NumeroEmpenho' => $empenho->e60_codemp,
                'AnoEmpenho' => $empenho->e60_anousu,
                'Competencia' => $this->competencia,
                'NumeroLiquidacaoEmpenho' => $empenho->e69_codnota,
                'NumeroDocumento' => $empenho->e69_numero,
                'Tipo' => $tipo,
                'DataEmissao' => $empenho->e69_dtnota,
                'Valor' => $empenho->e60_vlrliq,
                'CpfCnpjNifEmitente' => $empenho->z01_cgccpf,
                'NaturezaEmitente' => $natureza,
                'Nome' => trim(utf8_encode($empenho->z01_nome)),
                'Atestadores' => NULL,
                'DataAtestacao' => $empenho->e69_dtrecebe,
                'AnoLiquidacaoDeEmpenho' => $empenho->e69_anousu,
                'CodigoOrgao' => $empenho->o58_orgao,
                'CodigoUnidadeOrcamentaria' => $empenho->o58_unidade,
            ];

            }else{
                $tipo = $empenho->e178_sigfistipodocliquidacao == 4 ? 1 : 2;
            $natureza = strlen($empenho->z01_cgccpf) == 11 ? 2 : 1;

            $data = (object)[
                'Identificador' => $empenho->e69_codnota,
                'CodigoUnidadeGestora' => $this->sCodigoTribunal,
                'NumeroEmpenho' => $empenho->e60_codemp,
                'AnoEmpenho' => $empenho->e60_anousu,
                'Competencia' => $this->competencia,
                'NumeroLiquidacaoEmpenho' => $empenho->e69_codnota,
                'NumeroDocumento' => $empenho->e69_numero,
                'Tipo' => $tipo,
                'DataEmissao' => $empenho->e69_dtnota,
                'Valor' => $empenho->e60_vlrliq,
                'CpfCnpjNifEmitente' => $empenho->z01_cgccpf,
                'NaturezaEmitente' => $natureza,
                'Nome' => trim(utf8_encode($empenho->z01_nome)),
                'Atestadores' => (object)['Atestador' => $atestadores],
                'DataAtestacao' => $empenho->e69_dtrecebe,
                'AnoLiquidacaoDeEmpenho' => $empenho->e69_anousu,
                'CodigoOrgao' => $empenho->o58_orgao,
                'CodigoUnidadeOrcamentaria' => $empenho->o58_unidade,
            ];    
            }

            

            $obj->LiquidacoesEmpenhoDocumentosDiversos[] = (object)['LiquidacaoEmpenhoDocumentoDiverso' => $data];
        }

        $this->aDados = $obj;
    }
}
