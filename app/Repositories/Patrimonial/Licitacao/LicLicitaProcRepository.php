<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\LicLicitaProc;
use Illuminate\Database\Capsule\Manager as DB;

class LicLicitaProcRepository
{
    private LicLicitaProc $model;

    public function __construct()
    {
        $this->model = new LicLicitaProc();
    }

    function getNextVal(){
        return $this->model->getNextval();
    }

    function save(LicLicitaProc $data){
        $data->save();
        return $data;
    }

    function getLicLicitaProcByLicLicita(int $l20_codigo){
        return $this->model
            ->where('l34_liclicita', $l20_codigo)
            ->get();
    }

    function removeByCodigo(int $l34_sequencial){
        $licLicitaProc = $this->model->findOrFail($l34_sequencial);
        $licLicitaProc->delete();
    }

    public function update(int $l34_sequencial, array $data){
        $oData = $this->model->findOrFail($l34_sequencial);
        $oData->update($data);
        return $oData;
    }

    function findLicLicitaProcByLicLicita(int $l20_codigo){
        return $this->model
            ->where('l34_liclicita', $l20_codigo)
            ->first();
    }

    function deleteByLicitacao(int $l20_codigo){
        return $this->model->where('l34_liclicita', $l20_codigo)->delete();
    }
}
