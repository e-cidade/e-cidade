<?php
namespace App\Repositories\Contracts\Patrimonial\Licitacao;

interface CflicitaRepositoryInterface{
    public function getDadosByFilter(int $l03_instit, ?array $l03_pctipocompratribunal):?array;
}
