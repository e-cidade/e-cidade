<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicitem\DeleteLiclicitemService;

class ExcluirProcessoCompra implements HandleRepositoryInterface{

    private DeleteLiclicitemService $deleteLiclicitemService;

    public function __construct()
    {
        $this->deleteLiclicitemService = new DeleteLiclicitemService();
    }

    public function handle(object $data)
    {
        return $this->deleteLiclicitemService->execute($data);
    }
}
