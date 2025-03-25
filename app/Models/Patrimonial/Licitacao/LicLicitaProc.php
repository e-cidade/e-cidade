<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class LicLicitaProc extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.liclicitaproc';

    protected $primaryKey = 'l34_sequencial';

    public $incrementing = false;

    protected string $sequenceName = 'liclicitaproc_l34_sequencial_seq';

    protected $fillable = [
        'l34_sequencial',
        'l34_protprocesso',
        'l34_liclicita',
    ];

}
