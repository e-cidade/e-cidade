<?php
namespace App\Repositories\Contracts\Patrimonial\Orcamento;

interface OrcElementoRepositoryInterface{
    public function getDadosElemento(?int $o56_codele, string $anousu):?object;
}
