<?php

namespace App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025;

use Illuminate\Database\Capsule\Manager as DB;

class ArquivoResplicRepository
{
    public function getDadosRegistro10($licitacoes)
    {
        return DB::select(
            " select distinct '10' as tipoRegistro, infocomplementaresinstit.si09_codorgaotce as codOrgaoResp,
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
			liclicita.l20_anousu as exercicioLicitacao, liclicita.l20_edital as nroProcessoLicitatorio,
			liccomissaocgm.l31_tipo::int as tipoResp, l20_codigo as codigolicitacao, cgm.z01_cgccpf as nroCPFResp,
			liclicita.l20_codigo as codlicitacao,
			liclicita.l20_naturezaobjeto,
            manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant,
            infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual
			FROM liclicita as liclicita
			INNER JOIN liccomissaocgm AS liccomissaocgm ON (liclicita.l20_codigo=liccomissaocgm.l31_licitacao)
			INNER JOIN protocolo.cgm as cgm on (liccomissaocgm.l31_numcgm=cgm.z01_numcgm)
			INNER JOIN configuracoes.db_config as db_config on (liclicita.l20_instit=db_config.codigo)
			INNER JOIN cflicita ON (cflicita.l03_codigo = liclicita.l20_codtipocom)
	        INNER JOIN pctipocompratribunal ON (cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial)
			LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
            LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
			WHERE (l20_statusenviosicom = 1 or (l20_statusenviosicom = 1 and l20_licsituacao in (0,1,10))) AND db_config.codigo=" . db_getsession("DB_instit") . " and l03_pctipocompratribunal in (48,49,50,51,52,53,54,110) AND l20_codigo in ($licitacoes)"
        );
    }

    public function getDadosRegistro20($licitacoes){
        return DB::select("select distinct '20' as tipoRegistro,
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
				licpregao.l45_tipo as codTipoComissao,
				licpregao.l45_descrnomeacao as descricaoAtoNomeacao,
				licpregao.l45_numatonomeacao as nroAtoNomeacao,
				licpregao.l45_data as dataAtoNomeacao,
				licpregao.l45_data as inicioVigencia,
				licpregao.l45_validade as finalVigencia,
				cgm.z01_cgccpf as cpfMembroComissao,
				licpregaocgm.l46_tipo as codAtribuicao,
                l46_cargo as cargo,
				l46_naturezacargo as naturezaCargo,
                liclicita.l20_leidalicitacao as leidalicitacao,
                manutencaolicitacao.manutlic_codunidsubanterior AS codunidsubant,
                infocomplementaresinstit.si09_codunidadesubunidade AS codUnidadeSubRespEstadual
				FROM liclicita as liclicita
				INNER JOIN licpregao as licpregao on (liclicita.l20_equipepregao=licpregao.l45_sequencial)
				INNER JOIN licpregaocgm as licpregaocgm on (licpregao.l45_sequencial=licpregaocgm.l46_licpregao)
				INNER JOIN protocolo.cgm as cgm  on (licpregaocgm.l46_numcgm=cgm.z01_numcgm)
				INNER JOIN configuracoes.db_config as db_config on (liclicita.l20_instit=db_config.codigo)
                INNER JOIN cflicita ON (cflicita.l03_codigo = liclicita.l20_codtipocom)
                INNER JOIN pctipocompratribunal ON (cflicita.l03_pctipocompratribunal = pctipocompratribunal.l44_sequencial)
				LEFT JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
                LEFT JOIN manutencaolicitacao on (manutencaolicitacao.manutlic_licitacao = liclicita.l20_codigo)
			     WHERE db_config.codigo=" . db_getsession("DB_instit") . " and l03_pctipocompratribunal in (48,49,50,51,52,53,54,110) AND l20_codigo in ($licitacoes)"
        );
    }

}
