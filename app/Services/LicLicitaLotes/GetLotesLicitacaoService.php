<?php
namespace App\Services\LicLicitaLotes;

use App\Repositories\Patrimonial\Licitacao\LicLicitaLotesRepository;

class GetLotesLicitacaoService{
    private LicLicitaLotesRepository $liclicitalotesRepository;

    public function __construct()
    {
        $this->liclicitalotesRepository = new LicLicitaLotesRepository();
    }

    public function execute(object $data){
        $oData = $this->liclicitalotesRepository->getLotesByLicLicita($data->l20_codigo);
        return [
            'status' => 200,
            'message' => 'Lote carregados com sucesso!',
            'data' => $oData
        ];
    }
}
