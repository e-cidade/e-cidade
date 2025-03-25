<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\PccfLicitaPar;
use Illuminate\Support\Facades\DB;

class PccfLicitaParRepository{
    private PccfLicitaPar $model;

    public function __construct()
    {
        $this->model = new PccfLicitaPar();
    }

    public function getNumeracao(int $l25_anousu, int $l25_codcflicita){
        return $this->model->query()
            ->where('l25_anousu', $l25_anousu)
            ->where('l25_codcflicita', $l25_codcflicita)
            ->get()
            ->first();
    }

    public function getNumeracaoByNumero(int $l25_anousu, int $l25_numero, int $l25_codcflicita){
        return $this->model->query()
        ->where('l25_anousu', $l25_anousu)
        ->where('l25_codcflicita', $l25_codcflicita)
        ->where('l25_numero', $l25_numero)
        ->get()
        ->first();
    }

    public function getModalidadeByParam(int $l20_codtipocom, int $anousu, int $instit){
        return $this->model->query()
            ->join(
                'cflicita',
                'cflicita.l03_codigo',
                '=',
                'pccflicitapar.l25_codcflicita'
            )
            ->where('l25_codcflicita', $l20_codtipocom)
            ->where('l25_anousu', $anousu)
            ->where('l03_instit', $instit)
            ->get()
            ->first();
    }

    public function getModalidadeLicita(int $l20_codtipocom, int $l20_numero, int $anousu, int $instit){
        return $this->model->query()
            ->join(
                'liclicita',
                'pccflicitapar.l25_codcflicita',
                '=',
                'liclicita.l20_codtipocom'
            )
            ->where('l20_instit', $instit)
            ->where('l25_anousu', $anousu)
            ->where('l20_codtipocom', $l20_codtipocom)
            ->where('l20_numero', $l20_numero)
            ->where('l20_anousu', $anousu)
            ->get()
            ->first();
    }

    public function updateByInstitAnoUsu(int $instit, int $anousu, int $l20_codtipocom, array $data){
        $affectedRows = $this->model->query()
        ->where('l25_codcflicita', $l20_codtipocom)
        ->where('l25_anousu', $anousu)
            ->update($data);

        // Verifica se alguma linha foi afetada
        if ($affectedRows === 0) {
            throw new \Exception("PccfLicitaPar não encontrado", 500);
        }

        // Retorna os dados atualizados (se necessário, carregue novamente)
        return $this->model->query()
            ->where('l25_codcflicita', $l20_codtipocom)
            ->where('l25_anousu', $anousu)
            ->first();
    }

    public function updateByInstitAnoUsuNumero(int $anousu, int $l25_numero, int $l20_codtipocom, array $data){
        $affectedRows = $this->model->query()
        ->where('l25_codcflicita', $l20_codtipocom)
        ->where('l25_anousu', $anousu)
        ->where('l25_numero', $l25_numero)
        ->update($data);

        // Verifica se alguma linha foi afetada
        if ($affectedRows === 0) {
            throw new \Exception("PccfLicitaPar não encontrado", 500);
        }

        // Retorna os dados atualizados (se necessário, carregue novamente)
        return $this->model->query()
            ->where('l25_codcflicita', $l20_codtipocom)
            ->where('l25_anousu', $anousu)
            ->first();
    }

}
