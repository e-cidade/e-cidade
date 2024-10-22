<?php
namespace App\Services\Patrimonial\PlanoContratacao;

use App\Events\PlanoContratacao\PlanoContratacaoItemCreated;
use App\Events\PlanoContratacao\PlanoContratacaoItemRemoved;
use App\Events\PlanoContratacao\PlanoContratacaoItemRetificar;
use App\Models\Patrimonial\Compras\PcpcItem;
use App\Models\Patrimonial\Compras\PcPlanoContratacaoPcPcItem;
use App\Repositories\Patrimonial\PlanoContratacao\PcPcItemRepository;
use App\Repositories\Patrimonial\PlanoContratacao\PcPlanoContratacaoRepository;
use App\Repositories\Patrimonial\PlanoContratacao\PlanoContratacaoPcPcItemRepository;
use App\Support\EventManager;

class PlanoContratacaoPcPcItemService{
    private PlanoContratacaoPcPcItemRepository $pcPlanoContratacaoPcPcItem;
    private PcPlanoContratacaoRepository $planoContratacaoRepository;
    private PcPcItemRepository $pcPcItemRepository;

    public function __construct()
    {
        $this->pcPlanoContratacaoPcPcItem = new PlanoContratacaoPcPcItemRepository();
        $this->planoContratacaoRepository = new PcPlanoContratacaoRepository();
        $this->pcPcItemRepository = new PcPcItemRepository();
    }

    public function create(
        int $mpc01_codigo,
        array $itens,
        string $mpc02_datap
    ): array{
        $return = [];
        $pcPlanoContratacao = $this->planoContratacaoRepository->getPlanoContratacaoByCodigo($mpc01_codigo);

        foreach($itens ?: [] as $value){
            if(!empty($value->mpcpc01_codigo)){
                continue;
            }

            if(empty($value->mpc02_codigo)){
                $pcPlanoContratacaoItem = $this->pcPcItemRepository->pcPcItemGetByProperties(
                    $value->mpc02_codmater,
                    $value->mpc02_categoria,
                    $value->mpc02_un,
                    $value->mpc02_depto,
                    $value->mpc02_catalogo,
                    $value->mpc02_tproduto,
                    $value->mpc02_subgrupo,
                );
                if(empty($pcPlanoContratacaoItem)){
                    $pcPlanoContratacaoItem = new PcpcItem(
                        [
                            'mpc02_codigo'    => $this->pcPcItemRepository->getCodigo(),
                            'mpc02_codmater'  => $value->mpc02_codmater,
                            'mpc02_categoria' => $value->mpc02_categoria,
                            'mpc02_un'        => $value->mpc02_un,
                            'mpc02_depto'     => $value->mpc02_depto,
                            'mpc02_catalogo'  => $value->mpc02_catalogo,
                            'mpc02_tproduto'  => $value->mpc02_tproduto,
                            'mpc02_subgrupo'  => $value->mpc02_subgrupo
                        ]
                    );
                }
                $pcPlanoContratacaoItem = $this->pcPcItemRepository->save($pcPlanoContratacaoItem);
            } else {
                $pcPlanoContratacaoItem = $this->pcPcItemRepository->getByCodigo($value->mpc02_codigo);
            }

            $pcPlanoContratacaoPcItem = $this->pcPlanoContratacaoPcPcItem->find($pcPlanoContratacao->mpc01_codigo, $pcPlanoContratacaoItem->mpc02_codigo);
            if(empty($pcPlanoContratacaoPcItem)){
                $pcPlanoContratacaoPcPcItem = new PcPlanoContratacaoPcPcItem([
                    'mpcpc01_codigo'                  => $this->pcPlanoContratacaoPcPcItem->getCodigo(),
                    'mpc01_pcplanocontratacao_codigo' => $pcPlanoContratacao->mpc01_codigo,
                    'mpc02_pcpcitem_codigo'           => $pcPlanoContratacaoItem->mpc02_codigo,
                    'mpcpc01_qtdd'                    => !empty($value->mpc02_qtdd)? $this->limitaCasasDecimais($value->mpc02_qtdd) : 0.000,
                    'mpcpc01_vlrunit'                 => !empty($value->mpc02_vlrunit)? $this->limitaCasasDecimais($value->mpc02_vlrunit) : 0.000,
                    'mpcpc01_vlrtotal'                => !empty($value->mpc02_vlrtotal)? $this->limitaCasasDecimais($value->mpc02_vlrtotal) : 0.000,
                    'mpcpc01_datap'                   => !empty($mpc02_datap) ? date('Y-m-d', strtotime(str_replace('-', '/', $mpc02_datap))) : null
                ]);

                $return[] = $this->pcPlanoContratacaoPcPcItem->save($pcPlanoContratacaoPcPcItem)->toArray();
                continue;
            }

            $return[] = $this->pcPlanoContratacaoPcPcItem->update(
                $pcPlanoContratacaoPcItem->mpcpc01_codigo,
                [
                    'mpcpc01_qtdd'     => !empty($value->mpc02_qtdd)? $this->limitaCasasDecimais($value->mpc02_qtdd) : 0.000,
                    'mpcpc01_vlrunit'  => !empty($value->mpc02_vlrunit)? $this->limitaCasasDecimais($value->mpc02_vlrunit) : 0.000,
                    'mpcpc01_vlrtotal' => !empty($value->mpc02_vlrtotal)? $this->limitaCasasDecimais($value->mpc02_vlrtotal) : 0.000,
                    'mpcpc01_datap'    => !empty($mpc02_datap) ? date('Y-m-d', strtotime(str_replace('-', '/', $mpc02_datap))) : null
                ]
            )->toArray();

        }

        return $return;
    }

