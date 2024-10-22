<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Pcorcamitemproc extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'compras.pcorcamitemproc';

    protected $primaryKey = 'pc31_orcamitem,pc31_pcprocitem';

    public $incrementing = false;

    protected $fillable = [
        'pc31_orcamitem',
        'pc31_pcprocitem'
    ];
}
