<?php

use App\Helpers\FileHelper;

require_once("libs/db_stdlib.php");
require_once("std/db_stdClass.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_liclicita_classe.php");
require_once("model/compilacaoRegistroPreco.model.php");
require_once("model/licitacao.model.php");
require_once("model/licitacao/SituacaoLicitacao.model.php");
require_once("model/EditalDocumento.model.php");
require_once("classes/db_solicitem_classe.php");
require_once("app/Helpers/FileHelper.php");

$clliclicita       = new cl_liclicita;
$oJson             = new services_json();
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\", "", $_POST["json"])));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$oRetorno->itens   = array();
$dtDia             = date("Y-m-d", db_getsession("DB_datausu"));

switch ($oParam->exec) {


    case 'verificaModalidade':

        $oDaoModalidade = new cl_cflicita();
        $sSqlModalidade = $oDaoModalidade->sql_query_file($oParam->iModalidade);
        $rsModalidade   = $oDaoModalidade->sql_record($sSqlModalidade);
        if ($oDaoModalidade->numrows > 0) {

            $oDados = db_utils::fieldsMemory($rsModalidade, 0);
            $oRetorno->l03_usaregistropreco = $oDados->l03_usaregistropreco;
        }


        break;

    case 'getCodigoTribunal':

        $oDaoModalidade = new cl_cflicita();
        $sSqlModalidade = $oDaoModalidade->sql_query_file($oParam->iModalidade);
        $rsModalidade   = $oDaoModalidade->sql_record($sSqlModalidade);
        if ($oDaoModalidade->numrows > 0) {

            $oDados = db_utils::fieldsMemory($rsModalidade, 0);
            $oRetorno->l03_pctipocompratribunal = $oDados->l03_pctipocompratribunal;
            $oRetorno->l03_presencial = $oDados->l03_presencial;
        }


        break;


    case "salvarTrocaFornecedor":

        require_once("classes/db_pcorcamtroca_classe.php");
        require_once("classes/db_pcorcamjulg_classe.php");

        $oDaoPcorcamtroca = new cl_pcorcamtroca();
        $oDaopcorcamjulg  = new cl_pcorcamjulg();
        try {

            db_inicio_transacao(true);

            $oDaopcorcamjulg->pc24_orcamforne = $oParam->iFornecedorNovo;
            $oDaopcorcamjulg->pc24_orcamitem  = $oParam->iItem;
            $oDaopcorcamjulg->pc24_pontuacao  = $oParam->iPontuacao;
            $oDaopcorcamjulg->alterar(null, null, "pc24_orcamitem = {$oParam->iItem} and pc24_orcamforne = {$oParam->iFornecedorAntigo}");
            if ($oDaopcorcamjulg->erro_status == "0") {
                throw new Exception($oDaopcorcamjulg->erro_msg);
            }

            $oDaoPcorcamtroca->pc25_forneant  = $oParam->iFornecedorAntigo;
            $oDaoPcorcamtroca->pc25_forneatu  = $oParam->iFornecedorNovo;
            $oDaoPcorcamtroca->pc25_motivo    = addslashes($oParam->sMotivo);
            $oDaoPcorcamtroca->pc25_orcamitem = $oParam->iItem;
            $oDaoPcorcamtroca->incluir(null);
            if ($oDaoPcorcamtroca->erro_status == "0") {
                throw new Exception($oDaoPcorcamtroca->erro_msg);
            }

            db_fim_transacao(false);

            $oRetorno->message = "Troca de fornecedor realizada com sucesso.";
        } catch (Exception $eErro) {

            db_fim_transacao(true);
            $oRetorno->message = urlencode($eErro->getMessage());
            $oRetorno->status  = 2;
        }

        break;

    case "listaItensTroca":

        require_once("classes/db_pcorcamforne_classe.php");
        $clpcorcamforne = new cl_pcorcamforne;

        $iLicitacao = $oParam->iLicitacao;
        $aItens     = array();

        $sCamposItens  = "l21_codigo,        ";
        $sCamposItens  = "l21_codpcprocitem, ";
        $sCamposItens .= "pc01_codmater,     ";
        $sCamposItens .= "pc01_descrmater,   ";
        $sCamposItens .= "z01_numcgm,        ";
        $sCamposItens .= "z01_nome,          ";
        $sCamposItens .= "pc23_valor,        ";
        $sCamposItens .= "pc23_quant,        ";
        $sCamposItens .= "pc23_vlrun,        ";
        $sCamposItens .= "pc20_codorc,       ";
        $sCamposItens .= "l20_tipojulg,      ";
        $sCamposItens .= "pc24_pontuacao,    ";
        $sCamposItens .= "pc23_orcamitem,    ";
        $sCamposItens .= "pc11_numero,       ";
        $sCamposItens .= "pc24_orcamforne,   ";
        $sCamposItens .= "pc24_orcamitem,    ";
        $sCamposItens .= "pc23_obs           ";

        $sSqlItens  = "select {$sCamposItens}                                                                             ";
        $sSqlItens .= " from pcorcam                                                                                      ";
        $sSqlItens .= "inner join pcorcamitem          on pcorcamitem.pc22_codorc         = pcorcam.pc20_codorc           ";
        $sSqlItens .= "inner join pcorcamforne         on pcorcamforne.pc21_codorc        = pcorcam.pc20_codorc           ";
        $sSqlItens .= "inner join pcorcamval           on pcorcamval.pc23_orcamitem       = pcorcamitem.pc22_orcamitem    ";
        $sSqlItens .= "                               and pcorcamval.pc23_orcamforne      = pcorcamforne.pc21_orcamforne  ";
        $sSqlItens .= "inner join pcorcamitemlic       on pcorcamitemlic.pc26_orcamitem   = pcorcamitem.pc22_orcamitem    ";
        $sSqlItens .= "inner join liclicitem           on pcorcamitemlic.pc26_liclicitem  = liclicitem.l21_codigo         ";
        $sSqlItens .= "inner join liclicita            on liclicita.l20_codigo            = liclicitem.l21_codliclicita   ";
        $sSqlItens .= "inner join pcorcamjulg          on pcorcamjulg.pc24_orcamitem      = pcorcamitem.pc22_orcamitem    ";
        $sSqlItens .= "                               and pcorcamjulg.pc24_orcamforne     = pcorcamforne.pc21_orcamforne  ";
        $sSqlItens .= "inner join pcprocitem           on liclicitem.l21_codpcprocitem    = pcprocitem.pc81_codprocitem   ";
        $sSqlItens .= "inner join solicitem            on pcprocitem.pc81_solicitem       = solicitem.pc11_codigo         ";
        $sSqlItens .= "inner join solicitempcmater     on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo         ";
        $sSqlItens .= "inner join pcmater              on solicitempcmater.pc16_codmater  = pc01_codmater                 ";
        $sSqlItens .= "inner join cgm                  on cgm.z01_numcgm                  = pcorcamforne.pc21_numcgm      ";
        $sSqlItens .= "where l21_codliclicita = {$iLicitacao}                                                             ";

        $rsItens = db_query($sSqlItens);
        if (pg_numrows($rsItens) > 0) {

            for ($iItem = 0; $iItem < pg_numrows($rsItens); $iItem++) {

                $oItem = db_utils::fieldsMemory($rsItens, $iItem);
                $oDadosItens = new stdClass();
                $oDadosItens->item           = $oItem->pc24_orcamitem;
                $oDadosItens->iOrcamento     = $oItem->pc20_codorc;
                $oDadosItens->cgm            = $oItem->pc24_orcamforne;
                $oDadosItens->nome           = $oItem->z01_nome;
                $oDadosItens->obs            = $oItem->pc23_obs;
                $oDadosItens->valor          = db_formatar($oItem->pc23_valor, "f");
                $oDadosItens->solicita       = $oItem->pc11_numero;
                $oDadosItens->pontuacao      = $oItem->pc24_pontuacao;

                $oDadosItens->fornecedor     = $oItem->z01_numcgm    . " - " . $oItem->z01_nome;
                $oDadosItens->material       = $oItem->pc01_codmater . " - " . $oItem->pc01_descrmater;
                $oDadosItens->quantidade     = $oItem->pc23_quant;
                $oDadosItens->valorunitario  = trim(db_formatar($oItem->pc23_vlrun, "f"));

                $aItens[] = $oDadosItens;
            }
        }

        $oRetorno->dados = $aItens;
        break;


    case "getRegistrosdePreco":

        $sSqlRegistro  = "select distinct l21_codliclicita as licitacao,";
        $sSqlRegistro .= "       pc22_codorc      as orcamento,";
        $sSqlRegistro .= "       pc54_solicita    as solicitacao,";
        $sSqlRegistro .= "       pc54_datainicio  as datainicio,";
        $sSqlRegistro .= "       pc54_datatermino as datatermino,";
        $sSqlRegistro .= "       pc10_resumo      as resumo";
        $sSqlRegistro .= "  from solicitaregistropreco ";
        $sSqlRegistro .= "       inner join solicita       on pc54_solicita    = pc10_numero ";
        $sSqlRegistro .= "       inner join solicitem      on pc10_numero      = pc11_numero ";
        $sSqlRegistro .= "       inner join pcprocitem     on pc81_solicitem   = pc11_codigo ";
        $sSqlRegistro .= "       inner join liclicitem     on pc81_codprocitem = l21_codpcprocitem ";
        $sSqlRegistro .= "       inner join liclicita      on l21_codliclicita = l20_codigo";
        $sSqlRegistro .= "       inner join pcorcamitemlic on pc26_liclicitem  = l21_codigo ";
        $sSqlRegistro .= "       inner join pcorcamitem    on pc26_orcamitem   = pc22_orcamitem  ";
        $sSqlRegistro .= " where cast('{$dtDia}' as date) between pc54_datainicio and pc54_datatermino ";
        $sSqlRegistro .= "   and l20_licsituacao = 10";
        $sSqlRegistro .= "   and l20_instit = ".db_getsession('DB_instit');
        $sSqlRegistro .= " order by l21_codliclicita";
        $rsRegistro    = db_query($sSqlRegistro);

        $oRetorno->itens = db_utils::getCollectionByRecord($rsRegistro, true, false, true);
        break;

    case "getItensRegistro":

        $oCompilacao = new compilacaoRegistroPreco($oParam->iSolicitacao);
        $aItens                 = $oCompilacao->getItens();
        foreach ($aItens as $iIndice => $oItem) {


            $oItemRetono = new stdClass;
            $oItemRetono->codigoitem     = $oItem->getCodigoMaterial();
            $oItemRetono->codigoitemsol  = $oItem->getCodigoItemSolicitacao();
            $oItemRetono->descricaoitem  = $oItem->getDescricaoMaterial();
            $oItemRetono->qtdemin        = $oItem->getQuantidadeMinima();
            $oItemRetono->qtdemax        = $oItem->getQuantidadeMaxima();
            $oItemRetono->codigoitemorca = $oItem->getCodigoItemOrcamento();
            $oItemRetono->resumo         = $oItem->getResumo();
            $oItemRetono->marcado        = false;
            $oItemRetono->bloqueado      = false;
            $oItemRetono->legenda        = "";
            if (isset($oParam->iFornecedor) &&  isset($_SESSION["RP_fornecedores"][$oParam->iFornecedor])) {

                if (in_array($oItem->getCodigoItemOrcamento(), $_SESSION["RP_fornecedores"][$oParam->iFornecedor])) {
                    $oItemRetono->marcado = true;
                }
            }
            /**
             * Verificamos se o o item nao est� Bloqueado ou em desistencia
             */
            if (isset($oParam->iFornecedor) && $oParam->iFornecedor != "") {

                $sSqlBloqueio  = "select min(pc66_datainicial) as datainicial, max(pc66_datafinal) as datafinal ";
                $sSqlBloqueio .= "  from registroprecomovimentacaoitens ";
                $sSqlBloqueio .= "       inner join registroprecomovimentacao on pc58_sequencial = pc66_registroprecomovimentacao ";
                $sSqlBloqueio .= " where pc58_situacao    = 1 ";
                $sSqlBloqueio .= "   and pc58_tipo        = 2 ";
                $sSqlBloqueio .= "   and pc66_pcorcamitem = {$oItem->getCodigoItemOrcamento()}";
                $sSqlBloqueio .= "   and pc66_orcamforne  = {$oParam->iFornecedor}";
                $sSqlBloqueio .= "   and '{$dtDia}'::date between pc66_datainicial and pc66_datafinal";
                $rsBloqueio    = db_query($sSqlBloqueio);
                if (pg_num_rows($rsBloqueio) > 0) {

                    $oBloqueio = db_utils::fieldsMemory($rsBloqueio, 0);
                    if ($oBloqueio->datainicial != "" && $oBloqueio->datafinal != "") {

                        $oItemRetono->bloqueado  = true;
                        $sMsgLegenda             = "Item com desist�ncia de <b>" . db_formatar($oBloqueio->datainicial, "d") . "</b> a ";
                        $sMsgLegenda            .= "<b>" . db_formatar($oBloqueio->datafinal, "d") . "</b>";
                        $oItemRetono->legenda    = urlencode($sMsgLegenda);
                    }
                }
            }

            if (isset($oParam->verificaBloqueios)) {

                $sSqlBloqueio  = "select min(pc66_datainicial) as datainicial, max(pc66_datafinal) as datafinal ";
                $sSqlBloqueio .= "  from registroprecomovimentacaoitens ";
                $sSqlBloqueio .= "       inner join registroprecomovimentacao on pc58_sequencial = pc66_registroprecomovimentacao ";
                $sSqlBloqueio .= " where pc58_situacao    = 1 ";
                $sSqlBloqueio .= "   and pc58_tipo        = 3 ";
                $sSqlBloqueio .= "   and pc66_pcorcamitem = {$oItem->getCodigoItemOrcamento()}";
                $sSqlBloqueio .= "   and '{$dtDia}'::date between pc66_datainicial and pc66_datafinal";
                $rsBloqueio    = db_query($sSqlBloqueio);
                if (pg_num_rows($rsBloqueio) > 0) {

                    $oBloqueio = db_utils::fieldsMemory($rsBloqueio, 0);
                    if ($oBloqueio->datainicial != "" && $oBloqueio->datafinal != "") {

                        $oItemRetono->bloqueado  = true;
                        $sMsgLegenda             = "Item com bloqueio de <b>" . db_formatar($oBloqueio->datainicial, "d") . "</b> a ";
                        $sMsgLegenda            .= "<b>" . db_formatar($oBloqueio->datafinal, "d") . "</b>";
                        $oItemRetono->legenda    = urlencode($sMsgLegenda);
                    }
                }
            }
            $oItemRetono->unidade        = $oItem->getUnidade();
            $oDaoMatUnid                 = db_utils::getDao("matunid");
            $sSqlMatUnid                 = $oDaoMatUnid->sql_query_file($oItem->getUnidade());
            $sUnidade                    = db_utils::fieldsMemory($oDaoMatUnid->sql_record($sSqlMatUnid), 0)->m61_descr;
            $oItemRetono->descrunidade   = urlencode($sUnidade);
            $oItemRetono->indice         = $iIndice;
            $oItemRetono->ativo          = $oItem->isAtivo();
            $oRetorno->itens[] = $oItemRetono;
        }
        break;

    case "getFornecedoresItemRegistro":

        $oCompilacao                    = new compilacaoRegistroPreco($oParam->iSolicitacao);
        $oRetorno->itens                =  $oCompilacao->getFornecedoresPorItem($oParam->iCodigoItemSolicitacao);
        break;

    case "saveValoresFornecedoresRegistro":

        $oCompilacao = new compilacaoRegistroPreco($oParam->iSolicitacao);
        try {

            db_inicio_transacao(true);
            $oCompilacao->setValoresFornecedores(1, $oParam->aItens);
            $oCompilacao->julgarOrcamentoRegistroPreco($oParam->iCodigoOrcamento, $oParam->iCodigoItemOrcamento);
            db_fim_transacao(false);
        } catch (Exception $eErro) {

            db_fim_transacao(true);
            $oRetorno->message = urlencode($eErro->getMessage());
            $oRetorno->status  = 2;
        }
        break;

    case "julgarRegistroPreco":

        $oCompilacao = new compilacaoRegistroPreco($oParam->iSolicitacao);
        try {

            db_inicio_transacao(true);
            $oCompilacao->julgarOrcamentoRegistroPreco($oParam->iOrcamento);
            db_fim_transacao(false);
        } catch (Exception $eErro) {

            db_fim_transacao(true);
            $oRetorno->message = urlencode($eErro->getMessage());
            $oRetorno->status  = 2;
        }

        break;

    case "getVencedoresRegistro":

        $iNumeroCasasDecimais = 2;
        $aParametrosEmpenho   = db_stdClass::getParametro("empparametro", array(db_getsession("DB_anousu")));
        if (count($aParametrosEmpenho) > 0) {
            $iNumeroCasasDecimais = $aParametrosEmpenho[0]->e30_numdec;
        }
        $oCompilacao                    = new compilacaoRegistroPreco($oParam->iSolicitacao);
        $oRetorno->itens                = $oCompilacao->getVencedoresJulgamento($oParam->iOrcamento);
        $oRetorno->iNumeroCasasDecimais = $iNumeroCasasDecimais;
        break;

    case "getFornecedores":

        $oCompilacao     = new compilacaoRegistroPreco($oParam->iSolicitacao);
        $oRetorno->itens = $oCompilacao->getFornecedoresPorOrcamento($oParam->iOrcamento);
        break;

    case "saveItensDesistenciaFornecedor":

        /**
         * Apenas Salvamos os itens que o fornecedor marcou na sessao
         */
        if (!isset($_SESSION["RP_fornecedores"])) {
            $_SESSION["RP_fornecedores"] = array();
        }
        /*
         *
         */
        $oRetorno->lHabilitarBotao = false;
        unset($_SESSION["RP_fornecedores"][$oParam->iFornecedor]);
        $_SESSION["RP_fornecedores"][$oParam->iFornecedor] = array();
        foreach ($oParam->aItens as $oItem) {
            $_SESSION["RP_fornecedores"][$oParam->iFornecedor][] = $oItem->iItemOrcamento;
        }
        /**
         * Verifica o total de Itens Marcados
         */
        $iTotalItensMarcados = 0;
        foreach ($_SESSION["RP_fornecedores"] as $oFornecedor) {
            $iTotalItensMarcados += count($oFornecedor);
        }
        if ($iTotalItensMarcados > 0) {
            $oRetorno->lHabilitarBotao = true;
        }
        break;

    case "salvarDesistencia":

        /**
         * Verificamos se o usu�rio selecionou algum item
         */

        if (isset($_SESSION["RP_fornecedores"])) {

            try {

                db_inicio_transacao();
                $oCompilacao = new compilacaoRegistroPreco($oParam->iSolicitacao);
                $oCompilacao->salvarDesistencia(
                    $_SESSION["RP_fornecedores"],
                    $oParam->sJustificativa,
                    $oParam->iTipoDesistencia,
                    $oParam->dtDataInicial,
                    $oParam->dtDataFinal
                );

                foreach ($_SESSION["RP_fornecedores"] as $iFornecedores => $oFornecedores) {

                    foreach ($oFornecedores as $iItem => $oItem) {

                        $oCompilacao->julgarOrcamentoRegistroPreco($oParam->iOrcamento, $oItem);
                    }
                }

                db_fim_transacao(false);
                unset($_SESSION["RP_fornecedores"]);
            } catch (Exception $eErro) {

                $oRetorno->status = 2;
                $oRetorno->message = urlencode($eErro->getMessage());
                db_fim_transacao(true);
            }
        } else {

            $oRetorno->status = 2;
            $oRetorno->message = "Nenhum item Selecionado!\nProcessamento Cancelado.";
        }
        break;

    case "bloquearItensRegistro":

        try {

            db_inicio_transacao();
            $oCompilacao = new compilacaoRegistroPreco($oParam->iSolicitacao);
            $oCompilacao->bloquearItens(
                $oParam->aItens,
                $oParam->sJustificativa,
                $oParam->iTipoDesistencia,
                $oParam->dtDataInicial,
                $oParam->dtDataFinal
            );
            db_fim_transacao(false);
        } catch (Exception $eErro) {

            $oRetorno->status = 2;
            $oRetorno->message = urlencode($eErro->getMessage());
            db_fim_transacao(true);
        }
        break;

    case "getMovimentosRegistro":

        $oDaoRegistroPrecoMovimentos = db_utils::getDao("registroprecomovimentacaoitens");
        $sWhere                      = "pc20_codorc = {$oParam->iOrcamento} and pc58_situacao = 1 and pc58_tipo = {$oParam->iTipo}";
        $sCampos                     = " distinct  registroprecomovimentacao.*,login,";
        $sCampos                    .= "(select count(*) ";
        $sCampos                    .= "   from registroprecomovimentacaoitens";
        $sCampos                    .= "  where pc66_registroprecomovimentacao=pc58_sequencial) as qtditens";
        $sSqlMovimentos              = $oDaoRegistroPrecoMovimentos->sql_query_orcamento(null, $sCampos, "pc58_data", $sWhere);
        $rsMovimentos                = $oDaoRegistroPrecoMovimentos->sql_record($sSqlMovimentos);
        $oRetorno->itens             = db_utils::getCollectionByRecord($rsMovimentos, false, false, true);
        break;

    case "getItensMovimentosRegistro":

        $oDaoRegistroPrecoMovimentos = db_utils::getDao("registroprecomovimentacaoitens");
        $sWhere                      = "pc66_registroprecomovimentacao = {$oParam->iCodigoMovimentacao} and pc58_situacao = 1";
        $sCampos                     = " distinct  pc01_codmater,z01_nome,pc11_resum,pc01_descrmater,pc66_justificativa";
        $sSqlMovimentos              = $oDaoRegistroPrecoMovimentos->sql_query_orcamento(null, $sCampos, "pc01_codmater", $sWhere);
        $rsMovimentos                = $oDaoRegistroPrecoMovimentos->sql_record($sSqlMovimentos);
        $oRetorno->itens             = db_utils::getCollectionByRecord($rsMovimentos, false, false, true);
        break;

    case "CancelaMovimentos":

        db_inicio_transacao();
        $oDaoRegistroPrecoMovimentos = db_utils::getDao("registroprecomovimentacao");
        foreach ($oParam->aItens as $oItem) {

            $oDaoRegistroPrecoMovimentos->pc58_sequencial = $oItem->iCodigoMovimento;
            $oDaoRegistroPrecoMovimentos->pc58_situacao   = 2;
            $oDaoRegistroPrecoMovimentos->alterar($oItem->iCodigoMovimento);
        }
        db_fim_transacao(false);
        break;

    case "getValoresParciais":

        try {

            $oLicitacao = new licitacao();
            $oRetorno->nValorSaldoTotal = $oLicitacao->getValoresParciais(
                $oParam->iCodigoItemProcesso,
                $oParam->iCodigoDotacao,
                $oParam->iOrcTipoRec
            )->nValorSaldoTotal;
        } catch (Exception $eErro) {

            $oRetorno->status  = 2;
            $oRetorno->message = urlencode($eErro->getMessage());
        }
        break;


    case "getItensConsultaLicitacao":

        $oDaoLicLicitem = db_utils::getDao("liclicitem");
        $oDaoItemLog    = new cl_liclicitaitemlog();
        $sSqlBuscaLog   = $oDaoItemLog->sql_query_file($oParam->iCodigoLicitacao, "l14_liclicita");
        $rsBuscaLog     = $oDaoItemLog->sql_record($sSqlBuscaLog);

        $oLicitacao = new licitacao($oParam->iCodigoLicitacao);

        if ($oDaoItemLog->numrows > 0) {

            $lLog       = true;
            $oInfoLog   = $oLicitacao->getInfoLog();
            $iTotalLinhas = count($oInfoLog->item);
        } else {

            $lLog = false;
            $sBuscaFornecedor = "  (select z01_numcgm ||' - '||z01_nome
                               from liclicita
                                    inner join liclicitem lli     on lli.l21_codliclicita = liclicita.l20_codigo
                                    inner join pcorcamitemlic on pcorcamitemlic.pc26_liclicitem = lli.l21_codigo
                                    inner join pcorcamval     on pcorcamval.pc23_orcamitem = pcorcamitemlic.pc26_orcamitem
                                    inner join pcorcamjulg    on pcorcamval.pc23_orcamitem = pcorcamjulg.pc24_orcamitem
                                                             and pcorcamval.pc23_orcamforne = pcorcamjulg.pc24_orcamforne
                                                             and pcorcamjulg.pc24_pontuacao  = 1
                                    inner join pcorcamforne   on pcorcamforne.pc21_orcamforne = pcorcamjulg.pc24_orcamforne
                                    inner join cgm            on cgm.z01_numcgm = pcorcamforne.pc21_numcgm
                              where lli.l21_codigo = liclicitem.l21_codigo) as z01_nome";

            $sCampos  = " distinct l21_ordem, l21_codigo, pc81_codprocitem, pc11_seq, pc11_codigo, pc11_quant, pc11_vlrun, pc11_reservado, ";
            $sCampos .= " m61_descr, pc01_codmater, pc01_descrmater, e54_autori,e55_quant,  {$sBuscaFornecedor}";

            $sOrdem   = " l21_ordem ";
            $sWhere   = " pc11_quant > 0 and l21_codliclicita = {$oParam->iCodigoLicitacao} ";

            /**
             * adicionado essa condi��o pois licita��es do tipo 102 e 103 nao tem julgamento OC8339
             */
            $result = $clliclicita->sql_record($clliclicita->sql_query(null, "l03_pctipocompratribunal", null, "l20_codigo = {$oParam->iCodigoLicitacao} limit 1"));

            $tipocompratribunal = pg_result($result, 0, 0);

            if ($tipocompratribunal != "102" && $tipocompratribunal != "103") {
                if ($oLicitacao->getSituacao()->getCodigo() == 1) {
                    $sWhere   .= " and pcorcamjulg.pc24_pontuacao = 1 ";
                }
            }

            /**
             *Fim OC8339
             */
            $sSqlItemLicitacao  = $oDaoLicLicitem->sql_query_inf(null, $sCampos, $sOrdem, $sWhere);

            $rsItensDaLicitacao = $oDaoLicLicitem->sql_record($sSqlItemLicitacao);
            $iTotalLinhas = $oDaoLicLicitem->numrows;
        }

        $aItensRetorno = array();
        for ($iLinhaItem = 0; $iLinhaItem < $iTotalLinhas; $iLinhaItem++) {

            if ($lLog) {

                $oStdDadoItem                     = new stdClass();
                $oStdDadoItem->iOrdem             = utf8_decode($oInfoLog->item[$iLinhaItem]->l21_ordem);
                $oStdDadoItem->iCodigo            = utf8_decode($oInfoLog->item[$iLinhaItem]->l21_codigo);
                $oStdDadoItem->sDescricaoMaterial = urlencode("{$oInfoLog->item[$iLinhaItem]->pc01_codmater} - {$oInfoLog->item[$iLinhaItem]->pc01_descrmater}");
                $oStdDadoItem->sFornecedor        = "";
                $oStdDadoItem->iQuantidade        = utf8_decode($oInfoLog->item[$iLinhaItem]->pc11_quant);
                $oStdDadoItem->sUnidadeDeMedida   = utf8_decode($oInfoLog->item[$iLinhaItem]->m61_descr);
                $oStdDadoItem->nValorUnitario     = utf8_decode($oInfoLog->item[$iLinhaItem]->pc11_vlrun);
                $oStdDadoItem->sResumo            = utf8_decode($oInfoLog->item[$iLinhaItem]->pc11_resum);
                //$oStdDadoItem->sObservacao        = utf8_decode($oInfoLog->item[$i]->pc23_obs);
                $aItensRetorno[] = $oStdDadoItem;
            } else {

                $oStdResultItem = db_utils::fieldsMemory($rsItensDaLicitacao, $iLinhaItem);

                $oStdDadoItem                     = new stdClass();
                $oStdDadoItem->iOrdem             = $oStdResultItem->l21_ordem;
                $oStdDadoItem->iCodigo            = $oStdResultItem->l21_codigo;
                $oStdDadoItem->sDescricaoMaterial = urlencode("{$oStdResultItem->pc01_codmater} - {$oStdResultItem->pc01_descrmater}");
                $oStdDadoItem->iQuantidade        = $oStdResultItem->e55_quant;
                $oStdDadoItem->sUnidadeDeMedida   = urlencode($oStdResultItem->m61_descr);
                $oStdDadoItem->sFornecedor        = urlencode($oStdResultItem->z01_nome);
                $oStdDadoItem->nValorUnitario     = trim(db_formatar($oStdResultItem->pc11_vlrun, "f"));
                $oStdDadoItem->iAutorizacao       = urlencode($oStdResultItem->e54_autori);
                $oStdDadoItem->lReservado         = $oStdResultItem->pc11_reservado;
                //$oStdDadoItem->sObservacao        = urlencode($oStdResultItem->pc23_obs);
                $aItensRetorno[] = $oStdDadoItem;
            }
        }
        $oRetorno->aItens = $aItensRetorno;

        break;

    case 'VerificaMembrosModalidade':

        $verifica = $clliclicita->verificaMembrosModalidade($oParam->modalidade, $oParam->equipepregao);

        if ($verifica) {
            $oRetorno->validaMod = 1;
        } else {
            $oRetorno->validaMod = 0;
        }


        break;

    case "adicionarDocumento":

        try {

            $anexo = db_utils::getDao('editaldocumento');
            $sSql = $anexo->sql_query(null, 'l48_sequencial', null, "l48_tipo = '$oParam->tipo' and l48_liclicita = $oParam->licitacao");
            $rsSql = $anexo->sql_record($sSql);

            if ($anexo->numrows > 0) {
                $oRetorno->message = 'Ja existe um documento anexado para esse tipo';
                $oRetorno->status = 2;
                break;
            }

            $erro = false;

            $nometmp = explode('/', $oParam->arquivo);
            $novoNome = strlen($nometmp[1]) > 100 ? substr($nometmp[1], 0, 100) : $nometmp[1];
            $novoNome = FileHelper::sanitizeFileName(FileHelper::replaceSpecialChars($novoNome));

            if (!$erro) {
                $oEdital = new EditalDocumento;
                $oEdital->setCodigo('');
                $oEdital->setTipo($oParam->tipo);
                $oEdital->setLicLicita($oParam->licitacao);
                $oEdital->setNomeArquivo($novoNome);
                $oEdital->setArquivo($oParam->arquivo);
                $oEdital->salvar();
                $oRetorno->message = 'Anexo cadastrado com sucesso!';
            }
        } catch (Exception $oErro) {
            $oRetorno->message = $oErro->getMessage();
            $oRetorno->status  = 2;
        }
        break;

    case "getDocumento":

        $oEdital          = new EditalDocumento();

        $aEditalDocumento = $oEdital->getDocumentos($oParam->licitacao);

        $oRetorno->dados  = array();

        for ($i = 0; $i < count($aEditalDocumento); $i++) {

            $oDocumentos      = new stdClass();
            $oDocumentos->iCodigo    = $aEditalDocumento[$i]->getCodigo();
            $oDocumentos->iTipo = $aEditalDocumento[$i]->getTipo();
            $oDocumentos->iEdital = $aEditalDocumento[$i]->getEdital();
            $oRetorno->dados[] = $oDocumentos;
        }


        $oRetorno->detalhe    = "documentos";
        break;

    case "excluirDocumento":
        try {
            $oEdital          = new EditalDocumento($oParam->codigoDocumento);
            $oEdital->remover();
            $oRetorno->message = 'Documento removido com sucesso!';
        } catch (Exception $oErro) {
            $oRetorno->message = $oErro->getMessage();
            $oRetorno->status  = 2;
        }

        break;

    case "downloadDocumento":
        $oDocumento = new EditalDocumento($oParam->iCodigoDocumento);
        $sNomeArquivo = "tmp/{$oDocumento->getNomeArquivo()}";
        db_inicio_transacao();
        pg_lo_export($conn, $oDocumento->getArquivo(), $sNomeArquivo);
        db_fim_transacao();
        $oRetorno->nomearquivo = $sNomeArquivo;
        break;

    case "buscaPeriodosItem":

        $oAcordoItem      = new AcordoItem($oParam->iCodigoItem);

        $oRetorno->iCodigoItem = $oParam->iCodigoItem;

        $oRetorno->nomeItem    = $oAcordoItem->getMaterial()->getDescricao();
        $oRetorno->periodos    = $oAcordoItem->getPeriodosItem();

        break;

    case 'findDadosLicitacao':
        $oDaoLicEdital = db_utils::getDao("liclicita");

        $campos = "
                liclicita.l20_codigo,
                liclancedital.l47_sequencial,
                liclicita.l20_edital,
                liclicita.l20_nroedital,
                liclicita.l20_anousu,
                pctipocompra.pc50_descr,
                liclicita.l20_numero,
                pctipocompra.pc50_pctipocompratribunal,
                liclicita.l20_objeto,
                l47_origemrecurso,
                liclicita.l20_naturezaobjeto,
                liclicita.l20_cadinicial,
                liclancedital.l47_linkpub,
                liclancedital.l47_descrecurso,
                liclancedital.l47_email,
                liclancedital.l47_dataenvio,
                (CASE
                     WHEN pc50_pctipocompratribunal IN (48,
                                                        49,
                                                        50,
                                                        52,
                                                        53,
                                                        54)
                          AND liclicita.l20_dtpublic IS NOT NULL THEN liclicita.l20_dtpublic
                     WHEN pc50_pctipocompratribunal IN (100,
                                                        101,
                                                        102,
                                                        103,
                                                        106)
                          AND liclicita.l20_datacria IS NOT NULL THEN liclicita.l20_datacria
                 END) AS data_Referencia
    ";

        $sWhere = "
        AND (CASE WHEN pc50_pctipocompratribunal IN (48, 49, 50, 52, 53, 54)
                                     AND liclicita.l20_dtpublic IS NOT NULL THEN EXTRACT(YEAR FROM liclicita.l20_dtpublic)
                                     WHEN pc50_pctipocompratribunal IN (100, 101, 102, 103, 106)
                                     AND liclicita.l20_datacria IS NOT NULL THEN EXTRACT(YEAR FROM liclicita.l20_datacria)
                                END) >= 2020;
    ";
        $sSqlLicEdital = $oDaoLicEdital->sql_query_edital('', " DISTINCT $campos ", '', 'l20_codigo = ' . $oParam->iCodigoLicitacao . $sWhere);
        $rsLicEdital = $oDaoLicEdital->sql_record($sSqlLicEdital);
        $oDados = db_utils::fieldsMemory($rsLicEdital, 0);
        $oRetorno->dadosLicitacao = $oDados;
        break;

    case 'findTipos':
        $sSql = "
            SELECT DISTINCT l03_pctipocompratribunal,
                            l03_codcom,
                            l20_objeto,
                            l20_tipoprocesso
                    FROM liclicita
                    INNER JOIN db_usuarios ON db_usuarios.id_usuario = liclicita.l20_id_usucria
                    INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
                    INNER JOIN db_config ON db_config.codigo = cflicita.l03_instit
                    WHERE liclicita.l20_codigo = $oParam->iLicitacao
        ";

        $oDaoLicitacao = db_utils::getDao('liclicita');
        $rsSql = $oDaoLicitacao->sql_record($sSql);
        $oDados = db_utils::fieldsMemory($rsSql, 0);
        $oRetorno->dadosLicitacao = $oDados;
        break;

    case 'parecerLicitacao':

        $oDaoParecer = db_utils::getDao('parecerlicitacao');
        $sql = $oDaoParecer->sql_query(
            '',
            "l200_sequencial, l200_data,
        (CASE
            WHEN l200_tipoparecer = 1 THEN 'T\E9cnico'
            WHEN l200_tipoparecer = 2 THEN 'Juridico - Edital'
            WHEN l200_tipoparecer = 3 THEN 'Juridico - Julgamento'
            ELSE                           'Juridico - Outros'
        END) as l200_tipoparecer, z01_nome",
            "",
            "l200_licitacao = $oParam->iCodigoLicitacao"
        );
        $result = $oDaoParecer->sql_record($sql);

        for ($count = 0; $count < pg_num_rows($result); $count++) {
            $oDados = db_utils::fieldsMemory($result, $count);
            $oParecer = new stdClass();

            $oParecer->sequencial = $oDados->l200_sequencial;
            $oParecer->dataparecer = $oDados->l200_data;
            $oParecer->tipoparecer = urlencode($oDados->l200_tipoparecer);
            $oParecer->nomeresp = urlencode($oDados->z01_nome);

            $oRetorno->itens[] = $oParecer;
        }

        break;

    case 'getItensLicitacao':

        $oDaoProcItem = db_utils::getDao('pcprocitem');

        $sSql = $oDaoProcItem->sql_query_pcmater(
            null,
            "distinct
                                                       pc01_codmater as codigoitem,
                                                       pc11_seq as seqitem,
                                                       pc01_descrmater as descritem,
                                                       pc01_complmater as complitem,
                                                       pc11_quant as qtditem,
                                                       m61_descr as unidade,
                                                       pc81_codprocitem as procitem",
            "pc11_seq",
            "pc81_codproc=$oParam->iProcCompra and pc23_valor <> 0",
            true
        );

        $rsSql = $oDaoProcItem->sql_record($sSql);

        $sWhere = 'pc81_codproc=' . $oParam->iProcCompra . ' AND (l21_codliclicita <> ' . $oParam->iLicitacao . '
                        or l21_codliclicita = ' . $oParam->iLicitacao . ' and l21_codigo IS NOT NULL
                        or (e54_anulad IS NULL and e55_sequen IS NOT NULL))';

        $sSqlMarcados = $oDaoProcItem->sql_query_pcmater('null', 'DISTINCT pc81_codprocitem', '', $sWhere, true);
        $rsMarcados = db_query($sSqlMarcados);
        //print_r($sSqlMarcados);
        for ($count = 0; $count < pg_numrows($rsSql); $count++) {

            $oItem = db_utils::fieldsMemory($rsSql, $count);

            $oItemLicitacao = new stdClass();
            $oItem->codigoitem = $oItem->codigoitem;
            $oItem->seqitem = $oItem->seqitem;
            $oItem->descritem = urlencode($oItem->descritem);
            $oItem->complitem = urlencode($oItem->complitem);
            $oItem->qtditem = $oItem->qtditem;
            $oItem->unidade = $oItem->unidade;
            $oItem->procitem = $oItem->procitem;
            $oItem->marcado = false;

            for ($i = 0; $i < pg_numrows($rsMarcados); $i++) {

                $oMarcado = db_utils::fieldsMemory($rsMarcados, $i);

                if ($oMarcado->pc81_codprocitem == $oItem->procitem) {
                    $oItem->marcado = true;
                }
            }

            $oRetorno->itens[] = $oItem;
        }

        $oRetorno->erro = pg_numrows($rsSql) ? false : true;


        $oRetorno->erro = pg_numrows($rsSql) ? false : true;

        break;

    case 'insereItens':

        $aItens = $oParam->aItens;

        db_inicio_transacao();

        $clliclicitemlote = db_utils::getDao('liclicitemlote');
        $sSqlLicitacao = $clliclicitemlote->sql_query_licitacao(null, "l21_codpcprocitem", null, "l21_codliclicita=$oParam->licitacao");

        $rsLote = $clliclicitemlote->sql_record($sSqlLicitacao);
        $numrows_lote = $clliclicitemlote->numrows;

        if ($numrows_lote > 0) {

            $itens_incluidos = "";
            $separador       = "";

            for ($x = 0; $x < $numrows_lote; $x++) {

                db_fieldsmemory($rsLote, $x);
                $itens_incluidos .= $separador . $l21_codpcprocitem;
                $separador        = ", ";
            }

            if (strlen(trim($itens_incluidos)) > 0) {
                $arr_itens = explode(",", $itens_incluidos);
            }
        }

        $dbwhere = " ";

        if (strlen(trim($itens_incluidos)) > 0) {
            $dbwhere = " and l21_codpcprocitem not in ($itens_incluidos)";
        }

        $oDaoLiclicitem = db_utils::getDao('liclicitem');
        $oDaoLiclicitem->excluir(null, " l21_codliclicita = $oParam->licitacao $dbwhere ");

        if (!$oDaoLiclicitem->erro_status) {
            $sqlerro = true;
            $erro_msg = $oDaoLiclicitem->erro_msg;
        }

        if (!$sqlerro) {

            $sql_ult_ordem  = "select l21_ordem ";
            $sql_ult_ordem .= "from liclicitem ";
            $sql_ult_ordem .= "where l21_codliclicita = $oParam->licitacao ";
            $sql_ult_ordem .= "order by l21_codigo desc limit 1";

            $res_ult_ordem  = @db_query($sql_ult_ordem);

            if (pg_numrows($res_ult_ordem)) {
                $seq = pg_result($res_ult_ordem, 0, "l21_ordem");
                $seq++;
            } else {
                $seq = 1;
            }
            $seqlote = 0;
            $seqlotereservado = 0;
            for ($count = 0; $count < count($aItens); $count++) {
                $nova_qtd = 0;

                if (isset($aItens[$count])) {

                    if (!$sqlerro) {

                        $achou = false;

                        for ($x = 0; $x < count(@$arr_itens); $x++) {

                            if (trim($arr_itens[$x]) == trim($aItens[$x]->codprocitem)) {
                                $achou = true;
                                break;
                            }
                        }

                        if (!$achou) {

                            $oDaoSolicitemReservado = db_utils::getDao('solicitem');
                            $sWhereSolicitem = ' pc81_codproc = ' . $aItens[$count]->codproc;
                            $sWhereSolicitem .= ' AND pc11_seq = ' . $aItens[$count]->sequencial;
                            $sWhereSolicitem .= ' AND pc81_codprocitem = ' . $aItens[$count]->codprocitem;
                            $sSqlSolicitem = $oDaoSolicitemReservado->sql_query_pcmater('', 'distinct solicitem.*', '', $sWhereSolicitem);
                            //echo $sSqlSolicitem;

                            $rsSolicitem = $oDaoSolicitemReservado->sql_record($sSqlSolicitem);

                            if (pg_numrows($rsSolicitem)) {

                                if ($aItens[$count]->qtdexclusiva) {
                                    //db_criatabela($rsSolicitem);
                                    // echo $aItens[$count]->qtdexclusiva;

                                    /**
                                     * Cadastra o novo item com a quantidade exclusiva na solicitem
                                     */

                                    $oItem = db_utils::fieldsMemory($rsSolicitem, 0);

                                    $clliclicita = db_utils::getDao('liclicita');
                                    $sSqlLiclicita = $clliclicita->sql_query_file(null, "l20_usaregistropreco", null, "l20_codigo=$oParam->licitacao");
                                    $rsLiclicita = $clliclicita->sql_record($sSqlLiclicita);
                                    db_fieldsmemory($rsLiclicita, 0);

                                    if ($l20_usaregistropreco == 't') {

                                        $rsSolicitemControle = $oDaoSolicitemReservado->sql_record("select
                                            abertura.pc55_solicitempai as vinculopai,
                                            abertura.pc55_solicitemfilho as vinculofilho,
                                            itemdaabertura.pc11_codigo as itemdaabertura,
                                            itemdaabertura.pc11_numero as itemdaaberturanumero,
                                            itemdaestimativa.pc11_codigo as itemdaestimativa,
                                            itemdaestimativa.pc11_numero as itemdaestimativanumero,
                                            itemdaestimativa.pc11_quant as itemdaestimativaqtd,
                                            vinculo.pc55_solicitempai,
                                            vinculo.pc55_solicitemfilho
                                            from solicitem as compilacao
                                            join solicita as compsolicita on compsolicita.pc10_numero=compilacao.pc11_numero
                                            left join solicitemvinculo as vinculo on vinculo.pc55_solicitemfilho = compilacao.pc11_codigo
                                            left join solicitem as itemdaestimativa on itemdaestimativa.pc11_codigo = vinculo.pc55_solicitempai
                                            left join solicita as estisolicita on estisolicita.pc10_numero = itemdaestimativa.pc11_numero
                                            left join solicitemvinculo as abertura on abertura.pc55_solicitemfilho=vinculo.pc55_solicitempai
                                            left join solicitem as itemdaabertura on itemdaabertura.pc11_codigo = abertura.pc55_solicitempai
                                            left join solicita as solabertura on solabertura.pc10_numero = itemdaabertura.pc11_numero
                                            left join solicitemregistropreco on pc57_solicitem=compilacao.pc11_codigo
                                            where
                                            compilacao.pc11_codigo = $oItem->pc11_codigo");

                                        $oItemControle = db_utils::fieldsMemory($rsSolicitemControle, 0);
                                    }

                                    $sql_ult_item  = "select pc11_seq ";
                                    $sql_ult_item .= "from solicitem ";
                                    $sql_ult_item .= "where pc11_numero = $oItem->pc11_numero ";
                                    $sql_ult_item .= "order by pc11_seq desc limit 1";

                                    $res_ult_item  = @db_query($sql_ult_item);

                                    if (pg_numrows($res_ult_item)) {
                                        $seqsolicitem = pg_result($res_ult_item, 0, "pc11_seq");
                                        $seqsolicitem++;
                                    } else {
                                        $seqsolicitem = 1;
                                    }
                                    //Compila\E7\E3o
                                    $nova_qtd = floatval($oItem->pc11_quant) - floatval($aItens[$count]->qtdexclusiva);
                                    //Estimativa
                                    $nova_qtd_estimativa = floatval($oItemControle->itemdaestimativaqtd) - floatval($aItens[$count]->qtdexclusiva);

                                    //compila\E7\E3o
                                    $oDaoSolicitemReservado->pc11_numero = $oItem->pc11_numero;
                                    $oDaoSolicitemReservado->pc11_seq   = $seqsolicitem;
                                    $oDaoSolicitemReservado->pc11_quant = $aItens[$count]->qtdexclusiva;
                                    $oDaoSolicitemReservado->pc11_vlrun = $oItem->pc11_vlrun;
                                    $oDaoSolicitemReservado->pc11_prazo = $oItem->pc11_prazo;
                                    $oDaoSolicitemReservado->pc11_pgto = $oItem->pc11_pgto;
                                    $oDaoSolicitemReservado->pc11_resum = $oItem->pc11_resum;
                                    $oDaoSolicitemReservado->pc11_just = $oItem->pc11_just;
                                    $oDaoSolicitemReservado->pc11_liberado = $oItem->pc11_liberado;
                                    $oDaoSolicitemReservado->pc11_servicoquantidade = $oItem->pc11_servicoquantidade;
                                    $oDaoSolicitemReservado->pc11_reservado = 'true';

                                    $oDaoSolicitemReservado->incluir(null);

                                    if (!$oDaoSolicitemReservado->numrows_incluir) {
                                        $erro_msg = $oDaoSolicitemReservado->erro_msg;
                                        $sqlerro = true;
                                        break;
                                    }

                                    if ($l20_usaregistropreco == 't') {
                                        //Estimativa
                                        //$oDaoSolicitemEstimativa = new cl_solicitem;
                                        $oDaoSolicitemEstimativa = db_utils::getDao('solicitem');
                                        $oDaoSolicitemEstimativa->pc11_numero = $oItemControle->itemdaestimativanumero;
                                        $oDaoSolicitemEstimativa->pc11_seq   = $seqsolicitem;
                                        $oDaoSolicitemEstimativa->pc11_quant = $aItens[$count]->qtdexclusiva;
                                        $oDaoSolicitemEstimativa->pc11_vlrun = $oItem->pc11_vlrun;
                                        $oDaoSolicitemEstimativa->pc11_prazo = $oItem->pc11_prazo;
                                        $oDaoSolicitemEstimativa->pc11_pgto = $oItem->pc11_pgto;
                                        $oDaoSolicitemEstimativa->pc11_resum = $oItem->pc11_resum;
                                        $oDaoSolicitemEstimativa->pc11_just = $oItem->pc11_just;
                                        $oDaoSolicitemEstimativa->pc11_liberado = $oItem->pc11_liberado;
                                        $oDaoSolicitemEstimativa->pc11_servicoquantidade = $oItem->pc11_servicoquantidade;
                                        $oDaoSolicitemEstimativa->pc11_reservado = 'true';

                                        $oDaoSolicitemEstimativa->incluir(null);

                                        if (!$oDaoSolicitemEstimativa->numrows_incluir) {
                                            $erro_msg = $oDaoSolicitemEstimativa->erro_msg;
                                            $sqlerro = true;
                                            break;
                                        }

                                        //Abertura
                                        $oDaoSolicitemAbertura = new cl_solicitem;
                                        $oDaoSolicitemAbertura = db_utils::getDao('solicitem');
                                        $oDaoSolicitemAbertura->pc11_numero = $oItemControle->itemdaaberturanumero;
                                        $oDaoSolicitemAbertura->pc11_seq   = $seqsolicitem;
                                        $oDaoSolicitemAbertura->pc11_quant = '0';
                                        $oDaoSolicitemAbertura->pc11_vlrun = $oItem->pc11_vlrun;
                                        $oDaoSolicitemAbertura->pc11_prazo = $oItem->pc11_prazo;
                                        $oDaoSolicitemAbertura->pc11_pgto = $oItem->pc11_pgto;
                                        $oDaoSolicitemAbertura->pc11_resum = $oItem->pc11_resum;
                                        $oDaoSolicitemAbertura->pc11_just = $oItem->pc11_just;
                                        $oDaoSolicitemAbertura->pc11_liberado = $oItem->pc11_liberado;
                                        $oDaoSolicitemAbertura->pc11_servicoquantidade = $oItem->pc11_servicoquantidade;
                                        $oDaoSolicitemAbertura->pc11_reservado = 'true';

                                        $oDaoSolicitemAbertura->incluir(null);

                                        if (!$oDaoSolicitemAbertura->numrows_incluir) {
                                            $erro_msg = $oDaoSolicitemAbertura->erro_msg;
                                            $sqlerro = true;
                                            break;
                                        }
                                    }

                                    /**
                                     * Altera a quantidade do item origem na solicitem
                                     */

                                    if ($oDaoSolicitemReservado->numrows_incluir) {
                                        //compila\E7\E3o
                                        $oItemAlterado = db_utils::getDao('solicitem');
                                        $oItemAlterado->pc11_quant = $nova_qtd > 0 ? $nova_qtd : '0';
                                        $oItemAlterado->pc11_codigo = $oItem->pc11_codigo;
                                        $oItemAlterado->alterar($oItem->pc11_codigo);

                                        if (!$oItemAlterado->numrows_alterar) {
                                            $erro_msg = $oItemAlterado->erro_msg;
                                            $sqlerro = true;
                                            break;
                                        }
                                        if ($l20_usaregistropreco == 't') {
                                            //estimativa
                                            $oItemAlterado = db_utils::getDao('solicitem');
                                            $oItemAlterado->pc11_quant = $nova_qtd_estimativa;
                                            $oItemAlterado->pc11_codigo = $oItemControle->itemdaestimativa;
                                            $oItemAlterado->alterar($oItemControle->itemdaestimativa);

                                            if (!$oItemAlterado->numrows_alterar) {
                                                $erro_msg = $oItemAlterado->erro_msg;
                                                $sqlerro = true;
                                                break;
                                            }
                                            //abertura
                                            $oItemAlterado = db_utils::getDao('solicitem');
                                            $oItemAlterado->pc11_quant = 0;
                                            $oItemAlterado->pc11_codigo = $oItemControle->itemdaabertura;
                                            $oItemAlterado->alterar($oItemControle->itemdaabertura);

                                            if (!$oItemAlterado->numrows_alterar) {
                                                $erro_msg = $oItemAlterado->erro_msg;
                                                $sqlerro = true;
                                                break;
                                            }
                                        }
                                    }

                                    if ($l20_usaregistropreco == 't') {
                                        //compila\E7\E3o
                                        $oDaoSolicitemVinculo = db_utils::getDao('solicitemvinculo');
                                        $oDaoSolicitemVinculo->pc55_solicitempai   = $oDaoSolicitemEstimativa->pc11_codigo;
                                        $oDaoSolicitemVinculo->pc55_solicitemfilho = $oDaoSolicitemReservado->pc11_codigo;
                                        $oDaoSolicitemVinculo->incluir(null);

                                        if (!$oDaoSolicitemVinculo->numrows_incluir) {
                                            $erro_msg = $oDaoSolicitemVinculo->erro_msg;
                                            $sqlerro = true;
                                            break;
                                        }

                                        //estimativa
                                        $oDaoSolicitemVinculo = db_utils::getDao('solicitemvinculo');
                                        $oDaoSolicitemVinculo->pc55_solicitempai   = $oDaoSolicitemAbertura->pc11_codigo;
                                        $oDaoSolicitemVinculo->pc55_solicitemfilho = $oDaoSolicitemEstimativa->pc11_codigo;
                                        $oDaoSolicitemVinculo->incluir(null);

                                        if (!$oDaoSolicitemVinculo->numrows_incluir) {
                                            $erro_msg = $oDaoSolicitemVinculo->erro_msg;
                                            $sqlerro = true;
                                            break;
                                        }

                                        //abertura
                                        // $oDaoSolicitemVinculo = db_utils::getDao('solicitemvinculo');
                                        // $oDaoSolicitemVinculo->pc55_solicitempai   = $oDaoSolicitemAbertura->pc11_codigo;
                                        // $oDaoSolicitemVinculo->pc55_solicitemfilho = $oDaoSolicitemEstimativa->pc11_codigo;
                                        // $oDaoSolicitemVinculo->incluir(null);

                                        // if (!$oDaoSolicitemVinculo->numrows_incluir) {
                                        //   $erro_msg = $oDaoSolicitemVinculo->erro_msg;
                                        //   $sqlerro = true;
                                        //   break;
                                        // }

                                    }

                                    if ($l20_usaregistropreco != 't') {

                                        /**
                                         * Busca os dados do item de origem na tabela solicitemele e inclui na tabela solicitemele
                                         */

                                        $sSqlElemento = 'SELECT pc18_codele FROM solicitemele where pc18_solicitem = ' . $oItem->pc11_codigo;
                                        $rsElemento = db_query($sSqlElemento);
                                        $iSolicitemElemento = db_utils::fieldsMemory($rsElemento, 0)->pc18_codele;
                                        $oDaoSolicitemElem = db_utils::getDao('solicitemele');
                                        $oDaoSolicitemElem->incluir($oDaoSolicitemReservado->pc11_codigo, $iSolicitemElemento);

                                        if (!$oDaoSolicitemElem->numrows_incluir) {
                                            $erro_msg = $oDaoSolicitemElem->erro_msg;
                                            $sqlerro = true;
                                            break;
                                        }
                                    }

                                    if ($l20_usaregistropreco == 't') {

                                        //Compila\E7\E3o
                                        $oDaoSolicitemRegPrecoAlterado = db_utils::getDao('solicitemregistropreco');

                                        $sWhereSolicitem = ' pc57_solicitem = ' . $oItem->pc11_codigo;
                                        $sSqlSolicitemRegPreco = $oDaoSolicitemRegPrecoAlterado->sql_query('', 'distinct pc57_sequencial', '', $sWhereSolicitem);
                                        $rsSolicitemRegPreco = $oDaoSolicitemRegPrecoAlterado->sql_record($sSqlSolicitemRegPreco);

                                        $oSolicitemRegPreco = db_utils::fieldsMemory($rsSolicitemRegPreco, 0);

                                        $oDaoSolicitemRegPrecoAlterado->pc57_quantmax = $nova_qtd;
                                        $oDaoSolicitemRegPrecoAlterado->pc57_sequencial = $oSolicitemRegPreco->pc57_sequencial;
                                        $oDaoSolicitemRegPrecoAlterado->alterar($oSolicitemRegPreco->pc57_sequencial);

                                        if (!$oDaoSolicitemRegPrecoAlterado->numrows_alterar) {
                                            $erro_msg = $oDaoSolicitemRegPrecoAlterado->erro_msg;
                                            $sqlerro = true;
                                            break;
                                        }


                                        /**
                                         * Cadastra o novo item com a quantidade exclusiva na solicitemregistropreco
                                         */

                                        $oDaoSolicitemRegPreco = db_utils::getDao('solicitemregistropreco');

                                        $oDaoSolicitemRegPreco->pc57_solicitem = $oDaoSolicitemReservado->pc11_codigo;
                                        $oDaoSolicitemRegPreco->pc57_quantmax = $aItens[$count]->qtdexclusiva;
                                        $oDaoSolicitemRegPreco->pc57_quantmin = 1;
                                        $oDaoSolicitemRegPreco->pc57_itemorigem = $oDaoSolicitemAbertura->pc11_codigo;
                                        $oDaoSolicitemRegPreco->pc57_ativo = 't';
                                        $oDaoSolicitemRegPreco->pc57_quantidadeexecedente = 0;

                                        $oDaoSolicitemRegPreco->incluir(null);

                                        if (!$oDaoSolicitemRegPreco->numrows_incluir) {
                                            $erro_msg = $oDaoSolicitemRegPreco->erro_msg;
                                            $sqlerro = true;
                                            break;
                                        }

                                        //Estimativa
                                        $oDaoSolicitemRegPrecoAlterado = db_utils::getDao('solicitemregistropreco');

                                        $sWhereSolicitem = ' pc57_solicitem = ' . $oItemControle->itemdaestimativa;
                                        $sSqlSolicitemRegPreco = $oDaoSolicitemRegPrecoAlterado->sql_query('', 'distinct pc57_sequencial', '', $sWhereSolicitem);
                                        $rsSolicitemRegPreco = $oDaoSolicitemRegPrecoAlterado->sql_record($sSqlSolicitemRegPreco);

                                        $oSolicitemRegPreco = db_utils::fieldsMemory($rsSolicitemRegPreco, 0);

                                        $oDaoSolicitemRegPrecoAlterado->pc57_quantmax = $nova_qtd_estimativa;
                                        $oDaoSolicitemRegPrecoAlterado->pc57_sequencial = $oSolicitemRegPreco->pc57_sequencial;
                                        $oDaoSolicitemRegPrecoAlterado->alterar($oSolicitemRegPreco->pc57_sequencial);

                                        if (!$oDaoSolicitemRegPrecoAlterado->numrows_alterar) {
                                            $erro_msg = $oDaoSolicitemRegPrecoAlterado->erro_msg;
                                            $sqlerro = true;
                                            break;
                                        }


                                        /**
                                         * Cadastra o novo item com a quantidade exclusiva na solicitemregistropreco
                                         */

                                        $oDaoSolicitemRegPreco = db_utils::getDao('solicitemregistropreco');

                                        $oDaoSolicitemRegPreco->pc57_solicitem = $oDaoSolicitemEstimativa->pc11_codigo;
                                        $oDaoSolicitemRegPreco->pc57_quantmax = $aItens[$count]->qtdexclusiva;
                                        $oDaoSolicitemRegPreco->pc57_quantmin = 1;
                                        $oDaoSolicitemRegPreco->pc57_itemorigem = $oDaoSolicitemAbertura->pc11_codigo;
                                        $oDaoSolicitemRegPreco->pc57_ativo = 't';
                                        $oDaoSolicitemRegPreco->pc57_quantidadeexecedente = 0;

                                        $oDaoSolicitemRegPreco->incluir(null);

                                        if (!$oDaoSolicitemRegPreco->numrows_incluir) {
                                            $erro_msg = $oDaoSolicitemRegPreco->erro_msg;
                                            $sqlerro = true;
                                            break;
                                        }
                                    }

                                    /**
                                     *  Insere o item reservado na tabela pcprocitem
                                     */

                                    $oDaopcprocitem = db_utils::getDao('pcprocitem');
                                    $oDaopcprocitem->pc81_codproc = $aItens[$count]->codproc;
                                    $oDaopcprocitem->pc81_solicitem = $oDaoSolicitemReservado->pc11_codigo;
                                    $oDaopcprocitem->incluir(null);

                                    if (!$oDaopcprocitem->numrows_incluir) {
                                        $erro_msg = $oDaopcprocitem->erro_msg;
                                        $sqlerro = true;
                                        break;
                                    }

                                    $codprocitemreservado = $oDaopcprocitem->pc81_codprocitem;
                                    //echo "C\F3digo Item Reservado: " . $codprocitemreservado;
                                    /**
                                     * Busca o c\F3digo do material e insere as informa��es na tabela solicitempcmater
                                     */

                                    $sSqlMaterial = "SELECT pc16_codmater from solicitempcmater where pc16_solicitem = " . $oItem->pc11_codigo;
                                    $rsMaterial = db_query($sSqlMaterial);
                                    $iMaterial = db_utils::fieldsMemory($rsMaterial, 0)->pc16_codmater;
                                    //Compilado
                                    $oDaoSolicitempcmater = db_utils::getDao('solicitempcmater');
                                    $oDaoSolicitempcmater->incluir($iMaterial, $oDaoSolicitemReservado->pc11_codigo);

                                    if (!$oDaoSolicitempcmater->numrows_incluir) {
                                        $erro_msg = $oDaoSolicitempcmater->erro_msg;
                                        $sqlerro = true;
                                        break;
                                    }
                                    if ($l20_usaregistropreco == 't') {
                                        //Estimativa
                                        $oDaoSolicitempcmater = db_utils::getDao('solicitempcmater');
                                        $oDaoSolicitempcmater->incluir($iMaterial, $oDaoSolicitemEstimativa->pc11_codigo);

                                        if (!$oDaoSolicitempcmater->numrows_incluir) {
                                            $erro_msg = $oDaoSolicitempcmater->erro_msg;
                                            $sqlerro = true;
                                            break;
                                        }
                                        //Abertura
                                        $oDaoSolicitempcmater = db_utils::getDao('solicitempcmater');
                                        $oDaoSolicitempcmater->incluir($iMaterial, $oDaoSolicitemAbertura->pc11_codigo);

                                        if (!$oDaoSolicitempcmater->numrows_incluir) {
                                            $erro_msg = $oDaoSolicitempcmater->erro_msg;
                                            $sqlerro = true;
                                            break;
                                        }
                                    }

                                    if ($l20_usaregistropreco != 't') {

                                        /**
                                         * Busca informa��es da dota��o vinculada ao item na pcdotac
                                         */

                                        $sSqlDotacaoItem = ' SELECT pcdotac.*
                                                            FROM solicitem
                                                            INNER JOIN pcdotac ON pc13_codigo = pc11_codigo
                                                            WHERE pc11_codigo = ' . $oItem->pc11_codigo . '
                                                             and pc11_seq = ' . $aItens[$count]->sequencial;
                                        $rsSqlDotacaoItem = db_query($sSqlDotacaoItem);
                                        $oDotacaoItem = db_utils::fieldsMemory($rsSqlDotacaoItem, 0);

                                        /**
                                         * Insere a dota��o do item j� cadastrado no novo item reservado,
                                         * setando no campo quantidade a quantidade exclusiva reservada
                                         */

                                        $oDaoDotacReserva = db_utils::getDao('pcdotac');
                                        $oDaoDotacReserva->pc13_anousu = $oDotacaoItem->pc13_anousu;
                                        $oDaoDotacReserva->pc13_coddot = $oDotacaoItem->pc13_coddot;
                                        $oDaoDotacReserva->pc13_codigo = $oDaoSolicitemReservado->pc11_codigo;
                                        $oDaoDotacReserva->pc13_depto  = $oDotacaoItem->pc13_depto;
                                        $oDaoDotacReserva->pc13_quant  = $aItens[$count]->qtdexclusiva;
                                        $oDaoDotacReserva->pc13_valor  = $oDotacaoItem->pc13_valor;
                                        $oDaoDotacReserva->pc13_codele = $oDotacaoItem->pc13_codele;
                                        $oDaoDotacReserva->incluir(null);

                                        if (!$oDaoDotacReserva->numrows_incluir) {
                                            $erro_msg = $oDaoDotacReserva->erro_msg;
                                            $sqlerro = true;
                                            break;
                                        }

                                        /**
                                         * Depois de incluir a dota��o reservada, deve-se alterar o valor da dota��o original
                                         */

                                        $oDaoDotacao = db_utils::getDao('pcdotac');
                                        $oDaoDotacao->pc13_quant = $nova_qtd;
                                        $oDaoDotacao->pc13_sequencial = $oDotacaoItem->pc13_sequencial;
                                        $oDaoDotacao->alterar($oDotacaoItem->pc13_sequencial);

                                        if (!$oDaoDotacao->numrows_alterar) {
                                            $erro_msg = $oDaoDotacao->erro_msg;
                                            $sqlerro = true;
                                            break;
                                        }
                                    }

                                    /**
                                     * Busca as informa��es do item na tabela solicitemunid incluindo o novo item reservado
                                     */

                                    $sSqlSolicitemUnid = "SELECT * from solicitemunid where pc17_codigo = " . $oItem->pc11_codigo;
                                    $rsSolicitemUnid = db_query($sSqlSolicitemUnid);
                                    $oSolicitemUnid = db_utils::fieldsMemory($rsSolicitemUnid, 0);
                                    //compila\E7\E3o
                                    $oDaoSolicitemUnidReservado = db_utils::getDao('solicitemunid');
                                    $oDaoSolicitemUnidReservado->pc17_unid = $oSolicitemUnid->pc17_unid;
                                    $oDaoSolicitemUnidReservado->pc17_quant = $aItens[$count]->qtdexclusiva;
                                    $oDaoSolicitemUnidReservado->pc17_codigo = $oDaoSolicitemReservado->pc11_codigo;
                                    $oDaoSolicitemUnidReservado->incluir(null);

                                    if (!$oDaoSolicitemUnidReservado->numrows_incluir) {
                                        $erro_msg = $oDaoSolicitemUnidReservado->erro_msg;
                                        $sqlerro = true;
                                        break;
                                    }

                                    if ($l20_usaregistropreco == 't') {
                                        //estimativa
                                        $oDaoSolicitemUnidReservado = db_utils::getDao('solicitemunid');
                                        $oDaoSolicitemUnidReservado->pc17_unid = $oSolicitemUnid->pc17_unid;
                                        $oDaoSolicitemUnidReservado->pc17_quant = $aItens[$count]->qtdexclusiva;
                                        $oDaoSolicitemUnidReservado->pc17_codigo = $oDaoSolicitemEstimativa->pc11_codigo;
                                        $oDaoSolicitemUnidReservado->incluir(null);

                                        if (!$oDaoSolicitemUnidReservado->numrows_incluir) {
                                            $erro_msg = $oDaoSolicitemUnidReservado->erro_msg;
                                            $sqlerro = true;
                                            break;
                                        }

                                        //abertura
                                        $oDaoSolicitemUnidReservado = db_utils::getDao('solicitemunid');
                                        $oDaoSolicitemUnidReservado->pc17_unid = $oSolicitemUnid->pc17_unid;
                                        $oDaoSolicitemUnidReservado->pc17_quant = $aItens[$count]->qtdexclusiva;
                                        $oDaoSolicitemUnidReservado->pc17_codigo = $oDaoSolicitemAbertura->pc11_codigo;
                                        $oDaoSolicitemUnidReservado->incluir(null);

                                        if (!$oDaoSolicitemUnidReservado->numrows_incluir) {
                                            $erro_msg = $oDaoSolicitemUnidReservado->erro_msg;
                                            $sqlerro = true;
                                            break;
                                        }
                                    }
                                    /**
                                     * Depois de incluindo o item reservado, altera-se a quantidade do item.
                                     */

                                    $oDaoSolicitemUnid = db_utils::getDao('solicitemunid');
                                    $oDaoSolicitemUnid->pc17_quant = $nova_qtd;
                                    $oDaoSolicitemUnid->pc17_codigo = $oSolicitemUnid->pc17_codigo;
                                    $oDaoSolicitemUnid->alterar($oSolicitemUnid->pc17_codigo);

                                    if (!$oDaoSolicitemUnid->numrows_alterar) {
                                        $erro_msg = $oDaoSolicitemUnid->erro_msg;
                                        $sqlerror = true;
                                        break;
                                    }
                                }
                            }

                            /**
                             * Insere o novo item na liclicitem
                             */

                            $clliclicitem = db_utils::getDao('liclicitem');
                            $clliclicitem->l21_codliclicita  = $oParam->licitacao;
                            $clliclicitem->l21_codpcprocitem = $aItens[$count]->codprocitem;
                            $clliclicitem->l21_situacao      = "0";
                            $clliclicitem->l21_ordem         = $seq;
                            if ($aItens[$count]->sigilo == 1) {
                                $clliclicitem->l21_sigilo        = "t";
                            } else {
                                $clliclicitem->l21_sigilo        = "f";
                            }

                            $clliclicitem->incluir(null);

                            if (!$clliclicitem->erro_status) {
                                $erro_msg = $clliclicitem->erro_msg;
                                $sqlerro = true;
                                break;
                            }

                            $seq++;

                            /**
                             * Se o item tiver quantidade exclusiva reservada, cadastra-se um novo item
                             * na liclicitem com o valor reservado
                             */

                            if ($aItens[$count]->qtdexclusiva) {

                                $clliclicitemreservado = db_utils::getDao('liclicitem');
                                $clliclicitemreservado->l21_codliclicita  = $clliclicitem->l21_codliclicita;
                                $clliclicitemreservado->l21_codpcprocitem = $codprocitemreservado;
                                $clliclicitemreservado->l21_situacao      = $clliclicitem->l21_situacao;
                                $clliclicitemreservado->l21_ordem         = $seq;
                                $clliclicitemreservado->l21_reservado     = $aItens[$count]->qtdexclusiva ? true : false;
                                $clliclicitemreservado->incluir(null);

                                if (!$clliclicitemreservado->numrows_incluir) {
                                    $erro_msg = $clliclicitemreservado->erro_msg;
                                    $sqlerro = true;
                                    break;
                                }

                                $seq++;
                            }
                        }
                    }

                    if (!$sqlerro) {

                        if (!$achou) {

                            $coditem = $clliclicitem->l21_codigo;

                            if ($clliclicitemreservado->l21_reservado) {
                                $coditemreservado = $clliclicitemreservado->l21_codigo;
                            }

                            /**
                             * Vincula os itens ao lote
                             **/

                            $res_liclicitem = $clliclicitem->sql_record($clliclicitem->sql_query_sol($coditem, "pc11_codigo, pc68_nome, pc69_seq, pc80_tipoprocesso"));

                            if ($clliclicitem->numrows > 0) {
                                db_fieldsmemory($res_liclicitem, 0);
                            }

                            $clliclicitemlote->l04_liclicitem = $coditem;

                            /**
                             * Vincula os itens reservados ao lote
                             **/

                            if ($clliclicitemreservado->l21_reservado) {

                                $res_liclicitemreservado = $clliclicitemreservado->sql_record(
                                    $clliclicitemreservado->sql_query_sol($coditemreservado, "pc11_codigo, pc68_nome, pc69_seq, pc80_tipoprocesso")
                                );

                                if ($clliclicitemreservado->numrows > 0) {
                                    $oItemReservado = db_utils::fieldsMemory($res_liclicitemreservado, 0);
                                }

                                $clliclicitemlotereservado->l04_liclicitem = $coditemreservado;
                            }


                            /**
                             * Tipo de julgamento por item
                             */
                            if ($oParam->tipojulg == 1) {
                                $clliclicitemlote->l04_descricao = "LOTE_AUTOITEM_" . $pc11_codigo;
                                if ($pc80_tipoprocesso == 2) {
                                    $erro_msg = "Tipo de compra \E9 por Lote";
                                    $sqlerro = true;
                                    break;
                                }

                                if ($clliclicitemreservado->l21_reservado) {
                                    $clliclicitemlotereservado->l04_descricao = "LOTE_AUTOITEM_" . $oItemReservado->pc11_codigo;
                                }
                            }

                            /**
                             * Tipo de julgamento Global
                             */
                            if ($oParam->tipojulg == 2) {
                                $clliclicitemlote->l04_descricao = "GLOBAL";

                                if ($clliclicitemreservado->l21_reservado) {
                                    $clliclicitemlotereservado->l04_descricao = "GLOBAL";
                                }
                            }
                            /**
                             * Tipo de julgamento por lote
                             */
                            if ($oParam->tipojulg == 3) {
                                $clliclicitemlote->l04_descricao = $pc68_nome;
                            }


                            if (!empty($clliclicitemlote->l04_descricao) && in_array($oParam->tipojulg, array(1, 2, 3))) {
                                // echo 'tst' . $count + 1;
                                // exit;
                                if ($oParam->tipojulg == 3) {
                                    $clliclicitemlote->l04_seq = $pc69_seq;
                                } else {
                                    $seqlote++;
                                    $clliclicitemlote->l04_seq = $seqlote;
                                }
                                if($clliclicitemlote->l04_seq == 1){
                                    $clliclicitemlote->l04_numerolote = null;
                                }

                                $clliclicitemlote->incluir(null);

                                if ($clliclicitemlote->erro_status == 0) {
                                    $erro_msg = $clliclicitemlote->erro_msg;
                                    $sqlerro = true;
                                    break;
                                }
                            }

                            /**
                             * Inclus�o na tabela liclicitemlote para o novo item com valor reservado
                             */

                            if (!empty($clliclicitemlotereservado->l04_descricao) && in_array($oParam->tipojulg, array(1, 2))) {

                                $oDaoLoteReservado = db_utils::getDao('liclicitemlote');
                                $sqlItemLote = 'SELECT * from liclicitemlote where l04_liclicitem = ' . $clliclicitemlotereservado->l04_liclicitem;
                                $rsItemLote = db_query($sqlItemLote);

                                if (!pg_numrows($rsItemLote)) {
                                    $seqlotereservado++;
                                    $oDaoLoteReservado->l04_descricao = $clliclicitemlotereservado->l04_descricao;
                                    $oDaoLoteReservado->l04_liclicitem = $clliclicitemlotereservado->l04_liclicitem;
                                    $oDaoLoteReservado->l04_seq = $seqlotereservado;
                                    $oDaoLoteReservado->l04_numerolote = null;
                                    $oDaoLoteReservado->incluir(null);

                                    if (!$oDaoLoteReservado->numrows_incluir) {
                                        $erro_msg = $oDaoLoteReservado->erro_msg;
                                        $sqlerro = true;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if (!$sqlerro) {

            $clpcorcamitem = db_utils::getDao('pcorcamitem');
            $res_pcorcam = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmaterlic(null, "pc22_codorc", null, "l20_codigo = $oParam->licitacao limit 1"));

            if ($clpcorcamitem->numrows > 0) {   // Tem or�amento para esta Licitacao

                db_fieldsmemory($res_pcorcam, 0);

                for ($x = 0; $x < count($aItens); $x++) {

                    if ($aItens[$x]->codprocitem) {

                        $clpcorcamitemlic = db_utils::getDao('pcorcamitemlic');
                        $clpcorcamitemlic->sql_record($clpcorcamitemlic->sql_query(null, "*", null, "pc81_codprocitem = " . $aItens[$x]->codprocitem));

                        if (!$clpcorcamitemlic->numrows) {
                            $clliclicitem = db_utils::getDao('liclicitem');
                            $res_liclicitem = $clliclicitem->sql_record($clliclicitem->sql_query_file(null, "l21_codigo", null, "l21_codpcprocitem = " . $aItens[$x]->codprocitem));

                            $clliclicitemreservado = db_utils::getDao('liclicitem');
                            $res_liclicitemreservado = $clliclicitem->sql_record($clliclicitem->sql_query_file(null, "l21_codigo", null, "l21_codpcprocitem = " . $aItens[$x]->codprocitem));
                            //echo $clliclicitem->sql_query_file(null, "l21_codigo", null, "l21_codpcprocitem = " . $codprocitemreservado);

                            if ($clliclicitem->numrows > 0) {
                                db_fieldsmemory($res_liclicitem, 0);

                                $clpcorcamitem->pc22_codorc = $pc22_codorc;
                                $clpcorcamitem->incluir(null);

                                if ($clpcorcamitem->erro_status == 0) {
                                    $sqlerro  = true;
                                    $erro_msg = $clpcorcamitem->erro_msg;
                                    break;
                                }

                                if ($aItens[$x]->qtdexclusiva) {

                                    $clpcorcamitemreservado = db_utils::getDao('pcorcamitem');
                                    $clpcorcamitemreservado->pc22_codorc = $pc22_codorc;
                                    $clpcorcamitemreservado->incluir(null);

                                    if (!$clpcorcamitemreservado->erro_status) {
                                        $sqlerro  = true;
                                        $erro_msg = $clpcorcamitem->erro_msg;
                                        break;
                                    }

                                    $pc22_orcamitemreservado = $clpcorcamitemreservado->pc22_orcamitem;
                                }

                                /**
                                 * @todo verificar se ser� inclu�do o novo item reservado na pcorcamitemlic
                                 */

                                if (!$sqlerro) {

                                    $pc22_orcamitem = $clpcorcamitem->pc22_orcamitem;

                                    $clpcorcamitemlic->pc26_orcamitem  = $pc22_orcamitem;
                                    $clpcorcamitemlic->pc26_liclicitem = $l21_codigo;
                                    $clpcorcamitemlic->incluir(null);

                                    if ($clpcorcamitemlic->erro_status == 0) {
                                        $sqlerro  = true;
                                        $erro_msg = $clpcorcamitemlic->erro_msg;
                                        break;
                                    }

                                    if ($aItens[$x]->qtdexclusiva) {

                                        /**
                                         * Inclui o item reservado na pcorcamitemlic
                                         */

                                        $oItem = db_utils::fieldsMemory($res_liclicitemreservado, 0);

                                        $clpcorcamitemlicreservado = db_utils::getDao('pcorcamitemlic');
                                        $clpcorcamitemlicreservado->pc26_orcamitem = $pc22_orcamitemreservado;
                                        $clpcorcamitemlicreservado->pc26_liclicitem = $oItem->l21_codigo;
                                        $clpcorcamitemlicreservado->incluir(null);

                                        if (!$clpcorcamitemlicreservado->erro_status) {
                                            $sqlerro = true;
                                            $erro_msg = $clpcorcamitemlicreservado->erro_msg;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($sqlerro == false) {
            $rsOrcsigiloso = db_query("select l20_orcsigiloso from liclicita where l20_codigo = $oParam->licitacao;");
            $ol20_orcsigiloso = db_utils::fieldsMemory($rsOrcsigiloso, 0);
            $l20_orcsigiloso = $ol20_orcsigiloso->l20_orcsigiloso;
            $oRetorno->l20_orcsigiloso = $l20_orcsigiloso;
            if ($l20_orcsigiloso == null) {
                db_query("UPDATE liclicita SET l20_orcsigiloso = $oParam->l20_orcsigiloso WHERE l20_codigo = $oParam->licitacao;");
            }
        }

        /**
         * Alterar o parametro passado na db_fim_transacao para receber o $sqlerro,
         * mas antes tem que verificar os preenchimentos das tabelas anteriores
         */

        db_fim_transacao($sqlerro);

        if ($sqlerro) {
            $oRetorno->status = 2;
        } else {
            $oRetorno->status = 1;
        }
        $oRetorno->erro_msg = urlencode($erro_msg);

        break;

    case 'getLotesPendentes':

        $oDaoLiclicitemLote = db_utils::getDao('liclicitemlote');
        $sWhere = ' l20_codigo = ' . $oParam->iLicitacao;
        $sSqlLote = $oDaoLiclicitemLote->sql_query('', 'liclicitemlote.*', 'l04_descricao', $sWhere);

        $rsLote = $oDaoLiclicitemLote->sql_record($sSqlLote);
        $descInicial = '';
        $countLote = 0;

        for ($count = 0; $count < pg_numrows($rsLote); $count++) {

            $oDadosLote = db_utils::fieldsMemory($rsLote, $count);
            if ($descInicial != $oDadosLote->l04_descricao) {
                $descInicial = $oDadosLote->l04_descricao;
                $countLote += 1;
            }

            $oLote = new stdClass();
            $oLote->sequencial = $countLote;
            $oLote->codigo     = $oDadosLote->l04_codigo;
            $oLote->descricao  = $oDadosLote->l04_descricao;

            $oRetorno->itens[] = $oLote;
        }

        break;

    case 'getTipoJulgamento':

        $rsTipo = db_query('SELECT l20_tipojulg, l20_anousu,l20_datacria FROM liclicita WHERE l20_codigo = ' . $oParam->licitacao);
        $oLicitacao = db_utils::fieldsMemory($rsTipo, 0);
        $oRetorno->tipo = $oLicitacao->l20_tipojulg;
        $oRetorno->data = $oLicitacao->l20_datacria;

        break;

    case 'getLotes':

        $sSqlLotes = "
      SELECT l04_descricao,
             l04_codigo
             FROM liclicitemlote
             INNER JOIN liclicitem ON l04_liclicitem = l21_codigo
             INNER JOIN liclicita ON l21_codliclicita = l20_codigo
             WHERE l20_codigo = $oParam->iLicitacao and l04_descricao =
                      (SELECT l04_descricao
                      FROM liclicitemlote
                      WHERE l04_codigo =
                          (SELECT db150_lote
                          FROM obrasdadoscomplementareslote WHERE db150_sequencial = $oParam->loteReferencia))
      ";

        $rsLotes = db_query($sSqlLotes);
        $oRetorno->itens = db_utils::getCollectionByRecord($rsLotes, '', '', true);

        break;

    case 'getRedirecionaEdital':

        $clliclancedital = db_utils::getDao('liclancedital');
        $sSql = $clliclancedital->sql_query('', 'l20_naturezaobjeto, l47_sequencial', '', 'l20_codigo = ' . $oParam->licitacao);
        $rsSql = $clliclancedital->sql_record($sSql);

        $natureza_objeto = db_utils::fieldsMemory($rsSql, 0)->l20_naturezaobjeto;
        $sequencial = db_utils::fieldsMemory($rsSql, 0)->l47_sequencial;

        if (in_array(intval($natureza_objeto), array(1, 7))) {
            $oRetorno->redireciona_edital = true;
        } else {
            $oRetorno->redireciona_edital = false;
        }


        break;

    case 'itensSemLoteCount':
        // Retorna a quantidade de itens sem lote
        $sqlItensSemLote = "
            SELECT
                COUNT(li.l21_codigo) AS itensSemLotCount
            FROM
                liclicitem li
            LEFT JOIN
                liclicitemlote lil ON li.l21_codigo = lil.l04_liclicitem
            WHERE
                li.l21_codliclicita = $oParam->licitacao
                AND lil.l04_liclicitem IS NULL;
        ";

        $rsItensSemLote = db_query($sqlItensSemLote);
        $itensSemLote = db_utils::fieldsMemory($rsItensSemLote, 0);
        $oRetorno->itenssemlotcount = $itensSemLote->itenssemlotcount;

        break;

    case 'VerificaMembrosModalidadeParaLei1':

        $oRetorno->validacao = $clliclicita->verificaMembrosModalidadeParaLei1($oParam->modalidade, $oParam->comissao);
    
        break;
}
echo $oJson->encode($oRetorno);
