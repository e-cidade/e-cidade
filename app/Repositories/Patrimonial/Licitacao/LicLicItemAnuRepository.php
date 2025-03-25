<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\LicLicItemAnu;
use Illuminate\Database\Capsule\Manager as DB;

class LicLicItemAnuRepository
{
    private LicLicItemAnu $model;

    public function __construct()
    {
        $this->model = new LicLicItemAnu();
    }

    public function deleteByLiclicita($l07_liclicitem){
        $sql = "DELETE FROM liclicitemanu WHERE l07_liclicitem = $l07_liclicitem";
        return DB::statement($sql);
    }

    public function deleteByLicitacao($l20_codigo){
        return $this->model
            ->whereIn('l07_liclicitem', function($query) use ($l20_codigo){
                $query->select('l21_codigo')
                    ->from('liclicitem')
                    ->where('l21_codliclicita', $l20_codigo);
            })
            ->delete();
    }

}
