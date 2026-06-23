<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Arquivos\v2024;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Query\JoinClause;

class ArquivoLiquidacaoEmpenhoAdiantamento extends ArquivoBase
{
    protected $sNomeArquivo = 'LiquidacaoEmpenhoAdiantamento';

    /**
     * Busca os dados para gerar o Arquivo de Unidade Orçamentária
     */
    public function validaTipo($codnota){
        $sql = pg_query("SELECT e177_codigo, e177_descr, e178_sigfistipodocliquidacao FROM sigfistipodocliquidacao INNER JOIN empnotasigfistipodocliquidacao ON e178_sigfistipodocliquidacao = e177_sequencial WHERE e178_empnota = {$codnota}");
        $resultado = pg_fetch_all($sql);

        if($resultado[0]["e178_sigfistipodocliquidacao"] == 7){
            return true;
        }
        return false;        
    }

    public function buscaCPFordenador($cgm){
        $sql = pg_query("SELECT z01_cgccpf FROM cgm WHERE z01_numcgm = {$cgm}");
        $resultado = pg_fetch_all($sql);
        return $resultado[0]["z01_cgccpf"];
    }


    public function gerarDados()
    {       
        $ugs = $this->pegaCgmOrdenador();
        $ugs = $this->buscaCPFordenador($ugs);
        
        
        $campos = [
            'e03_numeroprocesso',
            'e50_data', 'e50_anousu', 'e50_obs',
            'o58_orgao', 'o58_unidade',
            'e60_codemp', 'e60_anousu', 'e60_numemp', 'e60_numcgm', 'e69_codnota',
            'e70_valor',
            'cgm_empenho.z01_cgccpf as cpf_responsavel',
            'cgm_empenho.z01_nome as nome_responsavel',
            'cgm_conta.z01_cgccpf as cpf_servidor',
            'cgm_conta.z01_nome as nome_servidor'
        ];

        $empenhos = DB::table('empempenho')
            ->select($campos)
            ->join('empnota', 'empnota.e69_numemp', 'empempenho.e60_numemp')
            ->join('empnotasigfistipodocliquidacao', 'e178_empnota', 'e69_codnota')
            ->join('sigfistipodocliquidacao', 'e177_sequencial', 'e178_sigfistipodocliquidacao')
            ->join('empnotaele', 'e70_codnota', 'e69_codnota')
            ->join('pagordem', 'e50_numemp', 'e69_numemp')
            ->leftJoin('pagordemprocesso', 'e03_pagordem', 'e50_codord')
            ->leftJoin('pagordemconta', 'e49_codord', 'e50_codord')
            ->join('pagordemnota', function (JoinClause $join) {
                $join->on('e71_codnota', 'e69_codnota')
                    ->on('e71_codord', 'e50_codord');
            })
            ->leftJoin('cgm AS cgm_empenho', 'cgm_empenho.z01_numcgm', 'e60_numcgm')
            ->leftJoin('cgm AS cgm_conta', 'cgm_conta.z01_numcgm', 'e49_numcgm')
            ->join('orcdotacao', function ($join) {
                $join->on('e60_anousu', 'o58_anousu')
                    ->on('e60_coddot', 'o58_coddot');
            })
            ->where('e60_anousu', $this->iAnoUsu)
            ->where('e60_instit', $this->instit)
            ->where('e177_codigo', '5')
            ->whereBetween('e69_dtinclusao', [$this->dtDataInicial, $this->dtDataFinal])
            ->get();

            
        if ($empenhos->isEmpty()) {
            throw new \Exception('Não foi encontrardo nenhum empenho para o arquivo de Remessa ');
        }

        $obj = new \stdClass();
        $obj->LiquidacoesEmpenhoAdiantamentos = [];

        foreach ($empenhos as $empenho) {
            $vt = $this->validaTipo($empenho->e69_codnota);
            if(!$vt){continue;}
            $dadosLiquidacaoEmpenho = (object)[
                'Identificador' => $empenho->e69_codnota,
                'CodigoUnidadeGestora' => $this->sCodigoTribunal,
                'NumeroEmpenho' => $empenho->e60_codemp,
                'AnoEmpenho' => $empenho->e60_anousu,
                'NumeroLiquidacaoEmpenho' => $empenho->e69_codnota,
                'Competencia' => $this->competencia,
                'CpfResponsavel' => $ugs,
                'CpfServidor' => !empty($empenho->cpf_servidor) ? $empenho->cpf_servidor :
                    $empenho->cpf_responsavel,
                'NomeServidor' => !empty($empenho->nome_servidor) ? $empenho->nome_servidor :
                    $empenho->nome_responsavel,
                'NumeroProcessoAdministrativo' => $empenho->e03_numeroprocesso,
                'ValorConcedido' => $empenho->e70_valor,
                'DataConcessao' => $empenho->e50_data,
                'DataLimite' => $empenho->e50_data,
                'Finalidade' => utf8_encode(substr($empenho->e50_obs, 0, 254)),
                'AnoLiquidacaoDeEmpenho' => $empenho->e50_anousu,
                'CodigoOrgao' => $empenho->o58_orgao,
                'CodigoUnidadeOrcamentaria' => $empenho->o58_unidade
            ];

            $obj->LiquidacoesEmpenhoAdiantamentos[] = (object)[
                'LiquidacaoEmpenhoAdiantamento' => $dadosLiquidacaoEmpenho
            ];
        }

        $this->aDados = $obj;
    }
}
