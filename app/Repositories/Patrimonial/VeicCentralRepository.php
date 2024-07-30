<?php

namespace App\Repositories\Patrimonial;

use App\Models\VeicCentral;

class VeicCentralRepository
{
    private VeicCentral $model;

    public function __construct()
    {
        $this->model = new VeicCentral();
    }

    public function getCentralByVeiculo($ve40_veiculos)
    {
        return $this->model->where('ve40_veiculos', $ve40_veiculos)->first('ve40_veiccadcentral');
    }
}
