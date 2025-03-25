<?php

namespace App\Repositories\Configuracao\Empenho;

use App\Models\Empenho\EmpAutoriza;
use Illuminate\Database\Capsule\Manager as DB;

class EmpAutorizaRepository
{
    private EmpAutoriza $model;

    public function __construct()
    {
        $this->model = new EmpAutoriza();
    }

    public function getByPrimaryKeyRange($iCodigoEmpenhoInicial, $iCodigoEmpenhoFinal)
    {
        return $this->model->whereBetween('e54_autori', [$iCodigoEmpenhoInicial, $iCodigoEmpenhoFinal])->get()->toArray();
    }

    public function updateDateByIds(array $dados)
    {
        foreach ($dados as $id => $novaDataEmissao) {
            DB::table('empenho.empautoriza')
                ->where('e54_autori', $id)
                ->update(['e54_emiss' => $novaDataEmissao]);
        }
    }

    public function update(int $e54_autori, array $data){
        $oEmpAutoriza = $this->model->findOrFail($e54_autori);
        $oEmpAutoriza->update($data);

        return $oEmpAutoriza;
    }
}
