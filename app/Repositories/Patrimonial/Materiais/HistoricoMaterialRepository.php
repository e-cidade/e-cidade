<?php

namespace App\Repositories\Patrimonial\Materiais;

use App\Models\Patrimonial\Materiais\HistoricoMaterial;
use Illuminate\Support\Facades\DB;

class HistoricoMaterialRepository{
    private HistoricoMaterial $model;

    public function __construct(){
        $this->model = new HistoricoMaterial();
    }

    public function getDadosFile($instit, $l228_datainicial, $l228_datafim){
      $this->model->select(
        DB::raw('db150_tipocadastro as tipoCadastro'),
        DB::raw('db150_pcmater as codItem'),
        DB::raw('CONCAT(pc01_descrmater, \'. \', pc01_complmater) as dscItem'),
        DB::raw('m61_codsicom as unidadeMedida'),
        DB::raw('db150_justificativaalteracao as justificativaAlteracao'),
        DB::raw('m61_descr as dscOutrosUnidMedida')
      )
        ->join(
          'pcmater',
          'pc01_codmater',
          '=',
          'db150_pcmater'
        )
        ->join(
          'matunid',
          'm61_codmatunid',
          '=',
          'db150_codunid'
        )
        ->where('db150_instit', $instit)
        ->where('db150_status', false);

        if($l228_datainicial && $l228_datafim){
          $this->model->whereBetween('db150_data', [$l228_datainicial, $l228_datafim]);
        }

        return $this->model->get();
    }

    public function updateByConditions($instit, $l228_datainicial, $l228_datafim){
      $ids = $this->model
        ->join(
          'pcmater',
          'pc01_codmater',
          '=',
          'db150_pcmater'
        )
        ->join(
          'matunid',
          'm61_codmatunid',
          '=',
          'db150_codunid'
        )
        // ->whereBetween('db150_data', [$l228_datainicial, $l228_datafim])
        ->where('db150_instit', $instit)
        ->where('db150_status', false)
        ->select('historicomaterial.db150_sequencial')
        ->pluck('db150_sequencial');

      return DB::table('historicomaterial')
        ->whereIn('db150_sequencial', $ids)
        ->update(['db150_status' => true]);
    }

}
