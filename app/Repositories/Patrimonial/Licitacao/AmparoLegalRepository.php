<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\AmparoLegal;
use App\Repositories\Contracts\Patrimonial\Licitacao\AmparoLegalRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;

class AmparoLegalRepository implements AmparoLegalRepositoryInterface
{
    private AmparoLegal $model;

    public function __construct()
    {
        $this->model = new AmparoLegal();
    }

    public function getDadosByFilter(?int $l20_codtipocom):?array
    {
        $query = $this->model->query();

        if(!empty($l20_codtipocom)){
            $query->whereIn('l212_codigo', function($query) use ($l20_codtipocom){
                $query
                    ->select('l213_amparo')
                    ->from('amparocflicita')
                    ->where('l213_modalidade', $l20_codtipocom)
                ;
            });
        }
        return $query->get()->toArray();
    }
}
