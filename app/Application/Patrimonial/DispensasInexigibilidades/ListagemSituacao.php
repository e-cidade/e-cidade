<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Repositories\Patrimonial\Licitacao\LicSituacaoRepository;

class ListagemSituacao implements HandleRepositoryInterface{

    private LicSituacaoRepository $licsituacaoRepository;

    public function __construct()
    {
        $this->licsituacaoRepository = new LicSituacaoRepository();
    }

    public function handle(object $data)
    {
        return ['data' => $this->licsituacaoRepository->getDados()];
    }
}
