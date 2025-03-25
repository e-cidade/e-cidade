<?php

namespace App\Repositories\Contabilidade;

use App\Models\OrcDotacao;
use Illuminate\Support\Facades\DB;

class OrcdotacaoRepository
{
    private OrcDotacao $model;

    public function __construct()
    {
        $this->model = new OrcDotacao();
    }

    public function getDotacaoAnoDestino($ano,$codEle,$unidade,$orgao,$projAtiv,$codigo,$funcao,$subfuncao,$programa)
    {
        $sql = "
                SELECT o58_coddot
                    FROM orcdotacao
                    INNER JOIN orcelemento ON orcdotacao.o58_codele = orcelemento.o56_codele
                    AND orcdotacao.o58_anousu = orcelemento.o56_anousu
                    WHERE o58_anousu = $ano
                        AND o58_codele = $codEle
                        AND o58_unidade = $unidade
                        AND o58_orgao = $orgao
                        AND o58_projativ = $projAtiv
                        AND o58_codigo = $codigo
                        AND o58_funcao = $funcao
                        AND o58_subfuncao = $subfuncao
                        AND o58_programa = $programa
                ";
        $coddot = DB::select($sql);

        return $coddot[0]->o58_coddot;
    }

    public function getDotacaoAno($ano,$coddot)
    {
        $sql = "
                SELECT *
                    FROM orcdotacao
                    INNER JOIN orcelemento ON orcdotacao.o58_codele = orcelemento.o56_codele
                    AND orcdotacao.o58_anousu = orcelemento.o56_anousu
                    WHERE o58_anousu = $ano
                        AND o58_coddot = $coddot
                ";
        $coddot = DB::select($sql);

        return $coddot[0];
    }

}
