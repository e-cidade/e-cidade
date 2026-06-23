<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Arquivos\v2024;

use BusinessException;
use ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Common\Helper;
use Illuminate\Database\Capsule\Manager as DB;
use stdClass;

class ArquivoPagamentoRestosPagarAnulacao extends ArquivoBase
{
    protected $sNomeArquivo = 'PagamentoRestosPagarAnulacao';
    

    public function buscaCPFordenador($cgm){
        $sql = pg_query("SELECT z01_cgccpf FROM cgm WHERE z01_numcgm = {$cgm}");
        $resultado = pg_fetch_all($sql);
        return $resultado[0]["z01_cgccpf"];
    }

    public function gerarDados()
    {
        $estornos = $this->getEstornos();
        $ugs = $this->pegaCgmOrdenador();
        $ugs = $this->buscaCPFordenador($ugs);
        

        if ($estornos->isEmpty()) {
            throw new BusinessException('Não há dados para a competência informada.');
        }

        $data = new stdClass();
        $data->PagamentosRestosPagarAnulacoes = [];        

        $guardalancamento = array();

        foreach ($estornos as $estorno) {
            if(in_array($estorno->Identificador, $guardalancamento)){
                continue;
            }
            array_push($guardalancamento, $estorno->Identificador);
            
            $rpAnulacao = new stdClass();
            $rpAnulacao->Identificador = $estorno->Identificador;
            $rpAnulacao->CodigoUnidadeGestora = $this->sCodigoTribunal;
            $rpAnulacao->NumeroEmpenho = $estorno->NumeroEmpenho;
            $rpAnulacao->AnoEmpenho = $estorno->AnoEmpenho;
            $rpAnulacao->Competencia = $this->competencia;
            $rpAnulacao->NumeroAnulacao = $estorno->NumeroNotaPagamento;
            $rpAnulacao->DataAnulacao = $estorno->DataAnulacao;
            $rpAnulacao->NumeroNotaPagamento = $estorno->NumNota;
            $rpAnulacao->AnoPagamentoRestosPagar = $estorno->c70_anousu;
            $rpAnulacao->CPFResponsavel = $ugs;
            $rpAnulacao->Justificativa = utf8_decode(Helper::convertAndLimit($estorno->Justificativa, 255));
            $rpAnulacao->Valor = $estorno->Valor;
            $rpAnulacao->CodigoOrgao = $estorno->CodigoOrgao;
            $rpAnulacao->CodigoUnidadeOrcamentaria = $estorno->CodigoUnidadeOrcamentaria;

            $data->PagamentosRestosPagarAnulacoes[] = (object) ['PagamentoRestosPagarAnulacao' => $rpAnulacao];
        }

        $this->aDados = $data;
    }

    private function getEstornos()
    {
        $query = DB::table('empresto')
            ->select(
                'conlancam.c70_codlan as Identificador',
                'empempenho.e60_codemp as NumeroEmpenho',
                'empempenho.e60_anousu as AnoEmpenho',
                'conlancam.c70_data as DataAnulacao',
                'empnota.e69_codnota as NumeroNotaPagamento',
                'empnota.e69_numero as NumNota',
                'empnota.e69_anousu as AnoPagamentoRestosPagar',
                'cgmusu.z01_cgccpf as CPFResponsavel',
                'conlancamcompl.c72_complem as Justificativa',
                'conlancam.c70_valor as Valor',
                'orcdotacao.o58_orgao as CodigoOrgao',
                'orcdotacao.o58_unidade as CodigoUnidadeOrcamentaria',
                "c70_anousu"
            )
            ->join('empempenho', 'empempenho.e60_numemp', 'empresto.e91_numemp')
            ->join('orcdotacao', function ($join) {
                $join->on('orcdotacao.o58_coddot', 'empempenho.e60_coddot')
                    ->on('orcdotacao.o58_anousu', 'empempenho.e60_anousu');
            })
            ->join('empnota', 'empnota.e69_numemp', 'empempenho.e60_numemp')
            ->join('pagordemnota', 'pagordemnota.e71_codnota', 'empnota.e69_codnota')
            ->join('pagordem', 'pagordem.e50_codord', 'pagordemnota.e71_codord')
            ->join('db_usuacgm', 'db_usuacgm.id_usuario', 'pagordem.e50_id_usuario')
            ->join('cgm as cgmusu', 'cgmusu.z01_numcgm', 'db_usuacgm.cgmlogin')
            ->join('conlancamemp', 'conlancamemp.c75_numemp', 'empempenho.e60_numemp')
            ->join('conlancam', 'conlancam.c70_codlan', 'conlancamemp.c75_codlan')
            ->join('conlancamord', function ($join) {
                $join->on('conlancamord.c80_codlan', 'conlancam.c70_codlan')
                    ->on('conlancamord.c80_codord', 'pagordemnota.e71_codord');
            })
            ->join('conlancamcompl', 'c72_codlan', 'conlancam.c70_codlan')
            ->join('conlancamdoc', 'conlancamdoc.c71_codlan', 'conlancam.c70_codlan')
            ->whereIn('conlancamdoc.c71_coddoc', [36, 38])
            ->whereBetween('conlancam.c70_data', [$this->dtDataInicial, $this->dtDataFinal])
            ->where('empempenho.e60_instit', $this->instit);

        return $query->get();
    }
}
