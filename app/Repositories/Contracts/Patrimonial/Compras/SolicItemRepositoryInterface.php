<?php
namespace App\Repositories\Contracts\Patrimonial\Compras;

interface SolicItemRepositoryInterface{
    public function getDadosItens(int $l21_codliclicita, int $pc82_codproc):?array;
}
