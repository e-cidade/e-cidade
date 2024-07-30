<?php

namespace App\Services\Tributario\ISSQN\Redesim\Alvara;

use App\Models\ISSQN\Ativprinc;
use App\Models\ISSQN\Cnae;
use App\Models\ISSQN\InscricaoRedesim;
use App\Models\ISSQN\IssbaseLog;
use App\Models\ISSQN\IssbaseLogTipo;
use App\Models\ISSQN\Tabativ;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\CompanyDTO;
use App\Services\Tributario\ISSQN\CreateIssbaseLogService;
use BusinessException;

class UpdateOrCreateCompanyActivityService
{
    private Cnae $cnae;
    private Tabativ $tabativ;
    private Ativprinc $ativprinc;
    private InscricaoRedesim $inscricaoRedesim;
    private CreateIssbaseLogService $createIssbaseLogService;

    public function __construct(
        Cnae $cnae,
        InscricaoRedesim $inscricaoRedesim,
        Tabativ $tabativ,
        Ativprinc $ativprinc,
        CreateIssbaseLogService $createIssbaseLogService
    ) {
        $this->cnae = $cnae;
        $this->tabativ = $tabativ;
        $this->ativprinc = $ativprinc;
        $this->inscricaoRedesim = $inscricaoRedesim;
        $this->createIssbaseLogService = $createIssbaseLogService;
    }

    /**
     * @param CompanyDTO $data
     * @return void
     * @throws BusinessException
     */
    public function execute(CompanyDTO $data): void
    {
        foreach ($data->atividades as $key => $atividade) {
            $sequence = $key + 1;
            $estrutural = $atividade->atividade->getCnaeEstruturalEcidade();
            $cnae = $this->cnae->newQuery()
                ->where('q71_estrutural', 'like', "%{$estrutural}")
                ->first();

            if (empty($cnae)) {
                throw new BusinessException('CNAE não cadastrado no e-Cidade: ' . $estrutural);
            }

            $inscricaoRedesim = $this->inscricaoRedesim->newQuery()->where('q179_inscricaoredesim', $data->inscricaoMunicipal)->first();

            if (empty($inscricaoRedesim)) {
                throw new BusinessException('Empresa não cadastrada no e-Cidade: ' . $data->cpfCnpj);
            }

            $ativid = $cnae->cnaeAnalitica->atividCnae->first()->ativid;

            if (empty($ativid)) {
                throw new BusinessException('Não existe atividade para o CNAE ' . $estrutural);
            }

            /**
             * verificar se existe $ativid->q03_ativ para $inscricaoRedesim->issbase->q02_inscr
             * se nao existe INCLUSAO_DE_ATIVIDADES
             */

            $hasActivity = $this->tabativ->inscricaoAtividade($inscricaoRedesim->issbase->q02_inscr, $ativid->q03_ativ)->get();

            if(!$hasActivity) {
                $this->createIssbaseLogService->execute(
                    $inscricaoRedesim->issbase->q02_inscr,
                    IssbaseLog::ORIGEM_REDESIM,
                    IssbaseLogTipo::INCLUSAO_DE_ATIVIDADES,
                    "INCLUSÃO DA ATIVIDADE {$ativid->q03_ativ} CNAE {$estrutural} REALIZADA PELO REDESIM."
                );
            }

            $this->tabativ->newQuery()->updateOrCreate(
                ['q07_inscr' => $inscricaoRedesim->issbase->q02_inscr, 'q07_seq' => $sequence],
                [
                    'q07_ativ' => $ativid->q03_ativ,
                    'q07_datain' => $atividade->atividade->inclusao->format('Y-m-d'),
                    'q07_datafi' => $data->getDateEncerramentoEcidade(),
                ],
            );

            $inscricaoEcidade = $inscricaoRedesim->issbase->q02_inscr;

            if($inscricaoEcidade === 0) {
                throw new BusinessException('erro');
            }

            if ($atividade->atividadePrincipal) {
                $this->ativprinc->newQuery()
                    ->where('q88_inscr', $inscricaoEcidade)
                    ->delete();

                $this->ativprinc->newQuery()->insert(
                    [
                        'q88_inscr' => $inscricaoEcidade,
                        'q88_seq' => $sequence
                    ]
                );
            }
        }
    }
}
