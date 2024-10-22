<?php

namespace App\Repositories\Patrimonial\Protocolo;

use App\Models\Cgm;
use App\Repositories\Contracts\Patrimonial\Protocolo\CgmRepositoryInterface;

class CgmRepository implements CgmRepositoryInterface
{
    /**
     *
     * @var AcCgmordo
     */
    private Cgm $model;

    public function __construct()
    {
        $this->model = new Cgm();
    }

    /**
     *
     * @param integer $codigoCgm
     * @param array $fields
     * @return Cgm
     */
    public function getCgm(int $codigoCgm, $fields = ['*']): Cgm
    {
       return $this->model->where('z01_numcgm', $codigoCgm)->first($fields);
    }
}
