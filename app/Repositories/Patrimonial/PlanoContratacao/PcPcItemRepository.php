<?php
namespace App\Repositories\Patrimonial\PlanoContratacao;

use App\Models\Patrimonial\Compras\PcpcItem;
use App\Repositories\Contracts\Patrimonial\PlanoDeContratacao\PcPcItemRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;

class PcPcItemRepository implements PcPcItemRepositoryInterface{
    private PcpcItem $model;

    public function __construct(){
        $this->model = new PcpcItem();
    }

    public function save(PcpcItem $pcPlanoContratacao): PcpcItem
    {
        $pcPlanoContratacao->save();
        return $pcPlanoContratacao;
    }

    public function getCodigo(): int
    {
        return $this->model->getNextval();
    }

    public function getByItens(int $mpc01_codigo, int $limit = 15, int $offset = 0): array
    {
        $query = $this->model->query();

        $this->getJoinsTable($query, $mpc01_codigo);

        $this->getSelectTable($query);

        $this->getGroupTable($query);

        $query->orderBy('compras.pcpcitem.mpc02_codigo', 'ASC');

        $total = $query->count();

        $query->limit($limit);
        $query->offset(($offset * $limit));

        $data = $query->get();

        return ['total' => $total, 'data' => $data->toArray()];
    }

    public function getByCodigo(int $mpc02_codigo): PcpcItem
    {
        return $this->model
            ->where('compras.pcpcitem.mpc02_codigo', $mpc02_codigo)
            ->get()
            ->first();
    }

    public function getByCodigoAndPlanoContratacao(int $mpc02_codigo, ?int $mpc01_codigo): PcpcItem
    {
        $query = $this->model->query();

        $this->getJoinsTable($query, $mpc01_codigo);

        $this->getSelectTable($query);

        $query->where('compras.pcpcitem.mpc02_codigo', $mpc02_codigo);

        return $query->get()->first();
    }

    private function getJoinsTable(&$query, $mpc01_codigo){
        $query->join(
            'compras.pcmater',
            'compras.pcpcitem.mpc02_codmater',
            '=',
            'compras.pcmater.pc01_codmater'
        );
        $query->leftJoin(
            'compras.pccategoria',
            'compras.pcpcitem.mpc02_categoria',
            '=',
            'compras.pccategoria.mpc03_codigo'
        );
        $query->leftJoin(
            'compras.pccatalogo',
            'compras.pcpcitem.mpc02_catalogo',
            '=',
            'compras.pccatalogo.mpc04_codigo'
        );
        $query->leftJoin(
            'compras.pctipoproduto',
            'compras.pcpcitem.mpc02_tproduto',
            '=',
            'compras.pctipoproduto.mpc05_codigo'
        );
        $query->leftJoin(
            'material.matunid',
            'compras.pcpcitem.mpc02_un',
            '=',
            'material.matunid.m61_codmatunid'
        );
        $query->leftJoin(
            'configuracoes.db_config',
            'compras.pcpcitem.mpc02_depto',
            '=',
            'configuracoes.db_config.codigo'
        );
        $query->leftJoin(
            'compras.pcsubgrupo',
            'compras.pcpcitem.mpc02_subgrupo',
            '=',
            'compras.pcsubgrupo.pc04_codsubgrupo'
        );
        $query->join(
            'compras.pcplanocontratacaopcpcitem',
            function($join) use ($mpc01_codigo){
                $join->on('compras.pcpcitem.mpc02_codigo', '=', 'compras.pcplanocontratacaopcpcitem.mpc02_pcpcitem_codigo')
                    ->on(DB::raw($mpc01_codigo), '=', 'compras.pcplanocontratacaopcpcitem.mpc01_pcplanocontratacao_codigo')
                ;
            }
        );
        $query->leftJoin(
            'compras.pcplanocontratacao',
            'compras.pcplanocontratacaopcpcitem.mpc01_pcplanocontratacao_codigo',
            '=',
            'compras.pcplanocontratacao.mpc01_codigo'
        );
    }

