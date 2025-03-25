<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;;

class ListagemCategoriaProcesso implements HandleRepositoryInterface{
    public function handle(object $data)
    {
        $data = [
            1  => "1 - Cessão",
            2  => "2 - Compras",
            3  => "3 - Informática (TIC)",
            4  => "4 - Internacional",
            5  => "5 - Locação Imóveis",
            6  => "6 - Mão de Obra",
            7  => "7 - Obras",
            8  => "8 - Serviços",
            9  => "9 - Serviços de Engenharia",
            10 => "10 - Serviços de Saúde"
        ];
        return ['data' => $data];
    }
}
