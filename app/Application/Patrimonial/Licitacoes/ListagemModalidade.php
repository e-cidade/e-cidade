<?php

namespace App\Application\Patrimonial\Licitacoes;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\Cflicita\GetCflicitaService;
use App\Services\Patrimonial\DispensasInexigibilidades\CflicitaService;

class ListagemModalidade implements HandleRepositoryInterface {
    private GetCflicitaService $getCflicitaService;

    public function __construct()
    {
        $this->getCflicitaService = new GetCflicitaService();
    }

    public function handle(object $data)
    {
        return $this->getCflicitaService->execute($data);
    }
}
