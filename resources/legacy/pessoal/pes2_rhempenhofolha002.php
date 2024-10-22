<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/**
 *
 * @author Iuri Guntchnigg
 * @revision $Author: dbtales.baz $
 * @version $Revision: 1.15 $
 */
require_once("fpdf151/pdf.php");
require_once("fpdf151/impcarne.php");

require_once("libs/db_utils.php");
require_once("libs/JSON.php");
require_once("libs/db_libpessoal.php");
require_once("classes/db_cfpess_classe.php");

$oDaoCfpess            = new cl_cfpess;

/**
 * Modelo de impressão de relatório empenho folha
 * Retorna false caso der erro na consulta
 */
$iTipoRelatorio = $oDaoCfpess->buscaCodigoRelatorio('empenhofolha', db_anofolha(), db_mesfolha());
if(!$iTipoRelatorio) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Modelo de impressão invalido, verifique parametros.');
}

$oJson       = new services_json();
$oParam      = $oJson->decode(str_replace("\\","",$_GET["json"]));

if(isset($oParam->aMatriculas)){
  $sMatriculas = implode(',', $oParam->aMatriculas);
}

if (isset($oParam->iTipoEmpenho) && $oParam->iTipoEmpenho == 2) {
  $sSqlEmpenhos = " SELECT
                    COUNT(*) over (partition by rh02_lota) as qtdlota,
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
                    end as rh25_codlotavinc, 
                    *
                    from
                    (
                    select		
                      rh02_lota,
                      rh25_codlotavinc as codlotavinc,
                      rh72_sequencial,
                      rh72_coddot,
                      o56_elemento,
                      substr(o56_descr, 0, 46) as o56_descr,
                      o40_descr,
                      o15_descr,
                      rh72_unidade,
                      rh72_orgao,
                      rh72_projativ,
                      rh72_anousu,
                      rh72_mesusu,
                      rh72_recurso,
                      rh72_siglaarq,
                      rh72_concarpeculiar,
                      o56_elemento as elemento,
                      o56_descr,
                      o120_orcreserva,
                      e60_codemp,
                      e60_anousu,
                      pc01_descrmater,
                      round(sum(case when rh73_pd = 2 then rh73_valor *-1 else rh73_valor end),
                      2) as rh73_valor
                    from
                      rhempenhofolha
                    inner join rhempenhofolharhemprubrica on
                      rh81_rhempenhofolha = rh72_sequencial
                    inner join rhempenhofolharubrica on
                      rh73_sequencial = rh81_rhempenhofolharubrica
                    inner join orctiporec on
                    o15_codigo = rh72_recurso
                    inner join orcelemento on
                      o56_codele = rh72_codele
                      and o56_anousu = rh72_anousu
                    inner join orcorgao on
                      rh72_orgao = o40_orgao
                      and rh72_anousu = o40_anousu
                    inner join orcunidade on
                      rh72_orgao = o41_orgao
                      and rh72_unidade = o41_unidade
                      and rh72_anousu = o41_anousu
                    inner join rhpessoalmov on
                      rh73_seqpes = rh02_seqpes
                      and rh73_instit = rh02_instit
                    left join rhempenhofolhaempenho on
                      rh72_sequencial = rh76_rhempenhofolha 
                      and rh76_lota = rh02_lota
                    left join empempenho on
                    rh76_numemp = e60_numemp
                    left join orcreservarhempenhofolha on
                      rh72_sequencial = o120_rhempenhofolha
                      and rh02_lota = o120_lota
                    left join rhlotavinc on
                      rh02_lota = rh25_codigo
                      and rh02_anousu = rh25_anousu
                      and rh72_projativ = rh25_projativ
                      and rh72_recurso = rh25_recurso
                      and rh25_codlotavinc = (
                      select
                        rh28_codlotavinc
                      from
                        rhlotavincele
                      where
                        rh25_codlotavinc = rh28_codlotavinc
                        and rh72_codele = rh28_codelenov)
                    left join rhelementoemp on
                      rh72_codele = rh38_codele
                      and rh38_anousu = rh72_anousu
                    left join rhelementoemppcmater on
                      rh38_seq = rh36_rhelementoemp
                    left join pcmater on
                      rh36_pcmater = pc01_codmater
                    where
                      rh72_tipoempenho = {$oParam->iTipo} 
                      and rh73_tiporubrica = 1
                      and rh72_anousu = {$oParam->iAnoFolha}
                      and rh72_mesusu = {$oParam->iMesFolha}
                      and rh72_siglaarq = '{$oParam->sSigla}'
                      and rh73_instit = ".db_getsession('DB_instit');
  
  if (!empty($sMatriculas)) {
    $sSqlEmpenhos .= " and rh02_regist in ( $sMatriculas ) ";
  }
  
  if (!empty($oParam->sSemestre)){
    $sSqlEmpenhos .= " and rh72_seqcompl = '{$oParam->sSemestre}' ";
  }
  
  if ( $oParam->sSigla == 'r48' ) {
    $sSqlEmpenhos .= " and rh72_seqcompl <> '0' ";
  }
  
  if (isset($oParam->sPrevidencia) && $oParam->sPrevidencia != "") {
    $sSqlEmpenhos .= " and rh72_tabprev in ($oParam->sPrevidencia) ";
  }
  
  $sSqlEmpenhos .= " group by
                      rh72_sequencial,
                      rh72_coddot,
                      rh02_lota,
                      rh25_codlotavinc,
                      rh72_codele,
                      o40_descr,
                      o15_descr,
                      e60_codemp,
                      e60_anousu,
                      pc01_descrmater,
                      o41_descr,
                      rh72_unidade,
                      rh72_orgao,
                      rh72_projativ,
                      rh72_programa,
                      rh72_funcao,
                      rh72_subfuncao,
                      rh72_mesusu,
                      rh72_anousu,
                      rh72_recurso,
                      rh72_siglaarq,
                      rh72_concarpeculiar,
                      rh72_tabprev,
                      o56_elemento,
                      o56_descr,
                      o120_orcreserva
                    order by
                      rh72_recurso,
                      rh72_orgao,
                      rh72_unidade,
                      rh72_projativ,
                      rh72_coddot,
                      rh72_codele ) as x
                    where
                    rh73_valor <> 0
                    order by 
                      rh72_recurso,
                      rh02_lota,
                      rh72_orgao,
                      rh72_unidade,
                      rh72_projativ,
                      elemento";
  $rsDadosEmpenho   = db_query($sSqlEmpenhos);

  $aEmpenhos        = db_utils::getColectionByRecord($rsDadosEmpenho);
  $aLinhasRelatorio = array();

  $rsCfPess        = $oDaoCfpess->sql_record($oDaoCfpess->sql_query_file(db_anofolha(), db_mesfolha(), db_getsession("DB_instit"), "r11_geraretencaoempenho"));
  $lMostraRetencao = db_utils::fieldsMemory($rsCfPess,0)->r11_geraretencaoempenho;
  $lLotacao = false;
  
    foreach($aEmpenhos as &$oEmpenho) { 
      if ($oEmpenho->qtdlota > 1 && $oEmpenho->rh25_codlotavinc != null ) { 
        $sqlDescontos = "SELECT 
                            rh72_siglaarq, 
                            rh72_anousu, 
                            rh72_mesusu, 
                            rh78_retencaotiporec,
                            round(sum(rh73_valor), 2) as valorretencao,
                            ROUND(SUM(SUM(rh73_valor)) OVER (), 2) AS total_valorretencao
                            from rhempenhofolha 
                                inner join rhempenhofolharhemprubrica on rh81_rhempenhofolha = rh72_sequencial 
                                inner join rhempenhofolharubrica on rh73_sequencial = rh81_rhempenhofolharubrica
                                inner join rhpessoalmov on rh73_seqpes = rh02_seqpes and rh73_instit = rh02_instit 
                                inner join  rhempenhofolharubricaretencao on rh78_rhempenhofolharubrica = rh73_sequencial 
                            where 
                                rh02_lota = {$oEmpenho->rh02_lota}
                                and rh72_tipoempenho = {$oParam->iTipo}
                                and rh73_tiporubrica = 2
                                and rh73_pd          = 2
                                and rh72_anousu = {$oParam->iAnoFolha}
                                and rh72_mesusu = {$oParam->iMesFolha}
                                and rh72_siglaarq = '{$oEmpenho->rh72_siglaarq}'
                            group by 
                                rh72_siglaarq,  
                                rh72_mesusu, 
                                rh72_anousu, 
                                rh78_retencaotiporec
                        order by valorretencao DESC";
        $rsDescontos = db_utils::getCollectionByRecord(db_query($sqlDescontos), false, false, true);
        if ((float)$oEmpenho->rh73_valor < (float)$rsDescontos[0]->total_valorretencao){	
          $aRetencoesLotacao = [];
          $aRetencoesPrincipais = [];
          $iSumRetencao = 0;
          foreach ($rsDescontos as $oRetencao) {
            if ($iSumRetencao + $oRetencao->valorretencao >= $oEmpenho->rh73_valor) {
              $aRetencoesLotacao = array_merge($aRetencoesLotacao, array_slice($rsDescontos, array_search($oRetencao, $rsDescontos)));
              break;
            }
            $oEmpenho->retencoescod[] = $oRetencao->rh78_retencaotiporec;
            $iSumRetencao += $oRetencao->valorretencao;
          }      
          if (count($aRetencoesLotacao) > 0) {
            $sqlSecundario = "SELECT
                               rh72_sequencial, 
                               round(sum(case when rh73_pd = 2 then rh73_valor *-1 else rh73_valor end),2) as rh73_valor 
                              from rhempenhofolha
                              inner join rhempenhofolharhemprubrica on
                                rh81_rhempenhofolha = rh72_sequencial
                              inner join rhempenhofolharubrica on
                                rh73_sequencial = rh81_rhempenhofolharubrica
                              inner join rhpessoalmov on
                                rh73_seqpes = rh02_seqpes and rh73_instit = rh02_instit
                              where 
                                rh02_lota = {$oEmpenho->rh02_lota}
                                and rh72_tipoempenho = {$oParam->iTipo}
                                and rh73_instit = ".db_getsession("DB_instit")."
                                and rh73_tiporubrica = 1
                                and rh72_anousu = {$oParam->iAnoFolha}
                                and rh72_mesusu = {$oParam->iMesFolha}
                                and rh72_siglaarq = '{$oEmpenho->rh72_siglaarq}'
                                and rh72_sequencial <> {$oEmpenho->rh72_sequencial} "; 
            if (!empty($oParam->sSemestre)){
              $sSqlEmpenhos .= " and rh72_seqcompl = '{$oParam->sSemestre}' ";
            }
            $sqlSecundario .= "group by rh72_sequencial order by rh73_valor desc limit 1";
            $sequencialSecundario = db_utils::fieldsMemory(db_query($sqlSecundario), 0)->rh72_sequencial;
            if ($sequencialSecundario) {
              $empenhos = array_column($aEmpenhos, 'rh72_sequencial');
              $iEmpenhoSecundario = array_search($sequencialSecundario, $empenhos);
              foreach ($aRetencoesLotacao as $oRetencoesLotacao) {
                $aEmpenhos[$iEmpenhoSecundario]->retencoescod[] = $oRetencoesLotacao->rh78_retencaotiporec;
              }
            }
          } 
        } else {          
          $oEmpenho->retencoescod = array_map(function($oRetencao) {
            return $oRetencao->rh78_retencaotiporec;
            }, $rsDescontos);
        }
      }
    }
    foreach ($aEmpenhos as &$oEmpenho){
      if (isset($oEmpenho->retencoescod) || $oEmpenho->qtdlota == '1' ) {
        $sSqlDadosRetencao   = "SELECT rh73_rubric,                                                                                         ";
        $sSqlDadosRetencao  .= "       rh27_descr,rh78_retencaotiporec,                                                                                          ";
        $sSqlDadosRetencao  .= "       round(sum(rh73_valor), 2) as valorretencao                                                           ";
        $sSqlDadosRetencao  .= "  from rhempenhofolha                                                                                       ";
        $sSqlDadosRetencao  .= "       inner join rhempenhofolharhemprubrica     on rh81_rhempenhofolha        = rh72_sequencial            ";
        $sSqlDadosRetencao  .= "       inner join rhempenhofolharubrica          on rh73_sequencial            = rh81_rhempenhofolharubrica ";
        $sSqlDadosRetencao  .= "       inner join rhpessoalmov                   on rh73_seqpes                = rh02_seqpes                ";
        $sSqlDadosRetencao  .= "                                                and rh73_instit                = rh02_instit                ";
        $sSqlDadosRetencao  .= "       inner join  rhempenhofolharubricaretencao on rh78_rhempenhofolharubrica = rh73_sequencial            ";
        $sSqlDadosRetencao  .= "       inner join rhrubricas                     on rh27_rubric                = rh73_rubric                ";
        $sSqlDadosRetencao  .= "                                                and rh73_instit                = rh27_instit                ";
        $sSqlDadosRetencao  .= "   where rh02_lota  = {$oEmpenho->rh02_lota}                                                    ";
        $sSqlDadosRetencao  .= "     and rh72_anousu = {$oParam->iAnoFolha}";
        $sSqlDadosRetencao  .= "     and rh72_mesusu = {$oParam->iMesFolha}";
        $sSqlDadosRetencao  .= "     and rh72_tipoempenho = {$oParam->iTipo}                                                                ";
        $sSqlDadosRetencao  .= "     and rh73_tiporubrica = 2                                                                               ";
        $sSqlDadosRetencao  .= "     and rh73_pd          = 2 and rh72_siglaarq = '{$oEmpenho->rh72_siglaarq}'                                                                               ";
        $sSqlDadosRetencao  .= "     and rh73_instit      = ".db_getsession('DB_instit');
        if (isset($oEmpenho->retencoescod)) {
          $sSqlDadosRetencao  .= " and rh78_retencaotiporec in (".implode(',', $oEmpenho->retencoescod).")";
        }
        $sSqlDadosRetencao  .= "   group by rh73_rubric,                                                                                    ";
        $sSqlDadosRetencao  .= "            rh27_descr,rh78_retencaotiporec                                                                                      ";
        $sSqlDadosRetencao  .= "   order by rh73_rubric                                                                                     ";
        $rsDadosEmpenho     = db_query($sSqlDadosRetencao);
        $aRetencoes         = db_utils::getColectionByRecord($rsDadosEmpenho);
  
        $oEmpenho->aDescontos = $aRetencoes;
      }
        $aLinhasRelatorio[]   = $oEmpenho;
    }
} else {
  $sSqlEmpenhos = "SELECT 
                    rh72_sequencial,
                    rh72_coddot,
                    o56_elemento,
                    o56_descr,
                    o40_descr,
                    o15_descr,
                    rh72_unidade,
                    rh72_orgao,
                    rh72_projativ,
                    rh72_anousu,
                    rh72_mesusu,
                    rh72_recurso,
                    rh72_siglaarq,
                    rh72_concarpeculiar,
                    e60_codemp,
                    e60_anousu,
                    pc01_descrmater,
                    round(sum(rh73_valor),2) as rh73_valor from (";
$sSqlEmpenhos  .= "SELECT rh72_sequencial,                                                                          ";
$sSqlEmpenhos  .= "       rh72_coddot,                                                                              ";
$sSqlEmpenhos  .= "       o56_elemento,                                                                             ";
$sSqlEmpenhos  .= "       substr(o56_descr, 0,46) as o56_descr,                                                     ";
$sSqlEmpenhos  .= "       o40_descr,                                                                                ";
$sSqlEmpenhos  .= "       o15_descr,                                                                                ";
$sSqlEmpenhos  .= "       rh72_unidade,                                                                             ";
$sSqlEmpenhos  .= "       rh72_orgao,                                                                               ";
$sSqlEmpenhos  .= "       rh72_projativ,                                                                            ";
$sSqlEmpenhos  .= "       rh72_anousu,                                                                              ";
$sSqlEmpenhos  .= "       rh72_mesusu,                                                                              ";
$sSqlEmpenhos  .= "       rh72_recurso,                                                                             ";
$sSqlEmpenhos  .= "       rh72_siglaarq,                                                                            ";
$sSqlEmpenhos  .= "       rh72_concarpeculiar,                                                                      ";
$sSqlEmpenhos  .= "       case when rh76_lota is null then e60_codemp end as e60_codemp,                            ";
$sSqlEmpenhos  .= "       case when rh76_lota is null then e60_anousu end as e60_anousu,                            ";
$sSqlEmpenhos  .= "       pc01_descrmater,                                                                          ";
$sSqlEmpenhos  .= "       round(sum(case when rh73_pd = 2 then rh73_valor *-1 else rh73_valor end), 2) as rh73_valor";
$sSqlEmpenhos  .= "  from rhempenhofolha                                                                            ";
$sSqlEmpenhos  .= "       inner join rhempenhofolharhemprubrica on rh81_rhempenhofolha = rh72_sequencial            ";
$sSqlEmpenhos  .= "       inner join rhempenhofolharubrica      on rh73_sequencial     = rh81_rhempenhofolharubrica ";
$sSqlEmpenhos  .= "       inner join orctiporec                 on o15_codigo          = rh72_recurso               ";
$sSqlEmpenhos  .= "       inner join orcorgao                   on rh72_orgao          = o40_orgao                  ";
$sSqlEmpenhos  .= "                                            and rh72_anousu         = o40_anousu                 ";
$sSqlEmpenhos  .= "       inner join rhpessoalmov               on rh73_seqpes         = rh02_seqpes                ";
$sSqlEmpenhos  .= "                                            and rh73_instit         = rh02_instit                ";
$sSqlEmpenhos  .= "       inner join orcelemento                on rh72_codele         = o56_codele                 ";
$sSqlEmpenhos  .= "                                            and rh72_anousu         = o56_anousu                 ";
$sSqlEmpenhos  .= "       left join rhempenhofolhaempenho       on rh72_sequencial     = rh76_rhempenhofolha        ";
$sSqlEmpenhos  .= "       left join empempenho                  on rh76_numemp         = e60_numemp                 ";
$sSqlEmpenhos  .= "       left join rhelementoemp               on rh72_codele         = rh38_codele                ";
$sSqlEmpenhos  .= "                                            and rh38_anousu         = rh72_anousu                ";
$sSqlEmpenhos  .= "       left join  rhelementoemppcmater       on rh38_seq            = rh36_rhelementoemp         ";
$sSqlEmpenhos  .= "       left join pcmater                     on rh36_pcmater        = pc01_codmater              ";
$sSqlEmpenhos  .= "   where rh72_tipoempenho = {$oParam->iTipo}                                                     ";
$sSqlEmpenhos  .= "     and rh73_tiporubrica = 1                                                                    ";
$sSqlEmpenhos  .= "     and rh72_anousu      = {$oParam->iAnoFolha}                                                 ";
$sSqlEmpenhos  .= "     and rh72_mesusu      = {$oParam->iMesFolha}                                                 ";
$sSqlEmpenhos  .= "     and rh72_siglaarq    = '{$oParam->sSigla}'                                                  ";
$sSqlEmpenhos  .= "     and (rh76_lota is null or rh76_lota = rh02_lota)                                            ";
$sSqlEmpenhos  .= "     and rh73_instit      = ".db_getsession('DB_instit') ;

if (!empty($sMatriculas)) {
  $sSqlEmpenhos  .= "   and rh02_regist     in ( $sMatriculas )";
}

if (!empty($oParam->sSemestre)){
  $sSqlEmpenhos  .= "   and rh72_seqcompl    = '{$oParam->sSemestre}'";
}

if ( $oParam->sSigla == 'r48' ) {
	$sSqlEmpenhos    .= " and rh72_seqcompl <> '0' ";
}

if (isset($oParam->sPrevidencia) && $oParam->sPrevidencia != "") {
  $sSqlEmpenhos  .= "     and rh72_tabprev  in ($oParam->sPrevidencia)";
}

$sSqlEmpenhos  .= "   group by rh72_sequencial,                                               ";
$sSqlEmpenhos  .= "            rh72_coddot,                                                   ";
$sSqlEmpenhos  .= "            o56_elemento,                                                  ";
$sSqlEmpenhos  .= "            o56_descr,                                                     ";
$sSqlEmpenhos  .= "            o40_descr,                                                     ";
$sSqlEmpenhos  .= "            o15_descr,                                                     ";
$sSqlEmpenhos  .= "            rh72_unidade,                                                  ";
$sSqlEmpenhos  .= "            rh72_orgao,                                                    ";
$sSqlEmpenhos  .= "            rh72_projativ,                                                 ";
$sSqlEmpenhos  .= "            rh72_mesusu,                                                   ";
$sSqlEmpenhos  .= "            rh72_anousu,                                                   ";
$sSqlEmpenhos  .= "            rh72_recurso,                                                  ";
$sSqlEmpenhos  .= "            rh72_siglaarq,                                                 ";
$sSqlEmpenhos  .= "            rh72_concarpeculiar,                                           ";
$sSqlEmpenhos  .= "            e60_codemp,                                                    ";
$sSqlEmpenhos  .= "            e60_anousu,                                                    ";
$sSqlEmpenhos  .= "            rh76_lota,                                                     ";
$sSqlEmpenhos  .= "            pc01_descrmater                                                ";
$sSqlEmpenhos  .= " order by   rh72_recurso,rh72_orgao,rh72_unidade,rh72_projativ,o56_elemento";
$sSqlEmpenhos  .= ") x group by
                        rh72_sequencial,
                        rh72_coddot,
                        o56_elemento,
                        o56_descr,
                        o40_descr,
                        o15_descr,
                        rh72_unidade,
                        rh72_orgao,
                        rh72_projativ,
                        rh72_mesusu,
                        rh72_anousu,
                        rh72_recurso,
                        rh72_siglaarq,
                        rh72_concarpeculiar,
                        e60_codemp,
                        e60_anousu,
                        pc01_descrmater
                      order by
                        rh72_recurso,
                        rh72_orgao,
                        rh72_unidade,
                        rh72_projativ,
                        o56_elemento";
$rsDadosEmpenho   = db_query($sSqlEmpenhos);
$aEmpenhos        = db_utils::getColectionByRecord($rsDadosEmpenho);
$aLinhasRelatorio = array();

$rsCfPess        = $oDaoCfpess->sql_record($oDaoCfpess->sql_query_file(db_anofolha(), db_mesfolha(), db_getsession("DB_instit"), "r11_geraretencaoempenho"));
$lMostraRetencao = db_utils::fieldsMemory($rsCfPess,0)->r11_geraretencaoempenho;
$lLotacao = false;
  foreach($aEmpenhos as $oEmpenho) {

      $sSqlDadosRetencao   = "SELECT rh73_rubric,                                                                                         ";
      $sSqlDadosRetencao  .= "       rh27_descr,rh78_retencaotiporec,                                                                                          ";
      $sSqlDadosRetencao  .= "       round(sum(rh73_valor), 2) as valorretencao                                                           ";
      $sSqlDadosRetencao  .= "  from rhempenhofolha                                                                                       ";
      $sSqlDadosRetencao  .= "       inner join rhempenhofolharhemprubrica     on rh81_rhempenhofolha        = rh72_sequencial            ";
      $sSqlDadosRetencao  .= "       inner join rhempenhofolharubrica          on rh73_sequencial            = rh81_rhempenhofolharubrica ";
      $sSqlDadosRetencao  .= "       inner join rhpessoalmov                   on rh73_seqpes                = rh02_seqpes                ";
      $sSqlDadosRetencao  .= "                                                and rh73_instit                = rh02_instit                ";
      $sSqlDadosRetencao  .= "       inner join  rhempenhofolharubricaretencao on rh78_rhempenhofolharubrica = rh73_sequencial            ";
      $sSqlDadosRetencao  .= "       inner join rhrubricas                     on rh27_rubric                = rh73_rubric                ";
      $sSqlDadosRetencao  .= "                                                and rh73_instit                = rh27_instit                ";
      $sSqlDadosRetencao  .= "   where rh72_sequencial  = {$oEmpenho->rh72_sequencial}                                                    ";
      $sSqlDadosRetencao  .= "     and rh72_tipoempenho = {$oParam->iTipo}                                                                ";
      $sSqlDadosRetencao  .= "     and rh73_tiporubrica = 2                                                                               ";
      $sSqlDadosRetencao  .= "     and rh73_pd          = 2 and rh72_siglaarq    = '{$oParam->sSigla}'                                                                               ";
      $sSqlDadosRetencao  .= "     and rh73_instit      = ".db_getsession('DB_instit');
      $sSqlDadosRetencao  .= "   group by rh73_rubric,                                                                                    ";
      $sSqlDadosRetencao  .= "            rh27_descr,rh78_retencaotiporec                                                                                      ";
      $sSqlDadosRetencao  .= "   order by rh73_rubric                                                                                     ";

      $rsDadosEmpenho     = db_query($sSqlDadosRetencao);
      $aRetencoes         = db_utils::getColectionByRecord($rsDadosEmpenho);

      $oEmpenho->aDescontos = $aRetencoes;
      $aLinhasRelatorio[]   = $oEmpenho;
  }
}

switch ($oParam->sSigla){
	case "r14":
		$sPonto = "Salário";
	break;
  case "r48":
  	$sPonto = "Complementar";
  break;
  case "r35":
    $sPonto = "13o Salário";
  break;
  case "r20":
  	$sPonto = "Rescisão";
  break;
  case "r22":
  	$sPonto = "Adiantamento";
  break;
}

switch ($oParam->iTipo) {
	case 1:
	  $sTipoPonto = "Salário";
	break;
  case 2:
    $sTipoPonto = "Previdência";
  break;
  case 3:
    $sTipoPonto = "FGTS";
  break;
}

$head2 = "Empenhos para a Folha";
$head3 = "Mês: {$oParam->iMesFolha}";
$head4 = "Ano: {$oParam->iAnoFolha}";
$head5 = "Ponto: {$sPonto}";
$head6 = "Tipo: {$sTipoPonto}";
$head7 = "Tipo de empenho : " . ($oParam->iTipoEmpenho == 2 ? "Lotação" : "Dotação");

$pdf = new PDF("L");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false,1);
$pdf->AddPage();
$pdf->setfillcolor(244);
$sFonte = "arial";
$iFonte = 7;
$iAlt   = 4;

$oDocumentoPDF = new db_impcarne($pdf, $iTipoRelatorio);
$oDocumentoPDF->lRetencao        = $oParam->lRetencao;
$oDocumentoPDF->aLinhasRelatorio = $aLinhasRelatorio;
$oDocumentoPDF->lLotacao = isset($oParam->iTipoEmpenho) && $oParam->iTipoEmpenho == 2 ? true : false;
$oDocumentoPDF->imprime();
$oDocumentoPDF->objpdf->output();