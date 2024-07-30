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
}
