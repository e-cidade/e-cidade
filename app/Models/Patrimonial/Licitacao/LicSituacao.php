<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class LicSituacao extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.licsituacao';

    protected $primaryKey = 'l08_sequencial';

    public $incrementing = false;

    protected $fillable = [
        'l08_sequencial',
        'l08_descr',
        'l08_altera',
    ];

}
