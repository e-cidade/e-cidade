<?php

namespace App\Application\Patrimonial\CadastroBasicoSicom;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\SicomAcodBasico\SalvarGeracaoArquivosService;

class SalvarGeracaoArquivos implements HandleRepositoryInterface {
    private SalvarGeracaoArquivosService $salvarGeracaoArquivosService;

    public function __construct()
    {
        $this->salvarGeracaoArquivosService = new SalvarGeracaoArquivosService();
    }

    public function handle($data){
        return $this->salvarGeracaoArquivosService->execute($data);
    }

}
