<?php

namespace App\Services\Patrimonial\Fornecedores;

use App\Repositories\Patrimonial\Fornecedores\PcForneRepository;
use Illuminate\Database\Capsule\Manager as DB;
use Exception;

class PcForneService
{
    /**
     * @var PcForneRepository
     */
    private $PcForneRepository;

    public function __construct()
    {
        $this->PcForneRepository = new PcForneRepository();
    }

    /**
     *
     * @param array $aDados - dados
     * @return bool
     */

    public function cadastroAutomatico($aDados)
    {
        
        $rsCgm = DB::select("select * from cgm where z01_numcgm = {$aDados["pc60_numcgm"]}")[0];

        if($rsCgm->z01_cgccpf == "00000000000"){
            throw new Exception("Usu�rio: Erro ao cadastrar fornecedor. O CGM {$aDados["pc60_numcgm"]} n�o possui CPF v�lido preenchido.");
        }

        if($rsCgm->z01_cgccpf == ""){
            throw new Exception( "Usu�rio: Erro ao cadastrar fornecedor. O CGM {$aDados["pc60_numcgm"]} n�o possui CPF preenchido.");
        }

        $rsPcForne = $this->PcForneRepository->get($aDados["pc60_numcgm"]);

        if($rsPcForne){
            throw new Exception( "Usu�rio: Erro ao cadastrar fornecedor. J� existe fornecedor cadastrado para o CPF '{$rsCgm->z01_cgccpf}'. CGM cadastrado: '{$rsCgm->z01_numcgm}'. CPF: '{$rsCgm->z01_cgccpf}'");
        }
        
        $result = $this->PcForneRepository->cadastroAutomatico($aDados);
        return $result;
    }

    /**
     *
     * @param int $cgm 
     * @return PcForne
     */

    public function get($cgm)
    {
        $PcForne = $this->PcForneRepository->get($cgm);

        return $PcForne;
    }

}
