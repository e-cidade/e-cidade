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

    require_once ("libs/db_stdlib.php");
    require_once ("libs/db_conecta.php");
    require_once ("libs/db_sessoes.php");
    require_once ("libs/db_usuariosonline.php");
    require_once ("libs/db_utils.php");
    require_once ("dbforms/db_funcoes.php");
    require_once ("dbforms/db_classesgenericas.php");
    require_once ("classes/db_bens_classe.php");
    require_once ("classes/db_bemfoto_classe.php");
    require_once ("classes/db_bensimoveis_classe.php");
    require_once ("classes/db_bensmater_classe.php");
    require_once ("classes/db_situabens_classe.php");
    require_once ("classes/db_clabens_classe.php");
    require_once ("classes/db_histbem_classe.php");
    require_once ("classes/db_bensplaca_classe.php");
    require_once ("classes/db_benscadlote_classe.php");
    require_once ("classes/db_benslote_classe.php");
    require_once ("classes/db_departdiv_classe.php");
    require_once ("classes/db_histbemdiv_classe.php");
    require_once ("classes/db_bensdiv_classe.php");
    require_once ("classes/db_cfpatriplaca_classe.php");
    require_once ("classes/db_cfpatri_classe.php");
    require_once ("classes/db_bensmarca_classe.php");
    require_once ("classes/db_bensmedida_classe.php");
    require_once ("classes/db_bensmodelo_classe.php");
    require_once ("classes/db_benscedente_classe.php") ;
    require_once ("classes/db_bensdepreciacao_classe.php");
    require_once ("classes/db_conlancambem_classe.php");
    require_once ("classes/db_bensmaterialempempenho_classe.php");
    require_once ("classes/db_bensempnotaitem_classe.php");

    $clbenscedente            = new cl_benscedente();
    $cldepartdiv              = new cl_departdiv;
    $clbens                   = new cl_bens;
    $clbemfoto                = new cl_bemfoto;
    $clhistbem                = new cl_histbem;
    $clclabens                = new cl_clabens;
    $clbensimoveis            = new cl_bensimoveis;
    $clbensmater              = new cl_bensmater;
    $cldb_estrut              = new cl_db_estrut;
    $clsituabens              = new cl_situabens;
    $clbensplaca              = new cl_bensplaca;
    $clbenscadlote            = new cl_benscadlote;
    $clbenslote               = new cl_benslote;
    $clhistbemdiv             = new cl_histbemdiv;
    $clbensdiv                = new cl_bensdiv;
    $clcfpatri                = new cl_cfpatri;
    $clcfpatriplaca           = new cl_cfpatriplaca;
    $clbensmarca              = new cl_bensmarca;
    $clbensmedida             = new cl_bensmedida;
    $clbensmodelo             = new cl_bensmodelo;
    $clbensdepreciacao        = new cl_bensdepreciacao;
    $clconlancambem           = new cl_conlancambem;
    $clbensmaterialempempenho = new cl_bensmaterialempempenho;
    $clbensempnotaitem        = new cl_bensempnotaitem;
    $cl_empnotaitem           = new cl_empnotaitem();
    $clempempenho             = new cl_empempenho;

    db_postmemory($HTTP_POST_VARS);
    db_postmemory($HTTP_GET_VARS);

    $db_opcao = 3;
    $db_botao = true;
    $sqlerro = false;

    if (isset($excluir)) {

      $erro_msg = "";

      $iPlacaInicial = $t52_placaini;
      $iPlacaFinal   = $t52_placafim;

      if(empty($t52_placaini) || empty($t52_placafim)){
        db_msgbox('Campos Placa inicial e Final devem ser preenchidos');
        db_redireciona("pat1_bensglobal003.php");
      }

      $sWhere  = "where t52_ident BETWEEN '{$iPlacaInicial}' and '$iPlacaFinal'";
      $sWhere .= " and t52_instit = " . db_getsession("DB_instit");
      $inner   = " join clabens ON clabens.t64_codcla = bens.t52_codcla " ;
      $sSqlVerificaIntervaloPlaca = "select * from bens $inner $sWhere";
      $rsVerificaIntervaloPlaca   = $clbensplaca->sql_record($sSqlVerificaIntervaloPlaca);
   
      if($clbensplaca->numrows > 0 ) {

        db_inicio_transacao();

        $iQuant = $clbensplaca->numrows;

        for ($Cont = 0; $Cont < $iQuant; $Cont++) {
            db_fieldsmemory($rsVerificaIntervaloPlaca, $Cont);
            $oDataImplantacao  = new DBDate(date("Y-m-d", db_getsession('DB_datausu')));
            $oInstituicao      = new Instituicao(db_getsession('DB_instit'));
            $result1           = $clbensmater->sql_record($clbensmater->sql_query_file(null,"*","","t53_codbem = ".$t52_bem));
            $numrows           = $clbensmater->numrows;
           
            if ($numrows > 0) {
              db_fieldsmemory($result1, 0);
            }
            if (ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio($oDataImplantacao, $oInstituicao) && $t53_empen > 0) { 
              $resultado = processarExclusaoBem($oDataImplantacao, $oInstituicao, $t52_bem, $t52_dtexclusao, $t52_dtaqu, $t53_empen, $t53_ntfisc, $t52_valaqu, $t64_class, $t52_descr, $t52_ident,$rsVerificaIntervaloPlaca, $iQuant);
              if ($resultado['erro']) {
                $sqlerro = true;
                $erro_msg = $resultado['mensagem'];
              } 
            } else {
              $resultado = processarExclusaoBemLegacy($t52_bem, $t53_ntfisc, $t53_empen, $rsVerificaIntervaloPlaca, $iQuant);
              if ($resultado['erro']) {
                $sqlerro = true;
                $erro_msg = $resultado['mensagem'];
              }  
            }
         
        }
 
        db_fim_transacao($sqlerro);

      }else{
        db_msgbox('Intervalo de Placa(s) não existente' );
        db_redireciona("pat1_bensglobal003.php?".$PARTICULARmetros."liberaaba=true&chavepesquisa=$t52_bem");
      }

    }
    ?>
    <html>
    <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    </head>
    <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_escondeFieldsetImovel();js_escondeFieldsetMaterial();" >
    <br><br>
    <table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
        <center>
          <?
            include ("forms/db_frm_bensglobalexclusao.php");
          ?>
        </center>
      </td>
      </tr>
    </table>
      <?
        db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
      ?>
    </body>
    </html>

    <?
    if(isset($excluir)){

      if (trim(@$erro_msg)!=""){
           db_msgbox($erro_msg);
      }else{
        db_msgbox('Bens excluidos com sucesso');
      }
      if($sqlerro==true){
        if($clbens->erro_campo!=""){
          echo "<script> document.form1.".$clbens->erro_campo.".style.backgroundColor='#99A9AE';</script>";
          echo "<script> document.form1.".$clbens->erro_campo.".focus();</script>";
        }
      } else {
        db_redireciona("pat1_bensglobal003.php?".$parametros."liberaaba=true&chavepesquisa=$t52_bem");
      }
    }

