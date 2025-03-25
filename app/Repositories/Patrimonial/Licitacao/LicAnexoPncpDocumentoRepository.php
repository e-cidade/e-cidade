<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\LicAnexoPncpDocumento;
use Illuminate\Database\Capsule\Manager as DB;

class LicAnexoPncpDocumentoRepository
{
    private LicAnexoPncpDocumento $model;

    public function __construct()
    {
        $this->model = new LicAnexoPncpDocumento();
    }

    public function getDadosByLicAnexoPncp(int $l216_licanexospncp):?array
    {
        $query = $this->model->query();
        $query->where('l216_licanexospncp', $l216_licanexospncp);

        return $query->get()->toArray();
    }

    public function getByCodigo(int $l216_sequencial){
        return $this->model->where('l216_sequencial', $l216_sequencial)->first();
    }

    public function delete(LicAnexoPncpDocumento $aData){
        $aData->delete();
    }

}
