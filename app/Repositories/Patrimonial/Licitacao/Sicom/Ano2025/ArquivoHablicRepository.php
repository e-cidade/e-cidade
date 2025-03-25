<?php

namespace App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025;

use Illuminate\Database\Capsule\Manager as DB;

class ArquivoHablicRepository
{

    public function getDadosRegistro10($licitacoes){
        return DB::select(
            "select distinct '10' as tipoRegistro,
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
	liclicita.l20_anousu AS exercicioLicitacao,
	liclicita.l20_edital as nroProcessoLicitatorio,
	(CASE length(cgm.z01_cgccpf) WHEN 11 THEN 1
		ELSE 2
	END) as tipoDocumento,
	cgm.z01_cgccpf as nroDocumento,
	pcforne.pc60_obs as  objetoSocial,
	pcforne.pc60_orgaoreg as orgaoRespRegistro,
	pcforne.pc60_dtreg as dataRegistro,
	pcforne.pc60_numeroregistro as nroRegistro,
	pcforne.pc60_dtreg_cvm as dataRegistroCVM,
	pcforne.pc60_numerocvm as nroRegistroCVM,
	pcforne.pc60_inscriestadual as nroInscricaoEstadual,
	pcforne.pc60_uf as ufInscricaoEstadual,
	habilitacaoforn.l206_numcertidaoinss as nroCertidaoRegularidadeINSS,
	habilitacaoforn.l206_dataemissaoinss as dtEmissaoCertidaoRegularidadeINSS,
	habilitacaoforn.l206_datavalidadeinss as dtValidadeCertidaoRegularidadeINSS,
	habilitacaoforn.l206_numcertidaofgts as nroCertidaoRegularidadeFGTS,
	habilitacaoforn.l206_dataemissaofgts as dtEmissaoCertidaoRegularidadeFGTS,
	habilitacaoforn.l206_datavalidadefgts as dtValidadeCertidaoRegularidadeFGTS,
	habilitacaoforn.l206_numcertidaocndt as nroCNDT,
	habilitacaoforn.l206_dataemissaocndt as dtEmissaoCNDT,
	habilitacaoforn.l206_datavalidadecndt as dtValidadeCNDT,
	habilitacaoforn.l206_datahab as dtHabilitacao,
    (SELECT distinct CASE
    WHEN pc31_regata IS NULL THEN 2
    ELSE pc31_regata
    END AS pc31_regata
    FROM pcorcamfornelic
    JOIN pcorcamforne ON pc31_orcamforne = pc21_orcamforne
    WHERE pc31_liclicita = liclicita.l20_codigo
    AND pc21_numcgm = pcforne.pc60_numcgm) AS PresencaLicitantes,
    (SELECT distinct CASE
    WHEN pc31_renunrecurso IS NULL THEN 2
    ELSE pc31_renunrecurso
    END AS pc31_renunrecurso
    FROM pcorcamfornelic
    JOIN pcorcamforne ON pc31_orcamforne = pc21_orcamforne
    WHERE pc31_liclicita = liclicita.l20_codigo
    AND pc21_numcgm = pcforne.pc60_numcgm) AS renunciaRecurso,
    l20_codigo AS codlicitacao,
    liclicita.l20_leidalicitacao AS leidalicitacao,
    manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant,
    infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual
	FROM liclicita as liclicita
	INNER JOIN homologacaoadjudica on (liclicita.l20_codigo=homologacaoadjudica.l202_licitacao)
	INNER JOIN habilitacaoforn as habilitacaoforn on (liclicita.l20_codigo=habilitacaoforn.l206_licitacao)
	INNER JOIN pcforne on (habilitacaoforn.l206_fornecedor=pcforne.pc60_numcgm)
	INNER JOIN cgm on (pcforne.pc60_numcgm=cgm.z01_numcgm)
	INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
	LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
	INNER JOIN cflicita ON (cflicita.l03_codigo = liclicita.l20_codtipocom)
	INNER JOIN pctipocompratribunal ON (cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial)
    LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
	WHERE (l20_statusenviosicom = 3 or (l20_statusenviosicom = 1 and l20_licsituacao in (10))) AND db_config.codigo=" . db_getsession("DB_instit") . " and l03_pctipocompratribunal in (48,49,50,51,52,53,54,110) AND l20_codigo in ($licitacoes)"
        );
    }

