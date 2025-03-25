<?php
namespace App\Repositories\Contracts\Patrimonial\Compras;

interface SolicItemPcMaterRepositoryInterface{
    public function getDadosByCodigoItem(int $pc16_solicitem):?object;
}
