<?php
namespace App\Services\LicLicitaLotes;

use App\Models\Patrimonial\Licitacao\LicLicitaLotes;
use App\Repositories\Patrimonial\Licitacao\LicLicitaLotesRepository;
use Illuminate\Database\Capsule\Manager as DB;

class InsertLotesService{
    private LicLicitaLotesRepository $liclicitalotesRepository;

    public function __construct()
    {
        $this->liclicitalotesRepository = new LicLicitaLotesRepository();
    }

    public function execute(object $data){
        $l24_codliclicita = $data->l24_codliclicita;
        $l24_pcdesc = $data->l24_pcdesc;

        $oData = new LicLicitaLotes([
            'l24_codigo' => $this->liclicitalotesRepository->getCodigo(),
            'l24_codliclicita' => $l24_codliclicita,
            'l24_pcdesc' => $l24_pcdesc
        ]);

        $lote = $this->liclicitalotesRepository->save($oData);
        if(empty($lote)){
            return [
                'status' => 500,
                'message' => 'Erro ao inserir lote!',
            ];
        }

        return [
            'status' => 200,
            'message' => 'Lote inserido com sucesso!',
        ];
    }
}
