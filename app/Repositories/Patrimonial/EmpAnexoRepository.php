<?php

namespace App\Repositories\Patrimonial;

use App\Models\EmpAnexo;
use Illuminate\Database\Capsule\Manager as DB;

class EmpAnexoRepository
{
    private EmpAnexo $model;

    public function __construct()
    {
        $this->model = new EmpAnexo();
    }

    /**
     *
     * @param array $aDadosEmpAnexo - dados para insert na tabela EmpAnexo
     * @return bool
     */

    public function create($aDadosEmpAnexo)
    {
        $aDadosEmpAnexo['e100_sequencial'] = $this->model->getNextval();

        return DB::table('empanexo')->insert($aDadosEmpAnexo);
    }

    /**
     * Atualiza informações do Anexo
     *
     * @param int $iSequencialEmpAnexo - Sequencial do Anexo
     * @param array $aDadosEmpAnexo - Dados do Anexo 
     * @return bool
     */

    public function update($iSequencialEmpAnexo, $aDadosEmpAnexo)
    {

        $empAnexo = $this->model->findOrFail($iSequencialEmpAnexo);
        $empAnexo->fill($aDadosEmpAnexo);
        $empAnexo->save();
        return $empAnexo;

    }

    /**
     * Deleta o Anexo informado
     *
     * @param int $iSequencialEmpAnexo - Sequencial do Anexo
     * @return bool
     */

    public function delete($iCodigoAnexo)
    {
        $empAnexo = $this->model->find($iCodigoAnexo);

        return $empAnexo->delete();
    }

    /**
     * Retorna todos os Anexos do Empenho Informado
     *
     * @param int $iNumeroEmpenho - Número do Empenho
     * @return EmpAnexo
     */

    public function getAnexosEmpenho($iNumeroEmpenho)
    {
        return DB::select("select * from empanexo inner join tipoanexo on e100_tipoanexo = l213_sequencial where e100_empenho = $iNumeroEmpenho");
    }

    /**
     * Retorna o Anexo do sequencial Informado
     *
     * @param int $iSequencialEmpAnexo - Sequencial do Anexo
     * @return EmpAnexo
     */

    public function getAnexo($iSequencialEmpAnexo)
    {
        return $this->model->findOrFail($iSequencialEmpAnexo);
    }

    /**
     * Retorna os Anexos dos sequenciais informados
     *
     * @param string $sSequencialEmpAnexo - Sequenciais dos Anexos no formato 'x,x,x'
     * @return ResultSet
     */

    public function getAnexos($sSequencialEmpAnexo)
    {
        return DB::select("select * from empanexo where e100_sequencial in ($sSequencialEmpAnexo)");
    }
}
