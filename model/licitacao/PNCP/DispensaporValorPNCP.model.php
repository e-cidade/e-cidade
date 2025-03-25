<?php

use ECidade\Patrimonial\Licitacao\PNCP\ModeloBasePNCP;

/**
 * Classe responsvel por montar as informaes do Envio de Avisos de Licitacao - PNCP
 *
 * @package  ECidade\model\licitacao\PNCP
 * @author   Mario Junior
 */
class DispensaPorValorPNCP extends ModeloBasePNCP
{

    /**
     *
     * @param \stdClass $dados
     */
    function __construct($dados = null)
    {
        parent::__construct($dados);
    }

    public function montarDados()
    {

        $oDado = $this->dados;

        $oDadosAPI                                  = new \stdClass;
        $oDadosAPI->codigoUnidadeCompradora         = $this->getUndCompradora();
        $oDadosAPI->tipoInstrumentoConvocatorioId   = $oDado->tipoinstrumentoconvocatorioid;
        $oDadosAPI->modalidadeId                    = $oDado->modalidadeid;
        $oDadosAPI->modoDisputaId                   = $oDado->mododisputaid;
        $oDadosAPI->numeroCompra                    = $oDado->numerocompra;
        $oDadosAPI->anoCompra                       = $oDado->anocompra;
        $oDadosAPI->numeroProcesso                  = $oDado->numeroprocesso;
        $oDadosAPI->objetoCompra                    = utf8_encode($oDado->objetocompra);
        $oDadosAPI->informacaoComplementar          = utf8_encode($oDado->informacaocomplementar);
        $oDadosAPI->srp                             = $oDado->srp == 'f' ? 'false' : 'true';
        //$oDadosAPI->orcamentoSigiloso               = $oDado->orcamentosigiloso == 'f' ? 'false' : 'true';
        $oDadosAPI->dataAberturaProposta            = $this->formatDate($oDado->dataaberturaproposta);
        $oDadosAPI->dataEncerramentoProposta        = $this->formatDate($oDado->dataencerramentoproposta);
        $oDadosAPI->amparoLegalId                   = $oDado->amparolegalid;
        $oDadosAPI->linkSistemaOrigem               = $oDado->linksistemaorigem;
        //ITENS
        $vlrtotal = 0;
        foreach ($oDado->itensCompra as $key => $item) {
            $vlrtotal = $item->pc11_quant * $item->valorunitarioestimado;

            $oDadosAPI->itensCompra[$key]->numeroItem                  = $item->numeroitem;
            $oDadosAPI->itensCompra[$key]->materialOuServico           = $item->materialouservico;
            $oDadosAPI->itensCompra[$key]->tipoBeneficioId             = $item->tipobeneficioid;
            $oDadosAPI->itensCompra[$key]->incentivoProdutivoBasico    = $item->incentivoprodutivobasico == 'f' ? 'false' : 'true';
            $oDadosAPI->itensCompra[$key]->descricao                   = utf8_encode($item->descricao);
            $oDadosAPI->itensCompra[$key]->quantidade                  = $item->pc11_quant;
            $oDadosAPI->itensCompra[$key]->unidadeMedida               = utf8_encode($item->unidademedida);
            $oDadosAPI->itensCompra[$key]->orcamentoSigiloso           = $oDado->orcamentosigiloso == 'f' ? 'false' : 'true';
            $oDadosAPI->itensCompra[$key]->valorUnitarioEstimado       = $item->valorunitarioestimado;
            $oDadosAPI->itensCompra[$key]->valorTotal                  = $vlrtotal;
            $oDadosAPI->itensCompra[$key]->criterioJulgamentoId        = $item->criteriojulgamentoid;
            $oDadosAPI->itensCompra[$key]->itemCategoriaId             = 3;

            // TODO: Confirmar os dados corretos para esses campos com um consultor. Houve uma atualização durante a execução da tarefa. Futuramente, será criada uma tarefa específica para essa ocorrência.
            $oDadosAPI->itensCompra[$key]->aplicabilidadeMargemPreferenciaNormal    = 0;
            $oDadosAPI->itensCompra[$key]->aplicabilidadeMargemPreferenciaAdicional = 0;
        }

        $aDadosAPI = $oDadosAPI;

        $name = 'Compra' . $oDado->numerocompra . '.json';
        $arquivo = 'model/licitacao/PNCP/arquivos/' . $name;
        if (file_exists($arquivo)) {
            unlink($arquivo);
        }
        file_put_contents($arquivo, json_encode($aDadosAPI));
    }

