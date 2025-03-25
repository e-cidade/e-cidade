<?php
namespace App\Services\LicLicitaParam;

use App\Repositories\Patrimonial\Licitacao\LicLicitaParamRepository;

class GetLicLicitaParamService{
    private LicLicitaParamRepository $licLicitaParam;

    public function __construct()
    {
        $this->licLicitaParam = new LicLicitaParamRepository();
    }

    public function execute(object $data){
        return $this->licLicitaParam->getDados($data->instit)->toArray();
    }
}