    private function getSelectTable(&$query){
        $query->select(
            'pcpcitem.mpc02_codigo',
            'pcmater.pc01_codmater AS mpc02_codmater',
            'pcplanocontratacaopcpcitem.mpcpc01_datap',
            'pccategoria.mpc03_codigo AS mpc02_categoria',
            'pcplanocontratacaopcpcitem.mpcpc01_qtdd AS mpc02_qtdd',
            'pcplanocontratacaopcpcitem.mpcpc01_vlrunit AS mpc02_vlrunit',
            'mpcpc01_vlrtotal AS mpc02_vlrtotal',
            'matunid.m61_codmatunid AS mpc02_un',
            DB::raw('COALESCE(pcpcitem.mpc02_depto, configuracoes.db_config.codigo) AS mpc02_depto'),
            'pccatalogo.mpc04_codigo AS mpc02_catalogo',
            'pctipoproduto.mpc05_codigo AS mpc02_tproduto',
            'pcsubgrupo.pc04_codsubgrupo AS mpc02_subgrupo',
            'mpc03_codigo',
            'mpc03_pcdesc',
            'mpc04_codigo',
            'mpc04_pcdesc',
            'mpc05_codigo',
            'pcmater.pc01_codmater AS pc01_codmater',
            DB::raw("pcmater.pc01_descrmater || '. ' || pcmater.pc01_complmater AS pc01_descrmater"),
            'configuracoes.db_config.codigo',
            'configuracoes.db_config.nomeinst',
            'm61_codmatunid',
            'm61_descr',
            'pc04_codsubgrupo',
            'pc04_descrsubgrupo',
            'pc01_servico',
            'mpcpc01_codigo',
            'mpcpc01_is_send_pncp'
        );
    }

    private function getGroupTable(&$query){
        $query->groupBy(

        );
    }

    public function update(int $mpc02_codigo, array $data): PcpcItem
    {
        $pcPcItem = $this->model->findOrFail($mpc02_codigo);
        $pcPcItem->update($data);

        return $pcPcItem;
    }

    public function delete(int $mpc02_codigo): void
    {
        $pcPcItem = $this->model->findOrFail($mpc02_codigo);
        $pcPcItem->delete();
    }

    public function getItensPlanoContratacao(int $exercicio, int $instit, int $mpc01_codigo, int $limit = 20, int $offset = 0) : ?array
    {
        $queryAssociados = $this->model->query();

        $this->getJoinsTable($queryAssociados, $mpc01_codigo);
        $this->getSelectTable($queryAssociados);

        $queryAssociados->orderBy('compras.pcpcitem.mpc02_codigo', 'ASC');

        // Segunda consulta
        $query = DB::table('liclicitem');

        $this->getJoinsItensPlanoContratacao($query, $instit, $mpc01_codigo);
        $this->getSelectItensPlanoContratacao($query);

        $query->where('liclicita.l20_licsituacao', 10)
              ->where('liclicita.l20_anousu', $exercicio)
              ->where('pcorcamjulg.pc24_pontuacao', 1);

        $query->whereNull('compras.pcplanocontratacaopcpcitem.mpcpc01_codigo');

        $this->getGroupByItensPlanoContratacao($query);

        // Crie subconsultas como strings SQL
        $subQuery1Sql = $queryAssociados->toSql();
        $subQuery1Bindings = $queryAssociados->getBindings();

        $subQuery2Sql = $query->toSql();
        $subQuery2Bindings = $query->getBindings();

        // Combine as subconsultas usando UNION ALL
        $combinedSql = "SELECT * FROM ({$subQuery1Sql}) as sub1 UNION SELECT * FROM ({$subQuery2Sql}) as sub2";

        // Crie uma nova consulta para a combinação
        $combinedQuery = DB::table(DB::raw("({$combinedSql}) as combined"))
            ->setBindings(array_merge($subQuery1Bindings, $subQuery2Bindings));

        // Obtenha o total
        $totalSql = "SELECT COUNT(*) as total FROM ({$combinedSql}) as combined";
        $totalBindings = array_merge($subQuery1Bindings, $subQuery2Bindings);
        $total = DB::select(DB::raw($totalSql), $totalBindings)[0]->total;

        // Ordene e limite o resultado
        $combinedQuery->orderBy('mpcpc01_codigo', 'asc');
        $combinedQuery->orderBy(DB::raw('COALESCE(mpcpc01_codigo, pc01_codmater)'), 'asc');

        $combinedQuery->limit($limit);
        $combinedQuery->offset(($offset * $limit));
        // echo '<pre>';
        // print_r($combinedQuery->toSql());
        // exit;
        $result = $combinedQuery->get();

        return $result->isEmpty() ? null : ['total' => $total, 'data' => $result->toArray()];
    }

