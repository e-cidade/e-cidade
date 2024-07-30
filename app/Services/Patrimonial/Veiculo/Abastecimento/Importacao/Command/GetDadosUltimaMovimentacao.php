<?php

namespace App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Command;

use Illuminate\Database\Capsule\Manager as DB;

class GetDadosUltimaMovimentacao
{
    /**
     * Undocumented function
     *
     * @param integer $codigoVeiculo
     * @param string $dataBusca
     * @param string $horaBusca
     * @param boolean $dadoAnterior
     * @return array|null
     */
    public function execute(int $codigoVeiculo, string $dataBusca, string $horaBusca, bool $dadoAnterior = true): ?array
    {
        $sql =  $dadoAnterior ? $this->getMedidaUsoAnterior($codigoVeiculo, $dataBusca, $horaBusca) : $this->getMedidaUsoPosterior($codigoVeiculo, $dataBusca, $horaBusca);

        $result = DB::select( DB::raw($sql));

        return $result;
    }

    /**
     * Undocumented function
     *
     * @param integer $codigoVeiculo
     * @param string $data
     * @param string $hora
     * @return void
     */
    private function getMedidaUsoAnterior(int $codigoVeiculo, string $data, string $hora)
    {
        $sql = "
            SELECT *
            FROM (
                    (SELECT ve70_dtabast AS DATA,
                            ve70_hora AS hora,
                            ve70_medida AS ultimamedida,
                            'ABASTECIMENTO' AS tipo
                    FROM veicabast
                    WHERE ve70_veiculos = {$codigoVeiculo}
                         AND to_timestamp((ve70_dtabast||' '||ve70_hora)::text, 'YYYY-MM-DD HH24:MI') <= to_timestamp('{$data} {$hora}'::text, 'YYYY-MM-DD HH24:MI')
                         AND NOT EXISTS
                             (SELECT 1
                              FROM veicabastanu
                              WHERE ve74_veicabast = ve70_codigo)
                    ORDER BY ve70_dtabast DESC, ve70_hora DESC, ultimamedida DESC, ve70_codigo DESC
                    LIMIT 1)
                    UNION ALL
                        (SELECT ve62_dtmanut AS DATA,
                                ve62_hora AS hora,
                                ve62_medida AS ultimamedida,
                                'MANUTENCAO' AS tipo
                         FROM veicmanut
                         WHERE ve62_veiculos = {$codigoVeiculo}
                             AND to_timestamp((ve62_dtmanut||' '||ve62_hora)::text, 'YYYY-MM-DD HH24:MI') <= to_timestamp('{$data} {$hora}'::text, 'YYYY-MM-DD HH24:MI')
                         ORDER BY ve62_dtmanut DESC, ve62_hora DESC, ultimamedida DESC, ve62_codigo DESC
                         LIMIT 1)
                    UNION ALL
                      (SELECT ve61_datadevol AS DATA,
                              ve61_horadevol AS hora,
                              ve61_medidadevol AS ultimamedida,
                              'DEVOLUCAO' AS tipo
                       FROM veicdevolucao
                       INNER JOIN veicretirada ON ve60_codigo = ve61_veicretirada
                       WHERE ve60_veiculo = {$codigoVeiculo}
                           AND to_timestamp((ve61_datadevol||' '||ve61_horadevol)::text, 'YYYY-MM-DD HH24:MI') <= to_timestamp('{$data} {$hora}'::text, 'YYYY-MM-DD HH24:MI')
                       ORDER BY ve61_datadevol DESC, ve61_horadevol DESC, ultimamedida DESC, ve61_codigo DESC
                       LIMIT 1)
                    UNION ALL
                      (SELECT ve60_datasaida AS DATA,
                              ve60_horasaida AS hora,
                              ve60_medidasaida AS ultimamedida,
                              'RETIRADA' AS tipo
                       FROM veicretirada
                       WHERE ve60_veiculo = {$codigoVeiculo}
                           AND to_timestamp((ve60_datasaida||' '||ve60_horasaida)::text, 'YYYY-MM-DD HH24:MI') <= to_timestamp('{$data} {$hora}'::text, 'YYYY-MM-DD HH24:MI')
                       ORDER BY ve60_datasaida DESC, ve60_horasaida DESC, ultimamedida DESC, ve60_codigo DESC
                       LIMIT 1)
                       UNION ALL
          (SELECT ve66_data AS DATA,
                  ve66_hora AS hora,
                  0 AS ultimamedida,
                  'MANUTENCAO DE MEDIDA' AS tipo
           FROM veicmanutencaomedida
           WHERE ve66_veiculo = {$codigoVeiculo}
               AND ve66_ativo = 't'
               AND to_timestamp((ve66_data||' '||ve66_hora)::text, 'YYYY-MM-DD HH24:MI') <= to_timestamp('{$data} {$hora}'::text, 'YYYY-MM-DD HH24:MI')
           ORDER BY ve66_data DESC, ve66_hora DESC, ultimamedida DESC, ve66_sequencial DESC
           LIMIT 1)) AS w
                       ORDER BY  1 DESC,
                                 2 DESC,
                                 3 DESC
                        LIMIT 1;";
        return $sql;
    }

