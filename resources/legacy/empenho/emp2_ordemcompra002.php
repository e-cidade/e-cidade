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

require_once("fpdf151/scpdf.php");
require_once("fpdf151/impcarne.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("classes/db_matordem_classe.php");
require_once("classes/db_matordemitem_classe.php");
require_once("classes/db_empparametro_classe.php");
require_once("classes/db_empautitem_classe.php");
require_once("classes/db_pcparam_classe.php");
require_once("classes/db_db_depart_classe.php");


/*
 * Configura��es GED
*/
require_once("integracao_externa/ged/GerenciadorEletronicoDocumento.model.php");
require_once("integracao_externa/ged/GerenciadorEletronicoDocumentoConfiguracao.model.php");
require_once("libs/exceptions/BusinessException.php");

$oGet = db_utils::postMemory($_GET);
$oConfiguracaoGed = GerenciadorEletronicoDocumentoConfiguracao::getInstance();
if ($oConfiguracaoGed->utilizaGED()) {

  if (empty($oGet->m51_codordem_ini) && !empty($cods)) {
    $oGet->m51_codordem_ini = $cods;
  }

  if (empty($oGet->m51_codordem_fim) && !empty($cods)) {
    $oGet->m51_codordem_fim = $cods;
  }

  if ($oGet->m51_codordem_ini != $oGet->m51_codordem_fim) {

    $sMsgErro  = "O par�metro para utiliza��o do GED (Gerenciador Eletr�nico de Documentos) est� ativado.<br><br>";
    $sMsgErro .= "Neste n�o � poss�vel informar interv�los de c�digos ou datas.<br><br>";
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");
    exit;
  }
}

$clmatordem     = new cl_matordem;
$clmatordemitem = new cl_matordemitem;
$clempautitem   = new cl_empautitem;
$clempparametro = new cl_empparametro;
$clpcparam      = new cl_pcparam;
$oDaoDbDepart   = new cl_db_depart;

$sqlpref  = "select db_config.*, cgm.z01_incest as inscricaoestadualinstituicao ";
$sqlpref .= "  from db_config                                                     ";
$sqlpref .= " inner join cgm on cgm.z01_numcgm = db_config.numcgm                 ";
$sqlpref .=  "	where codigo = " . db_getsession("DB_instit");

$resultpref = db_query($sqlpref);
db_fieldsmemory($resultpref, 0);

$rsPcParam = $clpcparam->sql_record($clpcparam->sql_query(db_getsession("DB_instit")));
if ($clpcparam->numrows > 0) {
  $oParam = db_utils::fieldsMemory($rsPcParam, 0);
} else {
  db_redireciona("db_erros.php?fechar=true&db_erro=N�o h� parametros configurados para essa institui��o.");
}
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$txt_where = '1=1';

if (isset($m51_codordem_ini) && $m51_codordem_ini != "" && isset($m51_codordem_fim) && $m51_codordem_fim != "") {
  $txt_where .= " and  m51_codordem between $m51_codordem_ini and  $m51_codordem_fim";
} else if (isset($m51_codordem_ini) && $m51_codordem_ini != "") {
  $txt_where .= " and  m51_codordem>$m51_codordem_ini";
} else  if (isset($m51_codordem_fim) && $m51_codordem_fim != "") {
  $txt_where .= " and  m51_codordem<$m51_codordem_fim";
} else if (isset($cods) && $cods != "") {
  $txt_where .= " and m51_codordem in ($cods) ";
}

if (isset($m51_codordem_ini) && $m51_codordem_ini == "" && isset($m51_codordem_fim) && $m51_codordem_fim == "") {
  if ($fornecedor != "") {
    $txt_where .= " and m51_numcgm IN ({$fornecedor}) ";
  }
  if ($data_ini != "") {
    $txt_where .= " and m51_data>='{$data_ini}' ";
  }
  if ($data_fim != "") {
    $txt_where .= " and m51_data<='{$data_fim}' ";
  }
}

if (isset($m51_codordem_ini) && $m51_codordem_ini == "" && isset($m51_codordem_fim) && $m51_codordem_fim == "" && $fornecedor != "") {
  if ($data_ini != "") {
    $txt_where .= " and m51_data>='{$data_ini}' ";
  }
  if ($data_fim != "") {
    $txt_where .= " and m51_data<='{$data_fim}' ";
  }
}

$result = $clmatordem->sql_record($clmatordem->sql_query_instit(null, "*", "", "$txt_where"));

$num = $clmatordem->numrows;


$pdf = new scpdf();
$pdf->Open();

$pdf1 = new db_impcarne($pdf, $oParam->pc30_modeloordemcompra);


//$pdf1->modelo = 10;
//$pdf1->nvias= 2 ;
$pdf1->objpdf->SetTextColor(0, 0, 0);

$flag_imprime = true;

for ($i = 0; $i < $num; $i++) {

  db_fieldsmemory($result, $i);

  $sSqlDeptoOrigem = $oDaoDbDepart->sql_query_file(null, "*", null, "coddepto = {$m51_deptoorigem}");

  $rsOrigem        = $oDaoDbDepart->sql_record($sSqlDeptoOrigem);
  $sOrigem = db_utils::fieldsMemory($rsOrigem, 0)->descrdepto;
  $iOrigem = db_utils::fieldsMemory($rsOrigem, 0)->coddepto;


  $sqlItem =   $clmatordemitem->sql_query_emiteordem(
    null,
    "distinct m52_codordem,
                                                         m52_sequen,
                                                         m52_quant,
                                                         m52_numemp,
                                                         m52_vlruni,
                                                         m52_valor,
                                                         e55_marca,
                                                         case when matunid.m61_abrev is null or matunid.m61_abrev = '' then coalesce(matunidaut.m61_abrev,coalesce(matunidsol.m61_abrev,'UN')) else coalesce(matunid.m61_abrev,coalesce(matunidsol.m61_abrev,'UN')) end as m61_abrev,
                                                         pcmater.pc01_descrmater,
                                                         pcmater.pc01_complmater,
                                                         pc01_codmater,
                                                         pc01_servico,
                                                         e62_servicoquantidade,
						         (case when solicitem.pc11_resum is null  or solicitem.pc11_resum ='' then pc01_complmater else solicitem.pc11_resum end)||'\n'||e55_descr AS e62_descr,
                                                        /* e62_descr,*/
                                                         empempenho.e60_codemp,
                                                         empempenho.e60_anousu,
                                                         empempenho.e60_emiss,
                                                         e62_vltot,
                                                         e62_quant,
                                                         e54_conpag,
                                                         e54_destin,
                                                         case when rp.pc81_codproc is not null then rp.pc81_codproc
                                                              else  pcprocitem.pc81_codproc end as pc81_codproc,
                                                         case when solrp.pc11_numero is not null then solrp.pc11_numero
                                                              else  solicitem.pc11_numero end as pc11_numero,
                                                         case WHEN e55_marca IS NOT NULL AND trim(e55_marca) != '' THEN e55_marca
                                                         when pc10_solicitacaotipo = 5 then coalesce(trim(pcitemvalrp.pc23_obs), '')
                                                              when  pcorcamval.pc23_obs is null then coalesce(trim(pcorcamvalsl.pc23_obs),trim(pcorcamvalac.pc23_obs), '')
                                                              else  coalesce(trim(pcorcamval.pc23_obs),trim(pcorcamvalac.pc23_obs), '') end as pc23_obs,
	                                                       pc50_descr,
	                                                       e61_autori",
    "m52_numemp, m52_sequen",
    "m52_codordem = $m51_codordem"
  );
  //echo ($sqlItem);
  //die();
  $resultitem = $clmatordemitem->sql_record($sqlItem);
  db_fieldsmemory($resultitem, $i)->e60_emiss;

  $numrows = $clmatordemitem->numrows;
  if ($numrows == 0) {
    $flag_imprime = false;
    continue;
  } else {
    $flag_imprime = true;
  }

  $datahj = date("Y-m-d", db_getsession("DB_datausu"));

  $pdf1->prefeitura   = $nomeinst;
  $pdf1->enderpref    = trim($ender) . "," . $numero;
  $pdf1->municpref    = $munic;
  $pdf1->uf           = $uf;
  $pdf1->telefpref    = $telef;
  $pdf1->logo       = $logo;
  $pdf1->emailpref    = $email;

  $pdf1->inscricaoestadual    = '';
  if ($db21_usasisagua == 't') {
    $pdf1->inscricaoestadualinstituicao    = "Inscri��o Estadual: " . $inscricaoestadualinstituicao;
  }

  $pdf1->numordem     = $m51_codordem;
  $pdf1->dataordem    = $m51_data;
  $pdf1->coddepto     = $m51_depto;
  $pdf1->descrdepto   = $descrdepto;
  $pdf1->numcgm       = $m51_numcgm;
  $pdf1->nome         = $z01_nome;
  $pdf1->email        = $z01_email;
  $pdf1->cnpj         = $z01_cgccpf;
  $pdf1->cgc          = $cgc;
  $pdf1->url          = $url;
  $pdf1->ender        = $z01_ender;
  $pdf1->munic        = $z01_munic;
  $pdf1->bairro       = strlen($z01_bairro) > 18 ? substr($z01_bairro, 0, 18) . '.' : $z01_bairro;
  $pdf1->cep          = $z01_cep;
  $pdf1->ufFornecedor = $z01_uf;
  $pdf1->numero       = $z01_numero;
  $pdf1->compl        = $z01_compl;
  $pdf1->contato      = $z01_telcon;
  $pdf1->telef_cont   = $z01_telef;
  $pdf1->telef_fax    = $z01_fax;
  $pdf1->recorddositens = $resultitem;
  $pdf1->linhasdositens = $numrows;
  $pdf1->emissao = $datahj;
  // $pdf1->item	      = 'm52_sequen';
  $pdf1->obs            = $m51_obs;
  $pdf1->sTipoCompra    = 'pc50_descr'; //campo do pg_result
  $pdf1->empempenho     = 'e60_codemp';
  $pdf1->servico        = 'pc01_servico';
  $pdf1->servicoquantidade = 'e62_servicoquantidade';
  $pdf1->anousuemp      = 'e60_anousu';
  $pdf1->quantitem      = 'm52_quant';
  $pdf1->unid           = 'm61_abrev';
  $pdf1->condpag        = 'e54_conpag';
  $pdf1->destino        = 'e54_destin';
  $pdf1->autori         = 'e61_autori';
  $pdf1->e60_emiss      = $e60_emiss;

  $pdf1->obs_ordcom_orcamval = "e55_marca";
  $pdf1->sOrigem        = $iOrigem . " - " . $sOrigem;
  //$pdf1->iOrigem        = $iOrigem;
  //   $pdf1->quantitememp   = 'e62_quant';
  $anousu = db_getsession("DB_anousu");
  $result_numdec = $clempparametro->sql_record($clempparametro->sql_query_file($anousu));

  if ($clempparametro->numrows > 0) {

    db_fieldsmemory($result_numdec, 0);
  } else {

    $e30_numdec = 4;
  }

  $resultado = db_utils::fieldsMemory($resultitem, $n);
  $sql = "SELECT * 
      FROM pcparam 
      WHERE pc30_prazoent = (SELECT MAX(pc30_prazoent) FROM pcparam);";
      $rsResult = db_query($sql);
      db_fieldsmemory($rsResult, 0);
      $prazo = $pc30_prazoent;

  $pdf1->numdec         = $e30_numdec;
  $pdf1->valoritem      = 'm52_valor';
  $pdf1->vlrunitem      = 'm52_vlruni';
  $pdf1->descricaoitem  = 'pc01_descrmater';
  $pdf1->pc01_complmater  = 'pc01_complmater';
  $pdf1->codmater       = 'pc01_codmater';
  $pdf1->observacaoitem = 'e62_descr';
  $pdf1->depto          = $m51_depto;

  if($m51_prazoentnovo && $prazo == 2){
    $pdf1->prazoent = $m51_prazoentnovo;
  }else{
    $pdf1->prazoent       = $m51_prazoent. " DIAS A CONTAR DA DATA DO RECEBIMENTO DESTA ORDEM DE COMPRA";
  }
  
  $pdf1->Snumeroproc    = "pc81_codproc";
  $pdf1->Snumero        = "pc11_numero";
  $pdf1->obs_ordcom_orcamval = "pc23_obs";

  $pdf1->imprime();
}


if ($flag_imprime == true) {

  if ($oConfiguracaoGed->utilizaGED()) {

    try {

      if (!empty($cods)) {
        $m51_codordem_ini = $cods;
      }

      $sTipoDocumento = GerenciadorEletronicoDocumentoConfiguracao::ORDEM_COMPRA;

      $oGerenciador = new GerenciadorEletronicoDocumento();
      $oGerenciador->setLocalizacaoOrigem("tmp/");
      $oGerenciador->setNomeArquivo("{$sTipoDocumento}_{$m51_codordem_ini}.pdf");

      $oStdDadosGED        = new stdClass();
      $oStdDadosGED->nome  = $sTipoDocumento;
      $oStdDadosGED->tipo  = "NUMERO";
      $oStdDadosGED->valor = $m51_codordem_ini;
      $pdf1->objpdf->Output("tmp/{$sTipoDocumento}_{$m51_codordem_ini}.pdf");
      $oGerenciador->moverArquivo(array($oStdDadosGED));
    } catch (Exception $eErro) {

      db_redireciona("db_erros.php?fechar=true&db_erro=" . $eErro->getMessage());
    }
  } else {

    $pdf1->objpdf->Output();
  }
} else {
  db_redireciona("db_erros.php?fechar=true&db_erro=Verifique a(s) ordem(ns) selecionada(s).");
}
