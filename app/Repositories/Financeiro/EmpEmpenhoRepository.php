<?php
namespace App\Repositories\Financeiro;

use App\Models\EmpEmpenho;
use Illuminate\Support\Facades\DB;

class EmpEmpenhoRepository
{
    /**
     * @var EmpEmpenho
     */
    private EmpEmpenho $model;

    public function __construct()
    {
        $this->model = new EmpEmpenho();
    }

    /**
     * @param string $code
     * @param string $ano
     * @return integer
     */
    public function getCodigoEmpenho(string $codemp, string $ano): int
    {
       $empenho = $this->model->where('e60_codemp', $codemp)
            ->where('e60_anousu', $ano)
            ->first(['e60_numemp']);

        if (empty($empenho->e60_numemp)) {
            throw new \Exception('Empenho não encontrado');
        }

        return (int)$empenho->e60_numemp;
    }

    /**
     * @param string $codemp
     * @param string $ano
     * @param array $campos
     * @return EmpEmpenho|null
     */
    public function getEmpenho(string $codemp, string $ano, array $campos = ['*']): ?EmpEmpenho
    {
        return $this->model->where('e60_codemp', $codemp)
            ->where('e60_anousu', $ano)
            ->first($campos);
    }

    /**
     *
     * @param integer $e60NumEmp
     * @param array $data
     * @return boolean
     */
    public function atualizarEmpenho(int $e60NumEmp, array $dados ): bool
    {
        return DB::table('empempenho')->where('e60_numemp',$e60NumEmp)->update($dados);
    }

    
    public function update(int $e60_numemp, array $data){
        $oEmpEmpenho = $this->model->findOrFail($e60_numemp);
        $oEmpEmpenho->update($data);

        return $oEmpEmpenho;
    }
}
