<?php
namespace App\Repositories\Contracts\Patrimonial\Orcamento;

interface OrcParametroRepositoryInterface{
    public function getDados(?int $anousu):?object;
}
