<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\ObrasCodigos;
use Illuminate\Database\Capsule\Manager as DB;

class ObrasCodigosRepository
{
    private ObrasCodigos $model;

    public function __construct()
    {
        $this->model = new ObrasCodigos();
    }

    public function getByLicLicita(int $db151_liclicita){
        return $this->model->query()
            ->where('db151_liclicita', $db151_liclicita)
            ->get();
    }

    public function delete(ObrasCodigos $aData){
        $aData->delete();
    }
}
