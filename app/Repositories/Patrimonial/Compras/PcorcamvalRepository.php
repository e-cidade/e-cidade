<?php

namespace App\Repositories\Patrimonial\Compras;

use App\Models\Patrimonial\Compras\Pcorcamval;
use Illuminate\Support\Facades\DB;

class PcorcamvalRepository
{
    private Pcorcamval $model;

    /**
     * Construtor da classe PcorcamvalRepository.
     * Inicializa o modelo Pcorcamval que será utilizado nas operações de banco de dados.
     */
    public function __construct()
    {
        $this->model = new Pcorcamval();
    }

    /**
     * Encontra registros no banco de dados com base em uma coluna e condição fornecidas.
     * 
     * @param string $column Nome da coluna para aplicar a condição de pesquisa.
     * @param mixed $conditions Valor da condição para a busca.
     * @return \Illuminate\Database\Eloquent\Collection Conjunto de resultados encontrados.
     */
    public function find($column, $conditions)
    {
        return $this->model->where($column, $conditions)->get();
    }

    /**
     * Insere dados na tabela Pcorcamval.
     * 
     * @param array $dados Dados a serem inseridos na tabela.
     * @return bool Retorna verdadeiro em caso de sucesso ou falso em caso de falha.
     */
    public function insertData($dados)
    {
        return $this->model->insert($dados);
    }

    /**
     * Cria um novo registro na tabela Pcorcamval.
     * 
     * @param array $dados Dados a serem inseridos no novo registro.
     * @return Pcorcamval Retorna a instância do modelo Pcorcamval criado.
     */
    public function insert($dados): Pcorcamval
    {
        return $this->model->create($dados);
    }

    /**
     * Atualiza um registro existente na tabela Pcorcamval.
     * 
     * @param int $pc23_orcamitem Identificador do item orçamentário a ser atualizado.
     * @param array $dados Dados a serem atualizados no registro.
     * @return bool Retorna verdadeiro se a atualização for bem-sucedida, falso caso contrário.
     */
    public function update(int $pc23_orcamitem, array $dados): bool
    {
        return DB::table('pcorcamval')->where('pc23_orcamitem',$pc23_orcamitem)->where('pc23_orcamforne',$dados->pc23_orcamforne)->update($dados);
    }

    /**
     * Exclui um registro da tabela Pcorcamval.
     * 
     * @param int $pc23_orcamitem Identificador do item orçamentário a ser excluído.
     * @return bool Retorna verdadeiro se a exclusão for bem-sucedida, falso caso contrário.
     */
    public function excluir(int $pc23_orcamitem): bool
    {
        $sql = "DELETE FROM pcorcamval WHERE pc23_orcamitem IN ($pc23_orcamitem)";
        return DB::statement($sql);
    }

    /**
     * Exclui registros da tabela Pcorcamval com base na coluna e condição fornecidas.
     * 
     * Este método permite excluir um ou mais registros da tabela Pcorcamval, 
     * com base em uma condição especificada para uma coluna. Ele utiliza o método 
     * `where` do Eloquent para filtrar os registros a serem excluídos e, em seguida, 
     * executa a exclusão.
     * 
     * @param string $column Nome da coluna para aplicar a condição de exclusão.
     * @param mixed $conditions Valor da condição para identificar os registros a serem excluídos.
     * @return bool Retorna verdadeiro se a exclusão for bem-sucedida, falso caso contrário.
     */
    public function delete($column, $conditions)
    {
        $pcorcamval = $this->model->where($column, $conditions);
        return $pcorcamval->delete();
    }
}
