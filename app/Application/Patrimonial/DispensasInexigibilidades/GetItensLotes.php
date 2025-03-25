<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicitem\GetLiclicitemLotesService;

class GetItensLotes implements HandleRepositoryInterface{

    private GetLiclicitemLotesService $getLiclicitemLotesService;

    public function __construct()
    {
        $this->getLiclicitemLotesService = new GetLiclicitemLotesService();
    }

    public function handle(object $data)
    {
        return $this->getLiclicitemLotesService->execute($data);
    }
}
