<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Arquivos\v2024;

use BusinessException;
use ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Common\Helper;
use Illuminate\Database\Capsule\Manager as DB;
use stdClass;

class ArquivoAnulacaoPagamentoDeEmpenhoConsignacaoRetencao extends ArquivoBase
{
    protected $sNomeArquivo = 'AnulacaoPagamentoDeEmpenhoConsignacaoRetencao';

    public function gerarDados()
    {
        $retencoes = $this->getRetencoes();

        if ($retencoes->isEmpty()) {
            throw new BusinessException('Não há retenções para a competência informada.');
        }

        $EmpenhoAnuConsignacaoRetencao = new stdClass();
        $EmpenhoAnuConsignacaoRetencao->AnulacoesPagamentosDeEmpenhoConsignacoesRetencoes = [];
        $ix = 100000;
        foreach ($retencoes as $retencao) {
            
            $AnuConsigRet = new stdClass();
            $AnuConsigRet->Identificador = $retencao->Identificador;
            $AnuConsigRet->CodigoUnidadeGestora = $this->sCodigoTribunal;
            $AnuConsigRet->Competencia = $this->competencia;
            $AnuConsigRet->NumeroEmpenho = $retencao->NumeroEmpenho;
            $AnuConsigRet->AnoEmpenho = $retencao->AnoEmpenho;
            
            
            $AnuConsigRet->NumeroNotaPagamentoDeEmpenhoConsignacaoRetencao = $retencao->NumeroNota;
            $AnuConsigRet->AnoNotaPagamentoDeEmpenhoConsignacaoRetencao = $retencao->AnoLiquidacaoEmpenho;
            
            $AnuConsigRet->NumeroNotaAnulacao = $ix;

            
            $AnuConsigRet->DataAnulacao = $retencao->DataAnulacao;
            $AnuConsigRet->Justificativa = Helper::convertAndLimit(trim($retencao->Justificativa));
            $AnuConsigRet->CpfResponsavel = $retencao->CPFResponsavel;
            $AnuConsigRet->TipoConsignacaoRetencaoPaga = $this->convertTipoRetencao($retencao->Tipo);
            
            $AnuConsigRet->Valor = $retencao->Valor;
            $AnuConsigRet->TipoAnulacaoMovimento = 2;

            $EmpenhoAnuConsignacaoRetencao->AnulacoesPagamentosDeEmpenhoConsignacoesRetencoes[] = (object) [
                'AnulacaoPagamentoDeEmpenhoConsignacaoRetencao' => $AnuConsigRet
            ];
            $ix++;
        }

        $this->aDados = $EmpenhoAnuConsignacaoRetencao;
    }

    private function getRetencoes()
    {
        $query = DB::table('empempenho')
            ->select([
                'c70_codlan as Identificador',
                'empempenho.e60_codemp as NumeroEmpenho',
                'empempenho.e60_anousu as AnoEmpenho',
                'orcdotacao.o58_orgao as CodigoOrgao',
                'orcdotacao.o58_unidade as CodigoUnidadeOrcamentaria',
                'empnota.e69_numero as NumeroNota',
                'c70_data as DataAnulacao',
                'e32_sequencial as Tipo',
                'c70_valor as Valor',
                'cgmusu.z01_cgccpf as CPFResponsavel',
                'empnota.e69_codnota as IdentificadorLiquidacao',
                'empnota.e69_anousu as AnoLiquidacaoEmpenho',
                'c72_complem as Justificativa'
            ])
            ->join('orcdotacao', function ($join) {
                $join
                ->on('o58_anousu', 'e60_anousu')
                ->on('o58_coddot', 'e60_coddot');
            })
            ->join('empnota', 'e69_numemp', 'e60_numemp')
            ->join('pagordemnota', 'e71_codnota', 'e69_codnota')
            ->join('pagordem', 'e50_codord', 'e71_codord')
            ->join('db_usuacgm', 'db_usuacgm.id_usuario', 'e50_id_usuario')
            ->join('cgm AS cgmusu', 'cgmlogin', 'cgmusu.z01_numcgm')
            ->join('conlancamemp', 'c75_numemp', 'e60_numemp')
            ->join('conlancam', 'c75_codlan', 'c70_codlan')
            ->join('conlancamcompl', 'c72_codlan', 'c70_codlan')
            ->join('conlancamdoc', 'c71_codlan', 'c70_codlan')
            ->join('conlancamord AS ord_ret', function ($join) {
                $join
                ->on('ord_ret.c80_codord', 'e71_codord')
                ->on('ord_ret.c80_codlan', 'c70_codlan');
            })
            ->join('conlancamretencao', 'c127_conlancam', 'c70_codlan')
            ->join('retencaotiporec', 'e21_sequencial', 'c127_retencaotiporec')
            ->join('retencaotipocalc', 'e32_sequencial', 'e21_retencaotipocalc')
            ->whereIn('c71_coddoc', [6003, 6005])
            ->where('e60_instit', $this->instit)
            ->whereBetween('c70_data', [$this->dtDataInicial, $this->dtDataFinal])
            ->get();            

        return $query;
    }

    private function convertTipoRetencao($tipo)
    {
        switch ($tipo) {
            // Imposto de Renda Retido na Fonte (IRRF)
            case '1':
            case '2':
                return 6;
                // Contribuições Previdenciárias
            case '3':
            case '4':
            case '7':
                return 3;
                // Imposto Sobre Serviços
            case '5':
                return 7;
                // Outras Retenções
            default:
                return 8;
        }
    }
}
