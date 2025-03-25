<?php
namespace App\Services\PcParam;

use App\Repositories\Patrimonial\Compras\PcParamRepository;

class GetPcParamService{
    private PcParamRepository $pcparamRepository;

    public function __construct()
    {
        $this->pcparamRepository = new PcParamRepository();
    }

    public function execute(object $data){
        return $this->pcparamRepository->getDados($data->instit)->toArray();
    }
}
