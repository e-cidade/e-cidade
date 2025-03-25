<?php

namespace App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025;

use Illuminate\Database\Capsule\Manager as DB;

class ArquivoAberlicRepository
{
    public function getDadosRegistro10($licitacoes)
    {

        return DB::select(
            
            "SELECT '10' AS tipoRegistro,
      l20_codigo as codlicitacao,
       public.infocomplementaresinstit.si09_codorgaotce as codOrgaoResp,
        public.infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual,
        l20_dataaberproposta,
       l20_leidalicitacao as lei,
       l20_dtpulicacaopncp as dataPncp,
       l20_inversaofases,
       l20_descrcriterio,
       l20_linkpncp as linkPncp,
       l20_dtpulicacaoedital as dataPubli,
       l20_linkedital as linkPublic,
       l20_diariooficialdivulgacao as divulgacaoDo,
        liclancedital.l47_origemrecurso AS origemRecurso,
        liclancedital.l47_descrecurso AS dscOrigemRecurso,
        liclancedital.l47_linkpub as link,
        liclancedital.l47_email as emailContato,
       l20_mododisputa as modoDisputa,
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
       liclicita.l20_edital AS nroProcessoLicitatorio,
       pctipocompratribunal.l44_codigotribunal AS codModalidadeLicitacao,
       liclicita.l20_numero AS nroModalidade,
       liclicita.l20_tipnaturezaproced AS naturezaProcedimento,
       liclicita.l20_datacria AS dtAbertura,
       liclicita.l20_dataaber AS dtEditalConvite,
       liclicita.l20_dtpublic AS dtPublicacaoEditalDO,
       liclicita.l20_datapublicacao1 AS dtPublicacaoEditalVeiculo1,
       liclicita.l20_nomeveiculo1 AS veiculo1Publicacao,
       liclicita.l20_datapublicacao2 AS dtPublicacaoEditalVeiculo2,
       liclicita.l20_nomeveiculo2 AS veiculo2Publicacao,
       liclicita.l20_recdocumentacao AS dtRecebimentoDoc,
       CASE
            WHEN liclicita.l20_tipliticacao = 2 THEN 7
            WHEN liclicita.l20_tipliticacao = 4 THEN 3
            WHEN liclicita.l20_tipliticacao = 5 THEN 4
            WHEN liclicita.l20_tipliticacao = 9 THEN 8
            ELSE liclicita.l20_tipliticacao
        END AS tipoLicitacao,
       liclicita.l20_tipliticacao AS criteriojulgamento,
       liclicita.l20_naturezaobjeto AS naturezaObjeto,
       liclicita.l20_objeto AS Objeto,
       l20_orcsigiloso,
       case when liclicita.l20_naturezaobjeto = '1' or liclicita.l20_naturezaobjeto = '7' then liclicita.l20_regimexecucao else 0 end AS regimeExecucaoObras,
       case when pctipocompratribunal.l44_codigotribunal = '1' then liclicita.l20_numeroconvidado else 0 end AS nroConvidado,
       ' ' AS clausulaProrrogacao,
       l20_diames AS unidadeMedidaPrazoExecucao,
       liclicita.l20_execucaoentrega AS prazoExecucao,
       liclicita.l20_condicoespag AS formaPagamento,
       liclicita.l20_aceitabilidade AS criterioAceitabilidade,
       CASE
           WHEN liclicita.l20_criterioadjudicacao is null THEN 3
           WHEN liclicita.l20_criterioadjudicacao = 0 THEN 3
           ELSE liclicita.l20_criterioadjudicacao
       END AS criterioAdjudicacao,
       	(CASE liclicita.l20_tipojulg
    WHEN 3 THEN 1
    ELSE 2
      END) as processoPorLote,
       liclicita.l20_critdesempate AS criterioDesempate,
       liclicita.l20_destexclusiva AS destinacaoExclusiva,
       liclicita.l20_subcontratacao AS subcontratacao,
       liclicita.l20_limitcontratacao AS limiteContratacao,
       liclicita.l20_nroedital,
       liclicita.l20_exercicioedital,
       manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant
     FROM liclicita
     INNER JOIN cflicita ON (cflicita.l03_codigo = liclicita.l20_codtipocom)
     INNER JOIN pctipocompratribunal ON (cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial)
     INNER JOIN db_config ON (liclicita.l20_instit=db_config.codigo)
     INNER JOIN liclancedital ON liclancedital.l47_liclicita = liclicita.l20_codigo
	LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
     LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
     WHERE (l20_statusenviosicom = 1 or (l20_statusenviosicom = 1 and l20_licsituacao in (0,1,10))) AND db_config.codigo=" . db_getsession("DB_instit") . " and l03_pctipocompratribunal in (48,49,50,51,52,53,54,110) AND l20_codigo in ($licitacoes)"
        );
    }

