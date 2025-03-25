<?php
namespace App\Repositories\Contracts\Patrimonial\Licitacao;

interface LicObrasRepositoryInterface{
    public function getDadosByFilter(int $obr01_licitacao):?array;
}
