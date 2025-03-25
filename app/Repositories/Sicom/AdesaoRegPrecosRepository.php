<?php

namespace App\Repositories\Sicom;

use App\Models\Sicom\AdesaoRegPrecos;
use Illuminate\Support\Facades\DB;

class AdesaoRegPrecosRepository{
  private AdesaoRegPrecos $model;

  public function __construct()
  {
    $this->model = new AdesaoRegPrecos();
  }

  function getNextVal(){
    return $this->model->getNextval();
  }

  public function getAllByFilters(
    ?int $si06_sequencial = null,
    ?int $si06_numeroprc = null,
    ?int $si06_numlicitacao = null,
    ?int $si06_numeroadm = null,
    ?array $orderable = [],
    ?string $search = null,
    int $limit = 15,
    int $offset = 0,
    ?string $si06_instit
  ): ?array{
    $query = $this->model->query();

    $query->select(
      'si06_sequencial',
      DB::raw('cgm.z01_nome as dl_orgao_gerenciador'),
      'si06_numeroprc',
      'si06_anoproc',
      'si06_numlicitacao',
      'si06_dataadesao',
      'si06_dataata',
      'si06_objetoadesao',
      DB::raw('c.z01_nome as dl_resp_aprovacao'),
      'si06_numeroadm',
      'si06_anocadastro',
      'si06_nummodadm',
      'si06_anomodadm',
      'si06_codunidadesubant'
    );

    $query->join(
      'cgm',
      'cgm.z01_numcgm',
      '=',
      'adesaoregprecos.si06_orgaogerenciador'
    );

    $query->join(
      DB::raw('cgm c'),
      'c.z01_numcgm',
      '=',
      'adesaoregprecos.si06_cgm'
    );

    $query->join(
      'pcproc',
      'pcproc.pc80_codproc',
      '=',
      'adesaoregprecos.si06_processocompra'
    );

    $query->join(
      'db_usuarios',
      'db_usuarios.id_usuario',
      '=',
      'pcproc.pc80_usuario'
    );

    $query->join(
      'db_depart',
      'db_depart.coddepto',
      '=',
      'pcproc.pc80_depto'
    );

    $query->where('si06_instit', $si06_instit ?? 1);

    if(!empty($si06_sequencial)){
      $query->where('si06_sequencial', $si06_sequencial);
    }

    if(!empty($si06_numeroprc)){
      $query->where('si06_numeroprc', $si06_numeroprc);
    }

    if(!empty($si06_numlicitacao)){
      $query->where('si06_numlicitacao', $si06_numlicitacao);
    }

    if(!empty($si06_numeroadm)){
      $query->where('si06_numeroadm', $si06_numeroadm);
    }

    if(!empty($search)){
      $search = mb_strtolower(mb_convert_encoding($search, 'ISO-8859-1'));
      $searchString = '%'. $search .'%';
      $query->whereRaw(
          '(
              (si06_sequencial IS NOT NULL AND si06_sequencial = ?)
              OR LOWER(cgm.z01_nome) LIKE ?
              OR LOWER(c.z01_nome) LIKE ?
              OR (si06_numeroprc IS NOT NULL AND si06_numeroprc = ?)
              OR (si06_anoproc IS NOT NULL AND si06_anoproc = ?)
              OR (si06_numlicitacao IS NOT NULL AND si06_numlicitacao = ?)
              OR (si06_numeroadm IS NOT NULL AND si06_numeroadm = ?)
              OR (si06_anocadastro IS NOT NULL AND si06_anocadastro = ?)
              OR (si06_nummodadm IS NOT NULL AND si06_nummodadm = ?)
              OR (si06_anomodadm IS NOT NULL AND si06_anomodadm = ?)
              OR (si06_codunidadesubant IS NOT NULL AND si06_codunidadesubant = ?)
              OR (si06_dataadesao IS NOT NULL AND si06_dataadesao = ?)
              OR (si06_dataata IS NOT NULL AND si06_dataata = ?)
              OR LOWER(si06_objetoadesao) LIKE ?
          )',
          [
              is_numeric($search) ? $search : null,
              $searchString,
              $searchString,
              is_numeric($search) ? $search : null,
              is_numeric($search) ? $search : null,
              is_numeric($search) ? $search : null,
              is_numeric($search) ? $search : null,
              is_numeric($search) ? $search : null,
              is_numeric($search) ? $search : null,
              is_numeric($search) ? $search : null,
              is_numeric($search) ? $search : null,
              (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $search) || preg_match('/^\d{4}-\d{2}-\d{2}$/', $search)) ? date('Y-m-d', strtotime(str_replace('/', '-', $search))) : null,
              (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $search) || preg_match('/^\d{4}-\d{2}-\d{2}$/', $search)) ? date('Y-m-d', strtotime(str_replace('/', '-', $search))) : null,
              $searchString
          ]
      );
    }
    
    if(!empty($orderable)){
      foreach($orderable as $value){
        $query->orderBy($value->slug, $value->order);
      }
    }

    $total = $query->count();

    $query->limit($limit);
    $query->offset(($offset * $limit));

    $data = $query->get();

    return ['total' => $total, 'data' => $data->toArray()];
  }

  function save(AdesaoRegPrecos $data){
    $data->save();
    return $data;
  }

  function getAdesaoRegPrecoByCodigo(int $si06_sequencial){
    $query = $this->model->query();
    $query->select(
      'adesaoregprecos.*',
      DB::raw('cgm.z01_nome as dl_orgao_gerenciador'),
      DB::raw('c.z01_nome as dl_resp_aprovacao'),
      'db_depart.descrdepto'
    );

    $query->join(
      'cgm',
      'cgm.z01_numcgm',
      '=',
      'adesaoregprecos.si06_orgaogerenciador'
    );

    $query->join(
      DB::raw('cgm c'),
      'c.z01_numcgm',
      '=',
      'adesaoregprecos.si06_cgm'
    );

    $query->join(
      'pcproc',
      'pcproc.pc80_codproc',
      '=',
      'adesaoregprecos.si06_processocompra'
    );

    $query->join(
      'db_depart',
      'db_depart.coddepto',
      '=',
      'pcproc.pc80_depto'
    );

    $query->where('si06_sequencial', $si06_sequencial);

    return $query->first();
  }

  public function validaAdesao($si06_sequencial, $si06_numeroadm, $si06_anomodadm){
    return $this->model
      ->where('si06_numeroadm', $si06_numeroadm)
      ->where('si06_sequencial', '!=', $si06_sequencial)
      ->where('si06_anomodadm', $si06_anomodadm)
      ->first()
      ;
  }

  public function update(int $si06_sequencial, array $data){
    $oAdesaoPrecos = $this->model->findOrFail($si06_sequencial);
    $oAdesaoPrecos->update($data);

    return $oAdesaoPrecos;
  }
  
  public function delete($si06_sequencial){
    $this->model
        ->where('si06_sequencial', $si06_sequencial)
        ->delete();
  }

}