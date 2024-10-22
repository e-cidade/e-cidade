<?php
namespace  App\Repositories\Contracts\Patrimonial\PlanoDeContratacao;

use App\Models\Patrimonial\Compras\PcPlanoContratacaoPcPcItem;

interface PlanoContratacaoPcPcItemRepositoryInterface{
    public function save(PcPlanoContratacaoPcPcItem $pcPlanoContratacaoPcPcItem): PcPlanoContratacaoPcPcItem;
    public function findByPlanoContratacao(int $mpc01_codigo): ?array;
    public function delete(PcPlanoContratacaoPcPcItem $pcPlanoContratacaoPcPcItem): void;
    public function getItemsCreatePlano(int $mpc01_codigo): ?array;
    public function getItemRetifica(array $codigos): ?array;
    public function getCodigo(): int;
    public function find(int $mpc01_codigo, int $mpc02_codigo): ?PcPlanoContratacaoPcPcItem;
    public function update(int $mpcpc01_codigo, array $data): PcPlanoContratacaoPcPcItem;
    public function removeByCodigo(int $mpcpc01_codigo): void;
    public function getSendByPlanoContracaoAndItens(int $mpc01_codigo, array $itens): ?array;
    public function getByPlanoContratacaoAndItens(int $mpc01_codigo, array $itens): ?array;
    public function pcPcItemGetByCodMaterAndUnit(int $mpc02_codmater,int $mpc02_un,int $mpc01_pcplanocontratacao_codigo): ?PcPlanoContratacaoPcPcItem;
}
