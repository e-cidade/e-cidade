<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Pcorcamval extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'compras.pcorcamval';

    protected $primaryKey = 'pc23_orcamforne';

    public $incrementing = false;

    protected $fillable = [
        'pc23_orcamforne',
        'pc23_orcamitem',
        'pc23_valor',
        'pc23_quant',
        'pc23_obs',
        'pc23_vlrun',
        'pc23_validmin',
        'pc23_percentualdesconto',
        'pc23_perctaxadesctabela'
    ];
}
