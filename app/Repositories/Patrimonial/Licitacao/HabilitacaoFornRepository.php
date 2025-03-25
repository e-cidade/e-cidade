<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\HabilitacaoForn;
use cl_liclicita;
use Illuminate\Database\Capsule\Manager as DB;

class HabilitacaoFornRepository
{
  private HabilitacaoForn $model;

  public function __construct()
  {
    $this->model = new HabilitacaoForn();
  }

   public function getFornecedoresByLicitacao($l206_licitacao){
      return $this->model
        ->select()
        ->join(
          'pcforne',
          'pcforne.pc60_numcgm',
          '=',
          'habilitacaoforn.l206_fornecedor'
        )
        ->join(
          'liclicita',
          'liclicita.l20_codigo',
          '=',
          'habilitacaoforn.l206_licitacao'
        )
        ->join(
          'db_usuarios',
          'db_usuarios.id_usuario',
          '=',
          'pcforne.pc60_usuario'
        )
        ->join(
          'cgm',
          'cgm.z01_numcgm',
          '=',
          'pcforne.pc60_numcgm'
        )
        ->join(
          'db_config',
          'db_config.codigo',
          '=',
          'liclicita.l20_instit'
        )
        ->join(
          'db_usuarios AS a',
          'a.id_usuario',
          '=',
          'liclicita.l20_id_usucria'
        )
        ->join(
          'cflicita',
          'cflicita.l03_codigo',
          '=',
          'liclicita.l20_codtipocom'
        )
        ->join(
          'liclocal',
          'liclocal.l26_codigo',
          '=',
          'liclicita.l20_liclocal'
        )
        ->join(
          'liccomissao',
          'liccomissao.l30_codigo',
          '=',
          'liclicita.l20_liccomissao'
        )
        ->join(
          'licsituacao',
          'licsituacao.l08_sequencial',
          '=',
          'liclicita.l20_licsituacao'
        )
        ->where('l206_licitacao', $l206_licitacao)
        ->get()
        ->toArray();
   }
}   
