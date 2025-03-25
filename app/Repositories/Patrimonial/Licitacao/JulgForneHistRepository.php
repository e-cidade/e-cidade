<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\Julgfornehist;

class JulgForneHistRepository
{
    /**
     * Encontra registros no banco de dados com base em uma coluna e condição fornecidas.
     *
     * @param string $column Nome da coluna para aplicar a condição de pesquisa.
     * @param mixed $conditions Valor da condição para a busca.
     * @return JulgForneHist
     */
    public function find($column, $conditions)
    {
        return Julgfornehist::where($column, $conditions)->get();
    }

    /**
     * Cria um novo registro no histórico de fornecedor.
     *
     * @param array $dados
     * @return JulgForneHist
     */
    public function create(array $dados)
    {
        return JulgForneHist::create($dados);
    }

    /**
     * Atualiza um registro de histórico de fornecedor existente pelo ID.
     *
     * @param int $id
     * @param array $dados
     * @return JulgForneHist
     */
    public function update($id, array $dados)
    {
        $julgForneHist = JulgForneHist::findOrFail($id);
        $julgForneHist->update($dados);
        return $julgForneHist;
    }

    /**
     * Exclui registros da tabela JulgForneHist com base na condição fornecida.
     * 
     * @param string $column Nome da coluna para aplicar a condição de exclusão.
     * @param mixed $conditions Valor da condição para identificar os registros a serem excluídos.
     * @return bool Retorna verdadeiro se a exclusão for bem-sucedida.
     */
    public function delete($column, $conditions)
    {
        $julgForneHist = JulgForneHist::where($column, $conditions);
        return $julgForneHist->delete();
    }

    public function deleteIn($column, array $conditions)
    {
        $julgForneHist = JulgForneHist::whereIn($column, $conditions);
        return $julgForneHist->delete();
    }
}
