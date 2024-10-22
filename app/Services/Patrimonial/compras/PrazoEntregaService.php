<?php


namespace App\Services\Patrimonial\compras;
use App\Repositories\Patrimonial\Compras\PrazoEntregaRepository;
use App\Models\Patrimonial\Compras\PrazoEntrega;


class PrazoEntregaService
{
    private $prazoEntregaRepository;

    public function __construct()
    {
        $this->prazoEntregaRepository = new PrazoEntregaRepository();
    }

    public function salvarPrazoEntrega ($dados): PrazoEntrega
    {
        
             $aprazoEntrega = [];
             $aprazoEntrega['pc97_descricao'] = $dados->pc97_descricao;
             $aprazoEntrega['pc97_ativo'] = $dados->pc97_ativo;
             
             return $this->prazoEntregaRepository->insert($aprazoEntrega);
    }

    
    
        public function alterarPrazoEntrega($dados):bool
    {   
        
        $aprazoEntrega = [];
        $aprazoEntrega['pc97_sequencial'] = $dados->sequencial;
        $aprazoEntrega['pc97_descricao'] = $dados->descricao;
        $aprazoEntrega['pc97_ativo']   = $dados->ativo;
        return $this->prazoEntregaRepository->update($aprazoEntrega);

    }

    public function deletaPrazo($pc97_sequencial)
    {
        return $this->prazoEntregaRepository->delete($pc97_sequencial);
    }
    
   

    
}
