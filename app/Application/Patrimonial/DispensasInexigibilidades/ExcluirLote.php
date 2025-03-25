<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicitaLotes\DeleteLicLicitaLotesService;

class ExcluirLote implements HandleRepositoryInterface{

    private DeleteLicLicitaLotesService $deleteLicLicitaLotesService;

    public function __construct()
    {
        $this->deleteLicLicitaLotesService = new DeleteLicLicitaLotesService();
    }

    public function handle(object $data)
    {
        return $this->deleteLicLicitaLotesService->execute($data);
    }
}
