<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
$clrotulo = new rotulocampo;
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

$clgerasql = new cl_gera_sql_folha;
$clgerasql->inicio_rh = false;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

function gerarDados($ano, $mes, $folha, $rubrs, $lRecisao)
{
  if ($lRecisao) {
    $sGrupo = "rh02_seqpes";
  } else {
    $sGrupo = "rh02_lota";
  }

  $sSqlEmpenhos = " SELECT
  COUNT(*) over (partition by {$sGrupo}, rh72_siglaarq) as qtdlota,
  case
    when COUNT(*) over (partition by {$sGrupo}, rh72_siglaarq) > 1
    and COUNT(codlotavinc) over (partition by {$sGrupo}, rh72_siglaarq) = 0
    and MIN(o56_elemento::bigint) over (partition by {$sGrupo}, rh72_siglaarq) = o56_elemento::bigint 
    and MIN(rh72_recurso) over (partition by {$sGrupo}, rh72_siglaarq) = rh72_recurso then o56_elemento::bigint
    when COUNT(*) over (partition by {$sGrupo}, rh72_siglaarq) > 1
    and (COUNT(codlotavinc) over (partition by {$sGrupo}, rh72_siglaarq) > 0
    and (row_number() over (partition by {$sGrupo}, rh72_siglaarq order by rh73_valor desc) = 1))
    or (COUNT(codlotavinc) over (partition by {$sGrupo}, rh72_siglaarq) = 1)
    or COUNT(*) over (partition by {$sGrupo}, rh72_siglaarq) = 1 then codlotavinc
    else null
  end as rh25_codlotavinc,
  *
  from
  (
  select		
    {$sGrupo},
    rh25_codlotavinc as codlotavinc,
    rh72_sequencial,
    rh72_coddot,
    o56_elemento,
    substr(o56_descr,
    0,
    46) as o56_descr,
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
    rh72_sequencial = rh76_rhempenhofolha and rh76_lota = rh02_lota
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
    rh36_pcmater = pc01_codmater";
  if ($lRecisao) {
    $sSqlEmpenhos .= "
    join rhpesrescisao on 
      rh02_seqpes = rh05_seqpes 
      and rh72_mesusu = extract(month from rh05_recis)
      and rh72_anousu = extract(year from rh05_recis)";
  }
  $sSqlEmpenhos .= " 
  where
    rh72_tipoempenho = 1
    and rh73_tiporubrica = 1
    and rh72_anousu = {$ano}
    and rh72_mesusu = {$mes}
    and rh72_siglaarq in ({$folha})
    and rh73_instit = " . db_getsession('DB_instit');
  $sSqlEmpenhos .= " group by
    rh72_sequencial,
    rh72_coddot,
    {$sGrupo},
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
    {$sGrupo},
    rh72_orgao,
    rh72_unidade,
    rh72_projativ,
    elemento";
  $rsDadosEmpenho = db_query($sSqlEmpenhos);
  $aEmpenhos      = db_utils::getColectionByRecord($rsDadosEmpenho);

  foreach ($aEmpenhos as &$oEmpenho) {
    if ($oEmpenho->qtdlota > 1 && $oEmpenho->rh25_codlotavinc != null) {
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
                            {$sGrupo} = {$oEmpenho->$sGrupo}
                            and rh72_tipoempenho = 1
                            and rh73_tiporubrica = 2
                            and rh73_pd          = 2
                            and rh72_anousu = {$ano}
                            and rh72_mesusu = {$mes}
                            and rh72_siglaarq = '{$oEmpenho->rh72_siglaarq}'
                        group by 
                            rh72_siglaarq,  
                            rh72_mesusu, 
                            rh72_anousu, 
                            rh78_retencaotiporec
                    order by valorretencao DESC";
      $rsDescontos = db_utils::getCollectionByRecord(db_query($sqlDescontos), false, false, true);
      if ((float)$oEmpenho->rh73_valor < (float)$rsDescontos[0]->total_valorretencao) {
        $aRetencoesLotacao = [];
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
                            $sGrupo = {$oEmpenho->$sGrupo}
                            and rh72_tipoempenho = 1
                            and rh73_instit = " . db_getsession("DB_instit") . "
                            and rh73_tiporubrica = 1
                            and rh72_anousu = {$ano}
                            and rh72_mesusu = {$mes}
                            and rh72_siglaarq = '{$oEmpenho->rh72_siglaarq}'
                            and rh72_sequencial <> {$oEmpenho->rh72_sequencial} 
                            group by rh72_sequencial order by rh73_valor desc limit 1";
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
        $oEmpenho->retencoescod = array_map(function ($oRetencao) {
          return $oRetencao->rh78_retencaotiporec;
        }, $rsDescontos);
      }
    }
  }
  $aRecurso = array();
  foreach ($aEmpenhos as &$oEmpenho) {
    if (isset($oEmpenho->retencoescod) || $oEmpenho->qtdlota == '1') {
      
      $sSqlDescontos = "SELECT
              rh02_regist,
              rh73_rubric,
              rh27_descr,
              case when rh73_pd = 1 then 1 else rh73_tiporubrica end as tipo,
              round(sum(rh73_valor), 2) as valordesconto
          from
              rhempenhofolha
          inner join rhempenhofolharhemprubrica on
              rh81_rhempenhofolha = rh72_sequencial
          inner join rhempenhofolharubrica on
              rh73_sequencial = rh81_rhempenhofolharubrica
          inner join rhpessoalmov on
              rh73_seqpes = rh02_seqpes
              and rh73_instit = rh02_instit
          left join rhrubricas on
              rh73_rubric = rh27_rubric
              and rh73_instit = rh27_instit
          left join rhempenhofolharubricaretencao on
              rh78_rhempenhofolharubrica = rh73_sequencial
          where
              rh72_tipoempenho = 1
              and rh73_tiporubrica = 2
              and rh73_pd = 2
              and rh72_anousu = {$ano}
              and rh72_mesusu = {$mes}
              and rh72_siglaarq = '{$oEmpenho->rh72_siglaarq}'
              and $sGrupo = {$oEmpenho->$sGrupo}";

      if (isset($oEmpenho->retencoescod)) {
        $sSqlDescontos  .= " and (rh78_retencaotiporec in (" . implode(',', $oEmpenho->retencoescod) . ") or (rh73_pd = 1 and rh73_tiporubrica = 2))";
      } else {
        $sSqlDescontos  .= " and rh72_sequencial = {$oEmpenho->rh72_sequencial} ";
      }
      if (trim($rubrs) != "") {
        $sSqlDescontos  .= " and rh73_rubric in ('" . str_replace(",", "','", $rubrs) . "') ";
      }
      $sSqlDescontos .= " group by rh02_regist, rh73_rubric, rh27_descr, rh73_tiporubrica, rh73_pd";
      $aDescontos = db_utils::getCollectionByRecord(db_query($sSqlDescontos), false, false, true);

      foreach ($aDescontos as $oDesconto) {
        $oRegistro = new stdClass;
        $oRegistro->regist = $oDesconto->rh02_regist;
        $oRegistro->recurso = $oEmpenho->rh72_recurso;
        $oRegistro->descricaorecurso = $oEmpenho->o15_descr;
        $oRegistro->codrubrica = $oDesconto->rh73_rubric;
        $oRegistro->descrrubrica = urldecode($oDesconto->rh27_descr);
        $oRegistro->valordescfinal = $oDesconto->valordesconto;
        $oRegistro->tipo = $oDesconto->tipo;
        $aRecurso[] = $oRegistro;
      }
    }
  }
  return $aRecurso;
}

$dbwhere = " rh23_rubric is null ";
$dbwhererubs = "";

if(trim($recrs) != ""){
  $dbwhere .= " and rh25_recurso in (".$recrs.")";
}

if(trim($rubrs) != ""){
  $dbwhererubs = " and #s#_rubric in ('".str_replace(",","','",$rubrs)."')";
}

$arr_pontos = explode(",",$ponts);
$varSQL = "";
if ($tipoEmpenho == 2){
  $subSqlLotacao = "";
  $pontos = "";
  $complementar = false;
}

$headPontos = "";

for($i=0; $i<6; $i++){
  $valor = "";
  if( isset($arr_pontos[$i]) && trim($arr_pontos[$i]) != "" ){
    $valor = $arr_pontos[$i];
  }else if( count($arr_pontos) == 1 && trim($arr_pontos[0]) == "" ){
    $valor = "$i";
  }
  switch ( $valor ) {
    case "0" :
               $headPontos .= (trim($varSQL) != "" ? ", " : "") . "Salário";
               $varSQL .= (trim($varSQL) != "" ? " union all" : "") . " ( " . $clgerasql->gerador_sql(
                                                                                                   "r14", $ano, $mes, null, null,
                                                                                                   "#s#_rubric, #s#_valor, #s#_regist, #s#_pd, #s#_anousu, #s#_mesusu",
                                                                                                   "#s#_rubric",
                                                                                                   "#s#_pd <> 3 " . $dbwhererubs
                                                                                                  ) . " ) ";
               $sigla = (isset($sigla) ? $sigla : "r14");
      if ($tipoEmpenho == 2){
        $pontos .= "'r14',";
        $subSqlLotacao .= (trim($subSqlLotacao) != "" ? " union all" : "") . " ( " . $clgerasql->gerador_sql("r14", $ano, $mes, null, null, "#s#_regist as regist, #s#_rubric as rubric", "#s#_rubric", "#s#_pd <> 3 " . $dbwhererubs) . " ) ";
      }
               break;
    case "1" :
               $headPontos .= (trim($varSQL) != "" ? ", " : "") . "Adiantamento";
               $varSQL .= (trim($varSQL) != "" ? " union all" : "") . " ( " . $clgerasql->gerador_sql(
                                                                                                   "r22", $ano, $mes, null, null,
                                                                                                   "#s#_rubric, #s#_valor, #s#_regist, #s#_pd, #s#_anousu, #s#_mesusu",
                                                                                                   "#s#_rubric",
                                                                                                   "#s#_pd <> 3" . $dbwhererubs
                                                                                                  ) . " ) ";
               $sigla = (isset($sigla) ? $sigla : "r22");
      if ($tipoEmpenho == 2){
        $pontos .= "'r22',";
        $subSqlLotacao .= (trim($subSqlLotacao) != "" ? " union all" : "") . " ( " . $clgerasql->gerador_sql("r22", $ano, $mes, null, null, "#s#_regist as regist, #s#_rubric as rubric", "#s#_rubric", "#s#_pd <> 3 " . $dbwhererubs) . " ) ";
      }
               break;
    case "2" :
               $headPontos .= (trim($varSQL) != "" ? ", " : "") . "Férias";
               $varSQL .= (trim($varSQL) != "" ? " union all " : "") . " ( " . $clgerasql->gerador_sql(
                                                                                                   "r31", $ano, $mes, null, null,
                                                                                                   "#s#_rubric, #s#_valor, #s#_regist, #s#_pd, #s#_anousu, #s#_mesusu",
                                                                                                   "#s#_rubric",
                                                                                                   "#s#_pd <> 3 " . $dbwhererubs
                                                                                                  ) . " ) ";
               $sigla = (isset($sigla) ? $sigla : "r31");
      if ($tipoEmpenho == 2){
        $pontos .= "'r31',";
        $subSqlLotacao .= (trim($subSqlLotacao) != "" ? " union all" : "") . " ( " . $clgerasql->gerador_sql("r31", $ano, $mes, null, null, "#s#_regist as regist, #s#_rubric as rubric", "#s#_rubric", "#s#_pd <> 3 " . $dbwhererubs) . " ) ";
      }
               break;
    case "3" :
               $headPontos .= (trim($varSQL) != "" ? ", " : "") . "Rescisão";
               $varSQL .= (trim($varSQL) != "" ? " union all " : "") . " ( " . $clgerasql->gerador_sql(
                                                                                                   "r20", $ano, $mes, null, null,
                                                                                                   "#s#_rubric, #s#_valor, #s#_regist, #s#_pd, #s#_anousu, #s#_mesusu",
                                                                                                   "#s#_rubric",
                                                                                                   "#s#_pd <> 3 " . $dbwhererubs
                                                                                                  ) . " ) ";
               $sigla = (isset($sigla) ? $sigla : "r20");
      if ($tipoEmpenho == 2){
        $pontos .= "'r20',";
        $subSqlLotacao .= (trim($subSqlLotacao) != "" ? " union all" : "") . " ( " . $clgerasql->gerador_sql("r20", $ano, $mes, null, null, "#s#_regist as regist, #s#_rubric as rubric", "#s#_rubric", "#s#_pd <> 3 " . $dbwhererubs) . " ) ";
      }
               break;
    case "4" :
               $headPontos .= (trim($varSQL) != "" ? ", " : "") . "Saldo do 13o.";
               $varSQL .= (trim($varSQL) != "" ? " union all " : "") . " ( " . $clgerasql->gerador_sql(
                                                                                                   "r35", $ano, $mes, null, null,
                                                                                                   "#s#_rubric, #s#_valor, #s#_regist, #s#_pd, #s#_anousu, #s#_mesusu",
                                                                                                   "#s#_rubric",
                                                                                                   "#s#_pd <> 3 " . $dbwhererubs
                                                                                                  ) . " ) ";
               $sigla = (isset($sigla) ? $sigla : "r35");
      if ($tipoEmpenho == 2){
        $pontos .= "'r35',";
        $subSqlLotacao .= (trim($subSqlLotacao) != "" ? " union all" : "") . " ( " . $clgerasql->gerador_sql("r35", $ano, $mes, null, null, "#s#_regist as regist, #s#_rubric as rubric", "#s#_rubric", "#s#_pd <> 3 " . $dbwhererubs) . " ) ";
      }
               break;
    case "5" :
               $headPontos .= (trim($varSQL) != "" ? ", " : "") . "Complementar";
               $varSQL .= (trim($varSQL) != "" ? " union all " : "") . " ( " . $clgerasql->gerador_sql(
                                                                                                   "r48", $ano, $mes, null, null,
                                                                                                   "#s#_rubric, #s#_valor, #s#_regist, #s#_pd, #s#_anousu, #s#_mesusu",
                                                                                                   "#s#_rubric",
                                                                                                   "#s#_pd <> 3 " . $dbwhererubs
                                                                                                  ) . " ) ";
               $sigla = (isset($sigla) ? $sigla : "r48");
      if ($tipoEmpenho == 2){
        $pontos .= "'r48',";
        $complementar = true;
        $subSqlLotacao .= (trim($subSqlLotacao) != "" ? " union all" : "") . " ( " . $clgerasql->gerador_sql("r48", $ano, $mes, null, null, "#s#_regist as regist, #s#_rubric as rubric", "#s#_rubric", "#s#_pd <> 3 " . $dbwhererubs) . " ) ";
      }
               break;
  }
}

if(count($arr_pontos) == 1 && trim($arr_pontos[0]) == ""){
  $headPontos = "Todos os pontos";
}
$clgerasql->inicio_rh = true;
$clgerasql->inner_rel = false;
$clgerasql->inner_exe = true;
$clgerasql->inner_org = true;
$clgerasql->inner_vin = true;
$clgerasql->inner_pro = true;
$clgerasql->inner_rec = true;
$clgerasql->usar_rub  = true;
$clgerasql->usar_rel  = true;
$clgerasql->usar_lot  = true;
$clgerasql->usar_exe  = true;
$clgerasql->usar_org  = true;
$clgerasql->usar_vin  = true;
$clgerasql->usar_pro  = true;
$clgerasql->usar_rec  = true;
$clgerasql->subsql    = $varSQL;
$clgerasql->subsqlano = $sigla."_anousu";
$clgerasql->subsqlmes = $sigla."_mesusu";
$clgerasql->subsqlreg = $sigla."_regist";
$clgerasql->subsqlrub = $sigla."_rubric";
$clgerasql->trancaGer = true;

if ($tipoEmpenho == 2) {
  $siglas = "(".str_replace("','", "'),('", rtrim($pontos,',')).")";
  $aPontos = array("r14" => "Salário", "r22" => "Adiantamento", "r31" => "Férias", "r20" => "Rescisão", "r35" => "Saldo do 13o.", "r48" => "Complementar");
  $sSqlPontos = "SELECT 
                  STRING_AGG(pontos.sigla, ',') as pontos
                FROM                   
                  (VALUES {$siglas}) AS pontos(sigla)
                LEFT JOIN 
                  rhempenhofolha
                ON 
                  rhempenhofolha.rh72_siglaarq = pontos.sigla
                  AND rhempenhofolha.rh72_mesusu = {$mes}
                  AND rhempenhofolha.rh72_anousu = {$ano}
                WHERE 
                  rhempenhofolha.rh72_siglaarq IS NULL";
  $siglas = db_utils::fieldsMemory(db_query($sSqlPontos), 0)->pontos;

if ($siglas != "") {
  $siglas = str_replace(array_keys($aPontos), array_values($aPontos), $siglas);
  $msgErro = "Para gerar o relatório por Lotação é necessário processar a geração dos empenhos da folha (Mod. Pessoal > Procedimentos > Geração de Empenhos (Novo) > Folha ) e o(s) seguinte(s) ponto(s) não está(ão) processado(s): ". str_replace(array_keys($aPontos), array_values($aPontos), $siglas);
  db_redireciona('db_erros.php?fechar=true&db_erro='.$msgErro);
}

$aFolha = explode(',', rtrim($pontos,','));
$lRecisao = false;
if (($chave = array_search("'r20'", $aFolha)) !== false) {
  unset($aFolha[$chave]);
  $lRecisao = true;
}

$sFolha = implode(",", $aFolha);

$aRecurso = gerarDados($ano, $mes, $sFolha, $rubrs, false);

if ($lRecisao) {
  $aRecurso = array_merge($aRecurso, gerarDados($ano, $mes, "'r20'", $rubrs, true));
  
}

if (count($aRecurso) == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nao existem lancamentos no periodo de ' . $mes . ' / ' . $ano . $erroajuda . ".");
}

$sSqlRetExtras = $clgerasql->gerador_sql("", $ano, $mes, null, null,
                                    "rh25_recurso as recurso, o15_descr, rh27_descr, {$sigla}_regist as regist, {$sigla}_pd as tipo, {$sigla}_rubric as rubrica, round(sum({$sigla}_valor),2) as valor",
                                     "{$sigla}_rubric, rh25_recurso",
                                    $dbwhere . " and {$sigla}_pd <> 3
                                     group by rh25_recurso, o15_descr, {$sigla}_regist, {$sigla}_pd, {$sigla}_rubric, rh27_descr");
$retExtras = db_utils::getCollectionByRecord(db_query($sSqlRetExtras), false, false, true);

foreach($retExtras as $oRetencao) {
  $existe = false;
  foreach($aRecurso as &$oRegistro) {
    if ($oRetencao->rubrica == $oRegistro->codrubrica && $oRetencao->tipo == $oRegistro->tipo && $oRetencao->regist == $oRegistro->regist) {
      $existe = true;
      break;
    }
  }
  if (!$existe) {
    $registro = new stdClass;
    $registro->recurso = $oRetencao->recurso;
    $registro->descricaorecurso = urldecode($oRetencao->o15_descr);
    $registro->codrubrica = $oRetencao->rubrica;
    $registro->descrrubrica = urldecode($oRetencao->rh27_descr);
    $registro->valordescfinal = $oRetencao->valor;
    $registro->tipo = $oRetencao->tipo;
    $aRecurso[] = $registro;
  }
}

$aResultado = array();
foreach($aRecurso as $ret){
  if (isset($aResultado[$ret->recurso."|".$ret->codrubrica])) {
    $aResultado[$ret->recurso."|".$ret->codrubrica]->valordescfinal += $ret->valordescfinal;
  } else {
    $aResultado[$ret->recurso."|".$ret->codrubrica] = $ret;
  }
}

$aRecurso = $aResultado;
    $aResult = array();

    foreach ($aRecurso as $key => $dados) {
        $chaverecurso = $dados->codrubrica;
        if(trim($recrs) != ""){
          if (in_array($dados->recurso, explode(',', $recrs))) {
            $aResult[$chaverecurso][$dados->recurso] = $dados;
          }
        } else {
          $aResult[$chaverecurso][$dados->recurso] = $dados;
        }
    }

    ksort($aResult);

    function ordenaRegime($a, $b)
    {
        return $a->recurso - $b->recurso;
    }

    foreach ($aResult as &$aRegime) {
        uasort($aRegime, 'ordenaRegime');
    }
    $aResultado = array();
    foreach ($aResult as $subArray) {
        foreach ($subArray as $obj) {
            $aResultado[] = $obj;
        }
    }
} else {
$sqlFinal = $clgerasql->gerador_sql("",
                                    $ano, $mes, null, null,
                                    "rh25_recurso,
                                     o15_descr,
                                     rh27_descr,
				                             ".$sigla."_pd as tipo,
                                     ".$sigla."_rubric as rubrica,
                                     round(sum(".$sigla."_valor),2) as valor",
                                    $sigla."_rubric, rh25_recurso",
                                    $dbwhere .
                                    "group by rh25_recurso,
                                              o15_descr,
					                                    ".$sigla."_pd,
                                              ".$sigla."_rubric,
                                              rh27_descr"
                                   );
$result = $clgerasql->sql_record($sqlFinal);
if($result === false || ($result !== false && $clgerasql->numrows_exec == 0)){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados no período de '.$mes.' / '.$ano);
}
}
$head3 = "Retenções e Consignações da Folha";
$head5 = "Período : " . $mes . " / " . $ano;
$head7 = "Pontos: " . $headPontos;
$head8 = "Tipo de empenho : " . ($tipoEmpenho == 2 ? "Lotação" : "Dotação");

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;

$rubri_ant = "";
$total_rub = 0;
$total_ger = 0;
$cor = 1;
$proxpag = true;
if ($tipoEmpenho == 2) {
    foreach ($aResultado as $registro) {

        if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
            $pdf->addpage();
            $pdf->setfont('arial','b',7);
            $pdf->cell(15,$alt,'RUBRICA',1,0,"C",1);
            $pdf->cell(75,$alt,'DESCRIÇÃO',1,0,"C",1);
            $pdf->cell(75,$alt,'RECURSO',1,0,"C",1);
            $pdf->cell(25,$alt,'DESCONTO',1,1,"C",1);
            $troca = 0;
            $cor = 1;
            $proxpag = true;
        }
        $cor = 0;
        if($rubri_ant != $registro->codrubrica || $proxpag == true){
            if($rubri_ant != $registro->codrubrica && $rubri_ant != ""){
            $pdf->setfont('arial','b',6);
            $pdf->cell(165,$alt,"Total da rubrica ",0,0,"R",1);
            $pdf->cell( 25,$alt,db_formatar($total_rub, "f"),0,1,"R",1);
            $pdf->ln(2);
            $total_rub = 0;
            $cor = 0;
            }
            $pdf->setfont('arial','',6);
            $pdf->cell(15,$alt,$registro->codrubrica,0,0,"C",$cor);
            $pdf->cell(75,$alt,$registro->descrrubrica,0,0,"L",$cor);
        }else{
            $pdf->setfont('arial','',6);
            $pdf->cell(15,$alt,"",0,0,"C",$cor);
            $pdf->cell(75,$alt,"",0,0,"L",$cor);
        }
        
        $pdf->cell(75,$alt,$registro->recurso . " - " .$registro->descricaorecurso,0,0,"L",$cor);
        $pdf->cell(25,$alt,db_formatar($registro->valordescfinal,"f"),0,1,"R",$cor);
        $total_rub += $registro->valordescfinal;

        $chave = $registro->recurso . " - " .$registro->descricaorecurso;
        if (isset($totalRecurso[$chave])) {
            $totalRecurso[$chave] += $registro->valordescfinal;
        } else {
            $totalRecurso[$chave] = $registro->valordescfinal;
        }

        if($registro->tipo == 1){
            $total_familia += $registro->valordescfinal;
        }else if ($registro->tipo == 2){
            $total_retencoes += $registro->valordescfinal;
        }
        $rubri_ant = $registro->codrubrica;
        $proxpag = false;
    }
    $pdf->ln(3);
    $pdf->setfont('arial','B',6);
    $pdf->cell(165,$alt,"Total da rubrica ",0,0,"R",1); 
    $pdf->cell( 25,$alt,db_formatar($total_rub, "f"),0,1,"R",1);
    $pdf->ln(1);
    $pdf->setfont('arial','B',8);
    $pdf->cell(165,$alt,"Total Retenções e Consignações","T",0,"R",1);
    $pdf->cell( 25,$alt,db_formatar($total_retencoes, "f"),"T",1,"R",1);
    $pdf->ln(1);
    $pdf->setfont('arial','B',8);
    $pdf->cell(165,$alt,"Total Salário Família e Maternidade","",0,"R",1);
    $pdf->cell( 25,$alt,db_formatar($total_familia, "f"),"",1,"R",1);
    $pdf->ln(1);
    $pdf->setfont('arial','B',8);
    $pdf->cell(165,$alt,"Total geral ","",0,"R",1);
    $pdf->cell( 25,$alt,db_formatar($total_familia + $total_retencoes, "f"),"",1,"R",1);
    
    if($totaliza == 's') {
        if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
            $pdf->addpage();
        }

        $pdf->cell(0,$alt,'Total dos Recursos ',0,1,"L",0);
        $pdf->setfont('arial','',9);
        foreach ($totalRecurso as $recurso => $total) {
            if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
                $pdf->addpage();
            }
            $pdf->cell(150,$alt,$recurso,0,0,"L",$cor,'','.');
            $pdf->cell(25,$alt,db_formatar($total,"f"),0,1,"R",$cor);
        }
    }
} else {
for($x = 0; $x < pg_numrows($result);$x++){
  db_fieldsmemory($result,$x);

  if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage();
    $pdf->setfont('arial','b',7);
    $pdf->cell(15,$alt,'RUBRICA',1,0,"C",1);
    $pdf->cell(75,$alt,'DESCRIÇÃO',1,0,"C",1);
    $pdf->cell(75,$alt,'RECURSO',1,0,"C",1);
    $pdf->cell(25,$alt,'DESCONTO',1,1,"C",1);
    $troca = 0;
    $cor = 1;
    $proxpag = true;
  }

  // $cor = ($cor == 0 ? 1 : 0);
  $cor = 0;

  if($rubri_ant != $rubrica || $proxpag == true){
    if($rubri_ant != $rubrica && $rubri_ant != ""){
      $pdf->setfont('arial','b',5);
      $pdf->cell(165,$alt,"Total da rubrica ",0,0,"R",1);
      $pdf->cell( 25,$alt,db_formatar($total_rub, "f"),0,1,"R",1);
      $pdf->ln(2);
      $total_rub = 0;
      $cor = 0;
    }
    $pdf->setfont('arial','',5);
    $pdf->cell(15,$alt,$rubrica,0,0,"C",$cor);
    $pdf->cell(75,$alt,$rh27_descr,0,0,"L",$cor);
  }else{
    $pdf->setfont('arial','',5);
    $pdf->cell(15,$alt,"",0,0,"C",$cor);
    $pdf->cell(75,$alt,"",0,0,"L",$cor);
  }

  $pdf->cell(75,$alt,$rh25_recurso . " - " .$o15_descr,0,0,"L",$cor);
  $pdf->cell(25,$alt,db_formatar($valor,"f"),0,1,"R",$cor);
  $total_rub += $valor;
  // print_r($valor);
  
  if($tipo == 1){
    $total_ger -= $valor;
  }else if ($tipo == 2){
    $total_ger += $valor;
  }
  $rubri_ant = $rubrica;
  $proxpag = false;
}
$pdf->ln(3);
$pdf->setfont('arial','B',7);
$pdf->cell(165,$alt,"Total da rubrica ",0,0,"R",1); 
$pdf->cell( 25,$alt,db_formatar($total_rub, "f"),0,1,"R",1);
$pdf->ln(1);
$pdf->setfont('arial','B',8);
$pdf->cell(165,$alt,"Total geral ","T",0,"R",1);
$pdf->cell( 25,$alt,db_formatar($total_ger, "f"),"T",1,"R",1);


if($totaliza == 's') {
  $sqlFinal = $clgerasql->gerador_sql("",
                                    $ano, $mes, null, null,
                                    "rh25_recurso,
                                     o15_descr,
                                     round(sum(case when ".$sigla."_pd = 2 then ".$sigla."_valor
                                                    when ".$sigla."_pd = 1 then ".$sigla."_valor *(-1) end ),2) as valor",
                                    "rh25_recurso , o15_descr",
                                    $dbwhere .
                                    "group by rh25_recurso,
                                              o15_descr"
                                   ); 
  $result = $clgerasql->sql_record($sqlFinal);
  $pdf->setfont('arial','B',9);
  if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage();
  }
  $pdf->cell(0,$alt,'Total dos Recursos ',0,1,"L",0);
  $pdf->setfont('arial','',9);
  for($x = 0; $x < pg_numrows($result);$x++){
    db_fieldsmemory($result,$x);

    if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
    }
    $pdf->cell(150,$alt,$rh25_recurso . " - " .$o15_descr,0,0,"L",$cor,'','.');
    $pdf->cell(25,$alt,db_formatar($valor,"f"),0,1,"R",$cor);
  }
}
}


$pdf->Output();
?>