<?php

namespace App\Repositories\Financeiro\Contabilidade;

use App\Domain\Financeiro\Contabilidade\ContaPlanoConta;
use App\Models\Financeiro\Contabilidade\ConPlanoConta;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoContaRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ContaPlanoContaRepository implements ContaPlanoContaRepositoryInterface
{
    private ConPlanoConta $model;

    public function __construct()
    {
        $this->model = new ConPlanoConta;
    }


    public function saveByContaPlanoConta(ContaPlanoConta $dadosContaPlanoConta): ?ConPlanoConta
    {

        $dados = [
            "c63_codcon"                  => $dadosContaPlanoConta->getC63Codcon(),
            "c63_anousu"                  => $dadosContaPlanoConta->getC63Anousu(),
            "c63_banco"                   => $dadosContaPlanoConta->getC63Banco(),
            "c63_agencia"                 => $dadosContaPlanoConta->getC63Agencia(),
            "c63_conta"                   => $dadosContaPlanoConta->getC63Conta(),
            "c63_dvconta"                 => $dadosContaPlanoConta->getC63Dvconta(),
            "c63_dvagencia"               => $dadosContaPlanoConta->getC63Dvagencia(),
            "c63_identificador"           => $dadosContaPlanoConta->getC63Identificador(),
            "c63_codigooperacao"          => $dadosContaPlanoConta->getC63Codigooperacao(),
            "c63_tipoconta"               => $dadosContaPlanoConta->getC63Tipoconta()
        ];

        return $this->model->create($dados);
    }

    public function update(int $sequencialcontaplanoconta, array $dadosContaPlanoConta): bool
    {

        return DB::table('conplanoconta')->where('c63_codcon',$sequencialcontaplanoconta)->update($dadosContaPlanoConta);
    }

    public function delete(int $sequencialcontaplano): bool
    {
        return DB::table('conplanoconta')->where('c63_codcon',$sequencialcontaplano)->delete();
    }

    public function dataSessao(int $ano, int $instituicao)
    {

          DB::beginTransaction();
          $sql = "
              SELECT fc_putsession('DB_instit', '{$instituicao}');
              SELECT fc_putsession('DB_anousu', '{$ano}');
              SELECT fc_putsession('DB_datausu', '".date("Y-m-d")."');
          ";
          $result = DB::unprepared($sql);

          if (!$result) {
              throw new \Exception("Falha, dados da sessão não informados");
          }
          DB::commit();


    }

}
