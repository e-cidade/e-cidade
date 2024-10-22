<?php
namespace App\Repositories\Patrimonial\PlanoContratacao;

use App\Models\Patrimonial\Compras\PcPlanoContratacaoPcPcItem;
use App\Repositories\Contracts\Patrimonial\PlanoDeContratacao\PlanoContratacaoPcPcItemRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;

class PlanoContratacaoPcPcItemRepository implements PlanoContratacaoPcPcItemRepositoryInterface {
    private PcPlanoContratacaoPcPcItem $model;

    public function __construct() {
        $this->model = new PcPlanoContratacaoPcPcItem();
    }

    public function save(PcPlanoContratacaoPcPcItem $pcPlanoContratacaoPcPcItem): PcPlanoContratacaoPcPcItem
    {
        $pcPlanoContratacaoPcPcItem->save();
        return $pcPlanoContratacaoPcPcItem;
    }

    public function getCodigo(): int
    {
        return $this->model->getNextval();
    }

    public function find(int $mpc01_codigo, int $mpc02_codigo): ?PcPlanoContratacaoPcPcItem
    {
        return $this->model
            ->where('mpc01_pcplanocontratacao_codigo', $mpc01_codigo)
            ->where('mpc02_pcpcitem_codigo', $mpc02_codigo)
            ->first();
    }

    public function findByPlanoContratacao(int $mpc01_codigo): ?array
    {
        return $this->model->where('mpc01_pcplanocontratacao_codigo', $mpc01_codigo)->get()->toArray();
    }

    public function delete(PcPlanoContratacaoPcPcItem $pcPlanoContratacaoPcPcItem): void
    {
        $pcPlanoContratacaoPcPcItem->delete();
    }

    /**
     * @param int $mpc01_codigo
     * @return int|null
     */
    public function getItemsCreatePlanoTotal(int $mpc01_codigo): ?int{
        $query = Db::table('compras.pcplanocontratacaopcpcitem');
        $this->getFromItems($query);
        $this->getSelectItems($query);

        $query->where('compras.pcplanocontratacaopcpcitem.mpc01_pcplanocontratacao_codigo', $mpc01_codigo);

        $this->getGroupBy($query);

        $result = $query->get();

        return $result->isEmpty() ? null : $result->count();
    }

    public function getItemsCreatePlano(int $mpc01_codigo): ?array
    {
        $query = Db::table('compras.pcplanocontratacaopcpcitem');
        $this->getFromItems($query);
        $this->getSelectItems($query);

        $query->where('compras.pcplanocontratacaopcpcitem.mpc01_pcplanocontratacao_codigo', $mpc01_codigo);

        $query->where('compras.pcplanocontratacaopcpcitem.mpcpc01_is_send_pncp', '!=', 1);

        $this->getGroupBy($query);

        $query->limit(200);

        $result = $query->get();

        return $result->isEmpty() ? null : $result->toArray();
    }

    public function getItemRetifica(array $codigos): ?array
    {
        $query = Db::table('compras.pcplanocontratacaopcpcitem');
        $this->getFromItems($query);
        $this->getSelectItems($query);

        $query->whereIn('mpcpc01_codigo', $codigos);

        $this->getGroupBy($query);

        $result = $query->get();

        return $result->isEmpty() ? null : $result->toArray();
    }

    private function getSelectItems(&$query){
        $query->select(
            'mpcpc01_numero_item',
            'mpcpc01_codigo',
            'mpc02_codigo AS numeroitem',
            'mpc03_codigo AS categoriaitempca',
            DB::raw('pc01_descrmater || \'. \' || pc01_complmater AS descricao'),
            'm61_descr AS unidadefornecimento',
            'mpcpc01_qtdd AS quantidade',
            'mpcpc01_vlrunit AS valorunitario',
            'mpcpc01_vlrtotal AS valortotal',
            DB::raw('0 AS valororcamentoexercicio'),
            DB::raw('NULL AS unidaderequisitante'),
            'mpcpc01_datap AS datadesejada',
            DB::raw('NULL AS grupocontratacaocodigo'),
            DB::raw('NULL AS grupocontratacaonome'),
            'mpc04_codigo AS catalogo',
            'mpc05_codigo AS classificacaocatalogo',
            'pc01_codmater AS codigoitem',
            'pc04_codsubgrupo AS classificacaosuperiorcodigo',
            'pc04_descrsubgrupo AS classificacaosuperiornome',
            DB::raw('NULL AS pdmcodigo'),
            DB::raw('NULL AS pdmdescricao')
        );
    }

    private function getFromItems(&$query){
        $query->join(
            'compras.pcpcitem',
            'compras.pcplanocontratacaopcpcitem.mpc02_pcpcitem_codigo',
            '=',
            'compras.pcpcitem.mpc02_codigo'
        );
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
    }

    private function getGroupBy(&$query){
        $query->groupBy(
            'mpcpc01_numero_item',
            'mpcpc01_codigo',
            'mpc02_codigo',
            'mpc03_codigo',
            'pc01_descrmater',
            'pc01_complmater',
            'm61_descr',
            'mpcpc01_vlrunit',
            'mpcpc01_vlrtotal',
            'mpcpc01_datap',
            'mpc04_codigo',
            'mpc05_codigo',
            'pc01_codmater',
            'pc04_codsubgrupo',
            'pc04_descrsubgrupo'
        );
    }

