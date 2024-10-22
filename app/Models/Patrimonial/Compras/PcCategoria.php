<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class PcCategoria extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'compras.pccategoria';

    protected $primaryKey = 'mpc03_codigo';

    public $incrementing = false;

    protected string $sequenceName = 'pccategoria_mpc03_codigo_seq';

    protected $fillable = [
        'mpc03_codigo',
        'mpc03_pcdesc'
    ];

}
