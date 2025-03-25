<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\Liclicitemlote;
use Illuminate\Support\Facades\DB;

class LiclicitemLoteRepository
{
    private Liclicitemlote $model;

    public function __construct()
    {
        $this->model = new Liclicitemlote();
    }

    /**
     * Retorna todos os registros da tabela Liclicitemlote.
     * 
     * @return \Illuminate\Database\Eloquent\Collection Retorna todos os registros encontrados.
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Encontra registros no banco de dados com base em uma coluna e condição fornecidas.
     *
     * @param string $column Nome da coluna para aplicar a condição de pesquisa.
     * @param mixed $conditions Valor da condição para a busca.
     * @return Liclicitemlote
     */
    public function find($column, $conditions, $relations = null)
    {
        $query = $this->model->where($column, $conditions);
        
        if ($relations) {
            $query->with($relations);
        }

        $query->get();
    }

    /**
     * Encontra um registro pelo ID ou gera uma exceção se não encontrado.
     * 
     * @param int $id Identificador único do registro.
     * @return Liclicitemlote Retorna o registro encontrado.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Se o registro não for encontrado.
     */
    public function findId($id)
    {
        return $this->model->findOrFail($id);
    }

    public function insert(array $dados): ?Liclicitemlote
    {
        $l04_codigo = $this->model->getNextval();
        $dados['l04_codigo'] = $l04_codigo;
        return $this->model->create($dados);
    }

    public function delete($l04_liclicitem)
    {
        $sql = "DELETE FROM liclicitemlote WHERE l04_liclicitem = $l04_liclicitem";
        return DB::statement($sql);
    }

    public function getDataLotsSelected($codigoNumerolote)
    {
        return $this->model->where('l04_numerolote', $codigoNumerolote)->first();
    }

    public function getOrcamItemByLoteNumber($codigoNumerolote)
    {
        return $this->model->select('licitacao.liclicitemlote.l04_numerolote', 'compras.pcorcamitem.pc22_orcamitem')
            ->join('licitacao.liclicitem', 'licitacao.liclicitem.l21_codigo', '=', 'licitacao.liclicitemlote.l04_liclicitem')
            ->join('licitacao.pcorcamitemlic', 'licitacao.pcorcamitemlic.pc26_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')
            ->join('compras.pcorcamitem', 'compras.pcorcamitem.pc22_orcamitem', '=', 'licitacao.pcorcamitemlic.pc26_orcamitem')
            ->where('licitacao.liclicitemlote.l04_numerolote', $codigoNumerolote)
            ->get();
    }

    public function getItemByCodigo(int $l04_codigo){
        return $this->model->where('l04_codigo', $l04_codigo)->first();
    }

    public function getNumeroLote($l24_codigo){
        return $this->model
            ->select('l04_numerolote')
            ->where('l04_codlilicitalote', $l24_codigo)
            ->first();
        ;
    }

    public function getNumeroByDescricao($l04_descricao){
        return $this->model
            ->select('l04_numerolote')
            ->where('l04_descricao', $l04_descricao)
            ->first();
        ;
    }

    public function deleteItem(Liclicitemlote $itemLote){
        return $itemLote->delete();
    }

    public function update(Liclicitemlote $itemLote, array $data){
        $itemLote->update($data);
        return $itemLote;
    }

    public function getItemByDescricao(int $l24_codigo){
        return $this->model
            ->where('l04_codlilicitalote', $l24_codigo)
            ->get()
            ->toArray();
    }

    public function getCodigo(): int
    {
        return $this->model->getNextval();
    }

    public function getCodigoLote(): int
    {
        return DB::select("SELECT nextval('liclicitemlote_l04_numerolote_seq') as nextval")[0]->nextval;
    }

    public function getItemByLote(int $l04_codlilicitalote){
        return $this->model->where('l04_codlilicitalote', $l04_codlilicitalote)->get()->toArray();
    }

    public function updateSeq(int $l04_codigo, int $seq){
        return $this->model->where('l04_codigo', $l04_codigo)->update(['l04_seq' => $seq]);
    }

