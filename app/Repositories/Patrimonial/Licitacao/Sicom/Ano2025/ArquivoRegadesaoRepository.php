<?php

namespace App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025;

use Illuminate\Database\Capsule\Manager as DB;

class ArquivoRegadesaoRepository
{
    public function getDadosRegistro10($adesoes)
    {
        return DB::select(
            "
                select adesaoregprecos.*,si06_dataadesao as exercicioadesao,orgaogerenciador.z01_cgccpf as cnpjorgaogerenciador,responsavel.z01_cgccpf as cpfresponsavel,
                       infocomplementaresinstit.si09_codorgaotce as codorgao,si06_anoproc as exerciciolicitacao,
                        infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual,

                (SELECT CASE
                 WHEN o41_subunidade != 0
                 OR NOT NULL THEN lpad((CASE WHEN o40_codtri = '0'
                 OR NULL THEN o40_orgao::varchar ELSE o40_codtri END),2,0)||lpad((CASE WHEN o41_codtri = '0'
                 OR NULL THEN o41_unidade::varchar ELSE o41_codtri END),3,0)||lpad(o41_subunidade::integer,3,0)
                 ELSE lpad((CASE WHEN o40_codtri = '0'
                 OR NULL THEN o40_orgao::varchar ELSE o40_codtri END),2,0)||lpad((CASE WHEN o41_codtri = '0'
                 OR NULL THEN o41_unidade::varchar ELSE o41_codtri END),3,0)
                 END AS codunidadesub
                 FROM db_departorg
                 JOIN public.infocomplementares ON si08_anousu = db01_anousu
                 AND si08_instit = " . db_getsession("DB_instit") . "
                 JOIN orcunidade ON db01_orgao=o41_orgao
                 AND db01_unidade=o41_unidade
                 AND db01_anousu = o41_anousu
                 AND o41_instit = " . db_getsession("DB_instit") . "
                 JOIN orcorgao on o40_orgao = o41_orgao and o40_anousu = o41_anousu and o40_instit = " . db_getsession("DB_instit") . "
                 WHERE db01_coddepto=si06_departamento and db01_anousu=" . db_getsession("DB_anousu") . " LIMIT 1) as codunidadesub
                from adesaoregprecos
                join cgm orgaogerenciador on si06_orgaogerenciador = orgaogerenciador.z01_numcgm
                join cgm responsavel on si06_cgm = responsavel.z01_numcgm
                INNER JOIN pcproc on si06_processocompra = pc80_codproc
                LEFT JOIN public.infocomplementaresinstit on adesaoregprecos.si06_instit = public.infocomplementaresinstit.si09_instit
                where si06_statusenviosicom = 4 AND si06_instit= " . db_getsession("DB_instit") . " and si06_sequencial in ($adesoes)");
        
    }

    public function getDadosRegistro11($adesao){

        return DB::select("select distinct si07_numerolote,(select si07_descricaolote from itensregpreco desclote
                   where desclote.si07_numerolote=itensregpreco.si07_numerolote and desclote.si07_sequencialadesao = itensregpreco.si07_sequencialadesao limit 1) as desclote
                   from itensregpreco where si07_numerolote > 0 and si07_numerolote is not null and si07_sequencialadesao = $adesao");

    }

    public function getDadosRegistro12($adesao){

        return DB::select("select si07_numeroitem,
      CASE
        WHEN (pcmater.pc01_codmaterant != 0 or pcmater.pc01_codmaterant != null) THEN pcmater.pc01_codmaterant::varchar
      ELSE pcmater.pc01_codmater::varchar END AS coditem
      from itensregpreco
      inner join pcmater on
                   		pcmater.pc01_codmater = si07_item
      where si07_sequencialadesao = $adesao ");

    }

    public function getDadosRegistro13($adesao){

        return DB::select("select si07_numerolote,
                   CASE
        WHEN (pcmater.pc01_codmaterant != 0 or pcmater.pc01_codmaterant != null) THEN pcmater.pc01_codmaterant::varchar
      ELSE pcmater.pc01_codmater::varchar END AS coditem
                   from itensregpreco
                   inner join pcmater on
                   		pcmater.pc01_codmater = si07_item
                   where si07_sequencialadesao = $adesao
                   and (select si06_processoporlote from adesaoregprecos where  si06_sequencial = $adesao) = 1");

    }

    public function getDadosRegistro14($dados){

        return DB::select("select * from (SELECT
                pc01_codmater,
                pc01_descrmater||'. '||pc01_complmater as pc01_descrmater,
                m61_abrev,
                sum(pc11_quant) as pc11_quant
                from (
                SELECT DISTINCT pc01_servico,
                                pc11_codigo,
                                pc11_seq,
                                pc11_quant,
                                pc11_prazo,
                                pc11_pgto,
                                pc11_resum,
                                pc11_just,
                                m61_abrev,
                                m61_descr,
                                pc17_quant,
                                pc01_codmater,
                                pc01_descrmater,pc01_complmater,
                                pc10_numero,
                                pc90_numeroprocesso AS processo_administrativo,
                                (pc11_quant * pc11_vlrun) AS pc11_valtot,
                                m61_usaquant
                FROM solicitem
                INNER JOIN solicita ON solicita.pc10_numero = solicitem.pc11_numero
                LEFT JOIN solicitaprotprocesso ON solicitaprotprocesso.pc90_solicita = solicita.pc10_numero
                LEFT JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                LEFT JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater
                LEFT JOIN pcprocitem ON pcprocitem.pc81_solicitem = solicitem.pc11_codigo
                LEFT JOIN solicitemunid ON solicitemunid.pc17_codigo = solicitem.pc11_codigo
                LEFT JOIN matunid ON matunid.m61_codmatunid = solicitemunid.pc17_unid
                LEFT JOIN solicitemele ON solicitemele.pc18_solicitem = solicitem.pc11_codigo
                LEFT JOIN orcelemento ON solicitemele.pc18_codele = orcelemento.o56_codele
                AND orcelemento.o56_anousu = " . db_getsession("DB_anousu") . "
                WHERE pc81_codproc = $dados->si06_processocompra
                  AND pc10_instit = " . db_getsession("DB_instit") . "
                ORDER BY pc11_seq) as x GROUP BY
                                pc01_codmater,
                                pc01_descrmater,pc01_complmater,m61_abrev ) as matquan join
                (SELECT DISTINCT
                                pc11_seq,
                                round(si02_vlprecoreferencia,2) as si02_vlprecoreferencia,
                                pc01_codmater,
                                si01_datacotacao,
                                si07_numerolote,
                                CASE
        WHEN (pcmater.pc01_codmaterant != 0 or pcmater.pc01_codmaterant != null) THEN pcmater.pc01_codmaterant::varchar
      ELSE pcmater.pc01_codmater::varchar END AS coditem
                FROM pcproc
                JOIN pcprocitem ON pc80_codproc = pc81_codproc
                JOIN pcorcamitemproc ON pc81_codprocitem = pc31_pcprocitem
                JOIN pcorcamitem ON pc31_orcamitem = pc22_orcamitem
                JOIN pcorcamval ON pc22_orcamitem = pc23_orcamitem
                JOIN solicitem ON pc81_solicitem = pc11_codigo
                JOIN solicitempcmater ON pc11_codigo = pc16_solicitem
                JOIN pcmater ON pc16_codmater = pc01_codmater
                JOIN itemprecoreferencia ON pc23_orcamitem = si02_itemproccompra
                JOIN precoreferencia ON itemprecoreferencia.si02_precoreferencia = precoreferencia.si01_sequencial
                JOIN itensregpreco ON si07_item = pc01_codmater AND si07_sequencialadesao = $dados->si06_sequencial
                WHERE pc80_codproc = $dados->si06_processocompra
                ORDER BY pc11_seq) as matpreco on matpreco.pc01_codmater = matquan.pc01_codmater order by pc11_seq");

    }

    public function getDadosRegistro20($adesao){

        return DB::select("select si07_numerolote,si07_precounitario,si07_quantidadelicitada,si07_quantidadeaderida,
                        case when length(z01_cgccpf) = 11 then 1 when length(z01_cgccpf) = 14 then 2 else 0 end as tipodocumento,
                        z01_cgccpf,
                        CASE
        WHEN (pcmater.pc01_codmaterant != 0 or pcmater.pc01_codmaterant != null) THEN pcmater.pc01_codmaterant::varchar
      ELSE pcmater.pc01_codmater::varchar END AS coditem
                        from itensregpreco
                        INNER JOIN adesaoregprecos on  si07_sequencialadesao = si06_sequencial
                        INNER join cgm on si07_fornecedor = z01_numcgm
                        inner join pcmater on
                                                pcmater.pc01_codmater = si07_item
                        where si07_sequencialadesao = {$adesao} and si06_criterioadjudicacao = 3");

    }

    public function getDadosRegistro30($adesao){
        return DB::select("select si07_numerolote,si07_precounitario,si07_quantidadelicitada,si07_quantidadeaderida,si07_percentual,
                case when length(z01_cgccpf) = 11 then 1 when length(z01_cgccpf) = 14 then 2 else 0 end as tipodocumento,
                z01_cgccpf,
                CASE
        WHEN (pcmater.pc01_codmaterant != 0 or pcmater.pc01_codmaterant != null) THEN pcmater.pc01_codmaterant::varchar
      ELSE pcmater.pc01_codmater::varchar END AS coditem
                from itensregpreco
                INNER JOIN adesaoregprecos on  si07_sequencialadesao = si06_sequencial
                INNER join cgm on si07_fornecedor = z01_numcgm
                inner join pcmater on
                                        pcmater.pc01_codmater = si07_item
                where si07_sequencialadesao = $adesao and si06_criterioadjudicacao = 1");
    }

    public function getDadosRegistro40($adesao){
        return DB::select("select si07_numerolote,si07_precounitario,si07_quantidadelicitada,si07_quantidadeaderida,si07_percentual,
                case when length(z01_cgccpf) = 11 then 1 when length(z01_cgccpf) = 14 then 2 else 0 end as tipodocumento,
                z01_cgccpf,
                CASE
        WHEN (pcmater.pc01_codmaterant != 0 or pcmater.pc01_codmaterant != null) THEN pcmater.pc01_codmaterant::varchar
      ELSE pcmater.pc01_codmater::varchar END AS coditem
                from itensregpreco
                INNER JOIN adesaoregprecos on  si07_sequencialadesao = si06_sequencial
                INNER join cgm on si07_fornecedor = z01_numcgm
                inner join pcmater on
                                        pcmater.pc01_codmater = si07_item
                where si07_sequencialadesao = {$adesao} and si06_criterioadjudicacao = 2");
    }

}
