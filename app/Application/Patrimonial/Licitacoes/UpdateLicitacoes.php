<?php

namespace App\Application\Patrimonial\Licitacoes;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicita\UpdateLicLicitaService;

class UpdateLicitacoes implements HandleRepositoryInterface{
    private UpdateLicLicitaService $updateLicLicitaService;

    public function __construct(){
        $this->updateLicLicitaService = new UpdateLicLicitaService();
    }

    public function handle(object $data){
        return $this->updateLicLicitaService->execute($data);
    }

}
