<?php

namespace App\Services\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\Licproposta;
use App\Models\Patrimonial\Licitacao\Licpropostavinc;
use App\Repositories\Patrimonial\Licitacao\LicpropostavincRepository;
use Illuminate\Database\Capsule\Manager as DB;

class LicpropostavincService
{
    private $licpropostavincRepository;
    private Licpropostavinc $model;

    public function __construct()
    {
        $this->licpropostavincRepository = new LicpropostavincRepository();
    }

    public function salvarItensLicpropostaVinc ($dados): Licpropostavinc
    {
        $alicpropostaVinc = [];
        $alicpropostaVinc['l223_liclicita'] = $dados->l20_codigo;
        $alicpropostaVinc['l223_fornecedor'] = $dados->l224_forne;
        return $this->licpropostavincRepository->insert($alicpropostaVinc);
    }

    public function getLicpropostavinc(int $l20_codigo, int $l224_forne)
    {

        $sql = "select * from licpropostavinc where l223_liclicita = {$l20_codigo} and l223_fornecedor = {$l224_forne}";
        $rslicpropostavinc = DB::select($sql);
        return $rslicpropostavinc[0];
      
    }

    public function deletaProposta($l223_codigo)
    {
        return $this->licpropostavincRepository->delete($l223_codigo);
    }

}