function processarExclusaoBem($oDataImplantacao, $oInstituicao, $t52_bem, $t52_dtexclusao, $t52_dtaqu, $t53_empen, $t53_ntfisc, $t52_valaqu, $t64_class, $t52_descr, $t52_ident,$rsVerificaIntervaloPlaca, $iQuant)
{
  global $cl_empnotaitem, $clempempenho;
  
  $sqlerro = false;
  $erro_msg = '';
     
  if (empty($t52_dtexclusao)) {
    $sqlerro = true;
    $erro_msg = "Campo data exclusão obrigatorio.";
    return ['erro' => $sqlerro, 'mensagem' => $erro_msg];
  }        

  $dtexclusao  = DateTime::createFromFormat('d/m/Y', $t52_dtexclusao);
  $dtaquisicao = new DateTime($t52_dtaqu);
 
  if ($dtexclusao < $dtaquisicao) {
      $sqlerro = true;
      $erro_msg = "Campo data exclusão deve ser igual ou maior que o campo data da aquisição do bem.";
      return ['erro' => $sqlerro, 'mensagem' => $erro_msg];
  }

  $sWhere = " e69_numero = '{$t53_ntfisc}' and e62_numemp = '{$t53_empen}' ";
  $sSqlNotaLiquidacao = $cl_empnotaitem->sql_query_empenho_item("", " * ", "", $sWhere);
  $rsNotaLiquidacao = $cl_empnotaitem->sql_record($sSqlNotaLiquidacao);

  $valorLiquidado = db_utils::fieldsMemory($rsNotaLiquidacao, 0)->e72_vlrliq;
  $sCampos = " e60_anousu ";
  $rsEmpenho = $clempempenho->sql_record($clempempenho->sql_query($t53_empen, $sCampos));

  if ($valorLiquidado > 0 ) {
      $sqlerro = true;
      $erro_msg = "O bem não poderá ser removido, pois a nota fiscal $t53_ntfisc do empenho $t53_empen está liquidada.";
      return ['erro' => $sqlerro, 'mensagem' => $erro_msg];
  }

  $sSqlConsultaFimPeriodoContabil = "SELECT * FROM condataconf WHERE c99_anousu = " . db_getsession('DB_anousu') . " and c99_instit = " . db_getsession('DB_instit');
  $rsConsultaFimPeriodoContabil = db_query($sSqlConsultaFimPeriodoContabil);
  
  if (pg_num_rows($rsConsultaFimPeriodoContabil) > 0) {
      $oFimPeriodoContabil = db_utils::fieldsMemory($rsConsultaFimPeriodoContabil, 0);
      $data_formatada = $dtexclusao->format('Y-m-d');
      if ($oFimPeriodoContabil->c99_data != '' && db_strtotime($data_formatada) <= db_strtotime($oFimPeriodoContabil->c99_data)) {
          $sqlerro = true;
          $erro_msg = "Data inferior à data do fim do período contábil.";
          return ['erro' => $sqlerro, 'mensagem' => $erro_msg];
      }
  }
          
  if (!$sqlerro) {
    $anoEmpenho = db_utils::fieldsMemory($rsEmpenho, 0)->e60_anousu;
    $anoEstorno = $dtexclusao->format('Y');
    $iCodigoDocumento = $anoEstorno == $anoEmpenho ? 209 : 215;

    $oParametros = new stdClass();
    $oParametros->iCodigoEmpNotaItem = db_utils::fieldsMemory($rsNotaLiquidacao, 0)->e72_sequencial;
    $oParametros->iNumeroEmpenho = db_utils::fieldsMemory($rsNotaLiquidacao, 0)->e62_numemp;
    $oParametros->nValorNota = $t52_valaqu;
    $oParametros->iCodigoNota = db_utils::fieldsMemory($rsNotaLiquidacao, 0)->e72_codnota;
    $oParametros->sJustificativa = "";
    $oParametros->iClassificacao = $t64_class;
    $oParametros->sDescricaoItem = "";
    $oParametros->iQuantidadeItem = db_utils::fieldsMemory($rsNotaLiquidacao, 0)->e72_qtd;
    $oParametros->dataaquisicao = $t52_dtaqu;
    $oParametros->t52_dtexclusao = $t52_dtexclusao;
    $oParametros->t52_bem = $t52_bem;
    $oParametros->t52_descr = $t52_descr;
    $oParametros->t52_ident = $t52_ident;
    $oParametros->t53_ntfisc = $t53_ntfisc;
    $oParametros->e69_numero = db_utils::fieldsMemory($rsNotaLiquidacao, 0)->e69_numero;

    if (processarLancamento($iCodigoDocumento, $oParametros->iCodigoEmpNotaItem, $oParametros)) {
        if ($cl_empnotaitem->numrows > 0) {
            inserirBensExcluidos($oParametros);
        }
        $resultado = excluirBem($rsVerificaIntervaloPlaca, $iQuant,$t52_bem);
        if ($resultado['erro']) {
            $sqlerro = true;
            $erro_msg = $resultado['mensagem'];
            return ['erro' => $sqlerro, 'mensagem' => $erro_msg];
        }
    }
  }         
 return ['erro' => $sqlerro, 'mensagem' => $erro_msg];
}

