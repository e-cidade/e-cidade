<?php

namespace repositories\protocolo;

class CgmSituacaoCadastralRepositoryLegacy
{
    CONST REGULAR = 0;
    CONST SUSPENSA = 2;
    CONST TITULAR_FALECIDO = 3;
    CONST PENDENTE_REGULARIZACAO = 4;
    CONST CANCELADA_MULTIPLICIDADE = 5;
    CONST INAPTA = 6;
    CONST ATIVA_NAO_REGULAR = 7;
    CONST NULA = 8;
    CONST CANCELADA_OFICIO = 9;
    CONST BAIXADA = 10;

    public static function toArray(): array
    {
        return [
            SELF::REGULAR => 'Regular',
            SELF::SUSPENSA => 'Suspensa',
            SELF::TITULAR_FALECIDO => 'Titular Falecido',
            SELF::PENDENTE_REGULARIZACAO => 'Pendente de Regularização',
            SELF::CANCELADA_MULTIPLICIDADE => 'Cancelada por Multiplicidade',
            SELF::INAPTA => 'Inapta',
            SELF::ATIVA_NAO_REGULAR => 'Ativa não Regular',
            SELF::NULA => 'Nula',
            SELF::CANCELADA_OFICIO => 'Cancelada de Ofício',
            SELF::BAIXADA => 'Baixada'
        ];
    }
}