<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class LicLicItemAnu extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.liclicitemanu';

    public $incrementing = false;

    protected $fillable = [
        'l07_codigo',
        'l07_usuario',
        'l07_data',
        'l07_hora',
        'l07_motivo',
        'l07_liclicitem',
    ];
}
