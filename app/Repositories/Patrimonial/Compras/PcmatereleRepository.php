<?php

namespace App\Repositories\Patrimonial\Compras;

use App\Models\Patrimonial\Compras\Pcmaterele;

class PcmatereleRepository
{
    private Pcmaterele $model;

    public function __construct()
    {
        $this->model = new Pcmaterele();
    }


    public function salvarPcmaterele(array $pcmaterele)
    {
        return $this->model->insert($pcmaterele);
    }

}
