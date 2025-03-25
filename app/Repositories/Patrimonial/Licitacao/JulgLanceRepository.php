<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\Julglance;

class JulgLanceRepository
{
    /**
     * Retorna todos os registros da tabela Julglance.
     * 
     * @return \Illuminate\Database\Eloquent\Collection Retorna todos os registros encontrados.
     */
    public function all()
    {
        return Julglance::all();
    }

    /**
     * Encontra registros no banco de dados com base em uma coluna e condição fornecidas.
     *
     * @param string $column Nome da coluna para aplicar a condição de pesquisa.
     * @param mixed $conditions Valor da condição para a busca.
     * @return Julglance
     */
    public function find($column, $conditions)
    {
        return Julglance::where($column, $conditions)->get();
    }

    /**
     * Encontra um registro pelo ID ou gera uma exceção se não encontrado.
     * 
     * @param int $id Identificador único do registro.
     * @return Julglance Retorna o registro encontrado.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Se o registro não for encontrado.
     */
    public function findId($id)
    {
        return Julglance::findOrFail($id);
    }

    /**
     * Cria um novo lance no banco de dados.
     *
     * @param array $dados
     * @return Julglance
     */
    public function create(array $dados)
    {
        return Julglance::create($dados);
    }

    /**
     * Atualiza os dados de um lance existente pelo ID.
     *
     * @param int $id
     * @param array $dados
     * @return Julglance
     */
    public function update($id, array $dados)
    {
        $julglance = Julglance::findOrFail($id);
        $julglance->update($dados);
        return $julglance;
    }

    /**
     * Exclui registros da tabela Julglance com base na condição fornecida.
     * 
     * @param string $column Nome da coluna para aplicar a condição de exclusão.
     * @param mixed $conditions Valor da condição para identificar os registros a serem excluídos.
     * @return bool Retorna verdadeiro se a exclusão for bem-sucedida.
     */
    public function delete($column, $conditions)
    {
        $julglance = Julglance::where($column, $conditions);
        return $julglance->delete();
    }

    /**
     * Remove um lance pelo item de orçamento.
     *
     * @param string $codigoItemOrcamento
     * @return Julglance|null
     */
    public function deleteBidItem($codigoItemOrcamento)
    {
        return Julglance::where('l32_julgitem', $codigoItemOrcamento)->delete();
    }

    /**
     * Busca o último lance registrado para um item de orçamento.
     *
     * @param string $codigoItemOrcamento
     * @return Julglance|null
     */
    public function findBidItem($codigoItemOrcamento)
    {
        return Julglance::where('l32_julgitem', $codigoItemOrcamento)->get();
    }

    /**
     * Busca o último lance registrado para um item de orçamento.
     *
     * @param string $codigoItemOrcamento
     * @return Julglance|null
     */
    public function findLastBid($codigoItemOrcamento)
    {
        return Julglance::where('l32_julgitem', $codigoItemOrcamento)
            ->orderBy('l32_codigo', 'desc')
            ->first();
    }

    /**
     * Busca o último lance registrado para um item de orçamento.
     *
     * @param string $codigoItemOrcamento
     * @return Julglance|null
     */
    public function findLastBidNotNull($codigoItemOrcamento)
    {
        return Julglance::where('l32_julgitem', $codigoItemOrcamento)
            ->whereNotNull('l32_lance')
            ->orderBy('l32_codigo', 'desc')
            ->first();
    }

    /**
     * Busca o último lance de um fornecedor específico para um item de orçamento.
     *
     * @param string $codigoFornecedor
     * @param string $codigoItemOrcamento
     * @return Julglance|null
     */
    public function findLastBidSupplier($codigoFornecedor, $codigoItemOrcamento)
    {
        return Julglance::where('l32_julgitem', $codigoItemOrcamento)
            ->where('l32_julgforne', $codigoFornecedor)
            ->orderBy('l32_codigo', 'desc')
            ->first();
    }

    /**
     * Busca o último lance de um fornecedor específico para um item de orçamento.
     *
     * @param string $codigoFornecedor
     * @param string $codigoItemOrcamento
     * @return Julglance|null
     */
    public function findLastBidSupplierNotNull($codigoFornecedor, $codigoItemOrcamento)
    {
        return Julglance::where('l32_julgitem', $codigoItemOrcamento)
            ->where('l32_julgforne', $codigoFornecedor)
            ->whereNotNull('l32_lance')
            ->orderBy('l32_codigo', 'desc')
            ->first();
    }
}
