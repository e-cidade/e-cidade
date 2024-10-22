<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class PcCatalogo extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'compras.pccatalogo';

    protected $primaryKey = 'mpc04_codigo';

    public $incrementing = false;

    protected string $sequenceName = 'pccatalogo_mpc04_codigo_seq';

    protected $fillable = [
        'mpc04_codigo',
        'mpc04_pcdesc'
    ];

}
