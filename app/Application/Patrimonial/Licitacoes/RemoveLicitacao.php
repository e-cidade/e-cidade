<?php
namespace App\Application\Patrimonial\Licitacoes;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicita\DeleteLicLicitaService;
use App\Services\Patrimonial\DispensasInexigibilidades\LicLicitaService;

class RemoveLicitacao implements HandleRepositoryInterface{
    private DeleteLicLicitaService $deleteLicLicitaService;

    public function __construct()
    {
        $this->deleteLicLicitaService = new DeleteLicLicitaService();
    }

    public function handle(object $data)
    {
        return $this->deleteLicLicitaService->execute($data);
    }
}
