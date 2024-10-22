<?php

namespace App\Repositories\Patrimonial\Materiais;

use App\Models\Patrimonial\Materiais\MatUnid;
use App\Repositories\Contracts\Patrimonial\Materiais\MatUnidRepositoryInterface;

class MatUnidRepository implements MatUnidRepositoryInterface{
    private MatUnid $model;

    public function __construct(){
        $this->model = new MatUnid();
    }

    public function getDados(){
        $result = $this->model
            ->orderby('m61_codmatunid', 'asc')
            ->get()
            ->toArray();

        return $result;
    }
}
