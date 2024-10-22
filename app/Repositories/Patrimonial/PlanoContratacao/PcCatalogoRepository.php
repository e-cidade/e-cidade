<?php
namespace App\Repositories\Patrimonial\PlanoContratacao;

use App\Models\Patrimonial\Compras\PcCatalogo;
use App\Repositories\Contracts\Patrimonial\PlanoDeContratacao\PcCatalogoRepositoryInterface;

class PcCatalogoRepository implements PcCatalogoRepositoryInterface{
    private PcCatalogo $model;

    public function __construct()
    {
        $this->model = new PcCatalogo();
    }

    public function getDados()
    {
        $result = $this->model
            ->orderby('mpc04_pcdesc', 'asc')
            ->get()
            ->toArray();

        return $result;
    }

}
