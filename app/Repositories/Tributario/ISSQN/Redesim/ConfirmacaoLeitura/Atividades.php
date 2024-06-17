<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\ConfirmacaoLeitura;

use App\Repositories\Tributario\ISSQN\Redesim\Contracts\IRedesimApiSettings;
use GuzzleHttp\ClientInterface;

class Atividades extends ConfirmacaoLeitura
{
    public function __construct(IRedesimApiSettings $settings, ClientInterface $client)
    {
        parent::__construct($settings, $client);
        $this->baseUrl = 'service/confirmacaoLeitura/alvara/atividades';
    }
}
