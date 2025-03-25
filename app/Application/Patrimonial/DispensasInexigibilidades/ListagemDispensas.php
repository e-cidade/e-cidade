<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicita\GetLicLicitaByFilters;

class ListagemDispensas implements HandleRepositoryInterface{

    private GetLicLicitaByFilters $getLicLicitaByFilters;

    public function __construct()
    {
        $this->getLicLicitaByFilters = new GetLicLicitaByFilters();
    }

    public function handle(object $data)
    {
        return $this->getLicLicitaByFilters->execute((object)[
            'l20_codigo'     => $data->l20_codigo ?? null,
            'l20_numero'     => $data->l20_numero ?? null,
            'l20_edital'     => $data->l20_edital ?? null,
            'l20_codtipocom' => $data->l20_codtipocom ?? null,
            'l20_anousu'     => $data->l20_anousu ?? null,
            'l20_nroedital'  => $data->l20_nroedital ?? null,
            'l20_datacria'   => $data->l20_datacria ?? null,
            'l20_objeto'     => $data->l20_objeto ?? null,
            'l08_sequencial' => $data->l08_sequencial ?? null,
            'orderable'      => $data->orderable ?? [],
            'search'         => $data->search ?? '',
            'is_contass'     => $data->is_contass,
            'limit'          => (!empty($data->limit) ? $data->limit : 20),
            'offset'         => (!empty($data->offset) ? ($data->offset - 1) : 0),
            'modalidades'    => [100, 101, 102, 103],
            'l20_instit'     => $data->instit ?? null
        ]);
    }
}
