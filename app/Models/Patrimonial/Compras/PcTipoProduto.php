<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class PcTipoProduto extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'compras.pctipoproduto';

    protected $primaryKey = 'mpc05_codigo';

    public $incrementing = false;

    protected string $sequenceName = 'pctipoproduto_mpc05_codigo_seq';

    protected $fillable = [
        'mpc05_codigo',
        'mpc05_pcdesc'
    ];

}
