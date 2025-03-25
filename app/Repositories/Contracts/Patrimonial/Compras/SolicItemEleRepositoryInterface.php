<?php
namespace App\Repositories\Contracts\Patrimonial\Compras;

interface SolicItemEleRepositoryInterface{
    public function getDadosCodEle(int $pc18_solicitem):?object;
}
