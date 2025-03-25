<?php

namespace App\Repositories\Patrimonial\Licitacao;
use App\Models\Patrimonial\Licitacao\LicLancEdital;
use Illuminate\Support\Facades\DB;

class LicLancEditalRepository
{
    private LicLancEdital $model;

    public function __construct()
    {
        $this->model = new LicLancEdital();
    }

    public function getLicEditalByLicitacao(int $l20_codigo){
        return $this->model
            ->where('l47_liclicita', $l20_codigo)
            ->get();
    }

    public function delete(LicLancEdital $edital){
        $edital->delete();
    }

}
