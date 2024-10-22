<?php
namespace  App\Repositories\Contracts\Patrimonial\PlanoDeContratacao;

use App\Models\Patrimonial\Compras\PcPlanoContratacao;

interface PcPlanoContracaoRepositoryInterface
{
    public function save(PcPlanoContratacao $pcPlanoContratacao): PcPlanoContratacao;
    public function getCodigo(): int;
    public function getByAnoUnidade(int $ano, int $unidade): array;
    public function getPlanoContratacaoByCodigo(int $mpc01_codigo): PcPlanoContratacao;
    public function update(int $mpc01_codigo, array $data): PcPlanoContratacao;
}
