<?php

namespace App\Services\Tributario\ISSQN;

use App\Models\ISSQN\IssCadSimples;
use App\Models\ISSQN\IssCadSimplesBaixa;
use DateTime;
use InvalidArgumentException;

class AlvaraSimplesNacionalService
{
    private IssCadSimples $issCadSimples;
    private IssCadSimplesBaixa $issCadSimplesBaixa;

    public function __construct(IssCadSimples $issCadSimples, IssCadSimplesBaixa $issCadSimplesBaixa)
    {
        $this->issCadSimples = $issCadSimples;
        $this->issCadSimplesBaixa = $issCadSimplesBaixa;
    }

    /**
     * @param int $inscricao
     * @param DateTime $dataInicial
     * @param int<IssCadSimples::CATEGORIA_ME | IssCadSimples::CATEGORIA_EPP | IssCadSimples::CATEGORIA_EPP> $categoria
     * @param DateTime|null $dataFim
     * @param int<IssMotivoBaixa::MOTIVO_BAIXA_DESENQUADRAMENTO | IssMotivoBaixa::MOTIVO_BAIXA_REDESIM>|null $motivoBaixa
     * @param string|null $observacao
     * @return void
     * @throws InvalidArgumentException
     */
    public function execute(
        int      $inscricao,
        DateTime $dataInicial,
        int      $categoria,
        DateTime $dataFim = null,
        int      $motivoBaixa = null,
        string   $observacao = null
    )
    {
        $this->validate($dataFim, $motivoBaixa);
        $issCadSimples = $this->issCadSimples->updateOrCreate(
            ['q38_inscr' => $inscricao, 'q38_categoria' => $categoria],
            [
                'q38_inscr' => $inscricao,
                'q38_dtinicial' => $dataInicial->format('Y-m-d'),
                'q38_categoria' => $categoria
            ]
        );

        if (!empty($dataFim)) {
            $this->issCadSimplesBaixa->updateOrCreate(
                ['q39_isscadsimples' => $issCadSimples->q38_sequencial],
                [
                    'q39_dtbaixa' => $dataFim->format('Y-m-d'),
                    'q39_issmotivobaixa' => $motivoBaixa,
                    'q39_obs' => $observacao
                ]
            );
        }
    }

    private function validate(DateTime $dataFim = null, int $motivoBaixa = null): void
    {
        if (!empty($dataFim) && empty($motivoBaixa)) {
            throw new InvalidArgumentException('Informe o motivo da baixa.');
        }
    }
}
