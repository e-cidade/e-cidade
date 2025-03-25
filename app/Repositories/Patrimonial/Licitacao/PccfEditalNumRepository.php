<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\PccfEditalNum;
use Illuminate\Support\Facades\DB;

class PccfEditalNumRepository{
    private PccfEditalNum $model;

    public function __construct()
    {
        $this->model = new PccfEditalNum();
    }

    public function getNroEdital(int $l24_instit, int $l24_anousu){
        return $this->model->query()
            ->where('l47_instit', $l24_instit)
            ->where('l47_anousu', $l24_anousu)
            ->where('l47_timestamp', function($query) use ($l24_anousu, $l24_instit){
                $query->selectRaw('MAX(l47_timestamp)')
                    ->from('pccfeditalnum')
                    ->where('l47_anousu', $l24_anousu);
            })
            ->get()
            ->first();
    }

    public function getNroEditalByNroEdital(int $l24_instit, int $l24_anousu, int $l47_numero){
        return $this->model->query()
            ->where('l47_instit', $l24_instit)
            ->where('l47_anousu', $l24_anousu)
            ->where('l47_numero', $l47_numero)
            ->get()
            ->first();
    }

    public function getNroEditalLicita(int $instit, int $anousu, int $l20_nroedital){
        return $this->model->query()
            ->join(
                'liclicita',
                'liclicita.l20_nroedital',
                '=',
                'pccfeditalnum.l47_numero'
            )
            ->join(
                'pccflicitapar',
                'pccflicitapar.l25_codcflicita',
                '=',
                'liclicita.l20_codtipocom'
            )
            ->where('l20_instit', $instit)
            ->where('l47_anousu', $anousu)
            ->where('l20_nroedital', $l20_nroedital)
            ->where('l20_anousu', $anousu)
            ->get()
            ->first();
    }

    public function save(PccfEditalNum $oData){
        DB::insert(DB::raw("
            INSERT INTO pccfeditalnum (l47_instit, l47_anousu, l47_numero, l47_timestamp)
            VALUES (:l47_instit, :l47_anousu, :l47_numero, :l47_timestamp)
        "), $oData->toArray());

        return $oData;
    }

    public function deleteNroEdital($l20_nroedital){
        $this->model
            ->where('l47_numero', $l20_nroedital)
            ->delete();
    }

}
