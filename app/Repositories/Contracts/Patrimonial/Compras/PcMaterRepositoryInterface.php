<?php
namespace App\Repositories\Contracts\Patrimonial\Compras;

interface PcMaterRepositoryInterface{
    public function search(?string $search, int $anousu);
}
