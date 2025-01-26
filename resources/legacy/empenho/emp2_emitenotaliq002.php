<?
/* aa a
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

require_once("fpdf151/scpdf.php");
require_once("fpdf151/impcarne.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("classes/db_pagordem_classe.php");
require_once("classes/db_pagordemele_classe.php");
require_once("model/retencaoNota.model.php");
require_once("classes/db_empautitem_classe.php");
require_once("classes/db_empagetipo_classe.php");
require_once("classes/db_emite_nota_liq.php");
require_once("classes/db_empdiaria_classe.php");
require_once("classes/db_rhpessoal_classe.php");
require_once("model/orcamento/ControleOrcamentario.model.php");
require_once("model/protocolo/AssinaturaDigital.model.php");
/*
 * Configura��es GED
*/
require_once ("integracao_externa/ged/GerenciadorEletronicoDocumento.model.php");
require_once ("integracao_externa/ged/GerenciadorEletronicoDocumentoConfiguracao.model.php");
require_once ("libs/exceptions/BusinessException.php");
$assinar = "true";
$oGet           = db_utils::postMemory($_GET);
$clpagordem     = new cl_pagordem;
$clpagordemele  = new cl_pagordemele;
$clempautitem   = new cl_empautitem;
$clempagetipo   = new cl_empagetipo;
$clemite_nota_liq = new cl_emite_nota_liq;
$clDiaria = new cl_empdiaria;
$clRhpessoal = new cl_rhpessoal;

$sFornecedor = null;
if ( isset($oGet) && !empty($oGet) ) {
  $sFornecedor = !empty($oGet->aFornecedor) ? $oGet->aFornecedor : null;
}
if($oGet->assinar == "false"){

    $assinar = $oGet->assinar;
}
$oAssintaraDigital =  new AssinaturaDigital();
$e50_codord = $codordem;
$oConfiguracaoGed = GerenciadorEletronicoDocumentoConfiguracao::getInstance();
if ($oConfiguracaoGed->utilizaGED()) {

  if (empty($oGet->e60_codemp_ini)) {
    $oGet->e60_codemp_ini = null;
  }

  if (empty($oGet->e60_codemp_fim)) {
    $oGet->e60_codemp_fim = null;
  }

  if ( !empty($oGet->dtini) || !empty($oGet->dtfim) || $oGet->e60_codemp_ini != $oGet->e60_codemp_fim ) {

    $sMsgErro  = "O par�metro para utiliza��o do GED (Gerenciador Eletr�nico de Documentos) est� ativado.<br><br>";
    $sMsgErro .= "Neste n�o � poss�vel informar interv�los de c�digos ou datas.<br><br>";
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");
    exit;
  }
}


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$sqlpref = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = db_query($sqlpref);
db_fieldsmemory($resultpref,0);

$dbwhere = "";
$iAnoUso = db_getsession('DB_anousu');

if(isset($e60_codemp_ini) && $e60_codemp_ini != "") {
  // $codemp  = explode("/",$e60_codemp_ini);

  if (isset($e60_codemp_fim) && $e60_codemp_fim != "") {
     $str = " e60_codemp::integer between ".$e60_codemp_ini." and ".$e60_codemp_fim." and e60_anousu = {$iAnoUso} ";
  } else {
       $codemp  = explode("/",$e60_codemp_ini);

       if (count($codemp) > 1) {
         $str = " e60_codemp = '".$codemp[0]."' and e60_anousu = ".$codemp[1]." ";
       } else {
         $str = " e60_codemp = '".$e60_codemp_ini."' and e60_anousu = {$iAnoUso} ";
       }
       if(isset($e50_numliquidacao) && $e50_numliquidacao != ''){
        $str .= " and e50_numliquidacao = $e50_numliquidacao ";
       }
    }

    $dbwhere = " {$str} ";
}

// Condi��o de filtro de fonte Oc18018
if (isset($recursos) && $recursos != "") {
    if(strlen($dbwhere) > 0) {
        $dbwhere .= " and ";
    }
    $dbwhere .= " o58_codigo in ($recursos) ";
}

