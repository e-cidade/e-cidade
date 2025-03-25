<?php
namespace App\Application\Patrimonial\Licitacoes;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicitem\InsertLiclicitemService;

class SalvarItensProcesso implements HandleRepositoryInterface{

    private InsertLiclicitemService $insertLiclicitemService;

    public function __construct()
    {
        $this->insertLiclicitemService = new InsertLiclicitemService();
    }

    public function handle(object $data)
    {
        return $this->insertLiclicitemService->execute($data);
    }
}
