<?php

namespace App\Services\Tributario\ISSQN;

use App\Models\ISSQN\IssbaseParalisacao;
use BusinessException;
use DateTime;
use Exception;

class CompanySuspensionService
{
    private IssbaseParalisacao $issbaseParalisacao;

    public function __construct(IssbaseParalisacao $issbaseParalisacao)
    {
        $this->issbaseParalisacao = $issbaseParalisacao;
    }

    /**
     * @param int $inscricao
     * @param DateTime $date
     * @param bool $shouldRevert
     * @param int $motivoParalizacao
     * @param string $observacao
     * @throws BusinessException
     */
    public function execute(
        int      $inscricao,
        DateTime $date,
        int      $motivoParalizacao = IssbaseParalisacao::MOTIVO_PARALIZACAO_NAO_LOCALIZACA,
        string   $observacao = '',
        bool     $shouldRevert = false
    ): void
    {
        try {

            if ($shouldRevert) {
                $issbaseParalisacao = $this->issbaseParalisacao->activeByInscricao($inscricao)->first();
                $issbaseParalisacao->update(['q140_datafim' => $date->format('Y-m-d')]);
                return;
            }

            $issbaseParalisacao = $this->issbaseParalisacao->newQuery()->create(
                [
                    'q140_issbase' => $inscricao,
                    'q140_issmotivoparalisacao' => $motivoParalizacao,
                    'q140_datainicio' => $date->format('Y-m-d'),
                    'q140_observacao' => $observacao,
                ]
            );

            if (!$issbaseParalisacao) {
                throw new BusinessException("Não foi possível paralizar a inscrição {$inscricao}.");
            }

        } catch (Exception $exception) {
            throw new BusinessException($exception->getMessage());
        }
    }
}
