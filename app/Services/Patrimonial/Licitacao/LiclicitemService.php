<?php

namespace App\Services\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\Liclicitem;
use App\Repositories\Patrimonial\Compras\PcprocitemRepository;
use App\Repositories\Patrimonial\Licitacao\LicilicitemRepository;
use App\Repositories\Patrimonial\Licitacao\LiclicitemLoteRepository;
use App\Repositories\Patrimonial\Licitacao\PcorcamitemlicRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class LiclicitemService
{
    private $licilicitemRepository;
    private $pcorcamitemlicRepository;
    private $pcprocitemRepository;

    public function __construct()
    {
        $this->licilicitemRepository = new LicilicitemRepository();
        $this->pcorcamitemlicRepository = new PcorcamitemlicRepository();
        $this->pcprocitemRepository = new PcprocitemRepository();
    }

    public function salvarItensLicitacao ($dados, $isOrdemPc11Seq = false): ?Liclicitem
    {
        $this->validaExistenciadeFornecedoresnaLicitacao($dados->l20_codigo);
        $aliclicitem = [];
        $aliclicitem['l21_codliclicita'] = $dados->l20_codigo;
        $aliclicitem['l21_codpcprocitem'] = $dados->pc81_codprocitem;
        $aliclicitem['l21_situacao'] = 0;
        $aliclicitem['l21_ordem'] = (!empty($dados->pc11_seq) && $isOrdemPc11Seq) ? $dados->pc11_seq : $this->getMaxOrdem($dados->l20_codigo);
        $aliclicitem['l21_reservado'] = $dados->l21_reservado == "1" ? 't' : 'f';
        $aliclicitem['l21_sigilo'] = $dados->l21_sigilo;

        return $this->licilicitemRepository->insert($aliclicitem);
    }

    public function excluirItensLicitacao($dados)
    {
        $itens = $this->pcprocitemRepository->getItensProcOnLiclicitem($dados->processo,$dados->l20_codigo);
        $this->validaExistenciadeFornecedoresnaLicitacao($dados->l20_codigo);
        foreach ($itens as $item) {
            $this->licilicitemRepository->delete($item->l21_codigo);
        }
    }
    public function getMaxOrdem($l20_codigo)
    {
        $ordem = $this->licilicitemRepository->getOrdemItens($l20_codigo);
        return $ordem[0]->l21_ordem;
    }

    public function validaExistenciadeFornecedoresnaLicitacao($l20_codigo)
    {
        $rsFornecedores = $this->pcorcamitemlicRepository->getFornecedoresLicitacao($l20_codigo);

        if($rsFornecedores){
            throw new Exception('Erro ! Existe fornecedor lançado para a licitação.');
        }
    }

    public function excluirItem(int $l21_codigo){
        return $this->licilicitemRepository->delete($l21_codigo);
    }

    public function getItensLotes(int $l20_codigo, int $limit = 15, int $offset = 0, ?bool $isPaginate = true){
        return $this->licilicitemRepository->getItensLotes($l20_codigo, $limit, $offset, $isPaginate);
    }

    public function getAll(int $l20_codigo){
        return $this->licilicitemRepository->getAll($l20_codigo);
    }

}
