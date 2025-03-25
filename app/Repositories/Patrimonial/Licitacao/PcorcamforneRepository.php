<?php

namespace App\Repositories\Patrimonial\Licitacao;
use cl_pcorcamforne;
use App\Models\Patrimonial\Licitacao\Pcorcamforne;
use Illuminate\Support\Facades\DB;

class PcorcamforneRepository
{
    private Pcorcamforne $model;

    public function __construct()
    {
        $this->model = new Pcorcamforne();
    }

    /**
     * Retorna todos os registros da tabela Pcorcamforne.
     * 
     * @return \Illuminate\Database\Eloquent\Collection Retorna todos os registros encontrados.
     */
    public function all()
    {
        return Pcorcamforne::all();
    }

    /**
     * Encontra registros no banco de dados com base em uma coluna e condição fornecidas.
     *
     * @param string $column Nome da coluna para aplicar a condição de pesquisa.
     * @param mixed $conditions Valor da condição para a busca.
     * @return Pcorcamforne
     */
    public function find($column, $conditions)
    {
        return Pcorcamforne::where($column, $conditions)->get();
    }

    /**
     * Encontra um registro pelo ID ou gera uma exceção se não encontrado.
     * 
     * @param int $id Identificador único do registro.
     * @return Pcorcamforne Retorna o registro encontrado.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Se o registro não for encontrado.
     */
    public function findId($id)
    {
        return Pcorcamforne::findOrFail($id);
    }

    public function getfornecedoreslicitacao($l20_codigo)
    {
        $clpcorcamforne = new cl_pcorcamforne();
        $sql = $clpcorcamforne->queryfornecedores($l20_codigo);
        return DB::select($sql);
    }

    public function getSupplierAndTheirDataFromCgm($orcamforne)
    {
        return Pcorcamforne::where('pc21_orcamforne', $orcamforne)
            ->join('protocolo.cgm', 'protocolo.cgm.z01_numcgm', '=', 'compras.pcorcamforne.pc21_numcgm')
            ->first();
    }
}

