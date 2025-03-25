<?php

namespace App\Application\Patrimonial\Pareceres;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\ParecerLicitacao\GetParecerLicitacaoByFilters;

class ListagemParecer implements HandleRepositoryInterface{
    private GetParecerLicitacaoByFilters $getParecerLicitacaoByFilters;

    public function __construct(){
        $this->getParecerLicitacaoByFilters = new GetParecerLicitacaoByFilters();
    }

    public function handle(object $data){
        return $this->getParecerLicitacaoByFilters->execute((object)[
            'l200_sequencial'  => $data->l200_sequencial ?? null,
            'l20_codigo'       => $data->l20_codigo ?? null,
            'l20_numero'       => $data->l20_numero ?? null,
            'l20_edital'       => $data->l20_edital ?? null,
            'l200_data'        => $data->l200_data ?? null,
            'l200_tipoparecer' => $data->l200_tipoparecer ?? null,
            'l200_exercicio'   => $data->l200_exercicio ?? null,
            'l20_objeto'       => $data->l20_objeto ?? null,
            'orderable'        => $data->orderable ?? [],
            'search'           => $data->search ?? '',
            'is_contass'       => $data->is_contass,
            'limit'            => (!empty($data->limit) ? $data->limit : 20),
            'offset'           => (!empty($data->offset) ? ($data->offset - 1) : 0),
            'l20_instit'       => $data->instit ?? null
        ]);
    }

}
