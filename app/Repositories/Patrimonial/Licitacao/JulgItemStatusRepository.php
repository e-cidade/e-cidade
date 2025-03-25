<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\Julgitemstatus;

class JulgItemStatusRepository
{
    /**
     * Retorna todos os registros da tabela JulgItem.
     * 
     * @return \Illuminate\Database\Eloquent\Collection Retorna todos os registros encontrados.
     */
    public function all()
    {
        return JulgItemStatus::all();
    }

    /**
     * Encontra registros no banco de dados com base em uma coluna e condição fornecidas.
     *
     * @param string $column Nome da coluna para aplicar a condição de pesquisa.
     * @param mixed $conditions Valor da condição para a busca.
     * @return JulgItemStatus
     */
    public function find($column, $conditions)
    {
        return JulgItemStatus::where($column, $conditions)->get();
    }

    /**
     * Encontra um registro pelo ID ou gera uma exceção se não encontrado.
     * 
     * @param int $id Identificador único do registro.
     * @return JulgItemStatus Retorna o registro encontrado.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Se o registro não for encontrado.
     */
    public function findId($id)
    {
        return JulgItemStatus::findOrFail($id);
    }

    /**
     * Cria um novo status de item.
     *
     * @param array $dados
     * @return JulgItemStatus
     */
    public function create(array $dados)
    {
        return JulgItemStatus::create($dados);
    }

    /**
     * Atualiza um status de item existente pelo ID.
     *
     * @param int $id
     * @param array $dados
     * @return JulgItemStatus
     */
    public function update($id, array $dados)
    {
        $julgItemStatus = JulgItemStatus::findOrFail($id);
        $julgItemStatus->update($dados);
        return $julgItemStatus;
    }

    /**
     * Exclui registros da tabela JulgItemStatus com base na condição fornecida.
     * 
     * @param string $column Nome da coluna para aplicar a condição de exclusão.
     * @param mixed $conditions Valor da condição para identificar os registros a serem excluídos.
     * @return bool Retorna verdadeiro se a exclusão for bem-sucedida.
     */
    public function delete($column, $conditions)
    {
        $julgItemStatus = JulgItemStatus::where($column, $conditions);
        return $julgItemStatus->delete();
    }

    /**
     * Busca um status de item pelo rotulo.
     *
     * @param string $rotulo
     * @return JulgItemStatus|null
     */
    public function findLabel($rotulo)
    {
        return JulgItemStatus::where('l31_label', $rotulo)->first();
    }
}
