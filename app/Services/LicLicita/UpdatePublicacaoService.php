<?php

namespace App\Services\LicLicita;

use App\Repositories\Patrimonial\Licitacao\LiclicitaRepository;
use App\Services\LicComissaoCgm\DeleteLicComissaoCgmService;
use App\Services\LicComissaoCgm\InsertLicComissaoCgmService;
use DateTime;
use Exception;
use Illuminate\Database\Capsule\Manager as DB;

class UpdatePublicacaoService{

    private LiclicitaRepository $liclicitaRepository;

    private InsertLicComissaoCgmService $insertLicComissaoCgmService;
    private DeleteLicComissaoCgmService $deleteLicComissaoCgmService;

    public function __construct(){
        $this->liclicitaRepository = new LiclicitaRepository();

        $this->insertLicComissaoCgmService = new InsertLicComissaoCgmService();
        $this->deleteLicComissaoCgmService = new DeleteLicComissaoCgmService();
    }

    public function execute(object $data){

        DB::beginTransaction();
        try{
            $oDispensa = $this->liclicitaRepository->getDispensasInexigibilidadeByCodigo($data->l20_codigo);
            if(empty($data->respPubliccodigo)){
                throw new Exception("Campo Resp. pela Publicação no informado", 1);
            }

            if(
                !empty($oDispensa->l20_datacria) 
                && !empty($data->l20_dtpublic)
                && date('Y-m-d', strtotime($data->l20_dtpublic)) < date('Y-m-d', strtotime($oDispensa->l20_datacria))
            ){
                throw new \Exception("A Data Publicação DO deve ser maior ou igual a Data de Abertura Proc. Adm.");
            }

            $aData = [
                'l20_dtpulicacaoedital' => (!empty($data->l20_dtpulicacaoedital)) ? date('Y-m-d', strtotime(str_replace('/', '-', $data->l20_dtpulicacaoedital))) : null,
                'l20_linkedital' => $data->l20_linkedital,
                'l20_diariooficialdivulgacao' => $data->l20_diariooficialdivulgacao,
                'l20_dtpublic' => (!empty($data->l20_dtpublic)) ? date('Y-m-d', strtotime(str_replace('/', '-', $data->l20_dtpublic))) : null,
                'l20_dtpulicacaopncp' => (!empty($data->l20_dtpulicacaopncp)) ? date('Y-m-d', strtotime(str_replace('/', '-', $data->l20_dtpulicacaopncp))) : null,
                'l20_linkpncp' => $data->l20_linkpncp,
                'l20_datapublicacao1' => (!empty($data->l20_datapublicacao1)) ? date('Y-m-d', strtotime(str_replace('/', '-', $data->l20_datapublicacao1))) : null,
                'l20_nomeveiculo1' => $data->l20_nomeveiculo1,
                'l20_datapublicacao2' => (!empty($data->l20_datapublicacao2)) ? date('Y-m-d', strtotime(str_replace('/', '-', $data->l20_datapublicacao2))) : null,
                'l20_nomeveiculo2' => $data->l20_nomeveiculo2,
            ];

            if(empty($aData['l20_diariooficialdivulgacao'])){
                $aData['l20_diariooficialdivulgacao'] = 0;
            }

            if(!empty($oDispensa->l20_datacria)){
                if(!empty($aData['l20_datapublicacao1']) && $oDispensa->l20_datacria > $aData['l20_datapublicacao1']){
                    throw new Exception("A data da publicacao em Edital Veiculo 1 deve ser superior ou igual a data de criacao.", 1);
                }
                if(!empty($aData['l20_datapublicacao2']) && $oDispensa->l20_datacria > $aData['l20_datapublicacao2']){
                    throw new Exception("A data da publicacao em Edital Veiculo 2 deve ser superior ou igual a data de criacao.", 1);
                }
            }

            $oDispensa = $this->liclicitaRepository->update(
                $oDispensa->l20_codigo,
                $aData
            );

            if(!empty($data->respPubliccodigo)){
                $oResponseCGM = $this->insertLicComissaoCgmService->execute((object)[
                    'l31_numcgm'    => $data->respPubliccodigo,
                    'l31_tipo'      => 8,
                    'l31_licitacao' => $oDispensa->l20_codigo,
                ]);

                if($oResponseCGM['status'] != 200){
                    throw new \Exception($oResponseCGM['message'], 400);
                }
            } else {
                $this->deleteLicComissaoCgmService->execute((object)[
                    'l31_numcgm'     => $data->respPubliccodigo,
                    'l31_tipo'       => 8,
                    'l31_licitacao'  => $oDispensa->l20_codigo,
                    'l31_numcgm_odd' => $oDispensa->respPubliccodigo ?? null
                ]);
            }

            DB::commit();
            return [
                'status' => 200,
                'message' => 'Publicação alterada com sucesso!',
                'data' => []
            ];
        } catch(\Throwable $e){
            DB::rollBack();
            return [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }
}
