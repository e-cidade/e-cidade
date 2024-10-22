<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Solicitavinculo extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'compras.solicitavinculo';

    protected $primaryKey = 'pc53_sequencial';

    protected string $sequenceName = 'solicitavinculo_pc53_sequencial_seq';

    public $incrementing = false;

    protected $fillable = [
        'pc53_sequencial',
        'pc53_solicitapai',
        'pc53_solicitafilho'
    ];
}
