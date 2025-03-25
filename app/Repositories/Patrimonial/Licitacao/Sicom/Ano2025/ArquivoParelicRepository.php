<?php

namespace App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025;

use Illuminate\Database\Capsule\Manager as DB;

class ArquivoParelicRepository
{
    public function getDados($licitacoes)
    {
        return DB::select(
            "SELECT DISTINCT 
                '10' AS tipoRegistro,
                infocomplementaresinstit.si09_codorgaotce AS codOrgaoResp,
                (
                    SELECT 
                        CASE
                            WHEN o41_subunidade != 0 OR NOT NULL THEN 
                                LPAD(
                                    (CASE 
                                        WHEN o40_codtri = '0' OR NULL THEN o40_orgao::varchar 
                                        ELSE o40_codtri 
                                    END), 2, 0
                                ) ||
                                LPAD(
                                    (CASE 
                                        WHEN o41_codtri = '0' OR NULL THEN o41_unidade::varchar 
                                        ELSE o41_codtri 
                                    END), 3, 0
                                ) ||
                                LPAD(o41_subunidade::integer, 3, 0)
                            ELSE 
                                LPAD(
                                    (CASE 
                                        WHEN o40_codtri = '0' OR NULL THEN o40_orgao::varchar 
                                        ELSE o40_codtri 
                                    END), 2, 0
                                ) ||
                                LPAD(
                                    (CASE 
                                        WHEN o41_codtri = '0' OR NULL THEN o41_unidade::varchar 
                                        ELSE o41_codtri 
                                    END), 3, 0
                                )
                        END AS codunidadesub
                    FROM db_departorg
                    JOIN public.infocomplementares 
                        ON si08_anousu = db01_anousu
                        AND si08_instit = " . db_getsession("DB_instit") . "
                    JOIN orcunidade 
                        ON db01_orgao = o41_orgao
                        AND db01_unidade = o41_unidade
                        AND db01_anousu = o41_anousu
                    JOIN orcorgao 
                        ON o40_orgao = o41_orgao 
                        AND o40_anousu = o41_anousu
                    WHERE db01_coddepto = l20_codepartamento 
                        AND db01_anousu = " . db_getsession("DB_anousu") . "
                    LIMIT 1
                ) AS codUnidadeSubResp,
                liclicita.l20_anousu AS exercicioLicitacao,
                liclicita.l20_edital AS nroProcessoLicitatorio,
                parecerlicitacao.l200_data AS dataParecer,
                parecerlicitacao.l200_tipoparecer AS tipoParecer,
                cgm.z01_cgccpf AS nroCpf,
                manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant,
                infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual
            FROM liclicita AS liclicita
            INNER JOIN parecerlicitacao 
                ON liclicita.l20_codigo = parecerlicitacao.l200_licitacao
            INNER JOIN cgm 
                ON parecerlicitacao.l200_numcgm = cgm.z01_numcgm
            INNER JOIN db_config 
                ON liclicita.l20_instit = db_config.codigo
            INNER JOIN cflicita 
                ON cflicita.l03_codigo = liclicita.l20_codtipocom
            INNER JOIN pctipocompratribunal 
                ON cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial
            LEFT JOIN public.infocomplementaresinstit 
                ON db_config.codigo = public.infocomplementaresinstit.si09_instit
            LEFT JOIN manutencaolicitacao 
                ON manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo
			     WHERE l20_statusenviosicom in(1,2) AND l200_enviosicom = false AND db_config.codigo=" . db_getsession("DB_instit") . " and l03_pctipocompratribunal in (48,49,50,51,52,53,54,110) AND l20_codigo in ($licitacoes)"
        );
    }
}
