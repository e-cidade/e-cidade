<?php

namespace App\Services\Patrimonial\Empenho;

use App\Repositories\Patrimonial\EmpAnexoRepository;
use Illuminate\Database\Capsule\Manager as DB;
use Exception;

class EmpAnexoService
{
    /**
     * @var EmpAnexoRepository
     */
    private $empAnexoRepository;

    public function __construct()
    {
        $this->empAnexoRepository = new EmpAnexoRepository();
    }

    /**
     *
     * @param array $aDadosEmpAnexo - dados do Anexo
     * @return bool
     */

    public function create($aDadosEmpAnexo)
    {
        $result = $this->empAnexoRepository->create($aDadosEmpAnexo);

        return $result;
    }

    /**
     * Atualiza informações do Anexo
     *
     * @param int $iSequencialEmpAnexo - Sequencial do Anexo
     * @param array $aDadosEmpAnexo - Dados do Anexo 
     * @return bool
     */

    public function update($iSequencialEmpAnexo, $aDadosEmpAnexo)
    {
        $result = $this->empAnexoRepository->update($iSequencialEmpAnexo, $aDadosEmpAnexo);

        return $result;
    }

    /**
     * Deleta o Anexo informado caso o mesmo já tenha sido excluido no PNCP
     *
     * @param int $iSequencialEmpAnexo - Sequencial do Anexo
     * @return bool
     */

    public function delete($iSequencialEmpAnexo)
    {
        $aAnexo = $this->empAnexoRepository->getAnexo($iSequencialEmpAnexo);
        if($aAnexo['e100_sequencialpncp'] != null){
            throw new Exception("Anexo já enviado ao PNCP, para excluí-lo é necessário que seja primeiro EXCLUÍDO no PNCP.");
        }

        $result = $this->empAnexoRepository->delete($iSequencialEmpAnexo);

        return $result;
    }

    /**
     * Deleta os Anexos informados caso tenham sido excluidos no PNCP
     *
     * @param array $aSequencialEmpAnexo - Sequenciais dos Anexos
     * @return bool
     */

    public function deleteAll($aSequencialEmpAnexo)
    {
        DB::beginTransaction();

        foreach ($aSequencialEmpAnexo as $iSequencialEmpAnexo) {
            $aAnexo = $this->empAnexoRepository->getAnexo($iSequencialEmpAnexo);
            if($aAnexo['e100_sequencialpncp'] != null){
                DB::rollBack();
                throw new Exception("Anexo $iSequencialEmpAnexo já enviado ao PNCP, para excluí-lo é necessário que seja primeiro EXCLUÍDO no PNCP.");
            }

            $result = $this->empAnexoRepository->delete($iSequencialEmpAnexo);
        }

        DB::commit();

        return $result;
    }

    /**
     * Retorna todos os Anexos do Empenho Informado
     *
     * @param int $iNumeroEmpenho - Número do Empenho
     * @return EmpAnexo
     */

    public function getAnexosEmpenho($iNumeroEmpenho)
    {
        $aAnexos = $this->empAnexoRepository->getAnexosEmpenho($iNumeroEmpenho);

        return $aAnexos;
    }

    /**
     * Retorna o Anexo do sequencial Informado
     *
     * @param int $iSequencialEmpAnexo - Sequencial do Anexo
     * @return EmpAnexo
     */

    public function getAnexo($iSequencialEmpAnexo)
    {
        $empAnexo = $this->empAnexoRepository->getAnexo($iSequencialEmpAnexo);

        return $empAnexo;
    }

    /**
     * Retorna os Anexo dos sequenciais informados
     *
     * @param string $sSequencialEmpAnexo - Sequenciais dos Anexos no formato 'x,x,x'
     * @return ResultSet
     */

    public function getAnexos($sSequencialEmpAnexo)
    {
        $anexoEmpenho = $this->empAnexoRepository->getAnexos($sSequencialEmpAnexo);

        return $anexoEmpenho;
    }
}
