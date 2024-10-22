<?php

namespace App\Services\Patrimonial\PlanoContratacao;

use App\Models\Patrimonial\Compras\PcpcItem;
use App\Repositories\Patrimonial\PlanoContratacao\PcPcItemRepository;

class PcPlanoContratacaoItemService {
    private PcPcItemRepository $pcPcItem;

    public function __construct()
    {
        $this->pcPcItem = new PcPcItemRepository();
    }

    public function create(
        int $mpc02_codmater,
        ?string $mpc02_datap,
        ?int $mpc02_categoria,
        string $mpc02_qtdd = "",
        string $mpc02_vlrunit = "",
        string $mpc02_vlrtotal = "",
        ?int $mpc02_un,
        int $mpc02_depto,
        ?int $mpc02_catalogo,
        ?int $mpc02_tproduto,
        ?int $mpc02_subgrupo
    ){

        $pcPlanoContratacaoItem = new PcpcItem(
            [
                'mpc02_codigo' => $this->pcPcItem->getCodigo(),
                'mpc02_codmater' => $mpc02_codmater,
                'mpc02_datap' => !empty($mpc02_datap) ? date('Y-m-d', strtotime(str_replace('/', '-', $mpc02_datap))) : null,
                'mpc02_categoria' => $mpc02_categoria,
                'mpc02_qtdd' => $mpc02_qtdd,
                'mpc02_vlrunit' => !empty($mpc02_vlrunit) ? $this->convertFloat($mpc02_vlrunit) : 0.000,
                'mpc02_vlrtotal' => !empty($mpc02_vlrtotal) ? $this->convertFloat($mpc02_vlrtotal) : 0.0000,
                'mpc02_un' => $mpc02_un,
                'mpc02_depto' => $mpc02_depto,
                'mpc02_catalogo' => $mpc02_catalogo,
                'mpc02_tproduto' => $mpc02_tproduto,
                'mpc02_subgrupo' => $mpc02_subgrupo
            ]
        );

        return $this->pcPcItem->save($pcPlanoContratacaoItem);
    }

    private function convertFloat($number){
        $number = str_replace(',', '.', str_replace('.', '', $number));
        return number_format((float)$number, 4, '.', '');
    }

    public function getAllByExercicioPrevisao(int $mpc01_codigo, int $limit = 15, int $offset = 0){
        return $this->pcPcItem->getByItens($mpc01_codigo, $limit, $offset);
    }

    public function getAllItensLicLicita(int $exercicio, int $instit, int $mpc01_codigo, int $limit = 15, int $offset = 0){
        return $this->pcPcItem->getItensPlanoContratacao($exercicio, $instit, $mpc01_codigo, $limit, $offset);
    }

    public function getByCodigo(int $mpc02_codigo, int $mpc01_codigo){
        return $this->pcPcItem->getByCodigo($mpc02_codigo, $mpc01_codigo)->toArray();
    }

    public function update(
        int $mpc02_codigo,
        int $mpc02_codmater,
        ?string $mpc02_datap,
        ?int $mpc02_categoria,
        string $mpc02_qtdd = "",
        string $mpc02_vlrunit = "",
        string $mpc02_vlrtotal = "",
        ?int $mpc02_un,
        int $mpc02_depto,
        ?int $mpc02_catalogo,
        ?int $mpc02_tproduto,
        ?int $mpc02_subgrupo
    ){
        return $this->pcPcItem->update(
            $mpc02_codigo,
            [
                'mpc02_codmater' => $mpc02_codmater,
                'mpc02_datap' => !empty($mpc02_datap) ? date('Y-m-d', strtotime(str_replace('/', '-', $mpc02_datap))) : null,
                'mpc02_categoria' => $mpc02_categoria,
                'mpc02_qtdd' => $mpc02_qtdd,
                'mpc02_vlrunit' => !empty($mpc02_vlrunit) ? $this->convertFloat($mpc02_vlrunit) : 0.000,
                'mpc02_vlrtotal' => !empty($mpc02_vlrtotal) ? $this->convertFloat($mpc02_vlrtotal) : 0.0000,
                'mpc02_un' => $mpc02_un,
                'mpc02_depto' => $mpc02_depto,
                'mpc02_catalogo' => $mpc02_catalogo,
                'mpc02_tproduto' => $mpc02_tproduto,
                'mpc02_subgrupo' => $mpc02_subgrupo
            ]
        );
    }

    public function delete(int $mpc02_codigo){
        return $this->pcPcItem->delete($mpc02_codigo);
    }

}
