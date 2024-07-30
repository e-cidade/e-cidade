<?php

namespace App\Repositories\Patrimonial;

use App\Models\VeicAbast;
use App\Models\VeicMotoristas;


class VeicMotoristasRepository
{
    private VeicMotoristas $model;

    public function __construct()
    {
        $this->model = new VeicMotoristas();
    }

    public function getMotoristaByCgm($z01_numcgm)
    {
        return $this->model->where('ve05_numcgm', $z01_numcgm)->first('ve05_codigo');
    }
}

