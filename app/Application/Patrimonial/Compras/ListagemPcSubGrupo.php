<?php

namespace App\Application\Patrimonial\Compras;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Repositories\Patrimonial\Compras\PcSubGrupoRepository;

class ListagemPcSubGrupo implements HandleRepositoryInterface {
    private PcSubGrupoRepository $pcSubGrupoRepository;

    public function __construct()
    {
        $this->pcSubGrupoRepository = new PcSubGrupoRepository();
    }

    public function handle($data){
        return $this->pcSubGrupoRepository->search($data->string ?? '', $data->anousu);
    }

}
