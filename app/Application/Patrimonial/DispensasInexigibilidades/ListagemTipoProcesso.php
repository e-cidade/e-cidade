<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\Cflicita\GetCflicitaService;

class ListagemTipoProcesso implements HandleRepositoryInterface{

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
