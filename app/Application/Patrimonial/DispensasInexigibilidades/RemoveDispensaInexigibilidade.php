<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicita\DeleteLicLicitaService;

class RemoveDispensaInexigibilidade implements HandleRepositoryInterface{

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
