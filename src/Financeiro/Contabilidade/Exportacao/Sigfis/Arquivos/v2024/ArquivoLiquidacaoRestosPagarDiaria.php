<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Arquivos\v2024;

use ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Common\Helper;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Query\JoinClause;
use stdClass;

class ArquivoLiquidacaoRestosPagarDiaria extends ArquivoBase
{
    protected $sNomeArquivo = 'LiquidacaoRestosPagarDiaria';

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
            'e70_vlrliq',
            'e50_obs',
            'e69_anousu',
            //'conta',
            'cgmusu.z01_cgccpf',
            'e46_nome',
            'e446_quantidade',
            'e446_datainicio',
            'e446_datafim',
            'e446_tipodiaria',
            'e446_estadodestino',
            'e446_destino',
            'e446_paisdestino',
            'e446_motivo',
            'e446_sequencial',
            'e82_codmov',
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
            ->join('empenho.empord', 'e82_codord', 'e50_codord')
            ->join('emppresta', 'e45_codmov', 'e82_codmov')
            ->join('empprestaitem', function (JoinClause $join) {
                $join->on('e46_numemp', 'e60_numemp')
                    ->on('e46_emppresta', 'e45_sequencial');
            })
            ->join('empprestaitemdiaria', 'e446_empprestaitem', 'e46_codigo')
            ->join('db_usuacgm', 'id_usuario', 'e50_id_usuario')
            ->join('cgm AS cgmusu', 'cgmusu.z01_numcgm', 'cgmlogin')
            ->join('orcdotacao', function (JoinClause $join) {
                $join->on('o58_anousu', 'e60_anousu')
                    ->on('o58_coddot', 'e60_coddot');
            })
            ->join('empelemento', 'e64_numemp', 'e60_numemp')
            ->join('conplanoorcamento', function (JoinClause $join) {
                $join->on('c60_codcon', 'e64_codele')
                    ->on('c60_anousu', 'e60_anousu');
            })            
            ->where('e60_instit', $this->instit)
            ->whereBetween('e69_dtinclusao', [$this->dtDataInicial, $this->dtDataFinal])
            ->where('e70_vlrliq', '!=', 0)
            ->orderBy('e60_numemp')
            ->get();            

        if ($empenhos->isEmpty()) {
            throw new \Exception('Não foi encontrado nenhum empenho para o arquivo da Remessa.');
        }

        $obj = new stdClass();
        $obj->LiquidacoesRestosPagarDiarias = [];
        foreach ($empenhos as $empenho) {
            $data = (object)[
                "Identificador" => $empenho->e69_codnota,
                "CodigoUnidadeGestora" => $this->sCodigoTribunal,
                "NumeroEmpenho" => $empenho->e60_codemp,
                "AnoEmpenho" => $empenho->e60_anousu,
                "AnoLiquidacaoRestosPagar" => $empenho->e69_anousu,
                "Competencia" => $this->competencia,
                "CPFServidor" => $empenho->z01_cgccpf,
                "NumeroDiaria" => $empenho->e446_sequencial,
                "NumeroLiquidacaoRestosPagar" => $empenho->e69_codnota,
                "Nome" => Helper::convertAndLimit($empenho->e46_nome, 255),
                "Valor" => $empenho->e70_vlrliq,
                "QuantidadeDiarias" => $empenho->e446_quantidade,
                "DataSaida" => $empenho->e446_datainicio,
                "DataRetorno" => $empenho->e446_datafim,
                "IndicadorDestino" => $empenho->e446_tipodiaria === 'internacional' ? 2 : 1,
                "EstadoDestino" => Helper::convertAndLimit($empenho->e446_estadodestino, 50),
                "CidadeDestino" => Helper::convertAndLimit($empenho->e446_destino, 50),
                "PaisDestino" => Helper::convertAndLimit($empenho->e446_paisdestino, 50),
                "ObjetoDiaria" => Helper::convertAndLimit($empenho->e446_motivo, 4000),
                "CodigoOrgao" => $empenho->o58_orgao,
                "CodigoUnidadeOrcamentaria" => $empenho->o58_unidade,
            ];

            $obj->LiquidacoesRestosPagarDiarias[] = (object)['LiquidacaoRestosPagarDiaria' => $data];
        }
        $this->aDados = $obj;
    }
}
