<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class PccfLicitaPar extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.pccflicitapar';

    protected $primaryKey = 'l25_codigo';

    public $incrementing = false;

    protected string $sequenceName = '';

    protected $fillable = [
        'l25_codigo',
        'l25_codcflicita',
        'l25_anousu',
        'l25_numero',
    ];

}