    public function delete(
        ?int $mpc01_codigo,
        array $itens,
        ?string $justificativa,
        string $anousu,
        int $instit,
        int $id_usuario,
        string $datausu
    ) {
        $aDataRemove = [];
        foreach($itens ?: [] as $value){

            $pcPlanoContratacaoPcItem = $this->pcPlanoContratacaoPcPcItem->getByCodigo(
                $value->mpcpc01_codigo
            );

            if(empty($pcPlanoContratacaoPcItem)){
                continue;
            }

            if(empty($pcPlanoContratacaoPcItem->mpcpc01_is_send_pncp)){
                $this->pcPlanoContratacaoPcPcItem->delete($pcPlanoContratacaoPcItem);
                continue;
            }

            $aDataRemove[] = $pcPlanoContratacaoPcItem->toArray();
        }

        if(empty($aDataRemove)){
            return ['status' => 200, 'message' => 'Item(s) deletado(s) com sucesso.'];
        }

        $planoContratacao = $this->planoContratacaoRepository->getPlanoContratacaoByCodigo($mpc01_codigo);
        $response = EventManager::dispatch(new PlanoContratacaoItemRemoved(
            $planoContratacao,
            $justificativa,
            $aDataRemove,
            $anousu,
            $instit,
            $id_usuario,
            $datausu
        ));

        $return = null;
        for ($i = 0; $i < count($response); $i++) {
            if(strpos($response[$i]['listener'], 'SendPlanoContratacaoItemRemoved') !== false) {
                $return = $response[$i]['response'];
                break;
            }
        }

        if(!empty($return) && in_array($return['status'], [200, 201, 404])){
            foreach($aDataRemove as $key => $value){
                $this->pcPlanoContratacaoPcPcItem->removeByCodigo($value['mpcpc01_codigo']);
            }

            return ['status' => 200, 'message' => 'Item(s) deletado(s) com sucesso.'];
        }

        return !empty($return) ? $return : ['status' => 403, 'message' => 'Erro ao remover.'];
    }