    public function getValorContrataoRegistro10($codigoLicitacao)
    {
        $resultado = DB::select("
            SELECT (
                SELECT SUM(si02_vltotalprecoreferencia)
                FROM itemprecoreferencia
                WHERE si02_precoreferencia = si01_sequencial
            ) AS vlcontratacao
            FROM liclicitem
            INNER JOIN pcprocitem ON pc81_codprocitem = l21_codpcprocitem
            INNER JOIN pcproc ON pc80_codproc = pc81_codproc
            INNER JOIN precoreferencia ON si01_processocompra = pc80_codproc
            INNER JOIN itemprecoreferencia ON si02_precoreferencia = si01_sequencial
            WHERE l21_codliclicita = $codigoLicitacao
            LIMIT 1
        ");
    
        return !empty($resultado) ? $resultado[0]->vlcontratacao : null;
    }    

    public function getDadosRegistro11($codigoLicitacao)
    {
        return DB::select("SELECT distinct '11' AS tipoRegistro,
           public.infocomplementaresinstit.si09_codorgaotce as codOrgaoResp,
        public.infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual,
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
           liclicita.l20_edital AS nroProcessoLicitatorio,
           liclicitemlote.l04_numerolote AS nroLote,
           liclicitemlote.l04_descricao AS dscLote,
           manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant
         FROM liclicitem
         INNER JOIN liclicita ON (liclicitem.l21_codliclicita=liclicita.l20_codigo)
           INNER JOIN db_config ON (liclicita.l20_instit=db_config.codigo)
         INNER JOIN liclicitemlote ON (liclicitem.l21_codigo=liclicitemlote.l04_liclicitem)
	LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
         LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
         WHERE db_config.codigo=" . db_getsession("DB_instit") . " AND liclicita.l20_tipojulg = 3
           AND liclicita.l20_codigo=$codigoLicitacao order by liclicitemlote.l04_numerolote");
    }

    public function getDadosRegistro12($codigoLicitacao){
        return DB::select(" select distinct '12' as tipoRegistro,
        public.infocomplementaresinstit.si09_codorgaotce as codOrgaoResp,
        public.infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual,
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
        CASE
        WHEN (pcmater.pc01_codmaterant != 0 or pcmater.pc01_codmaterant != null) THEN pcmater.pc01_codmaterant::varchar
      ELSE pcmater.pc01_codmater::varchar END AS coditem,
        manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant
        FROM liclicitem
        INNER JOIN liclicita on (liclicitem.l21_codliclicita=liclicita.l20_codigo)
        INNER JOIN pcprocitem  on (liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem)
        INNER JOIN solicitem on (pcprocitem.pc81_solicitem = solicitem.pc11_codigo)
        INNER JOIN solicitempcmater on (solicitem.pc11_codigo = solicitempcmater.pc16_solicitem)
        INNER JOIN pcmater on (solicitempcmater.pc16_codmater = pcmater.pc01_codmater)
        INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
        LEFT JOIN solicitemunid AS solicitemunid ON solicitem.pc11_codigo = solicitemunid.pc17_codigo
        LEFT JOIN matunid AS matunid ON solicitemunid.pc17_unid = matunid.m61_codmatunid
	LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
        LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
        WHERE db_config.codigo= " . db_getsession("DB_instit") . "
        AND liclicita.l20_codigo= $codigoLicitacao");
    }

    public function getDadosRegistro13($codigoLicitacao){

        return DB::select( " select '13' as tipoRegistro,
        public.infocomplementaresinstit.si09_codorgaotce as codOrgaoResp,
        public.infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual,
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
           liclicitemlote.l04_numerolote AS nroLote,
        CASE
        WHEN (pcmater.pc01_codmaterant != 0 or pcmater.pc01_codmaterant != null) THEN pcmater.pc01_codmaterant::varchar
      ELSE pcmater.pc01_codmater::varchar END AS coditem,
        manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant
        FROM liclicitem
        INNER JOIN liclicita on (liclicitem.l21_codliclicita=liclicita.l20_codigo)
        INNER JOIN pcprocitem  on (liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem)
        INNER JOIN solicitem on (pcprocitem.pc81_solicitem = solicitem.pc11_codigo)
        INNER JOIN solicitempcmater on (solicitem.pc11_codigo = solicitempcmater.pc16_solicitem)
        INNER JOIN pcmater on (solicitempcmater.pc16_codmater = pcmater.pc01_codmater)
        INNER JOIN db_config on (liclicita.l20_instit=db_config.codigo)
        INNER JOIN liclicitemlote on (liclicitem.l21_codigo=liclicitemlote.l04_liclicitem)
        LEFT JOIN solicitemunid AS solicitemunid ON solicitem.pc11_codigo = solicitemunid.pc17_codigo
        LEFT JOIN matunid AS matunid ON solicitemunid.pc17_unid = matunid.m61_codmatunid
	LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
        LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
        WHERE db_config.codigo= " . db_getsession("DB_instit") . " AND liclicita.l20_tipojulg = 3
        AND liclicita.l20_codigo= $codigoLicitacao");

    }

    public function getDadosRegistro14($codigoLicitacao){
        return DB::select("select distinct '14' as tipoRegistro,
    public.infocomplementaresinstit.si09_codorgaotce as codOrgaoResp,
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
           liclicitemlote.l04_numerolote AS nroLote,
    CASE
        WHEN (pcmater.pc01_codmaterant != 0 or pcmater.pc01_codmaterant != null) THEN pcmater.pc01_codmaterant::varchar
      ELSE pcmater.pc01_codmater::varchar END AS coditem,
    precoreferencia.si01_datacotacao as dtCotacao,
    itemprecoreferencia.si02_vlpercreferencia AS vlRefPercentual,
    itemprecoreferencia.si02_vlprecoreferencia as vlcotprecosunitario,
    pcorcamval.pc23_quant as quantidade,
    pc23_perctaxadesctabela,
    '0' as vlMinAlienBens,
        public.infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual,
    manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant
    FROM liclicitem
    INNER JOIN liclicita on (liclicitem.l21_codliclicita=liclicita.l20_codigo)
    LEFT  JOIN liclicitemlote on (liclicitem.l21_codigo=liclicitemlote.l04_liclicitem)
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
    INNER JOIN cflicita ON (cflicita.l03_codigo = liclicita.l20_codtipocom)
    INNER JOIN pctipocompratribunal ON (cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial)
	LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
    LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
    WHERE db_config.codigo= " . db_getsession("DB_instit") . " AND pctipocompratribunal.l44_sequencial != 102
    AND liclicita.l20_codigo= $codigoLicitacao");
    }

    public function getDadosRegistro15($codigoLicitacao){
        return DB::select( "select distinct '15' as tipoRegistro,
    public.infocomplementaresinstit.si09_codorgaotce as codOrgaoResp,
        public.infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual,
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
           liclicitemlote.l04_numerolote AS nroLote,
    CASE
        WHEN (pcmater.pc01_codmaterant != 0 or pcmater.pc01_codmaterant != null) THEN pcmater.pc01_codmaterant::varchar
      ELSE pcmater.pc01_codmater::varchar END AS coditem,
    itemprecoreferencia.si02_vlprecoreferencia as vlItem,
    manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant
    FROM liclicitem
    INNER JOIN liclicita on (liclicitem.l21_codliclicita=liclicita.l20_codigo)
    INNER JOIN liclicitemlote on (liclicitem.l21_codigo=liclicitemlote.l04_liclicitem)
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
    INNER JOIN solicitemunid AS solicitemunid ON solicitem.pc11_codigo = solicitemunid.pc17_codigo
    INNER JOIN matunid AS matunid ON solicitemunid.pc17_unid = matunid.m61_codmatunid
    INNER JOIN cflicita ON (cflicita.l03_codigo = liclicita.l20_codtipocom)
    INNER JOIN pctipocompratribunal ON (cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial)
	LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
    LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
    WHERE db_config.codigo= " . db_getsession("DB_instit") . " AND pctipocompratribunal.l44_sequencial = 102
    AND liclicita.l20_codigo= $codigoLicitacao");
    }

    public function getDadosRegistro20($licitacoes){

        return DB::select( "
SELECT DISTINCT ON (l20_codigo, db150_sequencial)
    public.infocomplementaresinstit.si09_codorgaotce AS codOrgaoResp,
    db150_sequencial AS sequencialendereco,
    CASE
        WHEN liclicita.l20_naturezaobjeto IN ('1', '7') THEN liclicita.l20_regimexecucao
        ELSE 0
    END AS regimeExecucaoObras,
    obrasdadoscomplementareslote.db150_bdi AS bdi,
    (
        SELECT CASE
            WHEN o41_subunidade != 0 OR NOT NULL THEN 
                LPAD(
                    (CASE
                        WHEN o40_codtri = '0' OR NULL THEN o40_orgao::VARCHAR
                        ELSE o40_codtri
                    END), 2, '0'
                ) || 
                LPAD(
                    (CASE
                        WHEN o41_codtri = '0' OR NULL THEN o41_unidade::VARCHAR
                        ELSE o41_codtri
                    END), 3, '0'
                ) || LPAD(o41_subunidade::INTEGER, 3, '0')
            ELSE 
                LPAD(
                    (CASE
                        WHEN o40_codtri = '0' OR NULL THEN o40_orgao::VARCHAR
                        ELSE o40_codtri
                    END), 2, '0'
                ) || 
                LPAD(
                    (CASE
                        WHEN o41_codtri = '0' OR NULL THEN o41_unidade::VARCHAR
                        ELSE o41_codtri
                    END), 3, '0'
                )
        END AS codunidadesubresp
        FROM db_departorg
        JOIN public.infocomplementares ON si08_anousu = db01_anousu AND si08_instit = " . db_getsession("DB_instit") . " 
        JOIN orcunidade ON db01_orgao = o41_orgao AND db01_unidade = o41_unidade AND db01_anousu = o41_anousu
        JOIN orcorgao ON o40_orgao = o41_orgao AND o40_anousu = o41_anousu
        WHERE db01_coddepto = l20_codepartamento AND db01_anousu = " . db_getsession("DB_anousu") . "
        LIMIT 1
    ) AS codUnidadeSubResp,
    liclicita.l20_anousu AS exercicioLicitacao,
    liclicita.l20_edital AS nroProcessoLicitatorio,
    CASE liclicita.l20_tipojulg
        WHEN 3 THEN 1
        ELSE 2
    END AS processoPorLote,
    public.infocomplementaresinstit.si09_codorgaotce AS codOrgao,
    orcdotacao.o58_orgao,
    orcdotacao.o58_unidade,
    orcdotacao.o58_funcao AS codFuncao,
    orcdotacao.o58_subfuncao AS codSubFuncao,
    obrasdadoscomplementareslote.db150_codobra AS codObraLocal,
    obrasdadoscomplementareslote.db150_classeobjeto AS classeObjeto,
    obrasdadoscomplementareslote.db150_atividadeobra AS tipoAtividadeObra,
    obrasdadoscomplementareslote.db150_atividadeservico AS tipoAtividadeServico,
    obrasdadoscomplementareslote.db150_descratividadeservico AS dscAtividadeServico,
    obrasdadoscomplementareslote.db150_atividadeservicoesp AS tipoAtividadeServEspecializado,
    obrasdadoscomplementareslote.db150_descratividadeservicoesp AS dscAtividadeServEspecializado,
    CASE 
        WHEN db150_grupobempublico <> 99 THEN db150_subgrupobempublico
        ELSE '9900'
    END AS codBemPublico,
    l04_descricao AS lote,
    liclicitemlote.l04_numerolote AS nroLote,
    l20_tipojulg AS julgamento,
    liclicita.l20_naturezaobjeto AS naturezaObjeto,
    EXTRACT(YEAR FROM si01_datacotacao) AS exercicioreforc,
    EXTRACT(MONTH FROM si01_datacotacao) AS mesreforc,
    obrasdadoscomplementareslote.db150_planilhatce AS planilhamodelo,
    obrasdadoscomplementareslote.db150_logradouro AS logradouro,
    obrasdadoscomplementareslote.db150_numero AS numero,
    obrasdadoscomplementareslote.db150_bairro AS bairro,
    obrasdadoscomplementareslote.db150_distrito AS distrito,
    db125_codigosistema AS codMunicipioIBGE,
    obrasdadoscomplementareslote.db150_cep AS cep,
    obrasdadoscomplementareslote.db150_latitude AS latitude,
    obrasdadoscomplementareslote.db150_longitude AS longitude,
    db72_descricao AS municipio,
    public.infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual
FROM obrasdadoscomplementareslote
INNER JOIN obrascodigos ON db151_sequencial = db150_seqobrascodigos
INNER JOIN liclicita ON obrascodigos.db151_liclicita = liclicita.l20_codigo
INNER JOIN liclicitem ON liclicita.l20_codigo = liclicitem.l21_codliclicita
INNER JOIN pcprocitem ON liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
INNER JOIN pcproc ON pcprocitem.pc81_codproc = pcproc.pc80_codproc
INNER JOIN precoreferencia ON pcproc.pc80_codproc = precoreferencia.si01_processocompra
LEFT JOIN pcdotac ON pcprocitem.pc81_solicitem = pcdotac.pc13_codigo
INNER JOIN orcdotacao ON pcdotac.pc13_anousu = orcdotacao.o58_anousu
    AND pcdotac.pc13_coddot = orcdotacao.o58_coddot
INNER JOIN orcunidade ON o41_anousu = o58_anousu
    AND o41_orgao = o58_orgao
    AND o41_unidade = o58_unidade
INNER JOIN orcorgao ON o40_orgao = o41_orgao
    AND o40_anousu = l20_anousu
    AND o40_instit = l20_instit
INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
INNER JOIN pctipocompratribunal ON cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial
INNER JOIN db_depart ON l20_codepartamento = coddepto
INNER JOIN db_departorg ON db01_coddepto = coddepto AND db01_anousu = " . db_getsession("DB_anousu") . "
INNER JOIN db_config ON liclicita.l20_instit = db_config.codigo
	LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
LEFT JOIN liclicitemlote ON l04_codigo = db150_lote
INNER JOIN cadendermunicipio ON db72_sequencial = db150_municipio
LEFT JOIN cadendermunicipiosistema ON db72_sequencial = db125_cadendermunicipio
WHERE db_config.codigo = " . db_getsession("DB_instit") . " 
    AND l03_pctipocompratribunal IN (48, 49, 50, 51, 52, 53, 54, 110)
    AND l20_codigo IN ($licitacoes)
ORDER BY l20_codigo, db150_sequencial;");

    }
}
