<?php

namespace App\Application\Patrimonial\CadastroBasicoSicom;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\SicomAcodBasico\GetNumeroRemessaService;

class GetNumeroRemessa implements HandleRepositoryInterface {
    private GetNumeroRemessaService $getNumeroRemessaService;

    public function __construct()
    {
        $this->getNumeroRemessaService = new GetNumeroRemessaService();
    }

    public function handle($data){
        return $this->getNumeroRemessaService->execute($data);
    }

}
