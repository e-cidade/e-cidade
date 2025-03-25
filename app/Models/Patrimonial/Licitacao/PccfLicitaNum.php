<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class PccfLicitaNum extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.pccflicitanum';

    protected $primaryKey = '';

    public $incrementing = false;

    protected string $sequenceName = '';

    protected $fillable = [
        'l24_instit',
        'l24_anousu',
        'l24_numero',
    ];

}
