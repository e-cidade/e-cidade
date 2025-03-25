<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;

class ListagemRegimeExecucao implements HandleRepositoryInterface{
    public function handle(object $data)
    {
        $result = [
            1 => "1 - Empreitada por Preço Global",
            2 => "2 - Empreitada por Preço Unitário",
            3 => "3 - Empreitada Integral",
            4 => "4 - Tarefa",
            // 5 => "5 - Execução Direta",
            6 => "6 - Contratação integrada",
            7 => "7 - Contratação semi-integrada",
            8 => "8 - Fornecimento e prestação de serviço associado"
        ];

        if(!empty($data->l20_leidalicitacao) && $data->l20_leidalicitacao == 2){
            unset($result[6]);
            unset($result[7]);
            unset($result[8]);
        }

        return ['data' => $result];
    }
}
