<?php
namespace App\Repositories\Patrimonial\Compras;

use App\Models\PcSubGrupo;
use App\Repositories\Contracts\Patrimonial\Compras\PcMaterRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;

class PcSubGrupoRepository implements PcMaterRepositoryInterface{
    private PcSubGrupo $model;

    public function __construct()
    {
        $this->model = new PcSubGrupo();
    }

    public function search(?string $search, int $anousu)
    {
        $result = $this->model
            ->select(
                'pc04_codsubgrupo as cod',
                DB::raw('pc04_descrsubgrupo as label')
            )
            ->join(
                'pcgrupo',
                'pcgrupo.pc03_codgrupo',
                '=',
                'pcsubgrupo.pc04_codgrupo'
            )
            ->join(
                'pctipo',
                'pctipo.pc05_codtipo',
                '=',
                'pcsubgrupo.pc04_codtipo'
            )
            ->whereRaw('pc04_descrsubgrupo ILIKE ?', [$search.'%'])
            ->get();

        return $result->toArray();
    }

    public function getSubgrupo($pc04_codsubgrupo)
    {
        $sql = "select * from pcsubgrupo where pc04_codsubgrupo = $pc04_codsubgrupo and pc04_instit in (0,".db_getsession('DB_instit').")";
        return DB::select($sql);
    }

    public function salvarSubgrupos(array $subGrupo):PcSubGrupo
    {
        return $this->model->create($subGrupo);
    }

}
