<?php

namespace App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025;

use Illuminate\Database\Capsule\Manager as DB;

class ArquivoEditalAnexoRepository
{

	public function getAnexosJulgamento($licitacoes){
		return DB::select("select distinct on (l20_codigo) 
							pc98_anexo as arquivo,
							pc98_nomearquivo as nomearquivo,
							liclicita.l20_edital AS nroprocesso,
							liclicita.l20_tipoprocesso as tipoProcesso,
						    l20_anousu AS exercicio,
							si09_tipoinstit,
						 	si09_codunidadesubunidade,
                         	si09_codorgaotce AS codorgao,
                         	db21_codigomunicipoestado AS codmunicipio,
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
						 LIMIT 1) AS unidade
							from liclicita
							INNER JOIN liclancedital ON l47_liclicita = liclicita.l20_codigo 
							INNER JOIN cflicita ON l03_codigo = liclicita.l20_codtipocom
							INNER JOIN db_config ON db_config.codigo = cflicita.l03_instit
							INNER JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
							inner join liclicitem on liclicitem.l21_codliclicita = liclicita.l20_codigo
							inner join pcorcamitemlic on pcorcamitemlic.pc26_liclicitem = liclicitem.l21_codigo
							inner join pcorcamitem on pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem
							inner join pcorcam on pcorcam.pc20_codorc = pcorcamitem.pc22_codorc
							inner join pcorcamanexos on pcorcam.pc20_codorc = pc98_codorc
							where l20_codigo in ($licitacoes) and liclicita.l20_instit = ".db_getsession("DB_instit"));
	}

    public function getAnexosDispensaEdital($licitacoes)
    {
        return DB::select(
            "		SELECT 	
                            l20_codigo,
					        l20_naturezaobjeto as naturezaobjeto,
                            l47_dataenvio AS dataenvio,
						   editaldocumentos.l48_arquivo AS arquivo,
						   editaldocumentos.l48_nomearquivo AS nomearquivo,
						   editaldocumentos.l48_tipo as tipo,
						   editaldocumentos.l48_sequencial as sequencial,
						   liclicita.l20_edital AS nroprocesso,
						   l20_anousu AS exercicio,
						   pctipocompratribunal.l44_sequencial AS codigotribunal,
						   liclicita.l20_tipoprocesso as tipoProcesso,
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
						 LIMIT 1) AS unidade,
						 si09_tipoinstit,
						 si09_codunidadesubunidade,
                         si09_codorgaotce AS codorgao,
                         db21_codigomunicipoestado AS codmunicipio
					FROM liclancedital
					INNER JOIN editaldocumentos ON editaldocumentos.l48_liclicita = liclancedital.l47_liclicita
					INNER JOIN liclicita ON liclicita.l20_codigo = l47_liclicita
					INNER JOIN cflicita ON l03_codigo = liclicita.l20_codtipocom
					INNER JOIN db_config ON db_config.codigo = cflicita.l03_instit
					INNER JOIN public.infocomplementaresinstit on db_config.codigo = public.infocomplementaresinstit.si09_instit
					INNER JOIN pctipocompra ON pctipocompra.pc50_codcom = cflicita.l03_codcom
					INNER JOIN pctipocompratribunal ON pctipocompratribunal.l44_sequencial = cflicita.l03_pctipocompratribunal
					WHERE  l20_codigo in ($licitacoes) and liclicita.l20_instit = ".db_getsession("DB_instit") . " order by l20_codigo"
        );
    }

	public function getAnexosRegAdesao($adesoes){
		return DB::select("select
							sd1_sequencialadesao,
							sd1_nomearquivo as nomearquivo,
							sd1_arquivo as arquivo,
							sd1_extensao,
							si06_anomodadm as exercicio,
							si06_nummodadm as nroprocesso,
							si09_tipoinstit,
							si09_codunidadesubunidade,
							si09_codorgaotce as codorgao,
							db21_codigomunicipoestado as codmunicipio,
							(
							select
								case
															when o41_subunidade != 0
									or not null then lpad((case
																							when o40_codtri = '0'
										or null then o40_orgao::varchar
										else o40_codtri
									end),
									2,
									0)|| lpad((case
																											when o41_codtri = '0'
										or null then o41_unidade::varchar
										else o41_codtri
									end),
									3,
									0)|| lpad(o41_subunidade::integer,
									3,
									0)
									else lpad((case
																		when o40_codtri = '0'
										or null then o40_orgao::varchar
										else o40_codtri
									end),
									2,
									0)|| lpad((case
																							when o41_codtri = '0'
										or null then o41_unidade::varchar
										else o41_codtri
									end),
									3,
									0)
								end as codunidadesubresp
							from
								db_departorg
							join public.infocomplementares on
								si08_anousu = db01_anousu
								and si08_instit = " . db_getsession('DB_instit') . "
							join orcunidade on
								db01_orgao = o41_orgao
								and db01_unidade = o41_unidade
								and db01_anousu = o41_anousu
							join orcorgao on
								o40_orgao = o41_orgao
								and o40_anousu = o41_anousu
							where
								db01_coddepto = si06_departamento
								and db01_anousu =  " . db_getsession('DB_anousu') . "
							limit 1) as unidade
						from
							adesaoregprecosdocumentos
						inner join adesaoregprecos on
							si06_sequencial = sd1_sequencialadesao
						inner join db_config on
							db_config.codigo = si06_instit
						inner join public.infocomplementaresinstit on
							db_config.codigo = public.infocomplementaresinstit.si09_instit
						where
							sd1_sequencialadesao in ($adesoes)  and si06_instit = ".db_getsession("DB_instit") . ";");
	}
}