    public function deleteItem(
        int $mpc02_codigo,
        int $mpc01_codigo,
        ?int $mpcpc01_codigo,
        ?string $justificativa,
        string $anousu,
        int $instit,
        int $id_usuario,
        string $datausu
    ){
        if(!empty($mpcpc01_codigo)){
            $pcPlanoContratacaoPcItem = $this->pcPlanoContratacaoPcPcItem->getByCodigo(
                $mpcpc01_codigo
            );
        } else {
            $pcPlanoContratacaoPcItem = $this->pcPlanoContratacaoPcPcItem->find(
                $mpc01_codigo,
                $mpc02_codigo
            );
        }

        if(empty($pcPlanoContratacaoPcItem->mpcpc01_is_send_pncp)){
            $this->pcPlanoContratacaoPcPcItem->delete($pcPlanoContratacaoPcItem);
            return ['status' => 200, 'message' => 'Item excluído com sucesso'];
        }

        $planoContratacao = $this->planoContratacaoRepository->getPlanoContratacaoByCodigo($mpc01_codigo);
        $response = EventManager::dispatch(new PlanoContratacaoItemRemoved(
            $planoContratacao,
            $justificativa,
            [$pcPlanoContratacaoPcItem->toArray()],
            $anousu,
            $instit,
            $id_usuario,
            $datausu
        ));

        for ($i = 0; $i < count($response); $i++) {
            if(strpos($response[$i]['listener'], 'SendPlanoContratacaoItemRemoved') !== false){
                return $response[$i]['response'];
            }
        }

        return ['status' => 403, 'message' => 'Erro ao excluir item.'];
    }

    public function removerItem(
        int $mpc01_codigo,
        ?string $justificativa,
        string $anousu,
        int $instit,
        int $id_usuario,
        string $datausu
    ){
        $planoContratacao = $this->planoContratacaoRepository->getPlanoContratacaoByCodigo($mpc01_codigo);
        $pcPlanoContratacaoPcItem = $this->pcPlanoContratacaoPcPcItem->getSendByPlanoContracao($planoContratacao->mpc01_codigo);
        if(empty($pcPlanoContratacaoPcItem)){
            return ['status' => 400, 'message' => 'Por favor informe itens já publicados'];
        }

        $response = EventManager::dispatch(new PlanoContratacaoItemRemoved(
            $planoContratacao,
            $justificativa,
            $pcPlanoContratacaoPcItem,
            $anousu,
            $instit,
            $id_usuario,
            $datausu
        ));

        for ($i = 0; $i < count($response); $i++) {
            if(strpos($response[$i]['listener'], 'SendPlanoContratacaoItemRemoved') !== false){
                return $response[$i]['response'];
            }
        }

        return ['status' => 403, 'message' => 'Erro ao excluir item.'];
    }

    public function retificarItem(
        int $mpc01_codigo,
        ?string $justificativa,
        array $itens,
        string $anousu,
        int $instit,
        int $id_usuario,
        string $datausu
    ){
        $planoContratacao = $this->planoContratacaoRepository->getPlanoContratacaoByCodigo($mpc01_codigo);
        if(empty($itens)){
            return ['status' => 400, 'message' => 'Por favor informes o itens'];
        }

        $pcPlanoContratacaoPcItem = $this->pcPlanoContratacaoPcPcItem->getByPlanoContratacaoAndItens(
            $planoContratacao->mpc01_codigo,
            array_column($itens, 'mpc02_codigo')
        );

        if(empty($pcPlanoContratacaoPcItem)){
            return ['status' => 400, 'message' => 'Por favor informe itens válidos'];
        }

        $aDataItensRetificar = [];
        $aDataItensInserir = [];
        foreach($pcPlanoContratacaoPcItem ?: [] as $value){
            if(!empty($value['mpcpc01_is_send_pncp'])){
                $aDataItensRetificar[] = $value;
                continue;
            }

            $aDataItensInserir[] = $value;
        }

        $perPage = 200;
        $return = null;
        if(!empty($aDataItensRetificar)){
            $totalItens = count($aDataItensRetificar);
            $pages = ceil($totalItens / $perPage);
            for ($page = 1; $page <= $pages; $page++) {
                $offset = ($page - 1) * $perPage;
                $itens = array_slice($aDataItensRetificar, $offset, $perPage);
                $tentativas = 0;
                $isSendSuccessful = false;

                do {
                    $response = EventManager::dispatch(new PlanoContratacaoItemRetificar(
                        $planoContratacao,
                        $justificativa,
                        $itens,
                        $anousu,
                        $instit,
                        $id_usuario,
                        $datausu
                    ));

                    $result = array_filter($response, function($item){
                        return strpos($item['listener'], 'SendPlanoContratacaoItemRetificar') !== false;
                    })[0];

                    if (in_array($result['response']['status'], [200, 201])) {
                        $isSendSuccessful = true;
                    }

                    $tentativas++;
                    sleep(pow(2, $tentativas - 1));

                } while ($tentativas < 3 && !$isSendSuccessful);
            }

            $result = array_filter($response, function($item){
                return strpos($item['listener'], 'SendPlanoContratacaoItemRetificar') !== false;
            })[0];

            if (!in_array($result['response']['status'], [200, 201])) {
                return $result['response'];
            }
        }

        if(!empty($aDataItensInserir)){
            $totalItens = count($aDataItensInserir);
            $pages = ceil($totalItens / $perPage);

            for ($page = 1; $page <= $pages; $page++) {
                $offset = ($page - 1) * $perPage;
                $itens = array_slice($aDataItensInserir, $offset, $perPage);
                $tentativas = 0;
                $isSendSuccessful = false;

                do{
                    $response = EventManager::dispatch(new PlanoContratacaoItemCreated(
                        $planoContratacao,
                        $justificativa,
                        $itens,
                        $anousu,
                        $instit,
                        $id_usuario,
                        $datausu
                    ));

                    $result = array_filter($response, function($item){
                        return strpos($item['listener'], 'SendPlanoContratacaoItemCreated') !== false;
                    })[0];

                    if (in_array($result['response']['status'], [200, 201])) {
                        $isSendSuccessful = true;
                    }

                    $tentativas++;
                    sleep(pow(2, $tentativas - 1));
                } while($tentativas < 3 && !$isSendSuccessful);

                $result = array_filter($response, function($item){
                    return strpos($item['listener'], 'SendPlanoContratacaoItemCreated') !== false;
                })[0];

                if (!in_array($result['response']['status'], [200, 201])) {
                    return $result['response'];
                }
            }
        }

        return !empty($result)? $result['response'] : ['status' => 200, 'message' => 'Erro ao retificar'];
    }

