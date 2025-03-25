<?php

namespace App\Repositories\Patrimonial\Protocolo;

use App\Models\Cgm;
use App\Repositories\Contracts\Patrimonial\Protocolo\CgmRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CgmRepository implements CgmRepositoryInterface
{
    /**
     *
     * @var AcCgmordo
     */
    private Cgm $model;

    public function __construct()
    {
        $this->model = new Cgm();
    }

    /**
     *
     * @param integer $codigoCgm
     * @param array $fields
     * @return Cgm
     */
    public function getCgm(int $codigoCgm, $fields = ['*']): Cgm
    {
       return $this->model->where('z01_numcgm', $codigoCgm)->first($fields);
    }

    public function getDadosFile($instit, $l228_datainicial, $l228_datafim){
        return $this->model->select(
            'z01_numcgm',
            DB::raw('10 as tipoRegistro'),
            DB::raw('
                case
                    when LENGTH(regexp_replace(z01_cgccpf, \'[^0-9]\', \'\', \'g\')) > 11 then 2
                    else 1
                end as tipoDocumento
            '),
            DB::raw('z01_cgccpf as nroDocumento')
        )
        // ->join('historicocgm', 'z09_numcgm', '=', 'z01_numcgm')
        // ->whereBetween('z09_datacadastro', [$l228_datainicial, $l228_datafim])
        ->leftJoin(
            'historicocgmenviosicom',
            function($join) use ($instit) {
                $join->on('z18_cgm', '=', 'z01_numcgm')
                     ->where('z18_instit', $instit);
            }
        )
        ->where(function($query) {
            $query->whereNull('z18_sequencial')
                  ->orWhere('z18_statusenvio', false);
        })
        // ->limit(10)
        ->get();
    }

    public function updateByConditions($instit, $l228_datainicial, $l228_datafim){
        $ids = $this->model
            ->join('historicocgm', 'historicocgm.z09_numcgm', '=', 'cgm.z01_numcgm')
            ->whereBetween('historicocgm.z09_datacadastro', [$l228_datainicial, $l228_datafim])
            ->select('historicocgm.z09_sequencial')
            ->pluck('z09_sequencial');

        DB::table('historicocgm')
            ->whereIn('z09_sequencial', $ids)
            ->update(['z09_status' => true]);
        
    }



}
