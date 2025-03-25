<?php

namespace App\Application\Patrimonial\Licitacoes;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicita\InsertLicLicitaService;

class InserirLicitacoes implements HandleRepositoryInterface{
    private InsertLicLicitaService $insertLicLicitaService;

    public function __construct(){
        $this->insertLicLicitaService = new InsertLicLicitaService();
    }

    public function handle(object $data){
        return $this->insertLicLicitaService->execute($data);
    }

}
