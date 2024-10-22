<?php
namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;

class Exercicio implements HandleRepositoryInterface{
    public function handle($data)
    {
        $anoInicio = 2020;
        $anoAtual = date('Y');
        $anos = [];
        for($ano = $anoInicio; $ano <= ($anoAtual + ($data->loadmore ?: 0)); $ano++){
            $anos[$ano] = $ano;
        }

        return ['anos' => $anos];
    }
}
