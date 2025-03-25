<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicitaLotes\InsertLotesService;

class InserirLote implements HandleRepositoryInterface{

    private InsertLotesService $insertLotesService;

    public function __construct()
    {
        $this->insertLotesService = new InsertLotesService();
    }

    public function handle(object $data)
    {
        return $this->insertLotesService->execute($data);
    }
}
