<?php

namespace App\Services\LicComissaoCgm;

use App\Models\Patrimonial\Licitacao\LicComissaoCgm;
use App\Repositories\Patrimonial\Licitacao\LicComissaoCgmRepository;

class DeleteLicComissaoCgmService{
    private LicComissaoCgmRepository $licComissaoCgmRepository;

    public function __construct()
    {
        $this->licComissaoCgmRepository = new LicComissaoCgmRepository();
    }

    public function execute(object $data){

        if(empty($data->l31_numcgm)){
            return [
                'status'  => 400,
                'message' => 'Por favor, informe o numero do CGM',
                'data'    => []
            ];
        }

        if(empty($data->l31_tipo)){
            return [
                'status'  => 400,
                'message' => 'Por favor, informe o tipo',
                'data'    => []
            ];
        }

        if(empty($data->l31_licitacao)){
            return [
                'status'  => 400,
                'message' => 'Por favor informe a licitacação',
                'data'    => []
            ];
        }

        if(!empty($data->l31_numcgm_odd)){
            $licComissaoCgm = $this->licComissaoCgmRepository->getLicComissaoByParams(
                $data->l31_licitacao,
                $data->l31_tipo,
                $data->l31_numcgm_odd
            );
        } else {
            $licComissaoCgm = $this->licComissaoCgmRepository->getLicComissaoByLicLicitaTipo(
                $data->l31_licitacao,
                $data->l31_tipo
            );
        }

        if(empty($licComissaoCgm)){
            return [
                'status'  => 200,
                'message' => 'Não há CGM para ser removida!',
                'data'    => []
            ];
        }

        $this->licComissaoCgmRepository->removeByCodigo($licComissaoCgm->l31_codigo);

        return [
            'status'  => 200,
            'message' => 'CGM removida com sucesso!',
            'data'    => []
        ];
    }
}
