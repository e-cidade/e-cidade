<?php

namespace App\Models\ISSQN;

use App\Models\LegacyModel;

class IssMotivoBaixa extends LegacyModel
{
    public const MOTIVO_BAIXA_DESENQUADRAMENTO = 1;
    public const MOTIVO_BAIXA_REDESIM  = 99;

    public $timestamps = false;

    protected $table = 'issqn.issmotivobaixa';

    protected $primaryKey = 'q42_sequencial';

    protected $fillable = [
        'q42_sequencial',
        'q42_descr',
    ];
}