    public function montarRetificacao()
    {
        //ini_set('display_errors', 'on');
        $aDadosAPI = array();

        $oDado = $this->dados;

        $oDadosAPI                                  = new \stdClass;
        $oDadosAPI->codigoUnidadeCompradora         = $this->getUndCompradora();
        $oDadosAPI->tipoInstrumentoConvocatorioId   = $oDado->tipoinstrumentoconvocatorioid;
        $oDadosAPI->modalidadeId                    = $oDado->modalidadeid;
        $oDadosAPI->modoDisputaId                   = $oDado->mododisputaid;
        $oDadosAPI->numeroCompra                    = $oDado->numerocompra;
        $oDadosAPI->anoCompra                       = $oDado->anocompra;
        $oDadosAPI->numeroProcesso                  = $oDado->numeroprocesso;
        $oDadosAPI->objetoCompra                    = utf8_encode($oDado->objetocompra);
        $oDadosAPI->informacaoComplementar          = $oDado->informacaocomplementar;
        $oDadosAPI->srp                             = $oDado->srp == 'f' ? 'false' : 'true';
        $oDadosAPI->orcamentoSigiloso               = $oDado->orcamentosigiloso == 'f' ? 'false' : 'true';
        $oDadosAPI->dataAberturaProposta            = $this->formatDate($oDado->dataaberturaproposta);
        $oDadosAPI->dataEncerramentoProposta        = $this->formatDate($oDado->dataencerramentoproposta);
        $oDadosAPI->amparoLegalId                   = $oDado->amparolegalid;
        $oDadosAPI->linkSistemaOrigem               = $oDado->linksistemaorigem;

        $aDadosAPI = json_encode($oDadosAPI);

        return $aDadosAPI;
    }

    public function montarRetificacaoItens()
    {
        $oDado      = $this->dados;
        $aDadosAPI  = [];

        $vlrtotal = 0;
        foreach ($oDado as $key => $item) {
            $vlrtotal = $item->pc11_quant * $item->valorunitarioestimado;

            $aDadosAPI[$key]['numeroItem']                  = $item->numeroitem;
            $aDadosAPI[$key]['materialOuServico']           = $item->materialouservico;
            $aDadosAPI[$key]['tipoBeneficioId']             = $item->tipobeneficioid;
            $aDadosAPI[$key]['incentivoProdutivoBasico']    = $item->incentivoprodutivobasico == 'f' ? 0 : 1;
            $aDadosAPI[$key]['descricao']                   = utf8_encode($item->descricao);
            $aDadosAPI[$key]['quantidade']                  = $item->pc11_quant;
            $aDadosAPI[$key]['unidadeMedida']               = utf8_encode($item->unidademedida);
            $aDadosAPI[$key]['orcamentoSigiloso']           = $item->l21_sigilo == 'f' ? 0 : 1;
            $aDadosAPI[$key]['valorUnitarioEstimado']       = $item->valorunitarioestimado;
            $aDadosAPI[$key]['valorTotal']                  = $vlrtotal;
            $aDadosAPI[$key]['criterioJulgamentoId']        = $item->criteriojulgamentoid;
            $aDadosAPI[$key]['itemCategoriaId']             = $item->itemcategoriaid;
            
            if(!empty($item->justificativa)) {
                $aDadosAPI[$key]['justificativa']           = $item->justificativa;
            }
            
            // TODO: Confirmar os dados corretos para esses campos com um consultor. Houve uma atualização durante a execução da tarefa. Futuramente, será criada uma tarefa específica para essa ocorrência.
            $aDadosAPI[$key]['aplicabilidadeMargemPreferenciaNormal']    = 0;
            $aDadosAPI[$key]['aplicabilidadeMargemPreferenciaAdicional'] = 0;
        }
        
        return $aDadosAPI;
    }

    /**
     * Realiza o requisicao na api do PNCP
     *
     * @param \int $tipoDocumento
     * 1  - Aviso de Contratao Direta
     * 2  - Edital
     * 11 - Ata de Registro de Preo
     */

