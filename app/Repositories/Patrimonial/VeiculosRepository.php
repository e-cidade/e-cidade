<?php

namespace App\Repositories\Patrimonial;

use App\Models\Veiculo;
use Illuminate\Support\Facades\DB;


class VeiculosRepository
{
    private Veiculo $model;

    public function __construct()
    {
        $this->model = new Veiculo();
    }

    public function

    getVeiculoByPlaca(string $placa, int $ve01_instit,array $campos = ['*']): ?Veiculo
    {
        return $this->model->where('ve01_placa', $placa)
            ->where('ve01_instit',$ve01_instit)
            ->first($campos);
    }

    public function getVeiculosByPlacaAndInstit(string $placa, int $ve01_instit,array $campos = ['*']): ?array{
        return $this->model->where('ve01_placa', $placa)
            ->where('ve01_instit',$ve01_instit)
            ->select($campos)
            ->get()
            ->toArray();
    }

    public function getVeiculoNotBaixa(array $codigoVeiculos, string $data){
        return $this->model
            ->leftJoin(
                'veicbaixa',
                'veicbaixa.ve04_veiculo',
                '=',
                'veiculos.ve01_codigo'
            )
            ->whereIn('veiculos.ve01_codigo', $codigoVeiculos)
            ->where(function($query) use ($data){
                $query->where('veicbaixa.ve04_data', '>=', DB::raw("'$data'"))
                ->orWhereNull('veicbaixa.ve04_data');
            })
            ->whereNull('veicbaixa.ve04_codigo')
            ->first('veiculos.*');
    }

}
