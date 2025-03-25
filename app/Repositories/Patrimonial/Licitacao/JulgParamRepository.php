<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\Julgparam;

class JulgParamRepository
{
    /**
     * Retorna todos os registros da tabela Julgparam.
     * 
     * @return \Illuminate\Database\Eloquent\Collection Retorna todos os registros encontrados.
     */
    public function all()
    {
        return Julgparam::all();
    }

    /**
     * Encontra registros no banco de dados com base em uma coluna e condição fornecidas.
     *
     * @param string $column Nome da coluna para aplicar a condição de pesquisa.
     * @param mixed $conditions Valor da condição para a busca.
     * @return Julgparam
     */
    public function find($column, $conditions)
    {
        return Julgparam::where($column, $conditions)->get();
    }

    /**
     * Encontra um registro pelo ID ou gera uma exceção se não encontrado.
     * 
     * @param int $id Identificador único do registro.
     * @return Julgparam Retorna o registro encontrado.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Se o registro não for encontrado.
     */
    public function findId($id)
    {
        return Julgparam::findOrFail($id);
    }

    /**
     * Cria um novo registro no histórico de fornecedor.
     *
     * @param array $dados
     * @return Julgparam
     */
    public function create(array $dados)
    {
        return Julgparam::create($dados);
    }

    /**
     * Atualiza um registro de histórico de fornecedor existente pelo ID.
     *
     * @param int $id
     * @param array $dados
     * @return Julgparam
     */
    public function update($id, array $dados)
    {
        $julgParam = Julgparam::findOrFail($id);
        $julgParam->update($dados);
        return $julgParam;
    }

    /**
     * Exclui registros da tabela Julgparam com base na condição fornecida.
     * 
     * @param string $column Nome da coluna para aplicar a condição de exclusão.
     * @param mixed $conditions Valor da condição para identificar os registros a serem excluídos.
     * @return bool Retorna verdadeiro se a exclusão for bem-sucedida.
     */
    public function delete($column, $conditions)
    {
        $julgParam = Julgparam::where($column, $conditions);
        return $julgParam->delete();
    }

    /**
     * Encontra um parâmetro de julgamento com base na instituição.
     *
     * Esta função consulta o modelo Julgparam para localizar o primeiro registro
     * que corresponde à instituição fornecida.
     *
     * @param string $instit O código ou identificador da instituição.
     * 
     * @return \App\Models\Julgparam|null Retorna uma instância de Julgparam se encontrado, 
     * ou `null` caso não exista um registro correspondente.
     */
    public function findParamInstit($instit)
    {
        return Julgparam::where('l13_instit', $instit)->first();
    }
}