    public function generateUniqueInt32() {
        $timestamp = time();

        // Gera um identificador único com base no microtime e no processo atual
        $uniqueId = getmypid() . uniqid('', true);

        $hash = crc32($uniqueId);

        $uniqueInt32 = ($timestamp << 16) | ($hash & 0xFFFF);

        $uniqueInt32 = $uniqueInt32 & 0x7FFFFFFF;

        return $uniqueInt32;
    }

    public function update(int $mpcpc01_codigo, array $data): PcPlanoContratacaoPcPcItem
    {
        $pcPcItem = $this->model->findOrFail($mpcpc01_codigo);
        $pcPcItem->update($data);

        return $pcPcItem;
    }

    public function removeByCodigo(int $mpcpc01_codigo): void
    {
        $pcPcItem = $this->model->findOrFail($mpcpc01_codigo);
        $pcPcItem->delete();
    }

    public function getSendByPlanoContracao(int $mpc01_codigo): ?array
    {
        return $this->model
        ->where('mpc01_pcplanocontratacao_codigo', $mpc01_codigo)
        ->where('mpcpc01_is_send_pncp', 1)
        ->get()
        ->toArray();
    }
    public function getSendByPlanoContracaoAndItens(int $mpc01_codigo, array $itens): ?array
    {
        return $this->model
            ->where('mpc01_pcplanocontratacao_codigo', $mpc01_codigo)
            ->where('mpcpc01_is_send_pncp', 1)
            ->whereIn('mpc02_pcpcitem_codigo', $itens)
            ->get()
            ->toArray();
    }

    public function getByPlanoContratacaoAndItens(int $mpc01_codigo, array $itens): ?array
    {
        return $this->model
            ->where('mpc01_pcplanocontratacao_codigo', $mpc01_codigo)
            ->whereIn('mpc02_pcpcitem_codigo', $itens)
            ->get()
            ->toArray();
    }

    public function getItemPlanoContratacao(int $mpc02_codigo, int $mpc01_codigo): Object
    {
        return $this->model
            ->select(
                'compras.pcpcitem.*',
                'compras.pccategoria.*',
                'compras.pccatalogo.*',
                'compras.pctipoproduto.*',
                'compras.pcplanocontratacaopcpcitem.*',
                'compras.pcplanocontratacao.mpc01_codigo',
                'compras.pcmater.pc01_codmater',
                'compras.pcmater.pc01_descrmater',
                'configuracoes.db_config.codigo',
                'configuracoes.db_config.nomeinst',
                'material.matunid.m61_codmatunid',
                'material.matunid.m61_descr',
                'compras.pcsubgrupo.pc04_codsubgrupo',
                'compras.pcsubgrupo.pc04_descrsubgrupo',
            )
            ->join(
                'compras.pcpcitem',
                'compras.pcplanocontratacaopcpcitem.mpc02_pcpcitem_codigo',
                '=',
                'compras.pcpcitem.mpc02_codigo'
            )
            ->join(
                'compras.pcplanocontratacao',
                'compras.pcplanocontratacaopcpcitem.mpc01_pcplanocontratacao_codigo',
                '=',
                'compras.pcplanocontratacao.mpc01_codigo'
            )
            ->join(
                'compras.pcmater',
                'compras.pcpcitem.mpc02_codmater',
                '=',
                'compras.pcmater.pc01_codmater'
            )
            ->leftJoin(
                'compras.pccategoria',
                'compras.pcpcitem.mpc02_categoria',
                '=',
                'compras.pccategoria.mpc03_codigo'
            )
            ->leftJoin(
                'compras.pccatalogo',
                'compras.pcpcitem.mpc02_catalogo',
                '=',
                'compras.pccatalogo.mpc04_codigo'
            )
            ->leftJoin(
                'compras.pctipoproduto',
                'compras.pcpcitem.mpc02_tproduto',
                '=',
                'compras.pctipoproduto.mpc05_codigo'
            )
            ->leftJoin(
                'material.matunid',
                'compras.pcpcitem.mpc02_un',
                '=',
                'material.matunid.m61_codmatunid'
            )
            ->leftJoin(
                'configuracoes.db_config',
                'compras.pcpcitem.mpc02_depto',
                '=',
                'configuracoes.db_config.codigo'
            )
            ->leftJoin(
                'compras.pcsubgrupo',
                'compras.pcpcitem.mpc02_subgrupo',
                '=',
                'compras.pcsubgrupo.pc04_codsubgrupo'
            )
            ->where('mpc01_pcplanocontratacao_codigo', $mpc01_codigo)
            ->where('mpc02_pcpcitem_codigo', $mpc02_codigo)
            ->get()
            ->first();
    }

    public function getByCodigo(int $mpcpc01_codigo): Object
    {
        return $this->model
            ->where('mpcpc01_codigo', $mpcpc01_codigo)
            ->get()
            ->first();
    }

    public function pcPcItemGetByCodMaterAndUnit(
        int $mpc02_codmater,
        int $mpc02_un,
        int $mpc01_pcplanocontratacao_codigo
    ): ?PcPlanoContratacaoPcPcItem{
        return $this->model
            ->join(
                'compras.pcpcitem',
                'compras.pcplanocontratacaopcpcitem.mpc02_pcpcitem_codigo',
                '=',
                'compras.pcpcitem.mpc02_codigo'
            )
            ->where('mpc02_codmater', $mpc02_codmater)
            ->where('mpc01_pcplanocontratacao_codigo', $mpc01_pcplanocontratacao_codigo)
            ->where('mpc02_un', $mpc02_un)
            ->get()
            ->first();
    }

}
