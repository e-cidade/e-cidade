<?php

namespace App\Services\Configuracoes;

use App\Services\Contracts\Configuracoes\ApiRFPServiceInterface;

class ApiRFPService implements ApiRFPServiceInterface
{
    private string $env = 'config/apirfp/.env';
    private string $url;
    private string $cliente;

    public function __construct()
    {
        $env = parse_ini_file($this->env, true);
        $this->url = $env['URL'];
        $this->cliente = $env['CLIENTE'];
    }

    public function getURL()
    {
        return $this->url;
    }

    public function getCliente()
    {
        return $this->cliente;
    }
}