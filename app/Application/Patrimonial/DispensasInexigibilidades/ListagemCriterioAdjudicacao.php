<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;

class ListagemCriterioAdjudicacao implements HandleRepositoryInterface{
    public function handle(object $data)
    {
        $data = [
            1 => "1 - Desconto Sobre Tabela",
            2 => "2 - Menor Taxa ou Percentual",
            3 => "3 - Outros",
        ];
        return ['data' => $data];
    }
}
