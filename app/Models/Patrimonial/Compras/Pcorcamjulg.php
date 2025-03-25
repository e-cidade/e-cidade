<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Pcorcamjulg extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'compras.pcorcamjulg';

    protected $primaryKey = 'pc24_orcamitem';

    public $incrementing = false;

    protected $fillable = [
        'pc24_orcamitem',
        'pc24_pontuacao',
        'pc24_orcamforne'
    ];
}
