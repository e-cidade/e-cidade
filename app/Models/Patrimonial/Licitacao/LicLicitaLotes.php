<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class LicLicitaLotes extends LegacyModel
{
    public $timestamps = false;

    protected $table = 'licitacao.liclicitalotes';

    protected $primaryKey = 'l24_codigo';

    public $incrementing = false;

    protected string $sequenceName = 'liclicitalotes_l24_codigo_seq';

    protected $fillable = [
        'l24_codigo',
        'l24_pcdesc',
        'l24_codliclicita',
    ];

}
