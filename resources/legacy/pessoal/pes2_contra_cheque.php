<?
require_once("fpdf151/impcarne.php");
require_once("fpdf151/scpdf.php");

require_once("classes/db_cgm_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_rhemitecontracheque_classe.php");
require_once("classes/db_cfpess_classe.php");

require_once("libs/db_utils.php");
require_once("libs/db_libpessoal.php");
require_once("libs/JSON.php");

db_postmemory($HTTP_SERVER_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$oJson        = new services_json();
$oParametros  = $oJson->decode(str_replace("\\","",$json));
/**
 * constantes para o Relatorio
 */
define( "TIPO_RELATORIO_GERAL"              , "0" );
define( "TIPO_RELATORIO_ORGAO"              , "1" );
define( "TIPO_RELATORIO_LOTACAO"            , "2" );
define( "TIPO_RELATORIO_MATRICULA"          , "3" );
define( "TIPO_RELATORIO_LOCAIS_TRABALHO"    , "4" );
define( "TIPO_RELATORIO_CARGO"              , "5" );
define( "TIPO_RELATORIO_RECURSO"            , "6" );

define( "TIPO_FILTRO_GERAL"                 , 0 );
define( "TIPO_FILTRO_INTERVALO"             , 1 );
define( "TIPO_FILTRO_SELECIONADOS"          , 2 );

$clrhemitecontracheque = new cl_rhemitecontracheque();
$oDaoCfpess            = new cl_cfpess;

/**
 * Tipo de relat�rio contracheque
 * Retorna false caso der erro na consulta
 */   
$iTipoRelatorio = $oDaoCfpess->buscaCodigoRelatorio('contracheque', db_anofolha(), db_mesfolha());
if(!$iTipoRelatorio) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Modelo de impress�o invalido, verifique parametros.');
}

switch ( $oParametros->iTipoRelatorio ) {

  default:

    $sCampoCondicaoTipoRelatorio   = 1;
    $sCampoDescricaoTipoRelatorio  = "";

  break;

  case TIPO_RELATORIO_CARGO:

    $sCampoCondicaoTipoRelatorio   = "rh37_funcao";
    $sCampoDescricaoTipoRelatorio  = "rh37_descr";

  break;

  case TIPO_RELATORIO_LOTACAO:

    $sCampoCondicaoTipoRelatorio     = "r70_codigo";
    $sCampoDescricaoTipoRelatorio    = "r70_descr";

  break;

  case TIPO_RELATORIO_ORGAO:

    $sCampoCondicaoTipoRelatorio  = "rh26_orgao";
    $sCampoDescricaoTipoRelatorio = "o40_descr";

  break;

  case TIPO_RELATORIO_LOCAIS_TRABALHO:

    $sCampoCondicaoTipoRelatorio  = "rh55_codigo";
    $sCampoDescricaoTipoRelatorio = "rh55_descr";

  break;

  case TIPO_RELATORIO_MATRICULA:

    $sCampoCondicaoTipoRelatorio  = "rh02_regist";
    $sCampoDescricaoTipoRelatorio = "z01_nome";

  break;

  case TIPO_RELATORIO_RECURSO:

    $sCampoCondicaoTipoRelatorio  = "o15_codigo";
    $sCampoDescricaoTipoRelatorio = "o15_descr";

  break;
}
$sWhere = "";
if ( $oParametros->iTipoRelatorio <> TIPO_RELATORIO_GERAL ) {

  switch ( $oParametros->iTipoFiltro ) {

    case TIPO_FILTRO_GERAL:
      //Sem Filtros
    break;
    case TIPO_FILTRO_INTERVALO:
      $sWhere = " and {$sCampoCondicaoTipoRelatorio} between $oParametros->iIntervaloInicial and $oParametros->iIntervaloFinal";
    break;
    case TIPO_FILTRO_SELECIONADOS:
      $sWhere = " and {$sCampoCondicaoTipoRelatorio} in (" . implode(", ", $oParametros->iRegistros) . ")";
    break;
  }
}

