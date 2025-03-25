<?php
namespace App\Application\Patrimonial\Licitacoes;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicita\GetTribunalService;

class GetTribunal implements HandleRepositoryInterface{
    private GetTribunalService $getTribunalService;

    public function __construct()
    {
        $this->getTribunalService = new GetTribunalService();
    }

    public function handle(object $data)
    {
        return $this->getTribunalService->execute($data);
    }
}
