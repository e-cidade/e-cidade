<?php
namespace App\Repositories\Patrimonial\Compras;

use App\Models\Patrimonial\Compras\PcDotac;
use App\Repositories\Contracts\Patrimonial\Compras\PcDotacRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;

class PcDotacRepository implements PcDotacRepositoryInterface{
    private PcDotac $model;

    public function __construct()
    {
        $this->model = new PcDotac();
    }

    public function getCodigo(): int
    {
        return $this->model->getNextval();
    }

    public function save(PcDotac $oPcDotacData): ?PcDotac{
        $oPcDotacData->save();
        return $oPcDotacData;
    }

    public function updateAllByCodItem(int $pc13_codigo, float $pc13_quant, float $pc13_valor){
        return $this->model
            ->where('pc13_codigo', $pc13_codigo)
            ->update([
                'pc13_quant' => $pc13_quant,
                'pc13_valor' => $pc13_valor
            ]);
    }

    public function getDotacoesProcItens(int $l20_codigo, string $anousu, int $limit = 15, int $offset = 0, ?bool $isPaginate = true):?array
    {
        $query = $this->model->query();

        $query->select(
            DB::raw('distinct o58_coddot'),
            DB::raw('fc_estruturaldotacao(o58_anousu,o58_coddot) as o50_estrutdespesa'),
            'o56_elemento'
        );

        $query->join(
            'orcdotacao',
            'o58_coddot',
            '=',
            'pc13_coddot'
        );

        $query->join(
            'orcelemento',
            function($join){
                $join->on('o56_codele', '=', 'o58_codele')
                    ->on('o56_anousu', '=', 'o58_anousu');
            }
        );

        $query->where('o58_anousu', '=', DB::raw("'$anousu'"));

        $query->whereIn('pc13_codigo', function ($query) use ($l20_codigo) {
            $query->select('pc11_codigo')
                ->from('pcprocitem')
                ->join('solicitem', 'pc81_solicitem', '=', 'pc11_codigo')
                ->whereIn('pc81_codprocitem', function ($subquery) use ($l20_codigo) {
                    $subquery->select('l21_codpcprocitem')
                             ->from('liclicitem')
                             ->where('l21_codliclicita', $l20_codigo);
                });
        });

        $total = $query->count();

        if($isPaginate){
            $query->limit($limit);
            $query->offset(($offset * $limit));
        }

        $data = $query->get()->toArray();

        return ['total' => $total, 'data' => $data];
    }

    public function getPcDotacByCodAndCodDot(int $pc13_codigo, int $pc13_coddot){
        return $this->model
            ->where('pc13_codigo', $pc13_codigo)
            ->where('pc13_coddot', $pc13_coddot)
            ->first();
    }

    public function getPcDotacBySequencial($pc13_sequencial){
        return $this->model
            ->where('pc13_sequencial', $pc13_sequencial)
            ->first();
    }

    public function deletePcDotac(PcDotac $oPcDotac){
        return $oPcDotac->delete();
    }

    public function update(int $pc13_sequencial, array $dados): bool
    {
        return \Illuminate\Support\Facades\DB::table('pcdotac')->where('pc13_sequencial',$pc13_sequencial)->update($dados);
    }

    public function insert($dados): Pcdotac
    {
        $pc13_sequencial = $this->model->getNextval();
        $dados['pc13_sequencial'] =  $pc13_sequencial;
        return $this->model->create($dados);
    }

    public function getDotacoesItem(int $pc11_codigo)
    {
        $sql = "select * from pcdotac where pc13_codigo = $pc11_codigo";
        return DB::select($sql);
    }
    public function excluir(int $pc11_codigo): bool
    {
        $sql = "DELETE FROM pcdotac WHERE pc13_codigo IN ($pc11_codigo)";
        return DB::statement($sql);
    }
}
