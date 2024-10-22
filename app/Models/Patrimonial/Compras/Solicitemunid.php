<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Solicitemunid extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'compras.solicitemunid';

    protected $primaryKey = 'pc17_codigo';

    public $incrementing = false;

    protected $fillable = [
        'pc17_unid',
        'pc17_quant',
        'pc17_codigo'
    ];
}
