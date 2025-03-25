<?php

namespace App\Services\Patrimonial\Licitacao;
use App\Models\Patrimonial\Licitacao\Licproposta;
use App\Repositories\Patrimonial\Licitacao\LicpropostaRepository;
use Illuminate\Support\Facades\DB;

class LicpropostaService
{
    private $licpropostaRepository;

    public function __construct()
    {
        $this->licpropostaRepository = new LicpropostaRepository();
    }

    public function salvarItensLicproposta ($dados): Licproposta
    {
            $alicproposta = [];
            $alicproposta['l224_codigo']   = $dados->l224_codigo;
            $alicproposta['l224_propitem'] = $dados->l224_propitem;
            $alicproposta['l224_forne'] = $dados->l224_forne;
            $alicproposta['l224_quant']    = $dados->l224_quant;
            $alicproposta['l224_vlrun']    = $dados->l224_vlrun;
            $alicproposta['l224_valor']    = $dados->l224_valor;
            $alicproposta['l224_porcent']  = $dados->l224_porcent;
            $alicproposta['l224_marca']    = $dados->l224_marca;
            return $this->licpropostaRepository->insert($alicproposta);
    }
    public function atualizarProposta($dados):bool
    {
        $alicproposta = [];
        $alicproposta['l224_sequencial'] = $dados->l224_sequencial;
        $alicproposta['l224_codigo']   = $dados->l224_codigo;
        $alicproposta['l224_propitem'] = $dados->l224_propitem;
        $alicproposta['l224_quant']    = $dados->l224_quant;
        $alicproposta['l224_vlrun']    = $dados->l224_vlrun;
        $alicproposta['l224_valor']    = $dados->l224_valor;
        $alicproposta['l224_porcent']  = $dados->l224_porcent;
        $alicproposta['l224_marca']    = $dados->l224_marca;
        return $this->licpropostaRepository->update($alicproposta);

    }

    public function deletaProposta($l224_codigo)
    {
        return $this->licpropostaRepository->delete($l224_codigo);
    }

    public function getProposta($l223_codigo,$l223_fornecedor)
    {
        return $this->licpropostaRepository->getProposta($l223_codigo,$l223_fornecedor);
    }

    public function getSequencial($l224_codigo,$l224_propitem)
    {
        $rsResposta = $this->licpropostaRepository->getSequencial($l224_codigo,$l224_propitem);
        foreach ($rsResposta as $resposta) {
            return $resposta->l224_sequencial;
        }
    }
    public function getCriterio($l20_codigo)
    {
        return $this->licpropostaRepository->getCriterio($l20_codigo);
    }
    public function getLote($l20_codigo)
    {
        return $this->licpropostaRepository->getLote($l20_codigo);
    }
}
