<?php

namespace App\Listeners\PlanoContratacao;

use App\Events\PlanoContratacao\PlanoContratacaoDownload;
use App\Repositories\Patrimonial\PlanoContratacao\PcPlanoContratacaoRepository;
use ECidade\Patrimonial\Licitacao\PNCP\BasePNCP;

class GetPlanoContratacaoDownload extends BasePNCP{
    private PcPlanoContratacaoRepository $planoContratacaoRepository;
    private $planocontracao;

    public function __construct(){
        parent::__construct();
        $this->planoContratacaoRepository = new PcPlanoContratacaoRepository();
    }

    public function handle(PlanoContratacaoDownload $event){
        $this->planocontracao = $event->item;
        $cnpj = $this->getCnpj($this->planocontracao->mpc01_uncompradora);
        $token = $this->login($this->planocontracao->mpc01_uncompradora);
        $url = 'orgaos/' . $cnpj . '/pca/' . $this->planocontracao->mpc01_ano . '/csv' ;

        $header = [
            'Content-Type' => 'application/json',
            'Authorization' => $token
        ];

        $response = $this->client->get($url, [
            'headers' => $header
        ]);
        $body = $response->getBody();
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

        return [
            'status' => $response->getStatusCode(),
            'message' => 'Arquivo encontrado com sucesso!',
            'arquivo' => utf8_decode($body),
            'name' => 'pca_'.$this->planocontracao->mpc01_ano.'.csv'
        ];
    }

    public function montarDados(){}
}
