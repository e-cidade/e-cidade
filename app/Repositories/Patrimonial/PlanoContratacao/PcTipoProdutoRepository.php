<?php
namespace App\Repositories\Patrimonial\PlanoContratacao;

use App\Models\Patrimonial\Compras\PcTipoProduto;
use App\Repositories\Contracts\Patrimonial\PlanoDeContratacao\PcTipoProdutoRepositoryInterface;

class PcTipoProdutoRepository implements PcTipoProdutoRepositoryInterface{
    private PcTipoProduto $model;

    public function __construct()
    {
        $this->model = new PcTipoProduto();
    }

    public function getDados()
    {
        $result = $this->model
            ->orderby('mpc05_pcdesc', 'asc')
            ->get()
            ->toArray();

        return $result;
    }

}
