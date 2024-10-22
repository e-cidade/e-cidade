<?php

namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Repositories\Patrimonial\PlanoContratacao\PcCatalogoRepository;

class ListagemCatalogo implements HandleRepositoryInterface {
    private PcCatalogoRepository $pcCatalogoRepository;

    public function __construct()
    {
        $this->pcCatalogoRepository = new PcCatalogoRepository();
    }

    public function handle($data){
        return ['pccatalogo' => $this->pcCatalogoRepository->getDados()];
    }

}
