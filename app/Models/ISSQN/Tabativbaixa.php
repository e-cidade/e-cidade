<?php

namespace App\Models\ISSQN;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Tabativbaixa extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;
    public $incrementing = false;

    protected $table = 'issqn.tabativbaixa';

    protected $primaryKey = 'q11_inscr, q11_seq';

    protected $fillable = [
        'q11_inscr',
        'q11_seq',
        'q11_processo',
        'q11_oficio',
        'q11_obs',
        'q11_login',
        'q11_data',
        'q11_hora',
        'q11_numero',
    ];
}
