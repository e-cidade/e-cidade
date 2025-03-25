<?php

namespace App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025;

use Illuminate\Database\Capsule\Manager as DB;

class ArquivoHomolicRepository
{
    public function getDadosRegistro10($licitacoes)
    {
        return DB::select(
            "SELECT   distinct '10' as tipoRegistro,
    	infocomplementaresinstit.si09_codorgaotce as codOrgaoResp,
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
       JOIN orcorgao on o40_orgao = o41_orgao and o40_anousu = o41_anousu
       WHERE db01_coddepto=l20_codepartamento and db01_anousu=" . db_getsession("DB_anousu") . " LIMIT 1) as codUnidadeSubResp,
    	liclicita.l20_anousu as exercicioLicitacao,
    	liclicita.l20_edital as nroProcessoLicitatorio,
    	(CASE length(cgm.z01_cgccpf) WHEN 11 THEN 1
    		ELSE 2
    	END) as tipoDocumento,
    	cgm.z01_cgccpf as nroDocumento,
    	l04_numerolote AS nroLote,
		CASE
        WHEN (pcmater.pc01_codmaterant != 0 or pcmater.pc01_codmaterant != null) THEN pcmater.pc01_codmaterant::varchar
      ELSE pcmater.pc01_codmater::varchar END AS coditem,
    	pcorcamval.pc23_vlrun as vlUnitario,
    	solicitem.pc11_quant as quantidade,
    	liclicita.l20_codigo as codlicitacao,
       CASE
               WHEN liclicita.l20_criterioadjudicacao is null THEN 3
               WHEN liclicita.l20_criterioadjudicacao = 0 THEN 3
               ELSE liclicita.l20_criterioadjudicacao
           END AS criterioAdjudicacao,
		manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant,
        infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual
    	FROM liclicita as liclicita
    	INNER JOIN homologacaoadjudica on (liclicita.l20_codigo=homologacaoadjudica.l202_licitacao)
    	INNER JOIN liclicitem on (liclicita.l20_codigo=liclicitem.l21_codliclicita)
    	INNER JOIN pcorcamitemlic ON (liclicitem.l21_codigo = pcorcamitemlic.pc26_liclicitem )
    	INNER JOIN pcorcamitem ON (pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem)
    	INNER JOIN pcorcamjulg ON (pcorcamitem.pc22_orcamitem = pcorcamjulg.pc24_orcamitem )
    	INNER JOIN pcorcamforne ON (pcorcamjulg.pc24_orcamforne = pcorcamforne.pc21_orcamforne)
    	INNER JOIN cgm ON (pcorcamforne.pc21_numcgm = cgm.z01_numcgm)
    	INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
    	INNER JOIN pcprocitem  ON (liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem)
    	INNER JOIN solicitem ON (pcprocitem.pc81_solicitem = solicitem.pc11_codigo)
    	INNER JOIN solicitempcmater ON (solicitem.pc11_codigo=solicitempcmater.pc16_solicitem)
		INNER JOIN pcmater on (solicitempcmater.pc16_codmater = pcmater.pc01_codmater)
    	INNER JOIN pcorcamval ON (pcorcamitem.pc22_orcamitem = pcorcamval.pc23_orcamitem and pcorcamforne.pc21_orcamforne=pcorcamval.pc23_orcamforne)
    	LEFT JOIN solicitemunid AS solicitemunid ON solicitem.pc11_codigo = solicitemunid.pc17_codigo
        LEFT JOIN matunid AS matunid ON solicitemunid.pc17_unid = matunid.m61_codmatunid
        INNER JOIN cflicita ON (cflicita.l03_codigo = liclicita.l20_codtipocom)
    	INNER JOIN pctipocompratribunal ON (cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial)
    	LEFT JOIN liclicitemlote on (liclicitem.l21_codigo=liclicitemlote.l04_liclicitem)
    	LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
    	INNER JOIN itenshomologacao ON itenshomologacao.l203_homologaadjudicacao = homologacaoadjudica.l202_sequencial and l203_item = pc81_codprocitem
    	LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
 WHERE pc24_pontuacao = 1 and (l20_statusenviosicom = 3 or (l20_statusenviosicom = 1 and l20_licsituacao in (10))) AND db_config.codigo=" . db_getsession("DB_instit") . " and l03_pctipocompratribunal in (48,49,50,51,52,53,54,110) AND l20_codigo in ($licitacoes) order by liclicita.l20_edital"

        );
    }

    public function getDadosRegistro20($aLicitacoes){
        return DB::select("SELECT '20' as tipoRegistro,
    infocomplementaresinstit.si09_codorgaotce as codOrgaoResp,
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
     JOIN orcorgao on o40_orgao = o41_orgao and o40_anousu = o41_anousu
     WHERE db01_coddepto=l20_codepartamento and db01_anousu=" . db_getsession("DB_anousu") . " LIMIT 1) as codUnidadeSubResp,
    liclicita.l20_anousu as exercicioLicitacao,
    liclicita.l20_edital as nroProcessoLicitatorio,
    (CASE length(cgm.z01_cgccpf) WHEN 11 THEN 1
      ELSE 2
    END) as tipoDocumento,
    cgm.z01_cgccpf as nroDocumento,
    l04_numerolote AS nroLote,
    pcorcamval.pc23_vlrun AS vlUnitario,
    solicitem.pc11_quant AS quantidade,
    liclicita.l20_codigo AS codlicitacao,
	CASE
        WHEN (pcmater.pc01_codmaterant != 0 or pcmater.pc01_codmaterant != null) THEN pcmater.pc01_codmaterant::varchar
      ELSE pcmater.pc01_codmater::varchar END AS coditem,
    pcorcamval.pc23_perctaxadesctabela as percDesconto,
    CASE
               WHEN liclicita.l20_criterioadjudicacao is null THEN 3
               WHEN liclicita.l20_criterioadjudicacao = 0 THEN 3
               ELSE liclicita.l20_criterioadjudicacao
           END AS criterioAdjudicacao,
	manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant,
    infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual
    FROM liclicita
    INNER JOIN homologacaoadjudica on (liclicita.l20_codigo=homologacaoadjudica.l202_licitacao)
    INNER JOIN liclicitem on (liclicita.l20_codigo=liclicitem.l21_codliclicita)
    INNER JOIN pcorcamitemlic ON (liclicitem.l21_codigo = pcorcamitemlic.pc26_liclicitem )
    INNER JOIN pcorcamitem ON (pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem)
    INNER JOIN pcorcamjulg ON (pcorcamitem.pc22_orcamitem = pcorcamjulg.pc24_orcamitem )
    INNER JOIN pcorcamforne ON (pcorcamjulg.pc24_orcamforne = pcorcamforne.pc21_orcamforne)
    INNER JOIN pcorcamval ON (pcorcamjulg.pc24_orcamitem = pcorcamval.pc23_orcamitem and pcorcamjulg.pc24_orcamforne=pcorcamval.pc23_orcamforne)
    INNER JOIN cgm ON (pcorcamforne.pc21_numcgm = cgm.z01_numcgm)
    INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
    INNER JOIN pcprocitem  ON (liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem)
    INNER JOIN solicitem ON (pcprocitem.pc81_solicitem = solicitem.pc11_codigo)
    INNER JOIN solicitempcmater ON (solicitem.pc11_codigo=solicitempcmater.pc16_solicitem)
	INNER JOIN pcmater on (solicitempcmater.pc16_codmater = pcmater.pc01_codmater)
    LEFT JOIN descontotabela on (liclicita.l20_codigo=descontotabela.l204_licitacao
      AND pcorcamforne.pc21_orcamforne=descontotabela.l204_fornecedor
      AND descontotabela.l204_item=solicitempcmater.pc16_codmater)
    LEFT JOIN solicitemunid AS solicitemunid ON solicitem.pc11_codigo = solicitemunid.pc17_codigo
    LEFT JOIN matunid AS matunid ON solicitemunid.pc17_unid = matunid.m61_codmatunid
    LEFT JOIN liclicitemlote on (liclicitem.l21_codigo=liclicitemlote.l04_liclicitem)
    LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
    INNER JOIN itenshomologacao ON itenshomologacao.l203_homologaadjudicacao = homologacaoadjudica.l202_sequencial and l203_item = pc81_codprocitem
    LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
	WHERE db_config.codigo=" . db_getsession("DB_instit") . " AND pcorcamjulg.pc24_pontuacao = 1
    and liclicita.l20_codigo in (" . implode(',', $aLicitacoes) . ") order by liclicita.l20_edital");
    }

    public function getDadosRegistro30($aLicitacoes){
        return DB::select("SELECT   '30' as tipoRegistro,
          infocomplementaresinstit.si09_codorgaotce as codOrgaoResp,
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
           JOIN orcorgao on o40_orgao = o41_orgao and o40_anousu = o41_anousu
           WHERE db01_coddepto=l20_codepartamento and db01_anousu=" . db_getsession("DB_anousu") . " LIMIT 1) as codUnidadeSubResp,
          liclicita.l20_anousu as exercicioLicitacao,
          liclicita.l20_edital as nroProcessoLicitatorio,
          (CASE length(cgm.z01_cgccpf) WHEN 11 THEN 1
            ELSE 2
          END) as tipoDocumento,
          cgm.z01_cgccpf as nroDocumento,
      l04_numerolote AS nroLote,
		  CASE
        WHEN (pcmater.pc01_codmaterant != 0 or pcmater.pc01_codmaterant != null) THEN pcmater.pc01_codmaterant::varchar
      ELSE pcmater.pc01_codmater::varchar END AS coditem,
          pcorcamval.pc23_percentualdesconto as perctaxaadm,
          CASE
               WHEN liclicita.l20_criterioadjudicacao is null THEN 3
               WHEN liclicita.l20_criterioadjudicacao = 0 THEN 3
               ELSE liclicita.l20_criterioadjudicacao
           END AS criterioAdjudicacao,
		   manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant,
        infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual
          FROM liclicita
          INNER JOIN homologacaoadjudica on (liclicita.l20_codigo=homologacaoadjudica.l202_licitacao)
          INNER JOIN liclicitem on (liclicita.l20_codigo=liclicitem.l21_codliclicita)
          INNER JOIN pcorcamitemlic ON (liclicitem.l21_codigo = pcorcamitemlic.pc26_liclicitem )
          INNER JOIN pcorcamitem ON (pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem)
          INNER JOIN pcorcamjulg ON (pcorcamitem.pc22_orcamitem = pcorcamjulg.pc24_orcamitem )
          INNER JOIN pcorcamforne ON (pcorcamjulg.pc24_orcamforne = pcorcamforne.pc21_orcamforne)
          INNER JOIN pcorcamval ON (pcorcamjulg.pc24_orcamitem = pcorcamval.pc23_orcamitem and pcorcamjulg.pc24_orcamforne=pcorcamval.pc23_orcamforne)
          INNER JOIN cgm ON (pcorcamforne.pc21_numcgm = cgm.z01_numcgm)
          INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
          INNER JOIN pcprocitem  ON (liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem)
          INNER JOIN solicitem ON (pcprocitem.pc81_solicitem = solicitem.pc11_codigo)
          INNER JOIN solicitempcmater ON (solicitem.pc11_codigo=solicitempcmater.pc16_solicitem)
		  INNER JOIN pcmater on (solicitempcmater.pc16_codmater = pcmater.pc01_codmater)
          LEFT JOIN descontotabela on (liclicita.l20_codigo=descontotabela.l204_licitacao
            and pcorcamforne.pc21_orcamforne=descontotabela.l204_fornecedor
            and descontotabela.l204_item=solicitempcmater.pc16_codmater)
          LEFT JOIN solicitemunid AS solicitemunid ON solicitem.pc11_codigo = solicitemunid.pc17_codigo
          LEFT JOIN matunid AS matunid ON solicitemunid.pc17_unid = matunid.m61_codmatunid
          LEFT JOIN liclicitemlote on (liclicitem.l21_codigo=liclicitemlote.l04_liclicitem)
          LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
		  LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
		  WHERE db_config.codigo=" . db_getsession("DB_instit") . " AND pcorcamjulg.pc24_pontuacao = 1
          AND liclicita.l20_codigo in (" . implode(",", $aLicitacoes) . ")  order by liclicita.l20_edital ");
    }

    public function getDadosRegistro50($aLicitacoes){
        return DB::select("SELECT   '50' as tipoRegistro,
	infocomplementaresinstit.si09_codorgaotce as codOrgaoResp,
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
   JOIN orcorgao on o40_orgao = o41_orgao and o40_anousu = o41_anousu
   WHERE db01_coddepto=l20_codepartamento and db01_anousu=" . db_getsession("DB_anousu") . " LIMIT 1) as codUnidadeSubResp,
	liclicita.l20_anousu as exercicioLicitacao,
	liclicita.l20_edital as nroProcessoLicitatorio,
	homologacaoadjudica.l202_datahomologacao as dtHomologacao,
	(case when liclicita.l20_tipnaturezaproced = 2 then null else homologacaoadjudica.l202_dataadjudicacao end) as dtAdjudicacao,
	manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant,
    infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual
	FROM liclicita
	INNER JOIN homologacaoadjudica on (liclicita.l20_codigo=homologacaoadjudica.l202_licitacao)
	INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
	LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
	LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
	WHERE db_config.codigo= " . db_getsession("DB_instit") . "
	AND liclicita.l20_codigo in (" . implode(",", $aLicitacoes) . ")
	and l202_datahomologacao is not null");
    }

    public function getDadosRegistro60($aLicitacoes){
        return DB::select("SELECT   '50' as tipoRegistro,
        infocomplementaresinstit.si09_codorgaotce as codOrgaoResp,
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
       JOIN orcorgao on o40_orgao = o41_orgao and o40_anousu = o41_anousu
       WHERE db01_coddepto=l20_codepartamento and db01_anousu=" . db_getsession("DB_anousu") . " LIMIT 1) as codUnidadeSubResp,
        liclicita.l20_anousu as exercicioLicitacao,
        liclicita.l20_edital as nroProcessoLicitatorio,
        homologacaoadjudica.l202_datahomologacao as dtHomologacao,
        (case when liclicita.l20_tipnaturezaproced = 2 then null else homologacaoadjudica.l202_dataadjudicacao end) as dtAdjudicacao,
        manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant,
        infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual,
        l31_codigo as codresponsavel,
            CASE 
            WHEN l31_tipo = '6' THEN 1
            WHEN l31_tipo = '7' THEN 2
            ELSE NULL -- ou outro valor padrão caso l31_tipo não seja 6 ou 7
        END AS tipoResp,
	    z01_cgccpf as nroCPFResp
        FROM liclicita
        INNER JOIN homologacaoadjudica on (liclicita.l20_codigo=homologacaoadjudica.l202_licitacao)
        INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
        LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
        LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
        left join liccomissaocgm on l31_licitacao = l20_codigo and l31_tipo in ('6','7')
	    left join cgm on l31_numcgm = z01_numcgm
        WHERE db_config.codigo= " . db_getsession("DB_instit") . "
        AND liclicita.l20_codigo in (" . implode(",", $aLicitacoes) . ")
        and l202_datahomologacao is not null");
    }
}
