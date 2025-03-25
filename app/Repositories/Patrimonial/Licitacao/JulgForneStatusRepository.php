<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\Julgfornestatus;

class JulgForneStatusRepository
{
    /**
     * Retorna todos os registros da tabela Julgfornestatus.
     * 
     * @return \Illuminate\Database\Eloquent\Collection Retorna todos os registros encontrados.
     */
    public function all()
    {
        return Julgfornestatus::all();
    }

    /**
     * Encontra registros no banco de dados com base em uma coluna e condição fornecidas.
     *
     * @param string $column Nome da coluna para aplicar a condição de pesquisa.
     * @param mixed $conditions Valor da condição para a busca.
     * @return Julgfornestatus
     */
    public function find($column, $conditions)
    {
        return Julgfornestatus::where($column, $conditions)->get();
    }

    /**
     * Encontra um registro pelo ID ou gera uma exceção se não encontrado.
     * 
     * @param int $id Identificador único do registro.
     * @return Julgfornestatus Retorna o registro encontrado.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Se o registro não for encontrado.
     */
    public function findId($id)
    {
        return Julgfornestatus::findOrFail($id);
    }

    /**
     * Cria um novo registro de status de fornecedor.
     *
     * @param array $dados
     * @return JulgForneStatus
     */
    public function create(array $dados)
    {
        return JulgForneStatus::create($dados);
    }

    /**
     * Atualiza um registro de status de fornecedor existente pelo ID.
     *
     * @param int $id
     * @param array $dados
     * @return JulgForneStatus
     */
    public function update($id, array $dados)
    {
        $status = JulgForneStatus::findOrFail($id);
        $status->update($dados);
        return $status;
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
        $julgForne = JulgForneStatus::where($column, $conditions);
        return $julgForne->delete();
    }

    /**
     * Busca um status de fornecedor pelo rótulo.
     *
     * @param string $rotulo
     * @return JulgForneStatus|null
     */
    public function findLabel($rotulo)
    {
        return JulgForneStatus::where('l35_label', $rotulo)->first();
    }
}
