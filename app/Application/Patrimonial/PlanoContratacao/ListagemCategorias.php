<?php
namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Repositories\Patrimonial\PlanoContratacao\PcCategoriaRepository;

class ListagemCategorias implements HandleRepositoryInterface{
    private PcCategoriaRepository $pcCategoriaRepository;

    public function __construct()
    {
        $this->pcCategoriaRepository = new PcCategoriaRepository();
    }

    public function handle($data){
        return ['pccategorias' => $this->pcCategoriaRepository->getDados()];
    }
}
