<?php
namespace  App\Repositories\Contracts\Patrimonial\PlanoDeContratacao;

use App\Models\Patrimonial\Compras\PcPlanoContratacaoIntegracao;

interface PcPlanoContratacaoIntegracaoInterface
{
    public function save(PcPlanoContratacaoIntegracao $pcPlanoContratacao): PcPlanoContratacaoIntegracao;
    public function getCodigo(): int;
}
