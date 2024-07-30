<?php

namespace App\Services\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\Liclicitemlote;
use App\Repositories\Patrimonial\Compras\PcprocitemRepository;
use App\Repositories\Patrimonial\Licitacao\LiclicitemLoteRepository;
use Illuminate\Database\Capsule\Manager as DB;

class LiclicitemLoteService
{
    private $licilicitemloteRepository;
    private $pcprocitemRepository;

    public function __construct()
    {
        $this->licilicitemloteRepository = new LiclicitemLoteRepository();
        $this->pcprocitemRepository = new PcprocitemRepository();
    }


    public function salvarItensLicitacaoLote ($dados):LiclicitemLote
    {
        $aliclicitemlote = [];
        $l04_descricao = 'LOTE_AUTOITEM_'.$dados->pc81_codprocitem;
        $aliclicitemlote['l04_liclicitem'] = $dados->l04_liclicitem;
        $aliclicitemlote['l04_descricao'] = $l04_descricao;
        return $this->licilicitemloteRepository->insert($aliclicitemlote);
    }

    public function excluirItensLote($dados)
    {
        $itens = $this->pcprocitemRepository->getItensProcOnLiclicitem($dados->processo,$dados->l20_codigo);
        foreach ($itens as $item) {
            return $this->licilicitemloteRepository->delete($item->l21_codigo);
        }
    }

}