    public function getDadosRegistro11($codlicitacao,$nrodocumento){
        return DB::select(
            "select distinct '11' as tipoRegistro,
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
	'2' as tipoDocumentoCNPJEmpresaHablic,
	cgm.z01_cgccpf as CNPJEmpresaHablic,
	(CASE length(cgmrep.z01_cgccpf) WHEN 11 THEN 1
		ELSE 2
	END) as tipoDocumentoSocio,
	cgmrep.z01_cgccpf as nroDocumentoSocio,
	pcfornereprlegal.pc81_tipopart as tipoParticipacao,
    manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant,
    infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual
	FROM licitacao.liclicita as liclicita
	INNER JOIN homologacaoadjudica on (liclicita.l20_codigo=homologacaoadjudica.l202_licitacao)
	INNER JOIN habilitacaoforn on (liclicita.l20_codigo=habilitacaoforn.l206_licitacao)
	INNER JOIN pcforne on (habilitacaoforn.l206_fornecedor=pcforne.pc60_numcgm)
	INNER JOIN cgm on (pcforne.pc60_numcgm=cgm.z01_numcgm)
	INNER JOIN pcfornereprlegal on (pcforne.pc60_numcgm=pcfornereprlegal.pc81_cgmforn)
	INNER JOIN protocolo.cgm as cgmrep on (pcfornereprlegal.pc81_cgmresp=cgmrep.z01_numcgm)
	INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
	LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
    LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
	WHERE db_config.codigo= " . db_getsession("DB_instit") . "
	AND length(cgm.z01_cgccpf)>11 AND cgm.z01_cgccpf = '{$nrodocumento}'
	AND liclicita.l20_codigo=  {$codlicitacao}"
        );
    }

    public function getDadosRegistro20($codlicitacao){
        return DB::select(
            "select '20' as tipoRegistro,
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
		l205_datacred as DataCredenciamento,
    	l04_numerolote AS nroLote,
		(pcmater.pc01_codmater::varchar || (CASE WHEN m61_codmatunid IS NULL THEN 1 ELSE m61_codmatunid END)::varchar) as codItem,
		pcforne.pc60_inscriestadual as nroInscricaoEstadual,
		pcforne.pc60_uf as ufInscricaoEstadual,
		habilitacaoforn.l206_numcertidaoinss as nroCertidaoRegularidadeINSS,
		habilitacaoforn.l206_dataemissaoinss as dataEmissaoCertidaoRegularidadeINSS,
		habilitacaoforn.l206_datavalidadeinss as dataValidadeCertidaoRegularidadeINSS,
		habilitacaoforn.l206_numcertidaofgts as nroCertidaoRegularidadeFGTS,
		habilitacaoforn.l206_dataemissaofgts as dataEmissaoCertidaoRegularidadeFGTS,
		habilitacaoforn.l206_datavalidadefgts as dataValidadeCertidaoRegularidadeFGTS,
		habilitacaoforn.l206_numcertidaocndt as nroCNDT,
		habilitacaoforn.l206_dataemissaocndt as dtEmissaoCNDT,
		habilitacaoforn.l206_datavalidadecndt as dtValidadeCNDT,
        manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant,
        infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual
		FROM liclicita as liclicita
		INNER JOIN homologacaoadjudica on (liclicita.l20_codigo=homologacaoadjudica.l202_licitacao)
		INNER JOIN habilitacaoforn on (liclicita.l20_codigo=habilitacaoforn.l206_licitacao)
		INNER JOIN pcforne on (habilitacaoforn.l206_fornecedor=pcforne.pc60_numcgm)
		INNER JOIN cgm on (pcforne.pc60_numcgm=cgm.z01_numcgm)
		INNER JOIN credenciamento on (liclicita.l20_codigo=credenciamento.l205_licitacao)
		INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
		INNER JOIN liclicitem on (liclicita.l20_codigo=liclicitem.l21_codliclicita and  liclicitem.l21_codigo=credenciamento.l205_item)
		INNER JOIN pcprocitem  on (liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem)
		INNER JOIN solicitem on (pcprocitem.pc81_solicitem = solicitem.pc11_codigo)
		INNER JOIN solicitempcmater on (solicitem.pc11_codigo = solicitempcmater.pc16_solicitem)
		INNER JOIN pcmater on (solicitempcmater.pc16_codmater = pcmater.pc01_codmater)
		INNER JOIN liclicitemlote on (liclicitem.l21_codigo=liclicitemlote.l04_liclicitem)
		LEFT JOIN solicitemunid AS solicitemunid ON solicitem.pc11_codigo = solicitemunid.pc17_codigo
        LEFT JOIN matunid AS matunid ON solicitemunid.pc17_unid = matunid.m61_codmatunid
		LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
        LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
		WHERE l20_statusenviosicom = 3 AND db_config.codigo=" . db_getsession("DB_instit") . "
		AND liclicita.l20_codigo= {$codlicitacao}"
        );
    }
}
