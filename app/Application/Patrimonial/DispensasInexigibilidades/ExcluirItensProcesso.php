<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicitem\DeleteItensProcessoService;

class ExcluirItensProcesso implements HandleRepositoryInterface{

    private DeleteItensProcessoService $deleteItensProcessoService;

    public function __construct()
    {
        $this->deleteItensProcessoService = new DeleteItensProcessoService();
    }

    public function handle(object $data)
    {
        return $this->deleteItensProcessoService->execute($data);
    }
}
