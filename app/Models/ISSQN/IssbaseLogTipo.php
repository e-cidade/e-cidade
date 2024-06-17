<?php

namespace App\Models\ISSQN;

use App\Models\LegacyModel;

class IssbaseLogTipo extends LegacyModel
{
    public const INCLUSAO_EMPRESA = 1;

    public const ALTERACAO_EMPRESA = 2;

    public const INGRESSO_RETIRADA_SOCIO = 3;

    public const ALTERACAO_CAPITAL_SOCIAL = 4;

    public const INCLUSAO_DE_ATIVIDADES = 5;

    public const BAIXA_ATIVIDADES_PEDIDO = 6;

    public const BAIXA_ATIVIDADES_OFICIO = 7;

    public const ALTERACAO_RAZAO_SOCIAL_NOME_SOCIAL = 8;

    public const INCLUSAO_ESCRITORIO_CONTABIL = 9;

    public const EXCLUSAO_ESCRITORIO_CONTABIL = 10;

    public const ALTERACAO_AREA = 11;

    public $timestamps = false;

    protected $table = 'issqn.issbaselogtipo';

    protected $primaryKey = 'q103_sequencial';
}
