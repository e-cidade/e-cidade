<?php

namespace App\Services\Tributario\ISSQN\Redesim\Alvara;

use App\Models\Cgm;
use App\Models\Issbase;
use App\Models\ISSQN\IssbaseLog;
use App\Models\ISSQN\IssbaseLogTipo;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\CompanyDTO;
use App\Services\Tributario\ISSQN\CreateIssbaseLogService;

class UpdateOrCreateAlvaraService
{
    private Issbase $issbase;
    private CreateIssbaseLogService $createIssbaseLogService;

    /**
     * @param Issbase $issbase
     * @param CreateIssbaseLogService $createIssbaseLogService
     */
    public function __construct(Issbase $issbase, CreateIssbaseLogService $createIssbaseLogService)
    {
        $this->issbase = $issbase;
        $this->createIssbaseLogService = $createIssbaseLogService;
    }

    /**
     * @param CompanyDTO $data
     * @param Cgm $cgmCompany
     * @return Issbase
     */
    public function execute(CompanyDTO $data, Cgm $cgmCompany): Issbase
    {
        $actionType = IssbaseLogTipo::INCLUSAO_EMPRESA;
        $attributes = [
            'q02_numcgm' => $cgmCompany->z01_numcgm,
            'q02_regjuc' => $data->inscricaoMunicipal,
            'q02_inscmu' => $data->inscricaoMunicipal,
            'q02_dtinic' => $data->getDataInicioEcidade(),
            'q02_capit' => $data->capitalSocial,
            'q02_cep' => $data->endereco->cep,
            'q02_dtjunta' => $data->getDataInicioEcidade(),
            'q02_ultalt' => date('Y-m-d'),
            'q02_dtalt' => date('Y-m-d'),
        ];

        if ($data->situacao === CompanyDTO::STATUS_BAIXADA) {
            $attributes['q02_dtbaix'] = $data->dataDoAcontecimento->format('Y-m-d');
        }

        $issbase = $this->issbase->cpfCnpj($data->cpfCnpj)->get()->first();

        if (!empty($issbase)) {
            $actionType = IssbaseLogTipo::ALTERACAO_EMPRESA;
            if (($issbase->cgm->z01_nome !== $data->razaoSocial) || ($issbase->cgm->z01_nomefanta !== $data->nomeFantasia)) {
                $actionType = IssbaseLogTipo::ALTERACAO_RAZAO_SOCIAL_NOME_SOCIAL;
            }

            $issbase->update($attributes);
            $this->performIssbaseLog($issbase->q02_inscr, $actionType, 'ALTERAÇÃO REALIZADA PELO REDESIM.');

            return $issbase;
        }
        $attributes['q02_dtcada'] = date('Y-m-d');
        $newIssbase = $this->issbase->create($attributes);

        $this->performIssbaseLog($newIssbase->q02_inscr, $actionType, 'INCLUSÃO REALIZADA PELO REDESIM.');

        return $newIssbase;
    }


    private function performIssbaseLog(int $inscricao, int $tipo, string $observacao): void
    {
        $this->createIssbaseLogService->execute
        (
            $inscricao,
            IssbaseLog::ORIGEM_REDESIM,
            $tipo,
            $observacao
        );
    }
}
