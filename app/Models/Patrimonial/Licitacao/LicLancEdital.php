<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class LicLancEdital extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'public.liclancedital';

    protected $primaryKey = 'l47_sequencial';

    public $incrementing = false;

    protected $fillable = [
        'l47_sequencial',
        'l47_linkpub',
        'l47_origemrecurso',
        'l47_descrecurso',
        'l47_dataenvio',
        'l47_liclicita',
        'l47_dataenviosicom',
        'l47_email',
    ];

}
