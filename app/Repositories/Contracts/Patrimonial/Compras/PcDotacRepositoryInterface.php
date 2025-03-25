<?php
namespace App\Repositories\Contracts\Patrimonial\Compras;

interface PcDotacRepositoryInterface{
    public function getDotacoesProcItens(int $l20_codigo, string $anousu, int $limit = 15, int $offset = 0, ?bool $isPaginate = true):?array;
}
