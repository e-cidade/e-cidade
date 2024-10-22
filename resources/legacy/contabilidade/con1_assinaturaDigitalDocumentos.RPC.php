<?php

require_once(modification("libs/db_stdlib.php"));
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
require_once("libs/db_sessoes.php");
require_once("model/empenho/relatorio/FormularioOrdemPagamento.model.php");
require_once("model/empenho/relatorio/FormularioAnulacaoEmpenho.model.php");
use App\Repositories\Configuracao\AssinaturaDigitalAssinatesRepository;
require_once("model/protocolo/AssinaturaDigital.model.php");

$oJson               = new services_json();
$oParam              = $oJson->decode(str_replace("\\", "", $_REQUEST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';
$oRetorno->erro      = false;

$assintaraDigital = new AssinaturaDigital();

try {
    switch( $oParam->sExecuta ) {

        case 'getDocumentos':

            $oRetorno->link_base = '';
            $oRetorno->aDocumentos = [];
            if($assintaraDigital->verificaAssituraAtiva()){
                $oRetorno->link_base  = $assintaraDigital->getUrlBaseUrlFile();
                $oRetorno->aDocumentos = $assintaraDigital->getDocumentosPorUsuario($oParam->iPagina ?? 1);
            }

            break;

        case 'assinarDocumento':

            $response = $assintaraDigital->assinarDocumento($oParam->sUuid);
            $oRetorno->link_base  = $assintaraDigital->getUrlBaseUrlFile();
            $oRetorno->aDocumentos = $assintaraDigital->getDocumentosPorUsuario();
            if(count($response->errors) > 0){
                throw new Exception($response->errors[0]);
            }
            break;

        case 'assinarDocumentoLote':
            foreach ($oParam->aDocumentos as $uuid) {
                $response = $assintaraDigital->assinarDocumento($uuid);
                if (count($response->errors) > 0) {
                    throw new Exception($response->errors[0]);
                }
            }
            $oRetorno->link_base = $assintaraDigital->getUrlBaseUrlFile();
            $oRetorno->aDocumentos = $assintaraDigital->getDocumentosPorUsuario();
            break;

        case 'assinarImprimirDocumentoAssinado':

            $response = $assintaraDigital->getEmpenhosAssinados($oParam);

            if (count($response->errors) > 0) {
                throw new Exception($response->errors[0]);
            }
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="empenho.pdf"');
            echo $response;
            return;

        case 'imprimirDocumentoAssinadoLiquidacao':

            $response = $assintaraDigital->getLiquidacoesAssinadas($oParam);

            if (count($response->errors) > 0) {
                throw new Exception($response->errors[0]);
            }
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="liquidacao.pdf"');
            echo $response;
            return;

        case 'immprimirDocumentoAssinadoAnulacao':

            $response = $assintaraDigital->getAnulacaoEmpenhoAssinadas($oParam);

            if (count($response->errors) > 0) {
                throw new Exception($response->errors[0]);
            }
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="anulacao_empenho_assinados.pdf"');
            echo $response;
            return;

        case 'imprimirDocumentoAssinadoOrdemPagamento':

            $response = $assintaraDigital->getOrdemPagamentoAssinadas($oParam);

            if (count($response->errors) > 0) {
                throw new Exception($response->errors[0]);
            }
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="ordem_pagamento_assinados.pdf"');
            echo $response;
            return;

        case 'imprimirDocumentoAssinadoSlip':

            $response = $assintaraDigital->getSlipAssinadas($oParam);
            if (count($response->errors) > 0) {
                throw new Exception($response->errors[0]);
            }
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="slip_assinados.pdf"');
            echo $response;
            return;

        case 'assinarDocumentosFolha':

            $response = $assintaraDigital->asssinarDocumentosFolha($oParam->sEmpenhosGerados);

            if(count($response->errors) > 0){
                throw new Exception($response->errors[0]);
            }
            break;

        case 'enviarOrdemaPagamentoAssinatura':

            if($assintaraDigital->verificaAssituraAtiva()) {
                $aMovimentos = explode( ',', $oParam->sMovimentos);
                foreach ($aMovimentos as $iMovimento){
                    $formularioOrdemPagamento = new FormularioOrdemPagamento();
                    $formularioOrdemPagamento->gerarFormularioOrdemPagamento($iMovimento);
                    $formularioOrdemPagamento->solitarAssinatura();
                }
            }
            break;

        case 'enviarOrdemaPagamentoAssinaturaLote':

            if($assintaraDigital->verificaAssituraAtiva()) {
                $response = $assintaraDigital->enviarOrdemaPagamentoAssinaturaLote($oParam);
                if(count($response->errors) > 0){
                    throw new Exception($response->errors[0]);
                }
            }
            break;

        case 'enviarOrdemaPagamentoByCodOrdAssinatura':
            if($assintaraDigital->verificaAssituraAtiva()) {
                $assinaturaDigitalAssinanteRepository = new AssinaturaDigitalAssinatesRepository();
                $aOrdensPagamento = $assinaturaDigitalAssinanteRepository->getMovimentoByCodOrd($oParam);
                foreach ($aOrdensPagamento as $iMovimento){
                    $formularioOrdemPagamento = new FormularioOrdemPagamento();
                    $formularioOrdemPagamento->gerarFormularioOrdemPagamento($iMovimento->e82_codmov);
                    $formularioOrdemPagamento->solitarAssinatura();
                }
            }
            break;

        case 'enviarAnulacaoEmpenhoAssinatura':

            $formularioAnulacaoEmpenho = new FormularioAnulacaoEmpenho();
            $formularioAnulacaoEmpenho->gerarFormularioAnulacaoEmpenho($oParam->iAnulacao);
            $formularioAnulacaoEmpenho->solitarAssinatura();

            break;
    }

} catch ( Exception $oErro ) {
    $oRetorno->erro      = true;
    $oRetorno->iStatus   = 2;
    $oRetorno->sMensagem =  $oErro->getMessage();
}


echo $oJson->encode(DBString::utf8_encode_all($oRetorno));


