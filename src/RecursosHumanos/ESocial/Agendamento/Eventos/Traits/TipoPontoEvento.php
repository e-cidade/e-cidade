<?php

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Eventos\Traits;

use stdClass;
use ECidade\RecursosHumanos\ESocial\Agendamento\Eventos\Traits\TipoPontoConstants;

trait TipoPontoEvento
{

    /**
     * Retorna o tipo de ponto e a respectiva sigla da tabela
     * Para tipo Rescisao retorna o tipo de pagamento de rescisao
     *
     * @param int $ponto
     * @return object
     */
    public function getTipoPonto($ponto)
    {
        $tipoPonto = new stdClass();
        $tipoPonto->sigla          = null;
        $tipoPonto->arquivo        = null;
        $tipoPonto->xtipo = "'x'";
        switch ($ponto) {
            case TipoPontoConstants::PONTO_SALARIO:
                $tipoPonto->sigla          = 'r14_';
                $tipoPonto->arquivo        = 'gerfsal';
                break;

            case TipoPontoConstants::PONTO_COMPLEMENTAR:
                $tipoPonto->sigla          = 'r48_';
                $tipoPonto->arquivo        = 'gerfcom';
                break;

            case TipoPontoConstants::PONTO_13SALARIO:
                $tipoPonto->sigla          = 'r35_';
                $tipoPonto->arquivo        = 'gerfs13';
                break;
            case TipoPontoConstants::PONTO_RESCISAO:
                $tipoPonto->sigla          = 'r20_';
                $tipoPonto->arquivo        = 'gerfres';
                $tipoPonto->xtipo          = ' r20_tpp ';
                break;

            default:
                $tipoPonto->sigla          = 'r14_';
                $tipoPonto->arquivo        = 'gerfsal';
                break;
        }
        return $tipoPonto;
    }
}
