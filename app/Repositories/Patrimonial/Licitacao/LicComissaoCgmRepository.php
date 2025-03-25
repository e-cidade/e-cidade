<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\LicComissaoCgm;
use Illuminate\Database\Capsule\Manager as DB;

class LicComissaoCgmRepository
{
    private LicComissaoCgm $model;

    public function __construct()
    {
        $this->model = new LicComissaoCgm();
    }

    function getNextVal(){
        return $this->model->getNextval();
    }

    function save(LicComissaoCgm $data){
        $data->save();
        return $data;
    }

    function getLicComissaoByLicLicita(int $l20_codigo){
        return $this->model
            ->where('l31_licitacao', $l20_codigo)
            ->get()
            ->toArray();
    }

    function removeByCodigo(int $l31_codigo){
        $licLicitaComissaoCgm = $this->model->findOrFail($l31_codigo);
        $licLicitaComissaoCgm->delete();
    }

    function getLicComissaoByLicLicitaTipo(int $l20_codigo, int $l31_tipo){
        return $this->model
            ->where('l31_licitacao', $l20_codigo)
            ->where('l31_tipo', DB::raw("'$l31_tipo'"))
            ->get()
            ->first();
    }

    function update(int $l31_codigo, array $data){
        $oData = $this->model->findOrFail($l31_codigo);
        $oData->update($data);
        return $oData;
    }

    function deleteByCodigoAndTipo(int $l20_codigo, int $l31_tipo){
        return $this->model
            ->where('l31_licitacao', $l20_codigo)
            ->where('l31_tipo', DB::raw("'$l31_tipo'"))
            ->delete()
        ;
    }

    function deleteByLicitacao(int $l20_codigo){
        return $this->model
            ->where('l31_licitacao', $l20_codigo)
            ->delete()
        ;
    }

    function getLicComissaoByParams(int $l20_codigo, int $l31_tipo, int $l31_numcgm){
        return $this->model
            ->where('l31_numcgm', $l31_numcgm)
            ->where('l31_licitacao', $l20_codigo)
            ->where('l31_tipo', DB::raw("'$l31_tipo'"))
            ->get()
            ->first();
    }

}