if(isset($codordem) && $codordem != ''){
  if(strlen($dbwhere) > 0) {
	  $dbwhere .= " and ";
  }
  $dbwhere .= " e50_codord in ($codordem) ";
}elseif(isset($e50_codord_ini) && $e50_codord_ini != ''){
  if(strlen($dbwhere) > 0) {
	  $dbwhere .= " and ";
  }

  if(isset($e50_codord_fim) && $e50_codord_fim != ''){
  $dbwhere .= "e50_codord::integer between ".$e50_codord_ini." and ".$e50_codord_fim;
  }else{
     $dbwhere .= " e50_codord in ($e50_codord_ini) ";
  }
}else{
  if(strlen($dbwhere) > 0) {
	  $dbwhere .= " and ";
  }
  $dbwhere .= "1=1 ";
}

if(isset($dtini) && $dtini!=""){
  if(strlen($dbwhere) > 0) {
	  $dbwhere .= " and ";
  }
  $dtini=str_replace("X","-",$dtini);
  $dbwhere.=" e50_data >= '$dtini'";
}

if(isset($dtfim) && $dtfim!=""){
  if(strlen($dbwhere) > 0) {
	  $dbwhere .= " and ";
  }
  $dtfim=str_replace("X","-",$dtfim);
  $dbwhere.=" e50_data <= '$dtfim'";
}
if (isset($e60_numemp) && $e60_numemp != '') {

  if(strlen($dbwhere) > 0) {
	  $dbwhere .= " and ";
  }
  $dbwhere .= " e60_numemp=$e60_numemp ";
  if(isset($e50_numliquidacao) && $e50_numliquidacao != ''){
    $dbwhere .= " and e50_numliquidacao = $e50_numliquidacao ";
  }
}

if ( !empty($sFornecedor) ) {

  if(strlen($dbwhere) > 0) {
	  $dbwhere .= " and ";
  }
  $dbwhere .= " z01_numcgm in ({$sFornecedor}) ";
}

$result2 = db_query("select * from empparametro where e39_anousu = ".db_getsession("DB_anousu"));

if(pg_numrows($result2)>0){
  $oParametros = db_utils::fieldsMemory($result2,0);

  $modelo = $oParametros->e30_modeloop == 2 ? '7_alt' : '7';
}

$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf, $modelo);
$pdf1->objpdf->SetTextColor(0,0,0);

$sSqlPagordem = $clpagordem->sql_query_notaliquidacao('',' e50_codord,e71_codnota,e60_numerol,pc50_descr,e50_numliquidacao',' e50_codord ', $dbwhere);
$result = $clpagordem->sql_record($sSqlPagordem);

if($clpagordem->numrows>0){
  db_fieldsmemory($result,0);
}else{
  db_redireciona('db_erros.php?fechar=true&db_erro=N�o a Ordem de Pagamento No. '.$codordem.'. Verifique!');
}

if(isset($oParametros)){
  $pdf1->nvias= $oParametros->e30_nroviaord;
}

/*
 *
 */