    public function getItemPlanoContratacao(int $mpc02_codigo, int $mpc01_codigo){
        return $this->pcPlanoContratacaoPcPcItem->getItemPlanoContratacao($mpc02_codigo, $mpc01_codigo)->toArray();
    }

    public function updateItem(
        int $mpcpc01_codigo,
        int $mpc02_codmater,
        int $mpc02_categoria,
        int $mpc02_un,
        int $mpc02_depto,
        int $mpc02_catalogo,
        int $mpc02_tproduto,
        int $mpc02_subgrupo,
        string $mpcpc01_qtdd,
        string $mpcpc01_vlrunit,
        string $mpcpc01_vlrtotal,
        string $mpcpc01_datap
    )
    {
        $pcPlanoContratacaoPcItem = $this->pcPlanoContratacaoPcPcItem->getByCodigo($mpcpc01_codigo);
        $pcPlanoContratacaoItem = $this->pcPcItemRepository->getByCodigo(
            $pcPlanoContratacaoPcItem->mpc02_pcpcitem_codigo
        );

        $this->pcPcItemRepository->update(
            $pcPlanoContratacaoItem->mpc02_codigo,
            [
                'mpc02_codmater'  => $mpc02_codmater,
                'mpc02_categoria' => $mpc02_categoria,
                'mpc02_un'        => $mpc02_un,
                'mpc02_depto'     => $mpc02_depto,
                'mpc02_catalogo'  => $mpc02_catalogo,
                'mpc02_tproduto'  => $mpc02_tproduto,
                'mpc02_subgrupo'  => $mpc02_subgrupo,
            ]
        );

        $this->pcPlanoContratacaoPcPcItem->update(
            $pcPlanoContratacaoPcItem->mpcpc01_codigo,
            [
                'mpcpc01_qtdd'     => $this->convertFloat($mpcpc01_qtdd),
                'mpcpc01_vlrunit'  => $this->convertFloat($mpcpc01_vlrunit),
                'mpcpc01_vlrtotal' => $this->convertFloat($mpcpc01_vlrtotal),
                'mpcpc01_datap'    => !empty($mpcpc01_datap) ? date('Y-m-d', strtotime(str_replace('/', '-', $mpcpc01_datap))) : null,
            ]
        );

        return $this->getItemPlanoContratacao(
            $pcPlanoContratacaoPcItem->mpc02_pcpcitem_codigo,
            $pcPlanoContratacaoPcItem->mpc01_pcplanocontratacao_codigo
        );
    }

