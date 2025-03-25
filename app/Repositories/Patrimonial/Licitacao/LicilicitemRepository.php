<?php

namespace App\Repositories\Patrimonial\Licitacao;
use cl_liclicitem;
use App\Models\Patrimonial\Licitacao\Liclicitem;
use Illuminate\Support\Facades\DB;

class LicilicitemRepository
{
    private Liclicitem $model;

    public function __construct()
    {
        $this->model = new Liclicitem();
    }

    /**
     * Retorna todos os registros da tabela Liclicitem.
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
     * @return Liclicitem
     */
    public function find($column, $conditions)
    {
        return $this->model->where($column, $conditions)->get();
    }

    /**
     * Encontra um registro pelo ID ou gera uma exceção se não encontrado.
     * 
     * @param int $id Identificador único do registro.
     * @return Liclicitem Retorna o registro encontrado.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Se o registro não for encontrado.
     */
    public function findId($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Insere um novo registro no banco de dados.
     *
     * Esta função gera um novo código de item (`l21_codigo`) utilizando o método `getNextval` 
     * do modelo `Liclicitem` e em seguida cria um novo registro com os dados fornecidos.
     *
     * @param array $dados Dados a serem inseridos no novo registro.
     *
     * @return Liclicitem|null Retorna a instância do modelo `Liclicitem` com os dados inseridos, 
     * ou `null` em caso de falha.
     */
    public function insert(array $dados): ?Liclicitem
    {
        $l21_codigo = $this->model->getNextval();
        $dados['l21_codigo'] =  $l21_codigo;
        return $this->model->create($dados);
    }

    /**
     * Remove um registro de Liclicitem pelo código.
     *
     * Esta função executa uma consulta SQL diretamente para excluir um item da tabela `liclicitem`
     * com o código fornecido.
     *
     * @param int $l21_codigo Código do item a ser removido.
     *
     * @return bool Retorna `true` se a operação de exclusão foi bem-sucedida, 
     * ou `false` em caso de falha.
     */
    public function delete($l21_codigo)
    {
        $sql = "DELETE FROM liclicitem WHERE l21_codigo = $l21_codigo";
        return DB::statement($sql);
    }

    /**
     * Obtém a ordem dos itens para uma licitação específica.
     *
     * Esta função utiliza uma instância da classe `cl_liclicitem` para realizar uma consulta 
     * e retornar a ordem dos itens relacionados ao código de licitação fornecido.
     *
     * @param int $l20_codigo Código da licitação.
     *
     * @return array Resultados da consulta que contém a ordem dos itens da licitação.
     */
    public function getOrdemItens($l20_codigo)
    {
        $cllicitem = new cl_liclicitem();
        $sql = $cllicitem->queryOrdemItens($l20_codigo);
        return DB::select($sql);
    }

    public function getItensLicitacao($l20_codigo,$l224_forne,$lote)
    {
        $cllicitem = new cl_liclicitem();
        $sql = $cllicitem->getItensLicitacao($l20_codigo,$l224_forne,$lote);
        return DB::select($sql);
    }
    public function getPrecoReferencia($l20_codigo)
    {
        $cllicitem = new cl_liclicitem();
        $sql = $cllicitem->getPrecoReferencia($l20_codigo);
        return DB::select($sql);
    }

    public function getBiddingItems($liclicita, $orcamforne, $loteNumero)
    {
        $query = DB::table('liclicitem')
            ->distinct()
            ->select(
                'l21_ordem',
                'pc22_orcamitem',
                'pc01_codmater',
                'pc01_descrmater',
                'pc01_taxa',
                'pc01_tabela',
                'l04_descricao',
                'm61_descr as unidade',
                'l224_quant as quantidade',
                DB::raw("'-' as percentual"),
                DB::raw("NULL vlr_unitario"),
                DB::raw("NULL as vlr_total"),
                'l224_marca as marca'
            )
            ->join('licitacao.liclicita', 'liclicita.l20_codigo', '=', 'licitacao.liclicitem.l21_codliclicita') // licitacao.liclicita
            ->join('compras.pcprocitem', 'pcprocitem.pc81_codprocitem', '=', 'licitacao.liclicitem.l21_codpcprocitem') // compras.pcprocitem
            ->join('compras.solicitem', 'solicitem.pc11_codigo', '=', 'compras.pcprocitem.pc81_solicitem') // compras.solicitem
            ->join('compras.solicitempcmater', 'solicitempcmater.pc16_solicitem', '=', 'compras.solicitem.pc11_codigo') // compras.solicitempcmater
            ->join('compras.pcmater', 'pcmater.pc01_codmater', '=', 'compras.solicitempcmater.pc16_codmater') // compras.pcmater
            ->join('compras.solicitemunid', 'solicitemunid.pc17_codigo', '=', 'compras.solicitem.pc11_codigo') // compras.solicitemunid
            ->join('material.matunid', 'matunid.m61_codmatunid', '=', 'compras.solicitemunid.pc17_unid') // material.matunid
            ->join('licitacao.licpropostavinc', 'licpropostavinc.l223_liclicita', '=', 'licitacao.liclicitem.l21_codliclicita') // licitacao.licpropostavinc
            ->join('licitacao.licproposta', function ($join) { // licproposta.licproposta
                $join->on('l224_codigo', '=', 'licitacao.licpropostavinc.l223_codigo')
                    ->on('l224_propitem', '=', 'licitacao.liclicitem.l21_codpcprocitem');
            })
            ->join('licitacao.pcorcamitemlic', 'pcorcamitemlic.pc26_liclicitem', '=', 'licitacao.liclicitem.l21_codigo') // licitacao.pcorcamitemlic
            ->join('compras.pcorcamitem', 'pcorcamitem.pc22_orcamitem', '=', 'pcorcamitemlic.pc26_orcamitem') // compras.pcorcamitem
            ->leftJoin('licitacao.liclicitemlote', 'liclicitemlote.l04_liclicitem', '=', 'licitacao.liclicitem.l21_codigo') // licitacao.liclicitemlote
            ->where('l21_codliclicita', $liclicita)
            ->where('l223_fornecedor', $orcamforne)
            ->where('l04_numerolote', $loteNumero);
        
        return $query->orderBy('l21_ordem')->get();
    }

    public function getLowestBidWithoutBidding($liclicita, $loteNumero)
    {
        $query = DB::table('liclicitem')
            ->select(
                'licproposta.l224_codigo',
                DB::raw('SUM(licproposta.l224_valor) as total_valor')
            )
            ->join('licitacao.liclicita', 'liclicita.l20_codigo', '=', 'licitacao.liclicitem.l21_codliclicita')
            ->join('licitacao.licpropostavinc', 'licpropostavinc.l223_liclicita', '=', 'licitacao.liclicitem.l21_codliclicita')
            ->join('licitacao.licproposta', function ($join) {
                $join->on('l224_codigo', '=', 'licitacao.licpropostavinc.l223_codigo')
                    ->on('l224_propitem', '=', 'licitacao.liclicitem.l21_codpcprocitem');
            })
            ->leftJoin('licitacao.liclicitemlote', 'liclicitemlote.l04_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')
            ->where('l21_codliclicita', $liclicita)
            ->where('l04_numerolote', $loteNumero)
            ->groupBy('licproposta.l224_codigo')
            ->orderBy('total_valor', 'asc');
        
        return $query->orderBy('total_valor', 'asc')->first();    
    }

    public function getBidItemQuantities($liclicita, $loteNumero, $orcamforne)
    {
        $query = liclicitem::select(
                'l21_ordem',
                'pc11_quant'
            )
            ->distinct()
            ->join('licitacao.liclicita', 'liclicita.l20_codigo', '=', 'licitacao.liclicitem.l21_codliclicita') // licitacao.liclicita
            ->join('compras.pcprocitem', 'pcprocitem.pc81_codprocitem', '=', 'licitacao.liclicitem.l21_codpcprocitem') // compras.pcprocitem
            ->join('compras.solicitem', 'solicitem.pc11_codigo', '=', 'compras.pcprocitem.pc81_solicitem') // compras.solicitem
            ->join('licitacao.licpropostavinc', 'licpropostavinc.l223_liclicita', '=', 'licitacao.liclicitem.l21_codliclicita') // licitacao.licpropostavinc
            ->leftJoin('licitacao.liclicitemlote', 'liclicitemlote.l04_liclicitem', '=', 'licitacao.liclicitem.l21_codigo') // licitacao.liclicitemlote
            ->where('l21_codliclicita', $liclicita)
            ->where('l04_numerolote', $loteNumero)
            ->where('l223_fornecedor', $orcamforne);
        
        return $query->orderBy('l21_ordem')->get();
    }
    
    public function getItensLotes($l20_codigo, $limit, $offset, $isPaginate){
        $query = $this->model->query();

        $query->select(
            'pc81_codprocitem',
            'pc01_codmater',
            'pc01_descrmater',
            'pc11_quant',
            DB::raw('
                CASE
                    WHEN pc80_criterioadjudicacao = 3 THEN si02_vlprecoreferencia
                    ELSE si02_vlpercreferencia
                END as vlrun
            '),
            'si02_vlprecoreferencia',
            DB::raw('
                CASE
                    WHEN l21_reservado = \'t\' THEN \'SIM\'
                    ELSE \'NÃO\'
                END as reservado
            '),
            'l21_codigo',
            'l04_codigo',
            'l04_descricao',
            'l21_situacao',
            'l04_codlilicitalote',
            DB::raw('l04_codlilicitalote as lote_id'),
            DB::raw('l04_descricao as lote')
        );

        $query->leftJoin(
            'liclicitemlote',
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

        $total = $query->count();

        if($isPaginate){
            $query->limit($limit);
            $query->offset(($offset * $limit));
        }

        $data = $query->get()->toArray();

        return ['total' => $total, 'data' => $data];
    }

    public function getItensByDispensa($l20_codigo){
        return $this->model->where('l21_codliclicita', $l20_codigo)->get()->toArray();
    }

    public function getAll(int $l20_codigo){
        return $this->model->where('l21_codliclicita', $l20_codigo)->get()->toArray();
    }

    public function updateSeq($l21_codigo, $l21_ordem){
        return $this->model->where('l21_codigo', $l21_codigo)->update(['l21_ordem' => $l21_ordem]);
    }

    public function deleteByLicitacao($l20_codigo){
        return $this->model
            ->where('l21_codliclicita', $l20_codigo)
            ->delete();
    }

    public function getCriterioAdjudicacaoByProcessoDecompra($l20_codigo){
        return $this->model->select('pcproc.pc80_criterioadjudicacao')
            ->join(
                'pcprocitem',
                'pcprocitem.pc81_codprocitem',
                '=',
                'liclicitem.l21_codpcprocitem'
            )
            ->join(
                'pcproc',
                'pcproc.pc80_codproc',
                '=',
                'pcprocitem.pc81_codproc'
            )
            ->where('l21_codliclicita', $l20_codigo)
            ->groupBy('pc80_criterioadjudicacao')
            ->get();
    }

    public function getReferencePrice($codigoLicitacao, $codigoLicitacaoItem)
    {
        return Liclicitem::select('sicom.itemprecoreferencia.si02_vltotalprecoreferencia', 'sicom.itemprecoreferencia.si02_vlprecoreferencia', 'sicom.itemprecoreferencia.si02_qtditem')
            ->join('licitacao.liclicitemlote', 'liclicitemlote.l04_liclicitem', '=', 'liclicitem.l21_codigo')
            ->join('compras.pcprocitem', 'liclicitem.l21_codpcprocitem', '=', 'pcprocitem.pc81_codprocitem')
            ->join('compras.pcproc', 'pcproc.pc80_codproc', '=', 'pcprocitem.pc81_codproc')
            ->join('compras.solicitem', 'solicitem.pc11_codigo', '=', 'pcprocitem.pc81_solicitem')
            ->join('compras.solicita', 'solicita.pc10_numero', '=', 'solicitem.pc11_numero')
            ->join('compras.pcorcamitemproc', 'pcorcamitemproc.pc31_pcprocitem', '=', 'pcprocitem.pc81_codprocitem')
            ->join('compras.pcorcamitem as orcpcproc', 'orcpcproc.pc22_orcamitem', '=', 'pcorcamitemproc.pc31_orcamitem')
            ->join('sicom.itemprecoreferencia', 'itemprecoreferencia.si02_itemproccompra', '=', 'orcpcproc.pc22_orcamitem')
            ->where('licitacao.liclicitem.l21_codliclicita', $codigoLicitacao)
            ->where('licitacao.liclicitem.l21_ordem', $codigoLicitacaoItem)
            ->first();
    }

    public function getReferencePriceLot($codigoLicitacao, $numeroLoteCodigo)
    {
        return Liclicitem::select('sicom.itemprecoreferencia.si02_vltotalprecoreferencia', 'sicom.itemprecoreferencia.si02_vlprecoreferencia', 'sicom.itemprecoreferencia.si02_qtditem')
            ->join('licitacao.liclicitemlote', 'licitacao.liclicitemlote.l04_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')
            ->join('compras.pcprocitem', 'liclicitem.l21_codpcprocitem', '=', 'pcprocitem.pc81_codprocitem')
            ->join('compras.pcproc', 'pcproc.pc80_codproc', '=', 'pcprocitem.pc81_codproc')
            ->join('compras.solicitem', 'solicitem.pc11_codigo', '=', 'pcprocitem.pc81_solicitem')
            ->join('compras.solicita', 'solicita.pc10_numero', '=', 'solicitem.pc11_numero')
            ->join('compras.pcorcamitemproc', 'pcorcamitemproc.pc31_pcprocitem', '=', 'pcprocitem.pc81_codprocitem')
            ->join('compras.pcorcamitem as orcpcproc', 'orcpcproc.pc22_orcamitem', '=', 'pcorcamitemproc.pc31_orcamitem')
            ->join('sicom.itemprecoreferencia', 'itemprecoreferencia.si02_itemproccompra', '=', 'orcpcproc.pc22_orcamitem')
            ->where('licitacao.liclicitem.l21_codliclicita', $codigoLicitacao)
            ->where('licitacao.liclicitemlote.l04_numerolote', $numeroLoteCodigo)
            ->groupBy([
                'itemprecoreferencia.si02_vltotalprecoreferencia',
                'itemprecoreferencia.si02_vlprecoreferencia',
                'itemprecoreferencia.si02_qtditem'
            ])
            ->first();
    }
    
    /**
     * Obtém o código do item de julgamento baseado nos códigos da licitação e do item de licitação.
     *
     * Esta função realiza uma série de junções entre tabelas para obter o código do item de julgamento 
     * com base nos códigos da licitação e do item de licitação fornecidos.
     *
     * @param int $codigoLicitacao Código da licitação.
     * @param int $codigoLicitacaoItem Código do item da licitação.
     *
     * @return Liclicitem|null Retorna o código do item de julgamento encontrado, ou `null` caso não encontrado.
     */
    public function getJulgItemCode($codigoLicitacao, $codigoLicitacaoItem)
    {
        return Liclicitem::select('l30_codigo', 'pc22_orcamitem')
            ->join('licitacao.pcorcamitemlic', 'pcorcamitemlic.pc26_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')
            ->join('compras.pcorcamitem', 'pcorcamitem.pc22_orcamitem', '=', 'pcorcamitemlic.pc26_orcamitem')
            ->join('licitacao.liclicita', 'liclicita.l20_codigo', '=', 'licitacao.liclicitem.l21_codliclicita')
            ->leftJoin('licitacao.julgitem', 'julgitem.l30_orcamitem', '=', 'compras.pcorcamitem.pc22_orcamitem')
            // ->join('compras.pcorcamval', 'pcorcamval.pc23_orcamitem', '=', 'pcorcamitem.pc22_orcamitem')
            ->where('liclicita.l20_codigo', $codigoLicitacao)
            ->where('liclicitem.l21_ordem', $codigoLicitacaoItem)
            ->distinct()
            ->first();
    }

    /**
     * Obtém a lista de fornecedores e propostas para um item de licitação.
     *
     * Esta função realiza uma consulta para obter a lista de fornecedores e suas propostas para 
     * um item de licitação específico, incluindo o valor da proposta.
     *
     * @param int $codigoLicitacao Código da licitação.
     * @param int $codigoLicitacaoItem Código do item da licitação.
     *
     * @return array Lista de fornecedores e suas propostas ordenadas pelo valor da proposta.
     */
    public function getProposalSupplierList($codigoLicitacao, $codigoLicitacaoItem, $valorDeReferencia)
    {
        $query = Liclicitem::select(
            'julgforne.l34_codigo',
            'julgforne.l34_julgfornestatus',
            'pcorcamitem.pc22_orcamitem',
            'pcorcamforne.pc21_orcamforne',
            'cgm.z01_cgccpf',
            'cgm.z01_nome',
            'licproposta.l224_sequencial',
            'licproposta.l224_vlrun'
        )
        ->join('licitacao.pcorcamitemlic', 'pcorcamitemlic.pc26_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')
        ->join('compras.pcorcamitem', 'pcorcamitem.pc22_orcamitem', '=', 'pcorcamitemlic.pc26_orcamitem')
        ->join('licitacao.liclicita', 'licitacao.liclicita.l20_codigo', '=', 'licitacao.liclicitem.l21_codliclicita')
        ->join('compras.pcorcam', 'compras.pcorcam.pc20_codorc', '=', 'compras.pcorcamitem.pc22_codorc')
        ->join('compras.pcorcamforne', 'compras.pcorcamforne.pc21_codorc', '=', 'compras.pcorcam.pc20_codorc')
        ->leftJoin('licitacao.julgforne', function($join) {
            $join->on('licitacao.julgforne.l34_orcamforne', '=', 'compras.pcorcamforne.pc21_orcamforne')
                ->on('licitacao.julgforne.l34_orcamitem', '=', 'pcorcamitem.pc22_orcamitem');
        })
        ->join('protocolo.cgm', 'protocolo.cgm.z01_numcgm', '=', 'compras.pcorcamforne.pc21_numcgm')

        ->join('licitacao.pcorcamfornelic', 'licitacao.pcorcamfornelic.pc31_orcamforne', '=', 'compras.pcorcamforne.pc21_orcamforne')
        ->join('compras.pcprocitem', 'compras.pcprocitem.pc81_codprocitem', '=', 'licitacao.liclicitem.l21_codpcprocitem')
        ->join('compras.pcorcamitemproc', 'compras.pcorcamitemproc.pc31_pcprocitem', '=', 'compras.pcprocitem.pc81_codprocitem')
        ->join('compras.pcorcamitem as orcitemcompras', 'orcitemcompras.pc22_orcamitem', '=', 'compras.pcorcamitemproc.pc31_orcamitem')

        ->join('licitacao.licproposta', function($join) {
            $join->on('licitacao.licproposta.l224_forne', '=', 'compras.pcorcamforne.pc21_orcamforne')
                ->on('licitacao.licproposta.l224_propitem', '=', 'compras.pcprocitem.pc81_codprocitem');
        })

        ->join('sicom.itemprecoreferencia', 'sicom.itemprecoreferencia.si02_itemproccompra', '=', 'orcitemcompras.pc22_orcamitem')
        ->where('liclicita.l20_codigo', '=', $codigoLicitacao)
        ->where('liclicitem.l21_ordem', '=', $codigoLicitacaoItem);

        if (!empty($valorDeReferencia)) {
            $query->whereBetween('licitacao.licproposta.l224_vlrun', [
                DB::raw('(CAST(sicom.itemprecoreferencia.si02_vltotalprecoreferencia AS DECIMAL) / CAST(sicom.itemprecoreferencia.si02_qtditem AS DECIMAL)) * (1 - (CAST(' . $valorDeReferencia . ' AS DECIMAL) / CAST(100 AS DECIMAL)))'),
                DB::raw('(CAST(sicom.itemprecoreferencia.si02_vltotalprecoreferencia AS DECIMAL) / CAST(sicom.itemprecoreferencia.si02_qtditem AS DECIMAL)) * (1 + (CAST(' . $valorDeReferencia . ' AS DECIMAL) / CAST(100 AS DECIMAL)))')
            ]);
        }

        $query->orderByDesc('licitacao.licproposta.l224_sequencial');

        return $query->get();
    }

    /**
     * Obtém a lista de fornecedores e propostas para um lote da licitação.
     *
     * Esta função realiza uma consulta para obter a lista de fornecedores e suas propostas para 
     * um lote de uma licitação específica, incluindo o valor da proposta.
     *
     * @param int $codigoLicitacao Código da licitação.
     * @param int $numeroLoteCodigo Código do lote da licitação.
     *
     * @return array Lista de fornecedores e suas propostas ordenadas pelo valor da proposta.
     */
    public function getProposalSupplierListLots($codigoLicitacao, $numeroLoteCodigo, $valorDeReferencia)
    {
        $query = Liclicitem::select(
            'julgforne.l34_codigo',
            'julgforne.l34_julgfornestatus',
            'liclicitemlote.l04_numerolote',
            'pcorcamforne.pc21_orcamforne',
            'cgm.z01_cgccpf',
            'cgm.z01_nome',
            'licproposta.l224_codigo',
            DB::raw('SUM(licproposta.l224_valor) as l224_vlrun')
        )
        ->join('licitacao.pcorcamitemlic', 'pcorcamitemlic.pc26_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')
        ->join('compras.pcorcamitem', 'pcorcamitem.pc22_orcamitem', '=', 'pcorcamitemlic.pc26_orcamitem')
        ->join('licitacao.liclicita', 'licitacao.liclicita.l20_codigo', '=', 'licitacao.liclicitem.l21_codliclicita')
        ->join('compras.pcorcam', 'compras.pcorcam.pc20_codorc', '=', 'compras.pcorcamitem.pc22_codorc')
        ->join('compras.pcorcamforne', 'compras.pcorcamforne.pc21_codorc', '=', 'compras.pcorcam.pc20_codorc')
        ->join('licitacao.liclicitemlote', 'licitacao.liclicitemlote.l04_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')
        ->leftJoin('licitacao.julgforne', function($join) {
            $join->on('licitacao.julgforne.l34_orcamforne', '=', 'compras.pcorcamforne.pc21_orcamforne')
                ->on('licitacao.julgforne.l34_numerolote', '=', 'liclicitemlote.l04_numerolote');
        })
        ->join('protocolo.cgm', 'protocolo.cgm.z01_numcgm', '=', 'compras.pcorcamforne.pc21_numcgm')
        ->join('licitacao.pcorcamfornelic', 'licitacao.pcorcamfornelic.pc31_orcamforne', '=', 'compras.pcorcamforne.pc21_orcamforne')
        ->join('compras.pcprocitem', 'compras.pcprocitem.pc81_codprocitem', '=', 'licitacao.liclicitem.l21_codpcprocitem')
        ->join('compras.pcorcamitemproc', 'compras.pcorcamitemproc.pc31_pcprocitem', '=', 'compras.pcprocitem.pc81_codprocitem')
        ->join('compras.pcorcamitem as orcitemcompras', 'orcitemcompras.pc22_orcamitem', '=', 'compras.pcorcamitemproc.pc31_orcamitem')
        ->join('licitacao.licproposta', function($join) {
            $join->on('licitacao.licproposta.l224_forne', '=', 'compras.pcorcamforne.pc21_orcamforne')
                ->on('licitacao.licproposta.l224_propitem', '=', 'compras.pcprocitem.pc81_codprocitem');
        })
        ->join('sicom.itemprecoreferencia', 'sicom.itemprecoreferencia.si02_itemproccompra', '=', 'orcitemcompras.pc22_orcamitem')
        ->where('liclicita.l20_codigo', '=', $codigoLicitacao)
        ->where('liclicitemlote.l04_numerolote', '=', $numeroLoteCodigo)
        ->groupBy([
            'julgforne.l34_codigo',
            'julgforne.l34_julgfornestatus',
            'liclicitemlote.l04_numerolote',
            'pcorcamforne.pc21_orcamforne',
            'cgm.z01_cgccpf',
            'cgm.z01_nome',
            'licproposta.l224_codigo'
        ])
        ->orderByDesc('licitacao.licproposta.l224_codigo');

        if (!empty($valorDeReferencia)) {
            $query->whereBetween('licitacao.licproposta.l224_vlrun', [
                DB::raw('(CAST(sicom.itemprecoreferencia.si02_vltotalprecoreferencia AS DECIMAL) / CAST(sicom.itemprecoreferencia.si02_qtditem AS DECIMAL)) * (1 - (CAST(' . $valorDeReferencia . ' AS DECIMAL) / CAST(100 AS DECIMAL)))'),
                DB::raw('(CAST(sicom.itemprecoreferencia.si02_vltotalprecoreferencia AS DECIMAL) / CAST(sicom.itemprecoreferencia.si02_qtditem AS DECIMAL)) * (1 + (CAST(' . $valorDeReferencia . ' AS DECIMAL) / CAST(100 AS DECIMAL)))')
            ]);
        }
        
        return $query->get();
    }

    public function getProposalSupplier($codigoLicitacao, $codigoLicitacaoItem, $codigoOrcamforne)
    {
        $query = Liclicitem::select(
            'pcorcamitem.pc22_orcamitem',
            'pcorcamforne.pc21_orcamforne',
            'licproposta.l224_sequencial',
            'licproposta.l224_vlrun'
        )

        // pcorcamitem (Licitação)
        ->join('licitacao.pcorcamitemlic', 'pcorcamitemlic.pc26_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')
        ->join('compras.pcorcamitem', 'pcorcamitem.pc22_orcamitem', '=', 'pcorcamitemlic.pc26_orcamitem')

        // liclicita
        ->join('licitacao.liclicita', 'licitacao.liclicita.l20_codigo', '=', 'licitacao.liclicitem.l21_codliclicita')

        // pcorcamforne (Compras)
        ->join('compras.pcorcam', 'compras.pcorcam.pc20_codorc', '=', 'compras.pcorcamitem.pc22_codorc')
        ->join('compras.pcorcamforne', 'compras.pcorcamforne.pc21_codorc', '=', 'compras.pcorcam.pc20_codorc')

        // pcprocitem (Compras)
        ->join('compras.pcprocitem', 'compras.pcprocitem.pc81_codprocitem', '=', 'licitacao.liclicitem.l21_codpcprocitem')

        // licproposta
        ->join('licitacao.licproposta', function($join) {
            $join->on('licitacao.licproposta.l224_forne', '=', 'compras.pcorcamforne.pc21_orcamforne')
                ->on('licitacao.licproposta.l224_propitem', '=', 'compras.pcprocitem.pc81_codprocitem');
        })

        ->where('liclicita.l20_codigo', '=', $codigoLicitacao)
        ->where('liclicitem.l21_ordem', '=', $codigoLicitacaoItem)
        ->where('pcorcamforne.pc21_orcamforne', '=', $codigoOrcamforne);

        $query->orderByDesc('licitacao.licproposta.l224_sequencial');

        return $query->first();
    }

    /**
     * Obtém a lista de fornecedores e propostas para um item de licitação.
     *
     * Esta função realiza uma consulta para obter a lista de fornecedores e suas propostas para 
     * um item de licitação específico, incluindo o valor da proposta.
     *
     * @param int $codigoLicitacao Código da licitação.
     * @param int $codigoLicitacaoItem Código do item da licitação.
     *
     * @return array Lista de fornecedores e suas propostas ordenadas pelo valor da proposta.
     */
    public function getProposalListOfSuppliersAvailableForBidding($codigoLicitacao, $codigoLicitacaoItem)
    {
        return Liclicitem::select(
            'julgforne.l34_codigo',
            'julgforne.l34_julgfornestatus',
            'pcorcamitem.pc22_orcamitem',
            'pcorcamforne.pc21_orcamforne',
            'cgm.z01_cgccpf',
            'cgm.z01_nome',
            'licproposta.l224_sequencial',
            'licproposta.l224_vlrun'
        )
        ->join('compras.pcprocitem', 'licitacao.liclicitem.l21_codpcprocitem', '=', 'compras.pcprocitem.pc81_codprocitem')
        ->join('licitacao.pcorcamitemlic', 'pcorcamitemlic.pc26_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')
        ->join('compras.pcorcamitem', 'pcorcamitem.pc22_orcamitem', '=', 'pcorcamitemlic.pc26_orcamitem')
        ->join('licitacao.liclicita', 'licitacao.liclicita.l20_codigo', '=', 'licitacao.liclicitem.l21_codliclicita')
        ->join('compras.pcorcam', 'compras.pcorcam.pc20_codorc', '=', 'compras.pcorcamitem.pc22_codorc')
        ->join('compras.pcorcamforne', 'compras.pcorcamforne.pc21_codorc', '=', 'compras.pcorcam.pc20_codorc')
        ->leftJoin('licitacao.julgforne', 'licitacao.julgforne.l34_orcamforne', '=', 'compras.pcorcamforne.pc21_orcamforne')
        ->join('protocolo.cgm', 'protocolo.cgm.z01_numcgm', '=', 'compras.pcorcamforne.pc21_numcgm')
        ->join('licitacao.licproposta', function($join) {
            $join->on('licitacao.licproposta.l224_forne', '=', 'compras.pcorcamforne.pc21_orcamforne')
                ->on('licitacao.licproposta.l224_propitem', '=', 'compras.pcprocitem.pc81_codprocitem');
        })
        ->where('liclicita.l20_codigo', '=', $codigoLicitacao)
        ->where('liclicitem.l21_ordem', '=', $codigoLicitacaoItem)
        ->where(function($query) {
            $query->whereNull('julgforne.l34_julgfornestatus')
                  ->orWhere('julgforne.l34_julgfornestatus', '=', 1);
        })
        ->orderByDesc('licitacao.licproposta.l224_sequencial')
        ->get();
    }

    /**
     * Obtém o código do item do orçamento relacionado a um item de licitação específico.
     *
     * Esta função realiza uma consulta para recuperar o código do item de orçamento para um item de licitação.
     *
     * @param int $codigoLicitacao Código da licitação.
     * @param int $codigoLicitacaoItem Código do item de licitação.
     *
     * @return mixed O código do item de orçamento se encontrado, ou `null` se não houver correspondência.
     */
    public function getOrcamItemCode($codigoLicitacao, $codigoLicitacaoItem)
    {
        return Liclicitem::select('pc22_orcamitem', 'l30_julgitemstatus')
            ->join('compras.pcprocitem', 'pcprocitem.pc81_codprocitem', '=', 'liclicitem.l21_codpcprocitem')
            ->join('licitacao.pcorcamitemlic', 'pcorcamitemlic.pc26_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')
            ->join('compras.pcorcamitem', 'pcorcamitem.pc22_orcamitem', '=', 'pcorcamitemlic.pc26_orcamitem')
            ->join('compras.solicitem', 'solicitem.pc11_codigo', '=', 'pcprocitem.pc81_solicitem')
            ->join('compras.solicitempcmater', 'solicitempcmater.pc16_solicitem', '=', 'solicitem.pc11_codigo')
            ->join('compras.pcmater', 'pcmater.pc01_codmater', '=', 'solicitempcmater.pc16_codmater')
            ->join('compras.solicitemunid', 'solicitemunid.pc17_codigo', '=', 'solicitem.pc11_codigo')
            ->join('licitacao.liclicita', 'liclicita.l20_codigo', '=', 'licitacao.liclicitem.l21_codliclicita')
            ->leftJoin('licitacao.julgitem', 'julgitem.l30_orcamitem', '=', 'compras.pcorcamitem.pc22_orcamitem')
            ->where('liclicita.l20_codigo', $codigoLicitacao)
            ->where('liclicitem.l21_ordem', $codigoLicitacaoItem)
            ->first();
    }

    /**
     * Obtém os dados do item selecionado de uma licitação.
     *
     * Esta função consulta informações detalhadas sobre um item selecionado para uma licitação específica, 
     * incluindo descrição do material e unidade de medida.
     *
     * @param int $codigoLicitacao Código da licitação.
     * @param int $codigoLicitacaoItem Código do item de licitação.
     *
     * @return array|null Dados do item selecionado ou `null` caso não encontrado.
     */
    public function getDataItemSelected($codigoLicitacao, $codigoLicitacaoItem)
    {
        return Liclicitem::select('pc22_orcamitem', 'l21_ordem', 'pc01_descrmater', 'm61_descr')
            ->join('compras.pcprocitem', 'pcprocitem.pc81_codprocitem', '=', 'licitacao.liclicitem.l21_codpcprocitem')

            ->join('licitacao.pcorcamitemlic', 'pcorcamitemlic.pc26_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')
            ->join('compras.pcorcamitem', 'pcorcamitem.pc22_orcamitem', '=', 'pcorcamitemlic.pc26_orcamitem')

            ->join('compras.solicitem', 'solicitem.pc11_codigo', '=', 'pcprocitem.pc81_solicitem')
            ->join('compras.solicitempcmater', 'solicitempcmater.pc16_solicitem', '=', 'solicitem.pc11_codigo')
            ->join('compras.pcmater', 'pcmater.pc01_codmater', '=', 'solicitempcmater.pc16_codmater')
            ->join('compras.solicitemunid', 'solicitemunid.pc17_codigo', '=', 'solicitem.pc11_codigo')
            ->join('material.matunid', 'matunid.m61_codmatunid', '=', 'solicitemunid.pc17_unid')

            ->where('liclicitem.l21_codliclicita', $codigoLicitacao)
            ->where('liclicitem.l21_ordem', $codigoLicitacaoItem)
            ->first();
    }

    /**
     * Obtém a proposta mais baixa para um item de licitação.
     *
     * Esta função realiza uma consulta para encontrar a proposta com o menor valor para um item de licitação específico.
     *
     * @param int $codigoLicitacao Código da licitação.
     * @param int $codigoLicitacaoItem Código do item da licitação.
     *
     * @return Liclicitem|null Retorna a proposta mais baixa ou `null` se não encontrada.
     */
    public function getTheLowestBid($codigoLicitacao, $codigoLicitacaoItem, $valorDeReferencia)
    {
        $query = Liclicitem::select(
                'compras.pcorcamforne.pc21_orcamforne',
                'compras.pcprocitem.pc81_codprocitem',
                'licitacao.liclicitem.l21_ordem',
                'licitacao.licproposta.l224_vlrun'
            )
            ->join('licitacao.liclicita', 'licitacao.liclicita.l20_codigo', '=', 'licitacao.liclicitem.l21_codliclicita')
            ->join('licitacao.pcorcamitemlic', 'licitacao.liclicitem.l21_codigo', '=', 'licitacao.pcorcamitemlic.pc26_liclicitem')
            ->join('compras.pcorcamitem', 'compras.pcorcamitem.pc22_orcamitem', '=', 'licitacao.pcorcamitemlic.pc26_orcamitem')
            ->join('compras.pcorcam', 'compras.pcorcam.pc20_codorc', '=', 'compras.pcorcamitem.pc22_codorc')
            ->join('compras.pcorcamforne', 'compras.pcorcamforne.pc21_codorc', '=', 'compras.pcorcam.pc20_codorc')
            ->join('compras.pcprocitem', 'compras.pcprocitem.pc81_codprocitem', '=', 'licitacao.liclicitem.l21_codpcprocitem')
            ->join('compras.pcorcamitemproc', 'compras.pcorcamitemproc.pc31_pcprocitem', '=', 'compras.pcprocitem.pc81_codprocitem')
            ->join('compras.pcorcamitem as orcitemcompras', 'orcitemcompras.pc22_orcamitem', '=', 'compras.pcorcamitemproc.pc31_orcamitem')
            ->join('licitacao.licproposta', function($join) {
                $join->on('licitacao.licproposta.l224_forne', '=', 'compras.pcorcamforne.pc21_orcamforne')
                    ->on('licitacao.licproposta.l224_propitem', '=', 'compras.pcprocitem.pc81_codprocitem');
            })
            ->join('sicom.itemprecoreferencia', 'sicom.itemprecoreferencia.si02_itemproccompra', '=', 'orcitemcompras.pc22_orcamitem')
            ->where('licitacao.liclicita.l20_codigo', $codigoLicitacao)
            ->where('licitacao.liclicitem.l21_ordem', $codigoLicitacaoItem)
            ->orderBy('licitacao.licproposta.l224_vlrun', 'asc');

        if (!empty($valorDeReferencia)) {
            $query->whereBetween('licitacao.licproposta.l224_vlrun', [
                DB::raw('(CAST(sicom.itemprecoreferencia.si02_vltotalprecoreferencia AS DECIMAL) / CAST(sicom.itemprecoreferencia.si02_qtditem AS DECIMAL)) * (1 - (CAST(' . $valorDeReferencia . ' AS DECIMAL) / CAST(100 AS DECIMAL)))'),
                DB::raw('(CAST(sicom.itemprecoreferencia.si02_vltotalprecoreferencia AS DECIMAL) / CAST(sicom.itemprecoreferencia.si02_qtditem AS DECIMAL)) * (1 + (CAST(' . $valorDeReferencia . ' AS DECIMAL) / CAST(100 AS DECIMAL)))')
            ]);
        }

        return $query->first();
    }

    public function getTheLowestBidLot($codigoLicitacao, $numeroLote, $valorDeReferencia)
    {
        $query = Liclicitem::select(
            'compras.pcorcamforne.pc21_orcamforne',
            DB::raw('SUM(licproposta.l224_valor) as l224_vlrun')
        )
        ->join('licitacao.liclicita', 'licitacao.liclicita.l20_codigo', '=', 'licitacao.liclicitem.l21_codliclicita')
        ->join('licitacao.pcorcamitemlic', 'pcorcamitemlic.pc26_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')
        ->join('compras.pcorcamitem', 'pcorcamitem.pc22_orcamitem', '=', 'pcorcamitemlic.pc26_orcamitem')
        ->join('compras.pcorcam', 'compras.pcorcam.pc20_codorc', '=', 'compras.pcorcamitem.pc22_codorc')
        ->join('compras.pcorcamforne', 'compras.pcorcamforne.pc21_codorc', '=', 'compras.pcorcam.pc20_codorc')
        ->join('licitacao.liclicitemlote', 'licitacao.liclicitemlote.l04_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')
        ->join('compras.pcprocitem', 'compras.pcprocitem.pc81_codprocitem', '=', 'licitacao.liclicitem.l21_codpcprocitem')
        ->join('compras.pcorcamitemproc', 'compras.pcorcamitemproc.pc31_pcprocitem', '=', 'compras.pcprocitem.pc81_codprocitem')
        ->join('compras.pcorcamitem as orcitemcompras', 'orcitemcompras.pc22_orcamitem', '=', 'compras.pcorcamitemproc.pc31_orcamitem')
        ->join('licitacao.licproposta', function($join) {
            $join->on('licitacao.licproposta.l224_forne', '=', 'compras.pcorcamforne.pc21_orcamforne')
                ->on('licitacao.licproposta.l224_propitem', '=', 'compras.pcprocitem.pc81_codprocitem');
        })
        ->join('sicom.itemprecoreferencia', 'sicom.itemprecoreferencia.si02_itemproccompra', '=', 'orcitemcompras.pc22_orcamitem')
        ->where('liclicita.l20_codigo', '=', $codigoLicitacao)
        ->where('liclicitemlote.l04_numerolote', '=', $numeroLote)
        ->groupBy([
            'compras.pcorcamforne.pc21_orcamforne'
        ])
        ->orderBy('l224_vlrun', 'asc');

        if (!empty($valorDeReferencia)) {
            $query->whereBetween('licitacao.licproposta.l224_vlrun', [
                DB::raw('(CAST(sicom.itemprecoreferencia.si02_vltotalprecoreferencia AS DECIMAL) / CAST(sicom.itemprecoreferencia.si02_qtditem AS DECIMAL)) * (1 - (CAST(' . $valorDeReferencia . ' AS DECIMAL) / CAST(100 AS DECIMAL)))'),
                DB::raw('(CAST(sicom.itemprecoreferencia.si02_vltotalprecoreferencia AS DECIMAL) / CAST(sicom.itemprecoreferencia.si02_qtditem AS DECIMAL)) * (1 + (CAST(' . $valorDeReferencia . ' AS DECIMAL) / CAST(100 AS DECIMAL)))')
            ]);
        }

        return $query->first();
    }

    public function getLowestBidDetailsForSupplier($liclicita, $liclicitem, $pcorcamforne, $pcorcamitem)
    {
        $subquery = DB::table('licitacao.julglances')
            ->join('licitacao.julgforne', 'licitacao.julglances.l32_julgforne', '=', 'licitacao.julgforne.l34_codigo')
            ->where('licitacao.julgforne.l34_orcamforne', $pcorcamforne)
            ->where('licitacao.julgforne.l34_orcamitem', $pcorcamitem)
            ->whereNotNull('licitacao.julglances.l32_lance')
            ->min('licitacao.julglances.l32_lance');

        $query = Liclicitem::select(
                'pcorcamitem.pc22_orcamitem',
                'pcorcamforne.pc21_orcamforne',
                'julglances.l32_lance',
                'licproposta.l224_quant',
                'julgforne.l34_julgfornestatus'
            )
            ->join('compras.pcprocitem', 'licitacao.liclicitem.l21_codpcprocitem', '=', 'compras.pcprocitem.pc81_codprocitem')
            ->join('licitacao.pcorcamitemlic', 'pcorcamitemlic.pc26_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')
            ->join('compras.pcorcamitem', 'pcorcamitem.pc22_orcamitem', '=', 'pcorcamitemlic.pc26_orcamitem')
            ->join('licitacao.liclicita', 'licitacao.liclicita.l20_codigo', '=', 'licitacao.liclicitem.l21_codliclicita')
            ->join('compras.pcorcam', 'compras.pcorcam.pc20_codorc', '=', 'compras.pcorcamitem.pc22_codorc')
            ->join('compras.pcorcamforne', 'compras.pcorcamforne.pc21_codorc', '=', 'compras.pcorcam.pc20_codorc')
            ->join('licitacao.julgforne', 'licitacao.julgforne.l34_orcamforne', '=', 'compras.pcorcamforne.pc21_orcamforne')
            ->join('licitacao.julgitem', 'licitacao.julgitem.l30_orcamitem', '=', 'pcorcamitem.pc22_orcamitem')
            ->join('licitacao.julglances', function ($join) {
                $join->on('licitacao.julglances.l32_julgitem', '=', 'licitacao.julgitem.l30_codigo')
                    ->on('licitacao.julglances.l32_julgforne', '=', 'licitacao.julgforne.l34_codigo');
            })
            ->join('licitacao.licproposta', function ($join) {
                $join->on('licitacao.licproposta.l224_forne', '=', 'compras.pcorcamforne.pc21_orcamforne')
                    ->on('licitacao.licproposta.l224_propitem', '=', 'compras.pcprocitem.pc81_codprocitem');
            })
            ->where('licitacao.liclicita.l20_codigo', $liclicita)
            ->where('licitacao.liclicitem.l21_ordem', $liclicitem)
            ->where('compras.pcorcamforne.pc21_orcamforne', $pcorcamforne);

            if (!is_null($subquery)) {
                $query->where('julglances.l32_lance', $subquery);
            }

            $query->orderBy('licitacao.julglances.l32_lance', 'asc');
            return $query->first();
    }

    public function getLowestBidDetailsForSupplierLot($liclicita, $pcorcamforne, $numeroLote)
    {
        $subquery = DB::table('licitacao.julglances')
            ->join('licitacao.julgforne', 'licitacao.julglances.l32_julgforne', '=', 'licitacao.julgforne.l34_codigo')
            ->where('licitacao.julgforne.l34_orcamforne', $pcorcamforne)
            ->where('licitacao.julgforne.l34_numerolote', $numeroLote)
            ->whereNotNull('licitacao.julglances.l32_lance')
            ->min('licitacao.julglances.l32_lance');

        $query = Liclicitem::select(
                'pcorcamitem.pc22_orcamitem',
                'pcorcamforne.pc21_orcamforne',
                'julglances.l32_lance',
                'licproposta.l224_quant',
                'julgforne.l34_julgfornestatus'
            )
            ->join('compras.pcprocitem', 'licitacao.liclicitem.l21_codpcprocitem', '=', 'compras.pcprocitem.pc81_codprocitem')
            ->join('licitacao.pcorcamitemlic', 'pcorcamitemlic.pc26_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')
            ->join('compras.pcorcamitem', 'pcorcamitem.pc22_orcamitem', '=', 'pcorcamitemlic.pc26_orcamitem')
            ->join('licitacao.liclicita', 'licitacao.liclicita.l20_codigo', '=', 'licitacao.liclicitem.l21_codliclicita')
            ->join('compras.pcorcam', 'compras.pcorcam.pc20_codorc', '=', 'compras.pcorcamitem.pc22_codorc')
            ->join('compras.pcorcamforne', 'compras.pcorcamforne.pc21_codorc', '=', 'compras.pcorcam.pc20_codorc')
            ->join('licitacao.liclicitemlote', 'licitacao.liclicitemlote.l04_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')
            ->join('licitacao.julgforne', 'licitacao.julgforne.l34_orcamforne', '=', 'compras.pcorcamforne.pc21_orcamforne')
            ->join('licitacao.julgitem', 'licitacao.julgitem.l30_numerolote', '=', 'licitacao.liclicitemlote.l04_numerolote')
            ->join('licitacao.julglances', function ($join) {
                $join->on('licitacao.julglances.l32_julgitem', '=', 'licitacao.julgitem.l30_codigo')
                    ->on('licitacao.julglances.l32_julgforne', '=', 'licitacao.julgforne.l34_codigo');
            })
            ->join('licitacao.licproposta', function ($join) {
                $join->on('licitacao.licproposta.l224_forne', '=', 'compras.pcorcamforne.pc21_orcamforne')
                    ->on('licitacao.licproposta.l224_propitem', '=', 'compras.pcprocitem.pc81_codprocitem');
            })
            ->where('licitacao.liclicita.l20_codigo', $liclicita)
            ->where('licitacao.liclicitemlote.l04_numerolote', '=', $numeroLote)
            ->where('compras.pcorcamforne.pc21_orcamforne', $pcorcamforne);

            if (!is_null($subquery)) {
                $query->where('julglances.l32_lance', $subquery);
            }

            $query->orderBy('licitacao.julglances.l32_lance', 'asc');
            return $query->get();
    }

    public function getSuppliersWithAMicroEnterprise($liclicita, $liclicitem, $valorDeReferencia=null)
    {
        $query = Liclicitem::select(
                'pcorcamitem.pc22_orcamitem',
                'compras.pcorcamforne.pc21_orcamforne',
                'cgm.z01_nome',
                'pcorcamfornelic.pc31_liclicitatipoempresa'
            )
            ->join('compras.pcprocitem', 'licitacao.liclicitem.l21_codpcprocitem', '=', 'compras.pcprocitem.pc81_codprocitem')
            ->join('licitacao.pcorcamitemlic', 'pcorcamitemlic.pc26_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')
            ->join('compras.pcorcamitem', 'pcorcamitem.pc22_orcamitem', '=', 'pcorcamitemlic.pc26_orcamitem')
            ->join('licitacao.liclicita', 'licitacao.liclicita.l20_codigo', '=', 'licitacao.liclicitem.l21_codliclicita')
            ->join('compras.pcorcam', 'compras.pcorcam.pc20_codorc', '=', 'compras.pcorcamitem.pc22_codorc')
            ->join('compras.pcorcamforne', 'compras.pcorcamforne.pc21_codorc', '=', 'compras.pcorcam.pc20_codorc')
            ->join('licitacao.pcorcamfornelic', 'licitacao.pcorcamfornelic.pc31_orcamforne', '=', 'compras.pcorcamforne.pc21_orcamforne')
            ->join('protocolo.cgm', 'protocolo.cgm.z01_numcgm', '=', 'compras.pcorcamforne.pc21_numcgm')
            ->join('compras.pcorcamitemproc', 'compras.pcorcamitemproc.pc31_pcprocitem', '=', 'compras.pcprocitem.pc81_codprocitem')  
            ->join('sicom.itemprecoreferencia', 'sicom.itemprecoreferencia.si02_itemproccompra', '=', 'pcorcamitemproc.pc31_orcamitem') 

            ->join('licitacao.licproposta', function($join) {
                $join->on('licitacao.licproposta.l224_forne', '=', 'compras.pcorcamforne.pc21_orcamforne')
                    ->on('licitacao.licproposta.l224_propitem', '=', 'compras.pcprocitem.pc81_codprocitem');
            })

            ->where('licitacao.liclicita.l20_codigo', $liclicita)
            ->where('licitacao.liclicitem.l21_ordem', $liclicitem)
            ->where('licitacao.pcorcamfornelic.pc31_liclicitatipoempresa', 2);

            if (!empty($valorDeReferencia)) {
                $query->whereBetween('licitacao.licproposta.l224_vlrun', [
                    DB::raw('(CAST(sicom.itemprecoreferencia.si02_vltotalprecoreferencia AS DECIMAL) / CAST(sicom.itemprecoreferencia.si02_qtditem AS DECIMAL)) * (1 - (CAST(' . $valorDeReferencia . ' AS DECIMAL) / CAST(100 AS DECIMAL)))'),
                    DB::raw('(CAST(sicom.itemprecoreferencia.si02_vltotalprecoreferencia AS DECIMAL) / CAST(sicom.itemprecoreferencia.si02_qtditem AS DECIMAL)) * (1 + (CAST(' . $valorDeReferencia . ' AS DECIMAL) / CAST(100 AS DECIMAL)))')
                ]);
            }

            return $query->get();
    }

    public function getSuppliersWithAMicroEnterpriseLot($liclicita, $numeroLote, $valorDeReferencia=null)
    {

        $query = Liclicitem::select(
            'pcorcamforne.pc21_orcamforne',
            'cgm.z01_nome',
            'liclicitemlote.l04_numerolote',
            'pcorcamfornelic.pc31_liclicitatipoempresa'
        )
        ->join('licitacao.liclicita', 'licitacao.liclicita.l20_codigo', '=', 'licitacao.liclicitem.l21_codliclicita')
        ->join('licitacao.liclicitemlote', 'licitacao.liclicitemlote.l04_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')
        ->join('compras.pcprocitem', 'compras.pcprocitem.pc81_codprocitem', '=', 'licitacao.liclicitem.l21_codpcprocitem')
        ->join('licitacao.pcorcamitemlic', 'pcorcamitemlic.pc26_liclicitem', '=', 'licitacao.liclicitem.l21_codigo')

        ->join('compras.pcorcamitemproc', 'compras.pcorcamitemproc.pc31_pcprocitem', '=', 'compras.pcprocitem.pc81_codprocitem')
        ->join('compras.pcorcamitem as orcitemcompras', 'orcitemcompras.pc22_orcamitem', '=', 'compras.pcorcamitemproc.pc31_orcamitem')

        ->join('sicom.itemprecoreferencia', 'sicom.itemprecoreferencia.si02_itemproccompra', '=', 'orcitemcompras.pc22_orcamitem')

        ->join('compras.pcorcamitem', 'pcorcamitem.pc22_orcamitem', '=', 'pcorcamitemlic.pc26_orcamitem')
        ->join('compras.pcorcam', 'compras.pcorcam.pc20_codorc', '=', 'compras.pcorcamitem.pc22_codorc')
        ->join('compras.pcorcamforne', 'compras.pcorcamforne.pc21_codorc', '=', 'compras.pcorcam.pc20_codorc')

        ->join('licitacao.pcorcamfornelic', 'licitacao.pcorcamfornelic.pc31_orcamforne', '=', 'compras.pcorcamforne.pc21_orcamforne')
        ->join('protocolo.cgm', 'protocolo.cgm.z01_numcgm', '=', 'compras.pcorcamforne.pc21_numcgm')
        
        ->leftJoin('licitacao.julgforne', function($join) {
            $join->on('licitacao.julgforne.l34_orcamforne', '=', 'compras.pcorcamforne.pc21_orcamforne')
                ->on('licitacao.julgforne.l34_numerolote', '=', 'liclicitemlote.l04_numerolote');
        })

        ->join('licitacao.licproposta', function($join) {
            $join->on('licitacao.licproposta.l224_forne', '=', 'compras.pcorcamforne.pc21_orcamforne')
                ->on('licitacao.licproposta.l224_propitem', '=', 'compras.pcprocitem.pc81_codprocitem');
        })

        ->where('liclicita.l20_codigo', '=', $liclicita)
        ->where('liclicitemlote.l04_numerolote', '=', $numeroLote)
        ->where('licitacao.pcorcamfornelic.pc31_liclicitatipoempresa', 2)

        ->groupBy([
            'pcorcamforne.pc21_orcamforne',
            'cgm.z01_nome',
            'liclicitemlote.l04_numerolote',
            'pcorcamfornelic.pc31_liclicitatipoempresa'
        ]);

        if (!empty($valorDeReferencia)) {
            $query->whereBetween('licitacao.licproposta.l224_vlrun', [
                DB::raw('(CAST(sicom.itemprecoreferencia.si02_vltotalprecoreferencia AS DECIMAL) / CAST(sicom.itemprecoreferencia.si02_qtditem AS DECIMAL)) * (1 - (CAST(' . $valorDeReferencia . ' AS DECIMAL) / CAST(100 AS DECIMAL)))'),
                DB::raw('(CAST(sicom.itemprecoreferencia.si02_vltotalprecoreferencia AS DECIMAL) / CAST(sicom.itemprecoreferencia.si02_qtditem AS DECIMAL)) * (1 + (CAST(' . $valorDeReferencia . ' AS DECIMAL) / CAST(100 AS DECIMAL)))')
            ]);
        }

        return $query->get();
    }
}
