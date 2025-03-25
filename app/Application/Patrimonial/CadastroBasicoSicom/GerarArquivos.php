<?php

namespace App\Application\Patrimonial\CadastroBasicoSicom;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\SicomAcodBasico\GerarArquivosService;

class GerarArquivos implements HandleRepositoryInterface {
    private GerarArquivosService $gerarArquivosService;

    public function __construct()
    {
        $this->gerarArquivosService = new GerarArquivosService();
    }

    public function handle($data){
        return $this->gerarArquivosService->execute($data);
    }
}
