<?php

namespace App\Services\Tributario\ISSQN;

use App\Models\Issbase;
use App\Models\ISSQN\IssbaseLog;
use App\Models\ISSQN\IssbaseLogTipo;
use App\Models\ISSQN\Tabativ;
use App\Models\ISSQN\Tabativbaixa;
use BusinessException;
use UUID;

class CompanyShutdownService
{
    private Tabativbaixa $tabativbaixa;
    private CreateIssbaseLogService $createIssbaseLogService;

    public function __construct(
        Tabativbaixa $tabativbaixa,
        CreateIssbaseLogService $createIssbaseLogService
    )
    {
        $this->tabativbaixa = $tabativbaixa;
        $this->createIssbaseLogService = $createIssbaseLogService;
    }

    /**
     * @param Issbase $issbase
     * @param string $observacao
     * @return void
     * @throws BusinessException
     */
    public function execute(Issbase $issbase, string $observacao = ''): void
    {
        /**
         * @var Tabativ[] $atividades
         */
        $atividades = $issbase->tabativ()->get();
        foreach ($atividades as $atividade) {
            $this->tabativbaixa->newQuery()->insert(
                [
                    'q11_inscr' => $issbase->q02_inscr,
                    'q11_seq' => $atividade->q07_seq,
                    'q11_oficio' => true,
                    'q11_obs' => $observacao,
                    'q11_login' => 1,
                    'q11_data' => $issbase->q02_dtbaix,
                    'q11_numero' => UUID::v4()
                ]
            );
            $atividade->q07_databx = $issbase->q02_dtbaix;
            $atividade->save();
        }

        $this->createIssbaseLogService->execute(
            $issbase->q02_inscr,
            IssbaseLog::ORIGEM_REDESIM,
            IssbaseLogTipo::BAIXA_ATIVIDADES_OFICIO,
            'BAIXA REALIZADA PELO REDESIM'
        );
    }
}
