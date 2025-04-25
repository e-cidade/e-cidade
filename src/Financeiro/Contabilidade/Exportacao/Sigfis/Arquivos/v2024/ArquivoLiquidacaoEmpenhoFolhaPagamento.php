<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Arquivos\v2024;

use ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Common\Helper;
use ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Xsd\XSDFactory;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Query\JoinClause;
use stdClass;

class ArquivoLiquidacaoEmpenhoFolhaPagamento extends ArquivoBase
{
    protected $sNomeArquivo = 'LiquidacaoEmpenhoFolhaPagamento';

    public function validaTipo($codnota){
        $sql = pg_query("SELECT e177_codigo, e177_descr, e178_sigfistipodocliquidacao FROM sigfistipodocliquidacao INNER JOIN empnotasigfistipodocliquidacao ON e178_sigfistipodocliquidacao = e177_sequencial WHERE e178_empnota = {$codnota}");
        $resultado = pg_fetch_all($sql);

        if($resultado[0]["e178_sigfistipodocliquidacao"] == 2 || $resultado[0]["e178_sigfistipodocliquidacao"] == 3){
            return true;
        }
        return false;
        
    }

    
    public function voltaValor($seqempenho, $codnota){
        $sql = pg_query("SELECT e70_vlrliq from empnota inner join empnotaele on e70_codnota = e69_codnota left join pagordemnota on e70_codnota = e71_codnota and e71_anulado is false left join pagordemele on e71_codord = e53_codord where e69_numemp = {$seqempenho} AND e69_codnota = {$codnota} order by e69_dtnota");
        $resultado = pg_fetch_all($sql);
        return $resultado[0]["e70_vlrliq"];
    }

    public function gerarDados()
    {
        $campos = [
            'e60_numemp', 'e60_codemp', 'e60_anousu',
            'o58_orgao', 'o58_unidade',
            'e69_codnota', 'e69_anousu',
            'e60_vlrliq',
            'e50_obs',
            'e164_data',
            //'conta',
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
            ->join('orcdotacao', function (JoinClause $join) {
                $join->on('o58_anousu', 'e60_anousu')
                    ->on('o58_coddot', 'e60_coddot');
            })
            ->join('empelemento', 'e64_numemp', 'e60_numemp')
            ->join('conplanoorcamento', function (JoinClause $join) {
                $join->on('c60_codcon', 'e64_codele')
                    ->on('c60_anousu', 'e60_anousu');
            })            
            ->leftJoin('empcompetencialiquidacao', 'e164_codord', 'e50_codord')
            ->where('e60_anousu', $this->iAnoUsu)
            ->where('e60_instit', $this->instit)
            ->whereBetween('e69_dtinclusao', [$this->dtDataInicial, $this->dtDataFinal])
            ->where('e60_vlrliq', '!=', 0)
            ->orderBy('e60_numemp')
            ->get();

        if ($empenhos->isEmpty()) {
            throw new \Exception('Não foi encontrado nenhum empenho para o arquivo da Remessa.');
        }

        $obj = new stdClass();
        $obj->LiquidacoesEmpenhoFolhasPagamentos = [];
        
        $numeroFolha = ['normal' => 0, 'decimo' => 0];
        $ix = 100;
        
        foreach ($empenhos as $empenho) {
            
            $vt = $this->validaTipo($empenho->e69_codnota);
            if(!$vt){continue;}
            $parts = explode('-', $this->dtDataFinal);
            $mesFolha = $parts[1];
            $anoFolha = $empenho->e60_anousu;
            if (!empty($empenho->e164_data) && $empenho->e164_data !== '00/0000') {
                $parts = explode('/', $empenho->e164_data);
                $mesFolha = $parts[0];
                $anoFolha = $parts[1];
            }

            $tipoFolha = 1;
            if (in_array($empenho->conta, [31900106, 31900126, 31900303, 31900304, 31900413, 31901143])) {
                $tipoFolha = 2;
            }

            $tipo = $tipoFolha === 1 ? 'normal' : 'decimo';
            $numeroFolha[$tipo] += 1;

            $novovalor = $this->voltaValor($empenho->e60_numemp, $empenho->e69_codnota);



            $data = (object)[
                "Identificador" => $empenho->e60_numemp.$ix,
                "CodigoUnidadeGestora" => $this->sCodigoTribunal,
                "NumeroEmpenho" => $empenho->e60_codemp,
                "AnoEmpenho" => $empenho->e60_anousu,
                "Competencia" => $this->competencia,
                "NumeroLiquidacaoEmpenho" => $empenho->e69_codnota,
                "NumeroFolhaPagamento" => str_pad($numeroFolha[$tipo], 2, '0', STR_PAD_LEFT),
                "AnoReferencia" => $anoFolha,
                "MesReferencia" => $mesFolha,
                "Valor" => $novovalor, //$empenho->e60_vlrliq,
                "Objeto" => utf8_encode(Helper::convertAndLimit($empenho->e50_obs, 200)),
                "AnoLiquidacaoDeEmpenho" => $empenho->e69_anousu,
                "CodigoOrgao" => $empenho->o58_orgao,
                "CodigoUnidadeOrcamentaria" => $empenho->o58_unidade,
                "CodigoUnidadeGestoraResponsavel" => $this->getUgResponsavelFolha(),
                "CodigoTipoFolhaPagamento" => $tipoFolha,
            ];
            $obj->LiquidacoesEmpenhoFolhasPagamentos[] = (object)['LiquidacaoEmpenhoFolhaPagamento' => $data];
            $ix++;
        }

        $this->aDados = $obj;
    }
}
