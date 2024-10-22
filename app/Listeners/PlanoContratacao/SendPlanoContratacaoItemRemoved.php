<?php
namespace App\Listeners\PlanoContratacao;

use App\Events\PlanoContratacao\PlanoContratacaoItemRemoved;
use App\Models\Patrimonial\Compras\PcPlanoContratacaoIntegracao;
use App\Repositories\Patrimonial\PlanoContratacao\PcPlanoContratacaoIntegracaoRepository;
use App\Repositories\Patrimonial\PlanoContratacao\PlanoContratacaoPcPcItemRepository;
use ECidade\Patrimonial\Licitacao\PNCP\BasePNCP;

class SendPlanoContratacaoItemRemoved extends BasePNCP{
    private PlanoContratacaoPcPcItemRepository $pcPcItemRepository;
    private PcPlanoContratacaoIntegracaoRepository $pcPlanoIntegracaoRepository;
    private $planocontracao;
    private $planoContratacaoItens;
    private $justificativa;

    public function __construct()
    {
        parent::__construct();
        $this->pcPcItemRepository = new PlanoContratacaoPcPcItemRepository();
        $this->pcPlanoIntegracaoRepository = new PcPlanoContratacaoIntegracaoRepository();
    }

    public function handle(PlanoContratacaoItemRemoved $event){
        $this->justificativa = $event->justificativa;
        $this->planocontracao = $event->item;
        $this->planoContratacaoItens = $event->itens;
        $dataSendIntegration = $this->montarDados();

        $cnpj = $this->getCnpj($this->planocontracao->mpc01_uncompradora);
        $token = $this->login($this->planocontracao->mpc01_uncompradora);
        $url = 'orgaos/' . $cnpj . '/pca/' . $this->planocontracao->mpc01_ano . '/' . $this->planocontracao->mpc01_sequencial . '/itens';

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $token
        ];

        $integracao = New PcPlanoContratacaoIntegracao([
            'mpci01_codigo'                    => $this->pcPlanoIntegracaoRepository->getCodigo(),
            'mpci01_pcplanocontratacao_codigo' => $this->planocontracao->mpc01_codigo,
            'mpci01_sequencial'                => '',
            'mpci01_usuario'                   => $event->id_usuario,
            'mpci01_dtlancamento'              => $event->datausu,
            'mpci01_anousu'                    => $event->anousu,
            'mpci01_instit'                    => $event->instit,
            'mpci01_ano'                       => $this->planocontracao->mpc01_ano,
            'mpci01_response_body'             => '',
            'mpci01_send_body'                 => json_encode($dataSendIntegration, JSON_UNESCAPED_UNICODE),
            'mpci01_response_headers'          => '',
            'mpci01_send_headers'              => json_encode($header, JSON_UNESCAPED_UNICODE),
            'mpci01_url'                       => $url,
        ]);

        $response = $this->client->delete($url, [
            'json' => $dataSendIntegration,
            'headers' => $header
        ]);

        $body = json_decode($response->getBody(), true);

        if(!in_array($response->getStatusCode(), [200, 201])){
            if(!empty($body['erros'])){
                $error = array_column($body['erros'], 'mensagem');
                $errorList = 'Erros: <br>';
                foreach($error as $key => $value){
                    $errorList .= '<li><b>'.utf8_decode($value).'</b></li>';
                }
                return [
                    'status' => $response->getStatusCode(),
                    'message' => $errorList
                ];
            }
            return [
                'status' => $response->getStatusCode(),
                'message' => utf8_decode($body['message'])
            ];
        }

        $integracao->mpci01_sequencial = $this->planocontracao->mpc01_sequencial;
        $integracao->mpci01_response_body = json_encode($body);
        $integracao->mpci01_response_headers = json_encode($response->getHeaders());
        $integracao->mpci01_status = $response->getStatusCode();

        $this->pcPlanoIntegracaoRepository->save($integracao);

        foreach($this->planoContratacaoItens as $value){
            $this->pcPcItemRepository->update(
                $value['mpcpc01_codigo'],
                [
                    'mpcpc01_is_send_pncp' => 0,
                    'mpcpc01_numero_item' => null
                ]
            );
        }

        return [
            'status' => $response->getStatusCode(),
            'message' => 'Item removido com sucesso'
        ];
    }

    public function montarDados()
    {
        $oDadosApi = [
            'justificativa' => $this->justificativa
        ];

        foreach($this->planoContratacaoItens ?: [] as $value){
            $oDadosApi['listaNumerosItens'][] = $value['mpcpc01_numero_item'];
        }

        return $oDadosApi;
    }

}