for($i = 0;$i < $clpagordem->numrows;$i++){

  db_fieldsmemory($result,$i);
  // var_dump($e50_codord);
  $oRetencaoNota = new retencaoNota($e71_codnota);
  $oRetencaoNota->setINotaLiquidacao($e50_codord);

  $sql = $clemite_nota_liq->get_sql_ordem_pagamento(db_getsession("DB_instit"), db_getsession("DB_anousu"), $e50_codord);

  $resultord = db_query($sql);
  db_fieldsmemory($resultord, $i);

  if (pg_numrows($resultord)==0) continue;

  db_fieldsmemory($resultord,0);

  $sql1         = $clemite_nota_liq->get_sql_assinaturas_ordenador($e50_codord);
  $resultordass = db_query($sql1);
  db_fieldsmemory($resultordass,0);

  $sqlitem      = $clemite_nota_liq->get_sql_item_ordem($e50_codord, $e50_data);
  $resultitem   = db_query($sqlitem);

  $sqloutrasordens      = $clemite_nota_liq->get_sql_outras_ordens($e50_codord, $e50_numemp, $e50_data);
  $resultoutrasordens   = db_query($sqloutrasordens);
  db_fieldsmemory($resultoutrasordens,0);

   $aRetencoes = $oRetencaoNota->getRetencoesFromDB($e50_codord, false, 0, "","",true);

   $sqlfornecon         = $clemite_nota_liq->get_sql_fornecedor($z01_cgccpf);
   $result_pcfornecon   = db_query($sqlfornecon);
   if(pg_numrows($result_pcfornecon) > 0){
     db_fieldsmemory($result_pcfornecon,0);
   }

   if ($o41_cnpj != "" && $o41_cnpj!= $cgc) {

     $nomeinst = $o41_descr;
     $cgc      = $o41_cnpj;

   }

  $resultContrato = $clpagordem->getContratoDataByEmpenho($e50_numemp);

  if (pg_num_rows($resultContrato) >= 1) {
    db_fieldsmemory($resultContrato, 0);
  }

  $sqlDiaria = $clDiaria->sql_query(null,'empdiaria.*,e60_numcgm',null,' e140_codord = '.$e50_codord);
  $resultDiaria   = db_query($sqlDiaria);

  if(pg_num_rows($resultDiaria) > 0){
    $diaria = db_utils::fieldsMemory($resultDiaria,0);

    $diaria->matricula = $diaria->e140_matricula;
    $diaria->cargo = $diaria->e140_cargo;

    $separador = '/ *-/';
    $diaria->origem = preg_split($separador, $diaria->e140_origem);
    $diaria->destino = preg_split($separador, $diaria->e140_destino);

    $diaria->e140_dtautorizacao = $diaria->e140_dtautorizacao == null ? '' : date('d/m/Y', strtotime($diaria->e140_dtautorizacao));
    $diaria->e140_dtinicial = $diaria->e140_dtinicial == null ? '' : date('d/m/Y', strtotime($diaria->e140_dtinicial));
    $diaria->e140_dtfinal = $diaria->e140_dtfinal == null ? '' : date('d/m/Y', strtotime($diaria->e140_dtfinal));

    $pdf1->diaria = $diaria;
  } else {
    $pdf1->diaria = null;
  }
   
    if ($e50_contafornecedor != null) {
      if ($e50_contafornecedor != 0) {
        $sSqlContaFornecedor = "SELECT * FROM pcfornecon WHERE pc63_contabanco = {$e50_contafornecedor}";
        db_fieldsmemory(db_query($sSqlContaFornecedor),0);      
      } else {
        $pc63_banco       = '';
        $pc63_agencia     = '';
        $pc63_agencia_dig = '';
        $pc63_conta       = '';
        $pc63_tipoconta   = '';
        $pc63_conta_dig   = '';
      }
    }

   $sSqlFuncaoOrdenaPagamento = $clemite_nota_liq->get_sql_funcao_ordena_pagamento($cgmpaga);
   $pdf1->cargoordenapagamento = db_utils::fieldsMemory(db_query($sSqlFuncaoOrdenaPagamento),0)->cargoordenapagamento;

   $sSqlFuncaoOrdenadespesa = $clemite_nota_liq->get_sql_funcao_ordena_despesa($cgmordenadespesa);
   $pdf1->cargoordenadespesa = db_utils::fieldsMemory(db_query($sSqlFuncaoOrdenadespesa),0)->cargoordenadespesa;

   $sSqlFuncaoLiquida = $clemite_nota_liq->get_sql_funcao_ordena_liquida($cgmliquida);
   $pdf1->cargoliquida = db_utils::fieldsMemory(db_query($sSqlFuncaoLiquida),0)->cargoliquida;

  /*OC4401*/
  $pdf1->usuario = $usuario;
  /*FIM = OC4401*/
  
   //assinaturas
   $pdf1->ordenadespesa    =  $assindsp;
   $pdf1->liquida          =  $assinlqd;
   $pdf1->ordenapagamento  =  $assinord;
   $pdf1->contador         =  $contador;
   $pdf1->crccontador      =  $crc;
   $pdf1->controleinterno  =  $controleinterno;

   $pdf1->numliquidacao    = $e50_numliquidacao;
   $pdf1->numeronota       = $e69_numero;
   $pdf1->datanota         = $e69_dtnota;
   $pdf1->valor_ordem      = '';
   $pdf1->logo             = $logo;
   $pdf1->processo         = $e60_numerol;
   $pdf1->descr_tipocompra = $pc50_descr;
   $pdf1->prefeitura       = $nomeinst;
   $pdf1->enderpref        = $ender;
   $pdf1->municpref        = $munic;
   $pdf1->telefpref        = $telef;
   $pdf1->emailpref        = $email;
   $pdf1->cgcpref          = $cgc;
   $pdf1->banco            = $pc63_banco;
   $pdf1->agencia          = $pc63_agencia;
   $pdf1->agenciadv        = $pc63_agencia_dig;
   $pdf1->conta            = $pc63_conta;
   $pdf1->tipoconta        = $pc63_tipoconta;
   $pdf1->contadv          = $pc63_conta_dig;
   $pdf1->numcgm           = $z01_numcgm;
   $pdf1->nome             = $z01_nome;
   $pdf1->cnpj             = $z01_cgccpf;
   $pdf1->ender            = $z01_ender;
   $pdf1->bairro           = $z01_bairro;
   $pdf1->cep              = $z01_cep;
   $pdf1->ufFornecedor     = $z01_uf;
   $pdf1->munic            = $z01_munic;
   $pdf1->ordpag           = $e50_codord;
   $pdf1->coddot           = $o58_coddot;
   $pdf1->dotacao          = $estrutural;
   $pdf1->outrasordens     = $outrasordens;
   $pdf1->recorddositens   = $resultitem;
   $pdf1->ano		           = $e60_anousu;
   $pdf1->linhasdositens   = pg_numrows($resultitem);
   $pdf1->elementoitem     = "o56_elemento";
   $pdf1->descr_elementoitem = "o56_descr";
   $pdf1->vlremp           = "e53_valor";
   $pdf1->vlranu           = "e53_vlranu";
   $pdf1->vlrpag           = "e53_vlrpag";
   $pdf1->vlrsaldo         = "saldo";
   $pdf1->saldo_final      = "saldo_final";
   $pdf1->aRetencoes       = $aRetencoes;
   $pdf1->orcado	         = $e60_vlrorc;
   $pdf1->saldo_ant        = $e60_salant;
   $pdf1->empenhado        = $e60_vlremp;
   $pdf1->empenho_anulado  = $e60_vlranu;
   $pdf1->codemp           = $e60_codemp;
   $pdf1->numemp           = $e60_codemp.'/'.$e60_anousu;
   $pdf1->orgao            = $o58_orgao;
   $pdf1->descr_orgao      = $o40_descr;
   $pdf1->unidade          = $o58_unidade;
   $pdf1->descr_unidade    = $o41_descr;
   $pdf1->funcao           = $o58_funcao;
   $pdf1->descr_funcao     = $o52_descr;
   $pdf1->subfuncao        = $o58_subfuncao;
   $pdf1->descr_subfuncao  = $o53_descr;
   $pdf1->programa         = $o58_programa;
   $pdf1->descr_programa   = $o54_descr;
   $pdf1->projativ         = $o58_projativ;
   $pdf1->descr_projativ   = $o55_descr;
   $pdf1->recurso          = $o58_codigo;
   $pdf1->descr_recurso    = $o15_descr;
   $pdf1->elemento     	   = $o56_elemento;
   $pdf1->descr_elemento   = $o56_descr;
   $pdf1->obs		           = substr($e50_obs,0,300);
   $pdf1->contrato         = $ac16_numero;
   $pdf1->contrato_ano      = $ac16_anousu;
   $pdf1->cod_contrato     = $ac16_sequencial;
   $pdf1->emissao          = db_formatar($e50_data,'d');
   $pdf1->texto		         = db_getsession("DB_login").'  -  '.date("d-m-Y",db_getsession("DB_datausu")).'    '.db_hora(db_getsession("DB_datausu"));

   $pdf1->telef            = $z01_telef;
   $pdf1->fax              = $z01_numero;


   $clControleOrc = new ControleOrcamentario;
   $e60_codco = $e60_codco == null ? '0000' : $e60_codco;
   $clControleOrc->setCodCO($e60_codco);

   $pdf1->codco  = $e60_codco.' - '.$clControleOrc->getDescricaoResumoCO();


   /**
    * Vari�veis utilizadas na assinatura. S�mente utilizada na impress�o por movimento
    */
   $pdf1->iReduzido         = "";
   $pdf1->sContaContabil    = "";
   $pdf1->sBanco            = "";
   $pdf1->sAgencia          = "";
   $pdf1->sDigtoAgencia     = "";
   $pdf1->sContaBanco       = "";
   $pdf1->sDigitoContaBanco = "";
   $pdf1->iTipoPagamento    = "";
   $pdf1->sCheque           = "";
   $pdf1->sAutenticacao     = "";
   $pdf1->nValorMovimento   = "";

  if($clpagordem->numrows == 1 && isset($valor_ordem)){

   	if( $valor_ordem > pg_result($resultitem,0,"saldo") ){
       $valor_ordem = pg_result($resultitem,0,"saldo");
     }

     $pdf1->valor_ordem  = "$valor_ordem";
     if (isset($historico) && trim($historico)!= ""){
       $pdf1->obs = "$historico";
     }else{
       $pdf1->obs = "$e50_obs";
     }
   } else {
   	 $pdf1->valor_ordem = "";
   	 $pdf1->obs 		= "$e50_obs";
   }

    if (in_array($e54_tipoautorizacao, array('0','1','2','3','4'))) {

        $oAutoriza = $clemite_nota_liq->get_dados_licitacao($e54_tipoautorizacao, $e54_autori);

        $pdf1->processo         = $oAutoriza->processo;
        $pdf1->descr_tipocompra = $pc50_descr;

    }

    if ($e50_contapag != '') {

        $sCampos            = " e83_conta, e83_descr, c63_agencia, c63_dvagencia, c63_conta, c63_dvconta ";
        $sWhere             = " e83_codtipo = {$e50_contapag} ";
        $sSqlContaPagadora  = $clempagetipo->sql_query_conplanoconta(null, $sCampos, null, $sWhere);
        $rsContaPagadora    = $clempagetipo->sql_record($sSqlContaPagadora);

        if ($clempagetipo->numrows > 0) {

            db_fieldsmemory($rsContaPagadora, 0);
            $pdf1->conta_pagadora_reduz     = $e83_conta;
            $pdf1->conta_pagadora_agencia   = "{$c63_agencia}-{$c63_dvagencia}";
            $pdf1->conta_pagadora_conta     = "{$c63_conta}-{$c63_dvconta} {$e83_descr}";

        }
    } else {
      $pdf1->conta_pagadora_reduz   = null;
      $pdf1->conta_pagadora_agencia = null;
      $pdf1->conta_pagadora_conta   = null;
    }

   $pdf1->imprime();
}



