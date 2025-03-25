<?php
namespace App\Services\PcProc;

use App\Repositories\Patrimonial\Compras\PcprocRepository;
use App\Repositories\Patrimonial\Licitacao\LiclicitaRepository;

class GetProcessoComprasService{
    private PcprocRepository $pcprocRepository;
    private LiclicitaRepository $liclicitaRepository;

    public function __construct()
    {
        $this->pcprocRepository = new pcprocRepository();
        $this->liclicitaRepository = new LiclicitaRepository();
    }

    public function execute(object $data){
        $sWhere = ' AND pc10_solicitacaotipo IN(1,2,8) ';

        if($data->l20_codigo){
            $oDispensa = $this->liclicitaRepository->getDispensasInexigibilidadeByCodigo($data->l20_codigo);
            if($oDispensa->l20_tipnaturezaproced == 2){
                $sWhere = ' AND pc10_solicitacaotipo IN(6) ';
            }

            if(!empty($oDispensa->l20_criterioadjudicacao)){
                $sWhere .= " AND pc80_criterioadjudicacao = $oDispensa->l20_criterioadjudicacao ";
            }
        }

        $oProcessoCompras = $this->pcprocRepository->getProcessodeComprasLicitacaoByCriterioAdjudicacao($sWhere);
        
        return [
            'status' => 200,
            'message' => 'Processos carregado com sucesso!',
            'data' => $oProcessoCompras ?: []
        ];

    }
}
