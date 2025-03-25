<?php
namespace App\Repositories\Contracts\Patrimonial\Compras;

interface PcParamRepositoryInterface{
    public function getDados(?int $instit):?object;
}
