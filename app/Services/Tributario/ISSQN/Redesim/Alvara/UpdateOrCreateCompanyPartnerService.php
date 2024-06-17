<?php

namespace App\Services\Tributario\ISSQN\Redesim\Alvara;

use App\Models\Cgm;
use App\Models\Issbase;
use App\Models\ISSQN\IssbaseLog;
use App\Models\ISSQN\IssbaseLogTipo;
use App\Models\Socio;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\CompanyDTO;
use App\Services\Tributario\ISSQN\CreateIssbaseLogService;
use BusinessException;
use LogicException;

class UpdateOrCreateCompanyPartnerService
{
    private Cgm $cgm;
    private Socio $socio;
    private CreateIssbaseLogService $createIssbaseLogService;

    public function __construct(Cgm $cgm, Socio $socio, CreateIssbaseLogService $createIssbaseLogService)
    {
        $this->cgm = $cgm;
        $this->socio = $socio;
        $this->createIssbaseLogService = $createIssbaseLogService;
    }

    /**
     * @param CompanyDTO $data
     * @param Cgm $cgmCompany
     * @param Issbase $issbase
     * @return void
     * @throws BusinessException
     */
    public function execute(CompanyDTO $data, Cgm $cgmCompany, Issbase $issbase): void
    {
        foreach ($data->socios as $socio) {

            $obs = "Inclusão socio {$socio->cpf}.";

            $cgmSocio = $this->cgm->where('z01_cgccpf', $socio->cpf)->get()->first();

            if (!$cgmSocio || !$cgmSocio->exists) {
                throw new LogicException('CGM não cadastrado para o sócio' . $socio->cpf);
            }

            $socio = $this->socio->updateOrCreate(
                ['q95_numcgm' => $cgmSocio->z01_numcgm, 'q95_cgmpri' => $cgmCompany->z01_numcgm]
                ,
                [
                    'q95_cgmpri' => $cgmCompany->z01_numcgm,
                    'q95_numcgm' => $cgmSocio->z01_numcgm,
                    'q95_perc' => $socio->participacao,
                    'q95_tipo' => $socio->representanteLegal ? Socio::TYPE_RESPONSAVEL : Socio::TYPE_SOCIO,
                ]);

            if (!$socio) {
                throw new LogicException('Sócio não cadastrado para a empresa' . $data->cpfCnpj);
            }

            if (!empty($socio->fim)) {
                $obs = "Exclusao socio {$socio->cpf}.";
                $this->socio->newQuery()
                    ->where('q95_numcgm', $cgmSocio->z01_numcgm)
                    ->where('q95_cgmpri', $cgmCompany->z01_numcgm)
                    ->delete();
            }

            $this->createIssbaseLogService->execute(
                $issbase->q02_inscr,
                IssbaseLog::ORIGEM_REDESIM,
                IssbaseLogTipo::INGRESSO_RETIRADA_SOCIO,
                $obs
            );
        }
    }
}