function processarExclusaoBemLegacy($t52_bem, $t53_ntfisc, $t53_empen, $rsVerificaIntervaloPlaca, $iQuant) 
{
    global $clbensmater, $cl_empnotaitem;

    $sqlerro = false;
    $erro_msg = '';

    $result1 = $clbensmater->sql_record($clbensmater->sql_query_file(null, "*", "", "t53_codbem = " . $t52_bem));
    $numrows = $clbensmater->numrows;

    if ($numrows > 0) {
        db_fieldsmemory($result1, 0);

        $sWhere = " e69_numero = '{$t53_ntfisc}' and e62_numemp = '{$t53_empen}' ";
        $sSqlNotaLiquidacao = $cl_empnotaitem->sql_query_empenho_item("", " * ", "", $sWhere);
        $rsNotaLiquidacao = $cl_empnotaitem->sql_record($sSqlNotaLiquidacao);

        $oParametros = new stdClass();
        $oParametros->iCodigoEmpNotaItem = db_utils::fieldsMemory($rsNotaLiquidacao, 0)->e72_sequencial;
        $oParametros->t52_bem = $t52_bem;

        if ($cl_empnotaitem->numrows > 0) {
            inserirBensExcluidos($oParametros);
        }
    }

    $resultado = excluirBem($rsVerificaIntervaloPlaca, $iQuant, $t52_bem);
    if ($resultado['erro']) {
        $sqlerro = true;
        $erro_msg = $resultado['mensagem'];
    }

    return ['erro' => $sqlerro, 'mensagem' => $erro_msg];
}
        
