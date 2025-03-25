<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicita\InsertLicLicitaService;

class InserirDispensasInexigibilidade implements HandleRepositoryInterface{

    private InsertLicLicitaService $insertLicLicitaService;

    public function __construct()
    {
        $this->insertLicLicitaService = new InsertLicLicitaService();
    }

    public function handle(object $data)
    {
        return $this->insertLicLicitaService->execute($data);
    }
}
