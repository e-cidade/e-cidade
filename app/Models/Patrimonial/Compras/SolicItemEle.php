<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class SolicItemEle extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'compras.solicitemele';

    protected $primaryKey = 'pc18_codele';

    public $incrementing = false;

    protected $fillable = [
        'pc18_solicitem',
        'pc18_codele',
    ];
}