$wherepes = $sWhere;
if($selecao != ''){
  $result_sel = db_query("select r44_where , r44_descr from selecao where r44_selec = ".$selecao);
  if(pg_num_rows($result_sel) > 0){
    db_fieldsmemory($result_sel, 0, 1);
    $wherepes .= " and ".$r44_where;
    $head5 = $r44_descr;
    $erroajuda = " ou sele��o informada � inv�lida";
  }
}

$sql = "select * from db_config where codigo = ".db_getsession("DB_instit");
$result = db_query($sql);
db_fieldsmemory($result,0);

$xtipo = "'x'";
$qualarquivo = '';
if ( $opcao == 'salario' ){
  $sigla   = 'r14_';
  $arquivo = 'gerfsal';
  $qualarquivo = 'SAL�RIO';
}elseif ( $opcao == 'ferias' ){
  $sigla   = 'r31_';
  $arquivo = 'gerffer';
  $xtipo   = ' r31_tpp ';
  $qualarquivo = 'F�RIAS';
}elseif ( $opcao == 'rescisao' ){
  $sigla   = 'r20_';
  $arquivo = 'gerfres';
  $xtipo   = ' r20_tpp ';
  $qualarquivo = 'RESCIS�O';
}elseif ($opcao == 'adiantamento'){
  $sigla   = 'r22_';
  $arquivo = 'gerfadi';
  $qualarquivo = 'ADIANTAMENTO';
}elseif ($opcao == '13salario'){
  $sigla   = 'r35_';
  $arquivo = 'gerfs13';
  $qualarquivo = '13o. SAL�RIO';
}elseif ($opcao == 'complementar'){
  $sigla   = 'r48_';
  $arquivo = 'gerfcom';
  $qualarquivo = 'COMPLEMENTAR';
}elseif ($opcao == 'fixo'){
  $sigla   = 'r53_';
  $arquivo = 'gerffx';
  $qualarquivo = 'FIXO';
}elseif ($opcao == 'previden'){
  $sigla   = 'r60_';
  $arquivo = 'previden';
  $qualarquivo = 'AJUSTE DA PREVID�NCIA';
}elseif ($opcao == 'irf'){
  $sigla   = 'r61_';
  $arquivo = 'ajusteir';
  $qualarquivo = 'AJUSTE DO IRRF';
}

$wheresemest = "";
if(isset($semest) && trim($semest) != 0){
  $wheresemest = " and r48_semest = ".$semest;
}

$sql1= "select distinct
       		z01_nome,
       		z01_cgccpf,
       		rh01_admiss,
       		rh30_regime,
       		rh30_descr,
       		rh16_pis,
       		rhpessoal.*,
       		rhpessoalmov.*,
          rhpesbanco.*,
       		CASE WHEN rh04_descr != '' THEN rh04_descr ELSE rh37_descr end as rh37_descr,
       		r70_descr,
          rhregime.*,
      		substr(r70_estrut,1,7) as estrut,
	       	".$sigla."regist as regist,
	        substr(db_fxxx(".$sigla."regist,$ano,$mes,".db_getsession("DB_instit")."),111,11) as f010, 
        	substr(db_fxxx(".$sigla."regist,$ano,$mes,".db_getsession("DB_instit")."),221,10) as padrao
          from (select distinct ".$sigla."regist,
                                ".$sigla."anousu,
                                ".$sigla."mesusu,
                                ".$sigla."lotac
	              from ".$arquivo." ".bb_condicaosubpesproc( $sigla,$ano."/".$mes ).$wheresemest." 
               ) as ".$arquivo."
       	  inner join rhpessoal     on rh01_regist = ".$sigla."regist 
		      inner join rhpessoalmov  on rh02_regist = rh01_regist
                                  and rh02_anousu = $ano 
		  	                          and rh02_mesusu = $mes 
		  	                          and rh02_instit = ".db_getsession("DB_instit")."
          inner join rhregime      on rh02_codreg = rh30_codreg
                                  and rh02_instit = rh30_instit
     		  inner join cgm           on rh01_numcgm  = z01_numcgm
     		  left join rhfuncao       on rh37_funcao = rh02_funcao
		                              and rh37_instit = rh02_instit
     		  left join rhlota         on r70_codigo  = rh02_lota
				                          and r70_instit  = rh02_instit
          left join rhpescargo     on rh20_seqpes = rh02_seqpes
		                              and rh20_instit = rh02_instit
          left join rhpesbanco     on rh44_seqpes = rh02_seqpes
          left join rhcargo     	 on rh04_codigo = rh20_cargo
		                              and rh04_instit = rh02_instit
          left join rhpeslocaltrab on rh56_seqpes = rh02_seqpes
                                  and rh56_princ  = true
          left  join rhlocaltrab on rhpeslocaltrab.rh56_localtrab = rhlocaltrab.rh55_codigo
          left join rhpesdoc on rh16_regist = rh01_regist
          left  join rhlotaexe    on rh26_codigo = r70_codigo  
                            and rh26_anousu = $ano        
          left join orcorgao      on o40_orgao   = rh26_orgao  
                            and o40_anousu  = $ano        
                            and o40_instit  = rh02_instit 
          left  join rhlotavinc on rh25_codigo = r70_codigo
          and rh25_anousu = rhpessoalmov.rh02_anousu
          left  join orctiporec on o15_codigo = rh25_recurso

	        where 1=1 $wherepes
	        ";

