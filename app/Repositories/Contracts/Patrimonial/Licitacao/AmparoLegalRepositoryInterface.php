<?php
namespace App\Repositories\Contracts\Patrimonial\Licitacao;

interface AmparoLegalRepositoryInterface{
    public function getDadosByFilter(int $l20_codtipocom):?array;
}
