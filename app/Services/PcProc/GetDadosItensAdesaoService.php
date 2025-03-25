<?php
namespace App\Services\PcProc;

use App\Repositories\Patrimonial\Compras\PcprocRepository;
use App\Repositories\Sicom\AdesaoRegPrecosRepository;

class GetDadosItensAdesaoService{
    private AdesaoRegPrecosRepository $adesaoRegPrecosRepository;
    private PcprocRepository $pcprocRepository;

    public function __construct()
    {
      $this->adesaoRegPrecosRepository = new AdesaoRegPrecosRepository();
      $this->pcprocRepository = new pcprocRepository();
    }

    public function execute(object $data){
      $oAdesao = $this->adesaoRegPrecosRepository->getAdesaoRegPrecoByCodigo($data->si06_sequencial);
      $oData = $this->pcprocRepository->getItensByAdesaoPreco(
        $oAdesao->si06_sequencial,
        $oAdesao->si06_processocompra,
        $oAdesao->si06_criterioadjudicacao
      );
      
      return [
        'status' => 200,
        'message' => 'Itens carregados com sucesso!',
        'total' => !empty($oData['total']) ? $oData['total'] : 0,
        'data' => !empty($oData['data']) ? $oData['data'] : []
    ];
    }
}