$sql = "select * from ($sql1) as xxx, generate_series(1,$num_vias) order by ";

//echo $sql;exit; 

if($ordem == "L"){
  $sql .= " estrut,z01_nome, rh01_regist ";
}else if($ordem == "N"){
  $sql .= " z01_nome , rh01_regist";
}else if($ordem == "T"){
  $sql .= " rh56_localtrab , z01_nome , rh01_regist ";
}else{
  $sql .= " rh01_regist ";
}

// ------------- busca url do site do cliente ----------------------
$sqlDbConfig = " select url from db_config where prefeitura = true ";
$rsDbConfig  = db_query($sqlDbConfig);
$iDbConfig   = pg_num_rows($rsDbConfig);

if ($iDbConfig > 0) {
	$oDbConfig = db_utils::fieldsMemory($rsDbConfig, 0);
	$sDbConfig = $oDbConfig->url;
} else {
	$sDbConfig = "";
}
//------------------------------------------------------------------
 //echo $sql;exit;
$res = db_query($sql);
$num = pg_num_rows($res);
if ($num == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=N�o existe C�lculo no per�odo de '.$mes.' / '.$ano);
}
  global $pdf;
  $pdf = new scpdf();
  $pdf->setautopagebreak(false,0.05);
  $pdf->Open();
  $pdf1 = new db_impcarne($pdf, $iTipoRelatorio);
  $pdf1->logo             = $logo;
  if(strlen($nomeinst) > 50){
    $pdf1->prefeitura       = $nomeinstabrev;
  } else{ 
  $pdf1->prefeitura       = $nomeinst;
  }
  $pdf1->enderpref        = $ender.(isset($numero)?(', '.$numero):"");
  $pdf1->cgcpref          = $cgc;
  $pdf1->municpref        = $munic;
  $pdf1->telefpref        = $telef;
  $pdf1->emailpref        = $email;
  $pdf1->ano        	  = $ano;
  $pdf1->mes          	  = $mes;
  $pdf1->mensagem         = $msg;
  $pdf1->qualarquivo      = $qualarquivo;
 
  $lin = 1;
  
