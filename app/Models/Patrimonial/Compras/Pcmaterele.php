<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;

class Pcmaterele extends LegacyModel
{

    protected $table = 'compras.pcmaterele';

    protected $primaryKey = ' pc07_codmater,pc07_codele';

    public $incrementing = false;

    protected $fillable = [
        'pc07_codmater',
        'pc07_codele'
    ];

    public $timestamps = false;
}
