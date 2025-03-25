<?php
namespace App\Repositories\Patrimonial\PlanoContratacao;

use App\Models\Patrimonial\Compras\PcPlanoContratacao;
use App\Repositories\Contracts\Patrimonial\PlanoDeContratacao\PcPlanoContracaoRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PcPlanoContratacaoRepository implements PcPlanoContracaoRepositoryInterface{
    private PcPlanoContratacao $model;

    public  function __construct()
    {
        $this->model = new PcPlanoContratacao();
    }

    public function save(PcPlanoContratacao $pcPlanoContratacao): PcPlanoContratacao
    {
        $pcPlanoContratacao->save();
        return $pcPlanoContratacao;
    }

    public function getCodigo(): int
    {
        return $this->model->getNextval();
    }

    public function getByAnoUnidade(int $ano, int $unidade, int $limit = 15, int $offset = 0): array{
        $query = $this->model->query();
        $query->join(
            'db_config',
            'compras.pcplanocontratacao.mpc01_uncompradora',
            '=',
            'db_config.codigo'
        );

        if(!empty($ano)){
            $query->where('mpc01_ano', $ano);
        }
        if(!empty($unidade)){
            $query->where('mpc01_uncompradora', $unidade);
        }

        $query->select('compras.pcplanocontratacao.*', 'db_config.nomeinst');

        $total = $query->count();

        $query->limit($limit);
        $query->offset(($offset * $limit));

        return ['total' => $total, 'data' => $query->get()->toArray()];
    }

    public function getPlanoContratacaoByCodigo(int $mpc01_codigo): PcPlanoContratacao
    {
        return $this->model->findOrFail($mpc01_codigo);
    }

    public function getGrafico(int $mpc01_codigo){
        $sql = "
            SELECT
                SUM(pcpcitem.mpcpc01_qtdd) as qtd,
                SUM(pcpcitem.mpcpc01_vlrtotal) as total,
                pccat.mpc03_pcdesc
            FROM
                compras.pcplanocontratacao pc
                INNER JOIN compras.pcplanocontratacaopcpcitem pcpcitem ON pcpcitem.mpc01_pcplanocontratacao_codigo = pc.mpc01_codigo
                INNER JOIN compras.pcpcitem pcitem ON pcitem.mpc02_codigo = pcpcitem.mpc02_pcpcitem_codigo
                INNER JOIN compras.pccategoria pccat ON pccat.mpc03_codigo = pcitem.mpc02_categoria
            WHERE
                pc.mpc01_codigo = ".$mpc01_codigo."
            GROUP BY
                pccat.mpc03_pcdesc
        ";
        return DB::select($sql);
    }

    public function getValores($mpc01_codigo){
        $sql = "
            SELECT
                SUM(1) as qtdeItens,
                SUM(pcpcitem.mpcpc01_vlrtotal) as vlrEstimado
            FROM
                compras.pcplanocontratacao pc
                INNER JOIN compras.pcplanocontratacaopcpcitem pcpcitem ON pcpcitem.mpc01_pcplanocontratacao_codigo = pc.mpc01_codigo
                INNER JOIN compras.pcpcitem pcitem ON pcitem.mpc02_codigo = pcpcitem.mpc02_pcpcitem_codigo
                INNER JOIN compras.pccategoria pccat ON pccat.mpc03_codigo = pcitem.mpc02_categoria
            WHERE
                pc.mpc01_codigo = ".$mpc01_codigo."
            GROUP BY
                pc.mpc01_codigo
        ";
        return DB::select($sql);
    }

    public function update(int $mpc01_codigo, array $data): PcPlanoContratacao
    {
        $pcPlanoContratacao = $this->model->findOrFail($mpc01_codigo);
        $pcPlanoContratacao->update($data);

        return $pcPlanoContratacao;
    }

    public function deleteByCodigo(int $mpc01_codigo){
        $pcPlanoContratacao = $this->model->findOrFail($mpc01_codigo);
        $pcPlanoContratacao->delete();
    }

}
