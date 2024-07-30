<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBselller Servicos de Informatica
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_bens_classe.php");
include("classes/db_bemfoto_classe.php");
include("classes/db_clabens_classe.php");
include("classes/db_bensmater_classe.php");
include("classes/db_bensimoveis_classe.php");
include("classes/db_bensbaix_classe.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_bensplaca_classe.php");
include("classes/db_departdiv_classe.php");
include("classes/db_histbemdiv_classe.php");
include("classes/db_histbem_classe.php");
include("classes/db_histbemtrans_classe.php");
include("classes/db_bensdiv_classe.php");
include("classes/db_cfpatri_classe.php");
include("classes/db_cfpatriplaca_classe.php");
include("classes/db_benslote_classe.php");
include("classes/db_bensmarca_classe.php");
include("classes/db_bensmedida_classe.php");
include("classes/db_bensmodelo_classe.php");
include("classes/db_benscedente_classe.php");
include("classes/db_conlancambem_classe.php");
include("classes/db_bensdepreciacao_classe.php");
include("classes/db_bensmaterialempempenho_classe.php");
include("classes/db_bensempnotaitem_classe.php");
include("classes/db_parametrointegracaopatrimonial_classe.php");
include("classes/db_bensexcluidos_classe.php");
require_once("model/empenho/EmpenhoFinanceiro.model.php");
require_once("model/patrimonio/BemClassificacao.model.php");


