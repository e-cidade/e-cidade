<?php
namespace App\Services\Patrimonial\Orcamento;

use App\Repositories\Patrimonial\Orcamento\OrcParametroRepository;

class GetOrcParametroService{
    private OrcParametroRepository $orcParametroRepository;

    public function __construct()
    {
        $this->orcParametroRepository = new OrcParametroRepository();
    }

    public function execute(object $data){
        return $this->orcParametroRepository->getDados($data->anousu ?? null)->toArray();
    }
}
