<?php
namespace App\Services\PcParam;

use App\Repositories\Patrimonial\Compras\PcDotacRepository;
use App\Repositories\Patrimonial\Compras\PcprocitemRepository;
use App\Repositories\Patrimonial\Compras\SolicItemEleRepository;
use App\Repositories\Patrimonial\Compras\SolicItemPcMaterRepository;
use App\Repositories\Patrimonial\Compras\SolicItemRepository;
use App\Repositories\Patrimonial\Orcamento\OrcElementoRepository;
use Illuminate\Database\Capsule\Manager as DB;

class DeleteDotacaoService{
    private PcDotacRepository $pcdotacRepository;
    private PcprocitemRepository $pcprocitemRepository;
    private SolicItemRepository $solicitemRepository;
    private SolicItemPcMaterRepository $solicitempcmaterRepository;
    private SolicItemEleRepository $solicitemeleRepository;
    private OrcElementoRepository $orcelementoRepository;

    public function __construct()
    {
        $this->pcdotacRepository = new PcDotacRepository();
        $this->pcprocitemRepository = new pcprocitemRepository();
        $this->solicitemRepository = new SolicItemRepository();
        $this->solicitempcmaterRepository = new SolicItemPcMaterRepository();
        $this->solicitemeleRepository = new SolicItemEleRepository();
        $this->orcelementoRepository = new OrcElementoRepository();
    }

    public function execute(object $data){
        DB::beginTransaction();
        try{
            $itensProcesso = $this->pcprocitemRepository->getItensProcesso($data->l20_codigo);
            if(empty($itensProcesso)){
                throw new \Exception("Nenhum item do processo foi encontrado", 1);
            }

            $dotacoes = $this->pcdotacRepository->getDotacoesProcItens($data->l20_codigo, $data->anousu, 0, 0, false)['data'];
            foreach($itensProcesso as $key => $value){
                $itens = $this->solicitemRepository->getDadosItens($data->l20_codigo, $value['pc81_codproc']);
                if(empty($itens)){
                    continue;
                }

                foreach($itens as $item){
                    $codigoItem = $item['pc11_codigo'];
                    $servico = $this->solicitempcmaterRepository->getDadosByCodigoItem($codigoItem)->toArray();
                    $codele = $this->solicitemeleRepository->getDadosCodEle($codigoItem);
                    $qtdeDotacao = 0;

                    if(!empty($codele)){
                        $codele = $codele->toArray();
                        $oElemento = $this->orcelementoRepository->getDadosElemento($codele['pc18_codele'], $data->anousu)->toArray();
                        $elemento = substr($oElemento['o56_elemento'], 0, 7);
    
                        foreach($dotacoes as $value){
                            if($elemento == substr($value['o50_estrutdespesa'], 23, 7)){
                                $qtdeDotacao++;
                            }
                        }
                    }

                    $oPcdotac = $this->pcdotacRepository->getPcDotacByCodAndCodDot($codigoItem, $data->o58_coddot);
                    if(!empty($oPcdotac)){
                        $this->pcdotacRepository->deletePcDotac($oPcdotac);
                    }

                    if($qtdeDotacao > 1){
                        $qtdeDotacao--;
                    }

                    $qtdeValor = (!empty($item['pc11_quant']) && !empty($qtdeDotacao) ? $item['pc11_quant'] / $qtdeDotacao : 1);

                    if(!$servico['pc01_servico'] || !empty($item['pc11_servicoquantidade'])){
                            $this->pcdotacRepository->updateAllByCodItem($codigoItem, $qtdeValor, $qtdeValor);
                            continue;
                    }

                }
            }

            DB::commit();
            return [
                'status' => 200,
                'message' => 'Dotação removida com sucesso!',
                'data' => []
            ];
        }catch(\Throwable $e){
            DB::rollBack();
            return [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }
}