    public function createItem(
        int $mpc01_codigo,
        int $mpc02_codmater,
        ?string $mpcpc01_datap,
        int $mpc02_categoria,
        string $mpcpc01_qtdd,
        string $mpcpc01_vlrunit,
        string $mpcpc01_vlrtotal,
        int $mpc02_un,
        int $mpc02_depto,
        int $mpc02_catalogo,
        int $mpc02_tproduto,
        int $mpc02_subgrupo
    ){
        $pcPlanoContratacao = $this->planoContratacaoRepository->getPlanoContratacaoByCodigo($mpc01_codigo);

        $pcPlanoContratacaoPcItem = $this->pcPlanoContratacaoPcPcItem->pcPcItemGetByCodMaterAndUnit(
            $mpc02_codmater,
            $mpc02_un,
            $pcPlanoContratacao->mpc01_codigo
        );


        if(!empty($pcPlanoContratacaoPcItem)){
            return [
                'status' => 400,
                'message' => 'Item já foi inserido ao PCA',
                'pcpcitem' => null
            ];
        }


        $pcPlanoContratacaoItem = $this->pcPcItemRepository->pcPcItemGetByProperties(
            $mpc02_codmater,
            $mpc02_categoria,
            $mpc02_un,
            $mpc02_depto,
            $mpc02_catalogo,
            $mpc02_tproduto,
            $mpc02_subgrupo,
        );

        if(empty($pcPlanoContratacaoItem)){
            $pcPlanoContratacaoItem = new PcpcItem(
                [
                    'mpc02_codigo'    => $this->pcPcItemRepository->getCodigo(),
                    'mpc02_codmater'  => $mpc02_codmater,
                    'mpc02_categoria' => $mpc02_categoria,
                    'mpc02_un'        => $mpc02_un,
                    'mpc02_depto'     => $mpc02_depto,
                    'mpc02_catalogo'  => $mpc02_catalogo,
                    'mpc02_tproduto'  => $mpc02_tproduto,
                    'mpc02_subgrupo'  => $mpc02_subgrupo
                ]
            );
            $pcPlanoContratacaoItem = $this->pcPcItemRepository->save($pcPlanoContratacaoItem);
        }

        $pcPlanoContratacaoPcPcItem = new PcPlanoContratacaoPcPcItem([
            'mpcpc01_codigo'                  => $this->pcPlanoContratacaoPcPcItem->getCodigo(),
            'mpc01_pcplanocontratacao_codigo' => $pcPlanoContratacao->mpc01_codigo,
            'mpc02_pcpcitem_codigo'           => $pcPlanoContratacaoItem->mpc02_codigo,
            'mpcpc01_qtdd'                    => !empty($mpcpc01_qtdd)? $this->convertFloat($mpcpc01_qtdd) : 0.000,
            'mpcpc01_vlrunit'                 => !empty($mpcpc01_vlrunit)? $this->convertFloat($mpcpc01_vlrunit) : 0.000,
            'mpcpc01_vlrtotal'                => !empty($mpcpc01_vlrtotal)? $this->convertFloat($mpcpc01_vlrtotal) : 0.000,
            'mpcpc01_datap'    => !empty($mpcpc01_datap) ? date('Y-m-d', strtotime(str_replace('-', '/', $mpcpc01_datap))) : null
        ]);

        $pcPlanoContratacaoPcItem = $this->pcPlanoContratacaoPcPcItem->save($pcPlanoContratacaoPcPcItem);

        return [
            'status' => 200,
            'message' => 'Item cadastrado com sucesso',
            'pcpcitem' => $this->getItemPlanoContratacao($pcPlanoContratacaoPcItem->mpc02_pcpcitem_codigo,$pcPlanoContratacaoPcItem->mpc01_pcplanocontratacao_codigo)
        ];
    }


    private function convertFloat($number){
        $number = str_replace(',', '.', str_replace('.', '', $number));
        return number_format((float)$number, 4, '.', '');
    }

    private function limitaCasasDecimais($number){
        return number_format((float)$number, 4, '.', '');
    }
}
