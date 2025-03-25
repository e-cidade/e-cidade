<?php

namespace App\Application\Patrimonial\Licitacoes;

use App\Repositories\Contracts\HandleRepositoryInterface;

class ListagemNaturezaObjeto implements HandleRepositoryInterface {
    public function handle(object $data)
    {
        $data = [
            1 => '1 - Obras e Serviços de Engenharia',
            2 => '2 - Compras e outros serviços',
            3 => '3 - Locação de imóveis',
            4 => '4 - Concessão',
            5 => '5 - Permissão',
            6 => '6 - Alienação de bens',
            7 => '7 - Compras para obras e/ou serviços de engenharia'
        ];
        return ['data' => $data];
    }
}
