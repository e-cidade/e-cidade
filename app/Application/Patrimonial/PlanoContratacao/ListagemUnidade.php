<?php
namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Repositories\Patrimonial\Materiais\MatUnidRepository;
use App\Repositories\Patrimonial\PlanoContratacao\PcCatalogoRepository;

class ListagemUnidade implements HandleRepositoryInterface{
   private MatUnidRepository $matUniRepository;

    public function __construct(){
        $this->matUniRepository = new MatUnidRepository();
    }

    public function handle($data)
    {
        return ['matunid' => $this->matUniRepository->getDados()];
    }

}
