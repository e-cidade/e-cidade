<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\Cflicita;
use App\Repositories\Contracts\Patrimonial\Licitacao\CflicitaRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;

class CflicitaRepository implements CflicitaRepositoryInterface
{
    private Cflicita $model;

    public function __construct()
    {
        $this->model = new Cflicita();
    }

    public function getDadosByFilter(int $l03_instit, ?array $l03_pctipocompratribunal):?array
    {
        $query = $this->model->query();
        $query->orderBy('l03_descr', 'ASC');

        $query->where('l03_instit', $l03_instit);

        if(!empty($l03_pctipocompratribunal)){
            $query->whereIn('l03_pctipocompratribunal', $l03_pctipocompratribunal);
        }

        return $query->get()->toArray();
    }

    public function getDadosTipoProcesso(int $l03_codigo){
        return $this->model
            ->where('l03_codigo', $l03_codigo)
            ->get()
            ->first();
    }

}
