<?php

namespace App\Repositories;

use App\Models\Cgm;

class CgmRepository
{
    private Cgm $model;

    public function __construct()
    {
        $this->model = new Cgm();
    }

    public function getCgmByCpf(string $cpf, array $campos = ['*']): ?Cgm
    {
        return $this->model->where('z01_cgccpf', $cpf)->first($campos);
    }

    public function getMotoristaByCpf(string $cpf, array $campos = ['*']): ?object{
        return $this->model
            ->join(
                'veicmotoristas',
                'veicmotoristas.ve05_numcgm',
                '=',
                'cgm.z01_numcgm'
            )
            ->where('z01_cgccpf', $cpf)
            ->first($campos);
    }

}
