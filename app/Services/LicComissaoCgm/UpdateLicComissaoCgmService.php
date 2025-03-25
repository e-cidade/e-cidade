<?php

namespace App\Services\LicComissaoCgm;

use App\Models\Patrimonial\Licitacao\LicComissaoCgm;
use App\Repositories\Patrimonial\Licitacao\LicComissaoCgmRepository;

class UpdateLicComissaoCgmService{
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
            $oData = new LicComissaoCgm([
                'l31_codigo'      => $this->licComissaoCgmRepository->getNextVal(),
                'l31_liccomissao' => null,
                'l31_numcgm'      => $data->l31_numcgm,
                'l31_tipo'        => $data->l31_tipo,
                'l31_licitacao'   => $data->l31_licitacao,
            ]);

            $oResponseCgm = $this->licComissaoCgmRepository->save($oData);
        } else {
            $oResponseCgm = $this->licComissaoCgmRepository->update($licComissaoCgm->l31_codigo, [
                'l31_numcgm' => $data->l31_numcgm
            ]);
        }

        if(empty($oResponseCgm)){
            return [
                'status'  => 400,
                'message' => 'Não foi possivel salvar o CGM, por favor tente novamente!',
                'data'    => []
            ];
        }

        return [
            'status'  => 200,
            'message' => 'CGM inserido com sucesso!',
            'data'    => []
        ];
    }
}
