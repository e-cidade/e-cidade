<?php

namespace App\Repositories\Configuracao;

use App\Models\Configuracao\ProtProcesso;
use Illuminate\Database\Capsule\Manager as DB;

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

        return $this->model->findOrFail($p58_codproc);
    }
}