function excluirBem($rsVerificaIntervaloPlaca, $iQuant,$t52_bem) 
{
    global $clbensplaca, $clbensdepreciacao, $clbensempnotaitem, $clbenslote, $clbensmater, $clhistbem, $clbenscedente, $clbens, $clconlancambem, $clbemfoto;

    $sqlerro = false;
    $erro_msg = '';

    for ($Cont = 0; $Cont < $iQuant; $Cont++) {
        db_fieldsmemory($rsVerificaIntervaloPlaca, $Cont);

        $clbensplaca->excluir('', "t41_bem = $t52_bem");
        if ($clbensplaca->erro_status == 0) {
            $sqlerro = true;
            $erro_msg = $clbensplaca->erro_msg;
        }
    }

    for ($Cont = 0; $Cont < $iQuant; $Cont++) {
        db_fieldsmemory($rsVerificaIntervaloPlaca, $Cont);

        $entidades = [
            ['obj' => $clbensdepreciacao, 'campo' => 't44_bens'],
            ['obj' => $clbensempnotaitem, 'campo' => 'e136_bens'],
            ['obj' => $clbenslote, 'campo' => 't43_bem'],
            ['obj' => $clbensmater, 'campo' => 't53_codbem'],
            ['obj' => $clhistbem, 'campo' => 't56_codbem'],
            ['obj' => $clbenscedente, 'campo' => 't09_bem']
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
    }

    for ($Cont = 0; $Cont < $iQuant; $Cont++) {
        db_fieldsmemory($rsVerificaIntervaloPlaca, $Cont);

        $clbens->excluir('', "t52_bem = $t52_bem");
        if ($clbens->erro_status == 0) {
            $sqlerro = true;
            $erro_msg = $clbens->erro_msg;
        }

        $clconlancambem->excluir('', "c110_bem = $t52_bem");
        if ($clconlancambem->erro_status == 0) {
            $sqlerro = true;
            $erro_msg = $clconlancambem->erro_msg;
        }
    }

    $quantidade = pg_affected_rows($rsVerificaIntervaloPlaca);
    for ($cont = 0; $cont < $quantidade; $cont++) {
        db_fieldsmemory($rsVerificaIntervaloPlaca, $cont);
        $clbemfoto->excluir('', "t54_numbem = $t52_bem");
        if ($clbemfoto->erro_status == 0) {
            $sqlerro = true;
            $erro_msg = $clbemfoto->erro_msg;
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

      $oDaoBensExcluidos->incluir(null);

      if ($oDaoBensExcluidos->erro_status == 0) {
        throw new \Exception($oDaoBensExcluidos->erro_msg);
        $sqlerro = true;
      }
  
    }  

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

?>