    public function enviarAviso($tipodocumento, $processo, $aAnexos)
    {

        $token = $this->login();

        $cnpj =  $this->getCnpj();

        $url = $this->envs['URL'] . "orgaos/" . $cnpj . "/compras";

        $file = 'model/licitacao/PNCP/arquivos/Compra' . $processo . '.json';
        $filezip = curl_file_create('model/licitacao/PNCP/anexoslicitacao/' . $aAnexos[0]->l217_documento);

        $cfile = new \CURLFile($file, 'application/json', 'compra');
        //$cfilezip = new \CURLFile($filezip, 'application/zip', 'documento');
        $post_data =  array(
            'compra' => $cfile,
            'documento' => $filezip
        );

        $chpncp      = curl_init($url);

        $headers = array(
            'Content-Type: multipart/form-data',
            'Authorization: ' . $token,
            'Titulo-Documento: ' . utf8_decode($aAnexos[0]->l216_nomedocumento),
            'Tipo-Documento-Id:' . $aAnexos[0]->l213_sequencial
        );

        $optionspncp = $this->getParancurl('POST',$post_data,$headers,false,false);
        curl_setopt_array($chpncp, $optionspncp);
        $contentpncp = curl_exec($chpncp);
        curl_close($chpncp);
        $retorno = json_decode($contentpncp);

        //enviado de api
        if ($retorno->status) {
            return array(422, $retorno->message);
        }
        //enviado de cadastro
        if($retorno->erros){
            return array(422, $retorno->erros[0]->mensagem);
        }
        //enviado com sucesso
        if($retorno->compraUri){
            return array(201, $retorno->compraUri);
        }
    }

    public function enviarRetificacao($oDados, $sCodigoControlePNCP, $iAnoCompra)
    {
        $token = $this->login();

        //aqui sera necessario informar o cnpj da instituicao de envio
        $cnpj =  $this->getCnpj();

        $url = $this->envs['URL'] . "orgaos/" . $cnpj . "/compras/$iAnoCompra/$sCodigoControlePNCP";

        $chpncp      = curl_init($url);

        $headers = array(
            'Content-Type: application/json',
            'Authorization: ' . $token
        );

        $optionspncp = $this->getParancurl('PATCH',$oDados,$headers,false, false);

        curl_setopt_array($chpncp, $optionspncp);
        $contentpncp = curl_exec($chpncp);
        curl_close($chpncp);

        $retorno = json_decode($contentpncp);

        return $retorno;
    }

    public function enviarRetificacaoItens($oDados, $iAnoCompra, $sCodigoControlePNCP, $numeroItem)
    {
        $token  = $this->login();
        $cnpj   = $this->getCnpj();
        $url    = $this->envs['URL'] . "orgaos/$cnpj/compras/$iAnoCompra/$sCodigoControlePNCP/itens/$numeroItem";
        $chpncp = curl_init($url);

        $headers = array(
            'Content-Type: application/json',
            'Authorization: ' . $token
        );

        $optionspncp = $this->getParancurl('PUT', $oDados, $headers, true, false);

        curl_setopt_array($chpncp, $optionspncp);
        $contentpncp = curl_exec($chpncp);
        $httpStatus = curl_getinfo($chpncp, CURLINFO_HTTP_CODE);
        curl_close($chpncp);

        $aRetorno['httpStatus'] = $httpStatus;
        if ($httpStatus !== 200) {
            $aRetorno['numeroItem'] = $oDados['numeroItem'];
            $aRetorno['contentPncp'] = json_decode($contentpncp);
            return $this->returnRetificacaoPncpMessage($aRetorno);
        } else {
            return $aRetorno;
        }
    }

    public function excluirAviso($sCodigoControlePNCP, $iAnoCompra, $sJustificativa = null)
    {
        $token = $this->login();

        //aqui sera necessario informar o cnpj da instituicao de envio
        $cnpj =  $this->getCnpj();

        $url = $this->envs['URL'] . "orgaos/" . $cnpj . "/compras/$iAnoCompra/$sCodigoControlePNCP";

        $chpncp      = curl_init($url);

        $headers = array(
            'Content-Type: application/json',
            'Authorization: ' . $token
        );

        if(!empty($sJustificativa)) {
            $aBody['justificativa'] = $sJustificativa;
            $optionspncp = $this->getParancurl('DELETE', $aBody, $headers, true, false);
        } else {
            $optionspncp = $this->getParancurl('DELETE',null,$headers,false,false);
        }

        curl_setopt_array($chpncp, $optionspncp);
        $contentpncp = curl_exec($chpncp);
        curl_close($chpncp);

        $retorno = json_decode($contentpncp);

        return $retorno;
    }

