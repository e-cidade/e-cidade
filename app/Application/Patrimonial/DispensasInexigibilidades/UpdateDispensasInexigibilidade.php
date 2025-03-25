<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicita\UpdateLicLicitaService;

class UpdateDispensasInexigibilidade implements HandleRepositoryInterface{

    private UpdateLicLicitaService $updateLicLicitaService;

    public function __construct()
    {
        $this->updateLicLicitaService = new UpdateLicLicitaService();
    }

    public function handle(object $data)
    {
        return $this->updateLicLicitaService->execute(
            $data
        );
    }
}
