<?php

namespace App\Services\Tributario\ISSQN;

use App\Models\ISSQN\IssbaseLog;
use BusinessException;

class CreateIssbaseLogService
{
    private IssbaseLog $issbaseLog;

    /**
     * @param IssbaseLog $issbaseLog
     */
    public function __construct(IssbaseLog $issbaseLog)
    {
        $this->issbaseLog = $issbaseLog;
    }

    /**
     * @param int $inscricao
     * @param IssbaseLog::ORIGEM_AUTOMATICO|IssbaseLog::ORIGEM_MANUAL|IssbaseLog::ORIGEM_REDESIM|int $origem
     * @param int $tipo
     * @param string $observacao
     * @return IssbaseLog
     * @throws BusinessException
     */
    public function execute(int $inscricao, int $origem, int $tipo, string $observacao): IssbaseLog
    {
        $saved = $this->issbaseLog->create(
            [
                'q102_inscr' => $inscricao,
                'q102_issbaselogtipo' => $tipo,
                'q102_data' => date('Y-m-d'),
                'q102_hora' => date('H:i'),
                'q102_obs' => $observacao,
                'q102_origem' => $origem,
            ]
        );

        if (!$saved) {
            throw new BusinessException('Erro ao salvar log na Issbaselog.');
        }

        return $saved;
    }
}
