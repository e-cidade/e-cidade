<?php

namespace App\Models\Financeiro\Contabilidade;

use App\Traits\LegacyAccount;
use App\Models\LegacyModel;


class ConPlanoReduz extends LegacyModel
{
    use LegacyAccount;

    protected $table = 'conplanoreduz';

    protected $primaryKey = 'c61_reduz, c61_anousu';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'c61_codcon',
        'c61_anousu',
        'c61_reduz',
        'c61_instit',
        'c61_codigo',
        'c61_contrapartida',
        'c61_codtce',
    
    ];

}
