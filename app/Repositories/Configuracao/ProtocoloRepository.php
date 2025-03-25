<?php

namespace App\Repositories\Configuracao;

use App\Models\Configuracao\ProtProcesso;
use Illuminate\Support\Facades\DB;

class ProtocoloRepository
{
    private ProtProcesso $model;

    public function __construct()
    {
        $this->model = new ProtProcesso();
    }

    public function getProtocoloByCod($p58_codproc){
        return $this->model->where('p58_codproc', $p58_codproc)->first();
    }

    public function update($p58_codproc, $data, $anousu){
        DB::statement("SELECT FC_PUTSESSION('DB_anousu','$anousu')");
        $oProtocolo = $this->model->findOrFail($p58_codproc);
        $oProtocolo->update($data);
    }

    public function update_obs($p58_codproc, $p58_obs, $anousu){
        DB::statement("SELECT FC_PUTSESSION('DB_anousu','$anousu')");
        DB::statement("
            UPDATE
                protocolo.protprocesso
            SET
                p58_obs = ". DB::raw("'".$p58_obs."'") ."
            WHERE
                p58_codproc = $p58_codproc
            ");
    }

    public function update_requer($p58_codproc, $p58_requer, $anousu){
        DB::statement("SELECT FC_PUTSESSION('DB_anousu','$anousu')");
        DB::statement("
            UPDATE
                protocolo.protprocesso
            SET
                p58_requer = ". DB::raw("'".$p58_requer."'") ."
            WHERE
                p58_codproc = $p58_codproc
        ");
    }

}