if($oAssintaraDigital->verificaAssituraAtiva() && $assinar == "true"){

    try {
        $sInstituicao = str_replace( " ", "_", strtoupper($oAssintaraDigital->removerAcentos($nomeinstabrev)));
        $lqd = "$e60_codemp-$e50_numliquidacao";
        $nomeDocumento = "LIQUIDACAO_{$e60_codemp}_{$e60_anousu}_LQD_{$lqd}_OP_{$e50_codord}_{$sInstituicao}.pdf";
        $pdf1->objpdf->Output("tmp/$nomeDocumento", false, true);
        $oAssintaraDigital->gerarArquivoBase64($nomeDocumento);
        $oAssintaraDigital->assinarLiquidacao($e60_numemp, $e71_codnota, $e60_coddot, $e60_anousu,  $e50_data, $nomeDocumento, "LIQUIDACAO EMP {$e60_codemp}/{$e60_anousu} LQD {$lqd} OP {$e50_codord}");
        $pdf1->objpdf->Output();
    } catch (Exception $eErro) {
        db_redireciona("db_erros.php?fechar=true&db_erro=".$eErro->getMessage());
    }

} else if ($oConfiguracaoGed->utilizaGED()) {

  try {

    $sTipoDocumento = GerenciadorEletronicoDocumentoConfiguracao::ORDEM_PAGAMENTO;
    $oGerenciador = new GerenciadorEletronicoDocumento();
    $oGerenciador->setLocalizacaoOrigem("tmp/");
    $oGerenciador->setNomeArquivo("{$sTipoDocumento}_{$e50_codord}.pdf");

    $oStdDadosGED        = new stdClass();
    $oStdDadosGED->nome  = $sTipoDocumento;
    $oStdDadosGED->tipo  = "NUMERO";
    $oStdDadosGED->valor = $e50_codord;
    $pdf1->objpdf->Output("tmp/{$sTipoDocumento}_{$e50_codord}.pdf");
    $oGerenciador->moverArquivo(array($oStdDadosGED));

  } catch (Exception $eErro) {
    db_redireciona("db_erros.php?fechar=true&db_erro=".$eErro->getMessage());
  }
} else {
  $pdf1->objpdf->Output();
}

?>
