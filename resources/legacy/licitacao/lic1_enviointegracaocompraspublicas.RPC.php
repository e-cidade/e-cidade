<?php

use function GuzzleHttp\json_encode;

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
require_once("classes/db_liclicitaportalcompras_classe.php");
require_once("model/licitacao/PortalCompras/Fabricas/LicitacaoFabrica.model.php");
require_once("model/licitacao/PortalCompras/Comandos/EnviaLicitacaoPcp.model.php");
require_once("model/licitacao/PortalCompras/Comandos/ValidaAcessoApi.model.php");
require_once("model/licitacao/PortalCompras/Modalidades/UnidadeMedida.model.php");
require_once("model/licitacao/PortalCompras/Comandos/EnviaUnidadeMedidaPcp.model.php");

$cl_liclicitaportalcompras = new cl_liclicitaportalcompras;
$licitacaoFabrica  = new LicitacaoFabrica;
$validaAcessoApi = new ValidaAcessoApi();

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$oRetorno->itens   = array();

switch ($oParam->exec) {
    case 'EnviarPregao':
        try {
            // Recupera a chave de acesso e o código da licitação
            $codigoLicitacao = $oParam->codigo;
            $chaveAcesso = $validaAcessoApi->getChaveAcesso();

            // Busca informações da licitação
            $resultadosLicitacao = $cl_liclicitaportalcompras->buscaLicitacoes($codigoLicitacao);
            $validaAcessoApi->execute($resultadosLicitacao);

            $unidadeMedida = new UnidadeMedida();
            $unidadeMedida->validateAcronymAndDescriptionShoppingPortal($resultadosLicitacao, $cl_liclicitaportalcompras->numrows, $chaveAcesso);
            
            $siglasFaltantesNoPcp = $unidadeMedida->getSiglasFaltantesNoPcp();

            // Trata siglas faltantes
            if (!empty($siglasFaltantesNoPcp)) {

                $urlPost = $unidadeMedida->getUrlPostShoppingPortal($chaveAcesso);
                $aBodyPostShoppingPortal = $unidadeMedida->getBodyPostShoppingPortal();

                $messageErroCreateunitMeasure = "";
                foreach ($aBodyPostShoppingPortal as $key => $body) {
                    // Cria novas unidades no portal de compras se estiverem faltando
                    $enviadorUnidadeMedida = new EnviaUnidadeMedidaPcp();
                    $resultadoPost = $enviadorUnidadeMedida->create($body, $urlPost);

                    if (!$resultadoPost->success || empty($resultadoPost->success)) {
                        $messageErroCreateunitMeasure .= $resultadoEnvio->message . "\n";
                    }
                }

                if (!empty($messageErroCreateunitMeasure)) {
                    $oRetorno->message = "Erro: " . $messageErroCreateunitMeasure;
                    $oRetorno->status = 2;
                }

            }

            // Prossegue para enviar a licitação se não houver siglas faltantes ou se o post foi bem-sucedido
            if (empty($siglasFaltantesNoPcp) || $resultadoPost->success) {
                $fabricaLicitacao = new LicitacaoFabrica();
                $licitacao = $fabricaLicitacao->criar($resultadosLicitacao, $cl_liclicitaportalcompras->numrows);
                $urlPortalLicitacao = $licitacao->getUrlPortalCompras($chaveAcesso);

                $enviadorLicitacao = new EnviaLicitacaoPcp();
                $resultadoEnvio = $enviadorLicitacao->execute($licitacao, $urlPortalLicitacao);

                $oRetorno->status = $resultadoEnvio['sucess'];
                $oRetorno->message = $resultadoEnvio['message'];
            }

        } catch (Exception $exception) {
            // Trata exceções
            $oRetorno->message = $exception->getMessage();
            $oRetorno->status = 2;
        }
        break;

    case 'ValidaPregao':

        try {
            // Recupera a chave de acesso e o código da licitação
            $codigoLicitacao = $oParam->codigo;
            $chaveAcesso = $validaAcessoApi->getChaveAcesso();

            // Busca informações da licitação
            $resultadosLicitacao = $cl_liclicitaportalcompras->buscaLicitacoes($codigoLicitacao);
            $validaAcessoApi->execute($resultadosLicitacao);

            $unidadeMedida = new UnidadeMedida();
            $unidadeMedida->validateAcronymAndDescriptionShoppingPortal($resultadosLicitacao, $cl_liclicitaportalcompras->numrows, $chaveAcesso);
            
            $siglasIncorretasECorretas = $unidadeMedida->getSiglasIncorretasECorretas();
            $siglasInvalidas = $unidadeMedida->getSiglasInvalidas();

            // Trata siglas faltantes ou incorretas
            if (empty($siglasIncorretasECorretas) && empty($siglasInvalidas)) {

                $responseGetPcpUnidadeMedida = $unidadeMedida->getRespostaSiglaUnidadeGet();

                if (empty($responseGetPcpUnidadeMedida['success']) || $responseGetPcpUnidadeMedida['success'] != 2) {

                    $siglasFaltantesNoPcp = $unidadeMedida->getSiglasFaltantesNoPcp();

                    if (!empty($siglasFaltantesNoPcp)) {
                        $oRetorno->message = utf8_encode("Será incluido as seguintes unidades de medidas no Portal de Compras Publicas");
                        $oRetorno->siglasFaltantesNoPcp = $siglasFaltantesNoPcp;
                        $oRetorno->status = 3;
                    } else {
                        $oRetorno->status = 1;
                    }

                } else {
                    $oRetorno->message = "Erro: " . $responseGetPcpUnidadeMedida['message'];
                    $oRetorno->status = 2;
                }

            } else {

                if (count($siglasIncorretasECorretas) > 1) {
                    $messageStatus = "Algumas unidades de medidas já estão cadastradas no Portal de Compras Publicas, mas as siglas enviadas estão incorretas. Segue o gabarito para correção.";
                } else {
                    $messageStatus = "A unidade de medida já esta cadastradas no Portal de Compras Publicas, mas as sigla enviada esta incorreta. Segue o gabarito para correção.";
                }

                $oRetorno->message = utf8_encode($messageStatus);
                $oRetorno->siglasInvalidas = $siglasInvalidas;
                $oRetorno->siglasIncorretasECorretas = $siglasIncorretasECorretas;
                $oRetorno->status = 4;

            }

        } catch (Exception $exception) {
            // Trata exceções
            $oRetorno->message = $exception->getMessage();
            $oRetorno->status = 2;
        }

        break;  
}

echo json_encode($oRetorno);