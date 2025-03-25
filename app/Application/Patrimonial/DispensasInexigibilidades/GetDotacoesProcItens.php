<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\PcParam\GetDotacoesProcItensService;

class GetDotacoesProcItens implements HandleRepositoryInterface{

    private GetDotacoesProcItensService $getDotacoesProcItensService;

    public function __construct()
    {
        $this->getDotacoesProcItensService = new GetDotacoesProcItensService();
    }

    public function handle(object $data)
    {
        return $this->getDotacoesProcItensService->execute((object)[
            'l20_codigo' => $data->l20_codigo,
            'anousu'     => $data->anousu,
            'limit'      => (!empty($data->limit) ? $data->limit : 20),
            'offset'     => (!empty($data->offset) ? ($data->offset - 1) : 0),
            'isPaginate' => $data->isPaginate
        ]);
    }
}
