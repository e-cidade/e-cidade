<?php
namespace App\Application\Patrimonial\Licitacoes;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicitaLotes\GetLotesLicitacaoService;

class GetLotes implements HandleRepositoryInterface{

    private GetLotesLicitacaoService $getLotesLicitacaoService;

    public function __construct()
    {
        $this->getLotesLicitacaoService = new GetLotesLicitacaoService();
    }

    public function handle(object $data)
    {
        return $this->getLotesLicitacaoService->execute($data);
    }
}
