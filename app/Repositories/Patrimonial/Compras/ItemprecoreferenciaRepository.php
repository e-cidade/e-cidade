<?php

namespace App\Repositories\Patrimonial\Compras;

use App\Models\Patrimonial\Compras\Itemprecoreferencia;
use Illuminate\Support\Facades\DB;

class ItemprecoreferenciaRepository
{
    private Itemprecoreferencia $model;

    public function __construct()
    {
        $this->model = new Itemprecoreferencia();
    }

    public function insert(array $item): Itemprecoreferencia
    {
        $si02_sequencial = $this->model->getNextval();
        $item['si02_sequencial'] =  $si02_sequencial;
        return $this->model->create($item);
    }

    public function update(int $si02_sequencial, array $dados): bool
    {
        return DB::table('itemprecoreferencia')->where('si02_sequencial',$si02_sequencial)->update($dados);
    }

    public function excluir(int $si02_itemproccompra)
    {
        $sql = "DELETE FROM itemprecoreferencia WHERE si02_itemproccompra IN ($si02_itemproccompra)";
        return DB::statement($sql);
    }

    public function excluirItemPrecoReferencia(int $si02_precoreferencia):bool
    {
        $sql = "DELETE FROM itemprecoreferencia WHERE si02_precoreferencia = $si02_precoreferencia";
        return DB::statement($sql);
    }

    public function getPrecoMedio(int $si01_processocompra, int $pc01_codmater, bool $reservado): array
    {
        $clitemprecoreferencia = new \cl_itemprecoreferencia();
        $sql = $clitemprecoreferencia->getPrecomedio($si01_processocompra,$pc01_codmater,$reservado);
        return DB::select($sql);
    }

    public function getItensCota(int $si02_precoreferencia): array
    {
        $clitemprecoreferencia = new \cl_itemprecoreferencia();
        $sql = $clitemprecoreferencia->getItensCota($si02_precoreferencia);
        return DB::select($sql);
    }
}