for($i=0;$i<$num;$i++){
	
  db_fieldsmemory($res,$i);

  $rsSeqContraCheque = db_query("select nextval('rhemitecontracheque_rh85_sequencial_seq') as sequencial");
  $oSeqContraCheque  = db_utils::fieldsMemory($rsSeqContraCheque,0);
  $iSequencial       = str_pad($oSeqContraCheque->sequencial,6,'0',STR_PAD_LEFT);
  
  $iMes       = str_pad($mes,2,'0',STR_PAD_LEFT);
  $iMatricula = str_pad($regist,6,'0',STR_PAD_LEFT);
  $iMod1      = db_CalculaDV($iMatricula);
  $iMod2      = db_CalculaDV($iMatricula.$iMod1.$iMes.$ano.$iSequencial); 
     
  $iCodAutent = $iMatricula.$iMod1.$iMes.$iMod2.$ano.$iSequencial;
  
  $clrhemitecontracheque->rh85_sequencial  = $iSequencial;
  $clrhemitecontracheque->rh85_regist      = $regist;
  $clrhemitecontracheque->rh85_anousu      = $ano;
  $clrhemitecontracheque->rh85_mesusu      = $mes;
  $clrhemitecontracheque->rh85_sigla       = substr($sigla,0,3);
  $clrhemitecontracheque->rh85_codautent   = $iCodAutent;
  $clrhemitecontracheque->rh85_dataemissao = date('Y-m-d',db_getsession('DB_datausu'));
  $clrhemitecontracheque->rh85_horaemissao = db_hora();
  $clrhemitecontracheque->rh85_ip          = db_getsession('DB_ip');
  $clrhemitecontracheque->rh85_externo     = 'false';

  $clrhemitecontracheque->incluir($iSequencial);
  
  if ( $clrhemitecontracheque->erro_status == 0 ) {
  	db_redireciona('db_erros.php?fechar=true&db_erro='.$clrhemitecontracheque->erro_msg);
  }
  
  if($lin == 1){
    $lin = 0;
    $pdf1->seq = 0;
  }else{
    $lin = 1;
    $pdf1->seq = 1;
  }
  
  $sql = "
  select ".$sigla."rubric as rubrica,
       round(".$sigla."valor,2) as valor,
       round(".$sigla."quant,2) as quant, 
       rh27_descr, 
       ".$xtipo." as tipo , 
       case when rh27_pd = '3' then 'B' 
            when ".$sigla."pd = 1 then 'P' 
	          when ".$sigla."pd = 2 then 'D' 
       end as provdesc
 
  from ".$arquivo." 
     inner join rhrubricas on rh27_rubric = ".$sigla."rubric 
                          and rh27_instit = ".db_getsession('DB_instit')."
  where ".$sigla."regist = $regist
    and ".$sigla."anousu = $ano 
    and ".$sigla."mesusu = $mes
    and ".$sigla."instit = ".db_getsession("DB_instit")."
    $wheresemest
  order by ".$sigla."rubric  ";

  $res_env = db_query($sql);
  //modificado 
  $pdf1->registro	      = $rh01_regist;
  $pdf1->nome		      = substr($z01_nome, 0, 40);
  $pdf1->descr_funcao	  = substr($rh37_descr, 0, 37);
  $pdf1->descr_lota       = $estrut.'-'.$r70_descr;
  $pdf1->f010          	  = $f010;
  $pdf1->padrao        	  = $padrao;
  $pdf1->banco         	  = $rh44_codban;
  $pdf1->agencia       	  = trim($rh44_agencia).'-'.trim($rh44_dvagencia);
  $pdf1->conta         	  = trim($rh44_conta).'-'.trim($rh44_dvconta);
  $pdf1->lotacao	      = $estrut;
  $pdf1->recordenvelope   = $res_env;
  $pdf1->linhasenvelope	  = pg_num_rows($res_env);
  $pdf1->valor		      = 'valor';
  $pdf1->quantidade	      = 'quant';
  $pdf1->tipo		      = 'provdesc';
  $pdf1->rubrica	      = 'rubrica';
  $pdf1->descr_rub	      = 'rh27_descr';
  $pdf1->numero	  	      = $i+1;
  $pdf1->total	  	      = $num;
  $pdf1->codautent        = $iCodAutent;
  $pdf1->url              = $sDbConfig;
   //adicionado
  $pdf1->admiss           = $rh01_admiss;
  $pdf1->cgm           	  = $z01_cgccpf;
  $pdf1->vinculo       	  = $rh30_regime.'-'.$rh30_descr;
  $pdf1->pis	       	  = $rh16_pis;
  $pdf1->codcgm           = $rh01_numcgm;
  
  
  $pdf1->imprime();
  
}
$pdf1->objpdf->output();
?>   
