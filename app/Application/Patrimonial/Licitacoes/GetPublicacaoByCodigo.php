<?php
namespace App\Application\Patrimonial\Licitacoes;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicita\GetPublicacaoService;

class GetPublicacaoByCodigo implements HandleRepositoryInterface{

    private GetPublicacaoService $getPublicacaoService;

    public function __construct()
    {
        $this->getPublicacaoService = new GetPublicacaoService();
    }

    public function handle(object $data)
    {
        return $this->getPublicacaoService->execute($data);
    }
}
