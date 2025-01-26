<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("model/orcamento/ControleOrcamentario.model.php");
include("libs/JSON.php");
db_app::import("exceptions.*");
db_app::import("configuracao.*");
db_app::import("contabilidade.*");
db_app::import("CgmFactory");
db_app::import("CgmBase");
db_app::import("CgmJuridico");
db_app::import("CgmFisico");
db_app::import("Dotacao");
db_app::import('exceptions.*');
db_app::import('empenhoFolha');
db_app::import('empenho.*');

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";
$oRetorno->itens   = array();
if (!isset($oParam->sSemestre)) {
  $oParam->sSemestre = 0;
}

try {

	switch ($oParam->exec) {

		case "getDadosEmpenho":

			$aSiglas = explode(',', $oParam->sSigla);

			$nTotalDescontos = 0;
			$aItens          = array();

			foreach ($aSiglas as $sSigla) {

				$oParam->sSigla = trim($sSigla);

				/**
				 * Calculamos o saldo das dotac�es
				 */
				if (!isset($oParam->iSeqPes)) {
					$rsDotacaoSaldo           = db_dotacaosaldo(8, 2, 2, true, "o58_instit=".db_getsession("DB_instit"), db_getsession("DB_anousu"));
					$aDotacoes      = db_utils::getCollectionByRecord($rsDotacaoSaldo);
					$aDotacoesSaldo = array();
					foreach ($aDotacoes as $oDotacao) {
						$aDotacoesSaldo[$oDotacao->o58_coddot] = $oDotacao;
					}

				}
				/**
				 * selecionamos todos os empenhos do tipo que tenham empenho gerados e anulamos
				 */
				if ($oParam->iTipoEmpenho == 2) {
					$sSqlEmpenhos   = " SELECT 
										COUNT(*) over (partition by rh02_lota, rh72_siglaarq) as qtdlota,
										case
											when COUNT(*) over (partition by rh02_lota, rh72_siglaarq) > 1
											and COUNT(codlotavinc) over (partition by rh02_lota, rh72_siglaarq) = 0
											and MIN(o56_elemento::bigint) over (partition by rh02_lota, rh72_siglaarq) = o56_elemento::bigint 
                      						and MIN(rh72_recurso) over (partition by rh02_lota, rh72_siglaarq) = rh72_recurso then o56_elemento::bigint
											when COUNT(*) over (partition by rh02_lota, rh72_siglaarq) > 1
											and (COUNT(codlotavinc) over (partition by rh02_lota, rh72_siglaarq) > 0
											and (row_number() over (partition by rh02_lota, rh72_siglaarq order by rh73_valor desc) = 1))
											or (COUNT(codlotavinc) over (partition by rh02_lota, rh72_siglaarq) = 1)
											or COUNT(*) over (partition by rh02_lota, rh72_siglaarq) = 1 then codlotavinc
											else null
										end as rh25_codlotavinc, *		                                                                             ";			
					$sSqlEmpenhos  .= "	FROM ( SELECT rh72_sequencial,                                                                       ";
					$sSqlEmpenhos  .= "               rh02_lota,                                                                                ";
					$sSqlEmpenhos  .= "               rh25_codlotavinc as codlotavinc,                                                          ";
				} else {
					$sSqlEmpenhos   = "SELECT * from ( SELECT rh72_sequencial,                                                                       ";
				}
				$sSqlEmpenhos  .= "                        rh72_coddot,                                                                              ";
				$sSqlEmpenhos  .= "                        rh72_codele,                                                                              ";
				$sSqlEmpenhos  .= "                        o40_descr,                                                                                ";
				$sSqlEmpenhos  .= "                        o41_descr,                                                                                ";
				$sSqlEmpenhos  .= "                        rh72_unidade,                                                                             ";
				$sSqlEmpenhos  .= "                        rh72_orgao,                                                                               ";
				$sSqlEmpenhos  .= "                        rh72_projativ,                                                                            ";
				$sSqlEmpenhos  .= "                        rh72_programa,                                                                            ";
				$sSqlEmpenhos  .= "                        rh72_funcao,                                                                              ";
				$sSqlEmpenhos  .= "                        rh72_subfuncao,                                                                           ";
				$sSqlEmpenhos  .= "                        rh72_anousu,                                                                              ";
				$sSqlEmpenhos  .= "                        rh72_mesusu,                                                                              ";
				$sSqlEmpenhos  .= "                        rh72_recurso,                                                                             ";
				$sSqlEmpenhos  .= "                        rh72_siglaarq,                                                                            ";
				$sSqlEmpenhos  .= "                        rh72_concarpeculiar,                                                                      ";
				$sSqlEmpenhos  .= "                        rh72_tabprev,                                                                             ";
				$sSqlEmpenhos  .= "                        o56_elemento,                                                                             ";
				$sSqlEmpenhos  .= "                        o56_descr,                                                                                ";
				$sSqlEmpenhos  .= "                        o120_orcreserva,                                                                          ";
				$sSqlEmpenhos  .= "                        round(sum(case when rh73_pd = 2 then rh73_valor *-1 else rh73_valor end), 2) as rh73_valor";
				$sSqlEmpenhos  .= "                   from rhempenhofolha 																																				   ";
				$sSqlEmpenhos  .= "                        inner join rhempenhofolharhemprubrica  on rh81_rhempenhofolha = rh72_sequencial           ";
				$sSqlEmpenhos  .= "                        inner join rhempenhofolharubrica       on rh73_sequencial     = rh81_rhempenhofolharubrica";
				$sSqlEmpenhos  .= "                        inner join orcelemento                 on o56_codele          = rh72_codele               ";
				$sSqlEmpenhos  .= "                                                              and o56_anousu          = rh72_anousu               ";
				$sSqlEmpenhos  .= "                        inner join orcorgao                    on rh72_orgao          = o40_orgao                 ";
				$sSqlEmpenhos  .= "                                                              and rh72_anousu         = o40_anousu                ";
				$sSqlEmpenhos  .= "                        inner join orcunidade                  on rh72_orgao          = o41_orgao                 ";
				$sSqlEmpenhos  .= "                                                              and rh72_unidade        = o41_unidade               ";
				$sSqlEmpenhos  .= "                                                              and rh72_anousu         = o41_anousu                ";
				$sSqlEmpenhos  .= "                        inner join rhpessoalmov                on rh73_seqpes         = rh02_seqpes               ";
				$sSqlEmpenhos  .= "                                                              and rh73_instit         = rh02_instit               ";
				$sSqlEmpenhos  .= "                        left join rhempenhofolhaempenho        on rh72_sequencial     = rh76_rhempenhofolha       ";
				if ($oParam->iTipoEmpenho == 2) {
					$sSqlEmpenhos  .= "                    and rh76_lota = rh02_lota                                                                 ";
					$sSqlEmpenhos  .= "                    left join orcreservarhempenhofolha     on rh72_sequencial     = o120_rhempenhofolha       ";
					$sSqlEmpenhos  .= "                    and rh02_lota = o120_lota                                                                 ";
					$sSqlEmpenhos  .= "                    left join rhlotavinc on                                                                   ";
					$sSqlEmpenhos  .= "                    	rh02_lota = rh25_codigo                                                                   ";
					$sSqlEmpenhos  .= "                    	and rh02_anousu = rh25_anousu                                                             ";
					$sSqlEmpenhos  .= "                    	and rh72_projativ = rh25_projativ                                                         ";
					$sSqlEmpenhos  .= "                    	and rh72_recurso = rh25_recurso                                                           ";
					$sSqlEmpenhos  .= "                    	and rh25_codlotavinc = (select rh28_codlotavinc from rhlotavincele                        ";
					$sSqlEmpenhos  .= "                    where rh25_codlotavinc = rh28_codlotavinc and rh72_codele = rh28_codelenov)               ";
				} else {
					$sSqlEmpenhos  .= "                    left join orcreservarhempenhofolha     on rh72_sequencial     = o120_rhempenhofolha       ";
				}
				$sSqlEmpenhos  .= "                  where rh76_rhempenhofolha is null			                                                     ";
				$sSqlEmpenhos  .= "                    and rh72_tipoempenho = {$oParam->iTipo}                                                       ";
				$sSqlEmpenhos  .= "                    and rh73_instit      = ".db_getsession("DB_instit"). "                                        ";
				$sSqlEmpenhos  .= "                    and rh73_tiporubrica = 1																																			 ";
				$sSqlEmpenhos  .= "                    and rh72_anousu      = {$oParam->iAnoFolha}                                                   ";
				$sSqlEmpenhos  .= "                    and rh72_mesusu      = {$oParam->iMesFolha}                                                   ";
				if (isset($oParam->iSeqPes)) {
					$sSqlEmpenhos  .= "                    and rh73_seqpes     = {$oParam->iSeqPes}";
				} else if ($oParam->iTipo == 1) {
					$sSqlEmpenhos  .= "                    and rh72_seqcompl    = {$oParam->sSemestre}";
				}

				/**
				 * Inclui no where condicao das tabelas da previdencia
				 * caso seja o tipo 2 - previdencia e selecionou 1 ou mais tabelas
				 */
				if ( $oParam->iTipo == 2 && $oParam->sPrevidencia !== '' ) {
					$sSqlEmpenhos .= "                    and rh72_tabprev in({$oParam->sPrevidencia})  ";
				}

				$sSqlEmpenhos  .= "                    and rh72_siglaarq    = '{$oParam->sSigla}'";
				$sSqlEmpenhos  .= "                  group by rh72_sequencial,    ";
				$sSqlEmpenhos  .= "                           rh72_coddot,        ";
				if ($oParam->iTipoEmpenho == 2) {
					$sSqlEmpenhos  .= "                       rh02_lota,          ";
					$sSqlEmpenhos  .= "                       rh25_codlotavinc,   ";
				}
				$sSqlEmpenhos  .= "                           rh72_codele,        ";
				$sSqlEmpenhos  .= "                           o40_descr,          ";
				$sSqlEmpenhos  .= "                           o41_descr,          ";
				$sSqlEmpenhos  .= "                           rh72_unidade,       ";
				$sSqlEmpenhos  .= "                           rh72_orgao,         ";
				$sSqlEmpenhos  .= "                           rh72_projativ,      ";
				$sSqlEmpenhos  .= "                           rh72_programa,      ";
				$sSqlEmpenhos  .= "                           rh72_funcao,        ";
				$sSqlEmpenhos  .= "                           rh72_subfuncao,     ";
				$sSqlEmpenhos  .= "                           rh72_mesusu,        ";
				$sSqlEmpenhos  .= "                           rh72_anousu,        ";
				$sSqlEmpenhos  .= "                           rh72_recurso,       ";
				$sSqlEmpenhos  .= "                           rh72_siglaarq,      ";
				$sSqlEmpenhos  .= "                           rh72_concarpeculiar,";
				$sSqlEmpenhos  .= "                           rh72_tabprev,       ";
				$sSqlEmpenhos  .= "                           o56_elemento,       ";
				$sSqlEmpenhos  .= "                           o56_descr,          ";
				$sSqlEmpenhos  .= "                           o120_orcreserva     ";
				$sSqlEmpenhos  .= "                order by   rh72_recurso,rh72_orgao,rh72_unidade,rh72_projativ,rh72_coddot,rh72_codele ) as x ";
				$sSqlEmpenhos  .= "        WHERE rh73_valor <> 0                                                                    ";
				$sSqlEmpenhos  .= "        order by ";
				if ($oParam->iTipoEmpenho == 2) {
					$sSqlEmpenhos  .= "    rh02_lota,                             ";
				}
				$sSqlEmpenhos  .= "        rh72_recurso,rh72_orgao,rh72_unidade,rh72_projativ,rh72_coddot,rh72_codele ";

				$rsDadosEmpenho = db_query($sSqlEmpenhos);

				$aEmpenhos      = db_utils::getCollectionByRecord($rsDadosEmpenho, false, false, true);
				$iTotalEmpenhos = count($aEmpenhos);
				for ($iEmpenho = 0; $iEmpenho < $iTotalEmpenhos; $iEmpenho++) {


					if ($aEmpenhos[$iEmpenho]->qtdlota > 1 && $aEmpenhos[$iEmpenho]->rh25_codlotavinc != null ) {
						$sql = "SELECT rh72_siglaarq,
									rh72_anousu,
									rh72_mesusu,
									rh78_retencaotiporec,
									round(sum(rh73_valor), 2) as valorretencao,
									ROUND(SUM(SUM(rh73_valor)) OVER (), 2) AS total_valorretencao
									from rhempenhofolha
									inner join rhempenhofolharhemprubrica        on rh81_rhempenhofolha = rh72_sequencial
									inner join rhempenhofolharubrica on rh73_sequencial     = rh81_rhempenhofolharubrica
									inner join rhpessoalmov          on rh73_seqpes                         = rh02_seqpes
																			and rh73_instit                = rh02_instit
									inner join  rhempenhofolharubricaretencao on rh78_rhempenhofolharubrica = rh73_sequencial
									where rh02_lota = {$aEmpenhos[$iEmpenho]->rh02_lota}
									and rh72_tipoempenho = {$oParam->iTipo}
									and rh73_tiporubrica = 2
									and rh73_pd          = 2
									and rh72_anousu = {$oParam->iAnoFolha}
									and rh72_mesusu = {$oParam->iMesFolha}
									group by rh72_siglaarq,
											rh72_mesusu,
											rh72_anousu,
											rh78_retencaotiporec
									order by valorretencao DESC";
						$result = db_utils::getCollectionByRecord(db_query($sql), false, false, true);

						if ((float)$aEmpenhos[$iEmpenho]->rh73_valor < (float)$result[0]->total_valorretencao){

							$aRetencoesLotacao = [];
							$iSumRetencao = 0;
							foreach ($result as $oRetencao) {
								if ($iSumRetencao + $oRetencao->valorretencao >= $aEmpenhos[$iEmpenho]->rh73_valor) {
									$aRetencoesLotacao = array_merge($aRetencoesLotacao, array_slice($result, array_search($oRetencao, $result)));
									break;
								}
								$aEmpenhos[$iEmpenho]->retencoescod[] = $oRetencao->rh78_retencaotiporec;
								$iSumRetencao += $oRetencao->valorretencao;
							}
							if (count($aRetencoesLotacao) > 0){
								$sqlSecundario = "
								select rh72_sequencial, round(sum(case when rh73_pd = 2 then rh73_valor *-1 else rh73_valor end),2) as rh73_valor
								from rhempenhofolha
									inner join rhempenhofolharhemprubrica on
										rh81_rhempenhofolha = rh72_sequencial
									inner join rhempenhofolharubrica on
										rh73_sequencial = rh81_rhempenhofolharubrica
								inner join rhpessoalmov on
										rh73_seqpes = rh02_seqpes
										and rh73_instit = rh02_instit
								where rh02_lota = {$aEmpenhos[$iEmpenho]->rh02_lota}
										and rh72_tipoempenho = {$oParam->iTipo}
										and rh73_instit = ".db_getsession("DB_instit")."
										and rh73_tiporubrica = 1
										and rh72_anousu = {$oParam->iAnoFolha}
										and rh72_mesusu = {$oParam->iMesFolha}
										and rh72_sequencial <> {$aEmpenhos[$iEmpenho]->rh72_sequencial} ";
										if (isset($oParam->iSeqPes)) {
											$sqlSecundario  .= "                    and rh73_seqpes     = {$oParam->iSeqPes}";
										} else if ($oParam->iTipo == 1) {
											$sqlSecundario  .= "                    and rh72_seqcompl    = {$oParam->sSemestre}";
										}
										$sqlSecundario .= "group by
										rh72_sequencial
									order by rh73_valor desc limit 1";
								$sequencialSecundario = db_utils::fieldsMemory(db_query($sqlSecundario), 0)->rh72_sequencial;
								if ($sequencialSecundario) {
									$empenhos = array_column($aEmpenhos, 'rh72_sequencial');
									$iEmpenhoSecundario = array_search($sequencialSecundario, $empenhos);
									foreach ($aRetencoesLotacao as $oRetencoesLotacao) {
										$aEmpenhos[$iEmpenhoSecundario]->retencoescod[] = $oRetencoesLotacao->rh78_retencaotiporec;
									}
								}
							}
						}
					}
					$aEmpenhos[$iEmpenho]->diferencaretencao = 0;
					$sSqlValorDesconto  = "select coalesce(sum(rh73_valor),0) as retencao ";
					$sSqlValorDesconto .= "  from rhempenhofolha ";
					$sSqlValorDesconto .= "       inner join rhempenhofolharhemprubrica on rh72_sequencial = rh81_rhempenhofolha ";
					$sSqlValorDesconto .= "       inner join rhempenhofolharubrica on rh81_rhempenhofolharubrica = rh73_sequencial ";
					if ($oParam->iTipoEmpenho == 2) {
						$sSqlValorDesconto  .= "      inner join rhpessoalmov on rh73_seqpes = rh02_seqpes and rh73_instit         = rh02_instit   ";
					}
					$sSqlValorDesconto .= " where rh73_tiporubrica = 2";
					$sSqlValorDesconto .= " and rh72_sequencial    = {$aEmpenhos[$iEmpenho]->rh72_sequencial}";
					if ($oParam->iTipoEmpenho == 2) {
						$sSqlValorDesconto  .= " and rh02_lota = {$aEmpenhos[$iEmpenho]->rh02_lota}";
					}
					$rsValorDesconto    = db_query($sSqlValorDesconto);
					if ($rsValorDesconto) {

						$nValorDesconto    = db_utils::fieldsMemory($rsValorDesconto, 0)->retencao;
						$aEmpenhos[$iEmpenho]->diferencaretencao = $aEmpenhos[$iEmpenho]->rh73_valor - $nValorDesconto;

					}
					$aEmpenhos[$iEmpenho]->saldodotacao = 0;
					/**
					 * Verificamos o saldo da Dotacao.
					 * caso foi solicitado o empenhamento de apenas uma dotacao pesquisammos o saldo da dotacao.
					 */
					if (isset($aDotacoesSaldo[$aEmpenhos[$iEmpenho]->rh72_coddot])) {
						$aEmpenhos[$iEmpenho]->saldodotacao = $aDotacoesSaldo[$aEmpenhos[$iEmpenho]->rh72_coddot]->atual_menos_reservado;
					} else if (isset($oParam->iSeqPes)) {

						$rsDotacaoSaldo           = db_dotacaosaldo(8, 2, 2, true,
								"o58_instit=".db_getsession("DB_instit")."
								and o58_coddot = {$aEmpenhos[$iEmpenho]->rh72_coddot}"
								, db_getsession("DB_anousu"));
						$aDotacoes      = db_utils::getCollectionByRecord($rsDotacaoSaldo);

						$aDotacoesSaldo = array();
						foreach ($aDotacoes as $oDotacao) {
							$aDotacoesSaldo[$oDotacao->o58_coddot] = $oDotacao;
						}

						if (isset($aDotacoesSaldo[$aEmpenhos[$iEmpenho]->rh72_coddot])) {
							$aEmpenhos[$iEmpenho]->saldodotacao = $aDotacoesSaldo[$aEmpenhos[$iEmpenho]->rh72_coddot]->atual_menos_reservado;
						}
					}
					if (isset($oParam->iSeqPes)) {

						$aEmpenhos[$iEmpenho]->rh73_seqpes = $oParam->iSeqPes;
						$oEmpenho                  = new empenhoFolha($aEmpenhos[$iEmpenho]->rh72_sequencial);
						$aEmpenhos[$iEmpenho]->rubricas   = $oEmpenho->getInfoEmpenho();
						$oRetorno->iProximoEmpenho = $oParam->iProximoEmpenho;
					}
				}
				/**
				 * Calculamos o total de descontos da folha
				 */
				$sSqlTotalDescontos  = "SELECT sum(round(rh73_valor,2)) as valor ";
				$sSqlTotalDescontos .= "  from rhempenhofolha ";
				$sSqlTotalDescontos .= "       inner join rhempenhofolharhemprubrica on rh81_rhempenhofolha        = rh72_sequencial";
				$sSqlTotalDescontos .= "       inner join rhempenhofolharubrica      on rh81_rhempenhofolharubrica = rh73_sequencial";
				$sSqlTotalDescontos .= " where rh72_tipoempenho = {$oParam->iTipo} ";
				$sSqlTotalDescontos .= "   and rh73_pd          = 2 ";
				$sSqlTotalDescontos .= "  and rh72_siglaarq     = '{$oParam->sSigla}'";
				$sSqlTotalDescontos .= "  and rh73_tiporubrica = 2";
				$sSqlTotalDescontos .= "  and rh72_mesusu       = '{$oParam->iMesFolha}'";
				$sSqlTotalDescontos .= "  and rh72_anousu       = '{$oParam->iAnoFolha}'";
				$sSqlTotalDescontos .= "  and rh73_instit       = ".db_getsession("DB_instit");
				if (isset($oParam->iSeqPes)) {
					$oRetorno->iProximoEmpenho = $oParam->iProximoEmpenho;
				} else if ($oParam->iTipo == 1) {
					$sSqlTotalDescontos .= "  and rh72_seqcompl     = '{$oParam->sSemestre}'";
				}

				if (  $oParam->iTipo == 2 && $oParam->sPrevidencia !== '' ){
					$sSqlTotalDescontos  .= " and rh72_tabprev in ({$oParam->sPrevidencia}) ";
				}

				$rsTotalDescontos = db_query($sSqlTotalDescontos);

				$nTotalDescontos += db_utils::fieldsMemory($rsTotalDescontos, 0)->valor;

				$aItens           = array_merge($aEmpenhos, $aItens);

			}

			$oRetorno->nTotalDescontos = $nTotalDescontos;
			$oRetorno->itens           = $aItens;

			break;

		case "getDadosEmpenhoFilho":

			$iLotacao = $oParam->iLotacao ? $oParam->iLotacao : null;
			$lRubrica = $oParam->lRubrica ? $oParam->lRubrica : null;
			$oEmpenho                  = new empenhoFolha($oParam->iEmpenho, $iLotacao, $lRubrica);
			$oRetorno->itens           = $oEmpenho->getInfoEmpenho();
			$oRetorno->iProximoEmpenho = $oParam->iProximoEmpenho;
			$oRetorno->iIndex          = $oParam->iIndex;

			break;
		case "getUnidades":

			$clorcunidade = db_utils::getDao("orcunidade");
			$result = $clorcunidade->sql_record($clorcunidade->sql_query(null,null,null,
					"o41_unidade,o41_unidade::varchar||' - '||o41_descr||' -'||o41_anousu as o41_descr,o41_orgao",
					"o41_unidade","o41_anousu=".db_getsession("DB_anousu")."
					and o41_orgao={$oParam->orgao}")
			);
			$oRetorno->itens = db_utils::getCollectionByRecord($result, false, false, true);

			break;
		case "getDotacoes":

			$iAnoUsu          = db_getsession("DB_anousu");
			$oDaoOrcParametro = db_utils::getDao('orcparametro');
			$oDaoOrcElemento  = db_utils::getDao('orcelemento');
			$sSqlParametro    = $oDaoOrcParametro->sql_query_file($iAnoUsu,'o50_subelem');
			$rsParametro      = $oDaoOrcParametro->sql_record($sSqlParametro);

			if ( $oDaoOrcParametro->numrows > 0 ) {
				$oParametro = db_utils::fieldsMemory($rsParametro,0);
			} else {
				throw new Exception("Configure os par�metros do or�amento para o ano {$iAnoUsu}!");
			}
			if ( $oParametro->o50_subelem == "f" ) {

				$sCamposElemento = "substr(o56_elemento,1,7)||'000000' as elemento";
				$sWhereElemento  = "     o56_codele = {$oParam->iElemento} ";
				$sWhereElemento .= " and o56_anousu = {$iAnoUsu}   ";

				$sSqlElemento = $oDaoOrcElemento->sql_query_file(null,null,$sCamposElemento,null,$sWhereElemento);
				$rsElemento   = $oDaoOrcElemento->sql_record($sSqlElemento);

				if ( $oDaoOrcElemento->numrows > 0 ) {

					$oElemento   = db_utils::fieldsMemory($rsElemento,0);
					$sWhereParam = " and o56_elemento='{$oElemento->elemento}' ";

				}

			} else {
				$sWhereParam = " and o58_codele = {$iElemento}";
			}
			$sSql  = "select distinct o58_coddot";
			$sSql .= "  from orcdotacao  ";
			$sSql .= "       inner join orcelemento on o56_codele = o58_codele";
			$sSql .= "                             and o56_anousu = o58_anousu";
			$sSql .= " where o58_orgao    = {$oParam->iOrgao}   ";
			$sSql .= "   and o58_unidade  = {$oParam->iUnidade} ";
			$sSql .= "   and o58_projativ = {$oParam->iProjAtiv} ";
			$sSql .= "   {$sWhereParam}";
			$sSql .= "   and o58_codigo      = {$oParam->iRecurso}";
			$sSql .= "   and o58_anousu      = ".db_getsession("DB_anousu");
			$sSql .= "   and o58_instit      = ".db_getsession("DB_instit");
			$rsDotacoes = db_query($sSql);
			$oRetorno->itens = db_utils::getCollectionByRecord($rsDotacoes);

			break;
		case "alterarDadosEmpenho":

			if ($oParam->iTipo == 1) {

				$iSeqPes = '';
				if (isset($oParam->sSigla) && $oParam->sSigla == 'r20') {
					$iSeqPes = $oParam->iSeqPes;
				}

				db_inicio_transacao();
				$oEmpenho = new empenhoFolha($oParam->iEmpenho);
				$oEmpenho->alterarDados($oParam->iOrgao,
						$oParam->iUnidade,
						$oParam->iProjAtiv,
						$oParam->iElemento,
						$oParam->iRecurso,
						$oParam->iDotacao,
						$oParam->iCaract,
						$iSeqPes,
						$oParam->iPrograma,
						$oParam->iFuncao,
						$oParam->iSubFuncao,
						$oParam->iTabPrev
				);
				db_fim_transacao(false);

			} else {

				db_inicio_transacao();

				$oEmpenho = new empenhoFolha($oParam->iEmpenho);
				$oEmpenho->alterarDadosRubricas(
						$oParam->iSeqPes,
						$oParam->iOrgao,
						$oParam->iUnidade,
						$oParam->iProjAtiv,
						$oParam->iElemento,
						$oParam->iRecurso,
						$oParam->iDotacao,
						$oParam->iCaract,
						$oParam->iPrograma,
						$oParam->iFuncao,
						$oParam->iSubFuncao
				);
				db_fim_transacao(false);

			}

			break;
		case "reservarSaldo":

			if (isset($oParam->rescisao) && $oParam->rescisao) {

				$aSeqPes = $oParam->aEmpenhos;
				$sListaRescisoes  = implode(",", $aSeqPes);
				$oParam->aEmpenhos = array();
				$sSqlListaEmpenhosRescisao  = "select distinct rh72_sequencial                                                           ";
				$sSqlListaEmpenhosRescisao .= "  from rhempenhofolha                                                                     ";
				$sSqlListaEmpenhosRescisao .= "       inner join rhempenhofolharhemprubrica on rh81_rhempenhofolha = rh72_sequencial     ";
				$sSqlListaEmpenhosRescisao .= "       inner join rhempenhofolharubrica on rh73_sequencial = rh81_rhempenhofolharubrica   ";
				$sSqlListaEmpenhosRescisao .= "       left join orcreservarhempenhofolha on rh72_sequencial = o120_rhempenhofolha        ";
				$sSqlListaEmpenhosRescisao .= " where rh73_seqpes in ({$sListaRescisoes})                                                ";
				$sSqlListaEmpenhosRescisao .= "  and  rh72_mesusu      = {$oParam->iMesFolha}                                            ";
				$sSqlListaEmpenhosRescisao .= "  and  rh72_anousu      = {$oParam->iAnoFolha}                                            ";
				$sSqlListaEmpenhosRescisao .= "  and  rh72_tipoempenho = {$oParam->iTipo}                                                ";
				$sSqlListaEmpenhosRescisao .= "  and  rh72_siglaarq = 'r20'                                                              ";

				/**
				 * Inclui no where condicao das tabelas da previdencia
				 * caso seja o tipo 2 - previdencia e selecionou 1 ou mais tabelas
				 */
				if ( $oParam->iTipo == 2 && $oParam->sPrevidencia !== null ) {
					$sSqlListaEmpenhosRescisao .= " and rh72_tabprev in({$oParam->sPrevidencia})                                           ";
				}

				$rsListaEmpenhos            = db_query($sSqlListaEmpenhosRescisao);
				$oParam->aEmpenhos = db_utils::getCollectionByRecord($rsListaEmpenhos);
			}

			db_inicio_transacao();

			foreach ($oParam->aEmpenhos as $oEmpenho) {
				$iLotacao = $oEmpenho->rh02_lota ? $oEmpenho->rh02_lota : null;
				$oEmpenho = new empenhoFolha($oEmpenho->rh72_sequencial,$iLotacao);
				$oEmpenho->reservarSaldo();
			}

			db_fim_transacao(false);

			break;
		case "cancelarReservas":

			if (isset($oParam->rescisao) && $oParam->rescisao) {

				$aSeqPes = $oParam->aEmpenhos;
				$sListaRescisoes  = implode(",", $aSeqPes);
				$oParam->aEmpenhos = array();
				$sSqlListaEmpenhosRescisao  = "select distinct rh72_sequencial ";
				$sSqlListaEmpenhosRescisao .= "  from rhempenhofolha ";
				$sSqlListaEmpenhosRescisao .= "       inner join rhempenhofolharhemprubrica on rh81_rhempenhofolha = rh72_sequencial ";
				$sSqlListaEmpenhosRescisao .= "       inner join rhempenhofolharubrica on rh73_sequencial = rh81_rhempenhofolharubrica ";
				$sSqlListaEmpenhosRescisao .= "       left join orcreservarhempenhofolha on rh72_sequencial = o120_rhempenhofolha  ";
				$sSqlListaEmpenhosRescisao .= " where rh73_seqpes in ({$sListaRescisoes})";
				$sSqlListaEmpenhosRescisao .= "  and  rh72_mesusu      = {$oParam->iMesFolha}";
				$sSqlListaEmpenhosRescisao .= "  and  rh72_anousu      = {$oParam->iAnoFolha}";
				$sSqlListaEmpenhosRescisao .= "  and  rh72_tipoempenho = {$oParam->iTipo}";
				$sSqlListaEmpenhosRescisao .= "  and  rh72_siglaarq = 'r20'";
				$rsListaEmpenhos            = db_query($sSqlListaEmpenhosRescisao);
				$oParam->aEmpenhos = db_utils::getCollectionByRecord($rsListaEmpenhos);
			}

			db_inicio_transacao();
			foreach ($oParam->aEmpenhos as $oEmpenho) {

				$oEmpenho = new empenhoFolha($oEmpenho->rh72_sequencial);
				$oEmpenho->cancelarReservaSaldo();

			}

			db_fim_transacao(false);

			break;
		case "gerarEmpenhos":

			db_inicio_transacao();
			/**
			 * Incluimos uma OP auxiliar nova
			*/
			$aRecursos       = array();
			$oDaoOPAuxiliar  = db_utils::getDao("empageordem");
			if (!$oParam->lOPporRecurso) {

				$oDaoOPAuxiliar->e42_dtpagamento = date("Y-m-d",db_getsession("DB_datausu"));
				$oDaoOPAuxiliar->incluir(null);

			}

			if (isset($oParam->rescisao) && $oParam->rescisao) {

				$aSeqPes = $oParam->aEmpenhos;
				$sListaRescisoes  = implode(",", $aSeqPes);
				$oParam->aEmpenhos = array();
				$sSqlListaEmpenhosRescisao  = "select distinct rh72_sequencial,rh01_numcgm, rh73_seqpes ";
				$sSqlListaEmpenhosRescisao .= "  from rhempenhofolha ";
				$sSqlListaEmpenhosRescisao .= "       inner join rhempenhofolharhemprubrica on rh81_rhempenhofolha = rh72_sequencial ";
				$sSqlListaEmpenhosRescisao .= "       inner join rhempenhofolharubrica on rh73_sequencial = rh81_rhempenhofolharubrica ";
				$sSqlListaEmpenhosRescisao .= "       inner join rhpessoalmov                on rh73_seqpes         = rh02_seqpes ";
				$sSqlListaEmpenhosRescisao .= "                                            and rh73_instit         = rh02_instit  ";
				$sSqlListaEmpenhosRescisao .= "       inner join rhpessoal                   on rh01_regist         = rh02_regist ";
				$sSqlListaEmpenhosRescisao .= "       left join orcreservarhempenhofolha on rh72_sequencial = o120_rhempenhofolha  ";
				$sSqlListaEmpenhosRescisao .= " where rh73_seqpes in ({$sListaRescisoes})";
				$sSqlListaEmpenhosRescisao .= "   and rh72_mesusu   = {$oParam->iMesFolha}";
				$sSqlListaEmpenhosRescisao .= "   and rh72_anousu   = {$oParam->iAnoFolha}";
				$sSqlListaEmpenhosRescisao .= "  and  rh72_tipoempenho = {$oParam->iTipo}";
				$sSqlListaEmpenhosRescisao .= "   and rh72_siglaarq = 'r20'";

				/**
				 * Inclui no where condicao das tabelas da previdencia
				 * caso seja o tipo 2 - previdencia e selecionou 1 ou mais tabelas
				 */
				if ( $oParam->iTipo == 2 && $oParam->sPrevidencia !== '' ) {
					$sSqlListaEmpenhosRescisao .= " and rh72_tabprev in({$oParam->sPrevidencia})                                           ";
				}

				$rsListaEmpenhos   = db_query($sSqlListaEmpenhosRescisao);
				$oParam->aEmpenhos = db_utils::getCollectionByRecord($rsListaEmpenhos);
			}

            $aEmpenhosFinaceirosGerados = array();

            $lPrevidencia = ($oParam->iTipo == 2);

			foreach ($oParam->aEmpenhos as $oEmpenhoFolha) {

				$iLotacao = $oEmpenhoFolha->rh02_lota ? $oEmpenhoFolha->rh02_lota : null;
				$lRubrica = $oEmpenhoFolha->lRubrica ? $oEmpenhoFolha->lRubrica : null;
				$sRetencoes = $oEmpenhoFolha->sRetencoes ? $oEmpenhoFolha->sRetencoes : null;
				$oEmpenho = new empenhoFolha($oEmpenhoFolha->rh72_sequencial, $iLotacao, $lRubrica, $sRetencoes);

				if (!isset($aRecursos[$oEmpenho->getRecurso()]) && $oParam->lOPporRecurso) {

					$oDaoOPAuxiliar->e42_dtpagamento = date("Y-m-d",db_getsession("DB_datausu"));
					$oDaoOPAuxiliar->incluir(null);
					$aRecursos[$oEmpenho->getRecurso()]  = $oDaoOPAuxiliar->e42_sequencial;

				}
				$iOPAuxiliar = $oParam->lOPporRecurso?$aRecursos[$oEmpenho->getRecurso()]:$oDaoOPAuxiliar->e42_sequencial;
				$oEmpenho->setOPAuxiliar($iOPAuxiliar);
				if (isset($oParam->rescisao) && $oParam->rescisao) {
					$oParam->iNumCgm = $oEmpenhoFolha->rh01_numcgm;
				}

                $oEmpenho->setTipoEmpenhoResumo($oParam->iTipo);
				$oEmpenho->gerarEmpenho($oParam->iNumCgm, $lPrevidencia);

                $aEmpenhosFinaceirosGerados[] = $oEmpenho->getNumeroEmpenhoFinanceiro();

				/**
				 * caso for folha de rescisao, devemos atualizar a rescisao como empenhada
				*/
				if (isset($oParam->rescisao) && $oParam->rescisao) {

					$oDaoPesRescisao = db_utils::getDao("rhpesrescisao");
					$oDaoPesRescisao->rh05_empenhado = "true";
					$oDaoPesRescisao->rh05_seqpes    = $oEmpenhoFolha->rh73_seqpes;
					$oDaoPesRescisao->alterar($oEmpenhoFolha->rh73_seqpes);
				}

			}

			$oRetorno->e42_sequencial = $iOPAuxiliar = $oParam->lOPporRecurso?implode(", ", $aRecursos):$iOPAuxiliar;
            $oRetorno->empenhos_financeiros_gerados = count($aEmpenhosFinaceirosGerados) > 0 ? implode(',',$aEmpenhosFinaceirosGerados) : '';
			db_fim_transacao(false);

			break;
		case "getOrigemDotacao":

			/**
			 * Consulta os dados de origem da dota��o ( Org�o, Unidade, Projeto, Elemento,  Recurso e Dota��o )
			 */
			$clOrcDotacao     = db_utils::getDao('orcdotacao');
			$sSqlDadosDotacao = $clOrcDotacao->sql_query(db_getsession('DB_anousu'),$oParam->iDotacao);
			$rsDadosDotacao   = $clOrcDotacao->sql_record($sSqlDadosDotacao);
			$oRetorno->itens  = db_utils::getCollectionByRecord($rsDadosDotacao,false,false,true);

			if ( $clOrcDotacao->numrows > 0 && isset($oParam->iDesdobramento) && trim($oParam->iDesdobramento) != '' ) {

				$oDotacao = db_utils::fieldsMemory($rsDadosDotacao,0);

				$sSqlDesdobramentos  = "select orcelemento.o56_codele,                                                                    ";
				$sSqlDesdobramentos .= "       orcelemento.o56_descr                                                                      ";
				$sSqlDesdobramentos .= "  from ( select *                                                                                 ";
				$sSqlDesdobramentos .= "           from orcelemento                                                                       ";
				$sSqlDesdobramentos .= "          where o56_codele = {$oDotacao->o56_codele}                                              ";
				$sSqlDesdobramentos .= "            and o56_anousu = ".db_getsession('DB_anousu')." ) as x                                ";
				$sSqlDesdobramentos .= "       inner join orcelemento on orcelemento.o56_anousu = ".db_getsession('DB_anousu')."          ";
				$sSqlDesdobramentos .= "                             and substr(orcelemento.o56_elemento,0,8) = substr(x.o56_elemento,0,8)";
				$sSqlDesdobramentos .= "                             and orcelemento.o56_codele = {$oParam->iDesdobramento}               ";

				$rsDesdobramento     = db_query($sSqlDesdobramentos);
				$iNroDesdobramento   = pg_num_rows($rsDesdobramento);

				if ( $iNroDesdobramento > 0 ) {

					$oDesdobramento = db_utils::fieldsMemory($rsDesdobramento,0);

					$lDesdobramento                = true;
					$oRetorno->iCodDesdobramento   = $oDesdobramento->o56_codele;
					$oRetorno->iDescrDesdobramento = $oDesdobramento->o56_descr;

				} else {
					$lDesdobramento = false;
				}

			} else {
				$lDesdobramento = false;
			}

			$oRetorno->lDesdobramento = $lDesdobramento;

			break;
		case "getMatriculasComEmpenhoRescisao":

			$sListaRescisoes = implode(",", $oParam->aRescisoes);
			/**
			 * selecionamos todos os empenhos do tipo que tenham empenho gerados e anulamos
			*/
			$sSqlEmpenhos   = "SELECT * from ( SELECT  z01_numcgm,    ";
			$sSqlEmpenhos  .= "                        z01_nome,      ";
			$sSqlEmpenhos  .= "                        rh01_regist,    ";
			$sSqlEmpenhos  .= "                        rh02_seqpes,    ";
			$sSqlEmpenhos  .= "                        rh05_recis,    ";
			$sSqlEmpenhos  .= "                        rh72_coddot,    ";
			$sSqlEmpenhos  .= "                        round(sum(case when rh73_pd = 2 then rh73_valor *-1 else rh73_valor end), 2) as rh73_valor,";
			$sSqlEmpenhos  .= "                        (select count(distinct rh72_sequencial) ";
			$sSqlEmpenhos  .= "                          from rhempenhofolha a";
			$sSqlEmpenhos  .= "                               inner join rhempenhofolharhemprubrica b on b.rh81_rhempenhofolha = a.rh72_sequencial ";
			$sSqlEmpenhos  .= "                               inner join rhempenhofolharubrica  c     on c.rh73_sequencial     = b.rh81_rhempenhofolharubrica ";
			$sSqlEmpenhos  .= "                          where c.rh73_seqpes = rh02_seqpes ";
			$sSqlEmpenhos  .= "                           and a.rh72_siglaarq = 'r20' ";
			$sSqlEmpenhos  .= "                           and a.rh72_mesusu   = {$oParam->iMesFolha} ";
			$sSqlEmpenhos  .= "                           and a.rh72_tipoempenho  = {$oParam->iTipo} ";
			$sSqlEmpenhos  .= "                           and a.rh72_anousu   = {$oParam->iAnoFolha} ";
			$sSqlEmpenhos  .= "                          ) as totalEmpenhos,";
			$sSqlEmpenhos  .= "                         (select count(distinct rh72_sequencial) ";
			$sSqlEmpenhos  .= "                          from rhempenhofolha a";
			$sSqlEmpenhos  .= "                               inner join rhempenhofolharhemprubrica b on b.rh81_rhempenhofolha = a.rh72_sequencial ";
			$sSqlEmpenhos  .= "                               inner join rhempenhofolharubrica  c     on c.rh73_sequencial     = b.rh81_rhempenhofolharubrica ";
			$sSqlEmpenhos  .= "                               inner join orcreservarhempenhofolha d    on a.rh72_sequencial     = d.o120_rhempenhofolha  ";
			$sSqlEmpenhos  .= "                          where c.rh73_seqpes = rh02_seqpes ";
			$sSqlEmpenhos  .= "                           and a.rh72_siglaarq = 'r20' ";
			$sSqlEmpenhos  .= "                           and a.rh72_mesusu   = {$oParam->iMesFolha} ";
			$sSqlEmpenhos  .= "                           and a.rh72_anousu   = {$oParam->iAnoFolha} ";
			$sSqlEmpenhos  .= "                           and a.rh72_tipoempenho  = {$oParam->iTipo} ";
			$sSqlEmpenhos  .= "                          ) as totalEmpenhosReserva";
			$sSqlEmpenhos  .= "                   from rhempenhofolha ";
			$sSqlEmpenhos  .= "                        inner join rhempenhofolharhemprubrica  on rh81_rhempenhofolha = rh72_sequencial ";
			$sSqlEmpenhos  .= "                        inner join rhempenhofolharubrica       on rh73_sequencial     = rh81_rhempenhofolharubrica ";
			$sSqlEmpenhos  .= "                        inner join rhpessoalmov                on rh73_seqpes         = rh02_seqpes          ";
			$sSqlEmpenhos  .= "                                                              and rh73_instit         = rh02_instit          ";
			$sSqlEmpenhos  .= "                        inner join rhpessoal                   on rh01_regist         = rh02_regist          ";
			$sSqlEmpenhos  .= "                        inner join cgm                         on rh01_numcgm         = z01_numcgm           ";
			$sSqlEmpenhos  .= "                        inner join rhpesrescisao               on rh05_seqpes         = rh02_seqpes          ";
			$sSqlEmpenhos  .= "                        left join rhempenhofolhaempenho        on rh72_sequencial     = rh76_rhempenhofolha  ";
			$sSqlEmpenhos  .= "                        left join orcreservarhempenhofolha     on rh72_sequencial     = o120_rhempenhofolha  ";
			$sSqlEmpenhos  .= "                  where rh76_rhempenhofolha is null";
			$sSqlEmpenhos  .= "                    and rh72_tipoempenho = {$oParam->iTipo}";
			$sSqlEmpenhos  .= "                    and rh73_instit      = ".db_getsession("DB_instit");
			$sSqlEmpenhos  .= "                    and rh73_tiporubrica = 1";
			$sSqlEmpenhos  .= "                    and rh72_anousu      = {$oParam->iAnoFolha}";
			$sSqlEmpenhos  .= "                    and rh72_mesusu      = {$oParam->iMesFolha}";
			$sSqlEmpenhos  .= "                    and rh72_siglaarq    = '{$oParam->sSigla}'";
			$sSqlEmpenhos  .= "                    and rh73_seqpes      in ({$sListaRescisoes})";
			$sSqlEmpenhos  .= "                  group by z01_numcgm,    ";
			$sSqlEmpenhos  .= "                           z01_nome,      ";
			$sSqlEmpenhos  .= "                           rh01_regist,    ";
			$sSqlEmpenhos  .= "                           rh02_seqpes,    ";
			$sSqlEmpenhos  .= "                           rh05_recis,    ";
			$sSqlEmpenhos  .= "                           rh72_coddot    ";
			$sSqlEmpenhos  .= "                order by  rh01_regist,rh72_coddot) as x ";
			$sSqlEmpenhos  .= "        WHERE rh73_valor <> 0 order by rh72_coddot";

			$rsDadosEmpenho = db_query($sSqlEmpenhos);
			$aEmpenhos      = db_utils::getCollectionByRecord($rsDadosEmpenho, false, false, true);
			$iTotalEmpenhos = count($aEmpenhos);

			/**
			 * Calculamos o total de descontos da folha
			*/
			$sSqlTotalDescontos  = "SELECT sum(round(rh73_valor,2)) as valor ";
			$sSqlTotalDescontos .= "  from rhempenhofolha ";
			$sSqlTotalDescontos .= "       inner join rhempenhofolharhemprubrica on rh81_rhempenhofolha        = rh72_sequencial";
			$sSqlTotalDescontos .= "       inner join rhempenhofolharubrica      on rh81_rhempenhofolharubrica = rh73_sequencial";
			$sSqlTotalDescontos .= " where rh72_tipoempenho = {$oParam->iTipo} ";
			$sSqlTotalDescontos     .= "   and rh73_pd          = 2 ";
			$sSqlTotalDescontos     .= "   and rh73_tiporubrica = 2 ";
			$sSqlTotalDescontos .= "  and rh72_siglaarq     = '{$oParam->sSigla}'";
			$sSqlTotalDescontos .= "  and rh72_mesusu       = '{$oParam->iMesFolha}'";
			$sSqlTotalDescontos .= "  and rh72_anousu       = '{$oParam->iAnoFolha}'";
			$sSqlTotalDescontos .= "  and rh73_instit      = ".db_getsession("DB_instit");
			$sSqlTotalDescontos .= "                    and rh73_seqpes      in ({$sListaRescisoes})";
			$rsTotalDescontos    = db_query($sSqlTotalDescontos);

			$oRetorno->nTotalDescontos = db_utils::fieldsMemory($rsTotalDescontos, 0)->valor;
			$oRetorno->itens = $aEmpenhos;

			break;

        case "getEmpenhosFinanceiros":

            $aSiglas = explode(',', $oParam->sSigla);
			$aEmpenhosFinanceiros = array();

			foreach ($aSiglas as $sSigla) {

				$oParam->sSigla = trim($sSigla);

                $sSqlEmpenhos   = "SELECT DISTINCT rh76_numemp                                                                                          ";
				$sSqlEmpenhos  .= "  from rhempenhofolha 																		        ";
				$sSqlEmpenhos  .= "       inner join rhempenhofolharhemprubrica  on rh81_rhempenhofolha = rh72_sequencial               ";
				$sSqlEmpenhos  .= "       inner join rhempenhofolharubrica       on rh73_sequencial     = rh81_rhempenhofolharubrica    ";
				$sSqlEmpenhos  .= "       inner join rhempenhofolhaempenho        on rh72_sequencial     = rh76_rhempenhofolha          ";
				$sSqlEmpenhos  .= " where rh72_tipoempenho = {$oParam->iTipo}                                                           ";
				$sSqlEmpenhos  .= "   and rh73_instit      = ".db_getsession("DB_instit"). "                                            ";
				$sSqlEmpenhos  .= "   and rh73_tiporubrica = 1																		    ";
				$sSqlEmpenhos  .= "   and rh72_anousu      = {$oParam->iAnoFolha}                                                       ";
				$sSqlEmpenhos  .= "   and rh72_mesusu      = {$oParam->iMesFolha}                                                       ";
				$sSqlEmpenhos  .= "   and rh72_siglaarq    = '{$oParam->sSigla}'                                                        ";

                if (isset($oParam->iSeqPes)) {
					$sSqlEmpenhos  .= " and rh73_seqpes     = {$oParam->iSeqPes}";
				} else if ($oParam->iTipo == 1) {
					$sSqlEmpenhos  .= " and rh72_seqcompl    = {$oParam->sSemestre}";
				}

				/**
				 * Inclui no where condicao das tabelas da previdencia
				 * caso seja o tipo 2 - previdencia e selecionou 1 ou mais tabelas
				 */
				if ( $oParam->iTipo == 2 && $oParam->sPrevidencia !== '' ) {
					$sSqlEmpenhos .= " and rh72_tabprev in({$oParam->sPrevidencia})  ";
				}

				$sSqlEmpenhos  .= " order by rh76_numemp";

				$rsDadosEmpenho = db_query($sSqlEmpenhos);
                for ($i = 0; $i < pg_num_rows($rsDadosEmpenho); $i++) {
                    $aEmpenhosFinanceiros[] = db_utils::fieldsMemory($rsDadosEmpenho,$i)->rh76_numemp;
                }

            }

            $oRetorno->empenhos_financeiros = count($aEmpenhosFinanceiros) > 1 ? implode(',',$aEmpenhosFinanceiros) : '';

            break;

	}

} catch (DBException $eErro){          // DB Exception

  db_fim_transacao(true);

  $oRetorno->status = 2;
  $oRetorno->message = urlencode($eErro->getMessage());

} catch (BusinessException $eErro){     // Business Exception

  db_fim_transacao(true);

  $oRetorno->status = 2;
  $oRetorno->message = urlencode($eErro->getMessage());

} catch (ParameterException $eErro){     // Parameter Exception

  db_fim_transacao(true);

  $oRetorno->status = 2;
  $oRetorno->message = urlencode($eErro->getMessage());

} catch (Exception $eErro){

  db_fim_transacao(true);

  $oRetorno->status = 2;
  $oRetorno->message = urlencode($eErro->getMessage());

}

echo $oJson->encode($oRetorno);

?>
