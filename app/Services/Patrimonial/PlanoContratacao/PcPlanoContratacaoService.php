<?php
namespace App\Services\Patrimonial\PlanoContratacao;

use App\Events\PlanoContratacao\PlanoContratacaoCreated;
use App\Events\PlanoContratacao\PlanoContratacaoDownload;
use App\Events\PlanoContratacao\PlanoContratacaoItemCreated;
use App\Events\PlanoContratacao\PlanoContratacaoRemoved;
use App\Models\Patrimonial\Compras\PcPlanoContratacao;
use App\Repositories\Patrimonial\PlanoContratacao\PcPlanoContratacaoRepository;
use App\Repositories\Patrimonial\PlanoContratacao\PlanoContratacaoPcPcItemRepository;
use App\Support\EventManager;

class PcPlanoContratacaoService {
    private PcPlanoContratacaoRepository $pcPlanoContratacaoRepository;
    private PlanoContratacaoPcPcItemRepository $pcPlanoContracaoPcItemRepository;

    public function __construct()
    {
        $this->pcPlanoContratacaoRepository = new PcPlanoContratacaoRepository();
        $this->pcPlanoContracaoPcItemRepository = new PlanoContratacaoPcPcItemRepository();
    }
    public function create(
        int $mpc01_ano,
        int $mpc01_uncompradora,
        ?string $mpc01_data,
        int $mpc01_usuario
    ){
        $pcPlanoContratacao = new PcPlanoContratacao(
            [
                'mpc01_codigo' => $this->pcPlanoContratacaoRepository->getCodigo(),
                'mpc01_ano' => $mpc01_ano,
                'mpc01_uncompradora' => $mpc01_uncompradora,
                'mpc01_data' => !empty($mpc01_data) ? date('Y-m-d', strtotime(str_replace('/','-',$mpc01_data))) : null,
                'mpc01_datacria' => date('Y-m-d H:i:s'),
                'mpc01_usuario' => $mpc01_usuario
            ]
        );

        return $this->pcPlanoContratacaoRepository->save($pcPlanoContratacao);
    }

    public function getAllByAnoUnidade(int $ano, int $unidade, int $limit = 15, int $offset = 0){
        return $this->pcPlanoContratacaoRepository->getByAnoUnidade(
            $ano,
            $unidade,
            $limit,
            $offset
        );
    }

    public function getDadosGrafico(int $mpc01_codigo){
        $data = $this->pcPlanoContratacaoRepository->getGrafico($mpc01_codigo);
        $dados = [];

        foreach($data?:[] as $value){
            $dados[mb_convert_encoding($value->mpc03_pcdesc, 'UTF-8', 'ISO-8859-1')] = $value->qtd;
        }

        return ['data' => $dados];
    }

    public function getDadosValores(int $mpc01_codigo){
        return ['valores' => $this->pcPlanoContratacaoRepository->getValores($mpc01_codigo)[0]];
    }

    public function publicar(
        int $mpc01_codigo,
        string $anousu,
        int $instit,
        int $id_usuario,
        string $datausu
    ){
        $planoContratacao = $this->pcPlanoContratacaoRepository->getPlanoContratacaoByCodigo(
            $mpc01_codigo
        );

        $response = [];

        $iTotalItensPublicacao = $this->pcPlanoContracaoPcItemRepository->getItemsCreatePlanoTotal($planoContratacao->mpc01_codigo);
        $pages = ceil($iTotalItensPublicacao / 200) - 1;

        if(empty($planoContratacao->mpc01_is_send_pncp)){
            $tentativas = 0;
            do {
                $response = EventManager::dispatch(new PlanoContratacaoCreated(
                    $planoContratacao,
                    $anousu,
                    $instit,
                    $id_usuario,
                    $datausu
                ));

                $tentativas++;
                $planoContratacao = $this->pcPlanoContratacaoRepository->getPlanoContratacaoByCodigo(
                    $mpc01_codigo
                );
                sleep(1);
            } while ($tentativas < 3 && empty($planoContratacao->mpc01_is_send_pncp));

            for ($i = 0; $i < count($response); $i++) {
                if(strpos($response[$i]['listener'], 'SendPlanoContratacaoCreated') !== false && !in_array($response[$i]['response']['status'], [200, 201])) {
                    return $response[$i]['response'];
                }
            }
        }

        if($pages > 0){
            $tentativas = 0;
            do {
                for ($i = 0; $i < $pages; $i++) {
                    $pcPlanoContratacaoPcItem = $this->pcPlanoContracaoPcItemRepository->getItemsCreatePlano($planoContratacao->mpc01_codigo);
                    if(empty($pcPlanoContratacaoPcItem)){
                        continue;
                    }
                    $response = EventManager::dispatch(new PlanoContratacaoItemCreated(
                        $planoContratacao,
                        '',
                        $pcPlanoContratacaoPcItem,
                        $anousu,
                        $instit,
                        $id_usuario,
                        $datausu
                    ));
                }

                $tentativas++;
                $iTotalItensFaltantes = $this->pcPlanoContracaoPcItemRepository->getItemsCreatePlanoTotal($planoContratacao->mpc01_codigo);
                sleep(1);
            } while ($tentativas < 3 && $iTotalItensFaltantes > 0);
        }

        for ($i = 0; $i < count($response); $i++) {
            if(strpos($response[$i]['listener'], 'SendPlanoContratacaoCreated') !== false || strpos($response[$i]['listener'], 'SendPlanoContratacaoItemCreated') !== false) {
                $response[$i]['response']['message'] = 'Plano de contratação publicado com sucesso!';
                return $response[$i]['response'];
            }
        }

        return ['status' => 403, 'message' => 'Erro ao publicar.'];
    }