$clbensmaterialempempenho = new cl_bensmaterialempempenho;
$clhistbem                = new cl_histbem;
$clbensdepreciacao        = new cl_bensdepreciacao;
$clconlancambem           = new cl_conlancambem;
$clbenscedente            = new cl_benscedente;
$cldepartdiv              = new cl_departdiv;
$cldb_estrut              = new cl_db_estrut;
$clbens                   = new cl_bens;
$clbemfoto                = new cl_bemfoto;
$clbensmater              = new cl_bensmater;
$clbensimoveis            = new cl_bensimoveis;
$clclabens                = new cl_clabens;
$clhistbemocorrencia      = new cl_histbensocorrencia;
$clbensdiv = new cl_bensdiv;
$clbensbaix               = new cl_bensbaix;
$clbensplaca              = new cl_bensplaca;
$clhistbemdiv             = new cl_histbemdiv;
$clhistbemtrans           = new cl_histbemtrans;
$clbensdiv                = new cl_bensdiv;
$clcfpatri                = new cl_cfpatri;
$clcfpatriplaca           = new cl_cfpatriplaca;
$clbenslote               = new cl_benslote;
$clbensmarca              = new cl_bensmarca;
$clbensmedida             = new cl_bensmedida;
$clbensmodelo             = new cl_bensmodelo;
$clbensempnotaitem        = new cl_bensempnotaitem;
db_postmemory($HTTP_POST_VARS);
$db_opcao = 33;
$db_botao = false;
$mostrarCampo = "none";
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  
  $oDataImplantacao  = new DBDate(date("Y-m-d", db_getsession('DB_datausu')));
  $oInstituicao      = new Instituicao(db_getsession('DB_instit'));
  $result1           = $clbensmater->sql_record($clbensmater->sql_query_file(null,"*","","t53_codbem = ".$t52_bem));
  $numrows           = $clbensmater->numrows;
  
  if($numrows>0){
    db_fieldsmemory($result1, 0);
  }

  if (ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio($oDataImplantacao, $oInstituicao) && $t53_empen > 0) {
    $mostrarCampo = 1;
    if ($t52_dtexclusao == '') {
      $sqlerro=true;
      $erro_msg = "Campo data exclusão obrigatorio.";
    }
    
    $dtexclusao  = DateTime::createFromFormat('d/m/Y', $t52_dtexclusao);
    $dtaquisicao = DateTime::createFromFormat('d/m/Y', $t52_dtaqu);
    if ($dtexclusao < $dtaquisicao) {
      $sqlerro=true;
      $erro_msg = "Campo data exclusão deve ser igual ou maior que o campo data da aquisição do bem.";
    }
  
    if ($sqlerro == false) {
     
      $oDaoEmpnotaitem    = db_utils::getDao('empnotaitem');
      $sWhere             = " e69_numero =  '{$t53_ntfisc}' and e62_numemp = '{$t53_empen}' ";
      $sSqlNotaLiquidacao = $oDaoEmpnotaitem->sql_query_empenho_item(""," * ","",$sWhere );
      $rsNotaLiquidacao   = $oDaoEmpnotaitem->sql_record($sSqlNotaLiquidacao);

      $valorLiquidado     = db_utils::fieldsMemory($rsNotaLiquidacao, 0)->e72_vlrliq;
      $sCampos            = " e60_anousu ";
      $clempempenho       = new cl_empempenho;
      $rsEmpenho          = $clempempenho->sql_record($clempempenho->sql_query($t53_empen,$sCampos));

      if ($valorLiquidado > 0) {
        $sqlerro = true;
        $erro_msg = "O bem não poderá ser removido, pois a nota fiscal $t53_ntfisc do empenho $t53_empen está liquidada.";
      } 

      $sSqlConsultaFimPeriodoContabil   = "SELECT * FROM condataconf WHERE c99_anousu = " . db_getsession('DB_anousu') . " and c99_instit = " . db_getsession('DB_instit');
      $rsConsultaFimPeriodoContabil     = db_query($sSqlConsultaFimPeriodoContabil);
      if (pg_num_rows($rsConsultaFimPeriodoContabil) > 0) {
        $oFimPeriodoContabil = db_utils::fieldsMemory($rsConsultaFimPeriodoContabil, 0);
        $data_objeto = DateTime::createFromFormat('d/m/Y', $t52_dtexclusao);
        $data_formatada = $data_objeto->format('Y-m-d');
        if ($oFimPeriodoContabil->c99_data != '' && db_strtotime($data_formatada) <= db_strtotime($oFimPeriodoContabil->c99_data)) {
          $sqlerro = true;
          $erro_msg = "Data inferior à data do fim do período contábil.";
        }
      }
     
      if ($sqlerro == false) { 
  
        $anoEmpenho = db_utils::fieldsMemory($rsEmpenho, 0)->e60_anousu;
        $dataObj = DateTime::createFromFormat('d/m/Y', $t52_dtexclusao);
        $anoEstorno =  $dataObj->format('Y');
        $iCodigoDocumento =  $anoEstorno == $anoEmpenho ? 209 : 215;
  
        $oParametros->iCodigoEmpNotaItem = db_utils::fieldsMemory($rsNotaLiquidacao, 0)->e72_sequencial;
        $oParametros->iNumeroEmpenho     = db_utils::fieldsMemory($rsNotaLiquidacao, 0)->e62_numemp;
        $oParametros->nValorNota         = $t52_valaqu;
        $oParametros->iCodigoNota        = db_utils::fieldsMemory($rsNotaLiquidacao, 0)->e72_codnota;
        $oParametros->sJustificativa     = "";
        $oParametros->iClassificacao     = $t64_class;
        $oParametros->sDescricaoItem     = "";
        $oParametros->iQuantidadeItem    = db_utils::fieldsMemory($rsNotaLiquidacao, 0)->e72_qtd;
        $oParametros->dataaquisicao      = $t52_dtaqu;
        $oParametros->t52_dtexclusao     = $t52_dtexclusao;
        $oParametros->t52_bem            = $t52_bem;
        $oParametros->t52_descr          = $t52_descr;
        $oParametros->t52_ident          = $t52_ident;
        $oParametros->t53_ntfisc         = $t53_ntfisc;
        $oParametros->e69_numero         = db_utils::fieldsMemory($rsNotaLiquidacao, 0)->e69_numero;

        if ($sqlerro == false) {
          if(processarLancamento($iCodigoDocumento,$oParametros->iCodigoEmpNotaItem, $oParametros)) {
            if ($oDaoEmpnotaitem->numrows > 0) {
              inserirBensExcluidos($oParametros);
            }
            $resultado = excluirBem($t52_bem);
            if ($resultado['erro']) {
                $sqlerro = true;
                $erro_msg = $resultado['mensagem'];
            } 
          }
        }
      
      }

    }
  }else {

    $result1           = $clbensmater->sql_record($clbensmater->sql_query_file(null,"*","","t53_codbem = ".$t52_bem));
    $numrows           = $clbensmater->numrows;
 
    if ($numrows>0) {
      db_fieldsmemory($result1, 0);

      $oDaoEmpnotaitem    = db_utils::getDao('empnotaitem');
      $sWhere             = " e69_numero =  '{$t53_ntfisc}' and e62_numemp = '{$t53_empen}' ";
      $sSqlNotaLiquidacao = $oDaoEmpnotaitem->sql_query_empenho_item(""," * ","",$sWhere );
      $rsNotaLiquidacao   = $oDaoEmpnotaitem->sql_record($sSqlNotaLiquidacao);

      $oParametros->iCodigoEmpNotaItem = db_utils::fieldsMemory($rsNotaLiquidacao, 0)->e72_sequencial;
      $oParametros->t52_bem            = $t52_bem;
      
      if ($oDaoEmpnotaitem->numrows > 0) {
        inserirBensExcluidos($oParametros);
      }
    }
    $resultado = excluirBem($t52_bem);
    if ($resultado['erro']) {
        $sqlerro = true;
        $erro_msg = $resultado['mensagem'];
    }
  }

  db_fim_transacao($sqlerro);

  if($sqlerro == true){
    $erro_msg = 'Erro ao excluir bem: '.$erro_msg;
  }else{
    $erro_msg = 'Bem excluido com sucesso';
  }

  $db_opcao = 3;
  $db_botao = true;

}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result = $clbens->sql_record($clbens->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
   $oDataImplantacao  = new DBDate(date("Y-m-d", db_getsession('DB_datausu')));
   $oInstituicao      = new Instituicao(db_getsession('DB_instit'));

   if (ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio($oDataImplantacao, $oInstituicao) && $t53_empen > 0) {
      $mostrarCampo = 1;
   } 
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
	include("forms/db_frmbens.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($excluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clbens->erro_campo!=""){
      echo "<script> document.form1.".$clbens->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbens->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
   db_redireciona('pat1_bens006.php');
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.bensimoveis.disabled=false;
         CurrentWindow.corpo.iframe_bensimoveis.location.href='pat1_bensimoveis001.php?db_opcaoal=33&t54_codbem=".@$t52_bem."';
         parent.document.formaba.bensmater.disabled=false;
         CurrentWindow.corpo.iframe_bensmater.location.href='pat1_bensmater001.php?db_opcaoal=33&t53_codbem=".@$t52_bem."';
         parent.document.formaba.bensfotos.disabled=false;
         CurrentWindow.corpo.iframe_bensfotos.location.href='pat1_cadgeralfotos001.php?db_opcaoal=33&t52_codbem=".@$t52_bem."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('bensimoveis');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }

 function processarLancamento($iCodigoDocumento, $iCodigoItemNota, $oParametros) 
 {
 
  $oDataImplantacao  = new DBDate(date("Y-m-d", db_getsession('DB_datausu')));
  $oInstituicao      = new Instituicao(db_getsession('DB_instit'));

  if ( !ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio($oDataImplantacao, $oInstituicao) ) {
    return false;
  }

  if ( empty($oParametros->iNumeroEmpenho) ) {
    throw new Exception('Número de empenho não informado.');
  }

  $oDaoEmpnotaitem  = db_utils::getDao('empnotaitem');
  $sSqlNotaLiquidacao = $oDaoEmpnotaitem->sql_query_empenho_item($iCodigoItemNota);
  $rsNotaLiquidacao = $oDaoEmpnotaitem->sql_record($sSqlNotaLiquidacao);

  if ( $oDaoEmpnotaitem->erro_status == "0" ) {
    throw new DBException("Erro ao buscar item da nota, item não encontrado: $iCodigoItemNota.");
  }
  
  $iCodigoNotaLiquidacao = db_utils::fieldsMemory($rsNotaLiquidacao, 0)->e72_codnota;
  $iNumeroNotaLiquidacao = $oParametros->e69_numero;
  $iCodigoItemEstoque    = db_utils::fieldsMemory($rsNotaLiquidacao, 0)->e72_empempitem;

  $aItensEmpenho = $iCodigoItemEstoque;
  $dataaquisicao = explode('/',$oParametros->t52_dtexclusao);
  $oDataEstorno  = $dataaquisicao[2].'-'.$dataaquisicao[1].'-'.$dataaquisicao[0];
  
  $oEventoContabil = new EventoContabil($iCodigoDocumento, db_getsession("DB_anousu"));

  $nValorNota = $oParametros->nValorNota;
 
  $sObservacao = "ESTORNO DE LANÇAMENTO EM LIQUIDAÇÃO DO TOMBAMENTO DO BEM {$oParametros->t52_descr}, ";
  $sObservacao .= " CÓDIGO {$oParametros->t52_bem}, PLACA {$oParametros->t52_ident}, NÚMERO DA NOTA FISCAL {$iNumeroNotaLiquidacao }.";

  if ($oParametros->iClassificacao != "") {
    $codclass = str_replace('.', '', $oParametros->iClassificacao);    
    $oDaoClaBens          = db_utils::getDao("clabens");
    $sWhere               = " clabens.t64_class = '{$codclass}'";
    $sWhere              .= " and clabens.t64_instit = " . db_getsession("DB_instit");
    $sSqlClassificacao    = $oDaoClaBens->sql_query_file(null, "*", null, $sWhere);
    $rsDadosClassificacao = $oDaoClaBens->sql_record($sSqlClassificacao);;
    if ($oDaoClaBens->numrows > 0) {

      $oDadosClassificacao  = db_utils::fieldsMemory($rsDadosClassificacao, 0);
      $iCodigoClassificacao = $oDadosClassificacao->t64_codcla;
      $sClassificacao       = $oDadosClassificacao->t64_class;
    }  
  }

  $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oParametros->iNumeroEmpenho);
  $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
  $oContaCorrenteDetalhe->setEmpenho($oEmpenhoFinanceiro);
  $oContaCorrenteDetalhe->setDotacao($oEmpenhoFinanceiro->getDotacao());
  $oRecurso = new Recurso($oEmpenhoFinanceiro->getDotacao()->getRecurso());
  $oContaCorrenteDetalhe->setRecurso($oRecurso);
  $oLancamentoAuxiliarEmLiquidacao = new LancamentoAuxiliarEmLiquidacaoMaterialPermanente();
  $oLancamentoAuxiliarEmLiquidacao->setClassificacao(new BemClassificacao($iCodigoClassificacao));
  $oLancamentoAuxiliarEmLiquidacao->setObservacaoHistorico($sObservacao);
  $oLancamentoAuxiliarEmLiquidacao->setFavorecido($oEmpenhoFinanceiro->getFornecedor()->getCodigo());
  $oLancamentoAuxiliarEmLiquidacao->setCodigoElemento($aItensEmpenho);
  $oLancamentoAuxiliarEmLiquidacao->setNumeroEmpenho($oParametros->iNumeroEmpenho);
  $oLancamentoAuxiliarEmLiquidacao->setCodigoDotacao($oEmpenhoFinanceiro->getDotacao()->getCodigo());
  $oLancamentoAuxiliarEmLiquidacao->setCodigoNotaLiquidacao($iCodigoNotaLiquidacao);
  $oLancamentoAuxiliarEmLiquidacao->setValorTotal($nValorNota);
  $oLancamentoAuxiliarEmLiquidacao->setContaCorrenteDetalhe($oContaCorrenteDetalhe);
  $oEventoContabil->executaLancamento($oLancamentoAuxiliarEmLiquidacao,$oDataEstorno);

  return true;
}

function excluirBem($t52_bem) 
{
  global $clbensplaca, $clbensmaterialempempenho, $clbensdepreciacao, $clbensempnotaitem, $clbenslote, $clbensmater, $clhistbem, $clbenscedente, $clconlancambem, $clbens, $clbemfoto,$clhistbemocorrencia,$clbensdiv;
  
  $sqlerro = false;
  $erro_msg = '';

  $entidades = [
      ['obj' => $clbensplaca, 'campo' => 't41_bem'],
      ['obj' => $clbensmaterialempempenho, 'campo' => 't11_bensmaterial'],
      ['obj' => $clbensdepreciacao, 'campo' => 't44_bens'],
      ['obj' => $clbensempnotaitem, 'campo' => 'e136_bens'],
      ['obj' => $clbenslote, 'campo' => 't43_bem'],
      ['obj' => $clbensmater, 'campo' => 't53_codbem'],
      ['obj' => $clhistbem, 'campo' => 't56_codbem'],
      ['obj' => $clhistbemocorrencia, 'campo' => 't69_codbem'],
      ['obj' => $clbensdiv, 'campo' => 't33_bem'],
      ['obj' => $clbenscedente, 'campo' => 't09_bem'],
      ['obj' => $clconlancambem, 'campo' => 'c110_bem'],
      ['obj' => $clbens, 'campo' => 't52_bem'],
      ['obj' => $clbemfoto, 'campo' => 't54_numbem']
  ];

  foreach ($entidades as $entidade) {
      if (!$sqlerro) {
          $entidade['obj']->excluir('', "{$entidade['campo']} = $t52_bem");
          if ($entidade['obj']->erro_status == 0) {
              $sqlerro = true;
              $erro_msg = $entidade['obj']->erro_msg;
          }
      }
  }

  return ['erro' => $sqlerro, 'mensagem' => $erro_msg];
}

function inserirBensExcluidos($oDadosEmpnotaitem) 
{
  
    $oBens  = db_utils::getDao("bens");
    $sSql   = $oBens->sql_query_file(null, '*', null, "t52_bem = {$oDadosEmpnotaitem->t52_bem}");
    $rsBens = $oBens->sql_record($sSql);
   
    if ($oBens->numrows > 0) {

      $oResult = db_utils::fieldsMemory($rsBens, 0);
  
      $oDaoBensExcluidos                     = db_utils::getDao("bensexcluidos");
      $oDaoBensExcluidos->t136_bens          = $oDadosEmpnotaitem->t52_bem;
      $oDaoBensExcluidos->t136_empnotaitem   = $oDadosEmpnotaitem->iCodigoEmpNotaItem;
      $oDaoBensExcluidos->t136_numcgm        = $oResult->t52_numcgm;
      $oDaoBensExcluidos->t136_instit        = $oResult->t52_instit;
      $oDaoBensExcluidos->t136_depart        = $oResult->t52_depart;
      $oDaoBensExcluidos->t136_codcla        = $oResult->t52_codcla;
      $oDaoBensExcluidos->t136_bensmarca     = $oResult->t52_bensmarca;
      $oDaoBensExcluidos->t136_bensmodelo    = $oResult->t52_bensmodelo;
      $oDaoBensExcluidos->t136_bensmedida    = $oResult->t52_bensmedida;
      $oDaoBensExcluidos->t136_descr         = $oResult->t52_descr;
      $oDaoBensExcluidos->t136_dtaqu         = $oResult->t52_dtaqu;
      $oDaoBensExcluidos->t136_obs           = $oResult->t52_obs;
      $oDaoBensExcluidos->t136_valaqu        = $oResult->t52_valaqu;
      $oDaoBensExcluidos->t136_ident         = $oResult->t52_ident;

      db_inicio_transacao();
      $oDaoBensExcluidos->incluir(null);

      if ($oDaoBensExcluidos->erro_status == 0) {
        throw new \Exception($oDaoBensExcluidos->erro_msg);
        $sqlerro = true;
      }
  
      db_fim_transacao($sqlerro);
    }  

}

?>
