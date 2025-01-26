<?php

namespace App\Repositories\Patrimonial\Compras;

use App\Models\Patrimonial\Compras\Solicitem;
use Illuminate\Database\Capsule\Manager as DB;
use cl_solicitem;
class SolicitemRepository
{
    private Solicitem $model;

    public function __construct()
    {
        $this->model = new Solicitem();
    }
    public function update(int $pc11_codigo, array $dados): bool
    {
        return DB::table('solicitem')->where('pc11_codigo',$pc11_codigo)->update($dados);
    }

    public function insert($dados): Solicitem
    {
        $pc11_codigo = $this->model->getNextval();
        $dados['pc11_codigo'] =  $pc11_codigo;
        return $this->model->create($dados);
    }

    public function NextSeqItemSolicitem($pc11_numero)
    {
        $clsolicitem = new cl_solicitem();
        $sql = $clsolicitem->getProximaSequenciaItem($pc11_numero);
        $pc11_seq = DB::select($sql);
        return $pc11_seq[0]->pc11_seq;
    }

    public function getItemCotaSolicitem(int $pc01_codmater, int $pc80_codproc)
    {
        $clsolicitem = new cl_solicitem();
        $sql = $clsolicitem->getItensCotaSolicitem($pc01_codmater,$pc80_codproc);
        return DB::select($sql);
    }

    public function excluir($pc11_codigo)
    {
        $sql = "DELETE FROM solicitem WHERE pc11_codigo IN ($pc11_codigo)";
        return DB::statement($sql);
    }

    public function getItens(string $where): object
    {
        $sql = "SELECT *
                    FROM solicitem
                    INNER JOIN solicitempcmater ON pc16_solicitem=pc11_codigo
                    INNER JOIN solicita ON pc11_numero = pc10_numero
                    WHERE $where";
        $item = DB::select($sql);
        return $item[0];
    }

    public function getAllItens(string $campos,string $where): array
    {
        $sql = "SELECT $campos
                    FROM solicitem
                    INNER JOIN solicitempcmater ON pc16_solicitem=pc11_codigo
                    INNER JOIN solicita ON pc11_numero = pc10_numero
                    WHERE $where";
        return DB::select($sql);
    }

}
