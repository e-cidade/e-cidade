<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Remessasicom extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.remessasicom';

    public $incrementing = false;

    protected $primaryKey = 'l227_sequencial';

    protected $fillable = [
        'l227_licitacao',
        'l227_adesao',
        'l227_arquivo',
        'l227_remessa',
        'l227_dataenvio',
        'l227_usuario',
    ];
}