    private function getMedidaUsoPosterior(int $codigoVeiculo, string $data, string $hora)
    {
        $sql = "
            SELECT *
            FROM (
                    (SELECT ve70_dtabast AS DATA,
                            ve70_hora AS hora,
                            ve70_medida AS ultimamedida,
                            'ABASTECIMENTO' AS tipo
                    FROM veicabast
                    WHERE ve70_veiculos = {$codigoVeiculo}
                         AND to_timestamp((ve70_dtabast||' '||ve70_hora)::text, 'YYYY-MM-DD HH24:MI') >= to_timestamp('{$data} {$hora}'::text, 'YYYY-MM-DD HH24:MI')
                         AND NOT EXISTS
                             (SELECT 1
                              FROM veicabastanu
                              WHERE ve74_veicabast = ve70_codigo)
                    ORDER BY ve70_dtabast ASC, ve70_hora ASC, ultimamedida ASC, ve70_codigo ASC
                    LIMIT 1)
                    UNION ALL
                        (SELECT ve62_dtmanut AS DATA,
                                ve62_hora AS hora,
                                ve62_medida AS ultimamedida,
                                'MANUTENCAO' AS tipo
                         FROM veicmanut
                         WHERE ve62_veiculos = {$codigoVeiculo}
                             AND to_timestamp((ve62_dtmanut||' '||ve62_hora)::text, 'YYYY-MM-DD HH24:MI') >= to_timestamp('{$data} {$hora}'::text, 'YYYY-MM-DD HH24:MI')
                         ORDER BY ve62_dtmanut ASC, ve62_hora ASC, ultimamedida ASC, ve62_codigo ASC
                         LIMIT 1)
                    UNION ALL
                      (SELECT ve61_datadevol AS DATA,
                              ve61_horadevol AS hora,
                              ve61_medidadevol AS ultimamedida,
                              'DEVOLUCAO' AS tipo
                       FROM veicdevolucao
                       INNER JOIN veicretirada ON ve60_codigo = ve61_veicretirada
                       WHERE ve60_veiculo = {$codigoVeiculo}
                           AND to_timestamp((ve61_datadevol||' '||ve61_horadevol)::text, 'YYYY-MM-DD HH24:MI') >= to_timestamp('{$data} {$hora}'::text, 'YYYY-MM-DD HH24:MI')
                       ORDER BY ve61_datadevol ASC, ve61_horadevol ASC, ultimamedida ASC, ve61_codigo ASC
                       LIMIT 1)
                    UNION ALL
                      (SELECT ve60_datasaida AS DATA,
                              ve60_horasaida AS hora,
                              ve60_medidasaida AS ultimamedida,
                              'RETIRADA' AS tipo
                       FROM veicretirada
                       WHERE ve60_veiculo = {$codigoVeiculo}
                           AND to_timestamp((ve60_datasaida||' '||ve60_horasaida)::text, 'YYYY-MM-DD HH24:MI') >= to_timestamp('{$data} {$hora}'::text, 'YYYY-MM-DD HH24:MI')
                       ORDER BY ve60_datasaida ASC, ve60_horasaida ASC, ultimamedida ASC, ve60_codigo ASC
                       LIMIT 1)
                       UNION ALL
          (SELECT ve66_data AS DATA,
                  ve66_hora AS hora,
                  0 AS ultimamedida,
                  'MANUTENCAO DE MEDIDA' AS tipo
           FROM veicmanutencaomedida
           WHERE ve66_veiculo = {$codigoVeiculo}
               AND ve66_ativo = 't'
               AND to_timestamp((ve66_data||' '||ve66_hora)::text, 'YYYY-MM-DD HH24:MI') >= to_timestamp('{$data} {$hora}'::text, 'YYYY-MM-DD HH24:MI')
           ORDER BY ve66_data ASC, ve66_hora ASC, ultimamedida ASC, ve66_sequencial ASC
           LIMIT 1)) AS w
           ORDER BY  1 ASC,
                     2 ASC,
                     3 ASC
           LIMIT 1;
    ";
        return $sql;
    }
}
