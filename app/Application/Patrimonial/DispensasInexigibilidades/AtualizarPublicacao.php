<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicita\UpdatePublicacaoService;
use App\Services\Patrimonial\DispensasInexigibilidades\LicLicitaService;

class AtualizarPublicacao implements HandleRepositoryInterface{

    private UpdatePublicacaoService $updatePublicacaoService;

    public function __construct()
    {
        $this->updatePublicacaoService = new UpdatePublicacaoService();
    }

    public function handle(object $data)
    {
        return $this->updatePublicacaoService->execute($data);
    }
}
