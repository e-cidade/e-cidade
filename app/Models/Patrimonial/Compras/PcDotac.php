<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class PcDotac extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'compras.pcdotac';

    protected $primaryKey = 'pc13_sequencial';

    protected string $sequenceName = 'pcdotac_pc13_sequencial_seq';

    public $incrementing = false;

    protected $fillable = [
        'pc13_anousu',
        'pc13_coddot',
        'pc13_codigo',
        'pc13_depto',
        'pc13_quant',
        'pc13_valor',
        'pc13_codele',
        'pc13_sequencial',
    ];

}