    public function updateSeqAndNumeroLote(int $l04_codigo, int $seq, int $oNumeroLote){
        return $this->model->where('l04_codigo', $l04_codigo)->update(['l04_seq' => $seq, 'l04_numerolote' => $oNumeroLote]);
    }

    public function getItensByLiclicitam(int $l20_codigo){
        $query = $this->model->query();

        $query->select(
            'l04_codigo',
            'l04_liclicitem',
            'l04_descricao',
            'l04_seq',
            'l04_numerolote',
            'l04_codlilicitalote',
            'pc11_seq'
        );

        $query->leftJoin(
            'liclicitem',
            'liclicitemlote.l04_liclicitem',
            '=',
            'liclicitem.l21_codigo'
        );

        $query->join(
            'liclicita',
            'liclicita.l20_codigo',
            '=',
            'liclicitem.l21_codliclicita'
        );
        $query->join(
            'pcprocitem',
            'liclicitem.l21_codpcprocitem',
            '=',
            'pcprocitem.pc81_codprocitem'
        );
        $query->join(
            'pcproc',
            'pcproc.pc80_codproc',
            '=',
            'pcprocitem.pc81_codproc'
        );
        $query->join(
            'solicitem',
            'solicitem.pc11_codigo',
            '=',
            'pcprocitem.pc81_solicitem'
        );
        $query->join(
            'solicita',
            'solicita.pc10_numero',
            '=',
            'solicitem.pc11_numero'
        );
        $query->join(
            'db_depart',
            'db_depart.coddepto',
            '=',
            'solicita.pc10_depto'
        );
        $query->join(
            'db_usuarios',
            'solicita.pc10_login',
            '=',
            'db_usuarios.id_usuario'
        );
        $query->leftJoin(
            'pcorcamitemproc',
            'pc31_pcprocitem',
            '=',
            'pc81_codprocitem'
        );
        $query->leftJoin(
            'itemprecoreferencia',
            'si02_itemproccompra',
            '=',
            'pc31_orcamitem'
        );
        $query->leftJoin(
            'solicitemunid',
            'solicitemunid.pc17_codigo',
            '=',
            'solicitem.pc11_codigo'
        );
        $query->leftJoin(
            'matunid',
            'matunid.m61_codmatunid',
            '=',
            'solicitemunid.pc17_unid'
        );
        $query->leftJoin(
            'solicitempcmater',
            'solicitempcmater.pc16_solicitem',
            '=',
            'solicitem.pc11_codigo'
        );
        $query->leftJoin(
            'pcmater',
            'pcmater.pc01_codmater',
            '=',
            'solicitempcmater.pc16_codmater'
        );
        $query->leftJoin(
            'solicitemele',
            'solicitemele.pc18_solicitem',
            '=',
            'solicitem.pc11_codigo'
        );

        $query->where('l21_codliclicita', $l20_codigo);

        $query->orderBy('pc81_codprocitem', 'ASC');
        $query->orderBy('l04_codigo', 'ASC');

        $query->where('l21_codliclicita', $l20_codigo);

        return $query->get()->toArray();
    }

    public function deleteByLicitacao($l20_codigo){
        return $this->model
            ->whereIn('l04_liclicitem', function($query) use ($l20_codigo){
                $query->select('l21_codigo')
                    ->from('liclicitem')
                    ->where('l21_codliclicita', $l20_codigo);
            })
            ->delete();
    }

    public function validaLotesAssociados($l20_codigo){
        return DB::table('liclicitem as item')
            ->select(
                DB::raw('COUNT(item.l21_codigo) as total_itens'),
                DB::raw('COUNT(lote.l04_codigo) as total_item_lote')
            )
            ->leftJoin('liclicitemlote as lote', 'lote.l04_liclicitem', '=', 'item.l21_codigo')
            ->where('l21_codliclicita', $l20_codigo)
            ->havingRaw('COUNT(item.l21_codigo) = COUNT(lote.l04_codigo)')
            ->get();
    }

}
