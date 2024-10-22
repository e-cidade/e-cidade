<?php
namespace  App\Repositories\Contracts\Patrimonial\PlanoDeContratacao;

use App\Models\Patrimonial\Compras\PcpcItem;

interface PcPcItemRepositoryInterface{
    public function save(PcpcItem $pcPlanoContratacao): PcpcItem;
    public function getCodigo(): int;
    public function getByItens(int $mpc01_codigo): array;
    public function getByCodigo(int $mpc02_codigo): PcpcItem;
    public function getByCodigoAndPlanoContratacao(int $mpc02_codigo, ?int $mpc01_codigo): PcpcItem;
    public function update(int $mpc02_codigo, array $data): PcpcItem;
    public function delete(int $mpc02_codigo): void;
}
