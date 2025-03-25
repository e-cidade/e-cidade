<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicitaLotes\InsertLicLicitaLotesService;

class SalvarItensLote implements HandleRepositoryInterface{

    private InsertLicLicitaLotesService $insertLicLicitaLotesService;

    public function __construct()
    {
        $this->insertLicLicitaLotesService = new InsertLicLicitaLotesService();
    }

    public function handle(object $data)
    {
        return $this->insertLicLicitaLotesService->execute($data);
    }
}
