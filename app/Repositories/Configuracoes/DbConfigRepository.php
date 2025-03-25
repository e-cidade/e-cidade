<?php

namespace App\Repositories\Configuracoes;

use App\Models\Configuracoes\DbConfig;
use App\Repositories\Contracts\Configuracoes\DbConfigRepositoryInterface;
use Illuminate\Support\Facades\DB;

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


    public function getDadosFile($instit){
        return $this->model->select(
            DB::raw('db21_codigomunicipoestado AS codmunicipio'),
            DB::raw('cgc AS cnpjmunicipio'),
            DB::raw('si09_tipoinstit AS tipoorgao'),
            DB::raw('si09_codorgaotce AS codorgao')
        )
        ->leftJoin(
            'infocomplementaresinstit',
            'si09_instit',
            '=',
            DB::raw($instit)
        )
        ->where('codigo', $instit)
        ->get();
    }

    public function getDadosComplementaresByCodigo(int $iCodigo): ?DbConfig
    {
        return $this->model
            ->select(
                DB::raw('db21_codigomunicipoestado AS codmunicipio'),
                DB::raw("
                    CASE 
                        WHEN si09_tipoinstit::varchar != '2' THEN cgc::varchar 
                        ELSE si09_cnpjprefeitura::varchar 
                    END AS cnpjmunicipio
                "),            
                DB::raw('si09_tipoinstit AS tipoorgao'),
                DB::raw('si09_codorgaotce AS codorgao')
            )
            ->leftJoin(
                'public.infocomplementaresinstit',
                'si09_instit',
                '=',
                'codigo'
            )
            ->where('codigo', $iCodigo)
            ->first();
    }

}
