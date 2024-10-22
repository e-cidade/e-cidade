<?php

namespace App\Repositories\Contracts\Patrimonial\Protocolo;

use App\Models\Cgm;

interface CgmRepositoryInterface
{
    public function getCgm(int $codigoAcordo, $fields = ['*']): Cgm;
}
