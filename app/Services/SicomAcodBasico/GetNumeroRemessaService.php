<?php
namespace App\Services\SicomAcodBasico;

use App\Repositories\Patrimonial\Licitacao\Sicom\SicomAcodBasicoRepository;
use Illuminate\Database\Capsule\Manager as DB;

class GetNumeroRemessaService{
    private SicomAcodBasicoRepository $sicomAcodBasicoRepository;

    public function __construct()
    {
        $this->sicomAcodBasicoRepository = new SicomAcodBasicoRepository();
    }

    public function execute(object $data){
        $remessa = $this->sicomAcodBasicoRepository->getNextRemessa($data->instit);

        return [
            'status'  => 200,
            'message' => 'Remessa gerada com sucesso',
            'data'    => [
                'l228_seqremessa' => $remessa->l228_seqremessa
            ]
        ];
    }
}
