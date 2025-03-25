<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\PccfLicitaNum;
use cl_liclicita;
use Illuminate\Support\Facades\DB;

class PccfLicitaNumRepository {
    private PccfLicitaNum $model;

    public function __construct()
    {
        $this->model = new PccfLicitaNum();
    }

    public function getEdital(int $l24_instit, int $l24_anousu){
        return $this->model->query()
            ->where('l24_instit', $l24_instit)
            ->where('l24_anousu', $l24_anousu)
            ->get()
            ->first();
    }

    public function getEditalByNumero(int $l24_instit, int $l24_anousu, int $l24_numero){
        return $this->model->query()
            ->where('l24_instit', $l24_instit)
            ->where('l24_anousu', $l24_anousu)
            ->where('l24_numero', $l24_numero)
            ->get()
            ->first();
    }

    public function getEditalLicita(int $instit, int $anousu, int $l20_edital){
        return $this->model->query()
            ->join(
                'liclicita',
                'liclicita.l20_edital',
                '=',
                'pccflicitanum.l24_numero'
            )
            ->join(
                'pccflicitapar',
                'pccflicitapar.l25_codcflicita',
                '=',
                'liclicita.l20_codtipocom'
            )
            ->where('l20_instit', $instit)
            ->where('l25_anousu', $anousu)
            ->where('l20_edital', $l20_edital)
            ->where('l20_anousu', $anousu)
            ->get()
            ->first();
    }

    public function updateByInstitAnoUsu(int $instit, int $anousu, array $data){
        $affectedRows = $this->model->query()
            ->where('l24_instit', $instit)
            ->where('l24_anousu', $anousu)
            ->update($data);

        // Verifica se alguma linha foi afetada
        if ($affectedRows === 0) {
            throw new \Exception("PccfLicitaNum não encontrado", 500);
        }

        // Retorna os dados atualizados (se necessário, carregue novamente)
        return $this->model->query()
            ->where('l24_instit', $instit)
            ->where('l24_anousu', $anousu)
            ->first();
    }

    public function updateByInstitAnoUsuEdital(int $instit, int $anousu, int $l24_numero, array $data){
        $affectedRows = $this->model->query()
            ->where('l24_instit', $instit)
            ->where('l24_anousu', $anousu)
            ->where('l24_numero', $l24_numero)
            ->update($data);

        // Verifica se alguma linha foi afetada
        if ($affectedRows === 0) {
            throw new \Exception("PccfLicitaNum não encontrado", 500);
        }

        // Retorna os dados atualizados (se necessário, carregue novamente)
        return $this->model->query()
            ->where('l24_instit', $instit)
            ->where('l24_anousu', $anousu)
            ->first();
    }
}
