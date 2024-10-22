<?php
namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Repositories\Patrimonial\PlanoContratacao\PcTipoProdutoRepository;

class ListagemTipoProduto implements HandleRepositoryInterface{
    private PcTipoProdutoRepository $pcTipoProdutoRepository;

    public function __construct(){
        $this->pcTipoProdutoRepository = new PcTipoProdutoRepository();
    }

    public function handle($data)
    {
        return ['pctipoproduto' => $this->pcTipoProdutoRepository->getDados()];
    }
}
