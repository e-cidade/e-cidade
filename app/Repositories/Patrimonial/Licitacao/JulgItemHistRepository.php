<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\Julgitemhist;

class JulgItemHistRepository
{
    /**
     * Retorna todos os registros da tabela JulgItemHist.
     * 
     * @return \Illuminate\Database\Eloquent\Collection Retorna todos os registros encontrados.
     */
    public function all()
    {
        return JulgItemHist::all();
    }

    /**
     * Encontra registros no banco de dados com base em uma coluna e condição fornecidas.
     *
     * @param string $column Nome da coluna para aplicar a condição de pesquisa.
     * @param mixed $conditions Valor da condição para a busca.
     * @return JulgItemHist
     */
    public function find($column, $conditions)
    {
        return JulgItemHist::where($column, $conditions)->get();
    }

    /**
     * Encontra um registro pelo ID ou gera uma exceção se não encontrado.
     * 
     * @param int $id Identificador único do registro.
     * @return JulgItemHist Retorna o registro encontrado.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Se o registro não for encontrado.
     */
    public function findId($id)
    {
        return JulgItemHist::findOrFail($id);
    }

    /**
     * Cria um novo registro de histórico de item.
     *
     * @param array $dados
     * @return JulgItemHist
     */
    public function create(array $dados)
    {
        return JulgItemHist::create($dados);
    }

    /**
     * Atualiza um registro de histórico de item existente pelo ID.
     *
     * @param int $id
     * @param array $dados
     * @return JulgItemHist
     */
    public function update($id, array $dados)
    {
        $historico = JulgItemHist::findOrFail($id);
        $historico->update($dados);
        return $historico;
    }

    /**
     * Exclui registros da tabela JulgForneStatus com base na condição fornecida.
     * 
     * @param string $column Nome da coluna para aplicar a condição de exclusão.
     * @param mixed $conditions Valor da condição para identificar os registros a serem excluídos.
     * @return bool Retorna verdadeiro se a exclusão for bem-sucedida.
     */
    public function delete($column, $conditions)
    {
        $julgForne = JulgItemHist::where($column, $conditions);
        return $julgForne->delete();
    }
}
