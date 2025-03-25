<?php

namespace App\Repositories\Patrimonial;

use App\Models\AcordoPosicao;
use App\Repositories\Contracts\Patrimonial\AcordoPosicaoRepositoryInterface;


class AcordoPosicaoRepository implements AcordoPosicaoRepositoryInterface
{
    /**
     *
     * @var AcordoPosicao
     */
    private AcordoPosicao $model;

    public function __construct()
    {
        $this->model = new AcordoPosicao();
    }

    /**
     * Undocumented function
     *
     * @param integer $idAcordo
     * @param integer $numeroAditamento
     * @return AcordoPosicao|null
     */
    public function getAcordoPorNumeroAditamento(int $idAcordo, int $numeroAditamento): ?AcordoPosicao
    {
        return $this->model->where('ac26_acordo', $idAcordo)
            ->where('ac26_numeroaditamento', $numeroAditamento)
            ->first();
    }

    /**
     *
     * @param integer $ac26Acordo
     * @return AcordoPosicao
     */
    public function getAditamentoUltimaPosicao(int $ac26Acordo): AcordoPosicao
    {
        $acordoPosicao = $this->model
            ->with(['itens', 'posicaoAditamento', 'acordo'])
            ->where('ac26_acordo', $ac26Acordo)
            ->where('ac26_numeroaditamento','<>', '')
            ->orderBy('ac26_numero', 'desc')
            ->first();

        return $acordoPosicao;
    }
    /**
     *
     * @param integer $ac26Acordo
     * @return AcordoPosicao
     */
    public function getPosicaoInicial(int $ac26Acordo): AcordoPosicao
    {
        $acordoPosicao = $this->model
            ->with(['itens', 'posicaoAditamento', 'acordo'])
            ->where('ac26_acordo', $ac26Acordo)
            ->where('ac26_numero', 1)
            ->orderBy('ac26_numero', 'asc')
            ->first();

        return $acordoPosicao;
    }

    /**
     *
     * @param integer $ac26Acordo
     * @return AcordoPosicao
     */
    public function getAditamentoByNumero(int $ac26Acordo, int $numeroAditamento): AcordoPosicao
    {
        $acordoPosicao = $this->model
            ->with(['itens', 'posicaoAditamento', 'acordo'])
            ->where('ac26_acordo', $ac26Acordo)
            ->where('ac26_numeroaditamento', $numeroAditamento)
            ->orderBy('ac26_numero', 'desc')
            ->first();

        return $acordoPosicao;
    }

    /**
     *
     * @param integer $ac26Acordo
     * @return AcordoPosicao
     */
    public function getAditamentoPosicaoAnterior(int $ac26Acordo, int $sequencial): AcordoPosicao
    {
        $acordoPosicao = $this->model
            ->with(['itens', 'posicaoAditamento', 'acordo'])
            ->where('ac26_acordo', $ac26Acordo)
            ->whereNotNull('ac26_numeroaditamento')
            ->where('ac26_sequencial', '<', $sequencial)
            ->orderBy('ac26_sequencial', 'desc')
            ->first();
        return $acordoPosicao;
    }

    public function getAditamentoByAcordo(int $acordo)
    {
        return $this->model->where('ac26_acordo', $acordo)
        ->whereNotNull('ac26_numeroaditamento')
        ->get();

    }

    public function getQtdAditamentoPorAcordo(int $acordo): int
    {
        $aditamentos = $this->getAditamentoByAcordo($acordo);

        if (empty($aditamentos)) {
            return 0;
        }

        return count($aditamentos->toArray());
    }

    /**
     *
     * @param integer $codigo
     * @param array $dados
     * @return boolean
     */
    public function update(int $codigo, array $dados): bool
    {
        $acordoPosicao = $this->model->find($codigo);

        return $acordoPosicao->update($dados);
    }

    public function getUltimoIdApostilmentoByAcordo(int $acordo): ?int
    {
        $acordoPosicao = $this->model->where('ac26_acordo', $acordo)
            ->where('ac26_numeroapostilamento','<>', '')
            ->orderBy('ac26_numero', 'desc')
            ->first(['ac26_sequencial']);
        return $acordoPosicao->ac26_sequencial;
    }

    public function getAcordoPosicoes($ac16_sequencial){
        $query1 = $this->model
            ->join('acordoitem', 'ac20_acordoposicao', '=', 'ac26_sequencial')
            ->join('acordoitemexecutado', 'ac20_sequencial', '=', 'ac29_acordoitem')
            ->join('acordoitemexecutadoempautitem', 'ac29_sequencial', '=', 'ac19_acordoitemexecutado')
            ->join('empautitem', function ($join) {
                $join->on('e55_sequen', '=', 'ac19_sequen')
                    ->on('ac19_autori', '=', 'e55_autori');
            })
            ->join('empautoriza', 'e54_autori', '=', 'e55_autori')
            ->leftJoin('empempaut', 'e61_autori', '=', 'e54_autori')
            ->leftJoin('empempenho', 'e61_numemp', '=', 'e60_numemp')
            ->where('ac26_acordo', $ac16_sequencial)
            ->groupBy('e54_autori', 'e54_emiss', 'e60_codemp', 'e60_anousu', 'e60_numemp', 'e54_anulad')
            ->select('e54_autori', 'e60_numemp');

        $query2 = $this->model
            ->join('acordoitem', 'ac20_acordoposicao', '=', 'ac26_sequencial')
            ->join('acordoitemexecutado', 'ac20_sequencial', '=', 'ac29_acordoitem')
            ->join('acordoitemexecutadoperiodo', 'ac29_sequencial', '=', 'ac38_acordoitemexecutado')
            ->join('acordoitemexecutadoempenho', 'ac38_sequencial', '=', 'ac39_acordoitemexecutadoperiodo')
            ->join('empempenho', 'ac39_numemp', '=', 'e60_numemp')
            ->leftJoin('empempaut', 'e60_numemp', '=', 'e61_numemp')
            ->join('empautoriza', 'e54_autori', '=', 'e61_autori')
            ->where('ac26_acordo', $ac16_sequencial)
            ->distinct()
            ->select('e54_autori', 'e60_numemp');

        return $query1->union($query2)->orderBy('e54_autori')->get();
    }

}
