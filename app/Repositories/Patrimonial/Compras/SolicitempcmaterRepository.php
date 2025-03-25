<?php

namespace App\Repositories\Patrimonial\Compras;

use App\Models\Patrimonial\Compras\Solicitempcmater;
use Illuminate\Support\Facades\DB;

class SolicitempcmaterRepository
{
    private Solicitempcmater $model;

    public function __construct()
    {
        $this->model = new Solicitempcmater();
    }

    public function insert($dados): bool
    {
        $pc16_codmater = $dados['pc16_codmater'];
        $pc16_solicitem = $dados['pc16_solicitem'];
        $sql = "insert into solicitempcmater values($pc16_codmater,$pc16_solicitem)";
        return DB::statement($sql);
    }

    public function excluir($pc16_solicitem)
    {
        $sql = "DELETE FROM solicitempcmater WHERE pc16_solicitem IN ($pc16_solicitem)";
        return DB::statement($sql);
    }

    public function getDadosByCodigoItem(int $pc16_solicitem):?object
    {
        return $this->model
            ->join(
                'pcmater',
                'pc16_codmater',
                '=',
                'pc01_codmater'
            )
            ->where('pc16_solicitem', $pc16_solicitem)
            ->first();
    }
}
