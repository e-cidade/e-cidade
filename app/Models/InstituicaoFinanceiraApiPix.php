<?php

namespace App\Models;

class InstituicaoFinanceiraApiPix extends LegacyModel
{
    public const BANCO_DO_BRASIL = ConfiguracaoPixBancoDoBrasil::CODIGO;
    public const INSTITUICOES_FINANCEIRAS_OPTION = [
        self::BANCO_DO_BRASIL => ConfiguracaoPixBancoDoBrasil::OPTION_LABEL
    ];
    /**
     * @var string
     */
    protected $table = 'arrecadacao.instituicoes_financeiras_api_pix';

    /**
     * @var string
     */
    protected $primaryKey = 'k175_sequencial';

    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'k175_nome'
    ];
}