    private function getSelectItensPlanoContratacao(&$query){
        $query->select(
            'pcpcitem.mpc02_codigo',
            'pcmater.pc01_codmater AS mpc02_codmater',
            'pcplanocontratacaopcpcitem.mpcpc01_datap',
            DB::raw("COALESCE(pcpcitem.mpc02_categoria, pccategoria.mpc03_codigo) AS mpc02_categoria"),
            DB::raw('SUM(pcorcamval.pc23_quant) AS mpc02_qtdd'),
            DB::raw('(SUM(pcorcamval.pc23_vlrun) / COUNT(*)) AS mpc02_vlrunit'),
            DB::raw('((SUM(pcorcamval.pc23_vlrun) / COUNT(*)) * SUM(pcorcamval.pc23_quant)) AS mpc02_vlrtotal'),
            'matunid.m61_codmatunid AS mpc02_un',
            DB::raw('COALESCE(pcpcitem.mpc02_depto, configuracoes.db_config.codigo) AS mpc02_depto'),
            DB::raw('COALESCE(pcpcitem.mpc02_catalogo, pccatalogo.mpc04_codigo) AS mpc02_catalogo'),
            DB::raw('COALESCE(pcpcitem.mpc02_tproduto, pctipoproduto.mpc05_codigo) AS mpc02_tproduto'),
            'pcsubgrupo.pc04_codsubgrupo AS mpc02_subgrupo',
            'mpc03_codigo',
            'mpc03_pcdesc',
            'mpc04_codigo',
            'mpc04_pcdesc',
            'mpc05_codigo',
            'pcmater.pc01_codmater AS pc01_codmater',
            DB::raw("pcmater.pc01_descrmater || '. ' || pcmater.pc01_complmater AS pc01_descrmater"),
            'configuracoes.db_config.codigo',
            'configuracoes.db_config.nomeinst',
            'm61_codmatunid',
            'm61_descr',
            'pc04_codsubgrupo',
            'pc04_descrsubgrupo',
            'pc01_servico',
            'mpcpc01_codigo',
            'pcplanocontratacaopcpcitem.mpcpc01_is_send_pncp',
        );
    }

    private function getJoinsItensPlanoContratacao(&$query, $instit, $mpc01_codigo){
        $query->join('liclicita', 'l20_codigo', '=', 'l21_codliclicita');
        $query->join('pcprocitem', 'pc81_codprocitem', '=', 'l21_codpcprocitem');
        $query->join('pcorcamitemlic', 'pc26_liclicitem', '=', 'l21_codigo');
        $query->join('solicitem', 'pc11_codigo', '=', 'pc81_solicitem');
        $query->join('solicitempcmater', 'pc16_solicitem', '=', 'pc11_codigo');
        $query->join('solicitemunid', 'pc17_codigo', '=', 'pc11_codigo');
        $query->join('matunid', 'm61_codmatunid', '=', 'pc17_unid');
        $query->join('pcmater', 'pc01_codmater', '=', 'pc16_codmater');
        $query->join('pcsubgrupo', 'pc04_codsubgrupo', '=', 'pc01_codsubgrupo');
        $query->join('pcorcamitem', function($join) {
            $join->on('pc22_orcamitem', '=', 'pc26_orcamitem')
                ->on('pc26_liclicitem', '=', 'l21_codigo');
        });
        $query->join('pcorcam', 'pc20_codorc', '=', 'pc22_codorc');
        $query->join('pcorcamforne', 'pc21_codorc', '=', 'pc20_codorc');
        $query->join('pcorcamval', function($join) {
            $join->on('pc23_orcamforne', '=', 'pc21_orcamforne')
                ->on('pc23_orcamitem', '=', 'pc22_orcamitem');
        });
        $query->join('pcorcamjulg', function($join) {
            $join->on('pc24_orcamforne', '=', 'pc21_orcamforne')
                ->on('pc24_orcamitem', '=', 'pc22_orcamitem');
        });
        $query->leftJoin('pcpcitem', function($join) {
            $join->on('pcpcitem.mpc02_codmater', '=', 'pcmater.pc01_codmater')
                ->on('pcpcitem.mpc02_un', '=', 'matunid.m61_codmatunid')
                ->on('pcpcitem.mpc02_subgrupo', '=', 'pcsubgrupo.pc04_codsubgrupo');
        });
        $query->leftJoin(
            'pccategoria',
            function($join){
                $join->on('pccategoria.mpc03_codigo', '=', 'pcpcitem.mpc02_categoria')
                    ->orOn(
                        DB::raw('(
                            CASE
                                WHEN l20_categoriaprocesso = 1 THEN 7
                                WHEN l20_categoriaprocesso = 2 THEN 1
                                WHEN l20_categoriaprocesso = 3 THEN 5
                                WHEN l20_categoriaprocesso = 4 THEN 1
                                WHEN l20_categoriaprocesso = 5 THEN 6
                                WHEN l20_categoriaprocesso = 6 THEN 8
                                WHEN l20_categoriaprocesso = 7 THEN 3
                                WHEN l20_categoriaprocesso = 8 THEN 2
                                WHEN l20_categoriaprocesso = 9 THEN 4
                                WHEN l20_categoriaprocesso = 10 THEN 2
                                WHEN l20_categoriaprocesso = 11 THEN 7
                                ELSE 1
                            END
                        )'),
                        '=',
                        'pccategoria.mpc03_codigo'
                    );
            }
        );
        $query->leftJoin(
            'pccatalogo',
            function($join){
                $join->on('pccatalogo.mpc04_codigo', '=', 'pcpcitem.mpc02_catalogo')
                    ->orOn(
                        DB::raw('2'),
                        '=',
                        'pccatalogo.mpc04_codigo'
                    );
            }
        );
        $query->leftJoin(
            'pctipoproduto',
            function($join){
                $join->on('pctipoproduto.mpc05_codigo', '=', 'pcpcitem.mpc02_tproduto')
                    ->orOn(
                        DB::raw('(
                            CASE
                                WHEN pc01_servico=false THEN 1
                                WHEN pc01_servico=true THEN 2
                            END
                        )'),
                        '=',
                        'pctipoproduto.mpc05_codigo'
                    );
            }
        );
        $query->leftJoin(
            'configuracoes.db_config',
            function ($join) use ($instit) {
                $join->on('compras.pcpcitem.mpc02_depto', '=', 'configuracoes.db_config.codigo')
                    ->orOn(
                        'configuracoes.db_config.codigo',
                        '=',
                        DB::raw($instit)
                    );
            }
        );

        $query->leftJoin(
            'compras.pcplanocontratacaopcpcitem',
            function($join) use ($mpc01_codigo){
                $join->on('compras.pcpcitem.mpc02_codigo', '=', 'compras.pcplanocontratacaopcpcitem.mpc02_pcpcitem_codigo')
                    ->on(DB::raw($mpc01_codigo), '=', 'compras.pcplanocontratacaopcpcitem.mpc01_pcplanocontratacao_codigo')
                ;
            }
        );
        $query->leftJoin(
            'compras.pcplanocontratacao',
            'compras.pcplanocontratacaopcpcitem.mpc01_pcplanocontratacao_codigo',
            '=',
            'compras.pcplanocontratacao.mpc01_codigo'
        );
    }

