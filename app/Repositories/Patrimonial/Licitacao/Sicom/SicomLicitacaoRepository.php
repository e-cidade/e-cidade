<?php

namespace App\Repositories\Patrimonial\Licitacao\Sicom;

use Illuminate\Database\Capsule\Manager as DB;

class SicomLicitacaoRepository
{
    public function getProcessos($filtros)
    {

        $instituicao =  db_getsession("DB_instit");

        return DB::select("select distinct on (seq)
            seq,
            processo,
            modalidade,
            numeracao,
            objeto,
            adesao,
            status,
            codigoStatus,
            datareferencia,
            remessa
        from (

            -- Primeira consulta: Processos

            select
                l20_codigo as seq,
                l20_edital as processo,
                l03_descr as modalidade,
                l20_numero as numeracao,
                l20_objeto as objeto,
                'false' as adesao,
                l225_status as status,
                l20_statusenviosicom as codigostatus,
            CASE
                WHEN l20_statusenviosicom = 2 AND l20_licsituacao = 0 THEN '-'
                WHEN l20_statusenviosicom = 3 AND l20_licsituacao IN (0, 1) THEN '-'
                WHEN l20_licsituacao = 0 THEN to_char(l20_dtpublic, 'DD/MM/YYYY')
                WHEN l20_licsituacao = 1 THEN to_char(l11_data, 'DD/MM/YYYY')
                WHEN l20_licsituacao = 10 THEN to_char(l202_datahomologacao, 'DD/MM/YYYY')
                ELSE '-'
                END AS datareferencia,
                l227_remessa as remessa
            from
                liclicita
            inner join cflicita on
                l03_codigo = l20_codtipocom
            inner join liclicitasituacao on
                liclicitasituacao.l11_liclicita = liclicita.l20_codigo 
                and l11_licsituacao = l20_licsituacao
            left join statusenviosicom on
                l225_sequencial = l20_statusenviosicom
            left join homologacaoadjudica on
                l202_licitacao = l20_codigo and l202_datahomologacao is not null
            left join remessasicom on
                l227_licitacao = l20_codigo
            where
                l20_instit = $instituicao and l03_pctipocompratribunal in (48,49,50,51,52,53,54,110) and l20_statusenviosicom != 5
                and (
                    l20_anousu >= 2025 
                    or (l20_licsituacao = 0 and (l20_dtpublic > '31-12-2024' and l20_dtpublic is not null)) 
                    or (l20_licsituacao = 1 and l11_data > '31-12-2024')
                    or (l20_licsituacao = 10 and l202_datahomologacao > '31-12-2024')
                )

            union all

            -- Segunda consulta: Dispensas

            select
                l20_codigo as seq,
                l20_edital as processo,
                l03_descr as modalidade,
                l20_numero as numeracao,
                l20_objeto as objeto,
                'false' as adesao,
                l225_status as status,
                l20_statusenviosicom as codigostatus,
                CASE
                    WHEN l20_dtpubratificacao IS NULL THEN '-'
                    ELSE to_char(l20_dtpubratificacao, 'DD/MM/YYYY')
                END AS datareferencia,
                l227_remessa as remessa
            from
                liclicita
            inner join cflicita on
                l03_codigo = l20_codtipocom
            left join statusenviosicom on
                l225_sequencial = l20_statusenviosicom
            left join remessasicom on
                l227_licitacao = l20_codigo
            where
                l20_dispensaporvalor = 'f' and l20_instit = $instituicao and l03_pctipocompratribunal in (100, 101, 102, 103) and l20_statusenviosicom != 5
                and (
                    l20_anousu >= 2025 
                    or l20_dtpubratificacao > '31-12-2024'
                ) and l20_licsituacao = 10

            union all

            -- Terceira consulta: Adesões

            select
                si06_sequencial as seq,
                si06_numeroadm as processo,
                'ADESÃO DE REGISTRO DE PREÇO' as modalidade,
                si06_nummodadm as numeracao,
                si06_objetoadesao as objeto,
                'true' as adesao,
                l225_status as status,
                si06_statusenviosicom as codigostatus,
                CASE
                    WHEN si06_dataadesao IS NULL THEN '-'
                    ELSE to_char(si06_dataadesao, 'DD/MM/YYYY')
                END AS datareferencia,
                l227_remessa as remessa
            from
                adesaoregprecos
            left join statusenviosicom on
                l225_sequencial = si06_statusenviosicom
           left join remessasicom on
                l227_adesao = si06_sequencial
            where si06_instit = $instituicao and si06_statusenviosicom != 5 and
                (si06_anomodadm >= 2025
                or si06_dataadesao > '31-12-2024')
        ) as processos where datareferencia is not null;");
    }
}