    public function delete(
        int $mpc01_codigo,
        string $justificativa,
        string $anousu,
        int $instit,
        int $id_usuario,
        string $datausu
    ) {
        $planoContratacao = $this->pcPlanoContratacaoRepository->getPlanoContratacaoByCodigo($mpc01_codigo);
        if(empty($planoContratacao->mpc01_is_send_pncp)){
            $items = $this->pcPlanoContracaoPcItemRepository->findByPlanoContratacao($planoContratacao->mpc01_codigo);
            foreach ($items as $key => $value) {
                $this->pcPlanoContracaoPcItemRepository->removeByCodigo($value['mpcpc01_codigo']);
            }

            $this->pcPlanoContratacaoRepository->deleteByCodigo($planoContratacao->mpc01_codigo);
            return ['status' => 200, 'message' => 'Plano removido com sucesso!'];
        }

        $response = EventManager::dispatch(new PlanoContratacaoRemoved(
            $planoContratacao,
            $justificativa,
            $anousu,
            $instit,
            $id_usuario,
            $datausu
        ));

        $return = null;
        for ($i = 0; $i < count($response); $i++) {
            if(strpos($response[$i]['listener'], 'SendPlanoContratacaoDeleted') !== false) {
                $return = $response[$i]['response'];
                break;
            }
        }

        if(!empty($return) && in_array($return['status'], [200, 201, 404])){
            $items = $this->pcPlanoContracaoPcItemRepository->findByPlanoContratacao($planoContratacao->mpc01_codigo);
            foreach ($items as $key => $value) {
                $this->pcPlanoContracaoPcItemRepository->update(
                    $value['mpcpc01_codigo'],
                    [
                        'mpcpc01_is_send_pncp' => 0,
                        'mpcpc01_numero_item' => null
                    ]
                );
            }

            $this->pcPlanoContratacaoRepository->update(
                $planoContratacao->mpc01_codigo,
                [
                    'mpc01_is_send_pncp' => 0,
                    'mpc01_sequencial' => null,
                    'mpc01_data' => null
                ]
            );

            return ['status' => 200, 'message' => 'Plano removido com sucesso.'];
        }

        return !empty($return) ? $return : ['status' => 403, 'message' => 'Erro ao remover.'];
    }

    public function deletePlanoContratacao(
        int $mpc01_codigo
    ) {
        $planoContratacao = $this->pcPlanoContratacaoRepository->getPlanoContratacaoByCodigo($mpc01_codigo);
        if(!empty($planoContratacao->mpc01_is_send_pncp)){
            return ['status' => 400, 'message' => 'Para remover o plano de contratação é necessário realizar a  deleção no PNCP!'];
        }

        $items = $this->pcPlanoContracaoPcItemRepository->findByPlanoContratacao($planoContratacao->mpc01_codigo);
        foreach ($items as $key => $value) {
            $this->pcPlanoContracaoPcItemRepository->removeByCodigo($value['mpcpc01_codigo']);
        }

        $this->pcPlanoContratacaoRepository->deleteByCodigo($planoContratacao->mpc01_codigo);
        return ['status' => 200, 'message' => 'Plano removido com sucesso!'];
    }

    public function download(int $mpc01_codigo){
        $planoContratacao = $this->pcPlanoContratacaoRepository->getPlanoContratacaoByCodigo($mpc01_codigo);
        $response = EventManager::dispatch(new PlanoContratacaoDownload($planoContratacao));

        $return = null;
        for ($i = 0; $i < count($response); $i++) {
            if(strpos($response[$i]['listener'], 'GetPlanoContratacaoDownload') !== false) {
                $return = $response[$i]['response'];
                break;
            }
        }

        return !empty($return) ? $return : ['status' => 403, 'message' => 'Erro ao realizar o download.'];
    }

}