    private function getGroupByItensPlanoContratacao(&$query){
        $query->groupBy(
            'pcpcitem.mpc02_codigo',
            'pcmater.pc01_codmater',
            'pcpcitem.mpc02_categoria',
            'pcplanocontratacaopcpcitem.mpcpc01_datap',
            'matunid.m61_codmatunid',
            'pcpcitem.mpc02_depto',
            'pcpcitem.mpc02_catalogo',
            'pcpcitem.mpc02_tproduto',
            'pcsubgrupo.pc04_codsubgrupo',
            'pccategoria.mpc03_codigo',
            'pccategoria.mpc03_pcdesc',
            'pccatalogo.mpc04_codigo',
            'pccatalogo.mpc04_pcdesc',
            'pctipoproduto.mpc05_codigo',
            'pcmater.pc01_codmater',
            DB::raw("pcmater.pc01_descrmater || '. ' || pcmater.pc01_complmater"),
            'configuracoes.db_config.codigo',
            'configuracoes.db_config.nomeinst',
            'matunid.m61_codmatunid',
            'matunid.m61_descr',
            'pcsubgrupo.pc04_codsubgrupo',
            'pcsubgrupo.pc04_descrsubgrupo',
            'pc01_servico',
            'mpcpc01_codigo',
            'pcplanocontratacaopcpcitem.mpcpc01_is_send_pncp'
        );
    }

    public function pcPcItemGetByProperties(
        int $mpc02_codmater,
        int $mpc02_categoria,
        int $mpc02_un,
        int $mpc02_depto,
        int $mpc02_catalogo,
        int $mpc02_tproduto,
        int $mpc02_subgrupo
    ){
        return $this->model
            ->where('mpc02_codmater', $mpc02_codmater)
            ->where('mpc02_categoria', $mpc02_categoria)
            ->where('mpc02_un', $mpc02_un)
            ->where('mpc02_depto', $mpc02_depto)
            ->where('mpc02_catalogo', $mpc02_catalogo)
            ->where('mpc02_tproduto', $mpc02_tproduto)
            ->where('mpc02_subgrupo', $mpc02_subgrupo)
            ->get()
            ->first();
    }

}
