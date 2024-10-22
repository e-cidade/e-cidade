<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Pcorcamitem extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'compras.pcorcamitem';

    protected $primaryKey = 'pc22_orcamitem';

    protected string $sequenceName = 'pcorcamitem_pc22_orcamitem_seq';

    public $incrementing = false;

    protected $fillable = [
        'pc22_orcamitem',
        'pc22_codorc'
    ];
}
