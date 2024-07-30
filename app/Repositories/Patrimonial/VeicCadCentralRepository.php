<?php

namespace App\Repositories\Patrimonial;

use App\Models\VeicCadCentral;

class VeicCadCentralRepository
{
    private VeicCadCentral $model;

    public function __construct()
    {
        $this->model = new VeicCadCentral();
    }

    public function getCodDepartamentobyCentral($ve36_sequencial)
    {
        return $this->model->where('ve36_sequencial', $ve36_sequencial)->first('*');
    }
}
