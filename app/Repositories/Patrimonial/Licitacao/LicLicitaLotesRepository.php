<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\LicLicitaLotes;
use App\Repositories\Contracts\Patrimonial\Licitacao\LicLicitaLotesRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;

class LicLicitaLotesRepository implements LicLicitaLotesRepositoryInterface
{
    private LicLicitaLotes $model;

    public function __construct()
    {
        $this->model = new LicLicitaLotes();
    }

    public function getCodigo(){
        return $this->model->getNextval();
    }

    public function save(LicLicitaLotes $licLicitaLotes){
        $licLicitaLotes->save();
        return $licLicitaLotes;
    }

    public function getLotesByLicLicita(int $l20_codigo){
        return $this->model
            ->where('l24_codliclicita', $l20_codigo)
            ->get()
            ->toArray();
    }

    public function getLotes(int $l20_codigo){
        return $this->model
            ->where('l24_codliclicita', $l20_codigo)
            ->get();
    }

    public function getLoteByCodigo(int $l24_codigo){
        return $this->model
            ->where('l24_codigo', $l24_codigo)
            ->first();
    }

    public function getLoteByName(string $l24_pcdesc, int $l24_codliclicita){
        return $this->model
            ->where('l24_pcdesc', $l24_pcdesc)
            ->where('l24_codliclicita', $l24_codliclicita)
            ->first();
    }

    public function delete(LicLicitaLotes $oLote){
        return $oLote->delete();
    }

    public function getLoteByDescricaoAndLicitacao($l24_pcdesc, $l24_codliclicita){
        return $this->model
        ->where('l24_codliclicita', $l24_codliclicita)
        ->where('l24_pcdesc', $l24_pcdesc)
        ->first();
    }

}
