<?php

namespace App\Repositories\Tributario\ISSQN;

class IssbaseLogRepository
{
    public function getLovrotSql(int $inscricao): string
    {
        return "
        SELECT issbaselog.q102_sequencial,
               issbaselog.q102_inscr,
               CASE issbaselog.q102_issbaselogtipo
                   WHEN 1 THEN 'INCLUSรO EMPRESA'
                   WHEN 2 THEN 'ALTERAวรO EMPRESA'
                   WHEN 3 THEN 'INGRESSO/RETIRADA SOCIO'
                   WHEN 4 THEN 'ALTERACAO CAPITAL SOCIAL'
                   WHEN 5 THEN 'INCLUSAO DE ATIVIDADES'
                   WHEN 6 THEN 'BAIXA ATIVIDADES PEDIDO'
                   WHEN 7 THEN 'BAIXA ATIVIDADES OFICIO'
                   WHEN 8 THEN 'ALTERACAO RAZAO SOCIAL NOME SOCIAL'
                   WHEN 9 THEN 'INCLUSAO ESCRITORIO CONTABIL'
                   WHEN 10 THEN 'EXCLUSAO ESCRITORIO CONTABIL'
                   WHEN 11 THEN 'ALTERACAO AREA'
                   ELSE 'NรO IDENTIFICADA'
               END AS q102_issbaselogtipo,
               issbaselog.q102_data,
               issbaselog.q102_hora,
               issbaselog.q102_obs,
               CASE issbaselog.q102_origem
                   WHEN 2 THEN 'MANUAL'
                   WHEN 3 THEN 'REDESIM'
                   ELSE 'AUTOMATICO'
               END AS q102_origem
        FROM issbaselog
        INNER JOIN issbase ON issbase.q02_inscr = issbaselog.q102_inscr
        INNER JOIN issbaselogtipo ON issbaselogtipo.q103_sequencial = issbaselog.q102_issbaselogtipo
        INNER JOIN cgm ON cgm.z01_numcgm = issbase.q02_numcgm
        WHERE q102_inscr = {$inscricao}
        ORDER BY q102_sequencial DESC
        ";
    }
}
