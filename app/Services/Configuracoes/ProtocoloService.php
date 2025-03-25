<?php
namespace App\Services\Configuracoes;

use App\Support\String\StringHelper;
use App\Repositories\Configuracao\ProtocoloRepository;
use App\Repositories\Patrimonial\Licitacao\LiclicitaRepository;
use Illuminate\Support\Facades\DB;

class ProtocoloService{
    private ProtocoloRepository $protocoloRepository;

    public function __construct()
    {
        $this->protocoloRepository = new ProtocoloRepository();
    }

    public function atualizarProtocolo(object $data){
        DB::beginTransaction();
        try{
            $oProtocolo = $this->protocoloRepository->getProtocoloByCod($data->p58_codproc);
            if(empty($oProtocolo)){
                throw new \Exception("Protocolo no encontrado", 1);
            }

            $aData = array_replace(
                $oProtocolo->toArray(),
                [
                    'p58_dtproc'       => !empty($data->p58_dtproc) ? date('Y-m-d', strtotime(str_replace('/', '-', $data->p58_dtproc))) : null,
                    'p58_coddepto'     => $data->p58_coddepto,
                    'p58_codigo'       => $data->p58_codigo,
                    'p58_numcgm'       => $data->p58_numcgm,
                    'p58_requer'       => $data->p58_requer,
                    'p58_obs'          => $data->p58_obs
                ]
            );

            $p58_obs = StringHelper::convertToUtf8($aData['p58_obs']);
            $p58_requer = StringHelper::convertToUtf8($aData['p58_requer']);
            unset($aData['p58_requer']);
            unset($aData['p58_obs']);

            $this->protocoloRepository->update(
                $oProtocolo->p58_codproc,
                $aData,
                $data->anousu
            );

            $this->protocoloRepository->update_obs($oProtocolo->p58_codproc, $p58_obs, $data->anousu);
            $this->protocoloRepository->update_requer($oProtocolo->p58_codproc, $p58_requer, $data->anousu);

            DB::commit();
            return [
                'status' => 200,
                'message' => 'Protocolo alterada com sucesso!',
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
