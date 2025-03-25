<?php

namespace App\Repositories\Financeiro\Tesouraria;

use App\Domain\Financeiro\Tesouraria\ContaSaltes;
use App\Models\Financeiro\Tesouraria\Saltes;
use App\Repositories\Financeiro\Tesouraria\SaltesRepositoryInterface;
use Illuminate\Support\Facades\DB;

class SaltesRepository implements SaltesRepositoryInterface
{
    private Saltes $model;

    public function __construct()
    {
        $this->model = new Saltes;
    }

    public function saveBySaltes(ContaSaltes $dadosSaltes): ?Saltes
    {

        $dados = [
            "k13_conta"         => $dadosSaltes->getK13Conta()  ,
            "k13_reduz"         => $dadosSaltes->getK13Reduz(),
            "k13_descr"         => $dadosSaltes->getK13Descr(),
            "k13_saldo"         => $dadosSaltes->getK13Saldo(),
            "k13_ident"         => $dadosSaltes->getK13Ident(),
            "k13_vlratu"        => $dadosSaltes->getK13Vlratu(),
            "k13_datvlr"        => $dadosSaltes->getK13Datvlr()->format('Y-m-d'),
            "k13_dtimplantacao" => $dadosSaltes->getK13Dtimplantacao()->format('Y-m-d')
        ];
            if ($dadosSaltes->getK13Limite() === true) {
                $dados = [
                    "k13_limite"        => $dadosSaltes->getK13Limite()->format('Y-m-d')
                ];
            }

        return $this->model->create($dados);
    }

    public function update(int $sequencialsaltes, array $dadossaltes): bool
    {

        return DB::table('saltes')->where('k13_conta',$sequencialsaltes)->update($dadossaltes);
    }

    public function delete(int $sequencialcontaplanoexe): bool
    {

        return DB::table('saltes')
                ->where('k13_reduz',$sequencialcontaplanoexe)
                ->where('k13_conta',$sequencialcontaplanoexe)
                ->delete();
    }

}
