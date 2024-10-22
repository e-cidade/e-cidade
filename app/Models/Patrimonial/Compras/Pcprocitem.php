<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Pcprocitem extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'compras.pcprocitem';

    protected $primaryKey = 'pc81_codprocitem';

    public $incrementing = false;

    protected string $sequenceName = 'pcprocitem_pc81_codprocitem_seq';

    protected $fillable = [
        'pc81_codprocitem',
        'pc81_codproc',
        'pc81_solicitem'
    ];
}
