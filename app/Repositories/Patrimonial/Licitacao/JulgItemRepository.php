<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\Julgitem;

class JulgItemRepository
{
    /**
     * Retorna todos os registros da tabela JulgItem.
     * 
     * @return \Illuminate\Database\Eloquent\Collection Retorna todos os registros encontrados.
     */
    public function all()
    {
        return JulgItem::all();
    }

    /**
     * Encontra registros no banco de dados com base em uma coluna e condição fornecidas.
     *
     * @param string $column Nome da coluna para aplicar a condição de pesquisa.
     * @param mixed $conditions Valor da condição para a busca.
     * @return JulgItem
     */
    public function find($column, $conditions)
    {
        return JulgItem::where($column, $conditions)->get();
    }

    /**
     * Encontra um registro pelo ID ou gera uma exceção se não encontrado.
     * 
     * @param int $id Identificador único do registro.
     * @return JulgItem Retorna o registro encontrado.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Se o registro não for encontrado.
     */
    public function findId($id)
    {
        return JulgItem::findOrFail($id);
    }

    /**
     * Cria um novo item no banco de dados.
     *
     * @param array $dados
     * @return JulgItem
     */
    public function create(array $dados)
    {
        return JulgItem::create($dados);
    }

    /**
     * Atualiza os dados de um item existente pelo ID.
     *
     * @param int $id
     * @param array $dados
     * @return JulgItem
     */
    public function update($id, array $dados)
    {
        $julgItem = JulgItem::findOrFail($id);
        $julgItem->update($dados);
        return $julgItem;
    }

    /**
     * Exclui registros da tabela JulgItem com base na condição fornecida.
     * 
     * @param string $column Nome da coluna para aplicar a condição de exclusão.
     * @param mixed $conditions Valor da condição para identificar os registros a serem excluídos.
     * @return bool Retorna verdadeiro se a exclusão for bem-sucedida.
     */
    public function delete($column, $conditions)
    {
        $julgItem = JulgItem::where($column, $conditions);
        return $julgItem->delete();
    }

    /**
     * Busca um item de orçamento específico pelo código do item.
     *
     * @param string $codigoItemOrcamento
     * @return JulgItem|null
     */
    public function findItemBudget($codigoItemOrcamento)
    {
        return JulgItem::where('l30_orcamitem', $codigoItemOrcamento)
            ->orderBy('l30_codigo', 'desc')
            ->first();
    }

    /**
     * Busca um lote de orçamento específico pelo código do item.
     *
     * @param string $numeroloteCodigo
     * @return JulgItem|null
     */
    public function findLotBudget($numeroloteCodigo)
    {
        return JulgItem::where('l30_numerolote', $numeroloteCodigo)
            ->orderBy('l30_codigo', 'desc')
            ->first();
    }
}
