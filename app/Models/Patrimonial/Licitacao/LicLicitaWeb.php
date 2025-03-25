<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class LicLicitaWeb extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.liclicitaweb';

    protected $primaryKey = 'l29_sequencial';

    public $incrementing = false;

    protected $fillable = [
        'l29_sequencial',
        'l29_liclicita',
        'l29_datapublic',
        'l29_contato',
        'l29_email',
        'l29_telefone',
        'l29_obs',
        'l29_liberaedital',
    ];

}
