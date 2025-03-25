<?php
namespace App\Application\Patrimonial\Licitacoes;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicitaLotes\DeleteLiclicitaItemLotesService;

class ExcluirItensLote implements HandleRepositoryInterface{

    private DeleteLiclicitaItemLotesService $deleteLiclicitaItemLotesService;

    public function __construct()
    {
        $this->deleteLiclicitaItemLotesService = new DeleteLiclicitaItemLotesService();
    }

    public function handle(object $data)
    {
        return $this->deleteLiclicitaItemLotesService->execute($data);
    }
}
