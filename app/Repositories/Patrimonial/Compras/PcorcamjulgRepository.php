<?php

namespace App\Repositories\Patrimonial\Compras;

use App\Models\Patrimonial\Compras\Pcorcamjulg;

class PcorcamjulgRepository
{
    /**
     * Retorna todos os registros da tabela Pcorcamjulg.
     * 
     * @return \Illuminate\Database\Eloquent\Collection Retorna todos os registros encontrados.
     */
    public function all()
    {
        return Pcorcamjulg::all();
    }

    /**
     * Encontra registros no banco de dados com base em uma coluna e condição fornecidas.
     *
     * @param string $column Nome da coluna para aplicar a condição de pesquisa.
     * @param mixed $conditions Valor da condição para a busca.
     * @return Pcorcamjulg
     */
    public function find($column, $conditions)
    {
        return Pcorcamjulg::where($column, $conditions)->get();
    }

    /**
     * Encontra um registro pelo ID ou gera uma exceção se não encontrado.
     * 
     * @param int $id Identificador único do registro.
     * @return Pcorcamjulg Retorna o registro encontrado.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Se o registro não for encontrado.
     */
    public function findId($id)
    {
        return Pcorcamjulg::findOrFail($id);
    }


    public function insert(array $dados)
    {
        return Pcorcamjulg::insert($dados);
    }

    /**
     * Cria um novo registro de pcorcamjulg no banco de dados.
     *
     * @param array $dados
     * @return Pcorcamjulg
     */
    public function create(array $dados)
    {
        return Pcorcamjulg::create($dados);
    }

    /**
     * Atualiza os dados de um pcorcamjulg existente pelo ID.
     *
     * @param int $id
     * @param array $dados
     * @return Pcorcamjulg
     */
    public function update($id, array $dados)
    {
        $pcorcamjulg = Pcorcamjulg::findOrFail($id);
        $pcorcamjulg->update($dados);
        return $pcorcamjulg;
    }

    /**
     * Exclui registros da tabela pcorcamjulg com base na condição fornecida.
     * 
     * @param string $column Nome da coluna para aplicar a condição de exclusão.
     * @param mixed $conditions Valor da condição para identificar os registros a serem excluídos.
     * @return bool Retorna verdadeiro se a exclusão for bem-sucedida.
     */
    public function delete($column, $conditions)
    {
        $pcorcamjulg = Pcorcamjulg::where($column, $conditions);
        return $pcorcamjulg->delete();
    }

    public function findItemBudget($pcorcamitem)
    {
        return $this->model->where('pc24_orcamitem', $pcorcamitem)->get();
    }

    public function deletePcorcamitemRecords($pcorcamitem)
    {
        $pcorcamjulg = Pcorcamjulg::where('pc24_orcamitem', $pcorcamitem);
        return $pcorcamjulg->delete();
    }
}
