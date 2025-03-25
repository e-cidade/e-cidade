<?php

namespace App\Application\Patrimonial\Licitacoes;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicita\GetLicLicitaService;

class GetLicitacaoByCodigo implements HandleRepositoryInterface{
    private GetLicLicitaService $getLicLicitaService;

    public function __construct(){
        $this->getLicLicitaService = new GetLicLicitaService();
    }

    public function handle(object $data){
        return $this->getLicLicitaService->execute($data);
    }

}
