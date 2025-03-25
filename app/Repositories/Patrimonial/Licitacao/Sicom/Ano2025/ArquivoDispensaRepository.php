<?php

namespace App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025;

use Illuminate\Database\Capsule\Manager as DB;

class ArquivoDispensaRepository
{
    public function getDadosRegistro10($licitacoes)
    {

       return DB::select(
            "SELECT DISTINCT l20_codepartamento, '10' as tipoRegistro,
  l20_leidalicitacao,
    l20_tipnaturezaproced,
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
	liclicita.l20_tipoprocesso as tipoProcesso,
	case when liclicita.l20_dataaber is null then liclicita.l20_datacria else liclicita.l20_dataaber end as dtAbertura,
	liclicita.l20_naturezaobjeto as naturezaObjeto,
	liclicita.l20_objeto as objeto,
	liclicita.l20_justificativa as justificativa,
	liclicita.l20_razao as razao,
	liclicita.l20_dtpubratificacao as dtPublicacaoTermoRatificacao,
	l20_codigo as codlicitacao,
	liclicita.l20_veicdivulgacao as veiculoPublicacao,
	 (CASE
        WHEN liclicita.l20_cadInicial is null or liclicita.l20_cadInicial = 0 and liclicita.l20_anousu >= 2024 THEN 1
     	ELSE liclicita.l20_cadInicial
     END) as cadInicial,
	(CASE liclicita.l20_tipojulg WHEN 3 THEN 1
		ELSE 2
	END) as processoPorLote,
  manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant,
  (SELECT CASE
  WHEN o41_subunidade != 0
       OR NOT NULL THEN lpad((CASE
                                  WHEN o40_codtri = '0'
                                       OR NULL THEN o40_orgao::varchar
                                  ELSE o40_codtri
                              END),2,0)||lpad((CASE
                                                   WHEN o41_codtri = '0'
                                                        OR NULL THEN o41_unidade::varchar
                                                   ELSE o41_codtri
                                               END),3,0)||lpad(o41_subunidade::integer,3,0)
  ELSE lpad((CASE
                 WHEN o40_codtri = '0'
                      OR NULL THEN o40_orgao::varchar
                 ELSE o40_codtri
             END),2,0)||lpad((CASE
                                  WHEN o41_codtri = '0'
                                       OR NULL THEN o41_unidade::varchar
                                  ELSE o41_codtri
                              END),3,0)
END AS codunidadesubresp
FROM db_departorg
JOIN public.infocomplementares ON si08_anousu = db01_anousu
AND si08_instit = " . db_getsession('DB_instit') . "
JOIN orcunidade ON db01_orgao=o41_orgao
AND db01_unidade=o41_unidade
AND db01_anousu = o41_anousu
JOIN orcorgao ON o40_orgao = o41_orgao
AND o40_anousu = o41_anousu
WHERE db01_coddepto=l20_codepartamento
AND db01_anousu = " . db_getsession('DB_anousu') . "
LIMIT 1) AS codUnidadeSubEdital,
l20_criterioadjudicacao,
liclancedital.l47_linkpub AS linkpub,
liclancedital.l47_email AS emailContato,
infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual,
(SELECT SUM(si02_vlprecoreferencia * pc11_quant)
                                 FROM
                                     (SELECT DISTINCT pc11_seq,
                                                      (sum(pc23_vlrun)/count(pc23_orcamforne)) AS si02_vlprecoreferencia,
                                                      CASE
                                                          WHEN pc80_criterioadjudicacao = 1 THEN round((sum(pc23_perctaxadesctabela)/count(pc23_orcamforne)),2)
                                                          WHEN pc80_criterioadjudicacao = 2 THEN round((sum(pc23_percentualdesconto)/count(pc23_orcamforne)),2)
                                                      END AS mediapercentual,
                                                      pc11_quant,
                                                      pc01_codmater
                                      FROM pcproc
                                      JOIN pcprocitem ON pc80_codproc = pc81_codproc
                                      JOIN pcorcamitemproc ON pc81_codprocitem = pc31_pcprocitem
                                      JOIN pcorcamitem ON pc31_orcamitem = pc22_orcamitem
                                      JOIN pcorcamval ON pc22_orcamitem = pc23_orcamitem
                                      JOIN pcorcamforne ON pc21_orcamforne = pc23_orcamforne
                                      JOIN solicitem ON pc81_solicitem = pc11_codigo
                                      JOIN solicitempcmater ON pc11_codigo = pc16_solicitem
                                      JOIN pcmater ON pc16_codmater = pc01_codmater
                                      JOIN itemprecoreferencia ON pc23_orcamitem = si02_itemproccompra
                                      JOIN precoreferencia ON itemprecoreferencia.si02_precoreferencia = precoreferencia.si01_sequencial
                                      WHERE pc80_codproc IN
                                              (SELECT DISTINCT pc80_codproc
                                               FROM liclicitem
                                               INNER JOIN pcprocitem ON pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem
                                               INNER JOIN liclicita lic2 ON lic2.l20_codigo = liclicitem.l21_codliclicita
                                               INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                                               INNER JOIN pcproc ON pcproc.pc80_codproc = pcprocitem.pc81_codproc
                                               INNER JOIN db_usuarios ON db_usuarios.id_usuario = lic2.l20_id_usucria
                                               INNER JOIN cflicita ON cflicita.l03_codigo = lic2.l20_codtipocom
                                               INNER JOIN liclocal ON liclocal.l26_codigo = lic2.l20_liclocal
                                               INNER JOIN liccomissao ON liccomissao.l30_codigo = lic2.l20_liccomissao
                                               LEFT JOIN solicitatipo ON solicitatipo.pc12_numero = solicitem.pc11_numero
                                               LEFT JOIN pctipocompra ON pctipocompra.pc50_codcom = solicitatipo.pc12_tipo
                                               WHERE lic2.l20_codigo = liclicita.l20_codigo)
                                          AND pc23_valor <> 0
                                          AND pc23_vlrun <> 0
                                      GROUP BY pc11_seq,
                                               pc01_codmater,
                                               pc80_criterioadjudicacao,
                                               pc01_tabela,
                                               pc11_quant
                                      ORDER BY pc11_seq) AS valor_global) AS vlRecurso
	FROM liclicita
	INNER JOIN cflicita on (liclicita.l20_codtipocom = cflicita.l03_codigo)
	INNER JOIN pctipocompratribunal on (cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial)
	INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
	LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
	INNER JOIN liclicitasituacao ON liclicitasituacao.l11_liclicita = liclicita.l20_codigo and liclicitasituacao.l11_licsituacao in (1,10)
  INNER JOIN liclancedital ON liclancedital.l47_liclicita = liclicita.l20_codigo
	LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
  WHERE l20_statusenviosicom = 4 AND db_config.codigo= " . db_getsession("DB_instit") . "  and l03_pctipocompratribunal in (100, 101, 102, 103) AND l20_codigo in ($licitacoes)");
    }

    public function getDadosRegistro11($codigoLicitacao){
      return DB::select("SELECT DISTINCT  '11' as tipoRegistro,
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
		liclicita.l20_tipoprocesso as tipoProcesso,
		liclicitemlote.l04_numerolote as nroLote,
		liclicitemlote.l04_descricao as dscLote,
    manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant,
    infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual
		FROM liclicita
		INNER JOIN liclicitem on (liclicita.l20_codigo=liclicitem.l21_codliclicita)
		INNER JOIN cflicita on (liclicita.l20_codtipocom = cflicita.l03_codigo)
		INNER JOIN pctipocompratribunal on (cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial)
		INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
		INNER JOIN liclicitemlote on (liclicitem.l21_codigo=liclicitemlote.l04_liclicitem)
		LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
		INNER JOIN liclicitasituacao ON liclicitasituacao.l11_liclicita = liclicita.l20_codigo
    LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
		WHERE db_config.codigo= " . db_getsession("DB_instit") . " AND (liclicita.l20_licsituacao = 1 OR liclicita.l20_licsituacao = 10)
		AND liclicita.l20_tipojulg = 3
    AND liclicitemlote.l04_numerolote IS NOT NULL
    and l03_pctipocompratribunal in (100, 101, 102, 103)
		AND liclicita.l20_codigo=$codigoLicitacao");
    }

    public function getDadosRegistro12($codigoLicitacao){
        return DB::select("select DISTINCT '12' as tipoRegistro,
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
		liclicita.l20_tipoprocesso as tipoProcesso,
        infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual,
    CASE
        WHEN (pcmater.pc01_codmaterant != 0 or pcmater.pc01_codmaterant != null) THEN pcmater.pc01_codmaterant::varchar
      ELSE pcmater.pc01_codmater::varchar END AS coditem,
		(pcmater.pc01_codmater::varchar || (case when matunid.m61_codmatunid is null then 1 else matunid.m61_codmatunid end)::varchar) as nroItem,
		manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant
    FROM liclicitem
		INNER JOIN liclicita on (liclicitem.l21_codliclicita=liclicita.l20_codigo)
		INNER JOIN cflicita on (liclicita.l20_codtipocom = cflicita.l03_codigo)
		INNER JOIN pctipocompratribunal on (cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial)
		INNER JOIN pcprocitem  on (liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem)
		INNER JOIN solicitem on (pcprocitem.pc81_solicitem = solicitem.pc11_codigo)
		INNER JOIN solicitempcmater on (solicitem.pc11_codigo = solicitempcmater.pc16_solicitem)
		INNER JOIN pcmater on (solicitempcmater.pc16_codmater = pcmater.pc01_codmater)
		INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
		LEFT JOIN solicitemunid AS solicitemunid ON solicitem.pc11_codigo = solicitemunid.pc17_codigo
    LEFT JOIN matunid AS matunid ON solicitemunid.pc17_unid = matunid.m61_codmatunid
		LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
		INNER JOIN liclicitasituacao ON liclicitasituacao.l11_liclicita = liclicita.l20_codigo
		LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
    WHERE db_config.codigo= " . db_getsession("DB_instit") . " AND (liclicita.l20_licsituacao = 1 OR liclicita.l20_licsituacao = 10) and l03_pctipocompratribunal in (100, 101, 102, 103)
		AND liclicita.l20_codigo= $codigoLicitacao");
    }

    public function getDadosRegistro13($codigoLicitacao){
        return DB::select(" select DISTINCT '13' as tipoRegistro,
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
		liclicita.l20_tipoprocesso as tipoProcesso,
		liclicitemlote.l04_numerolote as nroLote,
        infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual,
    CASE
        WHEN (pcmater.pc01_codmaterant != 0 or pcmater.pc01_codmaterant != null) THEN pcmater.pc01_codmaterant::varchar
      ELSE pcmater.pc01_codmater::varchar END AS coditem,
		manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant
    FROM liclicitem
		INNER JOIN liclicitemlote on (liclicitem.l21_codigo=liclicitemlote.l04_liclicitem)
		INNER JOIN liclicita on (liclicitem.l21_codliclicita=liclicita.l20_codigo)
		INNER JOIN cflicita on (liclicita.l20_codtipocom = cflicita.l03_codigo)
		INNER JOIN pctipocompratribunal on (cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial)
		INNER JOIN pcprocitem  on (liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem)
		INNER JOIN solicitem on (pcprocitem.pc81_solicitem = solicitem.pc11_codigo)
		INNER JOIN solicitempcmater on (solicitem.pc11_codigo = solicitempcmater.pc16_solicitem)
		INNER JOIN pcmater on (solicitempcmater.pc16_codmater = pcmater.pc01_codmater)
		INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
		LEFT JOIN solicitemunid AS solicitemunid ON solicitem.pc11_codigo = solicitemunid.pc17_codigo
    LEFT JOIN matunid AS matunid ON solicitemunid.pc17_unid = matunid.m61_codmatunid
		LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
		INNER JOIN liclicitasituacao ON liclicitasituacao.l11_liclicita = liclicita.l20_codigo
		LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
    WHERE db_config.codigo= " . db_getsession("DB_instit") . " AND (liclicita.l20_licsituacao = 1 OR liclicita.l20_licsituacao = 10)
		AND liclicita.l20_tipojulg = 3 and l03_pctipocompratribunal in (100, 101, 102, 103)
		AND liclicita.l20_codigo= $codigoLicitacao");
    }

    public function getDadosRegistro14($codigoLicitacao){


      $sSql = "select DISTINCT '14' as tipoRegistro,
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
      liclicita.l20_tipoprocesso as tipoProcesso,
          infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual,
      (CASE parecerlicitacao.l200_tipoparecer WHEN 2 THEN 6
        ELSE 7
      END) as tipoResp,
      cgm.z01_cgccpf as nroCPFResp,
      manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant
      FROM liclicita
      INNER JOIN parecerlicitacao on (liclicita.l20_codigo=parecerlicitacao.l200_licitacao)
      INNER JOIN cflicita on (liclicita.l20_codtipocom = cflicita.l03_codigo)
      INNER JOIN pctipocompratribunal on (cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial)
      INNER JOIN cgm on (parecerlicitacao.l200_numcgm=cgm.z01_numcgm)
      INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
      LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
      INNER JOIN liclicitasituacao ON liclicitasituacao.l11_liclicita = liclicita.l20_codigo
      LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
      WHERE db_config.codigo= " . db_getsession("DB_instit") . " AND liclicitasituacao.l11_licsituacao = 1 and l03_pctipocompratribunal in (100, 101, 102, 103)
      AND liclicita.l20_codigo= $codigoLicitacao";
  
        $sSql .= " union select DISTINCT '14' as tipoRegistro,
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
      liclicita.l20_tipoprocesso as tipoProcesso,
        infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual,
      (CASE liccomissaocgm.l31_tipo WHEN '1' THEN 1
      WHEN '2' THEN 4 WHEN '3' THEN 2 WHEN '4' THEN 3 WHEN '8' THEN 5 END) as tipoResp,
      cgm.z01_cgccpf as nroCPFResp,
      manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant
      FROM liclicita
      INNER JOIN cflicita on (liclicita.l20_codtipocom = cflicita.l03_codigo)
      INNER JOIN pctipocompratribunal on (cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial)
      INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
      INNER JOIN liccomissaocgm AS liccomissaocgm ON (liclicita.l20_codigo=liccomissaocgm.l31_licitacao)
      INNER JOIN cgm on (liccomissaocgm.l31_numcgm=cgm.z01_numcgm)
      LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
      INNER JOIN liclicitasituacao ON liclicitasituacao.l11_liclicita = liclicita.l20_codigo
      LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
      WHERE db_config.codigo= " . db_getsession("DB_instit") . " AND (liclicita.l20_licsituacao = 1 OR liclicita.l20_licsituacao = 10) and l03_pctipocompratribunal in (100, 101, 102, 103)
      AND liclicita.l20_codigo= $codigoLicitacao AND liccomissaocgm.l31_tipo in('1','2','3','4','8')";

        return DB::select($sSql);
    }

    public function getDadosRegistro15($codigoLicitacao){
      return DB::select("select DISTINCT '15' as tipoRegistro,
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
		liclicita.l20_tipoprocesso as tipoProcesso,
    infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual,
		liclicitemlote.l04_numerolote as nroLote,
    CASE
        WHEN (pcmater.pc01_codmaterant != 0 or pcmater.pc01_codmaterant != null) THEN pcmater.pc01_codmaterant::varchar
      ELSE pcmater.pc01_codmater::varchar END AS coditem,
		itemprecoreferencia.si02_vlprecoreferencia as vlCotPrecosUnitario,
		pcorcamval.pc23_quant as quantidade,
    manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant
		FROM liclicitem
		INNER JOIN liclicita on (liclicitem.l21_codliclicita=liclicita.l20_codigo)
		LEFT  JOIN liclicitemlote on (liclicitem.l21_codigo=liclicitemlote.l04_liclicitem AND liclicita.l20_tipojulg = 3)
		LEFT  JOIN dispensa112024 on (liclicitemlote.l04_descricao = dispensa112024.si75_dsclote and dispensa112024.si75_nroprocesso = liclicita.l20_edital::varchar)
		INNER JOIN cflicita on (liclicita.l20_codtipocom = cflicita.l03_codigo)
		INNER JOIN pctipocompratribunal on (cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial)
		INNER JOIN pcprocitem  on (liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem)
		INNER JOIN solicitem on (pcprocitem.pc81_solicitem = solicitem.pc11_codigo)
		INNER JOIN solicitempcmater on (solicitem.pc11_codigo = solicitempcmater.pc16_solicitem)
		INNER JOIN pcmater on (solicitempcmater.pc16_codmater = pcmater.pc01_codmater)
		INNER JOIN pcproc on (pcprocitem.pc81_codproc=pcproc.pc80_codproc)
		INNER JOIN pcorcamitemproc on (pcprocitem.pc81_codprocitem = pcorcamitemproc.pc31_pcprocitem)
		INNER JOIN pcorcamitem on (pcorcamitemproc.pc31_orcamitem = pcorcamitem.pc22_orcamitem)
		INNER JOIN pcorcamval on (pcorcamitem.pc22_orcamitem = pcorcamval.pc23_orcamitem)
		INNER JOIN precoreferencia on (pcproc.pc80_codproc = precoreferencia.si01_processocompra)
		INNER JOIN itemprecoreferencia on (precoreferencia.si01_sequencial = itemprecoreferencia.si02_precoreferencia and pcorcamval.pc23_orcamitem = itemprecoreferencia.si02_itemproccompra)
		INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
		LEFT JOIN solicitemunid AS solicitemunid ON solicitem.pc11_codigo = solicitemunid.pc17_codigo
    LEFT JOIN matunid AS matunid ON solicitemunid.pc17_unid = matunid.m61_codmatunid
      LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
		INNER JOIN liclicitasituacao ON liclicitasituacao.l11_liclicita = liclicita.l20_codigo
		LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
    WHERE db_config.codigo= " . db_getsession("DB_instit") . " AND (liclicita.l20_licsituacao = 1 OR liclicita.l20_licsituacao = 10) and l03_pctipocompratribunal in (100, 101, 102, 103)
		AND liclicita.l20_codigo= $codigoLicitacao");
    }

    public function getDadosRegistro16($codigoLicitacao){
      return DB::select("select DISTINCT '16' as tipoRegistro,
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
	liclicita.l20_tipoprocesso as tipoProcesso,
	(CASE length(cgm.z01_cgccpf) WHEN 11 THEN 1
		ELSE 2
	END) as tipoDocumento,
	(
    select
    z01_cgccpf
    from
      cgm
    join pcorcamforne pof on
      pof.pc21_numcgm = cgm.z01_numcgm
    where
      pof.pc21_orcamforne = pcorcamforne.pc21_orcamforne) as nroDocumento,
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
  liclicitemlote.l04_numerolote as nroLote,
  infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual,
  CASE
        WHEN (pcmater.pc01_codmaterant != 0 or pcmater.pc01_codmaterant != null) THEN pcmater.pc01_codmaterant::varchar
      ELSE pcmater.pc01_codmater::varchar END AS coditem,
	pcorcamval.pc23_vlrun as vlUnitario,
	pcorcamval.pc23_quant as quantidade,
  manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant
	FROM liclicita
	INNER JOIN cflicita on (liclicita.l20_codtipocom = cflicita.l03_codigo)
	INNER JOIN pctipocompratribunal on (cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial)
	INNER JOIN liclicitem on (liclicita.l20_codigo=liclicitem.l21_codliclicita)
	INNER JOIN pcorcamitemlic ON (liclicitem.l21_codigo = pcorcamitemlic.pc26_liclicitem )
	INNER JOIN pcorcamitem ON (pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem)

	inner join pcorcam on pc20_codorc = pc22_codorc

inner join pcorcamforne on
	(pcorcam.pc20_codorc = pcorcamforne.pc21_codorc)


inner join pcorcamjulg on
	(pcorcamitem.pc22_orcamitem = pcorcamjulg.pc24_orcamitem
	and pcorcamforne.pc21_orcamforne = pcorcamjulg.pc24_orcamforne)
  INNER JOIN habilitacaoforn on (pcorcamforne.pc21_numcgm=habilitacaoforn.l206_fornecedor and liclicita.l20_codigo=habilitacaoforn.l206_licitacao)
  INNER JOIN pcforne on (habilitacaoforn.l206_fornecedor=pcforne.pc60_numcgm)
	INNER JOIN cgm on (pcforne.pc60_numcgm=cgm.z01_numcgm)
  INNER JOIN pcprocitem  ON (liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem)
	INNER JOIN solicitem ON (pcprocitem.pc81_solicitem = solicitem.pc11_codigo)
	INNER JOIN solicitempcmater ON (solicitem.pc11_codigo=solicitempcmater.pc16_solicitem)
  inner join pcmater on pcmater.pc01_codmater = solicitempcmater.pc16_codmater
	INNER JOIN pcorcamval ON (pcorcamitem.pc22_orcamitem = pcorcamval.pc23_orcamitem and pcorcamforne.pc21_orcamforne=pcorcamval.pc23_orcamforne)
	LEFT  JOIN liclicitemlote on (liclicitem.l21_codigo=liclicitemlote.l04_liclicitem AND liclicita.l20_tipojulg = 3)
	LEFT  JOIN dispensa112024 on (liclicitemlote.l04_descricao = dispensa112024.si75_dsclote and dispensa112024.si75_nroprocesso = liclicita.l20_edital::varchar)
	LEFT JOIN pcorcamjulg as julgamento ON julgamento.pc24_orcamitem = pcorcamitem.pc22_orcamitem
  AND pcorcamforne.pc21_orcamforne = julgamento.pc24_orcamforne
  INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
	LEFT JOIN solicitemunid AS solicitemunid ON solicitem.pc11_codigo = solicitemunid.pc17_codigo
  LEFT JOIN matunid AS matunid ON solicitemunid.pc17_unid = matunid.m61_codmatunid
      LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
	INNER JOIN liclicitasituacao ON liclicitasituacao.l11_liclicita = liclicita.l20_codigo
	LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
  WHERE db_config.codigo= " . db_getsession("DB_instit") . " AND (liclicita.l20_licsituacao = 1 OR liclicita.l20_licsituacao = 10)
	AND liclicita.l20_codigo= $codigoLicitacao AND pctipocompratribunal.l44_sequencial in (100,101) AND julgamento.pc24_pontuacao=1");
    }

    public function getDadosRegistro20($licitacoes){
      return DB::select("select distinct on (coditem,l205_fornecedor)
    '20' as tipoRegistro,
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
	liclicita.l20_tipoprocesso as tipoProcesso,
	(CASE length(cgm.z01_cgccpf) WHEN 11 THEN 1
		ELSE 2
	END) as tipoDocumento,
	cgm.z01_cgccpf as nroDocumento,
	credenciamento.l205_datacred as dataCredenciamento,
  liclicitemlote.l04_numerolote as nroLote,
  infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual,
  CASE
        WHEN (pcmater.pc01_codmaterant != 0 or pcmater.pc01_codmaterant != null) THEN pcmater.pc01_codmaterant::varchar
      ELSE pcmater.pc01_codmater::varchar END AS coditem,
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
  manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant
	FROM liclicita
	INNER JOIN credenciamento on (liclicita.l20_codigo=credenciamento.l205_licitacao)
  LEFT join habilitacaoforn on (liclicita.l20_codigo = habilitacaoforn.l206_licitacao) and l205_fornecedor = l206_fornecedor
	INNER JOIN pcforne on (credenciamento.l205_fornecedor=pcforne.pc60_numcgm)
	INNER JOIN cgm on (pcforne.pc60_numcgm=cgm.z01_numcgm)
	INNER JOIN cflicita on (liclicita.l20_codtipocom = cflicita.l03_codigo)
	INNER JOIN pctipocompratribunal on (cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial)
	INNER JOIN liclicitem on (liclicita.l20_codigo=liclicitem.l21_codliclicita) and (liclicitem.l21_codpcprocitem = credenciamento.l205_item)
	INNER JOIN pcorcamitemlic ON (liclicitem.l21_codigo = pcorcamitemlic.pc26_liclicitem )
	INNER JOIN pcorcamitem ON (pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem)
	INNER JOIN pcprocitem  ON (liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem)
	INNER JOIN solicitem ON (pcprocitem.pc81_solicitem = solicitem.pc11_codigo)
	INNER JOIN solicitempcmater ON (solicitem.pc11_codigo=solicitempcmater.pc16_solicitem)
	INNER JOIN pcmater on (solicitempcmater.pc16_codmater = pcmater.pc01_codmater)
	INNER JOIN liclicitemlote on (liclicitem.l21_codigo=liclicitemlote.l04_liclicitem)
	LEFT JOIN dispensa112024 on (liclicitemlote.l04_descricao = dispensa112024.si75_dsclote and dispensa112024.si75_nroprocesso = liclicita.l20_edital::varchar)
	INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
	LEFT JOIN solicitemunid AS solicitemunid ON solicitem.pc11_codigo = solicitemunid.pc17_codigo
  LEFT JOIN matunid AS matunid ON solicitemunid.pc17_unid = matunid.m61_codmatunid
      LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
	INNER JOIN liclicitasituacao ON liclicitasituacao.l11_liclicita = liclicita.l20_codigo
	LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
  WHERE db_config.codigo= " . db_getsession("DB_instit") . " and l03_pctipocompratribunal in (100, 101, 102, 103)
     AND l20_codigo in ($licitacoes)");

    }

    public function getDadosRegistro30($licitacoes){
      return DB::select("select l20_codigo, infocomplementaresinstit.si09_codorgaotce as codOrgaoResp,
    l20_tipojulg,
    (CASE liclicita.l20_tipojulg WHEN 3 THEN 1
		ELSE 2
	  END) as processoPorLote,
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
    liclicita.l20_tipoprocesso as tipoProcesso,
    liclicitemlote.l04_numerolote as nroLote,
    manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant,
      liclicitemlote.l04_numerolote as nroLote,
  infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual,
     CASE
        WHEN (pcmater.pc01_codmaterant != 0 or pcmater.pc01_codmaterant != null) THEN pcmater.pc01_codmaterant::varchar
      ELSE pcmater.pc01_codmater::varchar END AS coditem,
    (CASE length(cgm.z01_cgccpf) WHEN 11 THEN 1 ELSE 2 END) as tipoDocumento,
    (
        select
        z01_cgccpf
        from
          cgm
        join pcorcamforne pof on
          pof.pc21_numcgm = cgm.z01_numcgm
        where
          pof.pc21_orcamforne = pcorcamforne.pc21_orcamforne) as nroDocumento,
          pcorcamval.pc23_perctaxadesctabela as percDesconto,
    * from liclicita
    INNER JOIN cflicita on (liclicita.l20_codtipocom = cflicita.l03_codigo)
    INNER JOIN pctipocompratribunal on (cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial)
    INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
    INNER JOIN habilitacaoforn on (liclicita.l20_codigo=habilitacaoforn.l206_licitacao)
    INNER JOIN pcforne on (habilitacaoforn.l206_fornecedor=pcforne.pc60_numcgm)
    INNER JOIN cgm on (pcforne.pc60_numcgm=cgm.z01_numcgm)
    INNER JOIN liclicitem on (liclicita.l20_codigo=liclicitem.l21_codliclicita)
    INNER JOIN pcorcamitemlic ON (liclicitem.l21_codigo = pcorcamitemlic.pc26_liclicitem )
    INNER JOIN pcorcamitem ON (pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem)
    INNER join pcorcam on pc20_codorc = pc22_codorc
    INNER join pcorcamforne on (pcorcam.pc20_codorc = pcorcamforne.pc21_codorc)
    inner join pcorcamjulg on
      (pcorcamitem.pc22_orcamitem = pcorcamjulg.pc24_orcamitem
      and pcorcamforne.pc21_orcamforne = pcorcamjulg.pc24_orcamforne)
        INNER JOIN pcorcamval ON (pcorcamjulg.pc24_orcamitem = pcorcamval.pc23_orcamitem and pcorcamjulg.pc24_orcamforne=pcorcamval.pc23_orcamforne)
      INNER JOIN pcprocitem  ON (liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem)
      INNER JOIN solicitem ON (pcprocitem.pc81_solicitem = solicitem.pc11_codigo)
      INNER JOIN solicitempcmater ON (solicitem.pc11_codigo=solicitempcmater.pc16_solicitem)
      inner join pcmater on pcmater.pc01_codmater = solicitempcmater.pc16_codmater
    INNER JOIN liclicitemlote on (liclicitem.l21_codigo=liclicitemlote.l04_liclicitem)
      LEFT JOIN solicitemunid AS solicitemunid ON solicitem.pc11_codigo = solicitemunid.pc17_codigo
      LEFT JOIN matunid AS matunid ON solicitemunid.pc17_unid = matunid.m61_codmatunid
      LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
    LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
    WHERE db_config.codigo= " . db_getsession("DB_instit") . " and l03_pctipocompratribunal in (100, 101, 102, 103) and l20_criterioadjudicacao = 1
     AND l20_codigo in ($licitacoes)"
);
    }

    public function getDadosRegistro40($licitacoes){
      return DB::select("select l20_codigo, infocomplementaresinstit.si09_codorgaotce as codOrgaoResp,
    l20_tipojulg,
    (CASE liclicita.l20_tipojulg WHEN 3 THEN 1
		ELSE 2
	  END) as processoPorLote,
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
    liclicita.l20_tipoprocesso as tipoProcesso,
  liclicitemlote.l04_numerolote as nroLote,
  infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual,
    manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant,
     CASE
        WHEN (pcmater.pc01_codmaterant != 0 or pcmater.pc01_codmaterant != null) THEN pcmater.pc01_codmaterant::varchar
      ELSE pcmater.pc01_codmater::varchar END AS coditem,
    (CASE length(cgm.z01_cgccpf) WHEN 11 THEN 1 ELSE 2 END) as tipoDocumento,
    (
        select
        z01_cgccpf
        from
          cgm
        join pcorcamforne pof on
          pof.pc21_numcgm = cgm.z01_numcgm
        where
          pof.pc21_orcamforne = pcorcamforne.pc21_orcamforne) as nroDocumento,
          pcorcamval.pc23_perctaxadesctabela as perctaxaadm,
    * from liclicita
    INNER JOIN cflicita on (liclicita.l20_codtipocom = cflicita.l03_codigo)
    INNER JOIN pctipocompratribunal on (cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial)
    INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
    INNER JOIN habilitacaoforn on (liclicita.l20_codigo=habilitacaoforn.l206_licitacao)
    INNER JOIN pcforne on (habilitacaoforn.l206_fornecedor=pcforne.pc60_numcgm)
    INNER JOIN cgm on (pcforne.pc60_numcgm=cgm.z01_numcgm)
    INNER JOIN liclicitem on (liclicita.l20_codigo=liclicitem.l21_codliclicita)
    INNER JOIN pcorcamitemlic ON (liclicitem.l21_codigo = pcorcamitemlic.pc26_liclicitem )
    INNER JOIN pcorcamitem ON (pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem)
    INNER join pcorcam on pc20_codorc = pc22_codorc
    INNER join pcorcamforne on (pcorcam.pc20_codorc = pcorcamforne.pc21_codorc)
    inner join pcorcamjulg on
      (pcorcamitem.pc22_orcamitem = pcorcamjulg.pc24_orcamitem
      and pcorcamforne.pc21_orcamforne = pcorcamjulg.pc24_orcamforne)
        INNER JOIN pcorcamval ON (pcorcamjulg.pc24_orcamitem = pcorcamval.pc23_orcamitem and pcorcamjulg.pc24_orcamforne=pcorcamval.pc23_orcamforne)
      INNER JOIN pcprocitem  ON (liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem)
      INNER JOIN solicitem ON (pcprocitem.pc81_solicitem = solicitem.pc11_codigo)
      INNER JOIN solicitempcmater ON (solicitem.pc11_codigo=solicitempcmater.pc16_solicitem)
      inner join pcmater on pcmater.pc01_codmater = solicitempcmater.pc16_codmater
    INNER JOIN liclicitemlote on (liclicitem.l21_codigo=liclicitemlote.l04_liclicitem)
      LEFT JOIN solicitemunid AS solicitemunid ON solicitem.pc11_codigo = solicitemunid.pc17_codigo
      LEFT JOIN matunid AS matunid ON solicitemunid.pc17_unid = matunid.m61_codmatunid
      LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
    LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
    WHERE db_config.codigo= " . db_getsession("DB_instit") . " and l03_pctipocompratribunal in (100, 101, 102, 103) and l20_criterioadjudicacao = 2
     AND l20_codigo in ($licitacoes)");
    }

    public function getDadosRegistro50($licitacoes){
        return DB::select("SELECT DISTINCT infocomplementaresinstit.si09_codorgaotce AS codOrgaoResp,
						(SELECT CASE
									WHEN o41_subunidade != 0
										 OR NOT NULL THEN lpad((CASE
																	WHEN o40_codtri = '0'
																		 OR NULL THEN o40_orgao::varchar
																	ELSE o40_codtri
																END),2,0)||lpad((CASE
																					 WHEN o41_codtri = '0'
																						  OR NULL THEN o41_unidade::varchar
																					 ELSE o41_codtri
																				 END),3,0)||lpad(o41_subunidade::integer,3,0)
									ELSE lpad((CASE
												   WHEN o40_codtri = '0'
														OR NULL THEN o40_orgao::varchar
												   ELSE o40_codtri
											   END),2,0)||lpad((CASE
																	WHEN o41_codtri = '0'
																		 OR NULL THEN o41_unidade::varchar
																	ELSE o41_codtri
																END),3,0)
								END AS codunidadesubresp
						 FROM db_departorg
						 JOIN public.infocomplementares ON si08_anousu = db01_anousu
						 AND si08_instit = " . db_getsession('DB_instit') . "
						 JOIN orcunidade ON db01_orgao=o41_orgao
						 AND db01_unidade = o41_unidade
						 AND db01_anousu = o41_anousu
						 JOIN orcorgao ON o40_orgao = o41_orgao
						 AND o40_anousu = o41_anousu
						 WHERE db01_coddepto=l20_codepartamento
							 AND db01_anousu = " . db_getsession('DB_anousu') . "
						 LIMIT 1) AS codUnidadeSubResp,
               liclicita.l20_tipoprocesso AS tipoProcesso,
						   liclicita.l20_anousu AS exercicioProcesso,
						   liclicita.l20_edital AS nroProcesso,
               si01_datacotacao as datacotacao,
                   l20_tipojulg AS julgamento,
                       l04_descricao AS lote,
                           db150_sequencial AS sequencialendereco,
						   obrasdadoscomplementareslote.db150_sequencial as sequencial,
						   obrasdadoscomplementareslote.db150_codobra as codObraLocal,
						   obrasdadoscomplementareslote.db150_classeobjeto as classeObjeto,
						   obrasdadoscomplementareslote.db150_atividadeobra as tipoAtividadeObra,
						   case when obrasdadoscomplementareslote.db150_atividadeservico is null then 0
                    else obrasdadoscomplementareslote.db150_atividadeservico end as tipoAtividadeServico,
						   obrasdadoscomplementareslote.db150_descratividadeservico as dscAtividadeServico,
						   obrasdadoscomplementareslote.db150_atividadeservicoesp as tipoAtividadeServEspecializado,
						   obrasdadoscomplementareslote.db150_descratividadeservicoesp as dscAtividadeServEspecializado,
						   orcdotacao.o58_funcao AS codFuncao,
						   orcdotacao.o58_subfuncao AS codSubFuncao,
						   CASE WHEN db150_grupobempublico <> 99 THEN db150_subgrupobempublico ELSE '9900' END AS codBemPublico,
						   obrasdadoscomplementareslote.db150_planilhatce,
              case when liclicita.l20_naturezaobjeto = '1' or liclicita.l20_naturezaobjeto = '7' then liclicita.l20_regimexecucao else 0 end AS regimeExecucaoObras,
              obrasdadoscomplementareslote.db150_bdi AS bdi,
              	liclicita.l20_naturezaobjeto as naturezaObjeto,
                		liclicitemlote.l04_numerolote as nroLote,
                        (CASE liclicita.l20_tipojulg WHEN 3 THEN 1
		ELSE 2
	  END) as processoPorLote,
    						   obrasdadoscomplementareslote.db150_planilhatce as utilizacaoplanilha,
                   						   obrasdadoscomplementareslote.db150_logradouro AS logradouro,
						   obrasdadoscomplementareslote.db150_numero AS numero,
						   obrasdadoscomplementareslote.db150_bairro AS bairro,
						   db125_codigosistema AS cidade,
						   obrasdadoscomplementareslote.db150_distrito AS distrito,
						   obrasdadoscomplementareslote.db150_cep AS cep,
						   obrasdadoscomplementareslote.db150_latitude AS latitude,
						   obrasdadoscomplementareslote.db150_longitude AS longitude,
               CASE WHEN db150_grupobempublico <> 99 THEN db150_subgrupobempublico ELSE '9900' END AS codBemPublico,
               db72_descricao AS municipio,
               db125_codigosistema AS codMunicipioIBGE,
						   si09_codunidadesubunidade AS codUnidadeSubRespEstadual
                        FROM liclicita
                        INNER JOIN liclicitem ON (liclicita.l20_codigo=liclicitem.l21_codliclicita)
                        INNER JOIN liclicitemlote on (liclicitem.l21_codigo=liclicitemlote.l04_liclicitem)
                        INNER JOIN pcprocitem ON (liclicitem.l21_codpcprocitem=pcprocitem.pc81_codprocitem)
                        JOIN pcproc ON pc80_codproc = pc81_codproc
                        INNER JOIN precoreferencia on (pcproc.pc80_codproc = precoreferencia.si01_processocompra)
                        INNER JOIN cflicita ON (cflicita.l03_codigo = liclicita.l20_codtipocom)
                        INNER JOIN pctipocompratribunal ON (cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial)
                        INNER JOIN db_depart ON l20_codepartamento = coddepto
                        INNER JOIN db_departorg ON db01_coddepto = coddepto AND db01_anousu = " . db_getsession('DB_anousu') . "
                        INNER JOIN db_config ON (instit=codigo)
                   		  INNER JOIN pcdotac on (pcprocitem.pc81_solicitem=pcdotac.pc13_codigo)
                        INNER JOIN orcdotacao on (pcdotac.pc13_anousu=orcdotacao.o58_anousu and pcdotac.pc13_coddot=orcdotacao.o58_coddot)
                        inner join orcunidade on o41_anousu = o58_anousu and o41_orgao = o58_orgao and o41_unidade = o58_unidade
                        inner join orcorgao on o40_orgao = o41_orgao and o40_anousu = o41_anousu
      LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
                        INNER JOIN liclancedital ON liclancedital.l47_liclicita = liclicita.l20_codigo
                        INNER JOIN obrascodigos on obrascodigos.db151_liclicita = liclancedital.l47_liclicita
                        INNER JOIN obrasdadoscomplementareslote ON db151_codigoobra = db150_codobra
                        LEFT JOIN cadendermunicipio on db72_sequencial = db150_municipio
                        LEFT JOIN cadendermunicipiosistema on db72_sequencial = db125_cadendermunicipio
					    WHERE db_config.codigo= " . db_getsession("DB_instit") . " and l03_pctipocompratribunal in (100, 101, 102, 103)
     AND l20_codigo in ($licitacoes)");
    }
}
