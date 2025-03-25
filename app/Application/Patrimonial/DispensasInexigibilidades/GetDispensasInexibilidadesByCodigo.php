<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicita\GetLicLicitaService;
use App\Services\Patrimonial\DispensasInexigibilidades\LicLicitaService;

class GetDispensasInexibilidadesByCodigo implements HandleRepositoryInterface{

    private GetLicLicitaService $getLicLicitaService;

    public function __construct()
    {
        $this->getLicLicitaService = new GetLicLicitaService();
    }

    public function handle(object $data)
    {
        return $this->getLicLicitaService->execute($data);
    }
}
