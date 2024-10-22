<?php

namespace App\Application\Patrimonial\Compras;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Repositories\Patrimonial\Compras\PcMaterRepository;

class ListagemPcMater implements HandleRepositoryInterface {
    private PcMaterRepository $pcMaterRepository;

    public function __construct()
    {
        $this->pcMaterRepository = new PcMaterRepository();
    }

    public function handle($data){
        return $this->pcMaterRepository->search($data->string ?? '', $data->anousu);
    }

}
