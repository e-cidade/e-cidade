<?php

namespace App\Repositories\Patrimonial\Licitacao;
use cl_liclicita;
use App\Models\Patrimonial\Licitacao\Liclicita;
use App\Models\Patrimonial\Licitacao\ParecerLicitacao;
use Illuminate\Support\Facades\DB;

class ParecerLicitacaoRepository
{
  private ParecerLicitacao $model;

  public function __construct()
  {
    $this->model = new ParecerLicitacao();
  }

  public function getParecerLicitacaoByCodigo(int $l200_sequencial){
    $query = $this->model->query();

    $this->getJoinsLintagem($query);

    $this->getSelectListagem($query);

    $query->where('l200_sequencial', $l200_sequencial);

    return $query->first();
  }

  public function getAllByFilters(
      ?string $l200_sequencial = null,
      ?string $l20_codigo = null,
      ?string $l20_numero = null,
      ?string $l20_edital = null,
      ?string $l200_data = null,
      ?string $l200_tipoparecer = null,
      ?string $l200_exercicio = null,
      ?string $l20_objeto = null,
      ?array $orderable = [],
      ?string $search = null,
      ?bool $is_contass,
      int $limit = 15,
      int $offset = 0,
      ?string $l20_instit
  ): ?array {
      $query = $this->model->query();

      $this->getJoinsLintagem($query);

      $this->getSelectListagem($query);

      $query->where('l20_instit', $l20_instit ?? 1);

      // if(!$is_contass && (is_null($l08_sequencial) || $l08_sequencial == '')){
      //     $query->where('l20_licsituacao', 0);
      // }

      if(!empty($l200_sequencial)){
        $query->where('l200_sequencial', $l200_sequencial);
      }

      if(!empty($l20_codigo)){
        $query->where('l20_codigo', $l20_codigo);
      }

      if(!empty($l20_numero)){
        $query->where('l20_numero', $l20_numero);
      }

      if(!empty($l20_edital)){
        $query->where('l20_edital', $l20_edital);
      }
      
      if(!empty($l200_data)){
        $query->where('l200_data', date('Y-m-d', strtotime(str_replace('/', '-', $l200_data))));
      }
      
      if(!empty($l200_tipoparecer)){
        $query->where('l200_tipoparecer', $l200_tipoparecer);
      }
      
      if(!empty($l200_exercicio)){
        $query->where('l200_exercicio', $l200_exercicio);
      }

      if(!empty($l20_objeto)){
          $query->where(function($query) use ($l20_objeto){
              $query->whereRaw('LOWER(l20_objeto) LIKE ?', ['%' . mb_strtolower(mb_convert_encoding($l20_objeto, 'ISO-8859-1')) . '%']);
          });
      }

      // if(!empty($l20_anousu)){
      //   $query->where('l20_anousu', $l20_anousu);
      // }

      if(!empty($search)){
          $search = mb_strtolower(mb_convert_encoding($search, 'ISO-8859-1'));
          $searchString = '%'. $search .'%';
          $query->whereRaw(
              '(
                  (l200_sequencial IS NOT NULL AND l200_sequencial = ?)
                  OR (l20_codigo IS NOT NULL AND l20_codigo = ?)
                  OR (l20_edital IS NOT NULL AND l20_edital = ?)
                  OR (l20_numero IS NOT NULL AND l20_numero = ?)
                  OR LOWER(l03_descr) ILIKE ?
                  OR (l20_nroedital IS NOT NULL AND l20_nroedital = ?)
                  OR (l200_exercicio IS NOT NULL AND l200_exercicio = ?)
                  OR (l200_data IS NOT NULL AND l200_data = ?)
                  OR LOWER(
                      (
                          CASE
                            WHEN l200_tipoparecer = 1 THEN \'Técnico\'
                            WHEN l200_tipoparecer = 2 THEN \'Juridico - Edital\'
                            WHEN l200_tipoparecer = 3 THEN \'Juridico - Julgamento\'
                            ELSE \'Juridico - Outros\'
                          END
                      )
                  ) LIKE ?
                  OR LOWER(z01_nome) ILIKE ?
                  OR LOWER(l20_objeto) ILIKE ?
                  OR (l08_descr) ILIKE ?
              )',
              [
                  is_numeric($search) ? $search : null,
                  is_numeric($search) ? $search : null,
                  is_numeric($search) ? $search : null,
                  is_numeric($search) ? $search : null,
                  $searchString,
                  is_numeric($search) ? $search : null,
                  is_numeric($search) ? $search : null,
                  (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $search) || preg_match('/^\d{4}-\d{2}-\d{2}$/', $search)) ? date('Y-m-d', strtotime(str_replace('/', '-', $search))) : null,
                  $searchString,
                  $searchString,
                  $searchString,
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

  private function getSelectListagem(&$query){
      $query->select(
        'l20_codigo',
        // DB::raw('
        //   (
        //     SELECT
        //       l20_edital::varchar, \'/\', l20_anousu::varchar)
        //     FROM
        //       liclicita
        //     WHERE
        //       l20_codigo = l200_licitacao
        //   ) AS l20_edital
        // '),
        'liclicita.l20_edital',
        'liclicita.l20_numero',
        'l03_descr AS dl_modalidade',
        DB::raw('
          (
            CASE
              WHEN liclicita.l20_nroedital IS NULL THEN \'-\'
              ELSE liclicita.l20_nroedital::varchar
            END
          ) AS l20_nroedital
        '),
        'l200_exercicio',
        'l200_data',
        DB::raw('
          (
            CASE
              WHEN l200_tipoparecer = 1 THEN \'Técnico\'
              WHEN l200_tipoparecer = 2 THEN \'Juridico - Edital\'
              WHEN l200_tipoparecer = 3 THEN \'Juridico - Julgamento\'
              ELSE \'Juridico - Outros\'
            END
          ) AS l200_tipoparecer
        '),
        'l200_numcgm',
        'z01_nome',
        'l20_objeto',
        DB::raw('l08_descr AS dl_situacao'),
        'l200_sequencial',
        'l200_licitacao',
        DB::raw('l200_tipoparecer as parecer'),
        'pc50_descr',
        'l200_descrparecer'
      );
  }

  private function getJoinsLintagem(&$query){
      $query->join(
        'cgm',
        'cgm.z01_numcgm',
        '=',
        'parecerlicitacao.l200_numcgm'
      );

      $query->join(
        'liclicita',
        'liclicita.l20_codigo',
        '=',
        'parecerlicitacao.l200_licitacao'
      );

      $query->join(
        'db_config',
        'db_config.codigo',
        '=',
        'liclicita.l20_instit'
      );

      $query->join(
        'db_usuarios',
        'db_usuarios.id_usuario',
        '=',
        'liclicita.l20_id_usucria'
      );

      $query->join(
        'cflicita',
        'cflicita.l03_codigo',
        '=',
        'liclicita.l20_codtipocom'
      );

      $query->join(
        'liclocal',
        'liclocal.l26_codigo',
        '=',
        'liclicita.l20_liclocal'
      );

      $query->join(
        'liccomissao',
        'liccomissao.l30_codigo',
        '=',
        'liclicita.l20_liccomissao'
      );

      $query->join(
        'licsituacao',
        'licsituacao.l08_sequencial',
        '=',
        'liclicita.l20_licsituacao'
      );

      $query->join(
        'pctipocompra',
        'pctipocompra.pc50_codcom',
        '=',
        'cflicita.l03_codcom'
      );
  }

  function getNextVal(){
    return $this->model->getNextval();
  }

  function save(ParecerLicitacao $data){
    $data->save();
    return $data;
  }

  function find(int $l200_sequencial){
    return $this->model
        ->where('l200_sequencial', $l200_sequencial)
        ->first();
  }

  function delete(ParecerLicitacao $aData){
    $aData->delete();
  }

  public function update(int $l200_sequencial, array $data){
    $oDispensa = $this->model->findOrFail($l200_sequencial);
    $oDispensa->update($data);

    return $oDispensa;
  }
}