    public function enviarAnexos($iTipoAnexo, $sDescricao, $sAnexo, $iAnoCompra, $iCodigocompra)
    {

        $token = $this->login();

        //aqui sera necessario informar o cnpj da instituicao de envio
        $cnpj =  $this->getCnpj();

        $url = $this->envs['URL'] . "orgaos/" . $cnpj . "/compras/" . $iAnoCompra . "/" . $iCodigocompra . "/arquivos";

        //arquivo para envio
        $filezip = curl_file_create('model/licitacao/PNCP/anexoslicitacao/' . $sAnexo);

        $post_data =  array(
            'arquivo' => $filezip
        );

        $chpncp      = curl_init($url);

        $headers = array(
            'Content-Type: multipart/form-data',
            'Authorization: ' . $token,
            'Titulo-Documento: ' . $sDescricao,
            'Tipo-Documento-Id: ' . $iTipoAnexo
        );

        $optionspncp = $this->getParancurl('POST',$post_data,$headers,false,true);

        curl_setopt_array($chpncp, $optionspncp);
        $contentpncp = curl_exec($chpncp);
        curl_close($chpncp);

        $retorno = explode(':', $contentpncp);

        if ($retorno[5] == ' https') {
            return array(201, $retorno[6]);
        } else {
            return array(422, "Erro ao enviar anexo");
        }
    }

    public function excluirAnexos($iAnoCompra, $iCodigocompra, $iSeqAnexosPNCP)
    {
        $token = $this->login();

        //aqui sera necessario informar o cnpj da instituicao de envio
        $cnpj =  $this->getCnpj();

        $url = $this->envs['URL'] . "orgaos/" . $cnpj . "/compras/$iAnoCompra/$iCodigocompra/arquivos/$iSeqAnexosPNCP";

        $chpncp      = curl_init($url);

        $headers = array(
            'Content-Type: application/json',
            'Authorization: ' . $token
        );

        $optionspncp = $this->getParancurl('DELETE',null,$headers,false,false);

        curl_setopt_array($chpncp, $optionspncp);
        $contentpncp = curl_exec($chpncp);
        /*$err     = curl_errno($chpncp);
        $errmsg  = curl_error($chpncp);
        $header  = curl_getinfo($chpncp);
        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['header']  = $contentpncp;
        echo "<pre>";
        print_r($header);
        exit;*/

        curl_close($chpncp);

        $retorno = json_decode($contentpncp);

        if ($retorno->status) {
            return array(422, $retorno->message);
        } else {
            return array(201, "Excluido com Sucesso !");
        }
        return $retorno;
    }

    private function returnRetificacaoPncpMessage($rsApiPNCPItens)
    {
        switch ($rsApiPNCPItens['httpStatus']) {
            case 204:
                $message = "Erro No Content.";
                break;

            case 400:
                $messageError = "";
                if (isset($rsApiPNCPItens['contentPncp']->error)) {
                    $messageError = '- Erro Bad Request: ' . $rsApiPNCPItens['contentPncp']->message;
                } elseif (isset($rsApiPNCPItens['contentPncp']->erros)) {
                    foreach ($rsApiPNCPItens['contentPncp']->erros as $erro) {
                        $messageError .= "- Erro Bad Request: " . $erro->nomeCampo . ": " . $erro->mensagem . "\n";
                    }
                } else {
                    $messageError = '- Erro Bad Request: Erro desconhecido na requisição.';
                }
                $message = $messageError . "\n";
                break;

            case 401:
                $message = "Erro Unauthorized.";
                break;

            case 422:
                $message = "Erro Unprocessable Entity.";
                break;

            case 500:
                $message = "Erro Internal Server Error.";
                break;

            default:
                $message = "Erro desconhecido na requisição.";
                break;
        }

        return [
            "httpStatus" => $rsApiPNCPItens['httpStatus'],
            "message" => "Numero Item: " . $rsApiPNCPItens['numeroItem'] . " \n" . $message
        ];
    }
}
