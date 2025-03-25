<?php
namespace App\Repositories\Contracts\Patrimonial\Licitacao;

interface LicLicitaParamRepositoryInterface{
    public function getDados(?int $instit):?object;
}
