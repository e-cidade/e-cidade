<?php

namespace App\Repositories\Patrimonial\Licitacao\Sicom;

use App\Models\Sicom\HistoricoCgmEnvioSicom;
use Illuminate\Database\Capsule\Manager as DB;

class HistoricoCgmEnvioSicomRepository
{
  private HistoricoCgmEnvioSicom $model;

  public function __construct()
  {
      $this->model = new HistoricoCgmEnvioSicom();
  }

  public function saveInBath($instit, $remessa){
    return DB::table('historicocgmenviosicom')->insertUsing(
      ['z18_sequencial', 'z18_cgm', 'z18_instit', 'z18_statusenvio', 'z18_seqremessa'], // Colunas de destino
      DB::table('cgm')->select(
          DB::raw("nextval('historicocgmenviosicom_z18_sequencial_seq') as z18_sequencial"),
          DB::raw("z01_numcgm as z18_cgm"),
          DB::raw("$instit as z18_instit"),
          DB::raw("true as z18_statusenvio"),
          DB::raw("$remessa as z18_seqremessa")
      )
      // ->join('historicocgm', 'z09_numcgm', '=', 'z01_numcgm')
      ->leftJoin('historicocgmenviosicom', function($join) use ($instit) {
          $join->on('z18_cgm', '=', 'z01_numcgm')
               ->where('z18_instit', $instit);
      })
      ->where(function($query) {
          $query->whereNull('z18_sequencial')
                ->orWhere('z18_statusenvio', false);
      })
    );  
  }

}
