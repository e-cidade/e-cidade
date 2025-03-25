<?php

namespace App\Services\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\Liclicitemlote;
use App\Repositories\Patrimonial\Compras\PcprocitemRepository;
use App\Repositories\Patrimonial\Licitacao\LicLicitaLotesRepository;
use App\Repositories\Patrimonial\Licitacao\LiclicitemLoteRepository;
use Illuminate\Support\Facades\DB;

class LiclicitemLoteService
{
    private $licilicitemloteRepository;
    private $pcprocitemRepository;
    private $liclicitalotesRepository;

    public function __construct()
    {
        $this->licilicitemloteRepository = new LiclicitemLoteRepository();
        $this->pcprocitemRepository = new PcprocitemRepository();
        $this->liclicitalotesRepository = new LicLicitaLotesRepository();
    }


    public function salvarItensLicitacaoLote ($dados, $is_automatico = true):LiclicitemLote
    {
        $aliclicitemlote = [];
        if(empty($dados->l04_descricao)){
            if($is_automatico){
                $l04_descricao = 'LOTE_AUTOITEM_'.$dados->pc81_codprocitem;
                $aliclicitemlote['l04_descricao'] = $l04_descricao;
            } else {
                $aliclicitemlote['l04_descricao'] = null;
            }
        } else {
            $aliclicitemlote['l04_descricao'] = $dados->l04_descricao;
        }

        if(!empty($dados->l04_codlilicitalote)){
            $aliclicitemlote['l04_codlilicitalote'] = $dados->l04_codlilicitalote;
        }

        $aliclicitemlote['l04_seq'] = $dados->l04_seq ?? null;

        if(!empty($dados->l04_numerolote)){
            $aliclicitemlote['l04_numerolote'] = $dados->l04_numerolote;
        }

        $aliclicitemlote['l04_liclicitem'] = $dados->l04_liclicitem;
        return $this->licilicitemloteRepository->insert($aliclicitemlote);
    }

    public function salvarItensLicitacaoLoteAutomatico ($dados):LiclicitemLote
    {

            $aliclicitemlote = [];
            if(empty($dados->l04_descricao)){
                $l04_descricao = 'LOTE_AUTOITEM_'.$dados->pc81_codprocitem;
                $aliclicitemlote['l04_descricao'] = $l04_descricao;
            } else {
                $aliclicitemlote['l04_descricao'] = $dados->l04_descricao;
            }
            $aliclicitemlote['l04_liclicitem'] = $dados->l04_liclicitem;

        return $this->licilicitemloteRepository->insert($aliclicitemlote);
    }

    public function excluirItensLote($dados)
    {
        $itens = $this->pcprocitemRepository->getItensProcOnLiclicitem($dados->processo, $dados->l20_codigo);
        foreach ($itens as $item) {
            $this->licilicitemloteRepository->delete($item->l21_codigo);
        }
    }

    public function excluirItemLote(int $l04_codigo){
        $itemLote = $this->licilicitemloteRepository->getItemByCodigo($l04_codigo);
        return $this->licilicitemloteRepository->delete($itemLote);
    }
}
