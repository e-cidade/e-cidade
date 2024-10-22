<?php

namespace App\Repositories\Configuracoes;

use App\Models\Configuracoes\DbConfig;
use App\Repositories\Contracts\Configuracoes\DbConfigRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;

class DbConfigRepository implements DbConfigRepositoryInterface
{
    private DbConfig $model;

    public function __construct()
    {
        $this->model = new DbConfig();
    }

    public function getDados(int $id_usuario){
        $result = $this->model
                ->orderby('codigo', 'asc')
                ->whereIn('codigo', DB::table('db_userinst')->select('id_instit')->where('id_usuario', $id_usuario))
                ->get()
                ->toArray();

        return $result;
    }

    /**
     * Busca os dados pelo código da institução
     *
     * @param integer $iCodigo
     * @return DbConfig
     */
    public function getByCodigo(int $iCodigo): ?DbConfig
    {
        return $this->model->where('codigo', $iCodigo)->first();
    }
}
