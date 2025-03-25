<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\AmparoLegal\GetAmparoLegalService;

class ListagemAmparoLegal implements HandleRepositoryInterface{

    private GetAmparoLegalService $getAmparoLegalService;

    public function __construct()
    {
        $this->getAmparoLegalService = new GetAmparoLegalService();
    }

    public function handle(object $data)
    {
        return $this->getAmparoLegalService->execute($data);
    }
}
