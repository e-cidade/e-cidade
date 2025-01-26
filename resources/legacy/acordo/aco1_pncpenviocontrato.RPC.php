<?php
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once('libs/db_app.utils.php');
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("std/DBTime.php");
require_once("std/DBDate.php");
require_once("classes/db_acordo_classe.php");
require_once("classes/db_contratos_classe.php");
require_once("classes/db_acocontratopncp_classe.php");
require_once("model/contrato/PNCP/ContratoPNCP.model.php");
require_once("model/Acordo.model.php");

db_app::import("configuracao.DBDepartamento");
$envs = parse_ini_file('config/PNCP/.env', true);
$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oErro             = new stdClass();
$oRetorno          = new stdClass();
$oRetorno->status  = 1;

switch ($oParam->exec) {
    case 'getContratos':
        $clcontratos = new cl_acordo;

        if ($oParam->tipo == "1") {
            $whereCustomParam = " AND ac213_numerocontrolepncp is null ";
        } else {
            $whereCustomParam = " AND ac213_numerocontrolepncp != '' ";
        }
        $whereCustomParam .= "AND ac16_anousu = $oParam->ano";

        $rsContrato = $clcontratos->sql_record($clcontratos->sql_Contrato_PCNP($whereCustomParam));

        for ($iCont = 0; $iCont < pg_num_rows($rsContrato); $iCont++) {

            $oContratos = db_utils::fieldsMemory($rsContrato, $iCont);
            $oContrato      = new stdClass();
            $oContrato->sequencial      = $oContratos->ac16_sequencial;
            $oContrato->objeto          = utf8_encode($oContratos->objetocontrato);
            $oContrato->contrato        = $oContratos->numerocontratoempenho . '/' . $oContratos->anocontrato;
            $oContrato->fornecedor      = utf8_encode($oContratos->nomerazaosocialfornecedor);
            $oContrato->licitacao       = $oContratos->processo;
            $oContrato->numerocontrolepncp = $oContratos->ac213_numerocontrolepncp;

            $itens[] = $oContrato;
        }
        $oRetorno->contratos = $itens;
        break;

    case 'enviarContrato':
        //todas as contratos marcadas
        try {

            foreach ($oParam->aContratos as $aContrato) {
                $clContrato  = new cl_acordo;
                $clacocontrolepncp = new cl_acocontratopncp;

                //Contrato
                $rsDadosEnvio = $clContrato->sql_record($clContrato->sql_DadosContrato_PCNP($aContrato->codigo));

                $aItensContrato = array();
                for ($aco = 0; $aco < pg_num_rows($rsDadosEnvio); $aco++) {
                    $oDadosContrato = db_utils::fieldsMemory($rsDadosEnvio, $aco);
                }

                $clContratoPNCP = new ContratoPNCP($oDadosContrato);
                $tipopessoafornecedor = $clContratoPNCP->verificarCpfCnpj($oDadosContrato->nifornecedor);

                if(empty($tipopessoafornecedor)) {
                    $oRetorno->status  = 2;
                    $oRetorno->message = urlencode("O n�mero identificador $oDadosContrato->nifornecedor � inv�lido! Ele n�o corresponde a um CNPJ ou CPF v�lido.");
                } else {
                    $oDadosContrato->tipopessoafornecedor;

                    //monta o json com os dados da Contrato
                    $oDados = $clContratoPNCP->montarDados();
                    $arraybensjson = json_encode(DBString::utf8_encode_all($oDados));
    
                    $rsApiPNCP = $clContratoPNCP->enviarContrato($arraybensjson);
    
                    //$rsApiPNCP = array(201,'//treina.pncp.gov.br/pncp-api/v1/orgaos/23539463000121/contratos/2024/6 x-content-type-options');
    
                    if ($rsApiPNCP[0] == 201) {
                        //producao
                        $anocontrato = substr($rsApiPNCP[1], 58, 4);
                        $ac213_sequencialpncp = trim(substr(str_replace('x-content-type-options', '', $rsApiPNCP[1]), 63));
                        $ac213_numerocontrolepncp = db_utils::getCnpj() . '-2-' . str_pad($ac213_sequencialpncp, 6, '0', STR_PAD_LEFT) . '/' . $anocontrato;
                        //treinamento
                        if($envs['APP_ENV'] == "T"){
                            $anocontrato = substr($rsApiPNCP[1], 65, 4);
                            $ac213_sequencialpncp = trim(substr(str_replace('x-content-type-options', '', $rsApiPNCP[1]), 70));
                            $ac213_numerocontrolepncp = db_utils::getCnpj() . '-2-' . str_pad($ac213_sequencialpncp, 6, '0', STR_PAD_LEFT) . '/' . $anocontrato;
                        }
    
                        //monto o codigo do contrato no pncp
                        $clacocontrolepncp->ac213_contrato = $aContrato->codigo;
                        $clacocontrolepncp->ac213_usuario = db_getsession('DB_id_usuario');
                        $clacocontrolepncp->ac213_dtlancamento = date('Y-m-d', db_getsession('DB_datausu'));
                        $clacocontrolepncp->ac213_numerocontrolepncp = $ac213_numerocontrolepncp;
                        $clacocontrolepncp->ac213_situacao = 1;
                        $clacocontrolepncp->ac213_instit = db_getsession('DB_instit');
                        $clacocontrolepncp->ac213_ano = $anocontrato;
                        $clacocontrolepncp->ac213_sequencialpncp = $ac213_sequencialpncp;
                        $clacocontrolepncp->incluir();
    
                        if($clacocontrolepncp->erro_status == 0){
                            $erro = $clacocontrolepncp->erro_msg;
                            $sqlerro = true;
                        }
    
                        $oRetorno->status  = 1;
                    } else {
                        throw new Exception(utf8_decode($rsApiPNCP[1]));
                    }
                }
            }
        } catch (Exception $eErro) {
            $oRetorno->status  = 2;
            $oRetorno->message = urlencode($eErro->getMessage());
        }

        break;

    case 'RetificarContrato':
        $clContrato  = db_utils::getDao("acordo");
        $clacocontrolepncp = db_utils::getDao("acocontratopncp");
        try {
            foreach ($oParam->aContratos as $aContrato) {
                //somente contratos que ja foram enviadas para pncp
                $rsDadosExtras = $clContrato->sql_record($clContrato->sql_query_pncp($aContrato->codigo));

                for ($aco = 0; $aco < pg_num_rows($rsDadosExtras); $aco++) {
                    $oDadosContratoExtras = db_utils::fieldsMemory($rsDadosExtras, $aco);
                }

                $clContratoPNCP = new ContratoPNCP($oDadosContratoExtras);
                $oDadosRatificacao = $clContratoPNCP->montarRetificacao();
                $arraybensjson = json_encode(DBString::utf8_encode_all($oDadosRatificacao));
                $rsApiPNCP = $clContratoPNCP->enviarRetificacaoContrato($arraybensjson, $oDadosContratoExtras);
            }
        } catch (Exception $eErro) {
            $oRetorno->status  = 2;
            $oRetorno->message = urlencode($eErro->getMessage());
        }
        break;

    case 'AdicionarJustificativaPncp':
        $clContrato  = db_utils::getDao("acordo");
        try {
            $oRetornoStatus = $clContrato->adicionarJustificativasPncp($oParam->aContratos, $oParam->justificativa);
            $oRetorno->message = urlencode($oRetornoStatus->erro_sql);
        } catch (Exception $eErro) {
            $oRetorno->status  = 2;
            $oRetorno->message = urlencode($eErro->getMessage());
        }
        break;

    case 'ExcluirContrato':

        $clContrato  = db_utils::getDao("acordo");
        $clacocontrolepncp = db_utils::getDao("acocontratopncp");

        try {
            foreach ($oParam->aContratos as $aContrato) {
                $clacocontrolepncp = new cl_acocontratopncp();
                $clContratoPNCP = new ContratoPNCP($oDadosContrato);

                $rsContrato = $clacocontrolepncp->sql_record($clacocontrolepncp->sql_query_file(null, " * ", null, "ac213_contrato = " . $aContrato->codigo));

                for ($iCont = 0; $iCont < pg_num_rows($rsContrato); $iCont++) {
                    $sequencialpncp = db_utils::fieldsMemory($rsContrato, $iCont);
                }

                $justificativapncp['justificativa'] = $sequencialpncp->ac16_justificativapncp;
                $statusExclusao = $clContratoPNCP->excluirContrato($sequencialpncp->ac213_sequencialpncp, $sequencialpncp->ac213_ano, $sequencialpncp->ac213_numerocontrolepncp, $justificativapncp);

                if ($statusExclusao->status == null)
                    $clacocontrolepncp->excluir($ac123_sequencial = null, "ac213_contrato = $aContrato->codigo");

                if ($statusExclusao->status == 404) {
                    throw new Exception(utf8_decode($statusExclusao->message));
                }
                if ($statusExclusao->status == 422) {
                    throw new Exception(utf8_decode($statusExclusao->message));
                }
                if ($statusExclusao->status == 500) {
                    throw new Exception(utf8_decode($statusExclusao->message));
                }
            }
        } catch (Exception $eErro) {
            $oRetorno->status  = 2;
            $oRetorno->message = urlencode($eErro->getMessage());
        }
        break;

    case 'getEmpenhos':
        $clcontratos = new cl_acordo;
        $rsEmpenhos = $clcontratos->sql_record($clcontratos->sql_query_publicacaoEmpenho_pncp("*", null, "", null));

        for ($iCont = 0; $iCont < pg_num_rows($rsEmpenhos); $iCont++) {

            $oEmpenhos = db_utils::fieldsMemory($rsEmpenhos, $iCont);

            $oEmpenho      = new stdClass();
            $oEmpenho->sequencial      = $oEmpenhos->e60_numemp;
            $oEmpenho->objeto          = utf8_encode($oEmpenhos->objetocontrato);
            $oEmpenho->empenho         = $oEmpenhos->numerocontratoempenho . '/' . $oEmpenhos->anocontrato;
            $oEmpenho->fornecedor      = utf8_encode($oEmpenhos->nomerazaosocialfornecedor);
            $oEmpenho->licitacao       = $oEmpenhos->processo;
            $oEmpenho->numerocontrolepncp = $oEmpenhos->l213_numerocontrolepncp;

            $itens[] = $oEmpenho;
        }
        $oRetorno->empenhos = $itens;
        break;

    case 'enviarEmpenho':
        $clEmpenho  = db_utils::getDao("acordo");
        $clacocontrolepncp = db_utils::getDao("acocontratopncp");

        //todas os empenhos marcadas
        try {
            foreach ($oParam->aEmpenhos as $aEmpenho) {
                $clEmpenho = new cl_acordo;
                $clacocontrolepncp = new cl_acocontratopncp();
                //Empenhos
                // $oParam->aEmpenhos;

                $rsDadosEnvio = $clEmpenho->sql_record($clEmpenho->sql_query_pncp_empenho($aEmpenho->codigo));

                for ($aco = 0; $aco < pg_num_rows($rsDadosEnvio); $aco++) {
                    $oDadosEmpenho = db_utils::fieldsMemory($rsDadosEnvio, $aco);
                }

                $clEmpenhoPNCP = new ContratoPNCP($oDadosEmpenho);
                //monta o json com os dados da Contrato
                $oDados = $clEmpenhoPNCP->montarDados();

                $arraybensjson = json_encode(DBString::utf8_encode_all($oDados));

                $rsApiPNCP = $clEmpenhoPNCP->enviarContrato($arraybensjson);
                // print_r($rsApiPNCP);exit;
                if ($rsApiPNCP[1] == 201) {
                    $codigocontrato = explode('x-content-type-options', $rsApiPNCP[0]);
                    $clacocontrolepncp = new cl_acocontratopncp();
                    //monto o codigo do contrato no pncp
                    $ac213_numerocontrolepncp = db_utils::getCnpj() . '-1-' . str_pad(substr($codigocontrato[0], 75), 6, '0', STR_PAD_LEFT) . '/' . $oDadosContrato->anocontrato;
                    $clacocontrolepncp->ac213_contrato = $aEmpenho->codigo;
                    $clacocontrolepncp->ac213_usuario = db_getsession('DB_id_usuario');
                    $clacocontrolepncp->ac213_dtlancamento = date('Y-m-d', db_getsession('DB_datausu'));
                    $clacocontrolepncp->ac213_numerocontrolepncp = $ac213_numerocontrolepncp;
                    $clacocontrolepncp->ac213_situacao = 1;
                    $clacocontrolepncp->ac213_instit = db_getsession('DB_instit');
                    $clacocontrolepncp->ac213_ano = substr($codigocontrato[0], 71, 4);
                    $clacocontrolepncp->ac213_sequencialpncp = str_pad(substr($codigocontrato[0], 76), 6, '0', STR_PAD_LEFT);
                    $clacocontrolepncp->ac213_tipopublicacao = 2;
                    $clacocontrolepncp->incluir();

                    $oRetorno->status  = 1;
                } else {
                    throw new Exception(utf8_decode($rsApiPNCP[0]));
                }
            }
        } catch (Exception $eErro) {
            $oRetorno->status  = 2;
            $oRetorno->message = urlencode($eErro->getMessage());
        }

        break;

    case 'ExcluirEmpenho':

        $clEmpenho  = db_utils::getDao("acordo");
        $clacocontrolepncp = db_utils::getDao("acocontratopncp");

        try {
            foreach ($oParam->aEmpenhos as $aEmpenho) {
                $clacocontrolepncp = new cl_acocontratopncp();
                $clContratoPNCP = new ContratoPNCP($oDadosContrato);

                $rsContrato = $clacocontrolepncp->sql_record($clacocontrolepncp->sql_query_file(null, " * ", null, "ac213_tipopublicacao = 2 and ac213_contrato = " . $aEmpenho->codigo));

                for ($iCont = 0; $iCont < pg_num_rows($rsContrato); $iCont++) {
                    $sequencialpncp = db_utils::fieldsMemory($rsContrato, $iCont);
                }

                $statusExclusao = $clContratoPNCP->excluirContrato($sequencialpncp->ac213_sequencialpncp, $sequencialpncp->ac213_ano, $sequencialpncp->ac213_numerocontrolepncp);

                if ($statusExclusao->status == null)
                    $clacocontrolepncp->excluir($ac123_sequencial = null, "ac213_contrato = $aContrato->codigo");

                if ($statusExclusao->status == 404) {
                    throw new Exception(utf8_decode($statusExclusao->message));
                }
                if ($statusExclusao->status == 422) {
                    throw new Exception(utf8_decode($statusExclusao->message));
                }
                if ($statusExclusao->status == 500) {
                    throw new Exception(utf8_decode($statusExclusao->message));
                }
            }
        } catch (Exception $eErro) {
            $oRetorno->status  = 2;
            $oRetorno->message = urlencode($eErro->getMessage());
        }
        break;
}
echo json_encode($oRetorno);
