<?php

namespace App\Repositories\Contabilidade;

use App\Models\Contabilidade\Condataconf;

class CondataconfRepository
{
    private Condataconf $model;

    public function __construct()
    {
        $this->model = new Condataconf();
    }

    public function getEncerramentoPatrimonial(string $ano, int $c99_instit,array $campos = ['*']): ?Condataconf
    {
        return $this->model->where('c99_anousu', $ano)
            ->where('c99_instit',$c99_instit)
            ->first($campos);
    }
}
