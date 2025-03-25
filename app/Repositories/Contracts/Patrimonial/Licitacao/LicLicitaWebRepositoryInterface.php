<?php
namespace App\Repositories\Contracts\Patrimonial\Licitacao;

interface LicLicitaWebRepositoryInterface{
    public function getDadosByFilter(int $l29_liclicita):?array;
}
