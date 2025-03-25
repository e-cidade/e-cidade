<?php

namespace App\Services\LicLicitaSituacao;

use App\Models\Patrimonial\Licitacao\LicLicitaSituacao;
use App\Repositories\Patrimonial\Licitacao\LicLicitaSituacaoRepository;

class UpdateLicLicitaSituacaoService{
    private LicLicitaSituacaoRepository $licLicitaSituacaoRepository;

    public function __construct()
    {
        $this->licLicitaSituacaoRepository = new LicLicitaSituacaoRepository();
    }

    public function execute(object $data){
        if(empty($data->id_usuario) || empty($data->l20_codigo) || empty($data->datausu)){
            return [
                'status'  => 400,
                'message' => 'Por favor, informe todos os dados corretamente',
                'data'    => []
            ];
        }

        $oLicLicitaSituacao = $this->licLicitaSituacaoRepository->findByParams(
            $data->id_usuario,
            $data->l20_codigo
        );

        if(empty($oLicLicitaSituacao)){
            $oData = new LicLicitaSituacao([
                'l11_sequencial'  => $this->licLicitaSituacaoRepository->getNextVal(),
                'l11_id_usuario'  => $data->id_usuario,
                'l11_licsituacao' => 0,
                'l11_liclicita'   => $data->l20_codigo,
                'l11_obs'         => 'Licitacao em andamento.',
                'l11_data'        => date('Y-m-d', strtotime($data->datausu)),
                'l11_hora'        => date('H:i')
            ]);

            $oResult = $this->licLicitaSituacaoRepository->save($oData);
        } else {
            $oResult = $this->licLicitaSituacaoRepository->update($oLicLicitaSituacao->l11_sequencial, [
                'l11_id_usuario'  => $data->id_usuario,
                'l11_licsituacao' => 0,
                'l11_liclicita'   => $data->l20_codigo,
                'l11_obs'         => 'Licitacao em andamento.',
                'l11_data'        => date('Y-m-d', strtotime($data->datausu)),
                'l11_hora'        => date('H:i')
            ]);
        }

        if(empty($oResult)){
            return [
                'status'  => 400,
                'message' => 'Não foi possivel inserir a situação, tente novamente',
                'data'    => []
            ];
        }

        return [
            'status'  => 200,
            'message' => 'Situação salva com sucesso',
            'data'    => []
        ];
    }

}
